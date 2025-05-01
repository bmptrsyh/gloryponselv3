<?php

namespace Database\Seeders;

use App\Models\Ponsel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PonselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ponsel::create(
        [
            'merk' => 'Samsung',
            'model' => 'Galaxy S23 Ultra',
            'harga_jual' => 12500000,
            'harga_beli' => 11000000,
            'stok' => 10,
            'status' => 'bekas',
            'processor' => 'Snapdragon 8 Gen 2',
            'dimension' => '146.3 x 70.9 x 7.6 mm',
            'ram' => 8,
            'storage' => 256,
            'gambar' => 'storage/gambar/ponsel/samsung-s23-ultra.png',
        ]);
    }
}
