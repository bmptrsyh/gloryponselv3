<?php

namespace App\Http\Controllers\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileControllerCustomer extends Controller
{
     public function index()
{
    $customer = Auth::user();
    return view('customer.profileCustomer', compact('customer'));
}
public function upload(Request $request, $id)
{
    // Validasi input file
    $request->validate([
        'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // Ambil customer yang sedang login
    $customer = Auth::user();

    // Cek apakah ID yang diminta sesuai dengan yang login (keamanan)
    if ($customer->id_customer != $id) {
        return back()->with('error', 'Akses tidak diizinkan.');
    }

    // Jika ada file diupload
    if ($request->hasFile('foto_profil')) {

        // Hapus foto lama jika ada
        if ($customer->foto_profil && \Storage::disk('public')->exists(str_replace('storage/', '', $customer->foto_profil))) {
            \Storage::disk('public')->delete(str_replace('storage/', '', $customer->foto_profil));
        }

        // Upload file baru
        $gambarPath = $request->file('foto_profil')->store('gambar/customer', 'public');

        // Simpan path ke database
        $customer->update([
            'foto_profil' => 'storage/' . $gambarPath
        ]);

        return redirect()->route('customer.profile')->with('success', 'Foto profil berhasil diperbarui.');
    }

    return back()->with('error', 'Tidak ada file yang diupload.');
}

   public function update(Request $request, $id)
{
    $customer = Customer::findOrFail($id);

    // Validasi input
    $request->validate([
        'nama' => 'nullable|string|max:255',
        'email' => [
            'nullable',
            'email',
            Rule::unique('customer', 'email')->ignore($customer->id_customer, 'id_customer'),
        ],
        'alamat' => 'nullable|string|max:500',
    ]);

    // Update data hanya jika customer yang login sesuai
    if (Auth::id() !== $customer->id_customer) {
        return back()->with('error', 'Akses tidak diizinkan.');
    }

    $customer->update([
        'nama' => $request->nama,
        'email' => $request->email,
        'alamat' => $request->alamat
    ]);

    return back()->with('success', 'Profil berhasil diperbarui!');
}
}
