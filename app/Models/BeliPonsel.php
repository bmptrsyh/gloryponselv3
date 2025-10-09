<?php

namespace App\Models;

use App\Models\Pembukuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeliPonsel extends Model
{
    use HasFactory;

    protected $table = 'beli_ponsel';
    protected $primaryKey = 'id_beli_ponsel';
    public $timestamps = true;

    protected $fillable = [
        'id_customer',
        'id_ponsel',
        'metode_pembayaran',
        'status',
        'tanggal_transaksi',
        'jumlah',
        'harga',
        'code',
        'status_pengiriman',
    ];

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }


    // Relasi ke ponsel
    public function ponsel()
    {
        return $this->belongsTo(Ponsel::class, 'id_ponsel', 'id_ponsel');
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'id_beli_ponsel', 'id_beli_ponsel');
    }

    public function pembukuan()
    {
        return $this->morphOne(Pembukuan::class, 'transaksi');
    }
}
