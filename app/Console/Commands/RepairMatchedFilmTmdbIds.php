<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Film;

class RepairMatchedFilmTmdbIds extends Command
{
    protected $signature = 'app:repair-matched-film-tmdb-ids';

    protected $description = 'Memulihkan TMDB ID berdasarkan kecocokan poster';

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

        $updated = 0;
        $skipped = 0;

        foreach ($films as $film) {
            $this->newLine();
            $this->info('Memeriksa: ' . $film->title);

            $posterPath = basename(
                parse_url($film->poster_url ?? '', PHP_URL_PATH) ?: ''
            );

            if ($posterPath === '') {
                $this->warn('Poster tidak tersedia. Dilewati.');
                $skipped++;
                continue;
            }

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
                        continue;
                    }

                    foreach ($response->json('results', []) as $movie) {
                        if (!empty($movie['id'])) {
                            $candidates[$movie['id']] = $movie;
                        }
                    }
                } catch (\Throwable $e) {
                    $this->error($e->getMessage());
                }
            }

            $matched = null;

            foreach ($candidates as $movie) {
                $candidatePoster = basename(
                    $movie['poster_path'] ?? ''
                );

                if (
                    $candidatePoster !== ''
                    && $candidatePoster === $posterPath
                ) {
                    $matched = $movie;
                    break;
                }
            }

            if (!$matched) {
                $this->warn('Poster tidak cocok. Tidak diperbarui.');
                $skipped++;
                continue;
            }

            $tmdbId = $matched['id'];

            $duplicate = Film::where('tmdb_id', $tmdbId)
                ->where('id', '!=', $film->id)
                ->exists();

            if ($duplicate) {
                $this->warn('TMDB ID sudah digunakan film lain.');
                $skipped++;
                continue;
            }

            $detailResponse = Http::timeout(20)->get(
                $baseUrl . '/movie/' . $tmdbId,
                [
                    'api_key' => $apiKey,
                    'language' => 'id-ID',
                ]
            );

            $overview = '';

            if ($detailResponse->successful()) {
                $overview = trim(
                    (string) $detailResponse->json('overview')
                );
            }

            if ($overview === '') {
                $englishResponse = Http::timeout(20)->get(
                    $baseUrl . '/movie/' . $tmdbId,
                    [
                        'api_key' => $apiKey,
                        'language' => 'en-US',
                    ]
                );

                if ($englishResponse->successful()) {
                    $overview = trim(
                        (string) $englishResponse->json('overview')
                    );
                }
            }

            $this->table(
                ['Data', 'Nilai'],
                [
                    ['Judul', $film->title],
                    ['TMDB ID', $tmdbId],
                    ['Judul TMDB', $matched['title'] ?? '-'],
                    ['Sinopsis ditemukan', $overview !== '' ? 'Ya' : 'Tidak'],
                ]
            );

            if (!$this->confirm('Apakah data ini cocok dan boleh diperbarui?', false)) {
                $this->warn('Dilewati atas pilihan pengguna.');
                $skipped++;
                continue;
            }

            $data = [
                'tmdb_id' => $tmdbId,
            ];

            if (trim((string) $film->description) === '' && $overview !== '') {
                $data['description'] = $overview;
            }

            $film->update($data);

            $this->info('Berhasil diperbarui: ' . $film->title);
            $updated++;
        }

        $this->newLine();
        $this->info('===== HASIL PERBAIKAN =====');
        $this->info('Berhasil diperbarui: ' . $updated);
        $this->warn('Dilewati: ' . $skipped);

        return self::SUCCESS;
    }
}
