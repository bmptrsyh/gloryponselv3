<?php

namespace App\Http\Controllers\Customer;

use App\Models\Ponsel;
use App\Models\Ulasan;
use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{

    public function index() {
        $customer = Auth::user();
        $id_customer = $customer->id_customer;
        $keranjang = session()->get('keranjang', []);
        $beliponsel = BeliPonsel::with('ponsel', 'ulasan')->where('id_customer', $id_customer)->get();

        return view('keranjang', compact('keranjang', 'beliponsel'));
    }

        public function selesai($id)
    {
        $pesanan = BeliPonsel::findOrFail($id);
        $pesanan->status_pengiriman = 'selesai';
        $pesanan->save();

        return redirect()->back()->with('success', 'Pesanan telah dikonfirmasi sebagai selesai.');
    }

    public function store(Request $request)
    {
        $produkId = $request->produk_id;
        $produk = Ponsel::findOrFail($produkId);// Ambil data produk dari DB
    
        // Ambil isi keranjang dari session
        $keranjang = session()->get('keranjang', []);
    
        // Jika produk sudah ada di keranjang, tambahkan jumlah
        if (isset($keranjang[$produkId])) {
            $keranjang[$produkId]['jumlah'] += $request->jumlah;
        } else {
            // Kalau belum, tambahkan produk baru
            $keranjang[$produkId] = [
                'produk_id' => $produk->id_ponsel,
                'nama' => $produk->merk . ' ' . $produk->model,
                'harga' => $produk->harga_jual,
                'jumlah' => $request->jumlah,
                'gambar' => $produk->gambar,
                'warna' => $produk->warna ?? '-',
                'storage' => $produk->storage,
                'ram' => $produk->ram,
                'stok' => $produk->stok,
            ];
        }
    
        // Simpan kembali ke session
        session()->put('keranjang', $keranjang);

    
        return redirect('/keranjang')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }


    public function ubahStatus(Request $request, $id)
    {
        $item = BeliPonsel::findOrFail($id);
        $item->status_pengiriman = 'selesai';
        $item->save();

        return back()->with('success', 'Status berhasil diubah.');
    }

    
    

}
