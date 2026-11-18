@extends('layouts.admin')

@section('title', 'Kelola Kursi')
@section('page-title', 'Kelola Data Kursi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Daftar Kursi Studio</h4>
        <a href="{{ route('admin.seats.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kursi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 px-4" width="70">No</th>
                        <th class="py-3 px-4">Studio</th>
                        <th class="py-3 px-4">Kode Kursi (Baris & Nomor)</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center" width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seats as $seat)
                        <tr>
                            <td class="px-4">{{ $loop->iteration }}</td>
                            <td class="px-4 fw-bold">{{ $seat->studio->name ?? '-' }}</td>
                            <td class="px-4"><span class="badge bg-dark">{{ $seat->row }}{{ $seat->number }}</span></td>
                            <td class="px-4">
                                <span class="badge bg-success">Aktif</span>
                            </td>
                            <td class="px-4 text-center">
                                <a href="{{ route('admin.seats.edit', $seat->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.seats.destroy', $seat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kursi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data kursi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection