<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePonselRequest;
use App\Models\Ponsel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PonselController extends Controller
{
    public function index(Request $request)
    {
        // Lakukan pencarian menggunakan Laravel Scout
        $produk = $request->filled('keyword')
            ? Ponsel::search($request->keyword)->get()
            : Ponsel::with('ulasan')->get();

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

    public function create()
    {
        return view('admin.ponsel.create');
    }

    public function store(StorePonselRequest $request)
    {
        $validated = $request->validated();

        $file = $request->file('gambar');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = time().'.'.$ext;

        $manager = new ImageManager(new Driver);
        $image = $manager->read($file)->cover(500, 500);

        $path = 'gambar/ponsel/'.$filename;

        // Simpan dengan format sesuai extensi
        if ($ext === 'png') {
            Storage::disk('public')->put($path, $image->toPng());
        } else {
            Storage::disk('public')->put($path, $image->toJpeg(85));
        }

        Ponsel::create(array_merge($validated, [
            'gambar' => 'storage/'.$path,
        ]));

        return redirect()->route('admin.ponsel.index')
            ->with('success', 'Ponsel berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $ponsel = Ponsel::findOrFail($id);

        return view('admin.ponsel.edit', compact('ponsel'));
    }

    public function update(StorePonselRequest $request, $id)
    {
        $ponsel = Ponsel::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($ponsel->gambar && Storage::disk('public')->exists(str_replace('storage/', '', $ponsel->gambar))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $ponsel->gambar));
            }

            $file = $request->file('gambar');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = time().'.'.$ext;

            $manager = new ImageManager(new Driver);
            $image = $manager->read($file)->cover(600, 600);

            $path = 'gambar/ponsel/'.$filename;

            if ($ext === 'png') {
                Storage::disk('public')->put($path, $image->toPng());
            } else {
                Storage::disk('public')->put($path, $image->toJpeg(85));
            }

            $validated['gambar'] = 'storage/'.$path;
        }

        $ponsel->update($validated);

        return redirect()->route('admin.ponsel.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ponsel = Ponsel::findOrFail($id);
        $ponsel->delete();

        return redirect()->route('admin.ponsel.index')->with('success', 'Ponsel berhasil dihapus!');
    }

    public function restore($id)
    {
        $ponsel = Ponsel::onlyTrashed()->findOrFail($id);
        $ponsel->restore();

        return redirect()->route('admin.ponsel.index')->with('success', 'Ponsel berhasil direstore!');
    }

    public function forceDelete($id)
    {
        $ponsel = Ponsel::onlyTrashed()->findOrFail($id);

        // Hapus gambar dari storage
        if ($ponsel->gambar && Storage::exists('public/'.str_replace('storage/', '', $ponsel->gambar))) {
            Storage::delete('public/'.str_replace('storage/', '', $ponsel->gambar));
        }

        // Hapus permanen dari database
        $ponsel->forceDelete();

        return redirect()->route('admin.ponsel.index')->with('success', 'Ponsel berhasil dihapus permanen!');
    }

    public function showSoftDeleted()
    {
        $ponselSoftDeleted = Ponsel::onlyTrashed()->latest()->get();

        return view('admin.ponsel.trashed', compact('ponselSoftDeleted'));
    }
}
