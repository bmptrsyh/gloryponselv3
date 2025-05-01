<?php

namespace App\Http\Controllers\Customer;

use App\Models\Ponsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    return view('customer.ponsel.index', compact('produk', 'filters'));
}



    public function show($id) {
        $produk = Ponsel::with(['ulasan' => function($query) {
        $query->orderBy('tanggal_ulasan', 'desc');
        }])->findOrFail($id);
        return view('customer.ponsel.show', compact('produk'));
    }
}
