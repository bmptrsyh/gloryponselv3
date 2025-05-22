<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileControllerAdmin;
use App\Http\Controllers\Customer\Auth\AuthController;
use App\Http\Controllers\Customer\KeranjangController;
use App\Http\Controllers\Customer\BeliPonselController;
use App\Http\Controllers\Customer\Auth\GoogleController;
use App\Http\Controllers\Customer\Auth\FacebookController;
use App\Http\Controllers\Customer\ProfileControllerCustomer;
use App\Http\Controllers\Customer\Auth\OTPResetPasswordController;
use App\Http\Controllers\Admin\PonselController as AdminPonselController;
use App\Http\Controllers\Customer\PonselController as CustomerPonselController;


Broadcast::routes(['middleware' => ['auth:web']]); // untuk customer
Broadcast::routes(['middleware' => ['auth:admin']]); // untuk admin


Route::get('/customer/profile', [ProfileControllerCustomer::class, 'index'])->name('customer.profile')->middleware('auth:web');
Route::put('/customer/profil/upload/{id}', [ProfileControllerCustomer::class, 'upload'])
    ->name('customer.profil.upload')
    ->middleware('auth:web');
Route::put('/customer/update/{id}', [ProfileControllerCustomer::class, 'update'])->name('customer.profil.update');
Route::get('/admin/profile', [ProfileControllerAdmin::class, 'index'])->name('admin.profile');
Route::put('/admin/profile/upload', [ProfileControllerAdmin::class, 'upload'])->name('admin.profil.upload');
Route::put('/admin/profile/update', [ProfileControllerAdmin::class, 'update'])->name('admin.profil.update');


Route::get('/adminInbox/{id}', [InboxController::class, 'adminInbox'])->name('admin.inbox');
Route::get('/customer/inbox', [InboxController::class, 'customerInbox'])->name('customer.inbox');
Route::post('/send', [InboxController::class, 'send'])->name('send.inbox');
Route::post('/admin/send', [InboxController::class, 'sendAdmin'])->name('admin.send.inbox')->middleware('auth:admin');
Route::get('/listInbox', [InboxController::class, 'listInbox'])->name('listInbox');

// routes/web.php
Route::post('/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('payment.methods');

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');

Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');




// Autentikasi
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')->name('register.submit');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(GoogleController::class)->group(function() {
    Route::get('/auth/google', 'redirectToGoogle')->name('google.login');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});
Route::controller(FacebookController::class)->group(function() {
    Route::get('/auth/facebook', 'redirectToFacebook')->name('facebook.login');
    Route::get('/auth/facebook/callback', 'handleFacebookCallback');
});

Route::middleware('auth:web')->group(function () {
    // Route untuk produk
    Route::prefix('produk')->name('produk.')->group(function () {
        Route::get('/', [CustomerPonselController::class, 'index'])->name('index');
        Route::get('/{id}', [CustomerPonselController::class, 'show'])->name('show');
    });

    // Route untuk transaksi dan beli ponsel
    Route::post('/beli-ponsel/{id}', [BeliPonselController::class, 'beliPonsel'])->name('beli.ponsel');
    Route::get('/transaksi', [BeliPonselController::class, 'transaksi'])->name('transaksi.index');
});


// Dashboard Admin
// Route::middleware('auth:admin')->group(function () {
//     Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
//     Route::get('/admin/produk', [DashboardController::class, 'produk'])->name('produk.admin');
//     Route::get('/ponsel/create', [PonselController::class, 'index'])->name('ponsel.create');
//     Route::post('/ponsel', [PonselController::class, 'store'])->name('ponsel.store');
//     Route::put('/ponsel/{id}', [PonselController::class, 'update'])->name('ponsel.update');
//     Route::get('/ponsel/{id}/edit', [PonselController::class, 'edit'])->name('ponsel.edit');
// });

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    // Gunakan resource controller untuk ponsel
    Route::resource('ponsel', AdminPonselController::class)->names('ponsel');
    Route::delete('/ponsel/{id}/force-delete', [AdminPonselController::class, 'forceDelete'])->name('ponsel.forceDelete');
    Route::patch('/ponsel/{id}/restore', [AdminPonselController::class, 'restore'])->name('ponsel.restore');
    // Tampilkan semua ponsel yang sudah di-soft delete
    Route::get('/admin/ponsel/softdelete', [AdminPonselController::class, 'showSoftDeleted'])->name('ponsel.softdelete');


    Route::get('/transaksi', [AdminPonselController::class, 'semuaTransaksi'])->name('ponsel.transaksi');


});

// Lupa & Reset Password
Route::controller(OTPResetPasswordController::class)->group(function () {
    Route::get('forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('forgot-password', 'sendOTP')->name('password.otp.send');

    Route::get('verify-otp', function () {
        return view('auth.verify-otp');
    })->name('password.otp.verify.form');

    Route::post('verify-otp', 'verifyOTP')->name('password.otp.verify');

    Route::get('/reset-password', function () {
        return view('auth.reset-password', ['email' => request()->query('email')]);
    })->name('password.reset.form');

    Route::post('reset-password', 'resetPassword')->name('password.update');
});