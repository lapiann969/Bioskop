<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $films = Film::where('status', 'Showing')
            ->latest()
            ->get();

        return view('customer.home', compact('films'));
    }
}
