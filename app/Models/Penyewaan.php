<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    protected $fillable = [
        'penyewa_id', 'motor_id', 'tanggal_mulai', 'tanggal_selesai',
        'durasi', 'tipe_durasi', 'harga', 'status', 'tanggal'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal' => 'date',
    ];

    public function penyewa()
    {
        return $this->belongsTo(Pengguna::class, 'penyewa_id');
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    public function transaksi()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function bagiHasil()
    {
        return $this->hasOne(Bagi_hasil::class);
    }
}
