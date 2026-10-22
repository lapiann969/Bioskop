<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['schedule.film', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_unless(
            $booking->user_id === auth()->id(),
            403
        );

        $booking->load([
            'schedule.film',
            'schedule.studio',
            'bookingDetails.seat',
            'payment',
        ]);

        return view('bookings.show', compact('booking'));
    }
}
