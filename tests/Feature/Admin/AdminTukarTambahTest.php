<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Pembukuan;
use App\Models\Ponsel;
use App\Models\TukarTambah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTukarTambahTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin()
    {
        /** @var \App\Models\Admin $admin */
        $admin = Admin::factory()->create();

        return $this->actingAs($admin, 'admin');
    }

    public function test_index_menampilkan_list_pengajuan()
    {
        $this->actingAsAdmin();

        TukarTambah::factory()->count(3)->create();

        $response = $this->get(route('admin.tukar-tambah.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.tukar-tambah.index');
        $response->assertViewHas('pengajuan');
    }

    public function test_show_menampilkan_detail_pengajuan()
    {
        $this->actingAsAdmin();

        $produk = Ponsel::factory()->create();
        $pengajuan = TukarTambah::factory()->create([
            'produk_tujuan_id' => $produk->id_ponsel,
        ]);

        $response = $this->get(route('admin.tukar-tambah.show', $pengajuan->id_tukar_tambah));

        $response->assertStatus(200);
        $response->assertViewIs('admin.tukar-tambah.show');
    }

    public function test_update_status_menjadi_menunggu()
    {
        $this->actingAsAdmin();

        $pengajuan = TukarTambah::factory()->create(['status' => 'di tolak']);

        $response = $this->put(route('admin.tukar-tambah.update-status', $pengajuan->id_tukar_tambah), [
            'status' => 'menunggu',
            'catatan_admin' => null,
        ]);

        $response->assertRedirect(route('admin.tukar-tambah.index'));

        $this->assertDatabaseHas('tukar_tambah', [
            'id_tukar_tambah' => $pengajuan->id_tukar_tambah,
            'status' => 'menunggu',
            'catatan_admin' => null,
        ]);
    }

    public function test_update_status_menjadi_ditolak()
    {
        $this->actingAsAdmin();

        $pengajuan = TukarTambah::factory()->create();

        $response = $this->put(route('admin.tukar-tambah.update-status', $pengajuan->id_tukar_tambah), [
            'status' => 'di tolak',
            'catatan_admin' => 'Alasan penolakan',
        ]);

        $response->assertRedirect(route('admin.tukar-tambah.index'));

        $this->assertDatabaseHas('tukar_tambah', [
            'id_tukar_tambah' => $pengajuan->id_tukar_tambah,
            'status' => 'di tolak',
            'catatan_admin' => 'Alasan penolakan',
        ]);
    }

    public function test_update_status_disetujui_membuat_pembukuan_jika_belum_ada()
    {
        $this->actingAsAdmin();

        $produk = Ponsel::factory()->create();

        $pengajuan = TukarTambah::factory()->create([
            'status' => 'menunggu',
            'produk_tujuan_id' => $produk->id_ponsel,
        ]);

        $response = $this->put(route('admin.tukar-tambah.update-status', $pengajuan->id_tukar_tambah), [
            'status' => 'di setujui',
            'catatan_admin' => 'OK',
        ]);

        $response->assertRedirect(route('admin.tukar-tambah.index'));

        $this->assertDatabaseHas('tukar_tambah', [
            'id_tukar_tambah' => $pengajuan->id_tukar_tambah,
            'status' => 'di setujui',
        ]);

        $this->assertDatabaseHas('laporan_pembukuan', [
            'transaksi_id' => $pengajuan->id_tukar_tambah,
            'transaksi_type' => TukarTambah::class,
        ]);
    }

    public function test_update_status_disetujui_tidak_membuat_pembukuan_jika_sudah_ada()
    {
        $this->actingAsAdmin();

        $produk = Ponsel::factory()->create();

        $pengajuan = TukarTambah::factory()->create([
            'status' => 'menunggu',
            'produk_tujuan_id' => $produk->id_ponsel,
        ]);

        Pembukuan::factory()->create([
            'transaksi_id' => $pengajuan->id_tukar_tambah,
            'transaksi_type' => TukarTambah::class,
        ]);

        $this->put(route('admin.tukar-tambah.update-status', $pengajuan->id_tukar_tambah), [
            'status' => 'di setujui',
            'catatan_admin' => 'OK',
        ]);

        // Tetap 1 pembukuan
        $this->assertEquals(
            1,
            Pembukuan::where('transaksi_id', $pengajuan->id_tukar_tambah)->count()
        );
    }

    public function test_destroy_menghapus_pengajuan_dan_file_menggunakan_storage_fake()
    {
        $this->actingAsAdmin();

        // Fake disk
        Storage::fake('public');

        $pengajuan = TukarTambah::factory()->create([
            'gambar' => 'storage/gambar/test.png',
        ]);

        // Buat file palsu
        Storage::disk('public')->put('gambar/test.png', 'dummy');

        $this->assertTrue(Storage::disk('public')->exists('gambar/test.png'));

        $response = $this->delete(route('admin.tukar-tambah.destroy', $pengajuan->id_tukar_tambah));

        $response->assertRedirect(route('admin.tukar-tambah.index'));

        // Data terhapus
        $this->assertDatabaseMissing('tukar_tambah', [
            'id_tukar_tambah' => $pengajuan->id_tukar_tambah,
        ]);

        // File terhapus
        $this->assertFalse(Storage::disk('public')->exists('public/gambar/test.png'));
    }

    public function test_destroy_without_image()
    {
        $this->actingAsAdmin();

        Storage::fake('public');

        $pengajuan = TukarTambah::factory()->create([
            'gambar' => 'storage/gambar/xxx.png', // string tapi file tidak dibuat
        ]);

        $response = $this->delete(route('admin.tukar-tambah.destroy', $pengajuan->id_tukar_tambah));

        $response->assertRedirect();

        $this->assertDatabaseMissing('tukar_tambah', [
            'id_tukar_tambah' => $pengajuan->id_tukar_tambah,
        ]);
    }


    public function test_destroy_image_not_found_in_storage()
    {
        $this->actingAsAdmin();

        Storage::fake('public');

        $pengajuan = TukarTambah::factory()->create([
            'gambar' => 'storage/gambar/xxx.png',
        ]);

        // Tidak membuat file di storage

        $response = $this->delete(route('admin.tukar-tambah.destroy', $pengajuan->id_tukar_tambah));

        $response->assertRedirect();

        $this->assertDatabaseMissing('tukar_tambah', [
            'id_tukar_tambah' => $pengajuan->id_tukar_tambah,
        ]);
    }
}
