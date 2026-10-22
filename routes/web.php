<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\AuthController; // Tambahkan pemanggilan AuthController

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.dashboard');

// Route Penarikan Data API TMDB
Route::get('/fetch-tmdb', [FilmController::class, 'fetchFromTMDB'])->name('films.fetch-tmdb');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/admin/dashboard', function () { 
    return 'Selamat datang Admin! Ini halaman khusus admin.'; 
})->name('admin.dashboard');

Route::get('/beranda', function () { 
    return 'Selamat datang Pelanggan! Silakan pesan tiket bioskop di sini.'; 
})->name('customer.dashboard');