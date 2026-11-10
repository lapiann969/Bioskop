@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Kursi: {{ $seat->row }}{{ $seat->number }}</h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md border border-gray-200">
        <form action="{{ route('admin.seats.update', $seat->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-medium">Pilih Studio</label>
                <select name="studio_id" class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring-purple-300" required>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}" {{ old('studio_id', $seat->studio_id) == $studio->id ? 'selected' : '' }}>
                            {{ $studio->name }}
                        </option>
                    @endforeach
                </select>
                @error('studio_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-700 font-medium">Baris (Row)</label>
                    <input type="text" name="row" value="{{ old('row', $seat->row) }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2 uppercase" required maxlength="2">
                    @error('row') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 font-medium">Nomor Kursi</label>
                    <input type="number" name="number" value="{{ old('number', $seat->number) }}" class="w-full mt-1 border border-gray-300 rounded-lg p-2" required>
                    @error('number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.seats.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Perbarui Kursi</button>
            </div>
        </form>
    </div>
</div>
@endsection