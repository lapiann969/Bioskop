<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio;
use App\Models\Seat;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar studio
        $studios = ['Studio 1', 'Studio 2', 'Studio VIP'];
        
        // Baris A-E (5 baris) x 10 kolom = 50 kursi
        $rows = ['A', 'B', 'C', 'D', 'E'];

        foreach ($studios as $studioName) {
            // 1. Buat data Studio lengkap dengan kapasitasnya
            $studio = Studio::create([
                'name' => $studioName,
                'capacity' => 50, // Tambahkan baris ini
            ]);

            foreach ($rows as $row) {
                for ($i = 1; $i <= 10; $i++) {
                    Seat::create([
                        'studio_id' => $studio->id,
                        'seat_number' => $row . $i,
                    ]);
                }
            }
        }
    }
}