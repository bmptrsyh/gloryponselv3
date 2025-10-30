<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function UserData(array $overrides = [])
    {
        return array_merge([
            'nama' => 'Bima Putrasyah',
            'email' => 'bima1@gmail.com',
            'password' => 'Bima@123',
            'password_confirmation' => 'Bima@123',
            'alamat' => 'Dusun Sumberwaru, RT 02 RW 05, Desa Tamanagung',
            'nomor_telepon' => '081252399368',
            'foto_profil' => UploadedFile::fake()->image('profil.jpg', 100, 100)->size(500),
        ], $overrides);
    }

    public function test_page_reegister_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    public function test_nama_lengkap_valid(): void
    {
        $response = $this->post('/register', $this->UserData());

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('customer', ['email' => 'bima1@gmail.com']);
    }

    public function test_nama_harus_diisi(): void
    {
        $response = $this->post('/register', $this->UserData(['nama' => '']));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['nama' => 'Nama lengkap harus diisi.']);
        $this->assertDatabaseMissing('customer', [
            'email' => 'bima1@gmail.com',
        ]);
    }
}
