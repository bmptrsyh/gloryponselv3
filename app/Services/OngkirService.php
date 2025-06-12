<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OngkirService
{
public static function getOngkir($destination, $courier, $weight = 1000)
{
    $response = Http::asForm()->withHeaders([
        'accept' => 'application/json',
        'key' => 'Nrd2USrEc1947001c21e8342lzEp9IlC'
    ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
        'origin' => 68413,
        'destination' => $destination,
        'weight' => $weight,
        'courier' => $courier,
        'price' => 'lowest'
    ]);

    $data = $response->json();
        if (isset($data['meta']['status']) && $data['meta']['status'] === 'success') {
        // Ambil hanya satu (pertama) data ongkir
        return isset($data['data'][0]) ? [$data['data'][0]] : [];
    }

    return [];
}

}
