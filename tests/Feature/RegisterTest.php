<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function UserData(array $overrides = [])
    {
        return array_merge([
            'nama' => 'Bima Putrasyah',
            'email' => 'bima@gmail.com',
            'password' => 'Bima@123',
            'password_confirmation' => 'Bima@123',
            'alamat' => 'Dusun Sumberwaru, RT 02 RW 05, Desa Tamanagung',
            'nomor_telepon' => '081252399368',
            'foto_profil' => UploadedFile::fake()->image('profil.jpg', 100, 100)->size(500),
        ], $overrides);
    }

    public function test_page_register_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    public function test_nama_lengkap_valid(): void
    {
        $userData = $this->UserData();
        $response = $this->post('/register', $userData);

        $response->assertRedirect('/login');

        $this->assertDatabaseHas('customer', ['email' => 'bima@gmail.com']);

    }

    public function test_nama_harus_diisi(): void
    {
        $response = $this->post('/register', $this->UserData(['nama' => '']));

        $response->assertSessionHasErrors(['nama' => 'Nama lengkap harus diisi']);
        $this->assertDatabaseMissing('customer', [
            'email' => 'bima1@gmail.com',
        ]);
    }

    public function test_nama_kurang_dari_2_karakter(): void
    {
        $response = $this->post('/register', $this->UserData(
            ['nama' => 'B']
        ));
        $response->assertSessionHasErrors(['nama' => 'Nama lengkap harus terdiri dari minimal 2 karakter']);
        $this->assertDatabaseMissing('customer', [
            'nama' => 'B',
        ]);
    }

    public function test_nama_lebih_dari_50_karakter(): void
    {
        $response = $this->post('/register', $this->UserData(
            ['nama' => 'Bima Putrasyah Bima Putasyah Bima Putasyah Bima Putrasyah']
        ));

        $response->assertSessionHasErrors(['nama' => 'Nama lengkap tidak boleh lebih dari 50 karakter']);
        $this->assertDatabaseMissing('customer', [
            'nama' => 'Bima Putrasyah Bima Putasyah Bima Putasyah Bima Putrasyah',
        ]);
    }

    public function test_nama_numerik(): void
    {
        $response = $this->post('/register', $this->UserData(
            ['nama' => 12345]
        ));

        $response->assertSessionHasErrors(['nama' => 'Nama hanya boleh huruf']);
        $this->assertDatabaseMissing('customer', [
            'nama' => 12345,
        ]);
    }

    public function test_nama_tidak_boleh_simbol(): void
    {
        $response = $this->post('/register', $this->UserData(
            ['nama' => 'Bima@Putrasyah!']
        ));

        $response->assertSessionHasErrors(['nama' => 'Nama tidak boleh mengandung simbol']);
        $this->assertDatabaseMissing('customer', [
            'nama' => 'Bima@Putrasyah!',
        ]);
    }

    public function test_email_valid(): void
    {
        $response = $this->post('/register', $this->UserData(
            ['email' => 'bima@gmail.com']
        ));

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('customer', [
            'email' => 'bima@gmail.com',
        ]);
    }

    public function test_email_kosong(): void
    {
        $response = $this->post('/register', $this->UserData(
            ['email' => '']
        ));

        $response->assertSessionHasErrors(['email' => 'Email wajib diisi']);
        $this->assertDatabaseMissing('customer', [
            'nama' => $this->UserData()['nama'],
        ]);
    }

    public function test_email_salah_format(): void
    {
        $response = $this->post('/register', $this->UserData(
            ['email' => 'bima@@gmail.com']
        ));

        $response->assertSessionHasErrors(['email' => 'Format email tidak valid']);
        $this->assertDatabaseMissing('customer', [
            'nama' => $this->UserData()['nama'],
        ]);
    }

    public function test_email_duplikat(): void
    {
        $emailCustomer = $this->UserData(
            ['email' => 'bima@gmail.com',
                'nomor_telepon' => '081234567890']
        );
        $this->post('/register', $emailCustomer);

        $emailDuplikat = $this->UserData(
            ['email' => 'bima@gmail.com']
        );
        $response = $this->post('/register', $emailDuplikat);

        $response->assertSessionHasErrors(['email' => 'Email sudah terdaftar']);

        $this->assertDatabaseHas('customer', [
            'email' => 'bima@gmail.com',
        ]);
        $this->assertDatabaseCount('customer', 1);
    }

    public function test_password_valid(): void
    {
        $passwordValid = $this->UserData([
            'password' => 'Bima@123',
        ]);

        $response = $this->post('/register', $passwordValid);
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('customer', [
            'email' => $passwordValid['email'],
        ]);
    }

    public function test_password_kosong(): void
    {
        $passwordKosong = $this->UserData([
            'password' => '',
        ]);

        $response = $this->post('/register', $passwordKosong);
        $response->assertSessionHasErrors(['password' => 'Password wajib diisi']);

        $this->assertDatabaseMissing('customer', [
            'email' => $passwordKosong['email'],
            'nama' => $passwordKosong['nama'],
        ]);
    }

    public function test_password_kurang_dari_8_karakter(): void
    {
        $passwordPendek = $this->UserData([
            'password' => 'Bima@1',
            'password_confirmation' => 'Bima@1',
        ]);

        $response = $this->post('/register', $passwordPendek);
        $response->assertSessionHasErrors(['password' => 'Password minimal 8 karakter']);

        $this->assertDatabaseMissing('customer', [
            'email' => $passwordPendek['email'],
            'nama' => $passwordPendek['nama'],
        ]);
    }

    public function test_password_tanpa_angka(): void
    {
        $passwordTanpaAngka = $this->UserData([
            'password' => 'Bima@bim',
            'password_confirmation' => 'Bima@bim',
        ]);

        $response = $this->post('/register', $passwordTanpaAngka);
        $response->assertSessionHasErrors(['password' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol']);

        $this->assertDatabaseMissing('customer', [
            'email' => $passwordTanpaAngka['email'],
            'nama' => $passwordTanpaAngka['nama'],
        ]);
    }

    public function test_password_tanpa_huruf_besar(): void
    {
        $passwordLowercase = $this->UserData([
            'password' => 'bima@123',
        ]);

        $response = $this->post('/register', $passwordLowercase);
        $response->assertSessionHasErrors(['password' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol']);

        $this->assertDatabaseMissing('customer', [
            'email' => $passwordLowercase['email'],
            'nama' => $passwordLowercase['nama'],
        ]);
    }

    public function test_password_tanpa_simbol(): void
    {
        $passwordTanpaSimbol = $this->UserData([
            'password' => 'bima1234',
        ]);

        $response = $this->post('/register', $passwordTanpaSimbol);
        $response->assertSessionHasErrors(['password' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol']);

        $this->assertDatabaseMissing('customer', [
            'email' => $passwordTanpaSimbol['email'],
            'nama' => $passwordTanpaSimbol['nama'],
        ]);
    }

    public function test_konfirmasi_password_sama(): void
    {
        $konfirmasiPasswordSama = $this->UserData([
            'password' => 'Bima@123',
            'password_confirmation' => 'Bima@123',
        ]);

        $response = $this->post('/register', $konfirmasiPasswordSama);
        $response->assertStatus(302);

        $this->assertDatabaseHas('customer', [
            'email' => 'bima@gmail.com',
            'nama' => 'Bima Putrasyah',
        ]);
    }

    public function test_konfirmasi_password_kosong(): void
    {
        $konfirmasiPasswordKosong = $this->UserData([
            'password' => 'Bima@123',
            'password_confirmation' => '',
        ]);

        $response = $this->post('/register', $konfirmasiPasswordKosong);
        $response->assertSessionHasErrors(['password_confirmation' => 'Konfirmasi password wajib diisi']);

        $this->assertDatabaseMissing('customer', [
            'email' => $konfirmasiPasswordKosong['email'],
            'nama' => $konfirmasiPasswordKosong['nama'],
        ]);
    }

    public function test_konfirmasi_password_tidak_sama(): void
    {
        $konfirmasiPasswordTidakSama = $this->UserData([
            'password' => 'Bima@123',
            'password_confirmation' => 'Bima@1234',
        ]);

        $response = $this->post('/register', $konfirmasiPasswordTidakSama);
        $response->assertSessionHasErrors(['password' => 'Konfirmasi password tidak cocok']);

        $this->assertDatabaseMissing('customer', [
            'email' => $konfirmasiPasswordTidakSama['email'],
            'nama' => $konfirmasiPasswordTidakSama['nama'],
        ]);
    }

    public function test_alamat_valid(): void
    {
        $alamatValid = $this->UserData([
            'alamat' => 'Dusun Sumberwaru, RT 02 RW 05, Desa Tamanagung, Kecamatan Cluring, Kabupaten Banyuwangi',
        ]);

        $response = $this->post('/register', $alamatValid);
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('customer', [
            'email' => $alamatValid['email'],
            'nama' => $alamatValid['nama'],
            'alamat' => $alamatValid['alamat'],
        ]);
    }

    public function test_alamat_lebih_100_karakter(): void
    {
        $alamatPanjang = $this->UserData([
            'alamat' => 'Dusun Sumberwaru RT 02 RW 05 Desa Tamanagung Kecamatan Cluring Kabupaten Banyuwangi Jawa Timur Indonesia, Jalan Raya Panjang Sekali Nomor 12345',
        ]);

        $response = $this->post('/register', $alamatPanjang);
        $response->assertSessionHasErrors(['alamat' => 'Alamat maksimal 100 karakter']);

        $this->assertDatabaseMissing('customer', [
            'email' => $alamatPanjang['email'],
            'nama' => $alamatPanjang['nama'],
        ]);
    }

    public function test_nomor_telepon_valid(): void
    {
        $nomorTeleponValid = $this->UserData([
            'nomor_telepon' => '081234567890',
        ]);

        $response = $this->post('/register', $nomorTeleponValid);
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('customer', [
            'email' => $nomorTeleponValid['email'],
            'nama' => $nomorTeleponValid['nama'],
            'nomor_telepon' => $nomorTeleponValid['nomor_telepon'],
        ]);
    }

    public function test_nomor_telepon_kosong(): void
    {
        $nomorTeleponKosong = $this->UserData([
            'nomor_telepon' => '',
        ]);

        $response = $this->post('/register', $nomorTeleponKosong);
        $response->assertSessionHasErrors(['nomor_telepon' => 'Nomor telepon wajib diisi']);

        $this->assertDatabaseMissing('customer', [
            'email' => $nomorTeleponKosong['email'],
            'nama' => $nomorTeleponKosong['nama'],
        ]);
    }

    public function test_nomor_telepon_kurang_10_digit(): void
    {
        $nomorTeleponKurang = $this->UserData([
            'nomor_telepon' => '08123456',
        ]);

        $response = $this->post('/register', $nomorTeleponKurang);
        $response->assertSessionHasErrors(['nomor_telepon' => 'Nomor telepon minimal 10 digit']);

        $this->assertDatabaseMissing('customer', [
            'email' => $nomorTeleponKurang['email'],
            'nama' => $nomorTeleponKurang['nama'],
        ]);
    }

    public function test_nomor_telepon_lebih_15_digit(): void
    {
        $nomorTeleponLebih = $this->UserData([
            'nomor_telepon' => '0812345678901234',
        ]);

        $response = $this->post('/register', $nomorTeleponLebih);
        $response->assertSessionHasErrors(['nomor_telepon' => 'Nomor telepon maksimal 15 digit']);

        $this->assertDatabaseMissing('customer', [
            'email' => $nomorTeleponLebih['email'],
            'nama' => $nomorTeleponLebih['nama'],
        ]);
    }

    public function test_nomor_telepon_dengan_huruf(): void
    {
        $nomorTeleponHuruf = $this->UserData([
            'nomor_telepon' => '08123456789a',
        ]);

        $response = $this->post('/register', $nomorTeleponHuruf);
        $response->assertSessionHasErrors(['nomor_telepon' => 'Nomor telepon harus berupa angka']);

        $this->assertDatabaseMissing('customer', [
            'email' => $nomorTeleponHuruf['email'],
            'nama' => $nomorTeleponHuruf['nama'],
        ]);
    }

    public function test_nomor_telepon_duplikat(): void
    {
        $nomorTeleponAwal = $this->UserData([
            'nama' => 'Bima',
            'email' => 'bima1@gmail.com',
            'nomor_telepon' => '081234567890',
        ]);
        $this->post('/register', $nomorTeleponAwal);

        $nomorTeleponDuplikat = $this->UserData([
            'nama' => 'putra',
            'email' => 'putra@gmail.com',
            'nomor_telepon' => '081234567890',
        ]);

        $response = $this->post('/register', $nomorTeleponDuplikat);
        $response->assertSessionHasErrors(['nomor_telepon' => 'Nomor telepon sudah terdaftar']);

        $this->assertDatabaseMissing('customer', [
            'email' => $nomorTeleponDuplikat['email'],
            'nama' => $nomorTeleponDuplikat['nama'],
        ]);
    }

    public function test_foto_profil_valid(): void
    {
        $fotoProfil = $this->UserData([
            'foto_profil' => UploadedFile::fake()->image('profil.jpg')->size(500),
        ]);

        $response = $this->post('/register', $fotoProfil);
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('customer', [
            'email' => $fotoProfil['email'],
            'nama' => $fotoProfil['nama'],
        ]);
    }

    public function test_foto_profil_salah_format(): void
    {
        $fotoProfilSalahFormat = $this->UserData([
            'foto_profil' => UploadedFile::fake()->image('profil.txt')->size(500),
        ]);

        $response = $this->post('/register', $fotoProfilSalahFormat);
        $response->assertSessionHasErrors(['foto_profil' => 'File harus berupa gambar']);

        $this->assertDatabaseMissing('customer', [
            'email' => $fotoProfilSalahFormat['email'],
            'nama' => $fotoProfilSalahFormat['nama'],
        ]);
    }

    public function test_foto_profil_ukuran_besar(): void
    {
        $fotoProfilUkuranBesar = $this->UserData([
            'foto_profil' => UploadedFile::fake()->image('profil.jpg')->size(2100),
        ]);

        $response = $this->post('/register', $fotoProfilUkuranBesar);
        $response->assertSessionHasErrors(['foto_profil' => 'Ukuran foto maksimal 2MB']);

        $this->assertDatabaseMissing('customer', [
            'email' => $fotoProfilUkuranBesar['email'],
            'nama' => $fotoProfilUkuranBesar['nama'],
        ]);
    }
}
