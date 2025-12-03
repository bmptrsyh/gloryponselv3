<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembukuan>
 */
class PembukuanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_laporan'       => $this->faker->unique()->randomNumber(),
            'transaksi_id'     => null,
            'transaksi_type'   => null,
            'tanggal'          => now(),
            'deskripsi'        => $this->faker->sentence(),
            'debit'            => $this->faker->numberBetween(100000, 2000000),
            'kredit'           => 0,
            'saldo'            => $this->faker->numberBetween(1000000, 5000000),
            'metode_pembayaran'=> null,
            'created_at'       => now(),
            'updated_at'       => now(),

        ];
    }
}
