<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class HistoryController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['schedule.film', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('history.index', compact('bookings'));
    }
}
