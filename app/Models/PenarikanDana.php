<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanDana extends Model
{
    use HasFactory;

    protected $table = 'penarikan_danas';

    protected $fillable = [
        'pemilik_id',
        'kode_penarikan',
        'jumlah',
        'nama_bank',
        'no_rekening',
        'atas_nama_rekening',
        'status',
        'catatan',
        'bukti_transfer',
        'tanggal_diproses',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_diproses' => 'datetime',
        ];
    }

    public function pemilik()
    {
        return $this->belongsTo(Pengguna::class, 'pemilik_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge badge-warning text-dark"><i class="fas fa-clock mr-1"></i>Menunggu Approval</span>',
            'disetujui' => '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Dana Ditransfer</span>',
            'ditolak' => '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>',
            default => '<span class="badge badge-light">' . $this->status . '</span>',
        };
    }
}

