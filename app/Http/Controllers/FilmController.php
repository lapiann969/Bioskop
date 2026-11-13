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
     * Mengambil ID film yang sedang tayang di Indonesia.
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
     * Menyimpan film dari TMDB ke database.
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

        $data = [
            'tmdb_id' => $movie['id'],
            'title' => $title,
            'description' => $movie['overview'] ?? '',
            'duration' => 120,
            'poster_url' => $poster,
            'status' => $this->getFilmStatus(
                $movie,
                $nowPlayingIds
            ),
        ];

        $film = Film::where('tmdb_id', $movie['id'])->first();

        // Menghindari duplikasi film yang sudah ada sebelumnya.
        if (!$film) {
            $film = Film::whereRaw(
                'LOWER(title) = ?',
                [mb_strtolower($title)]
            )->first();
        }

        if ($film) {
            $film->update($data);
        } else {
            Film::create($data);
        }
    }

    /**
     * Pencarian dan daftar film.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        // Jika pengguna mencari film, cari juga langsung ke TMDB.
        if ($search !== '') {

            try {

                $response = Http::timeout(20)->get(
                    $this->getBaseUrl() . '/search/movie',
                    [
                        'api_key' => $this->getApiKey(),
                        'language' => 'id-ID',
                        'region' => 'ID',
                        'query' => $search,
                        'include_adult' => false,
                        'page' => 1,
                    ]
                );

                if ($response->successful()) {

                    $movies = $response->json('results', []);

                    $nowPlayingIds = $this->getNowPlayingIds();

                    foreach ($movies as $movie) {
                        $this->saveMovie(
                            $movie,
                            $nowPlayingIds
                        );
                    }
                }

            } catch (\Exception $e) {

                report($e);

            }
        }

        // Ambil film dari database lokal.
        $films = Film::query()
            ->whereIn('status', ['Showing', 'Upcoming'])
            ->when($search !== '', function ($query) use ($search) {

                $query->where('title', 'like', '%' . $search . '%');

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
     * Mengambil film yang sedang tayang dari TMDB.
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
     * Menampilkan detail film.
     */
    public function show(Film $film)
    {
        return view('customer.films.show', compact('film'));
    }
}
