<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalFilms = Film::count();

        $totalCustomers = User::where('role', 'customer')->count();

        $totalBookings = Booking::count();

        $totalPayments = Payment::count();

        // Grafik pemesanan 7 hari terakhir
        $bookingLabels = [];
        $bookingData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $bookingLabels[] = $date->format('d/m');

            $bookingData[] = Booking::whereDate(
                'created_at',
                $date->toDateString()
            )->count();
        }

        // Grafik status pembayaran
        $paymentStatus = Payment::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $paymentLabels = ['Success', 'Pending', 'Failed'];

        $paymentData = [
            (int) ($paymentStatus['Success'] ?? 0),
            (int) ($paymentStatus['Pending'] ?? 0),
            (int) ($paymentStatus['Failed'] ?? 0),
        ];

        return view('admin.dashboard', compact(
            'totalFilms',
            'totalCustomers',
            'totalBookings',
            'totalPayments',
            'bookingLabels',
            'bookingData',
            'paymentLabels',
            'paymentData'
        ));
    }
}
