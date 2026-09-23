<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Model
{
    use Notifiable;

    protected $fillable = [
        'nama', 'email', 'no_hp', 'role', 'password', 
        'no_plat', 'status', 'photo', 'dokumen_kepemilikan'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function motors()
    {
        return $this->hasMany(Motor::class, 'pemilik_id');
    }

    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class, 'penyewa_id');
    }
}
