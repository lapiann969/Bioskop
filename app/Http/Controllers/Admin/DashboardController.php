<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFilms = Film::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalBookings = Booking::count();
        $totalPayments = Payment::count();

        return view('admin.dashboard', compact(
            'totalFilms',
            'totalCustomers',
            'totalBookings',
            'totalPayments'
        ));
    }
}
