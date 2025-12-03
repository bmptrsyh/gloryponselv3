<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ponsel>
 */
class PonselFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        // Membuat logika harga agar masuk akal (Jual > Beli)
        $hargaBeli = fake()->numberBetween(1000000, 20000000); // 1jt - 20jt
        $margin = fake()->numberBetween(50000, 2000000); // Margin 50rb - 2jt
        
        $merk = fake()->randomElement(['Samsung', 'Apple', 'Xiaomi', 'Oppo', 'Vivo', 'Realme', 'Asus']);
        
        return [
            'merk' => $merk,
            // Menghasilkan model acak, misal: "S23 Ultra" atau "X-200"
            'model' => fake()->lexify('Series-????') . ' ' . fake()->numerify('##'),
            
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaBeli + $margin,
            
            'stok' => fake()->numberBetween(0, 100),
            'status' => fake()->randomElement(['baru', 'bekas']),
            
            'processor' => fake()->randomElement([
                'Snapdragon 8 Gen 2', 
                'Apple A16 Bionic', 
                'MediaTek Dimensity 9200', 
                'Snapdragon 778G',
                'Exynos 2200'
            ]),
            
            // Membuat dimensi string acak yang terlihat nyata
            'dimension' => fake()->numberBetween(140, 170) . ' x ' . fake()->numberBetween(70, 80) . ' x ' . fake()->randomFloat(1, 6, 9) . ' mm',
            
            // Pilihan RAM umum (GB)
            'ram' => fake()->randomElement([4, 6, 8, 12, 16]),
            
            // Pilihan Storage umum (GB)
            'storage' => fake()->randomElement([64, 128, 256, 512, 1024]),
            
            // Placeholder gambar (atau bisa diganti dengan fake()->imageUrl())
            'gambar' => 'storage/gambar/ponsel/default-placeholder.png',
        ];
    }
}
