<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ulasan;
use App\Models\BeliPonsel;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function store(Request $request)
{

    $request->validate([
        'id_beli_ponsel' => 'required|exists:beli_ponsel,id_beli_ponsel',
        'id_ponsel' => 'required|exists:ponsel,id_ponsel',
        'ulasan' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
        'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);

    

    $ulasan = new Ulasan();
    $ulasan->id_beli_ponsel = $request->id_beli_ponsel;
    $ulasan->id_ponsel = $request->id_ponsel;
    $ulasan->ulasan = $request->ulasan;
    $ulasan->rating = $request->rating;
    $ulasan->tanggal_ulasan = now();

    $ulasan->save();

    // Handle foto jika ada
    if ($request->hasFile('foto')) {
        foreach ($request->file('foto') as $foto) {
            $namaFile = $foto->store('ulasan_foto', 'public');
        }
    }

    return redirect()->back()->with('success', 'Ulasan berhasil dikirim!');
}

public function show($id) {
    $beliponsel = BeliPonsel::findOrFail($id);
    $ulasan = Ulasan::where('id_beli_ponsel', $id)->get();

    return view('customer.ulasan-show', compact('ulasan'));
}

}
