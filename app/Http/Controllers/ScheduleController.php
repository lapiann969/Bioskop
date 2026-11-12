<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $film = null;
        
        // Query dasar: hanya mengambil jadwal dari film yang berstatus 'Showing'
        $query = Schedule::with(['film', 'studio'])
            ->whereHas('film', function ($q) {
                $q->where('status', 'Showing');
            })
            ->orderBy('show_time');

        // Menangkap parameter film_id dari URL (saat tombol Pesan Tiket diklik)
        if ($request->filled('film_id')) {
            $film = Film::findOrFail($request->film_id);
            $query->where('film_id', $film->id);
        }

        $schedules = $query->get();

        // Mengarahkan dengan benar ke folder customer/schedules/index.blade.php
        return view('customer.schedules.index', compact('schedules', 'film'));
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['film', 'studio']);

        // Mengarahkan pelanggan langsung ke rute halaman pilih kursi (Seat)
        return redirect()->route('seats.index', $schedule->id);
    }
}