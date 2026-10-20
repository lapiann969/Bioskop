
<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['film', 'studio'])
            ->whereHas('film', function ($query) {
                $query->where('status', 'Showing');
            })
            ->orderBy('show_time')
            ->get();

        return view('schedules.index', compact('schedules'));
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['film', 'studio']);

        return view('schedules.show', compact('schedule'));
    }
}
