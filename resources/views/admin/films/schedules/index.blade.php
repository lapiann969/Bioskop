@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center my-6">
        <h2 class="text-2xl font-semibold text-gray-700">Jadwal Tayang Film</h2>
        <a href="{{ route('admin.schedules.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
            + Tambah Jadwal
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs border border-gray-200 bg-white">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Film</th>
                    <th class="px-4 py-3">Studio</th>
                    <th class="px-4 py-3">Tanggal & Jam</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y text-gray-700">
                @forelse($schedules as $schedule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ $schedule->film->title ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $schedule->studio->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ \Carbon\Carbon::parse($schedule->show_date)->format('d M Y') }} <br>
                            <span class="text-xs text-gray-500">{{ $schedule->show_time }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm">Rp {{ number_format($schedule->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm flex space-x-2">
                            <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada jadwal tayang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection