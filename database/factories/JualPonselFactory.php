<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JualPonsel>
 */
class JualPonselFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_customer'    => Customer::factory(), // relasi ke customer
            'merk'           => $this->faker->randomElement(['Samsung', 'Xiaomi', 'iPhone']),
            'model'          => $this->faker->word(),
            'warna'          => $this->faker->safeColorName(),
            'ram'            => $this->faker->randomElement([4, 6, 8, 12]),
            'storage'        => $this->faker->randomElement([64, 128, 256, 512]),
            'processor'      => $this->faker->word(),
            'kondisi'        => $this->faker->sentence(),
            'deskripsi'      => $this->faker->paragraph(),
            'harga'          => $this->faker->numberBetween(500000, 5000000),
            'gambar'         => 'storage/' . $this->faker->word() . '.jpg',
            'status'         => 'menunggu',
            'created_at'     => now(),
            'updated_at'     => now(),

        ];
    }
}
