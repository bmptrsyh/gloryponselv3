<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_page_can_be_rendered(): void
    {
        $response = $this->get('/reset-password');
        $response->assertStatus(200);
    }

    /**
     * TCRPW-001: Reset password dengan konfirmasi yang cocok
     */
    public function test_user_can_reset_password_with_matching_confirmation(): void
    {
        Mail::fake();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('OldPassword123'),
        ]);

        // 1️⃣ Send OTP
        $response = $this->post(route('password.otp.send'), [
            'email' => 'test@example.com',
        ]);
        $response->assertStatus(302);

        // 2️⃣ Get OTP from database
        $customer->refresh();
        $otp = $customer->otp;

        // 3️⃣ Verify OTP
        $response = $this->post(route('password.otp.verify'), [
            'otp' => $otp,
        ]);
        $response->assertStatus(302);

        // 4️⃣ Reset password
        $response = $this->post(route('password.update'), [
            'password' => 'PasswordBaru123',
            'password_confirmation' => 'PasswordBaru123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(Hash::check('PasswordBaru123', $customer->fresh()->password));
    }

    /**
     * TCRPW-002: Reset password dengan konfirmasi tidak cocok
     */
    public function test_reset_password_with_mismatched_confirmation_fails(): void
    {
        Mail::fake();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('OldPassword123'),
        ]);

        // Send and verify OTP
        $this->post('/password/send-otp', ['email' => 'test@example.com']);
        $customer->refresh();
        $this->post('/password/verify-otp', ['otp' => $customer->otp]);

        $response = $this->post('/reset-password', [
            'password' => 'PasswordBaru123',
            'password_confirmation' => 'PasswordBeda456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    /**
     * TCRPW-003: Reset password dengan konfirmasi kosong
     */
    public function test_reset_password_with_empty_confirmation_field(): void
    {
        Mail::fake();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create(['email' => 'test@example.com']);
        $this->post('/password/send-otp', ['email' => 'test@example.com']);
        $customer->refresh();
        $this->post('/password/verify-otp', ['otp' => $customer->otp]);

        $response = $this->post('/reset-password', [
            'password' => 'PasswordBaru123',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    /**
     * TCRPW-004: Reset password dengan password baru kosong
     */
    public function test_reset_password_with_empty_new_password_field(): void
    {
        Mail::fake();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create(['email' => 'test@example.com']);
        $this->post('/password/send-otp', ['email' => 'test@example.com']);
        $customer->refresh();
        $this->post('/password/verify-otp', ['otp' => $customer->otp]);

        $response = $this->post('/reset-password', [
            'password' => '',
            'password_confirmation' => 'PasswordBaru123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    /**
     * TCRPW-005: Reset password dengan kedua field kosong
     */
    public function test_reset_password_with_both_fields_empty(): void
    {
        Mail::fake();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create(['email' => 'test@example.com']);
        $this->post('/password/send-otp', ['email' => 'test@example.com']);
        $customer->refresh();
        $this->post('/password/verify-otp', ['otp' => $customer->otp]);

        $response = $this->post('/reset-password', [
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    /**
     * TCRPW-006: Reset password dengan password lemah
     */
    public function test_reset_password_with_weak_password_fails(): void
    {
        Mail::fake();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $customer = Customer::factory()->create(['email' => 'test@example.com']);
        $this->post('/password/send-otp', ['email' => 'test@example.com']);
        $customer->refresh();
        $this->post('/password/verify-otp', ['otp' => $customer->otp]);

        $response = $this->post('/reset-password', [
            'password' => 'lemah',
            'password_confirmation' => 'lemah',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }
}
