<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan return true agar request ini dapat digunakan
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:50|min:2|regex:/^[A-Za-z\s\']+$/',
            'email' => 'required|email|unique:customer,email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
            'password_confirmation' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
            'alamat' => 'nullable|string|max:100',
            'nomor_telepon' => 'required|string|min:10|max:15|regex:/^[0-9]+$/|unique:customer,nomor_telepon',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama lengkap harus diisi',
            'nama.min' => 'Nama lengkap harus terdiri dari minimal 2 karakter',
            'nama.max' => 'Nama lengkap tidak boleh lebih dari 50 karakter',
            'nama.string' => 'Nama hanya boleh huruf',
            'nama.regex' => 'Nama tidak boleh mengandung simbol',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi',
            'password.regex' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol',
            'alamat.required' => 'Alamat lengkap harus diisi',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi',
            'nomor_telepon.regex' => 'Nomor telepon harus berupa angka',
            'nomor_telepon.unique' => 'Nomor telepon sudah terdaftar',
            'nomor_telepon.min' => 'Nomor telepon minimal 10 digit',
            'nomor_telepon.max' => 'Nomor telepon maksimal 15 digit',
            'foto_profil.image' => 'File harus berupa gambar',
            'foto_profil.mimes' => 'Format foto tidak didukung (jpeg, png, jpg)',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB',
            'alamat.max' => 'Alamat maksimal 100 karakter',

        ];
    }
}
