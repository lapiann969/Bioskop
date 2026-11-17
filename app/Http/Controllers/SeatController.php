<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Seat;

class SeatController extends Controller
{
    public function index($scheduleId)
    {
        // Mengambil jadwal, film, dan studio
        $schedule = Schedule::with(['film', 'studio'])
            ->findOrFail($scheduleId);

        // Mengambil kursi asli berdasarkan studio jadwal
        $seats = Seat::where('studio_id', $schedule->studio_id)
            ->orderBy('seat_number')
            ->get();

        // Mengambil kursi yang sudah dipesan
        $bookedSeats = Booking::where('schedule_id', $scheduleId)
            ->whereIn('status', ['Pending', 'Paid'])
            ->get(['seats'])
            ->flatMap(function ($booking) {
                return $booking->seats ?? [];
            })
            ->unique()
            ->values()
            ->toArray();

        return view('customer.seats.index', compact(
            'schedule',
            'bookedSeats',
            'seats'
        ));
    }
}
