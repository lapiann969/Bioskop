<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;

class BookingController extends Controller
{
    // ==========================================
    // 1. MENAMPILKAN DAFTAR BOOKING
    // ==========================================

    public function index()
    {
        $bookings = Booking::with(['schedule.film', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    // ==========================================
    // 2. MENAMPILKAN DETAIL BOOKING
    // ==========================================

    public function show(Booking $booking)
    {
        abort_unless(
            $booking->user_id === Auth::id(),
            403
        );

        $booking->load([
            'schedule.film',
            'schedule.studio',
            'bookingDetails.seat',
            'payment',
        ]);

        return view('bookings.show', compact('booking'));
    }

    // ==========================================
    // 3. MEMBUAT BOOKING DAN INVOICE XENDIT
    // ==========================================

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'selected_seats' => 'required|array|min:1',
            'selected_seats.*' => 'required|string|distinct',
        ], [
            'selected_seats.required' =>
                'Pilih minimal 1 kursi terlebih dahulu!',

            'selected_seats.min' =>
                'Pilih minimal 1 kursi terlebih dahulu!',
        ]);

        // Ambil API Key Xendit
        $secretKey = config('services.xendit.secret_key');

        if (empty($secretKey)) {
            Log::error('Xendit Secret API Key belum dikonfigurasi.');

            return back()->with(
                'error',
                'Konfigurasi pembayaran belum tersedia. Hubungi administrator.'
            );
        }

        // Ambil jadwal dan film
        $schedule = Schedule::with('film')
            ->findOrFail($request->schedule_id);

        $selectedSeats = $request->selected_seats;

        // Cek kursi yang sudah dipesan
        $existingBookings = Booking::where(
            'schedule_id',
            $schedule->id
        )
            ->whereIn('status', ['Pending', 'Paid'])
            ->get(['seats']);

        $alreadyBooked = $existingBookings->flatMap(
            function ($booking) {
                $seats = $booking->seats;

                if (is_string($seats)) {
                    $seats = json_decode($seats, true) ?? [];
                }

                return is_array($seats) ? $seats : [];
            }
        )->toArray();

        // Pastikan kursi belum dipesan
        if (
            count(array_intersect(
                $selectedSeats,
                $alreadyBooked
            )) > 0
        ) {
            return back()->with(
                'error',
                'Maaf, salah satu kursi yang kamu pilih sudah dipesan orang lain.'
            );
        }

        // Hitung total pembayaran
        $totalTickets = count($selectedSeats);

        $totalPrice = $totalTickets * ($schedule->price ?? 35000);

        // Buat kode booking
        $bookingCode = 'BKG-' . strtoupper(Str::random(6));

        // ==========================================
        // REQUEST INVOICE XENDIT
        // ==========================================

        try {
            $response = Http::withBasicAuth(
                $secretKey,
                ''
            )
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post('https://api.xendit.co/v2/invoices', [

                    'external_id' => $bookingCode,

                    'amount' => (int) $totalPrice,

                    'payer_email' => Auth::user()->email
                        ?? 'customer@tixbioskop.test',

                    'description' =>
                        'Tiket ' . $schedule->film->title .
                        ' - Kursi: ' . implode(', ', $selectedSeats),

                    'currency' => 'IDR',

                    'success_redirect_url' => route(
                        'checkout.success',
                        ['code' => $bookingCode]
                    ),

                    'failure_redirect_url' => route(
                        'checkout.show',
                        ['code' => $bookingCode]
                    ),
                ]);

        } catch (ConnectionException $e) {

            Log::error(
                'Koneksi ke Xendit gagal: ' . $e->getMessage()
            );

            return back()->with(
                'error',
                'Tidak dapat terhubung ke Xendit. Silakan coba kembali.'
            );
        }

        // Periksa respons Xendit
        if (!$response->successful()) {

            Log::error('Gagal membuat invoice Xendit', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return back()->with(
                'error',
                'Gagal membuat tagihan pembayaran. Silakan coba kembali.'
            );
        }

        $invoice = $response->json();

        // Pastikan URL pembayaran tersedia
        if (empty($invoice['invoice_url'])) {

            Log::error(
                'Respons Xendit tidak memiliki invoice_url.',
                ['response' => $invoice]
            );

            return back()->with(
                'error',
                'Invoice berhasil diproses tetapi URL pembayaran tidak ditemukan.'
            );
        }

        // ==========================================
        // SIMPAN DATA BOOKING
        // ==========================================

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => Auth::id(),
            'schedule_id' => $schedule->id,
            'seats' => $selectedSeats,
            'total_tickets' => $totalTickets,
            'total_price' => $totalPrice,
            'status' => 'Pending',
            'xendit_invoice_url' => $invoice['invoice_url'],
        ]);

        // ==========================================
        // SIMPAN DATA PAYMENT
        // ==========================================

        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => null,
            'amount' => $totalPrice,
            'status' => 'Pending',
            'payment_url' => $invoice['invoice_url'],
        ]);

        // Arahkan user ke halaman checkout
        return redirect()->route(
            'checkout.show',
            $booking->booking_code
        );
    }

    // ==========================================
    // 4. MENAMPILKAN HALAMAN CHECKOUT
    // ==========================================

    public function checkout($code)
    {
        $booking = Booking::with([
            'schedule.film',
            'schedule.studio',
        ])
            ->where('booking_code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view(
            'customer.checkout.show',
            compact('booking')
        );
    }

    // ==========================================
    // 5. REDIRECT SETELAH PEMBAYARAN
    // ==========================================

    public function success($code)
    {
        $booking = Booking::where('booking_code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Status Paid hanya ditetapkan melalui webhook Xendit
        $message = $booking->status === 'Paid'
            ? 'Pembayaran berhasil! E-Ticket kamu sudah aktif.'
            : 'Terima kasih! Pembayaran sedang menunggu konfirmasi dari Xendit.';

        return redirect()
            ->route('checkout.show', $booking->booking_code)
            ->with('success', $message);
    }

    // ==========================================
    // 6. WEBHOOK XENDIT
    // ==========================================

    public function webhook(Request $request)
    {
        $callbackToken = config('services.xendit.callback_token');

        $receivedToken = $request->header('x-callback-token');

        // Verifikasi token webhook
        if (
            empty($callbackToken) ||
            empty($receivedToken) ||
            !hash_equals($callbackToken, $receivedToken)
        ) {
            Log::warning('Webhook Xendit: token tidak valid.');

            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // Ambil data dari Xendit
        $externalId = $request->input('external_id');

        $paymentStatus = strtoupper(
            $request->input('status', '')
        );

        if (!$externalId) {
            return response()->json([
                'message' => 'External ID tidak ditemukan.',
            ], 400);
        }

        // Cari booking berdasarkan kode booking
        $booking = Booking::where(
            'booking_code',
            $externalId
        )->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking tidak ditemukan.',
            ], 404);
        }

        // ==========================================
        // KONFIRMASI PEMBAYARAN BERHASIL
        // ==========================================

        if ($paymentStatus === 'PAID') {

            // Perbarui status booking
            $booking->update([
                'status' => 'Paid',
            ]);

            // Perbarui status payment
            Payment::where('booking_id', $booking->id)
                ->update([
                    'status' => 'Success',
                ]);

            Log::info('Pembayaran booking berhasil.', [
                'booking_code' => $booking->booking_code,
            ]);
        }

        return response()->json([
            'message' => 'Webhook berhasil diproses.',
        ], 200);
    }
}
