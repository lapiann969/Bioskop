
@extends('layouts.admin')

@section('title', 'Detail Booking')
@section('page-title', 'Detail Data Booking')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Detail Booking</h4>

        <a href="{{ route('admin.bookings.index') }}"
            class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-bold">
            Informasi Booking
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <tr>
                    <th width="200">ID Booking</th>
                    <td>{{ $booking->id }}</td>
                </tr>

                <tr>
                    <th>Pelanggan</th>
                    <td>{{ $booking->user->name ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Film</th>
                    <td>{{ $booking->schedule->film->title ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Studio</th>
                    <td>{{ $booking->schedule->studio->name ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Waktu Mulai</th>
                    <td>{{ $booking->schedule->start_time ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Status Booking</th>
                    <td>{{ $booking->status ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Pembayaran</th>
                    <td>{{ $booking->payment->status ?? '-' }}</td>
                </tr>
            </table>

            <h5 class="fw-bold mt-4 mb-3">Detail Kursi</h5>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Kursi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($booking->bookingDetails as $key => $detail)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $detail->seat->seat_number ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">
                                    Belum ada detail kursi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
