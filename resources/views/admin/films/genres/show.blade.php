@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Detail Genre</h2>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="mb-6">
            <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Nama Genre</span>
            <p class="text-xl font-bold text-gray-800 mt-1">{{ $genre->name }}</p>
        </div>

        <!-- Opsional: Jika nanti Anda ingin menampilkan daftar film dengan genre ini -->
        <!-- 
        <div class="mt-6 border-t pt-4">
            <h3 class="text-md font-semibold text-gray-700 mb-2">Film dengan Genre {{ $genre->name }}</h3>
            <ul class="list-disc list-inside text-gray-600">
                @forelse($genre->films ?? [] as $film)
                    <li>{{ $film->title }}</li>
                @empty
                    <li class="text-sm italic">Belum ada film di kategori ini.</li>
                @endforelse
            </ul>
        </div> 
        -->

        <div class="mt-8 flex space-x-3">
            <a href="{{ route('admin.genres.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">Kembali</a>
            <a href="{{ route('admin.genres.edit', $genre->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">Edit Genre</a>
        </div>
    </div>
</div>
@endsection