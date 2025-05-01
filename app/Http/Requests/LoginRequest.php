<?php

namespace App\Http\Requests;

use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login' => ['required', 'string', function ($attribute, $value, $fail) {
                // Cek apakah input berupa email atau nomor telepon
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    // Cek apakah email ada di salah satu tabel (Pengguna atau Admin)
                    if (!Customer::where('email', $value)->exists() && !Admin::where('email', $value)->exists()) {
                        $fail('Email ini belum terdaftar.');
                    }
                } elseif (is_numeric($value)) {
                    if (!Customer::where('nomor_telepon', $value)->exists()) {
                        $fail('Nomor telepon ini belum terdaftar.');
                    }
                } else {
                    $fail('Masukkan email atau nomor telepon yang valid.');
                }
            }],
            'password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Email atau nomor telepon harus diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ];
    }
}
