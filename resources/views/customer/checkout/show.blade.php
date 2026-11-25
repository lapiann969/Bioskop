@extends('layouts.customer')

@push('styles')
    <style>
        .checkout-container {
            max-width: 600px;
            padding-top: 120px;
            padding-bottom: 40px;
        }

        /* Area tempat komponen pembayaran Xendit ditampilkan */
        #xendit-payment-container {
            color: #212529;
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="container checkout-container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card bg-black text-white border-secondary shadow">

            <div class="card-header border-secondary d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-warning fs-5">
                    Detail Pemesanan
                </span>

                @if ($booking->status === 'Paid')
                    <span class="badge bg-success fs-6">
                        LUNAS (E-TICKET)
                    </span>
                @else
                    <span class="badge bg-warning text-dark fs-6">
                        MENUNGGU PEMBAYARAN
                    </span>
                @endif
            </div>

            <div class="card-body p-4">

                <small class="text-secondary d-block">Kode Booking</small>

                <h4 class="fw-bold text-warning mb-3">
                    {{ $booking->booking_code }}
                </h4>

                <hr class="border-secondary">

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Film</span>
                    <span class="fw-bold text-end">
                        {{ $booking->schedule->film->title }}
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Studio</span>
                    <span>{{ $booking->schedule->studio->name }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Jadwal</span>
                    <span>
                        {{ \Carbon\Carbon::parse($booking->schedule->show_time)->format('d M Y, H:i') }} WIB
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">
                        Kursi ({{ $booking->total_tickets }} Tiket)
                    </span>

                    <span class="fw-bold text-warning">
                        {{ implode(', ', $booking->seats ?? []) }}
                    </span>
                </div>

                <hr class="border-secondary">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fs-5">Total Bayar</span>

                    <span class="fs-4 fw-bold text-warning">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </span>
                </div>

                {{-- PEMBAYARAN UNTUK BOOKING BARU DENGAN XENDIT COMPONENTS --}}
                @if ($booking->status === 'Pending' && $componentsSdkKey)
                    <div data-xendit-checkout data-sdk-key="{{ $componentsSdkKey }}"
                        data-status-url="{{ route('bookings.payment-status', $booking) }}">
                        <h5 class="fw-bold text-warning mb-3">
                            Pilih Metode Pembayaran
                        </h5>

                        {{-- Komponen pilihan metode pembayaran dari Xendit --}}
                        <div id="xendit-payment-container"></div>

                        {{-- Pesan status dari JavaScript --}}
                        <p id="xendit-payment-message" class="text-info small" aria-live="polite"></p>

                        <button type="button" id="xendit-submit-button" class="btn btn-warning w-100 fw-bold fs-5 py-2"
                            disabled>
                            Bayar Sekarang
                        </button>
                    </div>

                    {{-- PEMBAYARAN BOOKING LAMA DENGAN INVOICE --}}
                @elseif($booking->status === 'Pending' && $booking->xendit_invoice_url)
                    <a href="{{ $booking->xendit_invoice_url }}" class="btn btn-warning w-100 fw-bold fs-5 py-2">
                        Bayar Sekarang via Xendit
                        <i class="bi bi-arrow-right"></i>
                    </a>

                    {{-- BOOKING PENDING TANPA SESI PEMBAYARAN --}}
                @elseif($booking->status === 'Pending')
                    <div class="alert alert-warning">
                        Sesi pembayaran belum tersedia atau sudah tidak aktif.
                        Silakan hubungi admin untuk bantuan.
                    </div>

                    {{-- BOOKING SUDAH LUNAS --}}
                @elseif($booking->status === 'Paid')
                    <a href="{{ route('tickets.show', $booking) }}" class="btn btn-warning fw-bold w-100 mb-3">
                        <i class="bi bi-ticket-perforated-fill"></i>
                        Lihat E-Ticket
                    </a>

                    <div class="alert alert-dark border-success text-center mb-3">
                        Tunjukkan kode
                        <strong>{{ $booking->booking_code }}</strong>
                        di pintu masuk studio.
                    </div>

                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-light w-100">
                        Kembali ke Beranda
                    </a>
                @endif

            </div>
        </div>
    </div>
@endsection
