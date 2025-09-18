<?php

namespace App\Http\Controllers\Customer;

use App\Models\Ponsel;
use App\Models\Ulasan;
use App\Models\Customer;
use App\Models\BeliPonsel;
use App\Models\JualPonsel;
use App\Models\TukarTambah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KreditPonsel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {

        $ip = request()->ip();
    $monthKey = 'visitors:' . now()->format('Y-m');

    $visitors = Cache::get($monthKey, []);

    if (!in_array($ip, $visitors)) {
        $visitors[] = $ip;
        $expires = now()->endOfMonth()->diffInMinutes(now());
        Cache::put($monthKey, $visitors, now()->addMinutes($expires));
    }

    $jumlahPengunjung = count($visitors);

        
        $produkTerbaru = Ponsel::orderBy('created_at', 'desc')->take(4)->get();
        $ulasans = Ulasan::with('ponsel')->get();
        $countUlasan = Ulasan::count();
        $customers = Customer::all();
        $count = Customer::count();
        $beliPonsel = BeliPonsel::all();
        $countBeliPonsel = BeliPonsel::count();
        

        // Ambil data customer yang sedang login
        $customer = Auth::user();
        
    
        return view('customer.home', compact('produkTerbaru', 'customer', 'ulasans','count', 'countBeliPonsel', 'countUlasan', 'jumlahPengunjung'));
    }

    public function daftarPengajuan() 
    {
        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;
        $jualPonsel = JualPonsel::where('id_customer', $id_customer)->get();
        $tukarTambah = TukarTambah::where('id_customer', $id_customer)->get();

        return view ('customer.daftar-pengajuan', compact('jualPonsel', 'tukarTambah'));
    }

    public function daftarKredit()
    {
        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;
        $kreditPonsel = KreditPonsel::with('ponsel')
            ->where('id_customer', $id_customer)
            ->get();

        return view('customer.daftar-kredit', compact('kreditPonsel'));
    }

    public function daftarKreditShow($id)
    {
        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;
        $kredit = KreditPonsel::with('ponsel')
            ->where('id_customer', $id_customer)
            ->where('id_kredit_ponsel', $id)
            ->firstOrFail();
        return view('customer.show-kredit', compact('kredit'));
    }

        public function showJual($id)
        {
            $pengajuan = JualPonsel::findOrFail($id);

            return view('customer.pengajuan.show-jual', compact('pengajuan'));
        }

        public function showTukar($id)
        {
            $pengajuan = TukarTambah::with('produkTujuan')->findOrFail($id);
            return view('customer.pengajuan.show-tukar', compact('pengajuan'));
        }
}
