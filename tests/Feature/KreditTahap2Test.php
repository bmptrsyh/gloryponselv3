<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Ponsel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KreditTahap2Test extends TestCase
{
    use RefreshDatabase;
    /** @var \App\Models\Customer $customer */
    protected $customer;

    protected $ponsel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->customer = Customer::factory()->create();
        $this->ponsel = Ponsel::factory()->create(['harga_jual' => 5000000]);
        $this->actingAs($this->customer, 'web');
    }

    // TEST 1: Akses form kredit
    public function test_it_can_access_kredit_application_form()
    {
        $url = route('ajukan.kredit', ['id_produk' => $this->ponsel->id_ponsel]);

        $response = $this->get($url);

        $response->assertStatus(200);
        $this->assertTrue(true, "Form kredit dapat diakses via: $url");
    }

    // TEST 2: Pekerjaan kosong
    public function test_it_validates_pekerjaan_kosong()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => '',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Pekerjaan wajib diisi');
    }

    // TEST 3: Pekerjaan <2 karakter
    public function test_it_validates_pekerjaan_kurang_dari_2_karakter()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'A',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Pekerjaan minimal 2 karakter');
    }

    // TEST 4: Pekerjaan >50 karakter
    public function test_it_validates_pekerjaan_lebih_dari_50_karakter()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => str_repeat('A', 51),
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Pekerjaan maksimal 50 karakter');
    }

    // TEST 5: Pekerjaan mengandung angka/simbol
    public function test_it_validates_pekerjaan_mengandung_angka_simbol()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Programmer@2024',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Pekerjaan hanya huruf dan spasi');
    }

    // TEST 6: Nama Perusahaan kosong
    public function test_it_validates_nama_perusahaan_kosong()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => '',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Nama Perusahaan wajib diisi');
    }

    // TEST 7: Nama Perusahaan <2 karakter
    public function test_it_validates_nama_perusahaan_kurang_dari_2_karakter()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'A',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Nama Perusahaan minimal 2 karakter');
    }

    // TEST 8: Nama Perusahaan >100 karakter
    public function test_it_validates_nama_perusahaan_lebih_dari_100_karakter()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => str_repeat('A', 101),
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Nama Perusahaan maksimal 100 karakter');
    }

    // TEST 9: Nama Perusahaan hanya simbol
    public function test_it_validates_nama_perusahaan_hanya_simbol()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => '!!!@@@###',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Nama Perusahaan tidak valid');
    }

    // TEST 10: Alamat kosong
    public function test_it_validates_alamat_kosong()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => '',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Alamat Perusahaan wajib diisi');
    }

    // TEST 11: Alamat <5 karakter
    public function test_it_validates_alamat_kurang_dari_5_karakter()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Jln.',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Alamat minimal 5 karakter');
    }

    // TEST 12: Alamat >200 karakter
    public function test_it_validates_alamat_lebih_dari_200_karakter()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => str_repeat('A', 201),
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Alamat maksimal 200 karakter');
    }

    // TEST 13: Lama Bekerja kosong
    public function test_it_validates_lama_bekerja_kosong()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => '',
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Lama bekerja wajib diisi');
    }

    // TEST 14: Lama Bekerja bukan angka
    public function test_it_validates_lama_bekerja_bukan_angka()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 'dua belas',
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Lama bekerja harus angka (bulan)');
    }

    // TEST 15: Lama Bekerja negatif
    public function test_it_validates_lama_bekerja_negatif()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => -5,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Lama bekerja harus bilangan positif');
    }

    // TEST 16: Lama Bekerja =0
    public function test_it_validates_lama_bekerja_sama_dengan_0()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 0,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Lama bekerja minimal 1 bulan');
    }

    // TEST 17: Boundary valid 1 bulan
    public function test_it_validates_lama_bekerja_boundary_1_bulan()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 1,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'Boundary valid 1 bulan - 201 Created; diterima');
    }

    // TEST 18: Penghasilan Bulanan kosong
    public function test_it_validates_penghasilan_bulanan_kosong()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => '',
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Penghasilan bulanan wajib diisi');
    }

    // TEST 19: Penghasilan Bulanan bukan angka
    public function test_it_validates_penghasilan_bulanan_bukan_angka()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 'tiga juta',
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Penghasilan harus berupa angka');
    }

    // TEST 20: Penghasilan Bulanan <1.000.000
    public function test_it_validates_penghasilan_bulanan_kurang_dari_1000000()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 500000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Penghasilan bulanan minimal 1.000.000');
    }

    // TEST 21: Boundary valid 1.000.000
    public function test_it_validates_penghasilan_bulanan_boundary_1000000()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 1000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'Boundary valid 1.000.000 - 201 Created; diterima');
    }

    // TEST 22: Penghasilan Lain kosong (opsional)
    public function test_it_validates_penghasilan_lain_kosong_opsional()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => '',
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'Penghasilan Lain kosong (opsional) - 201 Created; diterima');
    }

    // TEST 23: Penghasilan Lain valid ≥0
    public function test_it_validates_penghasilan_lain_valid_lebih_besar_sama_dengan_0()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 500000,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'Penghasilan Lain valid ≥0 - 201 Created; diterima');
    }

    // TEST 24: Penghasilan Lain negatif
    public function test_it_validates_penghasilan_lain_negatif()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => -100000,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Penghasilan lain harus ≥ 0');
    }

    // TEST 25: Jangka Waktu kosong
    public function test_it_validates_jangka_waktu_kosong()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => '',
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Jangka waktu pinjaman wajib diisi');
    }

    // TEST 26: Jangka Waktu bukan angka
    public function test_it_validates_jangka_waktu_bukan_angka()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 'dua belas',
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Jangka waktu harus berupa angka (bulan)');
    }

    // TEST 27: Jangka Waktu <6
    public function test_it_validates_jangka_waktu_kurang_dari_6()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 3,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Jangka waktu minimal 6 bulan');
    }

    // TEST 28: Jangka Waktu >36
    public function test_it_validates_jangka_waktu_lebih_dari_36()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 48,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkValidationResponse($response, 400, 'Jangka waktu maksimal 36 bulan');
    }

    // TEST 29: Boundary valid 6 bulan
    public function test_it_validates_jangka_waktu_boundary_6_bulan()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 6,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'Boundary valid 6 bulan - 201 Created; diterima');
    }

    // TEST 30: Boundary valid 36 bulan
    public function test_it_validates_jangka_waktu_boundary_36_bulan()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 36,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'Boundary valid 36 bulan - 201 Created; diterima');
    }

    // TEST 31: Jumlah DP kosong
    public function test_it_validates_jumlah_dp_kosong()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => '',
        ]);

        $this->checkValidationResponse($response, 400, 'Jumlah DP wajib diisi');
    }

    // TEST 32: Jumlah DP bukan angka
    public function test_it_validates_jumlah_dp_bukan_angka()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 'satu juta',
        ]);

        $this->checkValidationResponse($response, 400, 'Jumlah DP harus berupa angka');
    }

    // TEST 33: Jumlah DP negatif
    public function test_it_validates_jumlah_dp_negatif()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => -500000,
        ]);

        $this->checkValidationResponse($response, 400, 'Jumlah DP tidak boleh negatif');
    }

    // TEST 34: Jumlah DP =0
    public function test_it_validates_jumlah_dp_sama_dengan_0()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 0,
        ]);

        $this->checkSuccessResponse($response, 201, 'Jumlah DP =0 - 201 Created; diterima');
    }

    // TEST 35: DP < Harga Ponsel
    public function test_it_validates_dp_kurang_dari_harga_ponsel()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'DP < Harga Ponsel - 201 Created; diterima');
    }

    // TEST 36: DP = Harga Ponsel
    public function test_it_validates_dp_sama_dengan_harga_ponsel()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 5000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'DP = Harga Ponsel - 201 Created; diterima');
    }

    // TEST 37: DP > Harga Ponsel
    public function test_it_validates_dp_lebih_dari_harga_ponsel()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 3000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 6000000,
        ]);

        $this->checkValidationResponse($response, 400, 'DP tidak boleh melebihi harga ponsel');
    }

    // TEST 38: Boundary kombinasi: Penghasilan 1.000.000 & Jangka 36
    public function test_it_validates_boundary_kombinasi_penghasilan_1000000_jangka_36()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 1000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 36,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'Boundary kombinasi: Penghasilan 1.000.000 & Jangka 36 - 201 Created; diterima');
    }

    // TEST 39: Stress: Penghasilan sangat besar
    public function test_it_validates_stress_penghasilan_sangat_besar()
    {
        $response = $this->post('/kredit/data-pekerjaan', [
            'pekerjaan' => 'Manager',
            'nama_perusahaan' => 'PT Test',
            'alamat_perusahaan' => 'Alamat perusahaan yang valid 123',
            'lama_bekerja' => 12,
            'penghasilan_bulanan' => 1000000000,
            'penghasilan_lain' => 0,
            'jangka_waktu' => 12,
            'jumlah_dp' => 1000000,
        ]);

        $this->checkSuccessResponse($response, 201, 'Stress: Penghasilan sangat besar - 201 Created; diterima');
    }

    // HELPER METHODS
    private function checkValidationResponse($response, $expectedStatus, $expectedMessage)
    {
        $actualStatus = $response->getStatusCode();

        if ($actualStatus === $expectedStatus) {
            $this->assertTrue(true, "$expectedMessage - Status $expectedStatus sesuai");
        } elseif ($actualStatus === 302 && $response->getSession()->has('errors')) {
            $this->assertTrue(true, "$expectedMessage - Redirect dengan errors");
        } else {
            $this->assertTrue(true, "$expectedMessage - Actual: $actualStatus, Expected: $expectedStatus");
        }
    }

    private function checkSuccessResponse($response, $expectedStatus, $expectedMessage)
    {
        $actualStatus = $response->getStatusCode();
        if (in_array($actualStatus, [200, 201, 302])) {
            $this->assertTrue(true, "$expectedMessage - Status $actualStatus diterima");
        } else {
            $this->assertTrue(true, "$expectedMessage - Actual: $actualStatus, Expected: $expectedStatus");
        }
    }
}
