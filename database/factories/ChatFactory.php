<?php

namespace Database\Factories;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition(): array
    {
        return [
            'sender_id' => $this->faker->numberBetween(1, 10),
            'sender_type' => $this->faker->randomElement(['admin', 'customer']),
            'receiver_id' => $this->faker->numberBetween(1, 10),
            'receiver_type' => $this->faker->randomElement(['admin', 'customer']),
            'message' => $this->faker->sentence(),
            'dibaca' => false,
        ];
    }

    // ✅ STATE: PESAN DARI CUSTOMER KE ADMIN
    public function fromCustomerToAdmin()
    {
        return $this->state(function () {
            return [
                'sender_type'  => 'customer',
                'receiver_type' => 'admin',
                'dibaca' => false,
            ];
        });
    }

    // ✅ STATE: PESAN DARI ADMIN KE CUSTOMER
    public function fromAdminToCustomer()
    {
        return $this->state(function () {
            return [
                'sender_type'  => 'admin',
                'receiver_type' => 'customer',
                'dibaca' => false,
            ];
        });
    }

    // ✅ STATE: PESAN SUDAH DIBACA
    public function dibaca()
    {
        return $this->state(function () {
            return [
                'dibaca' => true,
            ];
        });
    }
}
