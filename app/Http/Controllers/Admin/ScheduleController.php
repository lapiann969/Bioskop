<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Film;
use App\Models\Studio;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['film', 'studio'])
            ->latest('show_time')
            ->paginate(10);

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $films = Film::all();
        $studios = Studio::where('status', 'Active')->get();

        return view('admin.schedules.create', compact('films', 'studios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'show_time' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['film', 'studio']);

        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $films = Film::all();
        $studios = Studio::where('status', 'Active')->get();

        return view('admin.schedules.edit', compact(
            'schedule',
            'films',
            'studios'
        ));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'show_time' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
