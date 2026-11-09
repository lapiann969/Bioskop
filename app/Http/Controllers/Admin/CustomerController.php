<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        $customer->load('bookings');

        return view('admin.customers.show', compact('customer'));
    }
}
