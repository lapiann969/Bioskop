@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Tambah Studio Baru</h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md border border-gray-200">
        <form action="{{ route('admin.studios.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Nama Studio</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2" required placeholder="Contoh: Studio 1, IMAX, Premiere">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Kapasitas Kursi</label>
                <input type="number" name="capacity" value="{{ old('capacity') }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2" required placeholder="Contoh: 100">
                @error('capacity') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.studios.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Batal</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection