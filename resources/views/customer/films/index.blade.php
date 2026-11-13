
@extends('layouts.customer')

@section('title', 'Daftar Film - TIX Bioskop')

@section('content')

<div class="container my-4">

    <!-- Judul Halaman -->
    <div class="mb-4">
        <h3 class="text-warning fw-bold">
            🎬 Daftar Film
        </h3>

        <p class="text-secondary">
            Temukan film favoritmu dan pesan tiket sekarang.
        </p>
    </div>

    <!-- Form Pencarian -->
    <form action="{{ route('films.index') }}" method="GET"
        class="mb-4">

        <div class="input-group">

            <input type="search"
                name="search"
                class="form-control"
                placeholder="Cari judul film..."
                value="{{ $search }}">

            <button type="submit" class="btn btn-warning fw-bold">
                Cari
            </button>

            @if($search)
                <a href="{{ route('films.index') }}"
                    class="btn btn-outline-secondary">
                    Reset
                </a>
            @endif

        </div>

    </form>

    <!-- Informasi Jumlah Film -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <span class="text-white-50">
            @if($search)
                Hasil pencarian: "{{ $search }}"
            @else
                Semua film yang tersedia
            @endif
        </span>

        <span class="badge bg-warning text-dark">
            {{ $films->total() }} Film
        </span>

    </div>

    <!-- Daftar Film -->
    <div class="row">

        @forelse($films as $film)

            <div class="col-6 col-md-4 col-lg-3 mb-4">

                <div class="card bg-secondary text-white h-100 shadow border-0 overflow-hidden">

                    <!-- Poster -->
                    @if($film->poster_url)

                        <img
                            src="{{ $film->poster_url }}"
                            class="card-img-top"
                            alt="{{ $film->title }}"
                            style="height: 320px; object-fit: cover;"
                        >

                    @else

                        <div class="bg-dark d-flex align-items-center justify-content-center"
                            style="height: 320px;">

                            <span class="text-muted">
                                Tidak Ada Poster
                            </span>

                        </div>

                    @endif

                    <!-- Informasi Film -->
                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title fw-bold text-truncate"
                            title="{{ $film->title }}">

                            {{ $film->title }}

                        </h5>

                        <p class="text-white-50 small">
                            {{ \Illuminate\Support\Str::limit(
                                $film->description ?? 'Tidak ada ringkasan cerita.',
                                85
                            ) }}
                        </p>

                        <div class="mb-3">

                            @if($film->status === 'Showing')
                                <span class="badge bg-success">
                                    Sedang Tayang
                                </span>
                            @else
                                <span class="badge bg-info text-dark">
                                    Akan Tayang
                                </span>
                            @endif

                        </div>

                        <a href="{{ route('films.show', $film->id) }}"
   class="btn btn-warning w-100">
    Detail Film
</a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12 text-center py-5">

                <h5 class="text-white">
                    Film Tidak Ditemukan
                </h5>

                <p class="text-secondary">
                    Coba gunakan judul film yang berbeda.
                </p>

                <a href="{{ route('films.index') }}"
                    class="btn btn-outline-warning">

                    Lihat Semua Film

                </a>

            </div>

        @endforelse

    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">

        {{ $films->links('pagination::bootstrap-5') }}

    </div>

</div>

@endsection
