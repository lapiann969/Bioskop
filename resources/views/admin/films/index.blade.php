@extends('layouts.admin')

@section('title', 'Kelola Film')
@section('page-title', 'Kelola Data Film')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Daftar Film</h4>
        <a href="{{ route('admin.films.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Film Manual
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
                        <th class="py-3 px-4">Judul Film</th>
                        <th class="py-3 px-4">Ringkasan</th>
                        <th class="py-3 px-4 text-center" width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($films as $key => $film)
                        <tr>
                            <td class="px-4">{{ $key + 1 }}</td>
                            <td class="px-4 fw-bold">{{ $film->title }}</td>
                            <td class="px-4 text-muted">{{ Str::limit($film->overview ?? 'Tidak ada ringkasan', 55) }}</td>
                            <td class="px-4 text-center">
                                <a href="{{ route('admin.films.show', $film->id) }}" class="btn btn-info btn-sm text-white">Lihat</a>
                                <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus film ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data film.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection