@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Jadwal Tayang</h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md border border-gray-200">
        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Relasi ke Film -->
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Pilih Film</label>
                <select name="film_id" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                    @foreach($films as $film)
                        <option value="{{ $film->id }}" {{ old('film_id', $schedule->film_id) == $film->id ? 'selected' : '' }}>
                            {{ $film->title }} ({{ $film->duration }} Menit)
                        </option>
                    @endforeach
                </select>
                @error('film_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Relasi ke Studio -->
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Pilih Studio</label>
                <select name="studio_id" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}" {{ old('studio_id', $schedule->studio_id) == $studio->id ? 'selected' : '' }}>
                            {{ $studio->name }} (Kapasitas: {{ $studio->capacity }} Kursi)
                        </option>
                    @endforeach
                </select>
                @error('studio_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Tanggal & Waktu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-700 font-medium">Tanggal Tayang</label>
                    <input type="date" name="show_date" value="{{ old('show_date', $schedule->show_date) }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                    @error('show_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 font-medium">Jam Tayang</label>
                    <!-- Tergantung format database, format jam mungkin perlu disesuaikan (H:i) -->
                    <input type="time" name="show_time" value="{{ old('show_time', \Carbon\Carbon::parse($schedule->show_time)->format('H:i')) }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                    @error('show_time') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Harga Tiket -->
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Harga Tiket (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $schedule->price) }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-purple-300" required>
                @error('price') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Perbarui Jadwal</button>
            </div>
        </form>
    </div>
</div>
@endsection