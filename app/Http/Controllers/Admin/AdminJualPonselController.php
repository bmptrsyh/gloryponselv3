<?php

namespace App\Http\Controllers\Admin;

use App\Models\JualPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminJualPonselController extends Controller
{
    public function index() {
        $pengajuan = JualPonsel::with('customer')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.jual-ponsel.index', compact('pengajuan'));
    }

    public function show($id) {
        $pengajuan = JualPonsel::with('customer')->findOrFail($id);
        return view('admin.jual-ponsel.show', compact('pengajuan'));
    }

        public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,di setujui,di tolak',
            'catatan' => 'nullable|string',
        ]);

        $pengajuan = JualPonsel::findOrFail($id);
        $pengajuan->status = $request->status;
        
        // Jika ada catatan, simpan ke kolom deskripsi atau buat kolom baru
        if ($request->filled('catatan')) {
            // Opsional: Tambahkan catatan admin ke deskripsi yang sudah ada
            $pengajuan->deskripsi = $pengajuan->deskripsi . "\n\n--- Catatan Admin ---\n" . $request->catatan;
        }
        
        $pengajuan->save();

        return redirect()->route('admin.jual-ponsel.index')
            ->with('success', 'Status pengajuan jual ponsel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengajuan = JualPonsel::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($pengajuan->gambar && Storage::exists(str_replace('storage/', 'public/', $pengajuan->gambar))) {
            Storage::delete(str_replace('storage/', 'public/', $pengajuan->gambar));
        }
        
        $pengajuan->delete();

        return redirect()->route('admin.jual-ponsel.index')
            ->with('success', 'Pengajuan jual ponsel berhasil dihapus.');
    }
}
