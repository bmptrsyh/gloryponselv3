<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KreditPonsel extends Model
{
    protected $table = 'kredit_ponsel';
    protected $primaryKey = 'id_kredit_ponsel';
    protected $fillable = [
        'id_customer',
        'id_ponsel',
        'nama_lengkap',
        'NIK',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_pernikahan',
        'no_telepon',
        'email',
        'alamat_ktp',
        'alamat_domisili',
        'pekerjaan',
        'nama_perusahaan',
        'lama_bekerja',
        'penghasilan_per_bulan',
        'tenor',
        'jumlah_DP',
        'penghasilan_lainnya',
        'alamat_perusahaan',
        'jumlah_pinjaman',
        'angsuran_per_bulan',
        'gambar_ktp',
        'gambar_selfie',
        'status',
        'alasan_ditolak',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function ponsel()
    {
        return $this->belongsTo(Ponsel::class, 'id_ponsel', 'id_ponsel');
    }
}
