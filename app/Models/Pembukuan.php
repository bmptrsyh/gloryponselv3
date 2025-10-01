<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembukuan extends Model
{
    protected $table = 'laporan_pembukuan';
    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'tanggal',
        'deskripsi',
        'Debit',
        'Kredit',
        'Saldo',
        'metode_pembayaran',
    ];
}
