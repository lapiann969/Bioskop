<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
// use App\Models\Booking; // Dimatikan sementara sampai tabelnya siap
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index($scheduleId)
    {
        // 1. Ambil data jadwal, film, dan studio
        $schedule = Schedule::with(['film', 'studio'])->findOrFail($scheduleId);

        // 2. Matikan sementara query Booking agar tidak error SQL (karena tabel belum siap)
        $bookedSeats = []; 
        
        /* Nanti kita aktifkan ini kalau tabel bookings sudah kamu buat
        if (class_exists(\App\Models\Booking::class)) {
            $bookedSeats = \App\Models\Booking::where('schedule_id', $scheduleId)
                                  ->pluck('seat_number')
                                  ->toArray();
        }
        */

        // 3. Buat data kursi (A1 - E10) buatan agar sesuai dengan kode View (blade) kamu
        $seats = [];
        $rows = ['A', 'B', 'C', 'D', 'E'];
        foreach ($rows as $row) {
            for ($i = 1; $i <= 10; $i++) {
                $seats[] = (object) [
                    'id' => $row . $i,
                    'seat_number' => $row . $i
                ];
            }
        }

        // Kirim $seats ke view milikmu
        return view('customer.seats.index', compact('schedule', 'bookedSeats', 'seats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'selected_seats' => 'required|array'
        ]);

        // Ini hanya untuk tes apakah kursi yang dipilih berhasil ditangkap
        return back()->with('success', 'Berhasil memilih kursi: ' . implode(', ', $request->selected_seats));
    }
}