<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::latest()->paginate(10);

        return view('admin.studios.index', compact('studios'));
    }

    public function create()
    {
        return view('admin.studios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:Active,Maintenance',
        ]);

        Studio::create($validated);

        return redirect()->route('admin.studios.index')
            ->with('success', 'Studio berhasil ditambahkan.');
    }

    public function show(Studio $studio)
    {
        return view('admin.studios.show', compact('studio'));
    }

    public function edit(Studio $studio)
    {
        return view('admin.studios.edit', compact('studio'));
    }

    public function update(Request $request, Studio $studio)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:Active,Maintenance',
        ]);

        $studio->update($validated);

        return redirect()->route('admin.studios.index')
            ->with('success', 'Studio berhasil diperbarui.');
    }

    public function destroy(Studio $studio)
    {
        $studio->delete();

        return redirect()->route('admin.studios.index')
            ->with('success', 'Studio berhasil dihapus.');
    }
}
