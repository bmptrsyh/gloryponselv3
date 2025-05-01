<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';

    protected $fillable = [
        'id_beli_ponsel',
        'id_ponsel',
        'ulasan',
        'rating',
        'tanggal_ulasan',
    ];

    // Relasi ke Ponsel
    public function ponsel()
    {
        return $this->belongsTo(Ponsel::class, 'id_ponsel', 'id_ponsel');
    }

    // Relasi ke pembelian ponsel
    public function pembelian()
    {
        return $this->belongsTo(BeliPonsel::class, 'id_beli_ponsel', 'id_beli_ponsel');
    }
}
