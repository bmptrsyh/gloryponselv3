<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeliPonsel extends Model
{
    use HasFactory;

    protected $table = 'beli_ponsel';
    protected $primaryKey = 'id_beli_ponsel';
    public $timestamps = true; // Pastikan ini true karena ada timestamps di migration

    protected $fillable = [
        'id_customer',
        'id_ponsel',
        'metode_pembayaran',
        'status',
        'tanggal_transaksi',
        'jumlah',  // Tambahkan ini jika ada kolom jumlah
        'harga'    // Tambahkan ini jika ada kolom harga
    ];

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // Relasi ke ponsel
    public function ponsel()
    {
        return $this->belongsTo(Ponsel::class, 'id_ponsel', 'id_ponsel');
    }
}