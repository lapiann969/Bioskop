@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Detail Film</h2>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="mb-4">
            <span class="text-sm font-semibold text-gray-500 uppercase">Judul Film</span>
            <p class="text-lg font-bold text-gray-800">{{ $film->title }}</p>
        </div>
        <div class="mb-4">
            <span class="text-sm font-semibold text-gray-500 uppercase">Genre</span>
            <p class="text-gray-700">{{ $film->genre->name ?? '-' }}</p>
        </div>
        <div class="mb-4">
            <span class="text-sm font-semibold text-gray-500 uppercase">Durasi</span>
            <p class="text-gray-700">{{ $film->duration }} Menit</p>
        </div>
        <div class="mb-4">
            <span class="text-sm font-semibold text-gray-500 uppercase">Sinopsis</span>
            <p class="text-gray-700">{{ $film->synopsis ?? 'Tidak ada sinopsis.' }}</p>
        </div>

        <div class="mt-6 flex space-x-3">
            <a href="{{ route('admin.films.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Kembali</a>
            <a href="{{ route('admin.films.edit', $film->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Edit Film</a>
        </div>
    </div>
</div>
@endsection