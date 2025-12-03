<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Ponsel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KreditPonsel>
 */
class KreditPonselFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1. Ambil atau buat Customer & Ponsel untuk relasi
        // Menggunakan factory() agar jika belum ada data, otomatis dibuatkan
        $customer = Customer::inRandomOrder()->first() ?? Customer::factory()->create();
        $ponsel = Ponsel::inRandomOrder()->first() ?? Ponsel::factory()->create();

        // 2. Logika Perhitungan Keuangan
        $hargaPonsel = $ponsel->harga_jual;

        // DP antara 10% sampai 30% dari harga ponsel
        $persenDP = fake()->numberBetween(10, 30);
        $jumlahDP = (int) (($hargaPonsel * $persenDP) / 100);

        // Sisa pinjaman
        $jumlahPinjaman = (int) ($hargaPonsel - $jumlahDP);

        // Tenor (6, 12, 18, 24 bulan) - harus integer sesuai migrasi
        $tenor = fake()->randomElement([6, 12, 18, 24]);

        // Bunga flat sederhana (misal 2.5% per bulan)
        $bungaPerBulan = 0.025;
        $totalBunga = $jumlahPinjaman * $bungaPerBulan * $tenor;
        $totalHutang = $jumlahPinjaman + $totalBunga;

        // Angsuran per bulan (dibulatkan ke ribuan terdekat & di-cast ke integer)
        $angsuranPerBulan = (int) (ceil(($totalHutang / $tenor) / 1000) * 1000);

        // 3. Status sesuai enum di migration: ['menunggu', 'disetujui', 'ditolak']
        $status = fake()->randomElement(['menunggu', 'disetujui', 'ditolak']);

        return [
            // Foreign Keys
            'id_customer' => $customer->id_customer,
            'id_ponsel' => $ponsel->id_ponsel,

            // Data Diri
            'nama_lengkap' => $customer->nama_lengkap ?? fake()->name(),
            'NIK' => fake()->numerify('16##############'), // String
            'tempat_lahir' => fake()->city(), // String
            'tanggal_lahir' => fake()->date('Y-m-d', '-20 years'), // Date
            'jenis_kelamin' => fake()->randomElement(['L', 'P']), // String
            'status_pernikahan' => fake()->randomElement(['Belum Menikah', 'Menikah', 'Janda/Duda']), // String

            // Kontak & Alamat
            'no_telepon' => fake()->phoneNumber(), // String
            'email' => fake()->unique()->safeEmail(), // String
            'alamat_ktp' => fake()->address(), // Text
            'alamat_domisili' => fake()->address(), // Text

            // Data Pekerjaan
            'pekerjaan' => fake()->jobTitle(), // String
            'nama_perusahaan' => fake()->company(), // String
            'alamat_perusahaan' => fake()->address(), // Text
            'lama_bekerja' => fake()->numberBetween(1, 15), // Integer (sebelumnya string tahun)
            'penghasilan_per_bulan' => fake()->numberBetween(4000000, 20000000), // Integer
            'penghasilan_lainnya' => fake()->randomElement([0, fake()->numberBetween(500000, 2000000)]), // Integer nullable

            // Data Kredit (Integer)
            'tenor' => $tenor,
            'jumlah_DP' => $jumlahDP,
            'jumlah_pinjaman' => $jumlahPinjaman,
            'angsuran_per_bulan' => $angsuranPerBulan,

            // File & Status
            'gambar_ktp' => 'storage/gambar/ktp/default.jpg', // String
            'gambar_selfie' => 'storage/gambar/selfie/default.jpg', // String
            'status' => $status, // Enum

            // 'alasan_ditolak' dihapus karena tidak ada di migrasi
        ];
    }
}
