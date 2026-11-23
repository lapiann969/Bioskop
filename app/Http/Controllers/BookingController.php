<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
    // 3. MEMBUAT BOOKING DAN PAYMENT SESSION
    // ==========================================

    public function store(Request $request)
    {
        // ==========================================
        // 1. VALIDASI DATA PEMESANAN
        // ==========================================

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

        // ==========================================
        // 2. AMBIL KONFIGURASI XENDIT
        // ==========================================

        $secretKey = config('services.xendit.secret_key');

        $origin = config('services.xendit.components_origin');

        if (empty($secretKey) || empty($origin)) {
            Log::error('Konfigurasi Xendit belum lengkap.');

            return back()->with(
                'error',
                'Konfigurasi pembayaran belum tersedia. Hubungi administrator.'
            );
        }

        // ==========================================
        // 3. AMBIL DATA JADWAL DAN FILM
        // ==========================================

        $schedule = Schedule::with('film')
            ->findOrFail($request->schedule_id);

        $selectedSeats = $request->selected_seats;

        // ==========================================
        // 4. CEK KETERSEDIAAN KURSI
        // ==========================================

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

        // ==========================================
        // 5. HITUNG TOTAL PEMBAYARAN
        // ==========================================

        $totalTickets = count($selectedSeats);

        $totalPrice = $totalTickets * ($schedule->price ?? 35000);

        $bookingCode = 'BKG-' . strtoupper(Str::random(6));

        // ==========================================
        // 6. MENYIAPKAN DATA PELANGGAN
        // ==========================================

        $customerName = preg_replace(
            '/[^A-Za-z0-9 ]/',
            '',
            trim((string) Auth::user()->name)
        );

        if ($customerName === '') {
            $customerName = 'Customer';
        }

        // ==========================================
        // 7. MEMBUAT PAYMENT SESSION XENDIT
        // ==========================================

        try {
            $response = Http::withBasicAuth(
                $secretKey,
                ''
            )
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post('https://api.xendit.co/sessions', [

                    'reference_id' => $bookingCode,

                    'session_type' => 'PAY',

                    'mode' => 'COMPONENTS',

                    'amount' => (int) $totalPrice,

                    'currency' => 'IDR',

                    'country' => 'ID',

                    // Identitas dan detail pelanggan.
                    'customer' => [
                        'reference_id' => 'USER' . Auth::id() .
                            str_replace('-', '', $bookingCode),

                        'type' => 'INDIVIDUAL',

                        'email' => Auth::user()->email,

                        'individual_detail' => [
                            'given_names' => $customerName,
                        ],
                    ],

                    // Keterangan transaksi.
                    'description' =>
                    'Tiket ' . $schedule->film->title .
                        ' - Kursi: ' . implode(', ', $selectedSeats),

                    // Domain aplikasi untuk Xendit Components.
                    'components_configuration' => [
                        'origins' => [$origin],
                    ],
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

        // ==========================================
        // 8. PERIKSA RESPONS XENDIT
        // ==========================================

        if (!$response->successful()) {

            Log::error('Gagal membuat Payment Session Xendit', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return back()->with(
                'error',
                'Gagal membuat sesi pembayaran. Silakan coba kembali.'
            );
        }

        $session = $response->json();

        Log::info('Pemeriksaan respons Payment Session Xendit.', [
            'booking_code' => $bookingCode,
            'payment_session_id_tersedia' => !empty($session['payment_session_id']),
            'components_sdk_key_tersedia' => !empty($session['components_sdk_key']),
            'response_fields' => array_keys($session),
        ]);

        // Pastikan ID session dan SDK key tersedia.
        if (
            empty($session['payment_session_id']) ||
            empty($session['components_sdk_key'])
        ) {
            Log::error('Respons Payment Session tidak lengkap.', [
                'response' => $session,
            ]);

            return back()->with(
                'error',
                'Sesi pembayaran tidak lengkap. Silakan coba kembali.'
            );
        }

        // ==========================================
        // 9. SIMPAN DATA BOOKING
        // ==========================================

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => Auth::id(),
            'schedule_id' => $schedule->id,
            'seats' => $selectedSeats,
            'total_tickets' => $totalTickets,
            'total_price' => $totalPrice,
            'status' => 'Pending',
            'xendit_invoice_url' => null,
        ]);

        // ==========================================
        // 10. SIMPAN DATA PAYMENT
        // ==========================================

        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => null,
            'amount' => $totalPrice,
            'status' => 'Pending',
            'payment_url' => null,

            'payment_session_id' =>
            $session['payment_session_id'],

            'payment_request_id' =>
            $session['payment_request_id'] ?? null,
        ]);

        // ==========================================
        // 11. SIMPAN COMPONENTS SDK KEY
        // ==========================================

        // Simpan berdasarkan kode booking agar dapat
        // diambil kembali ketika halaman checkout dibuka ulang.
        session()->put(
            'components_sdk_key_' . $booking->booking_code,
            $session['components_sdk_key']
        );

        Log::info('Xendit SDK Key disimpan ke session.', [
            'booking_code' => $booking->booking_code,
            'sdk_key_tersedia' => session()->has(
                'components_sdk_key_' . $booking->booking_code
            ),
        ]);

        // ==========================================
        // 12. ARAHKAN KE HALAMAN CHECKOUT
        // ==========================================

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
            'payment',
        ])
            ->where('booking_code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Ambil SDK Key berdasarkan kode booking.
        $componentsSdkKey = session(
            'components_sdk_key_' . $booking->booking_code
        );

        Log::info('Pemeriksaan SDK Key halaman checkout.', [
            'booking_code' => $booking->booking_code,
            'sdk_key_tersedia' => !empty($componentsSdkKey),
        ]);

        return view(
            'customer.checkout.show',
            compact('booking', 'componentsSdkKey')
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

        // Status Paid hanya ditetapkan melalui webhook.
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
        // ==========================================
        // 1. VALIDASI CALLBACK TOKEN
        // ==========================================

        $callbackToken = config('services.xendit.callback_token');

        $receivedToken = $request->header('x-callback-token');

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

        // ==========================================
        // 2. WEBHOOK PAYMENT SESSION
        // ==========================================

        $event = $request->input('event');

        if ($event === 'payment_session.completed') {

            $data = $request->input('data', []);

            $referenceId = $data['reference_id'] ?? null;

            $sessionId = $data['payment_session_id'] ?? null;

            $sessionStatus = strtoupper($data['status'] ?? '');

            $sessionType = strtoupper($data['session_type'] ?? '');

            $currency = strtoupper($data['currency'] ?? '');

            $amount = $data['amount'] ?? null;

            $paymentId = $data['payment_request_id']
                ?? $data['latest_payment_request_id']
                ?? null;

            // Validasi data penting.
            if (
                !$referenceId ||
                !$sessionId ||
                $sessionStatus !== 'COMPLETED' ||
                $sessionType !== 'PAY' ||
                $currency !== 'IDR' ||
                !is_numeric($amount)
            ) {
                Log::warning(
                    'Webhook Payment Session: data tidak valid.',
                    [
                        'event' => $event,
                        'data' => $data,
                    ]
                );

                return response()->json([
                    'message' => 'Data Payment Session tidak valid.',
                ], 400);
            }

            try {
                DB::transaction(function () use (
                    $referenceId,
                    $sessionId,
                    $amount,
                    $paymentId
                ) {

                    // Cari booking berdasarkan reference ID.
                    $booking = Booking::where(
                        'booking_code',
                        $referenceId
                    )->lockForUpdate()->first();

                    if (!$booking) {
                        throw new \RuntimeException(
                            'Booking tidak ditemukan.'
                        );
                    }

                    // Ambil payment.
                    $payment = Payment::where(
                        'booking_id',
                        $booking->id
                    )->lockForUpdate()->first();

                    if (!$payment) {
                        throw new \RuntimeException(
                            'Data payment tidak ditemukan.'
                        );
                    }

                    // Cocokkan Payment Session ID.
                    if ($payment->payment_session_id !== $sessionId) {
                        throw new \RuntimeException(
                            'Payment Session ID tidak cocok.'
                        );
                    }

                    // Cocokkan nominal pembayaran.
                    if (
                        (float) $amount !== (float) $booking->total_price ||
                        (float) $amount !== (float) $payment->amount
                    ) {
                        throw new \RuntimeException(
                            'Nominal pembayaran tidak cocok.'
                        );
                    }

                    // Hindari pemrosesan pembayaran berulang.
                    if (
                        $booking->status === 'Paid' &&
                        $payment->status === 'Success'
                    ) {
                        return;
                    }

                    // Perbarui status payment.
                    $payment->update([
                        'status' => 'Success',

                        'payment_request_id' =>
                        $paymentId ?? $payment->payment_request_id,
                    ]);

                    // Perbarui status booking.
                    $booking->update([
                        'status' => 'Paid',
                    ]);

                    Log::info(
                        'Payment Session berhasil dikonfirmasi.',
                        [
                            'booking_code' => $booking->booking_code,
                            'payment_session_id' => $sessionId,
                        ]
                    );
                });
            } catch (\Throwable $e) {

                Log::error(
                    'Gagal memproses webhook Payment Session.',
                    [
                        'reference_id' => $referenceId,
                        'error' => $e->getMessage(),
                    ]
                );

                return response()->json([
                    'message' => 'Webhook gagal diproses.',
                ], 500);
            }

            return response()->json([
                'message' => 'Payment Session berhasil diproses.',
            ], 200);
        }

        // ==========================================
        // 3. WEBHOOK INVOICE LAMA
        // ==========================================

        $externalId = $request->input('external_id');

        $paymentStatus = strtoupper(
            $request->input('status', '')
        );

        if (!$externalId) {
            return response()->json([
                'message' => 'External ID tidak ditemukan.',
            ], 400);
        }

        $booking = Booking::where(
            'booking_code',
            $externalId
        )->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking tidak ditemukan.',
            ], 404);
        }

        if ($paymentStatus === 'PAID') {

            DB::transaction(function () use ($booking) {

                $lockedBooking = Booking::whereKey($booking->id)
                    ->lockForUpdate()
                    ->first();

                $payment = Payment::where(
                    'booking_id',
                    $lockedBooking->id
                )->lockForUpdate()->first();

                $lockedBooking->update([
                    'status' => 'Paid',
                ]);

                if ($payment) {
                    $payment->update([
                        'status' => 'Success',
                    ]);
                }
            });

            Log::info('Pembayaran invoice berhasil.', [
                'booking_code' => $booking->booking_code,
            ]);
        }

        return response()->json([
            'message' => 'Webhook berhasil diproses.',
        ], 200);
    }
}
