<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BeliPonsel>
 */
class BeliPonselFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_customer' => 1,
            'id_ponsel' => rand(1,3),
            'metode_pembayaran' => $this->faker->randomElement(['cash', 'credit']),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'tanggal_transaksi' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'jumlah' => $this->faker->numberBetween(1, 5),
            'harga' => $this->faker->numberBetween(1000000, 5000000),
            'code' => $this->faker->uuid(),
            'status_pengiriman' => $this->faker->randomElement(['pending', 'shipped', 'delivered']),
        ];
    }
}
