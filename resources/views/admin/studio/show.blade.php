@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Detail Studio</h2>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="mb-6">
            <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Nama Studio</span>
            <p class="text-xl font-bold text-gray-800 mt-1">{{ $studio->name }}</p>
        </div>
        
        <div class="mb-6">
            <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Kapasitas Maksimal</span>
            <p class="text-gray-700 mt-1">
                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-purple-100 bg-purple-600 rounded-full">
                    {{ $studio->capacity }} Kursi
                </span>
            </p>
        </div>

        <!-- Placeholder untuk layout kursi ke depannya (Opsional) -->
        <!-- <div class="mt-6 border-t pt-4">
            <h3 class="text-md font-semibold text-gray-700 mb-2">Denah Kursi</h3>
            <p class="text-sm text-gray-500">Fitur visualisasi layout kursi dapat ditambahkan di sini nantinya.</p>
        </div> -->

        <div class="mt-8 flex space-x-3">
            <a href="{{ route('admin.studios.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">Kembali</a>
            <a href="{{ route('admin.studios.edit', $studio->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">Edit Studio</a>
        </div>
    </div>
</div>
@endsection