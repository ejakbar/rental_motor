<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'penggunas';

    protected $fillable = [
        'nama',
        'email',
        'no_telp',
        'role',
        'password',
        'foto_profil',
        'nama_bank',
        'no_rekening',
        'atas_nama_rekening',
        'saldo',
        'no_ktp',
        'foto_ktp',
        'no_sim',
        'foto_sim',
        'foto_selfie',
        'status_kyc',
        'catatan_kyc',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'saldo' => 'decimal:2',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }

    public function isPenyewa(): bool
    {
        return $this->role === 'penyewa';
    }

    public function isKycVerified(): bool
    {
        return $this->status_kyc === 'verified';
    }

    // Relasi
    public function motors()
    {
        return $this->hasMany(Motor::class, 'pemilik_id');
    }

    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class, 'penyewa_id');
    }

    public function bagiHasils()
    {
        return $this->hasMany(Bagi_hasil::class, 'pemilik_id');
    }

    public function penarikanDanas()
    {
        return $this->hasMany(PenarikanDana::class, 'pemilik_id');
    }
}
