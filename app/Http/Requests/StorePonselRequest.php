<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePonselRequest extends FormRequest
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
    public function rules(): array {
        $rules = [
            'merk' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-&.]+$/',
            'model' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:baru,bekas',
            'processor' => 'required|string|max:255',
            'dimension' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\.\-x]+$/',
            'ram' => 'required|integer',
            'storage' => 'required|integer',
            'warna' => 'nullable|string|max:255',
        ];

        // Tambahkan validasi gambar tergantung method (POST untuk store, PUT/PATCH untuk update)
        if ($this->isMethod('post')) {
            $rules['gambar'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['gambar'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }
}
