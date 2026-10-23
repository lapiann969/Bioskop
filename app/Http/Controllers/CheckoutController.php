<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class CheckoutController extends Controller
{
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
        ]);

        return view('checkout.show', compact('booking'));
    }
}
