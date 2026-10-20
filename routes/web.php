<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home.dashboard');
Route::get('/fetch-tmdb', [FilmController::class, 'fetchFromTMDB'])->name('films.fetch-tmdb');
