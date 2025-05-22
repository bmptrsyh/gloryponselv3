<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Customer\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/payment-methods', [PaymentController::class, 'getPaymentMethods']);

Route::put('/customer/{id}', [ProfileController::class, 'update'])->name('customer.update.api');
