<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ponsel extends Model
{
    use SoftDeletes,Searchable, HasFactory;

    protected $table = 'ponsel';
    protected $primaryKey = 'id_ponsel';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'merk',
        'model',
        'harga_jual',
        'harga_beli',
        'stok',
        'status',
        'processor',
        'dimension',
        'ram',
        'storage',
        'gambar',
        'warna'
    ];

    public function ulasan()
{
    return $this->hasMany(Ulasan::class, 'id_ponsel', 'id_ponsel');
}

public function toSearchableArray()
{
    return [
        'merk' => $this->merk,
        'model' => $this->model,
        'processor' => $this->processor,
    ];
}

}




