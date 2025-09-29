<?php

namespace App\Http\Controllers\Admin;

use App\Models\Angsuran;
use App\Models\KreditPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateCicilanController extends Controller
{
    public function show($id)
    {
        $kredit = KreditPonsel::with(['ponsel', 'angsuran' => function ($q) {
            $q->orderBy('bulan_ke', 'asc');
        }])
            ->where('id_kredit_ponsel', $id)
            ->firstOrFail();

        return view('admin.cicilan', compact('kredit'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum,lunas',
        ]);
        $angsuran = Angsuran::findOrFail($id);

        $angsuran->status = $request->status;

        if ($request->status === 'lunas') {
            $angsuran->tanggal_bayar = now();
        } else {
            $angsuran->tanggal_bayar = null;
        }

        $angsuran->save();

        return redirect()->back()->with('success', 'Status angsuran berhasil diperbarui!');
    }
}
