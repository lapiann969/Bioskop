<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bioskop - Sedang Tayang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm">
    <div class="container">
        <a class="navbar-brand text-warning fw-bold fs-4" href="#">🎥 TIX Bioskop</a>
        <div class="d-flex align-items-center gap-3">
            <span>Halo, <strong>{{ Auth::user()->name }}</strong>!</span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h3 class="mb-4 text-warning fw-bold">🎬 Sedang Tayang di Bioskop</h3>
    
    <div class="row">
        @forelse($films as $film)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card bg-secondary text-white h-100 shadow border-0 overflow-hidden">
                    
                    <!-- Menampilkan Gambar Poster Film -->
                    @php
                        // Menggunakan $film->poster_url sesuai dengan nama kolom di database
                        $posterUrl = $film->poster_url ?? null;
                        
                        // Mengecek jika URL belum lengkap dengan domain TMDB
                        if ($posterUrl && !str_starts_with($posterUrl, 'http')) {
                            $posterUrl = 'https://image.tmdb.org/t/p/w500' . $posterUrl;
                        }
                    @endphp

                    @if($posterUrl)
                        <img src="{{ $posterUrl }}" class="card-img-top" alt="{{ $film->title }}" style="height: 360px; object-fit: cover;">
                    @else
                        <!-- Tampilan kotak jika tidak ada poster -->
                        <div class="card-body text-center d-flex flex-column justify-content-center bg-dark" style="height: 360px;">
                            <span class="text-muted">Tidak Ada Poster</span>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title text-truncate fw-bold mb-1" title="{{ $film->title }}">{{ $film->title }}</h5>
                            <p class="card-text text-white-50 small">
                                {{ Str::limit($film->description ?? 'Tidak ada ringkasan cerita.', 75) }}
                            </p>
                        </div>
                       <a href="{{ route('schedules.index', ['film_id' => $film->id]) }}" class="btn btn-warning w-100 fw-bold mt-3"> Pesan Tiket
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <!-- Tampilan jika database film masih kosong -->
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Belum ada film yang tersedia di database.</p>
                <a href="{{ route('films.fetch-tmdb') }}" class="btn btn-outline-warning">Tarik Data Film dari TMDB</a>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>