<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\JualPonsel;
use App\Models\Pembukuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminJualPonselTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup user admin untuk login (pastikan Model Admin dan factory-nya ada)
        // Jika error Class 'App\Models\Admin' not found, kembalikan ke User::factory()
        $this->admin = Admin::factory()->create();
    }

    public function test_halaman_index_bisa_diakses_dan_menampilkan_data()
    {

        // Arrange
        $customer = Customer::factory()->create([
            'nomor_telepon' => '08'.mt_rand(100000000, 999999999),
        ]);

        $jualPonsel = JualPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'merk' => 'Samsung',
            'model' => 'S23 Ultra',
        ]);

        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.jual-ponsel.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.jual-ponsel.index');
        $response->assertSee('Samsung');
        $response->assertSee('S23 Ultra');
    }

    public function test_update_status_menjadi_disetujui_akan_membuat_data_pembukuan()
    {
        // Buat data untuk perhitungan saldo
        Pembukuan::create([
            'transaksi_id' => 0,
            'transaksi_type' => 'Initial',
            'tanggal' => now()->subDay(),
            'deskripsi' => 'Saldo Awal',
            'debit' => 0,
            'kredit' => 10000000,
            'saldo' => 10000000,
        ]);

        $customer = Customer::factory()->create([
            'nomor_telepon' => '08'.mt_rand(100000000, 999999999),
        ]);

        $jualPonsel = JualPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'status' => 'menunggu',
            'harga' => 2000000,
            'merk' => 'iPhone',
            'model' => '11 Pro',
        ]);

        // Act: Gunakan explicit parameter name 'id' di route dan panggil id_jual_ponsel
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.jual-ponsel.update-status', ['id' => $jualPonsel->id_jual_ponsel]), [
                'status' => 'di setujui',
                'catatan_admin' => 'Barang oke, siap bayar.',
            ]);

        $response->assertRedirect(route('admin.jual-ponsel.index'));
        $response->assertSessionHas('success');

        // Cek status di tabel jual_ponsel
        $this->assertDatabaseHas('jual_ponsel', [
            'id_jual_ponsel' => $jualPonsel->id_jual_ponsel,
            'status' => 'di setujui',
        ]);

        // Cek tabel laporan_pembukuan
        $this->assertDatabaseHas('laporan_pembukuan', [
            'transaksi_id' => $jualPonsel->id_jual_ponsel,
            'transaksi_type' => JualPonsel::class,
            'debit' => 2000000,
            'saldo' => 8000000,
        ]);
    }

    public function test_update_status_menjadi_ditolak_tidak_membuat_pembukuan()
    {
        // Arrange
        // (Opsional) Buat Customer dulu jika JualPonsel butuh id_customer
        $customer = Customer::factory()->create([
             'nomor_telepon' => '08'.mt_rand(100000000, 999999999),
        ]);

        $jualPonsel = JualPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'status' => 'menunggu',
            'harga' => 5000000,
        ]);

        $jumlahPembukuanAwal = Pembukuan::count();

        // Act
        // PERBAIKAN DI SINI: Gunakan ['id' => $jualPonsel->id_jual_ponsel]
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.jual-ponsel.update-status', ['id' => $jualPonsel->id_jual_ponsel]), [
                'status' => 'di tolak',
                'catatan_admin' => 'Layar retak parah.',
            ]);

        // Assert
        $response->assertRedirect();

        // Status berubah (Gunakan id_jual_ponsel)
        $this->assertDatabaseHas('jual_ponsel', [
            'id_jual_ponsel' => $jualPonsel->id_jual_ponsel,
            'status' => 'di tolak',
            'catatan_admin' => 'Layar retak parah.',
        ]);

        // Pastikan TIDAK ada data pembukuan baru di tabel laporan_pembukuan
        $this->assertDatabaseCount('laporan_pembukuan', $jumlahPembukuanAwal);
    }

    public function test_menghapus_data_juga_menghapus_file_gambar_dari_storage()
    {
        // Arrange: Mock 'local' disk karena Controller menggunakan default disk
        // dan memanipulasi path menjadi 'public/...'
        Storage::fake('local');

        // Buat file dummy
        $file = UploadedFile::fake()->image('hp_bekas.jpg');
        
        // Simpan di disk 'local' dengan folder 'public/uploads'
        // Ini agar sesuai dengan logika delete controller yang mencari di 'public/...'
        $path = $file->store('public/uploads', 'local');

        // Buat customer agar factory jual ponsel berjalan lancar
        $customer = Customer::factory()->create([
             'nomor_telepon' => '08'.mt_rand(100000000, 999999999),
        ]);

        // Controller mengubah 'storage/' menjadi 'public/' saat delete
        // Maka di DB kita simpan format 'storage/' agar logikanya jalan
        // $path saat ini formatnya: public/uploads/hash.jpg
        $dbPath = str_replace('public/', 'storage/', $path);

        $jualPonsel = JualPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'gambar' => $dbPath, 
        ]);

        // Pastikan file ada dulu di fake storage local
        Storage::disk('local')->assertExists($path);

        // Act: Delete
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.jual-ponsel.destroy', ['id' => $jualPonsel->id_jual_ponsel]));

        // Assert
        $response->assertRedirect(route('admin.jual-ponsel.index'));

        // Data di DB hilang
        $this->assertDatabaseMissing('jual_ponsel', ['id_jual_ponsel' => $jualPonsel->id_jual_ponsel]);

        // File di storage local harus hilang
        Storage::disk('local')->assertMissing($path);
    }
}