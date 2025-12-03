<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ponsel;
use App\Models\Admin;
use App\Http\Requests\StorePonselRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TambahProdukTest extends TestCase
{
    use RefreshDatabase; // Menggunakan transaction agar tidak mengubah data asli

    protected $admin;
    protected $existingPonsel;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        
        
        $this->admin = Admin::factory()->create();
        if (!$this->admin) {
            $this->markTestSkipped('Tidak ada data admin di database. Silakan jalankan seeder terlebih dahulu.');
        }

        // Ambil data ponsel yang sudah ada untuk referensi
        $this->existingPonsel = Ponsel::first();
    }

    /**
     * Helper untuk mendapatkan data valid default
     * @param array $overrides Data yang ingin diubah dari default
     * @return array
     */
    private function getBaseData($overrides = [])
    {
        $file = UploadedFile::fake()->image('product.jpg', 500, 500)->size(1024); // 1MB JPG

        $defaultData = [
            'merk' => 'Test Samsung',
            'model' => 'Test Galaxy S24 Ultra',
            'harga_jual' => 15000000,
            'harga_beli' => 12000000,
            'stok' => 50,
            'status' => 'baru',
            'processor' => 'Snapdragon 8 Gen 3',
            'dimension' => '162.3 x 79 x 8.6 mm',
            'ram' => 12,
            'storage' => 256,
            'warna' => 'Titanium Gray',
            'gambar' => $file
        ];

        return array_merge($defaultData, $overrides);
    }

    
    public function test_TCTPR001_upload_gambar_file_jpg_berhasil()
    {
        // Arrange
        $file = UploadedFile::fake()->image('ProductImage.jpg', 500, 500)->size(2000); // 2MB
        $data = $this->getBaseData(['gambar' => $file, 'merk' => 'Test TCTPR001']); // Merk unik

        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $ponsel = Ponsel::where('merk', 'Test TCTPR001')->first();
        $this->assertNotNull($ponsel);
        $this->assertStringContainsString('.jpg', $ponsel->gambar);
    }

  
    public function test_TCTPR002_upload_gambar_file_png_berhasil()
    {
        // Arrange
        $file = UploadedFile::fake()->image('ProductImage.png', 500, 500)->size(1500); // 1.5MB
        $data = $this->getBaseData(['gambar' => $file, 'merk' => 'Test TCTPR002']); // Merk unik

        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $ponsel = Ponsel::where('merk', 'Test TCTPR002')->first();
        $this->assertNotNull($ponsel);
        $this->assertStringContainsString('.png', $ponsel->gambar);
    }

    
    public function test_TCTPR003_upload_file_format_tidak_didukung()
    {
        // Arrange
        $file = UploadedFile::fake()->create('document.pdf', 1024); // PDF
        $data = $this->getBaseData(['gambar' => $file]);
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['gambar']);
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR004_upload_gambar_ukuran_file_large()
    {
        // Arrange
        // Asumsi validasi max:5120 (5MB) -> 7MB
        $file = UploadedFile::fake()->image('large.jpg')->size(7000); 
        $data = $this->getBaseData(['gambar' => $file]);
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['gambar']);
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR005_input_merk_dengan_string_valid()
    {
        // Arrange
        $data = $this->getBaseData(['merk' => 'Test Xiaomi']); // String valid
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('merk', 'Test Xiaomi')->first());
    }

    /**
     * TCTPR006: Submit form dengan merk kosong
     * Expected: Error message - Negative
     */
    public function test_TCTPR006_submit_form_merk_kosong()
    {
        // Arrange
        $data = $this->getBaseData(['merk' => '']); // Kosong
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['merk']);
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR007_input_merk_karakter_khusus()
    {
        // Arrange
        $data = $this->getBaseData(['merk' => '@Brand#']); // Karakter khusus
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['merk']); // Asumsi ada validasi alpha/spaces
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR008_input_model_valid()
    {
        // Arrange
        $data = $this->getBaseData(['model' => 'Galaxy S24 Ultra']); // Data sudah valid
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('model', 'Galaxy S24 Ultra')->first());
    }

    
    public function test_TCTPR009_submit_form_model_kosong()
    {
        // Arrange
        $data = $this->getBaseData(['model' => '']); // Kosong
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['model']);
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR010_input_harga_jual_angka_valid()
    {
        // Arrange
        $data = $this->getBaseData(['harga_jual' => 15000000]); // Data sudah valid
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('harga_jual', 15000000)->first());
    }

    
    public function test_TCTPR011_input_harga_jual_dengan_format_rupiah()
    {
        // Arrange
        $data = $this->getBaseData(['harga_jual' => 'Rp15.000.000']); // Format rupiah
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['harga_jual']); // Harus numeric
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR012_input_harga_jual_dengan_huruf()
    {
        // Arrange
        $data = $this->getBaseData(['harga_jual' => 'lima belas juta']); // Huruf
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['harga_jual']); // Harus numeric
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR013_input_harga_jual_negatif()
    {
        // Arrange
        $data = $this->getBaseData(['harga_jual' => -15000000]); // Negatif
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['harga_jual']); // Asumsi validasi min:0
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR014_submit_form_harga_jual_kosong()
    {
        // Arrange
        $data = $this->getBaseData(['harga_jual' => '']); // Kosong
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['harga_jual']);
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR015_input_harga_beli_angka_valid()
    {
        // Arrange
        $data = $this->getBaseData(['harga_beli' => 12000000]); // Data sudah valid
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('harga_beli', 12000000)->first());
    }

    
    public function test_TCTPR016_input_harga_beli_dengan_format_rupiah()
    {
        // Arrange
        $data = $this->getBaseData(['harga_beli' => 'Rp12.000.000']); // Format rupiah
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['harga_beli']); // Harus numeric
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR017_input_harga_beli_dengan_huruf()
    {
        // Arrange
        $data = $this->getBaseData(['harga_beli' => 'dua belas juta']); // Huruf
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['harga_beli']); // Harus numeric
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR018_input_harga_beli_negatif()
    {
        // Arrange
        $data = $this->getBaseData(['harga_beli' => -12000000]); // Negatif
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['harga_beli']); // Asumsi validasi min:0
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR019_input_stok_valid()
    {
        // Arrange
        $data = $this->getBaseData(['stok' => 50]); // Data sudah valid
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('stok', 50)->first());
    }

    
    public function test_TCTPR020_input_stok_decimal()
    {
        // Arrange
        $data = $this->getBaseData(['stok' => 50.5]); // Decimal
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['stok']); // Harus integer
        $this->assertEquals($countBefore, Ponsel::count());
    }
    
    
    public function test_TCTPR021_input_stok_negatif()
    {
        // Arrange
        $data = $this->getBaseData(['stok' => -10]); // Negatif
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertSessionHasErrors(['stok']); // Asumsi validasi min:0
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR022_pilih_status_produk_baru()
    {
        // Arrange
        $data = $this->getBaseData(['status' => 'baru', 'merk' => 'Test Status Baru']); // Status baru
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $ponsel = Ponsel::where('merk', 'Test Status Baru')->first();
        $this->assertNotNull($ponsel);
        $this->assertEquals('baru', $ponsel->status);
    }

    
    public function test_TCTPR023_pilih_status_produk_bekas()
    {
        // Arrange
        $data = $this->getBaseData(['status' => 'bekas', 'merk' => 'Test Status Bekas']); // Status bekas
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $ponsel = Ponsel::where('merk', 'Test Status Bekas')->first();
        $this->assertNotNull($ponsel);
        $this->assertEquals('bekas', $ponsel->status);
    }

    
    public function test_TCTPR024_input_processor_valid()
    {
        // Arrange
        $data = $this->getBaseData(['processor' => 'Snapdragon 8 Gen 3']); // Data valid
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('processor', 'Snapdragon 8 Gen 3')->first());
    }

    
    public function test_TCTPR025_input_dimensi_valid()
    {
        // Arrange
        $data = $this->getBaseData(['dimension' => '162.3 x 79 x 8.6 mm']); // Data valid
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('dimension', '162.3 x 79 x 8.6 mm')->first());
    }

    
    public function test_TCTPR026_input_dimensi_format_salah()
    {
        // Arrange
        $data = $this->getBaseData(['dimension' => '162,3x79,5x8,6']); // Format salah (koma)
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        // Asumsi ada validasi format (regex) yang menolak koma
        $response->assertSessionHasErrors(['dimension']); 
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_TCTPR027_input_ram_valid()
    {
        // Arrange
        $data = $this->getBaseData(['ram' => 12]); // Data valid (integer)
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('ram', 12)->first());
    }

    
    public function test_TCTPR028_input_storage_valid()
    {
        // Arrange
        $data = $this->getBaseData(['storage' => 256]); // Data valid (integer)
        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.ponsel.index'));
        $this->assertEquals($countBefore + 1, Ponsel::count());
        $this->assertNotNull(Ponsel::where('storage', 256)->first());
    }


    
    public function test_gambar_wajib_diisi()
    {
        // Arrange - Data tanpa gambar
        $data = $this->getBaseData();
        unset($data['gambar']); // Hapus key gambar

        $countBefore = Ponsel::count();

        // Act
        $response = $this->actingAs($this->admin, 'admin')
                         ->post(route('admin.ponsel.store'), $data);

        // Assert - Harus ada error karena gambar required
        $response->assertSessionHasErrors(['gambar']);
        $this->assertEquals($countBefore, Ponsel::count());
    }

    
    public function test_database_connection()
    {
        // ... (code asli Anda)
        $this->assertDatabaseCount('ponsel', Ponsel::count());
        $this->assertDatabaseCount('admin', Admin::count());
    }

    protected function tearDown(): void
    {
        // DatabaseTransactions akan otomatis rollback semua perubahan
        parent::tearDown();
    }
}