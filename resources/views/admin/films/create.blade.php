<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Film Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm max-w-md mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white fw-bold">
                Tambah Film Baru
            </div>
            <div class="card-body">
                <form action="{{ route('admin.films.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Judul Film</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ringkasan (Overview)</label>
                        <textarea name="overview" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Poster (Opsional)</label>
                        <input type="text" name="poster_path" class="form-control" placeholder="/path_gambar_tmdb.jpg atau https://...">
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.films.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Film</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>