<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pembukuan;
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
        $validated = $request->validate([
            'status' => 'required|in:menunggu,di setujui,di tolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $pengajuan = TukarTambah::with('pembukuan', 'produkTujuan')->findOrFail($id);
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

                $debit = 0;
                $kredit = 0;
                $saldoBaru = $saldoTerakhir + $kredit - $debit;

                $deskripsi = "Tukar Tambah ponsel {$pengajuan->merk} {$pengajuan->model} dengan {$pengajuan->produkTujuan->merk} {$pengajuan->produkTujuan->model}";

                Pembukuan::create([
                    'transaksi_id' => $pengajuan->id_tukar_tambah,
                    'transaksi_type' => TukarTambah::class,
                    'tanggal' => now(),
                    'deskripsi' => $deskripsi,
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'saldo' => $saldoBaru,
                    'metode_pembayaran' => null,
                ]);
            }
        }

        return redirect()->route('admin.tukar-tambah.index')
            ->with('success', 'Status pengajuan tukar tambah berhasil diperbarui.');
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
