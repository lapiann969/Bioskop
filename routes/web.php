<?php

use Illuminate\Support\Facades\Route;
use App\Models\Film;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ETicketController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\FilmController as AdminFilmController;
use App\Http\Controllers\Admin\SeatController as AdminSeatController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomerController;

// ==========================================
// 1. PUBLIC ROUTES
// ==========================================

// Home Customer
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Daftar Film
Route::get('/films', [FilmController::class, 'index'])
    ->name('films.index');

// Detail Film
Route::get('/films/{film}', [FilmController::class, 'show'])
    ->name('films.show');

// Jadwal Tayang
Route::get('/schedules', [ScheduleController::class, 'index'])
    ->name('schedules.index');

// Sinkronisasi TMDB
Route::get('/fetch-tmdb', [FilmController::class, 'fetchFromTMDB'])
    ->name('films.fetch-tmdb');

Route::get('/fetch-upcoming', [FilmController::class, 'fetchUpcomingFromTMDB'])
    ->name('films.fetch-upcoming');

Route::post('/xendit/webhook', [BookingController::class, 'webhook'])
    ->name('xendit.webhook');

// ==========================================
// 2. AUTHENTICATION ROUTES
// ==========================================

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// ==========================================
// 3. CUSTOMER ROUTES (WAJIB LOGIN)
// ==========================================

Route::middleware('auth')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])
        ->name('customer.dashboard');

    Route::get('/beranda', [HomeController::class, 'index'])
        ->name('customer.beranda');

    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])
        ->name('schedules.show');

    Route::get('/schedules/{schedule}/seats', [SeatController::class, 'index'])
        ->name('seats.index');

    // Booking & Checkout Xendit
    Route::get('/bookings', [BookingController::class, 'index'])
        ->name('bookings.index');

    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/bookings/{booking}', [BookingController::class, 'show'])
        ->name('bookings.show');

    Route::get('/checkout/{code}', [BookingController::class, 'checkout'])
        ->name('checkout.show');

    Route::get('/checkout/{code}/success', [BookingController::class, 'success'])
        ->name('checkout.success');

    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'show'])
        ->name('payments.show');

    Route::get('/bookings/{booking}/ticket', [ETicketController::class, 'show'])
        ->name('tickets.show');

    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history.index');

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
});

// ==========================================
// 4. ADMIN ROUTES (KHUSUS ADMIN)
// ==========================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('films', AdminFilmController::class);
        Route::resource('genres', GenreController::class);
        Route::resource('studios', StudioController::class);
        Route::resource('seats', AdminSeatController::class);
        Route::resource('schedules', AdminScheduleController::class);

        Route::get('/bookings', [AdminBookingController::class, 'index'])
            ->name('bookings.index');

        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])
            ->name('bookings.show');

        Route::get('/payments', [AdminPaymentController::class, 'index'])
            ->name('payments.index');

        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])
            ->name('payments.show');

        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');

        Route::get('/customers/{customer}', [CustomerController::class, 'show'])
            ->name('customers.show');
    });