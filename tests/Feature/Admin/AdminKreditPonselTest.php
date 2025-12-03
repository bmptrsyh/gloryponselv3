<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Angsuran;
use App\Models\Customer;
use App\Models\KreditPonsel;
use App\Models\Ponsel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKreditPonselTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup user admin
        $this->admin = Admin::factory()->create();
    }

    public function test_halaman_index_kredit_bisa_diakses()
    {
        // Arrange
        $customer = Customer::factory()->create(['nomor_telepon' => '08'.mt_rand(100000000, 999999999)]);
        // Asumsi ada model Ponsel
        $ponsel = Ponsel::factory()->create();

        $kredit = KreditPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'id_ponsel' => $ponsel->id_ponsel ?? $ponsel->id, // Sesuaikan dengan PK Ponsel
            'status' => 'menunggu',
        ]);

        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.kredit.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.kredit.index');
        // Pastikan nama customer muncul di view
        $response->assertSee($customer->nama);
    }

    public function test_update_status_disetujui_otomatis_membuat_data_angsuran_sesuai_tenor()
    {
        // Arrange: Buat pengajuan kredit dengan tenor 6 bulan
        $customer = Customer::factory()->create(['nomor_telepon' => '08'.mt_rand(100000000, 999999999)]);
        $ponsel = Ponsel::factory()->create();

        $kredit = KreditPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'id_ponsel' => $ponsel->id_ponsel ?? $ponsel->id,
            'status' => 'menunggu',
            'tenor' => 6, // 6 Bulan
            'angsuran_per_bulan' => 500000,
            'updated_at' => now(), // Untuk patokan tanggal jatuh tempo
        ]);

        // Act: Setujui kredit
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.kredit.updateStatus', ['id' => $kredit->id_kredit_ponsel]), [
                'status' => 'disetujui',
            ]);

        // Assert
        $response->assertRedirect(route('admin.kredit.index'));
        $response->assertSessionHas('success');

        // 1. Cek Status Berubah
        $this->assertDatabaseHas('kredit_ponsel', [
            'id_kredit_ponsel' => $kredit->id_kredit_ponsel,
            'status' => 'disetujui',
        ]);

        // 2. Cek Angsuran Terbuat (Harus ada 6 baris)
        $this->assertDatabaseCount('angsuran', 6);

        // 3. Cek Detail Angsuran Bulan ke-1
        $this->assertDatabaseHas('angsuran', [
            'id_kredit_ponsel' => $kredit->id_kredit_ponsel,
            'bulan_ke' => 1,
            'jumlah_cicilan' => 500000,
            'status' => 'belum',
        ]);

        // 4. Cek Detail Angsuran Bulan Terakhir (ke-6)
        $this->assertDatabaseHas('angsuran', [
            'id_kredit_ponsel' => $kredit->id_kredit_ponsel,
            'bulan_ke' => 6,
            'jumlah_cicilan' => 500000,
        ]);
    }

    public function test_update_status_ditolak_menyimpan_alasan_penolakan()
    {
        // Arrange
        $customer = Customer::factory()->create(['nomor_telepon' => '08'.mt_rand(100000000, 999999999)]);
        $ponsel = Ponsel::factory()->create();

        $kredit = KreditPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'id_ponsel' => $ponsel->id_ponsel ?? $ponsel->id,
            'status' => 'menunggu',
        ]);

        // Act: Tolak kredit
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.kredit.updateStatus', ['id' => $kredit->id_kredit_ponsel]), [
                'status' => 'ditolak',
                'alasan_ditolak' => 'Data slip gaji tidak valid.',
            ]);

        // Assert
        $response->assertRedirect(route('admin.kredit.index'));

        // Cek database
        $this->assertDatabaseHas('kredit_ponsel', [
            'id_kredit_ponsel' => $kredit->id_kredit_ponsel,
            'status' => 'ditolak',
            'alasan_ditolak' => 'Data slip gaji tidak valid.',
        ]);

        // Pastikan TIDAK ada angsuran yang dibuat
        $this->assertDatabaseCount('angsuran', 0);
    }

    public function test_validasi_gagal_jika_status_ditolak_tapi_alasan_kosong()
    {
        // Arrange
        $customer = Customer::factory()->create(['nomor_telepon' => '08'.mt_rand(100000000, 999999999)]);
        $ponsel = Ponsel::factory()->create();
        $kredit = KreditPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'id_ponsel' => $ponsel->id_ponsel ?? $ponsel->id,
        ]);

        // Act: Tolak tanpa alasan
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.kredit.updateStatus', ['id' => $kredit->id_kredit_ponsel]), [
                'status' => 'ditolak',
                'alasan_ditolak' => '', // Kosong
            ]);

        // Assert: Harus error validation di field alasan_ditolak
        $response->assertSessionHasErrors('alasan_ditolak');
    }

    public function test_menghapus_data_kredit()
    {
        // Arrange
        $customer = Customer::factory()->create(['nomor_telepon' => '08'.mt_rand(100000000, 999999999)]);
        $ponsel = Ponsel::factory()->create();
        $kredit = KreditPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'id_ponsel' => $ponsel->id_ponsel ?? $ponsel->id,
        ]);

        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.kredit.destroy', ['id' => $kredit->id_kredit_ponsel]));

        // Assert
        $response->assertRedirect(route('admin.kredit.index'));
        $this->assertDatabaseMissing('kredit_ponsel', ['id_kredit_ponsel' => $kredit->id_kredit_ponsel]);
    }

    public function test_update_status_menunggu_tidak_membuat_angsuran()
    {
        $customer = Customer::factory()->create(['nomor_telepon' => '08'.mt_rand(100000000, 999999999)]);
        $ponsel = Ponsel::factory()->create();

        $kredit = KreditPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'id_ponsel' => $ponsel->id_ponsel ?? $ponsel->id,
            'status' => 'disetujui', // awalnya disetujui biar kelihatan reset
            'alasan_ditolak' => 'dummy',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.kredit.updateStatus', ['id' => $kredit->id_kredit_ponsel]), [
                'status' => 'menunggu',
            ]);

        $response->assertRedirect(route('admin.kredit.index'));

        // status harus kembali menunggu
        $this->assertDatabaseHas('kredit_ponsel', [
            'id_kredit_ponsel' => $kredit->id_kredit_ponsel,
            'status' => 'menunggu',
            'alasan_ditolak' => null, // because status != ditolak
        ]);

        // tidak ada angsuran
        $this->assertDatabaseCount('angsuran', 0);
    }

    public function test_update_status_ditolak_mengisi_alasan_ditolak()
    {
        $customer = Customer::factory()->create(['nomor_telepon' => '08'.mt_rand(100000000, 999999999)]);
        $ponsel = Ponsel::factory()->create();

        $kredit = KreditPonsel::factory()->create([
            'id_customer' => $customer->id_customer,
            'id_ponsel' => $ponsel->id_ponsel ?? $ponsel->id,
            'status' => 'menunggu',
            'alasan_ditolak' => null,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.kredit.updateStatus', ['id' => $kredit->id_kredit_ponsel]), [
                'status' => 'ditolak',
                'alasan_ditolak' => 'Dokumen tidak valid',
            ]);

        $response->assertRedirect(route('admin.kredit.index'));

        $this->assertDatabaseHas('kredit_ponsel', [
            'id_kredit_ponsel' => $kredit->id_kredit_ponsel,
            'status' => 'ditolak',
            'alasan_ditolak' => 'Dokumen tidak valid',
        ]);

        // harusnya tidak buat angsuran
        $this->assertDatabaseCount('angsuran', 0);
    }
}
