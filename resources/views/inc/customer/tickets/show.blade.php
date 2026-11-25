@extends('layouts.customer')

@push('styles')
    <style>
        .ticket-container {
            max-width: 700px;
            padding-top: 120px;
            padding-bottom: 50px;
        }

        .ticket-card {
            background: #111;
            color: #fff;
            border: 1px solid #eab308;
            border-radius: 16px;
            overflow: hidden;
        }

        .ticket-header {
            background: #eab308;
            color: #212529;
            padding: 22px;
        }

        .ticket-code {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: 2px;
            overflow-wrap: anywhere;
        }

        .ticket-label {
            color: #adb5bd;
            font-size: .85rem;
        }

        .ticket-value {
            font-weight: 600;
        }

        .ticket-qr {
            background: white;
            display: inline-block;
            padding: 10px;
            border-radius: 12px;
        }

        .ticket-qr img {
            width: 190px;
            height: 190px;
            display: block;
        }

        @media print {
            body {
                background: white !important;
            }

            .ticket-container {
                padding-top: 20px;
            }

            .no-print {
                display: none !important;
            }

            .ticket-card {
                color: black;
                background: white;
                border: 2px solid black;
            }

            .ticket-label {
                color: #555;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container ticket-container">

        <div class="ticket-card shadow-lg">

            <div class="ticket-header text-center">
                <h3 class="fw-bold mb-1">TIX BIOSKOP</h3>
                <span class="fw-semibold">E-TICKET</span>
            </div>

            <div class="p-4">

                <div class="text-center mb-4">
                    <span class="badge bg-success mb-2">PEMBAYARAN LUNAS</span>
                    <div class="ticket-label">KODE BOOKING</div>
                    <div class="ticket-code text-warning">
                        {{ $booking->booking_code }}
                    </div>

                    <div class="mt-3">
                        <div class="ticket-qr">
                            <img src="{{ $qrCodeDataUri }}" alt="QR Code E-Ticket">
                        </div>

                        <p class="small text-secondary mt-2 mb-0">
                            QR Code E-Ticket
                        </p>
                    </div>
                </div>

                <hr class="border-secondary">

                <div class="mb-3">
                    <div class="ticket-label">Judul Film</div>
                    <div class="ticket-value fs-5">
                        {{ $booking->schedule->film->title }}
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="ticket-label">Studio</div>
                        <div class="ticket-value">
                            {{ $booking->schedule->studio->name }}
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="ticket-label">Waktu Tayang</div>
                        <div class="ticket-value">
                            {{ $booking->schedule->show_time->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="ticket-label">Nomor Kursi</div>
                    <div class="ticket-value text-warning fs-5">
                        @if ($booking->bookingDetails->isNotEmpty())
                            {{ $booking->bookingDetails->pluck('seat.seat_number')->filter()->implode(', ') }}
                        @else
                            {{ implode(', ', $booking->seats ?? []) }}
                        @endif
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="ticket-label">Jumlah Tiket</div>
                        <div class="ticket-value">
                            {{ $booking->total_tickets }} Tiket
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="ticket-label">Total Pembayaran</div>
                        <div class="ticket-value text-warning">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <hr class="border-secondary">

                <div class="text-center">
                    <p class="small text-secondary mb-0">
                        Tunjukkan E-Ticket dan kode booking ini kepada petugas
                        sebelum memasuki studio.
                    </p>
                </div>

            </div>
        </div>

        <div class="d-flex gap-2 mt-3 no-print">
            <button onclick="window.print()" class="btn btn-warning fw-bold flex-fill">
                <i class="bi bi-printer"></i> Cetak Tiket
            </button>

            <a href="{{ route('history.index') }}" class="btn btn-outline-light flex-fill">
                Kembali
            </a>
        </div>

    </div>
@endsection
