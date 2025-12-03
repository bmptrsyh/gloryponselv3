<?php

namespace Tests\Feature;

use App\Models\Ponsel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class KreditTahap1Test extends TestCase
{
    use RefreshDatabase;

    protected $customer;

    protected $ponsel;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup customer dan ponsel untuk testing
        $this->customer = User::factory()->create([
            'email' => 'customer@test.com',
            'name' => 'Test Customer',
        ]);

        // Buat ponsel secara manual tanpa factory
        $this->ponsel = Ponsel::create([
            'model' => 'iPhone 14 Pro Max',
            'merk' => 'Apple',
            'harga_jual' => 5000000,
            'harga_beli' => 4500000,
            'stok' => 10,
            'status' => 'bekas',
            'processor' => 'A16 Bionic',
            'dimension' => '146.7 x 71.5 x 7.65 mm',
            'ram' => 6,
            'storage' => 128,
            'gambar' => 'test.jpg',
            'warna' => 'Hitam',
        ]);

        Storage::fake('public');
    }

    public function test_memverifikasi_alur_pengajuan_kredit()
    {
        $this->actingAs($this->customer, 'web');

        // Step 1: Ajukan Kredit
        $response = $this->get(route('ajukan.kredit', ['id_produk' => $this->ponsel->id_ponsel]));
        $response->assertStatus(200);
        $response->assertViewIs('customer.kredit.data_pribadi');
        $this->assertEquals($this->ponsel->id_ponsel, session('kredit_produk_id'));
    }

    public function test_validasi_field_wajib_untuk_nama_lengkap()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => '',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('nama_lengkap');
    }

    public function test_field_nama_lengkap_dikosongkan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => '',
        ]);

        $response->assertSessionHasErrors('nama_lengkap');
    }

    public function test_field_nama_diisi_dengan_karakter_atau_angka()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'Test123@#',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('nama_lengkap');
    }

    public function test_validasi_field_wajib_untuk_nik()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_field_nik_dikosongkan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_field_nik_tidak_sesuai_digit()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '123456', // kurang dari 16 digit
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_field_nik_diisi_dengan_huruf_atau_karakter_lain()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '351001234567890A', // mengandung huruf
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_validasi_field_wajib_untuk_tempat_lahir()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => '',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('tempat_lahir');
    }

    public function test_field_tempat_lahir_dikosongkan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => '',
        ]);

        $response->assertSessionHasErrors('tempat_lahir');
    }

    public function test_validasi_field_wajib_untuk_tanggal_lahir()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('tanggal_lahir');
    }

    public function test_field_tanggal_lahir_dikosongkan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '',
        ]);

        $response->assertSessionHasErrors('tanggal_lahir');
    }

    public function test_field_tanggal_lahir_diisi_dengan_tanggal_masa_depan()
    {
        $this->actingAs($this->customer, 'web');

        $futureDate = now()->addYear()->format('m/d/Y');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => $futureDate,
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('tanggal_lahir');
    }

    public function test_validasi_field_wajib_untuk_jenis_kelamin()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => '',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('jenis_kelamin');
    }

    public function test_field_jenis_kelamin_memilih_pilihan_yang_tidak_ada()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Other', // pilihan tidak valid
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('jenis_kelamin');
    }

    public function test_validasi_field_wajib_untuk_status_pernikahan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => '',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('status_pernikahan');
    }

    public function test_field_status_pernikahan_memilih_pilihan_yang_tidak_ada()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Complicated', // pilihan tidak valid
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('status_pernikahan');
    }

    public function test_validasi_field_wajib_untuk_alamat_ktp()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => '',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('alamat_ktp');
    }

    public function test_field_alamat_ktp_dikosongkan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'alamat_ktp' => '',
        ]);

        $response->assertSessionHasErrors('alamat_ktp');
    }

    public function test_field_alamat_ktp_tidak_sesuai_dengan_ktp()
    {
        // Test ini memerlukan verifikasi manual atau OCR,
        // di sini kita hanya test validasi format
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'abc', // alamat terlalu pendek
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('alamat_ktp');
    }

    public function test_validasi_field_wajib_untuk_alamat_domisili()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => '',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('alamat_domisili');
    }

    public function test_field_alamat_domisili_dikosongkan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'alamat_domisili' => '',
        ]);

        $response->assertSessionHasErrors('alamat_domisili');
    }

    public function test_field_alamat_domisili_tidak_sesuai_dengan_domisili()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'ab', // alamat terlalu pendek
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('alamat_domisili');
    }

    public function test_validasi_field_wajib_untuk_nomor_telepon()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('no_telp');
    }

    public function test_field_nomor_telepon_dikosongkan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'no_telp' => '',
        ]);

        $response->assertSessionHasErrors('no_telp');
    }

    public function test_field_nomor_telepon_tidak_valid()
    {
        // Test ini memerlukan verifikasi eksternal (SMS/OTP)
        // Di sini kita hanya test format
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '123', // format tidak valid
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('no_telp');
    }

    public function test_validasi_field_wajib_untuk_alamat_email()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => '',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_field_alamat_email_dikosongkan()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'email' => '',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_field_alamat_email_tidak_sesuai_format()
    {
        $this->actingAs($this->customer, 'web');

        $response = $this->post(route('data.pribadi.store'), [
            'nama_lengkap' => 'John Doe',
            'nik' => '3510012345678901',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '01/15/1990',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'alamat_ktp' => 'Jl. Test No. 1',
            'alamat_domisili' => 'Jl. Test No. 1',
            'no_telp' => '081234567890',
            'email' => 'invalid-email', // format tidak valid
        ]);

        $response->assertSessionHasErrors('email');
    }
}
