
@extends('layouts.customer')

@section('title', 'Detail Film - TIX Bioskop')

@section('content')
<div class="container py-5">

    <a href="{{ route('films.index') }}"
       class="btn btn-outline-secondary mb-4">
        &larr; Kembali
    </a>

    <div class="row g-4">

        <div class="col-md-4">
            @if($film->poster_url)
                <img src="{{ $film->poster_url }}"
                     class="img-fluid rounded shadow"
                     alt="{{ $film->title }}">
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

            <p>
                <strong>Durasi:</strong>
                {{ $film->duration }} menit
            </p>

            <hr>

            <h5>Sinopsis</h5>
            <p>
                {{ $film->description ?? 'Deskripsi belum tersedia.' }}
            </p>

            <a href="{{ route('schedules.index', ['film_id' => $film->id]) }}"
               class="btn btn-warning mt-3">
                Pilih Jadwal
            </a>
        </div>

    </div>
</div>
@endsection
