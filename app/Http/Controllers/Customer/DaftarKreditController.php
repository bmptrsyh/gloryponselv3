<?php

namespace App\Http\Controllers\Customer;

use Carbon\Carbon;
use App\Models\KreditPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DaftarKreditController extends Controller
{
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

        $jatuhTempo = null;
        if ($kredit->status === 'disetujui') {
            $tanggal = $kredit->tanggal_disetujui ?? $kredit->updated_at;
            $jatuhTempo = \Carbon\Carbon::parse($tanggal)->addWeek();
        }
        return view('customer.show-kredit', compact('kredit', 'jatuhTempo'));
    }
}
