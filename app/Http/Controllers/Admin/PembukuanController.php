<?php

namespace App\Http\Controllers\Admin;


use App\Models\Pembukuan;
use App\Models\BeliPonsel;
use App\Models\JualPonsel;
use App\Models\TukarTambah;
use App\Models\KreditPonsel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class PembukuanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $laporan = Pembukuan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();


        return view('admin.pembukuan.index', compact('laporan', 'bulan', 'tahun'));
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        $laporan = Pembukuan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.pembukuan.export_pdf', [
            'laporan' => $laporan,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan_pembukuan_{$bulan}_{$tahun}.pdf");
    }

    public function create()
    {
        return view('admin.pembukuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'deskripsi' => 'required|string|max:255',
            'debit' => 'nullable|numeric|min:0',
            'kredit' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'required|string',
        ]);
        $saldoTerakhir = Pembukuan::latest('id_laporan')->value('saldo');
        $saldoTerakhir = $saldoTerakhir ?? 0;
        $debit = $request->debit ?? 0;
        $kredit = $request->kredit ?? 0;
        $saldoBaru = $saldoTerakhir + $kredit - $debit;



        Pembukuan::create([
            'tanggal' => $request->tanggal,
            'transaksi_id' => null,
            'transaksi_type' => null,
            'deskripsi' => $request->deskripsi,
            'debit' => $request->debit ?? 0,
            'kredit' => $request->kredit ?? 0,
            'saldo' => $saldoBaru,
            'metode_pembayaran' => $request->metode_pembayaran ?? '-',
        ]);

        return redirect()->route('admin.pembukuan')->with('success', 'Data pembukuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $laporan = Pembukuan::findOrFail($id);
        return view('admin.pembukuan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Pembukuan::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'deskripsi' => 'required|string|max:255',
            'debit' => 'nullable|numeric|min:0',
            'kredit' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'string',
        ]);

        $debit = $request->debit ?? 0;
        $kredit = $request->kredit ?? 0;

        // 🔹 Ambil saldo sebelum transaksi yang diedit
        $laporanSebelum = Pembukuan::where('id_laporan', '<', $laporan->id_laporan)
            ->orderBy('id_laporan', 'desc')
            ->first();

        $saldoSebelumnya = $laporanSebelum ? $laporanSebelum->saldo : 0;

        // 🔹 Hitung saldo baru untuk transaksi yang diedit
        $saldoBaru = $saldoSebelumnya + $kredit - $debit;

        // 🔹 Update transaksi yang diedit
        $laporan->update([
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'debit' => $debit,
            'kredit' => $kredit,
            'saldo' => $saldoBaru,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        // 🔹 Recalculate saldo untuk semua transaksi setelahnya
        $saldoBerjalan = $saldoBaru;

        $laporanSetelahnya = Pembukuan::where('id_laporan', '>', $laporan->id_laporan)
            ->orderBy('id_laporan', 'asc')
            ->get();

        foreach ($laporanSetelahnya as $item) {
            $saldoBerjalan = $saldoBerjalan + $item->kredit - $item->debit;
            $item->update(['saldo' => $saldoBerjalan]);
        }

        return redirect()->route('admin.pembukuan')
            ->with('success', 'Data pembukuan berhasil diperbarui dan saldo diperbarui.');
    }



    public function destroy($id)
    {
        $laporan = Pembukuan::findOrFail($id);
        $deletedId = $laporan->id_laporan;

        $laporan->delete();

        $saldoSebelumnya = Pembukuan::where('id_laporan', '<', $deletedId)
            ->orderBy('id_laporan', 'desc')
            ->value('saldo') ?? 0;

        $laporanSetelahnya = Pembukuan::where('id_laporan', '>', $deletedId)
            ->orderBy('id_laporan', 'asc')
            ->get();


        foreach ($laporanSetelahnya as $item) {
            $saldoSebelumnya = $saldoSebelumnya + $item->kredit - $item->debit;

            $item->update([
                'saldo' => $saldoSebelumnya,
            ]);
        }

        return redirect()->route('admin.pembukuan')->with('success', 'Data pembukuan berhasil dihapus dan saldo diperbarui.');
    }
}
