<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        abort_unless(
            $booking->user_id === auth()->id(),
            403
        );

        $booking->load('payment', 'schedule.film');

        return view('payments.show', compact('booking'));
    }
}
