<?php

namespace Database\Factories;

use App\Models\Ponsel;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TukarTambah>
 */
class TukarTambahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
return [
            'id_customer' => Customer::factory(),
            'produk_tujuan_id' => Ponsel::factory(),

            'merk' => fake()->randomElement(['Samsung', 'Apple', 'Xiaomi', 'Oppo', 'Vivo', 'Realme', 'Asus']),
            'model' => fake()->lexify('Series-????') . ' ' . fake()->numerify('##'),
            'warna' => fake()->randomElement(['Hitam', 'Putih', 'Biru', 'Merah', 'Gold', 'Silver']),
            'ram' => fake()->randomElement([3, 4, 6, 8, 12]),
            'storage' => fake()->randomElement([32, 64, 128, 256, 512]),
            'processor' => fake()->randomElement([
                'Snapdragon 8 Gen 2',
                'Apple A16 Bionic',
                'MediaTek Dimensity 9200',
                'Snapdragon 778G',
                'Exynos 2200'
            ]),
            'kondisi' => fake()->randomElement(['mulus', 'normal', 'lecet ringan', 'lecet berat']),
            'deskripsi' => fake()->sentence(10),
            'harga_estimasi' => fake()->numberBetween(300000, 5000000),

            // Fake Storage aman untuk testing
            'gambar' => 'gambar/tukar-tambah/sample.png',

            // Status sesuai enum migration
            'status' => 'menunggu',

            // Dari migration kedua
            'catatan_admin' => null,

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
