<?php

namespace App\Http\Controllers\Admin;

use App\Models\Chat;
use App\Models\Ponsel;
use App\Models\Pembukuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BeliPonsel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // Daftar bulan Indonesia
        $bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        // Dapatkan bulan yang sedang dipilih
        $currentMonth = Carbon::now()->format('F');
        $currentMonthIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $selectedMonth = $request->get('bulan') ?? $currentMonthIndo[$currentMonth];
        $monthNumber = array_search($selectedMonth, $bulan) + 1;


        $totalTransaksi = Pembukuan::where('kredit', '!=', 0)->count();
        $transaksiTertunda = BeliPonsel::where('status', 'tertunda')->count();

        // Total pendapatan bulan ini (hanya transaksi kredit)
        $totalPendapatan = Pembukuan::whereMonth('tanggal', $monthNumber)
            ->whereYear('tanggal', date('Y'))
            ->sum('kredit');

        // ===========================
        // 📈 Grafik Penjualan (berdasarkan tanggal di bulan terpilih)
        // ===========================
        // ===========================
        // 📈 Grafik Penjualan (berdasarkan tanggal di bulan terpilih)
        // ===========================
        $grafikPenjualan = Pembukuan::select(
            DB::raw('DAY(tanggal) as hari'),
            DB::raw('SUM(kredit) as total')
        )
            ->whereMonth('tanggal', $monthNumber)
            ->whereYear('tanggal', date('Y'))
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        // Tentukan jumlah hari dalam bulan terpilih
        $daysInMonth = Carbon::createFromDate(date('Y'), $monthNumber, 1)->daysInMonth;

        // Buat array default hari 1–N dengan nilai 0
        $values = [];
        $labels = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = 'Hari ' . $i;
            $values[$i] = 0;
        }

        // Isi nilai dari hasil query (kalau ada transaksi)
        foreach ($grafikPenjualan as $data) {
            $values[$data->hari] = $data->total;
        }

        // Hapus index numerik (gunakan array biasa agar cocok di view)
        $values = array_values($values);


        // ===========================
        // 🧾 Daftar Transaksi Terkini
        // ===========================
        $transaksiTerkini = Pembukuan::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'bulan',
            'selectedMonth',
            'totalTransaksi',
            'totalPendapatan',
            'transaksiTertunda',
            'labels',
            'values',
            'transaksiTerkini'
        ));
    }
}
