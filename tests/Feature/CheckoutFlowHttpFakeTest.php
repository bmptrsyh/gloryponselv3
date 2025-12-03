<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Ponsel;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Royryando\Duitku\Facades\Duitku;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutFlowHttpFakeTest extends TestCase
{
    use RefreshDatabase;

    /** -----------------------------------------------
     *  1. Checkout Berhasil
     * ----------------------------------------------*/
    public function test_checkout_flow_success_http_fake()
    {
        Http::fake([
            'https://rajaongkir.komerce.id/*' => Http::response([
                'meta' => ['status' => 'success'],
                'data' => [
                    ['cost' => 15000],
                ],
            ], 200),
        ]);

        Duitku::shouldReceive('createInvoice')
            ->once()
            ->andReturn([
                'payment_url' => 'https://duitku.test/pay/ABC123',
            ]);

        $customer = Customer::factory()->create(['alamat' => 'Jl Test']);
        $this->actingAs($customer, 'web');

        Ponsel::factory()->create([
            'id_ponsel' => 1,
            'harga_jual' => 2000000,
            'stok' => 5,
        ]);

        $payload = [
            'id_ponsel' => [1],
            'jumlah' => [2],
            'destination' => '50111',
            'courier' => 'jne',
            'fee' => 0,
            'harga' => 4000000 + 15000,
            'payment_method' => 'VA',
            'payment_method_name' => 'Virtual Account',
            'exp' => 60,
        ];

        $response = $this->post(route('checkout'), $payload);

        $response->assertRedirect('https://duitku.test/pay/ABC123');

        $this->assertDatabaseHas('beli_ponsel', [
            'id_ponsel' => 1,
            'jumlah' => 2,
            'status' => 'tertunda',
        ]);
    }

    /** -----------------------------------------------
     *  2. Gagal karena stok kurang
     * ----------------------------------------------*/
    public function test_checkout_fails_when_stock_insufficient()
    {
        Http::fake(); // Tidak masalah untuk test ini

        $customer = Customer::factory()->create(['alamat' => 'Jl Test']);
        $this->actingAs($customer, 'web');

        Ponsel::factory()->create([
            'id_ponsel' => 1,
            'harga_jual' => 2000000,
            'stok' => 1,
        ]);

        $payload = [
            'id_ponsel' => [1],
            'jumlah' => [5], // lebih dari stok
            'destination' => '50111',
            'courier' => 'jne',
            'fee' => 0,
            'harga' => 0,
            'payment_method' => 'VA',
            'payment_method_name' => 'Virtual Account',
            'exp' => 60,
        ];

        $response = $this->post(route('checkout'), $payload);

        $response->assertRedirect('/produk');
        $response->assertSessionHas('error');
    }

    /** -----------------------------------------------
     *  3. Gagal karena ongkir API error
     * ----------------------------------------------*/
    public function test_checkout_fails_when_ongkir_api_error()
    {
        Http::fake([
            'https://rajaongkir.komerce.id/*' => Http::response([
                'meta' => ['status' => 'error']
            ], 500),
        ]);

        $customer = Customer::factory()->create(['alamat' => 'Jl Test']);
        $this->actingAs($customer, 'web');

        Ponsel::factory()->create([
            'id_ponsel' => 1,
            'harga_jual' => 2000000,
            'stok' => 3,
        ]);

        $payload = [
            'id_ponsel' => [1],
            'jumlah' => [1],
            'destination' => '50111',
            'courier' => 'jne',
            'fee' => 0,
            'harga' => 2000000, // tapi ongkir gagal → mismatch
            'payment_method' => 'VA',
            'payment_method_name' => 'VA',
            'exp' => 60,
        ];

        $response = $this->post(route('checkout'), $payload);

        $response->assertRedirect('/produk');
        $response->assertSessionHas('error');
    }

    /** -----------------------------------------------
     *  4. Gagal karena harga dimanipulasi dari frontend
     * ----------------------------------------------*/
    public function test_checkout_fails_when_price_manipulated()
    {
        Http::fake([
            'https://rajaongkir.komerce.id/*' => Http::response([
                'meta' => ['status' => 'success'],
                'data' => [
                    ['cost' => 20000]
                ]
            ], 200),
        ]);

        $customer = Customer::factory()->create(['alamat' => 'Jl Test']);
        $this->actingAs($customer, 'web');

        Ponsel::factory()->create([
            'id_ponsel' => 1,
            'harga_jual' => 2000000,
            'stok' => 10,
        ]);

        $payload = [
            'id_ponsel' => [1],
            'jumlah' => [1],
            'destination' => '50111',
            'courier' => 'jne',
            'fee' => 0,
            'harga' => 500, // manipulasi
            'payment_method' => 'VA',
            'payment_method_name' => 'VA',
            'exp' => 60,
        ];

        $response = $this->post(route('checkout'), $payload);

        $response->assertRedirect('/produk');
        $response->assertSessionHas('error');
    }

    /** -----------------------------------------------
     *  5. Gagal ketika invoice Duitku gagal dibuat
     * ----------------------------------------------*/
    public function test_checkout_fails_when_invoice_creation_fails()
    {
        Http::fake([
            'https://rajaongkir.komerce.id/*' => Http::response([
                'meta' => ['status' => 'success'],
                'data' => [
                    ['cost' => 30000]
                ]
            ], 200),
        ]);

        $customer = Customer::factory()->create(['alamat' => 'Jl Test']);
        $this->actingAs($customer, 'web');

        // Mock duitku gagal
        Duitku::shouldReceive('createInvoice')
            ->once()
            ->andThrow(new \Exception('Duitku Error'));

        Ponsel::factory()->create([
            'id_ponsel' => 1,
            'harga_jual' => 1500000,
            'stok' => 10,
        ]);

        $payload = [
            'id_ponsel' => [1],
            'jumlah' => [1],
            'destination' => '50111',
            'courier' => 'jne',
            'fee' => 0,
            'harga' => 1500000 + 30000, 
            'payment_method' => 'VA',
            'payment_method_name' => 'VA',
            'exp' => 60,
        ];

        $response = $this->post(route('checkout'), $payload);

        $response->assertRedirect('/produk');
        $response->assertSessionHas('error');
    }
}
