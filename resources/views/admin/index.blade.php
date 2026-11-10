@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center my-6">
        <h2 class="text-2xl font-semibold text-gray-700">Daftar Film</h2>
        <a href="{{ route('admin.films.create') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            + Tambah Film
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full overflow-hidden rounded-xs shadow-xs border border-gray-200 bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Genre</th>
                        <th class="px-4 py-3">Durasi</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-700">
                    @forelse($films as $film)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm font-semibold">{{ $film->title }}</td>
                            <td class="px-4 py-3 text-sm">{{ $film->genre->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $film->duration }} Menit</td>
                            <td class="px-4 py-3 text-sm flex space-x-2">
                                <a href="{{ route('admin.films.show', $film->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">Detail</a>
                                <a href="{{ route('admin.films.edit', $film->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</a>
                                <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada data film.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection