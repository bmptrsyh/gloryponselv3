<?php

namespace App\Http\Controllers\admin;

use App\Models\TukarTambah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminTukarTambahController extends Controller
{
    public function index()
    {
        $pengajuan = TukarTambah::with(['customer', 'produkTujuan'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.tukar-tambah.index', compact('pengajuan'));
    }

        public function show($id)
    {
        $pengajuan = TukarTambah::with(['customer', 'produkTujuan'])->findOrFail($id);
        return view('admin.tukar-tambah.show', compact('pengajuan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,di setujui,di tolak',
            'catatan' => 'nullable|string',
        ]);

        $pengajuan = TukarTambah::findOrFail($id);
        $pengajuan->status = $request->status;
        
        // Jika ada catatan, simpan ke kolom deskripsi atau buat kolom baru
        if ($request->filled('catatan')) {
            // Opsional: Tambahkan catatan admin ke deskripsi yang sudah ada
            $pengajuan->deskripsi = $pengajuan->deskripsi . "\n\n--- Catatan Admin ---\n" . $request->catatan;
        }
        
        $pengajuan->save();

        return redirect()->route('admin.tukar-tambah.index')
            ->with('success', 'Status pengajuan jual ponsel berhasil diperbarui.');
    }

     public function destroy($id)
    {
        $pengajuan = TukarTambah::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($pengajuan->gambar && Storage::exists(str_replace('storage/', 'public/', $pengajuan->gambar))) {
            Storage::delete(str_replace('storage/', 'public/', $pengajuan->gambar));
        }
        
        $pengajuan->delete();

        return redirect()->route('admin.tukar-tambah.index')
            ->with('success', 'Pengajuan tukar tambah berhasil dihapus.');
    }

    
}
