<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KreditPonsel;
use Illuminate\Http\Request;

class AdminKreditController extends Controller
{
    public function index()
    {
        $kredit = KreditPonsel::with('customer', 'ponsel')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.kredit.index', compact('kredit'));
    }

    public function show($id)
    {
        $kredit = KreditPonsel::with('customer', 'ponsel')->findOrFail($id);

        return view('admin.kredit.show', compact('kredit'));
    }

    public function updateStatus(Request $request, $id)
    {
        $kredit = KreditPonsel::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak',
            'alasan_ditolak' => 'nullable|string|required_if:status,ditolak',
        ]);

        $kredit->status = $request->status;
        if ($request->status === 'ditolak') {
            $kredit->alasan_ditolak = $request->alasan_ditolak;
        } else {
            $kredit->alasan_ditolak = null; // reset kalau disetujui
        }

        if ($request->status === 'disetujui') {
            $kredit->alasan_ditolak = null; // reset alasan ditolak jika disetujui
        }



        $kredit->save();

        return redirect()->route('admin.kredit.index')->with('success', 'Status pengajuan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kredit = KreditPonsel::findOrFail($id);
        $kredit->delete();

        return redirect()->route('admin.kredit.index')->with('success', 'Pengajuan kredit berhasil dihapus.');
    }
}
