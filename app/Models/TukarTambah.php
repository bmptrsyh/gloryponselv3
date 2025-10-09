<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TukarTambah extends Model
{
    use HasFactory;

    protected $table = 'tukar_tambah';
    protected $primaryKey = 'id_tukar_tambah';
    public $timestamps = true;

    protected $fillable = [
        'id_customer',
        'produk_tujuan_id',
        'merk',
        'model',
        'warna',
        'ram',
        'storage',
        'processor',
        'kondisi',
        'deskripsi',
        'harga_estimasi',
        'gambar',
        'status'
    ];

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // Relasi ke produk tujuan (ponsel yang ingin ditukar)
    public function produkTujuan()
    {
        return $this->belongsTo(Ponsel::class, 'produk_tujuan_id', 'id_ponsel');
    }

    public function pembukuan()
    {
        return $this->morphOne(Pembukuan::class, 'transaksi');
    }
}


