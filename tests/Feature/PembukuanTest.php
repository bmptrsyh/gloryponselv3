<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Pembukuan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PembukuanTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    private function Data(array $overrides = []): array
    {
        return array_merge([
            'tanggal' => '2025-11-06',
            'deskripsi' => 'Pembelian alat kantor',
            'jenis_transaksi' => 'debit',
            'jumlah' => '100.000',
            'metode_pembayaran' => 'cash',
        ], $overrides);
    }

    public function test_input_tanggal_valid(): void
    {
        $this->actingAs($this->admin, 'admin');

        $tanggal_valid = $this->Data([
            'tanggal' => '2025-09-24',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $tanggal_valid);

        $response->assertStatus(302);

        $this->assertDatabaseHas('laporan_pembukuan', [
            'tanggal' => '2025-09-24',
            'deskripsi' => 'Pembelian alat kantor',
            'debit' => 100000,
            'kredit' => 0,
            'metode_pembayaran' => 'cash',
        ]);
    }

    public function test_input_tanggal_tidak_valid(): void
    {
        $this->actingAs($this->admin, 'admin');

        $tanggal_valid = $this->Data([
            'tanggal' => '24/09/2025',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $tanggal_valid);

        $response->assertSessionHasErrors(['tanggal' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD']);

        $this->assertDatabaseMissing('laporan_pembukuan', [
            'tanggal' => '24/09/2025',
        ]);
    }

    public function test_input_tanggal_kosong(): void
    {
        $this->actingAs($this->admin, 'admin');

        $tanggal_kosong = $this->Data([
            'tanggal' => '',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $tanggal_kosong);

        $response->assertSessionHasErrors(['tanggal' => 'Tanggal wajib diisi']);

        $this->assertDatabaseMissing('laporan_pembukuan', [
            'tanggal' => '',
            'kredit' => 0,
            'debit' => 100000,
        ]);
    }

    public function test_input_deskripsi_valid(): void
    {
        $this->actingAs($this->admin, 'admin');

        $deskripsi = $this->Data([
            'deskripsi' => 'Pembelian ponsel',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $deskripsi);

        $response->assertStatus(302);

        $this->assertDatabaseHas('laporan_pembukuan', [
            'deskripsi' => 'Pembelian ponsel'
        ]);
    }

    public function test_input_deskripsi_kosong(): void
    {
        $this->actingAs($this->admin, 'admin');

        $deskripsi = $this->Data([
            'deskripsi' => '',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $deskripsi);

        $response->assertSessionHasErrors(['deskripsi' => 'Deskripsi transaksi wajib diisi']);

        $this->assertDatabaseMissing('laporan_pembukuan', [
            'deskripsi' => ''
        ]);
    }
    public function test_input_debit_valid(): void
    {
        $this->actingAs($this->admin, 'admin');

        $kredit = $this->Data([
            'jenis_transaksi' => 'kredit',
            'jumlah' => '100.000',
        ]);

        $this->post(route('admin.pembukuan.store'), $kredit);

        $debit = $this->Data([
            'jenis_transaksi' => 'debit',
            'jumlah' => '10.000',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $debit);

        $response->assertStatus(302);

        $this->assertDatabaseHas('laporan_pembukuan', [
            'debit' => 10000,
            'kredit' => 0,
            'saldo' => 90000
        ]);
    }
    
    public function test_input_debit_nilai_nol(): void
    {
        $this->actingAs($this->admin, 'admin');

        $debit = $this->Data([
            'jenis_transaksi' => 'debit',
            'jumlah' => '0',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $debit);

        $response->assertStatus(302);

        $this->assertDatabaseHas('laporan_pembukuan', [
            'debit' => 0,
            'kredit' => 0,
            'saldo' => 0
        ]);
    }

    public function test_input_debit_nilai_negatif(): void
    {
        $this->actingAs($this->admin, 'admin');

        $debit = $this->Data([
            'jenis_transaksi' => 'debit',
            'jumlah' => '-100.000',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $debit);

        $response->assertSessionHasErrors(['jumlah' => 'Nilai Debit tidak boleh negatif']);

        $this->assertDatabaseMissing('laporan_pembukuan', [
            'debit' => -100000,
            'kredit' => 0,
        ]);
    }

    public function test_input_debit_non_angka(): void
    {
        $this->actingAs($this->admin, 'admin');

        $debit = $this->Data([
            'jenis_transaksi' => 'debit',
            'jumlah' => 'dua belas juta'
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $debit);

        $response->assertSessionHasErrors(['jumlah' => 'Debit harus berupa angka']);

        $this->assertDatabaseMissing('laporan_pembukuan', [
            'debit' => 'dua belas juta',
            'kredit' => 0,
        ]);
    }
    public function test_input_kredit_valid(): void
    {
        $this->actingAs($this->admin, 'admin');

        $kredit = $this->Data([
            'jenis_transaksi' => 'kredit',
            'jumlah' => '100.000',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $kredit);

        $response->assertStatus(302);

        $this->assertDatabaseHas('laporan_pembukuan', [
            'kredit' => 100000,
            'debit' => 0,
            'saldo' => 100000
        ]);
    }
    public function test_input_kredit_nilai_nol(): void
    {
        $this->actingAs($this->admin, 'admin');

        $kredit = $this->Data([
            'jenis_transaksi' => 'kredit',
            'jumlah' => '0',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $kredit);

        $response->assertStatus(302);

        $this->assertDatabaseHas('laporan_pembukuan', [
            'kredit' => 0,
            'debit' => 0,
            'saldo' => 0
        ]);
    }
    public function test_input_kredit_nilai_negatif(): void
    {
        $this->actingAs($this->admin, 'admin');

        $kredit = $this->Data([
            'jenis_transaksi' => 'kredit',
            'jumlah' => '-200.000'
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $kredit);

        $response->assertSessionHasErrors(['jumlah' => 'Nilai Kredit tidak boleh negatif']);

        $this->assertDatabaseMissing('laporan_pembukuan', [
            'kredit' => -200000,
            'debit' => 0,
            'saldo' => -200000
        ]);
    }

    public function test_input_kredit_non_angka(): void
    {
        $this->actingAs($this->admin, 'admin');

        $kredit = $this->Data([
            'jenis_transaksi' => 'kredit',
            'jumlah' => 'dua belas juta',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $kredit);

        $response->assertSessionHasErrors(['jumlah' => 'Kredit harus berupa angka']);

        $this->assertDatabaseMissing('laporan_pembukuan', [
            'kredit' => 'dua belas juta',
            'debit' => 0,
            'saldo' => 0
        ]);
    }

    public function test_input_metode_pembayaran_valid(): void
    {
        $this->actingAs($this->admin, 'admin');

        $metode = $this->Data([
            'metode_pembayaran' => 'cash',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $metode);

        $response->assertStatus(302);

        $this->assertDatabaseHas('laporan_pembukuan', [
            'metode_pembayaran' => 'cash'
        ]);
    }

    public function test_input_metode_pembayaran_kosong(): void
    {
        $this->actingAs($this->admin, 'admin');

        $metode = $this->Data([
            'metode_pembayaran' => '',
        ]);

        $response = $this->post(route('admin.pembukuan.store'), $metode);

        $response->assertSessionHasErrors(['metode_pembayaran' => 'Metode pembayaran wajib dipilih']);

        $this->assertDatabaseMissing('laporan_pembukuan', [
            'metode_pembayaran' => ''
        ]);
    }
}
