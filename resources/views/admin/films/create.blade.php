@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Tambah Film Baru</h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md border border-gray-200">
        <form action="{{ route('admin.films.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Judul Film</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Genre</label>
                <select name="genre_id" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                    <option value="">-- Pilih Genre --</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
                @error('genre_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Durasi (Menit)</label>
                <input type="number" name="duration" value="{{ old('duration') }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2" required>
                @error('duration') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Sinopsis</label>
                <textarea name="synopsis" rows="4" class="w-full mt-1 border border-gray-300 rounded-lg p-2">{{ old('synopsis') }}</textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.films.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection