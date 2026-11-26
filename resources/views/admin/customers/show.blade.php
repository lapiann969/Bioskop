
@extends('layouts.admin')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="container-fluid">

    <div class="mb-3">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">

        {{-- Informasi Pelanggan --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Informasi Pelanggan</h5>
                </div>

                <div class="card-body">
                    <table class="table table-light table-bordered align-middle">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>{{ $customer->role }}</td>
                        </tr>
                        <tr>
                            <th>Bergabung</th>
                            <td>{{ $customer->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Riwayat Booking --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Riwayat Booking</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-light table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Booking</th>
                                    <th>Jumlah Tiket</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($customer->bookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $booking->booking_code }}</td>
                                        <td>{{ $booking->total_tickets }}</td>
                                        <td>
                                            Rp{{ number_format((float) $booking->total_price, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $booking->status }}</td>
                                        <td>{{ $booking->created_at?->format('d-m-Y') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            Pelanggan belum memiliki riwayat booking.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
