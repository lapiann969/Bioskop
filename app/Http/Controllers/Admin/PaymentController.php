<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with([
            'booking.user',
            'booking.schedule.film',
        ])->latest()->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'booking.user',
            'booking.schedule.film',
        ]);

        return view('admin.payments.show', compact('payment'));
    }
}
