<?php

namespace App\Http\Requests\Kredit;

use Illuminate\Foundation\Http\FormRequest;

class DataPekerjaanRequest extends FormRequest
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
            'pekerjaan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'lama_bekerja' => 'required|string|max:100',
            'penghasilan_bulanan' => 'required|numeric|min:0',
            'penghasilan_lain' => 'nullable|numeric|min:0',
            'jangka_waktu' => 'required|integer|min:1',
            'jumlah_dp' => 'required|numeric|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'alamat_perusahaan.required' => 'Alamat perusahaan wajib diisi.',
            'lama_bekerja.required' => 'Lama bekerja wajib diisi.',
            'penghasilan_bulanan.required' => 'Penghasilan bulanan wajib diisi.',
            'jangka_waktu.required' => 'Jangka waktu wajib diisi.',
            'jumlah_dp.required' => 'Jumlah DP wajib diisi.',
        ];
    }
}
