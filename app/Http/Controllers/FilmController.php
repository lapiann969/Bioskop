<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Film;
use Carbon\Carbon;

class FilmController extends Controller
{
    private function getBaseUrl()
    {
        return rtrim(env('TMDB_BASE_URL'), '/');
    }

    private function getApiKey()
    {
        return env('TMDB_API_KEY');
    }

    /**
     * Mengambil trailer YouTube dari TMDB.
     * Prioritas: trailer Indonesia, trailer resmi, lalu trailer lainnya.
     */
   
private function getMovieTrailer($tmdbId)
{
    if (!$tmdbId) {
        return null;
    }

    return Cache::remember(
        'tmdb_movie_trailer_' . $tmdbId,
        21600,
        function () use ($tmdbId) {

            try {
                // Coba bahasa Indonesia terlebih dahulu,
                // kemudian bahasa Inggris.
                foreach (['id-ID', 'en-US'] as $language) {

                    $response = Http::timeout(20)->get(
                        $this->getBaseUrl() . '/movie/' . $tmdbId . '/videos',
                        [
                            'api_key' => $this->getApiKey(),
                            'language' => $language,
                        ]
                    );

                    if (!$response->successful()) {
                        continue;
                    }

                    $videos = collect(
                        $response->json('results', [])
                    )->filter(function ($video) {
                        return ($video['site'] ?? '') === 'YouTube'
                            && ($video['type'] ?? '') === 'Trailer'
                            && !empty($video['key']);
                    });

                    if ($videos->isEmpty()) {
                        continue;
                    }

                    // Prioritaskan trailer resmi.
                    $trailer = $videos->first(function ($video) {
                        return !empty($video['official']);
                    });

                    // Jika tidak ada trailer resmi,
                    // gunakan trailer pertama.
                    $trailer = $trailer ?: $videos->first();

                    return 'https://www.youtube.com/watch?v='
                        . $trailer['key'];
                }

                return null;

            } catch (\Exception $e) {
                report($e);

                return null;
            }
        }
    );
}


    /**
     * Mengambil ID film yang sedang tayang di Indonesia dari TMDB.
     */
    private function getNowPlayingIds()
    {
        return Cache::remember('tmdb_now_playing_ids_id', 1800, function () {
            $ids = [];

            for ($page = 1; $page <= 5; $page++) {
                $response = Http::timeout(15)->get(
                    $this->getBaseUrl() . '/movie/now_playing',
                    [
                        'api_key' => $this->getApiKey(),
                        'language' => 'id-ID',
                        'region' => 'ID',
                        'page' => $page,
                    ]
                );

                if (!$response->successful()) {
                    break;
                }

                foreach ($response->json('results', []) as $movie) {
                    $ids[] = $movie['id'];
                }

                if ($page >= $response->json('total_pages', 1)) {
                    break;
                }
            }

            return $ids;
        });
    }

    /**
     * Mengambil sinopsis film dari TMDB.
     * Prioritas: Indonesia, kemudian Inggris, lalu sinopsis lama.
     */
    private function getMovieDescription($movie, $oldDescription = null)
    {
        $overview = trim($movie['overview'] ?? '');

        if ($overview !== '') {
            return $overview;
        }

        if (!empty($movie['id'])) {
            try {
                $response = Http::timeout(15)->get(
                    $this->getBaseUrl() . '/movie/' . $movie['id'],
                    [
                        'api_key' => $this->getApiKey(),
                        'language' => 'en-US',
                    ]
                );

                if ($response->successful()) {
                    $englishOverview = trim(
                        $response->json('overview') ?? ''
                    );

                    if ($englishOverview !== '') {
                        return $englishOverview;
                    }
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        return $oldDescription ?? '';
    }

    /**
     * Menentukan status film.
     */
    private function getFilmStatus($movie, $nowPlayingIds)
    {
        if (in_array($movie['id'], $nowPlayingIds)) {
            return 'Showing';
        }

        $releaseDate = $movie['release_date'] ?? null;

        if (!$releaseDate) {
            return 'Upcoming';
        }

        try {
            return Carbon::parse($releaseDate)->isFuture()
                ? 'Upcoming'
                : 'Ended';
        } catch (\Exception $e) {
            return 'Upcoming';
        }
    }

    /**
     * Menyimpan atau memperbarui data film dari TMDB.
     */
    private function saveMovie($movie, $nowPlayingIds)
    {
        $title = $movie['title'] ?? null;

        if (!$title || empty($movie['id'])) {
            return;
        }

        $poster = !empty($movie['poster_path'])
            ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
            : null;

        $film = Film::where('tmdb_id', $movie['id'])->first();

        if (!$film) {
            $film = Film::whereRaw(
                'LOWER(title) = ?',
                [mb_strtolower($title)]
            )->first();
        }

        $data = [
            'tmdb_id' => $movie['id'],
            'title' => $title,
            'description' => $this->getMovieDescription(
                $movie,
                $film?->description
            ),
            'duration' => $film->duration ?? 120,
            'poster_url' => $poster ?? $film->poster_url ?? null,
            'status' => $this->getFilmStatus($movie, $nowPlayingIds),
        ];

        // Ambil trailer otomatis hanya jika admin belum mengisinya.
        if (empty($film?->trailer_url)) {
            $trailerUrl = $this->getMovieTrailer($movie['id']);

            if ($trailerUrl) {
                $data['trailer_url'] = $trailerUrl;
            }
        }

        if ($film) {
            $film->update($data);
        } else {
            Film::create($data);
        }
    }

    /**
     * Halaman daftar film dan pencarian.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $status = $request->input('status');

        $films = Film::query()
            ->whereIn('status', ['Showing', 'Upcoming'])
            ->when(
                in_array($status, ['Showing', 'Upcoming']),
                function ($query) use ($status) {
                    $query->where('status', $status);
                }
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(
                    'title',
                    'like',
                    '%' . $search . '%'
                );
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view(
            'customer.films.index',
            compact('films', 'search')
        );
    }

    /**
     * Mengambil data Now Playing dari TMDB.
     */
    public function fetchFromTMDB()
    {
        try {
            $nowPlayingIds = $this->getNowPlayingIds();

            if (empty($nowPlayingIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak berhasil mengambil daftar film dari TMDB.'
                ], 500);
            }

            $count = 0;

            for ($page = 1; $page <= 5; $page++) {
                $response = Http::timeout(20)->get(
                    $this->getBaseUrl() . '/movie/now_playing',
                    [
                        'api_key' => $this->getApiKey(),
                        'language' => 'id-ID',
                        'region' => 'ID',
                        'page' => $page,
                    ]
                );

                if (!$response->successful()) {
                    break;
                }

                foreach ($response->json('results', []) as $movie) {
                    $existing = Film::where(
                        'tmdb_id',
                        $movie['id']
                    )->exists();

                    $this->saveMovie($movie, $nowPlayingIds);

                    if (!$existing) {
                        $count++;
                    }
                }

                if ($page >= $response->json('total_pages', 1)) {
                    break;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil mengambil $count film baru dari TMDB."
            ]);

        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghubungi TMDB.'
            ], 500);
        }
    }

    /**
     * Mengambil data Upcoming dari TMDB.
     */
    public function fetchUpcomingFromTMDB()
    {
        try {
            $count = 0;
            $apiCount = 0;
            $totalResults = 0;

            for ($page = 1; $page <= 5; $page++) {
                $response = Http::timeout(20)->get(
                    $this->getBaseUrl() . '/movie/upcoming',
                    [
                        'api_key' => $this->getApiKey(),
                        'language' => 'id-ID',
                        'region' => 'ID',
                        'page' => $page,
                    ]
                );

                if (!$response->successful()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $response->body()
                    ], 500);
                }

                $movies = $response->json('results', []);
                $totalResults = $response->json('total_results', 0);

                foreach ($movies as $movie) {
                    $apiCount++;

                    if (empty($movie['id']) || empty($movie['title'])) {
                        continue;
                    }

                    $film = Film::where(
                        'tmdb_id',
                        $movie['id']
                    )->first();

                    if ($film && $film->status === 'Showing') {
                        continue;
                    }

                    $filmData = [
                        'title' => $movie['title'],
                        'description' => $this->getMovieDescription(
                            $movie,
                            $film?->description
                        ),
                        'duration' => $film->duration ?? 120,
                        'poster_url' => !empty($movie['poster_path'])
                            ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
                            : ($film->poster_url ?? null),
                        'status' => 'Upcoming',
                    ];

                    // Jangan timpa trailer yang sudah diisi admin.
                    if (empty($film?->trailer_url)) {
                        $trailerUrl = $this->getMovieTrailer($movie['id']);

                        if ($trailerUrl) {
                            $filmData['trailer_url'] = $trailerUrl;
                        }
                    }

                    Film::updateOrCreate(
                        ['tmdb_id' => $movie['id']],
                        $filmData
                    );

                    $count++;
                }

                if ($page >= $response->json('total_pages', 1)) {
                    break;
                }
            }

            return response()->json([
                'status' => 'success',
                'total_film_dari_tmdb' => $totalResults,
                'film_diperiksa' => $apiCount,
                'film_diproses' => $count,
                'total_upcoming_di_database' => Film::where(
                    'status',
                    'Upcoming'
                )->count(),
            ]);

        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman detail film.
     */
    public function show($id)
    {
        $film = Film::findOrFail($id);

        return view('customer.films.show', compact('film'));
    }
}
