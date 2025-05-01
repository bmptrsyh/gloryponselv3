<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeliPonsel extends Model
{
    use HasFactory;

    protected $table = 'beli_ponsel';

    protected $primaryKey = 'id_beli_ponsel';

    protected $fillable = [
        'id_customer',
        'id_ponsel',
        'metode_pembayaran',
        'status',
        'tanggal_transaksi',
        'jumlah',
        'harga'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function ponsel()
    {
        return $this->belongsTo(Ponsel::class, 'id_ponsel', 'id_ponsel')->withTrashed();
    }
}
