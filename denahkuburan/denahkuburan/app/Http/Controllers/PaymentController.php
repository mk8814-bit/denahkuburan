<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function confirm(Payment $payment)
    {
        $payment->update(['status' => 'confirmed']);
        return back()->with('success', 'Pembayaran telah dikonfirmasi.');
    }
}
