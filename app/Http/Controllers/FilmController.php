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
     * Menentukan status film (Showing, Upcoming, atau Ended).
     */
    private function getFilmStatus($movie, $nowPlayingIds)
    {
        // Jika ada di daftar tayang TMDB saat ini
        if (in_array($movie['id'], $nowPlayingIds)) {
            return 'Showing';
        }

        $releaseDate = $movie['release_date'] ?? null;

        if (!$releaseDate) {
            return 'Upcoming';
        }

        try {
            // Jika tanggal rilis masih di masa depan
            return Carbon::parse($releaseDate)->isFuture()
                ? 'Upcoming'
                : 'Ended';
        } catch (\Exception $e) {
            return 'Upcoming';
        }
    }

    /**
     * Menyimpan atau memperbarui data film dari TMDB ke database lokal.
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
            'duration' => 120, // Default durasi
            'poster_url' => $poster,
            'status' => $this->getFilmStatus($movie, $nowPlayingIds),
        ];

        $film = Film::where('tmdb_id', $movie['id'])->first();

        // Cek duplikasi berdasarkan judul jika tmdb_id tidak ketemu
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
     * Halaman daftar film dan pencarian untuk pelanggan.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        // 1. Jika ada kata kunci pencarian, tarik dulu datanya dari TMDB
        if ($search !== '') {
            try {
                $response = Http::timeout(20)->get(
                    $this->getBaseUrl() . '/search/movie',
                    [
                        'api_key' => $this->getApiKey(),
                        // Kita lepas limit bahasa agar pencarian huruf Jepang/Romawi lebih luas
                        'query' => $search,
                        'include_adult' => false,
                        'page' => 1,
                    ]
                );

                if ($response->successful()) {
                    $movies = $response->json('results', []);
                    $nowPlayingIds = $this->getNowPlayingIds();

                    foreach ($movies as $movie) {
                        $this->saveMovie($movie, $nowPlayingIds);
                    }
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        // 2. Ambil data film dari database lokal
        $films = Film::query()
            // HANYA tampilkan film yang sedang tayang (Showing) atau akan datang (Upcoming)
            ->whereIn('status', ['Showing', 'Upcoming']) 
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('customer.films.index', compact('films', 'search'));
    }

    /**
     * Tombol manual untuk mengambil data Now Playing dari TMDB (biasanya untuk Admin).
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
                    $existing = Film::where('tmdb_id', $movie['id'])->exists();
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
}