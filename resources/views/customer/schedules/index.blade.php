<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jadwal Tayang - TIX Bioskop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm">
    <div class="container">
        <a class="navbar-brand text-warning fw-bold fs-4" href="{{ route('customer.dashboard') }}">🎥 TIX Bioskop</a>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-light btn-sm">← Kembali ke Film</a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container my-5">
    <!-- Info Detail Film yang Dipilih -->
    @if($film)
        <div class="card bg-black text-white border-secondary mb-5 shadow">
            <div class="row g-0 align-items-center">
                <div class="col-md-3 text-center p-3">
                    @php
                        $posterUrl = $film->poster_url ?? null;
                        if ($posterUrl && !str_starts_with($posterUrl, 'http')) {
                            $posterUrl = 'https://image.tmdb.org/t/p/w500' . $posterUrl;
                        }
                    @endphp
                    @if($posterUrl)
                        <img src="{{ $posterUrl }}" class="img-fluid rounded shadow" alt="{{ $film->title }}" style="max-height: 280px;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="height: 280px;">
                            <span>Tidak Ada Poster</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-9 p-4">
                    <span class="badge bg-warning text-dark mb-2">{{ $film->status ?? 'Showing' }}</span>
                    <h2 class="fw-bold text-warning">{{ $film->title }}</h2>
                    <p class="text-white-50 mb-2">⏱️ Durasi: {{ $film->duration ?? 120 }} Menit</p>
                    <p class="text-light">{{ $film->description ?? 'Tidak ada sinopsis untuk film ini.' }}</p>
                </div>
            </div>
        </div>
    @endif

    <h4 class="mb-4 text-warning fw-bold">📅 Pilih Jadwal & Studio</h4>

    <div class="row">
        @forelse($schedules as $schedule)
            @php
    $tanggal = $schedule->show_time
        ? $schedule->show_time->format('d M Y')
        : '-';

    $jam = $schedule->show_time
        ? $schedule->show_time->format('H:i')
        : '-';

    $harga = $schedule->price;
@endphp

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-secondary text-white h-100 border-0 shadow">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            @if(!$film && $schedule->film)
                                <h5 class="fw-bold text-warning mb-2">{{ $schedule->film->title }}</h5>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-dark fs-6">
                                    🏛️ {{ $schedule->studio->name ?? 'Studio ' . $schedule->studio_id }}
                                </span>
                                <span class="fw-bold text-warning fs-5">
                                    Rp {{ number_format($harga, 0, ',', '.') }}
                                </span>
                            </div>

                            <p class="mb-1">📆 <strong>Tanggal:</strong> {{ $tanggal }}</p>
                            <p class="mb-3">⏰ <strong>Jam Tayang:</strong> {{ $jam }}</p>
                        </div>

                        <a href="{{ route('seats.index', $schedule->id) }}" class="btn btn-warning w-100 fw-bold mt-2">
                            Pilih Kursi →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert bg-secondary text-white border-0 text-center py-5">
                    <h5 class="mb-2">Belum ada jadwal tayang untuk film ini.</h5>
                    <p class="text-white-50 mb-3">Silakan tambahkan jadwal terlebih dahulu melalui panel Admin (<code>/admin/schedules</code>) atau pilih film lain.</p>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-warning fw-bold">Lihat Film Lain</a>
                </div>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>