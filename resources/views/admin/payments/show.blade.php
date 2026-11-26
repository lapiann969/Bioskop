
@extends('layouts.admin')

@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran')

@section('content')
<div class="container-fluid">

    <div class="mb-3">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">

        {{-- Informasi Pembayaran --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Informasi Pembayaran</h5>
                </div>

                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Pembayaran</th>
                            <td>{{ $payment->id }}</td>
                        </tr>

                        <tr>
                            <th>Kode Booking</th>
                            <td>
                                {{ $payment->booking->booking_code ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Total Pembayaran</th>
                            <td class="fw-bold">
                                Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($payment->status === 'Success')
                                    <span class="badge bg-success">Success</span>
                                @elseif ($payment->status === 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($payment->status === 'Failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ $payment->status ?? 'Unknown' }}
                                    </span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>
                                {{ $payment->created_at?->format('d-m-Y H:i:s') ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Terakhir Diperbarui</th>
                            <td>
                                {{ $payment->updated_at?->format('d-m-Y H:i:s') ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Informasi Pelanggan dan Film --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Informasi Booking</h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nama Pelanggan</label>
                        <p class="fw-semibold mb-0">
                            {{ $payment->booking->user->name ?? '-' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Email Pelanggan</label>
                        <p class="fw-semibold mb-0">
                            {{ $payment->booking->user->email ?? '-' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Judul Film</label>
                        <p class="fw-semibold mb-0">
                            {{ $payment->booking->schedule->film->title ?? '-' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Jumlah Tiket</label>
                        <p class="fw-semibold mb-0">
                            {{ $payment->booking->total_tickets ?? '-' }}
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">Status Booking</label>
                        <p class="fw-semibold mb-0">
                            {{ $payment->booking->status ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Xendit --}}
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Informasi Xendit</h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Payment Session ID</label>
                        <p class="mb-0 text-break">
                            {{ $payment->payment_session_id ?? '-' }}
                        </p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted small">Payment Request ID</label>
                        <p class="mb-0 text-break">
                            {{ $payment->payment_request_id ?? '-' }}
                        </p>
                    </div>

                    <hr>

                    <div>
                        <label class="text-muted small">Payment URL</label>
                        @if ($payment->payment_url)
                            <a href="{{ $payment->payment_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="d-block text-break">
                                Buka Link Pembayaran
                            </a>
                        @else
                            <p class="mb-0">-</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
