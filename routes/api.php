<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Customer\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/payment-methods', [PaymentController::class, 'PaymentMethods']);

Route::get('/duitku/payment-methods', [PaymentController::class, 'PaymentMethods'])->name('duitku.payment-methods');
