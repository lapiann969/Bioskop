@extends('layouts.customer')

@section('content')
<div class="container py-5">

    @if($film)
        <div class="card bg-black text-white border-secondary shadow mb-4">
            <div class="row g-0 align-items-center">
                @if($film->poster_url)
                    <div class="col-md-3">
                        <img src="{{ $film->poster_url }}"
                             class="img-fluid rounded-start"
                             alt="{{ $film->title }}"
                             style="width: 100%; height: 300px; object-fit: cover;">
                    </div>
                @endif

                <div class="col-md">
                    <div class="card-body p-4">
                        <h3 class="text-warning fw-bold">{{ $film->title }}</h3>
                        <p class="text-warning">
                            <i class="bi bi-clock"></i>
                            Durasi: {{ $film->duration ?? 120 }} Menit
                        </p>
                        <p class="text-light">
                            {{ $film->description ?? 'Tidak ada sinopsis untuk film ini.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <h4 class="section-title mb-4">
        <i class="bi bi-calendar-event"></i>
        Pilih Jadwal & Studio
    </h4>

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
                <div class="card movie-card text-white h-100 shadow">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            @if(!$film && $schedule->film)
                                <h5 class="fw-bold text-warning mb-3">
                                    {{ $schedule->film->title }}
                                </h5>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-dark fs-6">
                                    <i class="bi bi-building"></i>
                                    {{ $schedule->studio->name ?? 'Studio ' . $schedule->studio_id }}
                                </span>

                                <span class="fw-bold text-warning fs-5">
                                    Rp {{ number_format($harga, 0, ',', '.') }}
                                </span>
                            </div>

                            <p class="mb-2">
                                <i class="bi bi-calendar3 text-warning"></i>
                                <strong>Tanggal:</strong> {{ $tanggal }}
                            </p>

                            <p class="mb-3">
                                <i class="bi bi-clock text-warning"></i>
                                <strong>Jam Tayang:</strong> {{ $jam }}
                            </p>
                        </div>

                        <a href="{{ route('seats.index', $schedule->id) }}"
                           class="btn btn-warning w-100 fw-bold mt-2">
                            Pilih Kursi <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert bg-dark text-white border-secondary text-center py-5">
                    <h5 class="mb-2">Belum ada jadwal tayang.</h5>
                    <p class="text-white-50 mb-3">
                        Silakan pilih film lain atau tunggu jadwal tersedia.
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-warning fw-bold">
                        Lihat Film Lain
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
