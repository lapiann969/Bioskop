@extends('layouts.admin')

@section('title', 'Kelola Studio')
@section('page-title', 'Kelola Data Studio')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Daftar Studio Bioskop</h4>
        <a href="{{ route('admin.studios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Studio
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
                        <th class="py-3 px-4">Nama Studio</th>
                        <th class="py-3 px-4">Kapasitas Kursi</th>
                        <th class="py-3 px-4 text-center" width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studios as $studio)
                        <tr>
                            <td class="px-4">{{ $loop->iteration }}</td>
                            <td class="px-4 fw-bold">{{ $studio->name }}</td>
                            <td class="px-4">{{ $studio->capacity }} Kursi</td>
                            <td class="px-4 text-center">
                                <a href="{{ route('admin.studios.edit', $studio->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.studios.destroy', $studio->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus studio ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data studio.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection