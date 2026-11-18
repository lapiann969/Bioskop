<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - TIX Bioskop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm">
    <div class="container">
        <a class="navbar-brand text-warning fw-bold fs-4" href="{{ route('customer.dashboard') }}">🎥 TIX Bioskop</a>
        <div class="d-flex gap-2">
            <a href="{{ route('films.index') }}" class="btn btn-outline-warning btn-sm">Daftar Film</a>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-light btn-sm">Beranda</a>
        </div>
    </div>
</nav>

<div class="container my-5" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-warning fw-bold mb-0">Riwayat Pemesanan Tiket</h3>
        <span class="badge bg-secondary fs-6">{{ $bookings->count() }} Transaksi</span>
    </div>

    @forelse($bookings as $booking)
        <div class="card bg-black text-white border-secondary shadow mb-3">
            <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-secondary">Kode Booking:</small>
                    <span class="fw-bold text-warning ms-1">{{ $booking->booking_code }}</span>
                </div>
                <div>
                    @if($booking->status === 'Paid')
                        <span class="badge bg-success">LUNAS</span>
                    @elseif($booking->status === 'Pending')
                        <span class="badge bg-warning text-dark">MENUNGGU PEMBAYARAN</span>
                    @else
                        <span class="badge bg-danger">{{ strtoupper($booking->status) }}</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <h5 class="fw-bold mb-1">{{ $booking->schedule->film->title ?? 'Film Tidak Tersedia' }}</h5>
                        <p class="text-secondary small mb-2">
                            {{ $booking->schedule->studio->name ?? '-' }} • 
                            {{ \Carbon\Carbon::parse($booking->schedule->show_time)->format('d M Y, H:i') }} WIB
                        </p>
                        <p class="mb-1">
                            <span class="text-secondary">Kursi ({{ $booking->total_tickets }} Tiket):</span>
                            <strong class="text-warning">{{ implode(', ', $booking->seats ?? []) }}</strong>
                        </p>
                        <p class="mb-0">
                            <span class="text-secondary">Total Bayar:</span>
                            <strong class="text-white">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                        </p>
                    </div>

                    <div class="col-md-4 text-md-end">
                        @if($booking->status === 'Paid')
                            <a href="{{ route('checkout.show', $booking->booking_code) }}" class="btn btn-success w-100 fw-bold mb-2">
                                Lihat E-Ticket
                            </a>
                        @elseif($booking->status === 'Pending' && $booking->xendit_invoice_url)
                            <a href="{{ $booking->xendit_invoice_url }}" class="btn btn-warning w-100 fw-bold mb-2">
                                Bayar Sekarang →
                            </a>
                            <a href="{{ route('checkout.show', $booking->booking_code) }}" class="btn btn-outline-light btn-sm w-100">
                                Detail Pesanan
                            </a>
                        @else
                            <a href="{{ route('checkout.show', $booking->booking_code) }}" class="btn btn-outline-secondary w-100">
                                Detail Pesanan
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-footer border-secondary text-secondary small">
                Dipesan pada {{ $booking->created_at->format('d M Y, H:i') }}
            </div>
        </div>
    @empty
        <div class="card bg-black text-white border-secondary text-center p-5">
            <h5 class="text-secondary mb-3">Belum ada riwayat pemesanan tiket.</h5>
            <div>
                <a href="{{ route('films.index') }}" class="btn btn-warning fw-bold">Cari Film Sekarang</a>
            </div>
        </div>
    @endforelse
</div>

</body>
</html>
