<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Untuk memanggil API eksternal
use App\Models\Film; // Memanggil model Film

class FilmController extends Controller
{
    public function fetchFromTMDB()
    {
        $apiKey = env('TMDB_API_KEY');
        $baseUrl = env('TMDB_BASE_URL');

        // Memanggil API TMDB untuk daftar film yang sedang tayang
        $response = Http::get("{$baseUrl}/movie/now_playing", [
            'api_key' => $apiKey,
            'language' => 'id-ID', // Meminta sinopsis dalam Bahasa Indonesia
            'page' => 1
        ]);

        if ($response->successful()) {
            $movies = $response->json()['results'];
            
            // Membatasi hanya mengambil 10 film pertama
            $movies = array_slice($movies, 0, 10);
            $count = 0;

            foreach ($movies as $movie) {
                // Mencegah duplikasi data film yang sama
                $existingFilm = Film::where('title', $movie['title'])->first();

                if (!$existingFilm) {
                    Film::create([
                        'title' => $movie['title'],
                        'description' => $movie['overview'],
                        'duration' => 120, // Set default 120 menit karena API ini tidak memberikan durasi
                        'poster_url' => 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'],
                        'status' => 'Showing'
                    ]);
                    $count++;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil menyimpan $count film baru dari TMDB ke database!"
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Gagal terhubung ke TMDB API'], 500);
    }
}