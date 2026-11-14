<?php

namespace App\Http\Controllers;

use App\Models\Film;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
{
    $showingFilms = Film::where('status', 'Showing')
        ->latest()
        ->take(10)
        ->get();

    $upcomingFilms = Film::where('status', 'Upcoming')
        ->latest()
        ->take(10)
        ->get();

    return view('customer.home', compact(
        'showingFilms',
        'upcomingFilms'
    ));
}
}
