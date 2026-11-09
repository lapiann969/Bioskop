<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'schedule.film', 'payment'])
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user',
            'schedule.film',
            'schedule.studio',
            'bookingDetails.seat',
            'payment',
        ]);

        return view('admin.bookings.show', compact('booking'));
    }
}
