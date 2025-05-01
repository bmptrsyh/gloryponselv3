<?php

namespace App\Http\Controllers;

use auth;
use Carbon\Carbon;
use App\Models\Ponsel;
use App\Models\Customer;
use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PonselController extends Controller
{
    public function beliPonsel(Request $request, $id)
    {
        $produk = Ponsel::findOrFail($id);

        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $produk->stok,
            'metode_pembayaran' => 'required|string',
        ]);

        $jumlah = $request->input('jumlah');
        $metodePembayaran = $request->input('metode_pembayaran');
        $hargaTotal = $produk->harga_jual * $jumlah;
        $tanggalTransaksi = Carbon::now();

        BeliPonsel::create([
            'id_customer' => auth()->user()->id_customer,
            'id_ponsel' => $produk->id_ponsel,
            'jumlah' => $jumlah,
            'metode_pembayaran' => $metodePembayaran,
            'harga' => $hargaTotal,
            'tanggal_transaksi' => $tanggalTransaksi,
        ]);

        // Update stok
        $produk->stok -= $jumlah;
        $produk->save();

        return redirect()->route('transaksi.index')->with('success', 'Pembelian berhasil diproses');
    }

    /**
     * Menampilkan daftar transaksi user
     */
    public function transaksi()
    {
        $transaksi = BeliPonsel::with('ponsel')
            ->where('id_customer', auth()->user()->id_customer)
            ->latest()
            ->get();


        return view('transaksi.index', compact('transaksi'));   
    }
}
