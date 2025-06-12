<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Duitku\Api;
use Duitku\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function getPaymentMethods(Request $request)
{
    $duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001");
    $duitkuConfig->setSandboxMode(true);

    try {
        $paymentAmount = "10000"; // contoh nominal
        $paymentMethods = \Duitku\Api::getPaymentMethod($paymentAmount, $duitkuConfig);

        return response()->json($paymentMethods);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



    public function PaymentMethods(Request $request)
{
    $duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001"); // ganti dengan milikmu
    $duitkuConfig->setSandboxMode(true);

    try {
        $paymentAmount = "10000"; // nominal
        $response = \Duitku\Api::getPaymentMethod($paymentAmount, $duitkuConfig);

        $paymentMethods = json_decode($response, true);

        // Kirim ke view
        return view('paymentMethods', compact('paymentMethods'));

    } catch (Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

    public function showInvoiceForm()
    {
        return view('CreateInvoice');
    }

    public function createInvoice(Request $request)
    {
        try {
            $merchantCode = "D0001"; // Ganti dengan kode merchantmu
            $merchantKey = "732B39FC61796845775D2C4FB05332AF"; // Ganti dengan API key dari Duitku

            $duitkuConfig = new \Duitku\Config($merchantKey, $merchantCode);
            $duitkuConfig->setSandboxMode(true); // true = sandbox, false = production

            $paymentAmount = $request->paymentAmount;
            $productDetails = $request->productDetail;
            $email = $request->email;
            $phoneNumber = $request->phoneNumber;

            $paymentMethod = "BK"; // kosongkan jika ingin menampilkan semua metode
            $merchantOrderId = time();
            $additionalParam = '';
            $merchantUserInfo = '';
            $customerVaName = 'John Doe';
            $callbackUrl = url('/callback'); // sesuaikan jika kamu buat route callback
            $returnUrl = url('/return');
            $expiryPeriod = 60;

            $firstName = "John";
            $lastName = "Doe";

            $address = array(
                'firstName' => $firstName,
                'lastName' => $lastName,
                'address' => 'Jl. Kembangan Raya',
                'city' => 'Jakarta',
                'postalCode' => '11530',
                'phone' => $phoneNumber,
                'countryCode' => 'ID'
            );

            $customerDetail = array(
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'billingAddress' => $address,
                'shippingAddress' => $address
            );

            $itemDetails = array([
                'name' => $productDetails,
                'price' => $paymentAmount,
                'quantity' => 1
            ]);

            $params = array(
                'paymentAmount' => $paymentAmount,
                'paymentMethod' => $paymentMethod,
                'merchantOrderId' => $merchantOrderId,
                'productDetails' => $productDetails,
                'additionalParam' => $additionalParam,
                'merchantUserInfo' => $merchantUserInfo,
                'customerVaName' => $customerVaName,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'itemDetails' => $itemDetails,
                'customerDetail' => $customerDetail,
                'callbackUrl' => $callbackUrl,
                'returnUrl' => $returnUrl,
                'expiryPeriod' => $expiryPeriod
            );

            $response = \Duitku\Api::createInvoice($params, $duitkuConfig);

            return response()->json(json_decode($response));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}