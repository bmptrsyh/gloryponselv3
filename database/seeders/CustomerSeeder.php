<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'nama' => 'Customer Glory Ponsel',
            'email' => 'customer@gmail.com',
            'nomor_telepon' => '08123456789',
            'password' => Hash::make('123456'),

        ]);
    }
}
