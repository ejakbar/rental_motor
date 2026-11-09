<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'penyewaan_id',
        'kode_transaksi',
        'jumlah',
        'metode_pembayaran',
        'status_pembayaran',
        'nomor_va_atau_rekening',
        'bukti_pembayaran',
        'tanggal_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_pembayaran' => 'datetime',
        ];
    }

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class, 'penyewaan_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status_pembayaran) {
            'pending' => '<span class="badge badge-warning text-dark"><i class="fas fa-clock mr-1"></i>Pending</span>',
            'lunas' => '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Lunas</span>',
            'gagal' => '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>Gagal</span>',
            'kadaluarsa' => '<span class="badge badge-secondary"><i class="fas fa-hourglass-end mr-1"></i>Kadaluarsa</span>',
            default => '<span class="badge badge-light">' . $this->status_pembayaran . '</span>',
        };
    }
}
