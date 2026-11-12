<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Booking; // Pastikan model Booking dipanggil jika ada tabel transaksi
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index($schedule_id)
    {
        // Ambil data jadwal beserta film dan studionya
        $schedule = Schedule::with(['film', 'studio'])->findOrFail($schedule_id);
        
        // Ambil semua kursi yang ada di studio tersebut
        $seats = Seat::where('studio_id', $schedule->studio_id)->get();

        // (Opsional) Ambil ID kursi yang sudah dipesan pada jadwal ini agar tidak bisa diklik lagi
        // $bookedSeats = Booking::where('schedule_id', $schedule_id)->pluck('seat_id')->toArray();
        $bookedSeats = []; // Sementara dikosongkan jika tabel relasi booking belum siap

        return view('customer.seats.index', compact('schedule', 'seats', 'bookedSeats'));
    }
}