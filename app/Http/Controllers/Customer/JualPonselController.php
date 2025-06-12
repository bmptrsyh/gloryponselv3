<?php

namespace App\Http\Controllers\Customer;

use App\Models\JualPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JualPonselController extends Controller
{
    public function index() {
        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;
        $jualPonsel = JualPonsel::where('id_customer', $id_customer)->get();
        dd($jualPonsel);
        return view ('customer.jualPonselIndex', compact('jualPonsel'));
    }
    public function create() {
        return view('customer.jualPonsel');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'merk' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'warna' => 'required|string|max:50',
            'ram' => 'required|integer|min:1',
            'storage' => 'required|integer|min:1',
            'processor' => 'required|string|max:100',
            'kondisi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|integer|min:1000',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;
        $path = $request->file('gambar')->store('gambar/ponsel/jual-ponsel', 'public');

        JualPonsel::create([
            'id_customer' => $id_customer,
            'merk' => $request->merk,
            'model' => $request->model,
            'warna' => $request->warna,
            'ram' => $request->ram,
            'storage' => $request->storage,
            'processor' => $request->processor,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => 'storage/' . $path,
            'status' => 'menunggu',
        ]);

        return redirect()->route('produk.index')
        ->with('success', 'Pengajuan jual ponsel berhasil dikirim! Kami akan segera meninjau pengajuan Anda.');
    }
}
