<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileControllerAdmin extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profileAdmin', compact('admin'));
    }

    public function upload(Request $request)
{
    // Validasi input file
    $request->validate([
        'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // Ambil data admin yang sedang login
    $admin = Auth::guard('admin')->user();
    
    if ($request->hasFile('foto_profil')) {
        // Hapus foto lama jika ada dan file-nya eksis
        if ($admin->foto_profil && Storage::disk('public')->exists($admin->foto_profil)) {
            Storage::disk('public')->delete($admin->foto_profil);
        }

        // Simpan foto baru
        $path = $request->file('foto_profil')->store('gambar/admin', 'public');

        // Update path di database
        $admin->update([
            'foto_profil' => 'storage/' . $path
        ]);

        return back()->with('success', 'Foto profil berhasil diupdate!');
    }

    return back()->with('error', 'Gagal mengupload foto');
}

    public function update(Request $request)
{
    $admin = Auth::guard('admin')->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique('admins', 'email')->ignore($admin->id),
        ],
        'description' => 'nullable|string|max:500',
    ]);

    $admin->update([
        'name' => $request->name,
        'email' => $request->email,
        'description' => $request->description,
    ]);

    return back()->with('success', 'Profil berhasil diperbarui!');
}
}