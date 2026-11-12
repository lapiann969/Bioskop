@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Jadwal Tayang Film</h2>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            + Tambah Jadwal
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Film</th>
                        <th class="py-3 px-4">Studio</th>
                        <th class="py-3 px-4">Tanggal & Jam</th>
                        <th class="py-3 px-4">Harga</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td class="px-4">{{ $loop->iteration }}</td>
                            <td class="px-4 fw-bold">{{ $schedule->film->title ?? '-' }}</td>
                            <td class="px-4">{{ $schedule->studio->name ?? '-' }}</td>
                            <td class="px-4">
                                {{ \Carbon\Carbon::parse($schedule->show_time)->format('d M Y') }} <br>
                                <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}</span>
                            </td>
                            <td class="px-4">Rp {{ number_format($schedule->price, 0, ',', '.') }}</td>
                            <td class="px-4">
                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada jadwal tayang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection