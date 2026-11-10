<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Film - {{ $film->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body row">
                <div class="col-md-4 text-center">
                    @php
                        $posterUrl = $film->poster_path ?? null;
                        if ($posterUrl && !str_starts_with($posterUrl, 'http')) {
                            $posterUrl = 'https://image.tmdb.org/t/p/w500/' . ltrim($posterUrl, '/');
                        }
                    @endphp

                    @if($posterUrl)
                        <img src="{{ $posterUrl }}" class="img-fluid rounded shadow" alt="{{ $film->title }}">
                    @else
                        <div class="bg-dark text-white d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                            Tidak ada poster
                        </div>
                    @endif
                </div>
                <div class="col-md-8 mt-4 mt-md-0">
                    <h2 class="fw-bold">{{ $film->title }}</h2>
                    <hr>
                    <h5>Ringkasan Cerita:</h5>
                    <p class="text-muted" style="line-height: 1.8;">
                        {{ $film->overview ?? 'Ringkasan cerita belum tersedia untuk film ini.' }}
                    </p>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.films.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
                        <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-warning">Edit Film Ini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>