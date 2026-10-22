<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        return view('profile.show', compact('user'));
    }
}
