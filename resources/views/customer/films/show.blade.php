
@extends('layouts.customer')

@section('title', 'Detail Film - TIX Bioskop')

@section('content')

<style>
    /* Jarak dari navbar */
    .film-detail-page {
        padding-top: 110px;
        padding-bottom: 50px;
    }

    /* Poster film */
    .film-detail-poster {
        width: 100%;
        height: auto;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
    }

    /* Judul film */
    .film-detail-title {
        color: #fff;
        font-weight: 800;
        margin-bottom: 12px;
    }

    /* Badge status */
    .badge-film-status {
        background-color: #facc15;
        color: #171717;
        padding: 7px 12px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 13px;
    }

    /* Badge genre */
    .badge-film-genre {
        background-color: #29230f;
        color: #facc15;
        border: 1px solid #806400;
        padding: 7px 13px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .badge-film-genre:hover {
        background-color: #facc15;
        color: #171717;
        border-color: #facc15;
    }

    /* Informasi film */
    .film-detail-info {
        color: #f5f5f5;
    }

    .film-detail-info hr {
        border-color: #555;
        margin: 22px 0 18px;
    }

    .film-detail-synopsis {
        color: #dedede;
        line-height: 1.7;
    }

    .film-detail-section-title {
        color: #fff;
        font-weight: 700;
        margin-bottom: 8px;
    }

    /* Responsif untuk HP */
    @media (max-width: 767px) {
        .film-detail-page {
            padding-top: 100px;
        }

        .film-detail-title {
            margin-top: 8px;
            font-size: 25px;
        }
    }
</style>

@php
    $youtubeId = null;

    if ($film->trailer_url) {
        $url = $film->trailer_url;
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'])) {
            parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);

            if (!empty($query['v'])) {
                $youtubeId = $query['v'];
            } elseif (preg_match('~^/(embed|shorts)/([a-zA-Z0-9_-]+)~', $path, $matches)) {
                $youtubeId = $matches[2];
            }
        } elseif ($host === 'youtu.be') {
            $youtubeId = trim($path, '/');
        }

        if (!is_string($youtubeId) || !preg_match('/^[a-zA-Z0-9_-]{11}$/', $youtubeId)) {
            $youtubeId = null;
        }
    }
@endphp

<div class="container film-detail-page">

    <a href="{{ route('films.index') }}"
       class="btn btn-outline-secondary mb-4">
        &larr; Kembali
    </a>

    <div class="row g-4">

        {{-- Poster --}}
        <div class="col-md-4">
            @if ($film->poster_url)
                <img
                    src="{{ $film->poster_url }}"
                    class="film-detail-poster"
                    alt="{{ $film->title }}"
                >
            @else
                <div class="bg-secondary text-white rounded p-5 text-center">
                    Poster tidak tersedia
                </div>
            @endif
        </div>

        {{-- Informasi Film --}}
        <div class="col-md-8 film-detail-info">

            <h2 class="film-detail-title">
                {{ $film->title }}
            </h2>

            {{-- Status dan Genre --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">

                <span class="badge-film-status">
                    {{ $film->status }}
                </span>

                @forelse ($film->genres as $genre)
                    <span class="badge-film-genre">
                        {{ $genre->name }}
                    </span>
                @empty
                    <span class="text-secondary small">
                        Genre belum tersedia
                    </span>
                @endforelse

            </div>

            <p class="mb-3">
                <strong>Durasi:</strong>
                {{ $film->duration }} menit
            </p>

            <hr>

            {{-- Sinopsis --}}
            <h5 class="film-detail-section-title">
                Sinopsis
            </h5>

            <p class="film-detail-synopsis">
                {{ $film->description ?? 'Deskripsi belum tersedia.' }}
            </p>

            {{-- Tombol --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3">

                <a
                    href="{{ route('schedules.index', ['film_id' => $film->id]) }}"
                    class="btn btn-warning px-4 py-2 fw-semibold"
                >
                    Pilih Jadwal
                </a>

                @if ($youtubeId)
                    <a
                        href="{{ $film->trailer_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-outline-warning px-4 py-2 fw-semibold"
                    >
                        <i class="fas fa-play me-1"></i>
                        Lihat Trailer
                    </a>
                @endif

            </div>

            @if ($film->trailer_url && !$youtubeId)
                <p class="text-muted mt-3 mb-0 small">
                    Link trailer tidak valid.
                </p>
            @endif

        </div>
    </div>
</div>

@endsection
