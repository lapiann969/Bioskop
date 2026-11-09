<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\AuthController; 

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ETicketController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FilmController as AdminFilmController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\SeatController as AdminSeatController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomerController;

>>>>>>> 1bf1292 (membuat controller admin dan route nya)

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.dashboard');

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
=======
Route::middleware('auth')->group(function () {

Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
Route::get('/schedules/{schedule}/seats', [SeatController::class, 'index'])->name('seats.index');
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
Route::get('/bookings/{booking}/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::get('/bookings/{booking}/payment', [PaymentController::class, 'show'])->name('payments.show');
Route::get('/bookings/{booking}/ticket', [ETicketController::class, 'show'])->name('tickets.show');
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('films', AdminFilmController::class);
Route::resource('genres', GenreController::class);
Route::resource('studios', StudioController::class);
Route::resource('seats', AdminSeatController::class);
Route::resource('schedules', AdminScheduleController::class);
Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
});
>>>>>>> 1bf1292 (membuat controller admin dan route nya)
