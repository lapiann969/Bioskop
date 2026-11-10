@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Detail Jadwal Tayang</h2>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Info Film -->
            <div>
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Judul Film</span>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ $schedule->film->title ?? 'Film tidak ditemukan' }}</p>
                <p class="text-sm text-gray-600">{{ $schedule->film->duration ?? '-' }} Menit</p>
            </div>
            
            <!-- Info Studio -->
            <div>
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Studio</span>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ $schedule->studio->name ?? 'Studio tidak ditemukan' }}</p>
            </div>

            <!-- Info Waktu Tayang -->
            <div>
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Waktu Tayang</span>
                <p class="text-lg text-gray-800 mt-1">
                    {{ \Carbon\Carbon::parse($schedule->show_date)->translatedFormat('l, d F Y') }} <br>
                    <span class="font-semibold text-purple-600">{{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }} WIB</span>
                </p>
            </div>

            <!-- Info Harga -->
            <div>
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Harga Tiket</span>
                <p class="text-lg font-bold text-green-600 mt-1">
                    Rp {{ number_format($schedule->price, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="mt-8 flex space-x-3 border-t pt-6">
            <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">Kembali</a>
            <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">Edit Jadwal</a>
        </div>
    </div>
</div>
@endsection