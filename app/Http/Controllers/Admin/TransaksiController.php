<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pembukuan;
use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TransaksiController extends Controller
{
    public function semuaTransaksi()
    {
        $transaksi = BeliPonsel::with(['ponsel' => function ($query) {
            $query->withTrashed();
        }, 'customer'])
            ->latest()
            ->get();

        return view('admin.transaksi', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = BeliPonsel::with(['ponsel' => function ($query) {
            $query->withTrashed();
        }, 'customer'])->findOrFail($id);
        return view('admin.editTransaksi', compact('transaksi'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = BeliPonsel::with('pembukuan', 'ponsel')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:tertunda,selesai',
            'status_pengiriman' => 'required|in:belum_dikirim,dikirim',
        ]);

        $transaksi->update([
            'status' => $request->status,
            'status_pengiriman' => $request->status_pengiriman,
        ]);

        if ($transaksi->pembukuan == null) {
            if ($transaksi->status === 'selesai' && $transaksi->status_pengiriman === 'dikirim') {
                $saldoTerakhir = Pembukuan::latest('id_laporan')->value('saldo');
                $saldoTerakhir = $saldoTerakhir ?? 0;

                $debit = 0;
                $kredit = $transaksi->harga;
                $saldoBaru = $saldoTerakhir + $kredit - $debit;

                $deskripsi = "Menjual ponsel {$transaksi->ponsel->merk} {$transaksi->ponsel->model}";

                Pembukuan::create([
                    'transaksi_id' => $transaksi->id_beli_ponsel,
                    'transaksi_type' => BeliPonsel::class,
                    'tanggal' => now(),
                    'deskripsi' => $deskripsi,
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'saldo' => $saldoBaru,
                    'metode_pembayaran' => $transaksi->metode_pembayaran,
                ]);
            }
        }


        return redirect()->route('admin.ponsel.transaksi')->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
