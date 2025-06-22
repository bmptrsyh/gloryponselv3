<?php

namespace App\Http\Controllers\Customer;

use App\Models\Ponsel;
use App\Models\Ulasan;
use App\Models\Customer;
use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

foreach ($produk as $p) {
    $avg = $p->ulasan->avg('rating');
    $count = $p->ulasan->count();
    $p->avg = $avg;
    $p->count = $count;
}




    return view('customer.ponsel.index', compact('produk', 'filters'));
}



    public function show($id) {
        $customer = Auth::user();
        $produk = Ponsel::with(['ulasan' => function($query) {
        $query->orderBy('tanggal_ulasan', 'desc');
        }])->findOrFail($id);
        $avg = $produk->ulasan->avg('rating');
        $terjual = BeliPonsel::where('id_ponsel', $id)
                ->where('status', 'selesai')
                ->count();
        $ulasan = Ulasan::with('beliPonsel')->where('id_ponsel', $id)->get();


        
        
        return view('customer.ponsel.show', compact('produk', 'customer', 'avg', 'terjual', 'ulasan'));
    }
}
