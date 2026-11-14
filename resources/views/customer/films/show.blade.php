@extends('layouts.customer')

@section('title', 'Detail Film - TIX Bioskop')

@section('content')
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

    <div class="container py-5">
        <a href="{{ route('films.index') }}" class="btn btn-outline-secondary mb-4">
            &larr; Kembali
        </a>

        <div class="row g-4">
            <div class="col-md-4">
                @if ($film->poster_url)
                    <img src="{{ $film->poster_url }}" class="img-fluid rounded shadow" alt="{{ $film->title }}">
                @else
                    <div class="bg-secondary text-white rounded p-5 text-center">
                        Poster tidak tersedia
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <h2 class="fw-bold">{{ $film->title }}</h2>

                <span class="badge bg-warning text-dark mb-3">
                    {{ $film->status }}
                </span>

                <p><strong>Durasi:</strong> {{ $film->duration }} menit</p>

                <hr>

                <h5>Sinopsis</h5>
                <p>{{ $film->description ?? 'Deskripsi belum tersedia.' }}</p>

                <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                    <!-- Tombol Pilih Jadwal -->
                    <a href="{{ route('schedules.index', ['film_id' => $film->id]) }}"
                        class="btn btn-warning px-4 py-2 fw-semibold">
                        Pilih Jadwal
                    </a>

                    @if ($youtubeId)
                        <!-- Tombol Lihat Trailer (Hanya sebagai tombol, tidak memunculkan video di bawah) -->
                        <div class="d-inline-block">
                            <a href="{{ $film->trailer_url }}" target="_blank" class="btn btn-outline-warning px-4 py-2 fw-semibold">
                                <i class="fas fa-play me-1"></i> Lihat Trailer
                            </a>
                        </div>
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