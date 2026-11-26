
@extends('layouts.admin')

@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Data Booking')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Daftar Booking</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Kode Booking</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Film</th>
                            <th class="py-3 px-4">Jadwal</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $key => $booking)
                            <tr>
                                <td class="px-4">
                                    {{ $bookings->firstItem() + $key }}
                                </td>

                                <td class="px-4 fw-bold">
                                    {{ $booking->booking_code ?? $booking->id }}
                                </td>

                                <td class="px-4">
                                    {{ $booking->user->name ?? '-' }}
                                </td>

                                <td class="px-4">
                                    {{ $booking->schedule->film->title ?? '-' }}
                                </td>

                                <td class="px-4">
                                    {{ $booking->schedule->start_time ?? '-' }}
                                </td>

                                <td class="px-4">
                                    @php
                                        $status = strtolower($booking->status ?? '');
                                    @endphp

                                    @if($status == 'paid' || $status == 'success')
                                        <span class="badge bg-success">
                                            {{ $booking->status }}
                                        </span>
                                    @elseif($status == 'pending')
                                        <span class="badge bg-warning text-dark">
                                            {{ $booking->status }}
                                        </span>
                                    @elseif($status == 'cancelled' || $status == 'failed')
                                        <span class="badge bg-danger">
                                            {{ $booking->status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $booking->status ?? 'Belum diketahui' }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 text-center">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                        class="btn btn-info btn-sm text-white">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada data booking.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer bg-white">
            {{ $bookings->links() }}
        </div>
    </div>

@endsection
