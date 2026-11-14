<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Film;

class UpdateMissingFilmDescriptions extends Command
{
    protected $signature = 'app:update-missing-film-descriptions';

    protected $description = 'Mengisi sinopsis film yang masih kosong dari TMDB';

    public function handle()
    {
        $baseUrl = rtrim(env('TMDB_BASE_URL'), '/');
        $apiKey = env('TMDB_API_KEY');

        if (!$baseUrl || !$apiKey) {
            $this->error('TMDB_BASE_URL atau TMDB_API_KEY belum tersedia.');
            return self::FAILURE;
        }

        $films = Film::where('status', 'Showing')
            ->where(function ($query) {
                $query->whereNull('description')
                    ->orWhere('description', '');
            })
            ->whereNotNull('tmdb_id')
            ->get();

        $updated = 0;
        $failed = [];

        $this->info('Total film diperiksa: ' . $films->count());

        foreach ($films as $film) {
            try {
                $this->line('Memeriksa: ' . $film->title);

                // Coba sinopsis bahasa Indonesia.
                $response = Http::timeout(20)->get(
                    $baseUrl . '/movie/' . $film->tmdb_id,
                    [
                        'api_key' => $apiKey,
                        'language' => 'id-ID',
                    ]
                );

                $overview = '';

                if ($response->successful()) {
                    $overview = trim(
                        (string) $response->json('overview')
                    );
                }

                // Jika kosong, coba bahasa Inggris.
                if ($overview === '') {
                    $responseEn = Http::timeout(20)->get(
                        $baseUrl . '/movie/' . $film->tmdb_id,
                        [
                            'api_key' => $apiKey,
                            'language' => 'en-US',
                        ]
                    );

                    if ($responseEn->successful()) {
                        $overview = trim(
                            (string) $responseEn->json('overview')
                        );
                    }
                }

                // Simpan hanya jika sinopsis ditemukan.
                if ($overview !== '') {
                    $film->update([
                        'description' => $overview,
                    ]);

                    $updated++;

                    $this->info('Berhasil: ' . $film->title);
                } else {
                    $failed[] = $film->title;

                    $this->warn('Sinopsis tidak tersedia: ' . $film->title);
                }

            } catch (\Throwable $e) {
                $failed[] = $film->title;

                $this->error(
                    'Gagal: ' . $film->title . ' - ' . $e->getMessage()
                );
            }
        }

        $this->newLine();

        $this->info('===== HASIL SINKRONISASI =====');
        $this->info('Total diperiksa: ' . $films->count());
        $this->info('Berhasil diisi: ' . $updated);
        $this->warn('Masih kosong/gagal: ' . count($failed));

        if (!empty($failed)) {
            $this->table(
                ['Film yang belum memiliki sinopsis'],
                array_map(
                    fn ($title) => [$title],
                    $failed
                )
            );
        }

        return self::SUCCESS;
    }
}
