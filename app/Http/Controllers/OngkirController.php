<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OngkirService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class OngkirController extends Controller
{
    public function getKecamatan ($search) {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'key' => 'Nrd2USrEc1947001c21e8342lzEp9IlC'
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'search' => $search,
            'limit' => 999
        ]);

        return response()->json($response->json());
    }

    public function getOngkir (Request $request) {
        $data = OngkirService::getOngkir($request->destination, $request->courier);

        return response()->json([
        'meta' => ['status' => 'success'],
        'data' => $data
    ]);
    }
}
