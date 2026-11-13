
@extends('layouts.customer')

@section('title', 'Home - TIX Bioskop')

@section('hero')
    @include('inc.customer.header')
@endsection

@section('content')

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-warning fw-bold mb-0">
            🎬 Sedang Tayang di Bioskop
        </h3>

        <a href="{{ route('films.index') }}"
            class="text-warning text-decoration-none">
            Lihat Semua →
        </a>
    </div>

    <div class="row">

        @forelse($films as $film)

            <div class="col-6 col-md-4 col-lg-3 mb-4">

                <div class="card bg-secondary text-white h-100 shadow border-0 overflow-hidden">

                    @if($film->poster_url)
                        <img
                            src="{{ $film->poster_url }}"
                            class="card-img-top"
                            alt="{{ $film->title }}"
                            style="height: 360px; object-fit: cover;">
                    @else
                        <div class="bg-dark d-flex align-items-center justify-content-center"
                            style="height: 360px;">
                            <span class="text-muted">
                                Tidak Ada Poster
                            </span>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column justify-content-between">

                        <div>
                            <h5 class="card-title text-truncate fw-bold">
                                {{ $film->title }}
                            </h5>

                            <p class="card-text text-white-50 small">
                                {{ \Illuminate\Support\Str::limit(
                                    $film->description ?? 'Tidak ada ringkasan cerita.',
                                    75
                                ) }}
                            </p>
                        </div>

                        <a href="{{ route('schedules.index', ['film_id' => $film->id]) }}"
                            class="btn btn-warning w-100 fw-bold mt-3">
                            Pesan Tiket
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">
                    Belum ada film yang tersedia di database.
                </p>

                <a href="{{ route('films.fetch-tmdb') }}"
                    class="btn btn-outline-warning">
                    Tarik Data Film dari TMDB
                </a>
            </div>

        @endforelse

    </div>

</div>

@endsection
