<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pembukuan;
use App\Models\JualPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminJualPonselController extends Controller
{
    public function index()
    {
        $pengajuan = JualPonsel::with('customer')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.jual-ponsel.index', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = JualPonsel::with('customer')->findOrFail($id);
        return view('admin.jual-ponsel.show', compact('pengajuan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,di setujui,di tolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $pengajuan = JualPonsel::with('pembukuan')->findOrFail($id);

        // Update status & deskripsi
        $pengajuan->status = $validated['status'];
        $statusMessages = [
            'di tolak'   => $validated['catatan_admin'],
            'di setujui' => $validated['catatan_admin']
                ?? 'Pengajuan Anda telah disetujui. Silahkan menghubungi admin untuk proses selanjutnya.',
            'menunggu'   => null,
        ];

        $pengajuan->catatan_admin = $statusMessages[$validated['status']] ?? null;

        $pengajuan->save();

        if ($pengajuan->pembukuan == null) {
            if ($pengajuan->status === 'di setujui') {
                $saldoTerakhir = Pembukuan::latest('id_laporan')->value('saldo');
                $saldoTerakhir = $saldoTerakhir ?? 0;

                $debit = $pengajuan->harga;
                $kredit = 0;
                $saldoBaru = $saldoTerakhir + $kredit - $debit;

                $deskripsi = "Membeli ponsel {$pengajuan->merk} {$pengajuan->model}";

                Pembukuan::create([
                    'transaksi_id' => $pengajuan->id_jual_ponsel,
                    'transaksi_type' => JualPonsel::class,
                    'tanggal' => now(),
                    'deskripsi' => $deskripsi,
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'saldo' => $saldoBaru,
                    'metode_pembayaran' => null,
                ]);
            }
        }

        return redirect()
            ->route('admin.jual-ponsel.index')
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
