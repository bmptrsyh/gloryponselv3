<?php

namespace App\Http\Controllers\Customer\Auth;


use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Callback dari Facebook
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // Cek apakah user sudah ada di database
            $user = Customer::where('email', $facebookUser->email)->first();

            if (!$user) {
                // Jika user belum ada, buat user baru
                $user = Customer::create([
                    'nama' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'password' => bcrypt('default_password'),
                ]);
            }

            // Login user
            Auth::login($user);

            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Facebook.');
        }
    }
}
