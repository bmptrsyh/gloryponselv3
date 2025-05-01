<?php

namespace App\Http\Controllers\Customer;

use App\Models\Ponsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KeranjangController extends Controller
{

    public function index() {
        $keranjang = session()->get('keranjang', []);
        return view('keranjang', compact('keranjang'));
    }


    public function store(Request $request)
    {
        $produkId = $request->produk_id;
        $produk = Ponsel::findOrFail($produkId); // Ambil data produk dari DB
    
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
                'gambar' => $produk->gambar, // Asumsi path sudah "storage/gambar/ponsel/nama.jpg"
            ];
        }
    
        // Simpan kembali ke session
        session()->put('keranjang', $keranjang);
    
        return redirect('/keranjang')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
    
    

}
