<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
    $id = $this->route('id'); // Ambil dari URL
    $customer = \App\Models\Customer::find($id);

    return [
        'nama' => 'required|string|max:255',
        'alamat' => 'nullable|string',
        'email' => [
            'required',
            'email',
            // hanya validasi unique kalau email berubah
            Rule::unique('customer', 'email')->ignore($id, 'id_customer')
                ->when($this->email !== $customer?->email, fn($rule) => $rule),
        ],
        'nomor_telepon' => [
            'required',
            'string',
            // hanya validasi unique kalau nomor_telepon berubah
            Rule::unique('customer', 'nomor_telepon')->ignore($id, 'id_customer')
                ->when($this->nomor_telepon !== $customer?->nomor_telepon, fn($rule) => $rule),
        ],
        'password' => 'nullable|string|min:6',
        'foto_profil' => 'nullable|image|max:2048',
    ];
}
}
