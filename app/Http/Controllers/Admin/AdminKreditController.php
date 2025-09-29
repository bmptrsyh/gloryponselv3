<?php

namespace App\Http\Controllers\Admin;

use App\Models\Angsuran;
use App\Models\KreditPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $kredit = KreditPonsel::with('customer', 'ponsel', 'angsuran')->findOrFail($id);

        $jatuhTempo = null;
        if ($kredit->angsuran->isNotEmpty()) {
            $jatuhTempo = $kredit->angsuran->first()->jatuh_tempo;
        }

        return view('admin.kredit.show', compact('kredit', 'jatuhTempo'));
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

        $kredit->save();

        if ($kredit->status === 'disetujui') {
            $tanggal = $kredit->updated_at;
            $jatuhTempoAwal = \Carbon\Carbon::parse($tanggal)->addDays(7); // 7 hari setelah disetujui

            for ($i = 1; $i <= $kredit->tenor; $i++) {
                Angsuran::firstOrCreate(
                    [
                        'id_kredit_ponsel' => $kredit->id_kredit_ponsel,
                        'bulan_ke' => $i,
                    ],
                    [
                        'jumlah_cicilan' => $kredit->angsuran_per_bulan,
                        'jatuh_tempo' => $jatuhTempoAwal->copy()->addMonths($i - 1),
                        'status' => 'belum',
                    ]
                );
            }
        }

        return redirect()->route('admin.kredit.index')->with('success', 'Status pengajuan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kredit = KreditPonsel::findOrFail($id);
        $kredit->delete();

        return redirect()->route('admin.kredit.index')->with('success', 'Pengajuan kredit berhasil dihapus.');
    }
}
