<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getPaymentMethods(Request $request)
    {
        $paymentMethods = [
            ['name' => 'BNI VA', 'value' => 'bni_va'],
            ['name' => 'OVO', 'value' => 'ovo'],
            ['name' => 'Mandiri VA', 'value' => 'mandiri_va'],
            ['name' => 'BCA VA', 'value' => 'bca_va'],
            ['name' => 'ShopeePay', 'value' => 'shopeepay'],
            ['name' => 'COD', 'value' => 'cod'],
        ];

        return response()->json($paymentMethods);
    }
}