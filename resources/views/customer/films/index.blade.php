@extends('layouts.customer')

@section('title', 'Daftar Film - TIX Bioskop')

@section('content')

    <style>
        .film-page {
            min-height: 70vh;
            color: #fff;
            padding-top: 85px;
        }

        .film-heading {
            color: #facc15;
            font-weight: 800;
        }


        /* TAB KATEGORI FILM */
        .film-tabs {
            display: flex;
            align-items: center;
            gap: 0;
            flex-wrap: wrap;
            margin-bottom: 20px;
            border-bottom: 1px solid #292929;
        }

        .film-tabs a {
            position: relative;
            padding: 13px 22px;
            color: #999;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: .2s;
            background: transparent;
            border-radius: 0;
        }

        .film-tabs a:hover {
            color: #facc15;
            background: transparent;
        }

        .film-tabs a.active {
            color: #facc15;
            background: transparent;
        }

        .film-tabs a.active::after {
            content: "";
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background: #facc15;
            border-radius: 3px 3px 0 0;
        }

        /* FILTER FILM */
        .film-filter {
            background: #171717;
            border: 1px solid #292929;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 28px;
        }

        .film-search {
            display: grid;
            grid-template-columns: minmax(180px, 1fr) 150px 130px 110px;
            gap: 10px;
            align-items: center;
        }

        .film-search input,
        .film-search select {
            width: 100%;
            height: 40px;
            padding: 0 12px;
            background: #222;
            color: #fff;
            border: 1px solid #303030;
            border-radius: 7px;
            outline: none;
            font-size: 13px;
        }

        .film-search input:focus,
        .film-search select:focus {
            border-color: #facc15;
            box-shadow: 0 0 0 2px rgba(250, 204, 21, .12);
        }

        .film-search input::placeholder {
            color: #888;
        }

        .film-search select option {
            background: #222;
            color: #fff;
        }

        .film-search button {
            height: 40px;
            background: #facc15;
            color: #171717;
            border: none;
            border-radius: 7px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
        }

        .film-search button:hover {
            background: #eab308;
        }

        .film-search a {
            color: #aaa;
            text-decoration: none;
            text-align: center;
            font-size: 12px;
            white-space: nowrap;
        }

        .film-search a:hover {
            color: #facc15;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .film-search {
                grid-template-columns: 1fr 1fr;
            }

            .film-search input {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 576px) {
            .film-search {
                grid-template-columns: 1fr;
            }

            .film-search input {
                grid-column: auto;
            }

            .film-tabs a {
                flex: 1;
                text-align: center;
                padding: 12px 8px;
                font-size: 12px;
            }
        }

        .film-card {
            background: #343a40;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: transform .25s, box-shadow .25s;
        }

        .film-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .3);
        }

        .film-poster {
            width: 100%;
            height: 320px;
            object-fit: cover;
        }

        .film-title {
            color: #fff;
            font-size: 17px;
            font-weight: 700;
        }

        .film-description {
            color: #adb5bd;
            font-size: 13px;
            min-height: 60px;
        }

        .film-detail-btn {
            background: #facc15;
            color: #212529;
            border: none;
            font-weight: 700;
        }

        .film-detail-btn:hover {
            background: #eab308;
            color: #212529;
        }

        @media (max-width: 576px) {
            .film-poster {
                height: 230px;
            }

            .film-filter {
                padding: 14px;
            }

            .film-tabs a {
                flex: 1;
                text-align: center;
                padding: 10px 8px;
            }
        }
    </style>

    <div class="container my-4 film-page">

        {{-- Judul halaman --}}
        <div class="mb-4">
            <h3 class="film-heading">
                🎬 Daftar Film
            </h3>

            <p class="text-secondary">
                Temukan film favoritmu dan pesan tiket sekarang.
            </p>
        </div>


        {{-- TAB KATEGORI FILM --}}
        <div class="film-tabs">

            <a href="{{ route('films.index', ['status' => 'Showing']) }}" class="{{ $status === 'Showing' ? 'active' : '' }}">
                Sedang Tayang
            </a>

            <a href="{{ route('films.index', ['status' => 'Upcoming']) }}"
                class="{{ $status === 'Upcoming' ? 'active' : '' }}">
                Segera Hadir
            </a>

            <a href="{{ route('films.index') }}" class="{{ !$status ? 'active' : '' }}">
                Semua Film
            </a>

        </div>

        {{-- FORM PENCARIAN DAN FILTER --}}
        <form action="{{ route('films.index') }}" method="GET" class="film-filter">

            @if ($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <div class="film-search">

                {{-- Pencarian film --}}
                <input type="text" name="search" placeholder="Cari film..." value="{{ $search }}">

                {{-- Filter genre --}}
                <select name="genre_id">
                    <option value="">Semua Genre</option>

                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}"
                            {{ (string) $genreId === (string) $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Pengurutan --}}
                <select name="sort">
                    <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>
                        Terbaru
                    </option>

                    <option value="oldest" {{ ($sort ?? '') === 'oldest' ? 'selected' : '' }}>
                        Terlama
                    </option>
                </select>

                {{-- Hapus filter --}}
                <a href="{{ route('films.index') }}">
                    Hapus Filter
                </a>

            </div>

        </form>

        {{-- Informasi hasil --}}
        <div class="text-white-50 mb-3">
            @if ($search)
                Hasil pencarian: "{{ $search }}"
            @elseif($genreId)
                Film berdasarkan genre terpilih
            @else
                Daftar film TIX Bioskop
            @endif
        </div>

        {{-- Daftar film --}}
        <div class="row">

            @forelse($films as $film)

                <div class="col-6 col-md-4 col-lg-3 mb-4">

                    <div class="card film-card h-100 shadow">

                        {{-- Poster --}}
                        @if ($film->poster_url)
                            <img src="{{ $film->poster_url }}" class="film-poster" alt="{{ $film->title }}">
                        @else
                            <div
                                class="film-poster bg-dark d-flex
                                    justify-content-center align-items-center">

                                <span class="text-secondary">
                                    Tidak Ada Poster
                                </span>

                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">

                            {{-- Judul --}}
                            <h5 class="film-title text-truncate" title="{{ $film->title }}">
                                {{ $film->title }}
                            </h5>

                            {{-- Sinopsis --}}
                            <p class="film-description">
                                {{ \Illuminate\Support\Str::limit($film->description ?? 'Tidak ada ringkasan cerita.', 85) }}
                            </p>

                            {{-- Genre film --}}
                            <div class="mb-3 d-flex flex-wrap gap-1">

                                @foreach ($film->genres as $genre)
                                    <span class="badge bg-dark text-warning">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach

                            </div>

                            {{-- Status --}}
                            <div class="mb-3">

                                @if ($film->status === 'Showing')
                                    <span class="badge bg-success">
                                        Sedang Tayang
                                    </span>
                                @else
                                    <span class="badge bg-info text-dark">
                                        Segera Hadir
                                    </span>
                                @endif

                            </div>

                            {{-- Detail --}}
                            <a href="{{ route('films.show', $film->id) }}" class="btn film-detail-btn w-100 mt-auto">
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
                        Coba ubah kata pencarian atau filter genre.
                    </p>

                    <a href="{{ route('films.index') }}" class="btn btn-outline-warning">
                        Lihat Semua Film
                    </a>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">

            {{ $films->links('pagination::bootstrap-5') }}

        </div>

    </div>

@endsection
