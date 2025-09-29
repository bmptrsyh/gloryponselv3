<?php

namespace App\Http\Controllers\Customer;

use Carbon\Carbon;
use App\Models\Angsuran;
use App\Models\KreditPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AngsuranController extends Controller
{
    public function detailCicilan($id)
    {
        $customer = auth('web')->user();
        $id_customer = $customer->id_customer;

        $kredit = KreditPonsel::with(['ponsel', 'angsuran' => function ($q) {
            $q->orderBy('bulan_ke', 'asc');
        }])
            ->where('id_customer', $id_customer)
            ->where('id_kredit_ponsel', $id)
            ->firstOrFail();

        $angsuran_pertama = $kredit->angsuran->first()->jatuh_tempo;
        $jatuhTempo = Carbon::parse($angsuran_pertama);


        return view('customer.kredit.detailcicilan', compact('kredit', 'jatuhTempo'));
    }
}
