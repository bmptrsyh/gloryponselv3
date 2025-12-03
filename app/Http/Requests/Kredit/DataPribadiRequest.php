<?php

namespace App\Http\Requests\Kredit;

use Illuminate\Foundation\Http\FormRequest;

class DataPribadiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255|min:4|regex:/^[A-Za-z\s]+$/',
            'nik' => 'required|digits:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(17)->format('Y-m-d'),
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah',
            'alamat_ktp' => 'required|string|min:4',
            'alamat_domisili' => 'required|string|min:4',
            'no_telp' => 'required|string|max:15|min:10',
            'email' => 'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.regex' => 'nama lengkap hanya boleh mengandung huruf dan spasi.',
            'nama_lengkap.min' => 'nama lengkap harus terdiri dari minimal 4 karakter.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'tanggal_lahir.before_or_equal' => 'Usia minimal adalah 17 tahun.',
            'alamat_ktp.min' => 'alamat KTP harus terdiri dari minimal 4 karakter.',
            'alamat_domisili.min' => 'alamat domisili harus terdiri dari minimal 4 karakter.',
            'no_telp.max' => 'nomor telepon maksimal 15 karakter.',
            'no_telp.min' => 'nomor telepon minimal 10 karakter.',
        ];
    }
}
