<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // Pastikan password di-hash
            'nomor_telepon' => '081256573872',
        ]);

        $response = $this->post('/login', [
            'login' => 'test@example.com', // Gunakan 'login' sesuai dengan AuthController
            'password' => 'password123', // Password plain text
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($customer, 'web'); // Pastikan guard 'web' digunakan
    }
    public function test_user_cannot_login_with_incorrect_password(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '081256573872'
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_invalid_email(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '081256573872'
        ]);

        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_empty_fields(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $response = $this->post('/login', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_admin_can_login(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $admin = Admin::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('Admin@123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@gmail.com',
            'password' => 'Admin@123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertEquals($admin->id, Auth::guard('admin')->user()->id);
    }
}
