<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Film;

class CheckMissingFilmTmdbIds extends Command
{
    protected $signature = 'app:check-missing-film-tmdb-ids';

    protected $description = 'Memeriksa kecocokan film tanpa TMDB ID';

    public function handle()
    {
        $baseUrl = rtrim(env('TMDB_BASE_URL'), '/');
        $apiKey = env('TMDB_API_KEY');

        if (!$baseUrl || !$apiKey) {
            $this->error('Konfigurasi TMDB belum tersedia.');
            return self::FAILURE;
        }

        $films = Film::where('status', 'Showing')
            ->whereNull('tmdb_id')
            ->get();

        if ($films->isEmpty()) {
            $this->info('Tidak ada film tanpa TMDB ID.');
            return self::SUCCESS;
        }

        $this->info('Total film diperiksa: ' . $films->count());

        foreach ($films as $film) {
            $this->newLine();
            $this->info('Film database: ' . $film->title);

            $existingPoster = basename(
                parse_url($film->poster_url ?? '', PHP_URL_PATH) ?: ''
            );

            $candidates = [];

            foreach (['id-ID', 'en-US'] as $language) {
                try {
                    $response = Http::timeout(20)->get(
                        $baseUrl . '/search/movie',
                        [
                            'api_key' => $apiKey,
                            'language' => $language,
                            'query' => $film->title,
                            'include_adult' => false,
                        ]
                    );

                    if (!$response->successful()) {
                        $this->warn(
                            'Pencarian ' . $language . ' gagal.'
                        );
                        continue;
                    }

                    foreach ($response->json('results', []) as $movie) {
                        if (empty($movie['id'])) {
                            continue;
                        }

                        $candidates[$movie['id']] = $movie;
                    }
                } catch (\Throwable $e) {
                    $this->error($e->getMessage());
                }
            }

            if (empty($candidates)) {
                $this->warn('Tidak ditemukan kandidat TMDB.');
                continue;
            }

            $rows = [];

            foreach (array_slice($candidates, 0, 10, true) as $movie) {
                $posterPath = basename($movie['poster_path'] ?? '');

                $posterMatch = $existingPoster !== ''
                    && $posterPath !== ''
                    && $existingPoster === $posterPath;

                $rows[] = [
                    $movie['id'],
                    $movie['title'] ?? '-',
                    $movie['release_date'] ?? '-',
                    $posterMatch ? 'COCOK' : 'Tidak cocok',
                ];
            }

            $this->table(
                ['TMDB ID', 'Judul TMDB', 'Tanggal rilis', 'Poster'],
                $rows
            );
        }

        $this->newLine();
        $this->info('Pemeriksaan selesai. Database tidak diubah.');

        return self::SUCCESS;
    }
}
