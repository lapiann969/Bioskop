<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index()
    {
        $films = Film::latest()->paginate(10);

        return view('admin.films.index', compact('films'));
    }

    public function create()
    {
        return view('admin.films.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'poster_url' => 'nullable|url|max:2048',
            'status' => 'required|in:Upcoming,Showing,Ended',
        ]);

        Film::create($validated);

        return redirect()
            ->route('admin.films.index')
            ->with('success', 'Film berhasil ditambahkan.');
    }

    public function show(Film $film)
    {
        return view('admin.films.show', compact('film'));
    }

    public function edit(Film $film)
    {
        return view('admin.films.edit', compact('film'));
    }

    public function update(Request $request, Film $film)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'poster_url' => 'nullable|url|max:2048',
            'status' => 'required|in:Upcoming,Showing,Ended',
        ]);

        $film->update($validated);

        return redirect()
            ->route('admin.films.index')
            ->with('success', 'Film berhasil diperbarui.');
    }

    public function destroy(Film $film)
    {
        $film->delete();

        return redirect()
            ->route('admin.films.index')
            ->with('success', 'Film berhasil dihapus.');
    }
}
