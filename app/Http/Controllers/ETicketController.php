<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class ETicketController extends Controller
{
    public function show(Booking $booking)
    {
        // Pastikan tiket milik pengguna yang sedang login.
        abort_unless(
            (int) $booking->user_id === (int) Auth::id(),
            403
        );

        // Hanya booking yang sudah lunas boleh menampilkan tiket.
        abort_unless($booking->status === 'Paid', 404);

        $booking->load([
            'schedule.film',
            'schedule.studio',
            'bookingDetails.seat',
        ]);

        // Buat QR Code dari kode booking.
        $qrCode = new QrCode(
            data: $booking->booking_code,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        // Ubah QR Code menjadi gambar PNG.
        $writer = new PngWriter();
        $qrCodeResult = $writer->write($qrCode);

        $qrCodeDataUri = $qrCodeResult->getDataUri();

        return view('customer.tickets.show', compact(
            'booking',
            'qrCodeDataUri'
        ));
    }
}
