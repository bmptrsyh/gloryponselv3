<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JualPonsel extends Model
{
    use HasFactory;

    protected $table = 'jual_ponsel';
    protected $primaryKey = 'id_jual_ponsel';
    public $timestamps = true;

    protected $fillable = [
        'id_customer',
        'id_ponsel',
        'merk',
        'model',
        'warna',
        'ram',
        'storage',
        'processor',
        'kondisi',
        'deskripsi',
        'harga',
        'gambar',
        'status'
    ];

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function pembukuan()
    {
        return $this->morphOne(Pembukuan::class, 'transaksi');
    }
}
