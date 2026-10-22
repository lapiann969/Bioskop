<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Admin
        User::create([
            'name' => 'Admin Bioskop',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'admin',
        ]);

        // 2. Membuat Akun Pelanggan (Customer)
        User::create([
            'name' => 'Pelanggan Bioskop',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'customer',
        ]);

        // 3. Memanggil seeder Studio & Kursi yang sudah kamu buat sebelumnya
        $this->call([
            StudioSeeder::class,
        ]);
    }
}