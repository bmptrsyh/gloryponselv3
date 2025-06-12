<?php

namespace App\Http\Controllers\Admin;

use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransaksiController extends Controller
{
    public function semuaTransaksi() {
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
        $transaksi = BeliPonsel::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:tertunda,selesai',
            'status_pengiriman' => 'required|in:belum_dikirim,dikirim',
        ]);

        $transaksi->update([
            'status' => $request->status,
            'status_pengiriman' => $request->status_pengiriman,
        ]);

        return redirect()->route('admin.ponsel.transaksi')->with('success', 'Status transaksi berhasil diperbarui.');
    }

}
