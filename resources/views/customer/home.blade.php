
@extends('layouts.customer')

@section('title', 'Home - TIX Bioskop')

@section('hero')
    @include('inc.customer.header')
@endsection

@section('content')

<div class="container my-4">

    {{-- BAGIAN 1: SEDANG TAYANG --}}
    <div class="mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-warning fw-bold mb-0">
                🎬 Sedang Tayang di Bioskop
            </h3>

            <a href="{{ route('films.index', ['status' => 'Showing']) }}"
                class="text-warning text-decoration-none">
                Lihat Semua →
            </a>
        </div>

        <div class="row">

            @forelse($showingFilms as $film)

                <div class="col-6 col-md-4 col-lg-3 mb-4">

                    <div class="card bg-secondary text-white h-100 shadow border-0 overflow-hidden">

                        @if($film->poster_url)
                            <img src="{{ $film->poster_url }}"
                                class="card-img-top"
                                alt="{{ $film->title }}"
                                style="height: 360px; object-fit: cover;">
                        @else
                            <div class="bg-dark d-flex align-items-center justify-content-center"
                                style="height: 360px;">
                                <span class="text-muted">Tidak Ada Poster</span>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title fw-bold text-truncate">
                                {{ $film->title }}
                            </h5>

                            <p class="text-white-50 small">
                                {{ \Illuminate\Support\Str::limit(
                                    $film->description ?? 'Tidak ada sinopsis.',
                                    85
                                ) }}
                            </p>

                            <div class="mt-auto">

                                @if($film->schedules()->where('show_time', '>=', now())->exists())

                                    <a href="{{ route('schedules.index', ['film_id' => $film->id]) }}"
                                        class="btn btn-warning w-100 fw-bold">
                                        Pesan Tiket
                                    </a>

                                @else

                                    <button class="btn btn-outline-light w-100" disabled>
                                        Jadwal Belum Tersedia
                                    </button>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12 text-center py-4">
                    <p class="text-secondary">
                        Belum ada film yang sedang tayang.
                    </p>
                </div>

            @endforelse

        </div>

    </div>


    {{-- BAGIAN 2: SEGERA HADIR --}}
    <div class="mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3 class="text-warning fw-bold mb-0">
                🍿 Segera Hadir
            </h3>

            <a href="{{ route('films.index', ['status' => 'Upcoming']) }}"
                class="text-warning text-decoration-none">
                Lihat Semua →
            </a>

        </div>

        <div class="row">

            @forelse($upcomingFilms as $film)

                <div class="col-6 col-md-4 col-lg-3 mb-4">

                    <div class="card bg-secondary text-white h-100 shadow border-0 overflow-hidden">

                        @if($film->poster_url)

                            <img src="{{ $film->poster_url }}"
                                class="card-img-top"
                                alt="{{ $film->title }}"
                                style="height: 360px; object-fit: cover;">

                        @else

                            <div class="bg-dark d-flex align-items-center justify-content-center"
                                style="height: 360px;">
                                <span class="text-muted">Tidak Ada Poster</span>
                            </div>

                        @endif

                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title fw-bold text-truncate">
                                {{ $film->title }}
                            </h5>

                            <p class="text-white-50 small">
                                {{ \Illuminate\Support\Str::limit(
                                    $film->description ?? 'Tidak ada sinopsis.',
                                    85
                                ) }}
                            </p>

                            <div class="mt-auto">

                                <span class="badge bg-info text-dark w-100 py-2">
                                    Segera Hadir
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12 text-center py-4">
                    <p class="text-secondary">
                        Belum ada film yang akan datang.
                    </p>
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
