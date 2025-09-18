<?php

namespace Database\Seeders;

use App\Models\KreditPonsel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KreditPonsel::create([
            'id_customer' => 1,
            'id_ponsel' => 3,
            'nama_lengkap' => 'Bima Putra',
            'NIK' => '1234567890123455',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
            'no_telepon' => '081234567890',
            'email' => 'bima.putra@example.com',
            'alamat_ktp' => 'Jl. Merdeka No. 1, Jakarta',
            'alamat_domisili' => 'Jl. Merdeka No. 1, Jakarta',
            'pekerjaan' => 'Karyawan Swasta',
            'nama_perusahaan' => 'PT. Contoh Perusahaan',
            'lama_bekerja' => 5,
            'penghasilan_per_bulan' => 10000000,
            'tenor' => 12,
            'jumlah_DP' => 2000000,
            'penghasilan_lainnya' => 2000000,
            'alamat_perusahaan' => 'Jl. Sudirman No. 2, Jakarta',
            'jumlah_pinjaman' => 8000000,
            'gambar_ktp' => 'ktp_bima.jpg',
            'gambar_selfie' => 'selfie_bima.jpg',
            'status' => 'menunggu',
        ]);
    }
}
