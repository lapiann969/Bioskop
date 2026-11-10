@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Studio: {{ $studio->name }}</h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md border border-gray-200">
        <form action="{{ route('admin.studios.update', $studio->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Nama Studio</label>
                <input type="text" name="name" value="{{ old('name', $studio->name) }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Kapasitas Kursi</label>
                <input type="number" name="capacity" value="{{ old('capacity', $studio->capacity) }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                @error('capacity') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.studios.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Perbarui Studio</button>
            </div>
        </form>
    </div>
</div>
@endsection