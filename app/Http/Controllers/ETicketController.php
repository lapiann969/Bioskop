<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class ETicketController extends Controller
{
    public function show(Booking $booking)
    {
        abort_unless(
            $booking->user_id === auth()->id(),
            403
        );

        abort_unless($booking->status === 'Paid', 404);

        $booking->load([
            'schedule.film',
            'schedule.studio',
            'bookingDetails.seat',
        ]);

        return view('e-tickets.show', compact('booking'));
    }
}
