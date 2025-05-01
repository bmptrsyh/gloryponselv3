<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ponsel;
use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePonselRequest;

class PonselController extends Controller
{
    public function index(Request $request)
    {
        // Lakukan pencarian menggunakan Laravel Scout
        $produk = $request->filled('keyword')
            ? Ponsel::search($request->keyword)->get()
            : Ponsel::all();
    
        // Filter di collection (bukan query builder)
        foreach (['merk', 'model', 'status', 'processor', 'dimension', 'ram', 'storage', 'warna'] as $field) {
            if ($request->filled($field)) {
                $produk = $produk->where($field, $request->$field);
            }
        }
    
        // Pisahkan data berdasarkan status
        $produkBaru = $produk->where('status', 'baru');
        $produkBekas = $produk->where('status', 'bekas');
    
        // Ambil nilai unik untuk filter
        $filters = [
            'merk' => Ponsel::select('merk')->distinct()->pluck('merk'),
            'model' => Ponsel::select('model')->distinct()->pluck('model'),
            'processor' => Ponsel::select('processor')->distinct()->pluck('processor'),
            'dimension' => Ponsel::select('dimension')->distinct()->pluck('dimension'),
            'ram' => Ponsel::select('ram')->distinct()->pluck('ram'),
            'storage' => Ponsel::select('storage')->distinct()->pluck('storage'),
            'warna' => Ponsel::select('warna')->distinct()->pluck('warna'),
        ];
    
        return view('admin.ponsel.index', compact('produkBaru', 'produkBekas', 'filters'));
    }
    


    public function create () {
        return view('admin.ponsel.create');
    }

    public function store (StorePonselRequest $request) {
        $validated = $request->validated();

        $gambarPath = $request->file('gambar')->store('gambar/ponsel', 'public');

        Ponsel::create(array_merge($validated, [
            'gambar' => 'storage/' . $gambarPath,
        ]));

        return redirect()->route('admin.ponsel.index')->with('success', 'Ponsel berhasil ditambahkan!');
    }


    public function edit ($id) {
        $ponsel = Ponsel::findOrFail($id);
        return view('admin.ponsel.edit', compact('ponsel'));
    }

    public function update (StorePonselRequest $request, $id) {
        $ponsel = Ponsel::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($ponsel->gambar && Storage::disk('public')->exists(str_replace('storage/', '', $ponsel->gambar))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $ponsel->gambar));
            }
        
            $gambarPath = $request->file('gambar')->store('gambar/ponsel', 'public');
            $validated['gambar'] = 'storage/' . $gambarPath;
        }
        

        $ponsel->update($validated);

        return redirect()->route('admin.ponsel.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id) {
        $ponsel = Ponsel::findOrFail($id);
        $ponsel->delete();

        return redirect()->route('admin.ponsel.index')->with('success', 'Ponsel berhasil dihapus!');
    }

    public function restore ($id) {
        $ponsel = Ponsel::onlyTrashed()->findOrFail($id);
        $ponsel->restore();

        return redirect()->route('admin.ponsel.index')->with('success', 'Ponsel berhasil dihapus!');
    }

    public function forceDelete($id) {
        $ponsel = Ponsel::onlyTrashed()->findOrFail($id);

        // Hapus gambar dari storage
        if ($ponsel->gambar && Storage::exists('public/' . str_replace('storage/', '', $ponsel->gambar))) {
            Storage::delete('public/' . str_replace('storage/', '', $ponsel->gambar));
        }

        // Hapus permanen dari database
        $ponsel->forceDelete();

        return redirect()->route('admin.ponsel.index')->with('success', 'Ponsel berhasil dihapus permanen!');
    }

    public function showSoftDeleted() {
        $ponselSoftDeleted = Ponsel::onlyTrashed()->latest()->get();

        return view('admin.ponsel.trashed', compact('ponselSoftDeleted'));
    }

    public function semuaTransaksi() {
        $transaksi = BeliPonsel::with(['ponsel' => function ($query) {
            $query->withTrashed();
        }, 'customer']) // optional: kalau mau tampilkan nama customer juga
        ->latest()
        ->get();

        return view('admin.transaksi', compact('transaksi'));
    }


}
