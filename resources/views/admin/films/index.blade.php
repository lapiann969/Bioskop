<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Film - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>🎬 Daftar Film</h3>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                <a href="{{ route('admin.films.create') }}" class="btn btn-primary">+ Tambah Film Manual</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Judul Film</th>
                            <th>Ringkasan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($films as $key =>$film)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-bold">{{ $film->title }}</td>
                            <td>{{ Str::limit($film->overview ?? 'Tidak ada ringkasan', 50) }}</td>
                            <td class="text-center">
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
                            <td colspan="4" class="text-center">Belum ada data film.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>