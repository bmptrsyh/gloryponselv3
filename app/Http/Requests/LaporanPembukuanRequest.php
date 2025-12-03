<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanPembukuanRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        // Cek jika ada input 'jumlah', hapus titiknya
        if ($this->has('jumlah')) {
            $this->merge([
                'jumlah' => str_replace('.', '', $this->input('jumlah')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'jenis_transaksi' => 'required|in:debit,kredit',
            'jumlah' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        // Cek input jenis_transaksi, default ke 'Jumlah' jika kosong
        $label = ucfirst($this->input('jenis_transaksi', 'Jumlah'));

        return [
            'jumlah' => $label,
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD',
            'deskripsi.required' => 'Deskripsi transaksi wajib diisi',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih',

            // Menggunakan :attribute agar otomatis berubah jadi Debit/Kredit
            'jumlah.numeric' => ':attribute harus berupa angka',
            'jumlah.min' => 'Nilai :attribute tidak boleh negatif',
        ];
    }
}
