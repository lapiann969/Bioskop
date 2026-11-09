<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Studio;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index()
    {
        $seats = Seat::with('studio')->latest()->paginate(10);

        return view('admin.seats.index', compact('seats'));
    }

    public function create()
    {
        $studios = Studio::all();

        return view('admin.seats.create', compact('studios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'seat_number' => 'required|string|max:255',
        ]);

        Seat::create($validated);

        return redirect()->route('admin.seats.index')
            ->with('success', 'Kursi berhasil ditambahkan.');
    }

    public function show(Seat $seat)
    {
        $seat->load('studio');

        return view('admin.seats.show', compact('seat'));
    }

    public function edit(Seat $seat)
    {
        $studios = Studio::all();

        return view('admin.seats.edit', compact('seat', 'studios'));
    }

    public function update(Request $request, Seat $seat)
    {
        $validated = $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'seat_number' => 'required|string|max:255',
        ]);

        $seat->update($validated);

        return redirect()->route('admin.seats.index')
            ->with('success', 'Kursi berhasil diperbarui.');
    }

    public function destroy(Seat $seat)
    {
        $seat->delete();

        return redirect()->route('admin.seats.index')
            ->with('success', 'Kursi berhasil dihapus.');
    }
}
