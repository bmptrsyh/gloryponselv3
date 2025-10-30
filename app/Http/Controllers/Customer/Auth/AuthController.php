<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Models\Customer;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

// hhhhh
class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function login(Request $request)
    {
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nomor_telepon';
        $rateLimiterKey = 'login:' . $request->login . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            throw ValidationException::withMessages([
                'login' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.',
            ]);
        }

        // Jika input adalah email → coba login admin dulu
        if ($loginType === 'email' && Auth::guard('admin')->attempt([$loginType => $request->login, 'password' => $request->password])) {
            RateLimiter::clear($rateLimiterKey);
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // Customer bisa login pakai email atau nomor telepon
        if (Auth::guard('web')->attempt([$loginType => $request->login, 'password' => $request->password])) {
            RateLimiter::clear($rateLimiterKey);
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        RateLimiter::hit($rateLimiterKey);
        return back()->withErrors(['login' => 'Credential yang diberikan tidak sesuai.']);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function register(RegisterRequest $request)
    {

        // Cek apakah ada file foto di-upload

        $gambarPath = $request->file('foto_profil')->store('gambar/customer', 'public');


        // Buat customer baru
        Customer::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'foto_profil' => 'storage/' . $gambarPath, // simpan path foto
        ]);


        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
