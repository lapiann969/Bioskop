
@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')
@section('page-title', 'Kelola Data Pembayaran')

@section('content')
<div class="container-fluid">

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Daftar Pembayaran</h5>
            <small class="text-muted">Kelola seluruh data transaksi pembayaran pelanggan.</small>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Film</th>
                            <th>Metode Pembayaran</th>
                            <th>Total Pembayaran</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>
                                    {{ $payments->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $payment->booking->booking_code ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $payment->booking->user->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $payment->booking->schedule->film->title ?? '-' }}
                                </td>

                                <td>
                                    {{ $payment->payment_method ?? '-' }}
                                </td>

                                <td>
                                    Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                                </td>

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

                                <td>
                                    {{ $payment->created_at?->format('d-m-Y H:i') ?? '-' }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}"
                                       class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Belum ada data pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
