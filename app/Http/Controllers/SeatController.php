
<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class SeatController extends Controller
{
    public function index(Schedule $schedule)
    {
        $schedule->load('studio.seats');

        $seats = $schedule->studio->seats;

        return view('seats.index', compact('schedule', 'seats'));
    }
}
