@extends('layouts.admin')

@section('title', 'Detail Film')
@section('page-title', 'Detail Data Film')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-4">

                {{-- POSTER FILM --}}
                <div class="col-md-4 text-center">
                    @if($film->poster_url)
                        <img
                            src="{{ $film->poster_url }}"
                            class="img-fluid rounded shadow-sm"
                            alt="{{ $film->title }}"
                            style="max-height: 450px; object-fit: contain;"
                        >
                    @else
                        <div class="bg-dark text-white d-flex align-items-center justify-content-center rounded"
                             style="height: 400px;">
                            Tidak ada poster
                        </div>
                    @endif
                </div>

                {{-- INFORMASI FILM --}}
                <div class="col-md-8">

                    <h2 class="fw-bold">{{ $film->title }}</h2>
                    <hr>

                    <h5>Ringkasan Cerita:</h5>

                    <p class="text-muted" style="line-height: 1.8;">
                        {{ $film->description ?: 'Ringkasan cerita belum tersedia untuk film ini.' }}
                    </p>

                    <div class="mt-4">
                        <a href="{{ route('admin.films.index') }}"
                           class="btn btn-secondary">
                            Kembali ke Daftar
                        </a>

                        <a href="{{ route('admin.films.edit', $film->id) }}"
                           class="btn btn-warning">
                            Edit Film Ini
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection