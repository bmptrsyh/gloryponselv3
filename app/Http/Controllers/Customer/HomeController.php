<?php

namespace App\Http\Controllers\Customer;

use App\Models\Ponsel;
use App\Models\Ulasan;
use App\Models\Customer;
use App\Models\JualPonsel;
use App\Models\TukarTambah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        
        $produkTerbaru = Ponsel::orderBy('created_at', 'desc')->take(4)->get();

        // Ambil data customer yang sedang login
        $customer = Auth::user();
        
    
        return view('customer.home', compact('produkTerbaru', 'customer'));
    }

    public function daftarPengajuan() 
    {
        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;
        $jualPonsel = JualPonsel::where('id_customer', $id_customer)->get();
        $tukarTambah = TukarTambah::where('id_customer', $id_customer)->get();

        return view ('customer.daftar-pengajuan', compact('jualPonsel', 'tukarTambah'));
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
