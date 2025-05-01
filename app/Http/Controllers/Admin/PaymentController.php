<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ponsel;
use Duitku\Api as DuitkuApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function getPaymentMethods(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|integer',
            'jumlah' => 'required|integer|min:1'
        ]);
        
        $duitkuConfig = new \Duitku\Config("f48433d111804caf8cd88f6728f8b070", "DS22831");
        $duitkuConfig->setSandboxMode(true);

        try {
            $produkId = $request->produk_id;
            $produk = Ponsel::findOrFail($produkId);

            $price = $produk->harga_jual;


            // Ambil harga produk dari database berdasarkan $validated['produk_id']
            // Ini contoh, ganti dengan query database
            
            $paymentAmount = $price * $validated['jumlah'];
            $paymentMethodList = DuitkuApi::getPaymentMethod($paymentAmount, $duitkuConfig);
            $productPrice = $paymentAmount;

            return response()->json([
                'success' => true,
                'payment_methods' => json_decode($paymentMethodList, true),
                'product_id' => $validated['produk_id'],
                'quantity' => $validated['jumlah'],
                'total_amount' => $paymentAmount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve payment methods: ' . $e->getMessage(),
            ], 500);
        }
    }

}
