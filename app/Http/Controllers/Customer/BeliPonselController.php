<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ponsel;
use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeliPonselController extends Controller
{
    public function beliPonsel(Request $request, $id_ponsel)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'metode_pembayaran' => 'required',
            'jasa_pengiriman' => 'required',
            'nama' => 'required',
            'telepon' => 'required',
            'alamat' => 'required',
        ]);

        // Ambil data ponsel dari database
        $ponsel = Ponsel::findOrFail($id_ponsel);

        // Simpan ke tabel beli_ponsel
        $beliPonsel = BeliPonsel::create([
            'id_customer' => Auth::id(), // Pastikan customer menggunakan id_customer
            'id_ponsel' => $ponsel->id_ponsel,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'tertunda',
            'tanggal_transaksi' => now(),
            'jumlah' => $request->jumlah,
            'harga' => $ponsel->harga * $request->jumlah
        ]);

        // Simpan info pengiriman jika diperlukan (buat tabel terpisah jika perlu)
        // ...

        return redirect()->route('transaksi.index')
            ->with('success', 'Pembelian berhasil! ID Transaksi: '.$beliPonsel->id_beli_ponsel);
    }

    public function transaksi()
    {
        $transaksis = BeliPonsel::with(['ponsel', 'customer'])
            ->where('id_customer', Auth::id())
            ->latest()
            ->get();
        dd($transaksis);
        return view('customer.ponsel.transaksi', compact('transaksis'));
    }
}