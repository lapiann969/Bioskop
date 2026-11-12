<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Film;
use App\Models\Studio;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // 1. Menampilkan daftar jadwal (View: index)
    public function index()
    {
        $schedules = Schedule::with(['film', 'studio'])->latest()->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    // 2. Menampilkan form tambah jadwal (View: create)
    public function create()
    {
        $films = Film::all();
        $studios = Studio::all();
        return view('admin.schedules.create', compact('films', 'studios'));
    }

    // 3. Menyimpan data jadwal baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required',
            'studio_id' => 'required',
            'show_date' => 'required|date',
            'show_time' => 'required',
            'price' => 'required|numeric'
        ]);

        // Gabungkan tanggal dan jam
        $waktuTayang = $request->show_date . ' ' . $request->show_time;

        Schedule::create([
            'film_id' => $request->film_id,
            'studio_id' => $request->studio_id,
            'show_time' => $waktuTayang,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // 4. Menampilkan detail satu jadwal (View: show)
    public function show(Schedule $schedule)
    {
        $schedule->load(['film', 'studio']);
        return view('admin.schedules.show', compact('schedule'));
    }

    // 5. Menampilkan form edit jadwal (View: edit)
    public function edit(Schedule $schedule)
    {
        $films = Film::all();
        $studios = Studio::all();
        return view('admin.schedules.edit', compact('schedule', 'films', 'studios'));
    }

    // 6. Menyimpan perubahan data jadwal ke database
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'film_id' => 'required',
            'studio_id' => 'required',
            'show_date' => 'required|date',
            'show_time' => 'required',
            'price' => 'required|numeric'
        ]);

        // Gabungkan kembali tanggal dan jam yang diedit
        $waktuTayang = $request->show_date . ' ' . $request->show_time;

        $schedule->update([
            'film_id' => $request->film_id,
            'studio_id' => $request->studio_id,
            'show_time' => $waktuTayang,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    // 7. Menghapus jadwal
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}