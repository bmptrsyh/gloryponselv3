<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customer';
    protected $primaryKey = 'id_customer';

    protected $fillable = ['nama', 'alamat', 'email', 'nomor_telepon', 'password', 'otp', 'otp_expires_at', 'foto_profil'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
    ];

    public function getFotoProfilUrlAttribute()
{
    if (!$this->foto_profil) {
        return asset('storage/gambar/customer/default.png');
    }

    // Cek apakah URL eksternal (dari Google, Facebook, dll)
    if (Str::startsWith($this->foto_profil, ['http://', 'https://'])) {
        return $this->foto_profil;
    }

    // Kalau bukan, berarti path lokal
    return asset($this->foto_profil);
}
}
