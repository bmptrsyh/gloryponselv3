<?php

namespace App\Http\Controllers\customer;

use App\Models\Ponsel;
use App\Models\TukarTambah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TukarTambahController extends Controller
{
    public function create($id)
    {
        $ponsel = Ponsel::findOrFail($id);
        return view('customer.tukarTambah', compact('ponsel'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'produk_tujuan_id' => 'required|exists:ponsel,id_ponsel',
            'merk' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'warna' => 'required|string|max:50',
            'ram' => 'required|integer|min:1',
            'storage' => 'required|integer|min:1',
            'processor' => 'required|string|max:100',
            'kondisi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_estimasi' => 'required|integer|min:1000',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;
        $path = $request->file('gambar')->store('gambar/ponsel/tukar-tambah', 'public');

        TukarTambah::create([
            'id_customer' => $id_customer,
            'produk_tujuan_id' => $request->produk_tujuan_id,
            'merk' => $request->merk,
            'model' => $request->model,
            'warna' => $request->warna,
            'ram' => $request->ram,
            'storage' => $request->storage,
            'processor' => $request->processor,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
            'harga_estimasi' => $request->harga_estimasi,
            'gambar' => 'storage/' . $path,
            'status' => 'menunggu',
        ]);
        return redirect()->route('produk.index')
            ->with('success', 'Pengajuan tukar tambah berhasil dikirim! Kami akan segera meninjau pengajuan Anda.');
    }

    public function index() {
        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;

        $pengajuan = TukarTambah::with('produkTujuan')->where('id_customer', $id_customer)->orderBy('created_at', 'desc')->get();

        return view('customer.tukarTambahIndex', compact('pengajuan'));
    }
}
