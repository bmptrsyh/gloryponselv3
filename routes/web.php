<?php

use App\Http\Controllers\Admin\AdminJualPonselController;
use App\Http\Controllers\Admin\AdminKreditController;
use App\Http\Controllers\Admin\AdminTukarTambahController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PonselController as AdminPonselController;
use App\Http\Controllers\Admin\ProfileControllerAdmin;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Customer\Auth\AuthController;
use App\Http\Controllers\Customer\Auth\FacebookController;
use App\Http\Controllers\Customer\Auth\GoogleController;
use App\Http\Controllers\Customer\Auth\OTPResetPasswordController;
use App\Http\Controllers\Customer\BeliPonselController;
use App\Http\Controllers\Customer\CallbackController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\JualPonselController;
use App\Http\Controllers\Customer\KeranjangController;
use App\Http\Controllers\Customer\KreditController;
use App\Http\Controllers\Customer\PonselController as CustomerPonselController;
use App\Http\Controllers\Customer\ProfileControllerCustomer;
use App\Http\Controllers\Customer\TukarTambahController;
use App\Http\Controllers\OngkirController;
use App\Http\Controllers\UlasanController;
use App\Models\Admin;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// Broadcast (Real-time Chat)
Broadcast::routes(['middleware' => ['auth:web']]);
Broadcast::routes(['middleware' => ['auth:admin']]);

// Payment Callback
Route::post('callback/payment', [CallbackController::class, 'paymentCallback']);
Route::get('callback/return', [CallbackController::class, 'myReturnCallback']);

Route::middleware('auth:web')->group(function () {
    Route::get('/customer/profile', [ProfileControllerCustomer::class, 'index'])->name('customer.profile');
    Route::put('/customer/profil/upload/{id}', [ProfileControllerCustomer::class, 'upload'])->name('customer.profil.upload');
    Route::put('/customer/update/{id}', [ProfileControllerCustomer::class, 'update'])->name('customer.profil.update');
});

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autentikasi
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')->name('register.submit');
    Route::post('/logout', 'logout')->name('logout');
});

// Login Google & Facebook
Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google', 'redirectToGoogle')->name('google.login');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});
Route::controller(FacebookController::class)->group(function () {
    Route::get('/auth/facebook', 'redirectToFacebook')->name('facebook.login');
    Route::get('/auth/facebook/callback', 'handleFacebookCallback');
});

// Customer Authenticated Routes
Route::middleware('auth:web')->group(function () {

    Route::post('ajukan-kredit/{id_produk}', [KreditController::class, 'ajukanKredit'])->name('ajukan.kredit');
    Route::post('kredit/step1', [KreditController::class, 'step1Post'])->name('kredit.step1.post');
    Route::post('kredit/step2', [KreditController::class, 'dataPribadi'])->name('kredit.step2.post');
    Route::post('kredit/step3', [KreditController::class, 'uploadDokumen'])->name('kredit.step3.post');
    Route::post('kredit/submit', [KreditController::class, 'submitPengajuan'])->name('kredit.submit');
    Route::get('/kredit/success/{id}', [KreditController::class, 'success'])->name('customer.kredit.success');

    // Produk
    Route::prefix('produk')->name('produk.')->group(function () {
        Route::get('/', [CustomerPonselController::class, 'index'])->name('index');
        Route::get('/{id}', [CustomerPonselController::class, 'show'])->name('show');
    });

    // Transaksi
    Route::post('/beli-ponsel', [BeliPonselController::class, 'beliPonsel'])->name('beli.ponsel');
    Route::get('/transaksi', [BeliPonselController::class, 'transaksi'])->name('transaksi.index');

    // Jual Ponsel & Tukar Tambah
    Route::get('/jual-ponsel', [JualPonselController::class, 'create'])->name('jual.ponsel.create');
    Route::post('/jual-ponsel', [JualPonselController::class, 'store'])->name('jual.ponsel.store');
    Route::get('/tukar-tambah/{id}', [TukarTambahController::class, 'create'])->name('tukar.tambah.create');
    Route::post('/tukar-tambah', [TukarTambahController::class, 'store'])->name('tukar.tambah.store');

    // Daftar Kredit Ponsel
    Route::get('/daftar-kredit', [HomeController::class, 'daftarKredit'])->name('daftar.kredit');
    Route::get('/daftar-kredit/{id}', [HomeController::class, 'daftarKreditShow'])->name('daftar.kredit.show');

    // Pengajuan
    Route::get('/pengajuan', [HomeController::class, 'daftarPengajuan'])->name('pengajuan');
    Route::get('/pengajuan/jual/{id}', [HomeController::class, 'showJual'])->name('pengajuan.jual.show');
    Route::get('/pengajuan/tukar-tambah/{id}', [HomeController::class, 'showTukar'])->name('pengajuan.tukar.show');

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::post('/ubah-status/{id}', [KeranjangController::class, 'ubahStatus'])->name('ubah.status');

    // inbox
    Route::get('/customer/inbox', [InboxController::class, 'customerInbox'])->name('customer.inbox');
    Route::post('/send', [InboxController::class, 'send'])->name('send.inbox');

    // Payment / Invoice
    Route::post('/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('payment.methods');
    Route::get('/payment-methods', [PaymentController::class, 'PaymentMethods'])->name('payment.method');
    Route::get('/create-invoice', [PaymentController::class, 'showInvoiceForm'])->name('invoice.form');
    Route::post('/create-invoice', [PaymentController::class, 'createInvoice'])->name('invoice.create');

    // Ongkir
    Route::get('get-kecamatan/{search}', [OngkirController::class, 'getKecamatan']);
    Route::post('get-ongkir', [OngkirController::class, 'getOngkir']);

    // Checkout
    Route::post('/checkout-ponsel', [BeliPonselController::class, 'submitCheckout'])->name('checkout');
    Route::put('/pesanan/selesai/{id}', [KeranjangController::class, 'selesai'])->name('selesai');

    // Ulasan
    Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::get('/ulasan.show/{id}', [UlasanController::class, 'show'])->name('ulasan.show');
});

// Admin Routes
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Ponsel (resource)
    Route::resource('ponsel', AdminPonselController::class)->names('ponsel');
    Route::delete('/ponsel/{id}/force-delete', [AdminPonselController::class, 'forceDelete'])->name('ponsel.forceDelete');
    Route::patch('/ponsel/{id}/restore', [AdminPonselController::class, 'restore'])->name('ponsel.restore');
    Route::get('/admin/ponsel/softdelete', [AdminPonselController::class, 'showSoftDeleted'])->name('ponsel.softdelete');

    // Jual Ponsel
    Route::get('/jual-ponsel', [AdminJualPonselController::class, 'index'])->name('jual-ponsel.index');
    Route::get('/jual-ponsel/{id}', [AdminJualPonselController::class, 'show'])->name('jual-ponsel.show');
    Route::put('/jual-ponsel/{id}/update-status', [AdminJualPonselController::class, 'updateStatus'])->name('jual-ponsel.update-status');
    Route::delete('/jual-ponsel/{id}', [AdminJualPonselController::class, 'destroy'])->name('jual-ponsel.destroy');

    // Tukar Tambah
    Route::get('/tukar-tambah', [AdminTukarTambahController::class, 'index'])->name('tukar-tambah.index');
    Route::get('/tukar-tambah/{id}', [AdminTukarTambahController::class, 'show'])->name('tukar-tambah.show');
    Route::put('/tukar-tambah/{id}/update-status', [AdminTukarTambahController::class, 'updateStatus'])->name('tukar-tambah.update-status');
    Route::delete('/tukar-tambah/{id}', [AdminTukarTambahController::class, 'destroy'])->name('tukar-tambah.destroy');

    // Kredit
    Route::get('/kredit', [AdminKreditController::class, 'index'])->name('kredit.index');
    Route::get('/kredit/{id}', [AdminKreditController::class, 'show'])->name('kredit.show');
    Route::put('/kredit/{id}/update-status', [AdminKreditController::class, 'updateStatus'])->name('kredit.updateStatus');
    Route::delete('/kredit/{id}', [AdminKreditController::class, 'destroy'])->name('kredit.destroy');

    // profile
    Route::get('/profile', [ProfileControllerAdmin::class, 'index'])->name('profile');
    Route::put('/profile/upload', [ProfileControllerAdmin::class, 'upload'])->name('profil.upload');
    Route::put('/profile/update', [ProfileControllerAdmin::class, 'update'])->name('profil.update');

    // Chat / Inbox
    Route::get('/adminInbox/{id}', [InboxController::class, 'adminInbox'])->name('inbox');
    Route::post('/admin/send', [InboxController::class, 'sendAdmin'])->name('send.inbox');
    Route::get('/listInbox', [InboxController::class, 'listInbox'])->name('listInbox');

    // Transaksi
    Route::get('/transaksi/edit/{id}', [TransaksiController::class, 'edit'])->name('edit.transaksi');
    Route::put('/transaksi/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::get('transaksi', [TransaksiController::class, 'semuaTransaksi'])->name('ponsel.transaksi');
});

// Lupa & Reset Password
Route::controller(OTPResetPasswordController::class)->group(function () {
    Route::get('forgot-password', fn () => view('auth.forgot-password'))->name('password.request');
    Route::post('forgot-password', 'sendOTP')->name('password.otp.send');
    Route::get('verify-otp', fn () => view('auth.verify-otp'))->name('password.otp.verify.form');
    Route::post('verify-otp', 'verifyOTP')->name('password.otp.verify');
    Route::get('/reset-password', fn () => view('auth.reset-password', ['email' => request()->query('email')]))->name('password.reset.form');
    Route::post('reset-password', 'resetPassword')->name('password.update');
});
