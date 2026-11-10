@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Detail Kursi</h2>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Lokasi Studio</span>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ $seat->studio->name ?? 'Studio tidak ditemukan' }}</p>
            </div>
            
            <div>
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Kode Kursi</span>
                <p class="text-2xl font-black text-purple-600 mt-1">
                    {{ $seat->row }}{{ $seat->number }}
                </p>
            </div>

            <div>
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Baris (Row)</span>
                <p class="text-lg text-gray-800 mt-1">{{ $seat->row }}</p>
            </div>

            <div>
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Nomor</span>
                <p class="text-lg text-gray-800 mt-1">{{ $seat->number }}</p>
            </div>
        </div>

        <div class="mt-8 flex space-x-3 border-t pt-6">
            <a href="{{ route('admin.seats.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">Kembali</a>
            <a href="{{ route('admin.seats.edit', $seat->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">Edit Kursi</a>
        </div>
    </div>
</div>
@endsection