<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembukuan extends Model
{
    use HasFactory;
    protected $table = 'laporan_pembukuan';
    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'transaksi_id',
        'transaksi_type',
        'tanggal',
        'deskripsi',
        'debit',
        'kredit',
        'saldo',
        'metode_pembayaran',
    ];

    public function transaksi()
    {
        return $this->morphTo();
    }
}
