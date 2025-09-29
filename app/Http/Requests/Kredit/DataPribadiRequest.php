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
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(17)->format('Y-m-d'),
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'required|string',
            'no_telp' => 'required|string',
            'email' => 'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'tanggal_lahir.before_or_equal' => 'Usia minimal adalah 17 tahun.',
        ];
    }
}
