<?php

namespace App\Http\Controllers\Customer;

use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Royryando\Duitku\Facades\Duitku;
use Royryando\Duitku\Http\Controllers\DuitkuBaseController;

class CallbackController extends DuitkuBaseController
{

        public function paymentCallback(Request $request)
    {
        return parent::paymentCallback($request);
    }
    
    protected function onPaymentSuccess(string $orderId, string $productDetail, int $amount, string $paymentCode, ?string $shopeeUserHash, string $reference, ?string $additionalParam): void
    {
        $beliponsel = BeliPonsel::where('code', $orderId)->first();
        if (!$beliponsel) return;
        $beliponsel->status = 'selesai';
        $beliponsel->save();
    }

        protected function onPaymentFailed(string $orderId, string $productDetail, int $amount, string $paymentCode, ?string $shopeeUserHash, string $reference, ?string $additionalParam): void
    {
        $beliponsel = BeliPonsel::where('code', $orderId)->first();
        if (!$beliponsel) return;
        /*
         * Transaction failed, just delete
         */
        $beliponsel->delete();
    }
        public function myReturnCallback() {
            $beliponsel = BeliPonsel::where('code', request('order_id'))->first();
            $beliponsel->status = 'selesai';
            $beliponsel->save();
            $jumlahDibeli = $beliponsel->jumlah;
            $ponsel = $beliponsel->ponsel;
            $ponsel->stok -= $jumlahDibeli;
            $ponsel->save();
            return redirect('/')->with('success', 'Pembayaran berhasil');
        }
}
