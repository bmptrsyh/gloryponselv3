<?php

namespace App\Http\Controllers\Customer\Auth;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OTPResetPasswordController extends Controller
{
    // 1️⃣ Kirim OTP ke Email
    public function sendOTP(Request $request) {
        $request->validate(['email' => 'required|email']);

        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

    // Buat OTP
        $otp = rand(100000, 999999);
        $customer->otp = $otp;
        $customer->otp_expires_at = now()->addMinutes(10);
        $customer->save();

    // Kirim OTP via email
        Mail::raw("Kode OTP Anda adalah: $otp", function ($message) use ($customer) {
            $message->to($customer->email)->subject('Reset Password OTP');
        });

    // Simpan email di session
        session(['reset_email' => $request->email]);

        return redirect()->route('password.otp.verify.form')->with('success', 'OTP telah dikirim ke email Anda.');
    }

    

    // 2️⃣ Verifikasi OTP
    public function verifyOTP(Request $request) {
        $request->validate(['otp' => 'required|digits:6']);
    
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session berakhir. Silakan coba lagi.']);
        }
    
        $customer = Customer::where('email', $email)
                            ->where('otp', $request->otp)
                            ->where('otp_expires_at', '>', Carbon::now())
                            ->first();
    
        if (!$customer) {
            return back()->withErrors(['otp' => 'OTP salah atau telah kedaluwarsa']);
        }
    
        // Hapus OTP dan lanjut
        $customer->otp = null;
        $customer->otp_expires_at = null;
        $customer->save();
    
        return redirect()->route('password.reset.form')
                         ->with('success', 'OTP berhasil diverifikasi. Silakan reset password Anda.');
    }
    

    // 3️⃣ Reset Password setelah OTP Valid
    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session berakhir. Silakan coba lagi.']);
        }

        $customer = Customer::where('email', $email)->first();
        if (!$customer) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        $customer->password = Hash::make($request->password);
        $customer->save();

    // Bersihkan session
        session()->forget('reset_email');

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login.');
    }

}

