
@extends('layouts.customer')

@section('title', 'Riwayat Pemesanan - TIX Bioskop')

@section('content')

<div class="container py-4" style="max-width: 1000px;">

    {{-- Judul halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="text-warning fw-bold mb-1">Riwayat Pemesanan</h3>
            <p class="text-secondary mb-0 small">
                Daftar transaksi pemesanan tiket bioskop kamu.
            </p>
        </div>

        <span class="badge bg-secondary px-3 py-2">
            {{ $bookings->count() }} Transaksi
        </span>
    </div>

    {{-- Daftar booking --}}
    @forelse($bookings as $booking)

        <div class="card booking-card mb-3">

            {{-- Header transaksi --}}
            <div class="card-header booking-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <small class="text-secondary d-block">Kode Booking</small>
                    <span class="text-warning fw-bold">
                        {{ $booking->booking_code }}
                    </span>
                </div>

                <div>
                    @if($booking->status === 'Paid')
                        <span class="badge bg-success">LUNAS</span>
                    @elseif($booking->status === 'Pending')
                        <span class="badge bg-warning text-dark">MENUNGGU PEMBAYARAN</span>
                    @else
                        <span class="badge bg-danger">
                            {{ strtoupper($booking->status) }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Isi transaksi --}}
            <div class="card-body p-4">
                <div class="row align-items-center">

                    <div class="col-md-8 mb-3 mb-md-0">

                        <h5 class="text-white fw-bold mb-2">
                            {{ $booking->schedule->film->title ?? 'Film Tidak Tersedia' }}
                        </h5>

                        <div class="text-secondary small mb-3">
                            <div class="mb-1">
                                <i class="bi bi-building me-2"></i>
                                {{ $booking->schedule->studio->name ?? '-' }}
                            </div>

                            <div>
                                <i class="bi bi-calendar-event me-2"></i>

                                @if($booking->schedule && $booking->schedule->show_time)
                                    {{ \Carbon\Carbon::parse($booking->schedule->show_time)->format('d M Y, H:i') }} WIB
                                @else
                                    Jadwal tidak tersedia
                                @endif
                            </div>
                        </div>

                        <div class="mb-2">
                            <span class="text-secondary small">
                                <i class="bi bi-ticket-perforated me-1"></i>
                                Kursi ({{ $booking->total_tickets }} Tiket)
                            </span>

                            <div class="text-warning fw-semibold">
                                {{ implode(', ', $booking->seats ?? []) ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-secondary small">Total Pembayaran</span>
                            <div class="text-white fw-bold">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </div>
                        </div>

                    </div>

                    {{-- Tombol aksi --}}
                    <div class="col-md-4">

                        @if($booking->status === 'Paid')

                            <a href="{{ route('checkout.show', $booking->booking_code) }}"
                                class="btn btn-success w-100 fw-semibold mb-2">
                                <i class="bi bi-ticket-perforated me-1"></i>
                                Lihat E-Ticket
                            </a>

                        @elseif($booking->status === 'Pending' && $booking->xendit_invoice_url)

                            <a href="{{ $booking->xendit_invoice_url }}"
                                class="btn btn-warning w-100 fw-semibold mb-2">
                                <i class="bi bi-credit-card me-1"></i>
                                Bayar Sekarang
                            </a>

                            <a href="{{ route('checkout.show', $booking->booking_code) }}"
                                class="btn btn-outline-light w-100 btn-sm">
                                Detail Pesanan
                            </a>

                        @else

                            <a href="{{ route('checkout.show', $booking->booking_code) }}"
                                class="btn btn-outline-secondary w-100">
                                Detail Pesanan
                            </a>

                        @endif

                    </div>
                </div>
            </div>

            {{-- Footer transaksi --}}
            <div class="card-footer booking-footer small text-secondary">
                <i class="bi bi-clock me-1"></i>
                Dipesan pada
                {{ $booking->created_at->format('d M Y, H:i') }}
            </div>

        </div>

    @empty

        <div class="empty-history text-center py-5">
            <i class="bi bi-ticket-perforated empty-icon"></i>

            <h5 class="text-white fw-bold mt-3">
                Belum Ada Riwayat Pemesanan
            </h5>

            <p class="text-secondary small mb-4">
                Kamu belum pernah melakukan pemesanan tiket.
            </p>

            <a href="{{ route('films.index') }}" class="btn btn-warning fw-bold px-4">
                <i class="bi bi-film me-1"></i>
                Cari Film Sekarang
            </a>
        </div>

    @endforelse

</div>

<style>
    .booking-card {
        background: #15161c;
        border: 1px solid #34343d;
        border-radius: 12px;
        overflow: hidden;
        color: white;
        transition: border-color 0.2s ease;
    }

    .booking-card:hover {
        border-color: #facc15;
    }

    .booking-header {
        background: #1c1d24;
        border-bottom: 1px solid #34343d;
        padding: 14px 20px;
    }

    .booking-footer {
        background: #1c1d24;
        border-top: 1px solid #34343d;
        padding: 12px 20px;
    }

    .empty-history {
        background: #15161c;
        border: 1px solid #34343d;
        border-radius: 12px;
        padding: 50px 20px;
    }

    .empty-icon {
        font-size: 55px;
        color: #facc15;
    }

    @media (max-width: 576px) {
        .booking-card .card-body {
            padding: 20px !important;
        }
    }
</style>

@endsection
