<?php

namespace App\Models;

use App\Models\KreditPonsel;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    protected $table = 'angsuran';
    protected $primaryKey = 'id_angsuran';
    protected $fillable = [
        'id_kredit_ponsel',
        'bulan_ke',
        'jumlah_cicilan',
        'jatuh_tempo',
        'tanggal_bayar',
        'status'
    ];

    public function kredit() 
    {
        return $this->belongsTo(KreditPonsel::class, 'id_kredit_ponsel', 'id_kredit_ponsel');
    }
}
