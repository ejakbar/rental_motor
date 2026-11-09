<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bagi_hasil extends Model
{
    use HasFactory;

    protected $table = 'bagi_hasils';

    protected $fillable = [
        'penyewaan_id',
        'pemilik_id',
        'total_biaya_sewa',
        'persentase_komisi',
        'komisi_admin',
        'bagi_hasil_pemilik',
        'status_bagi_hasil',
        'tanggal_bagi_hasil',
    ];

    protected function casts(): array
    {
        return [
            'total_biaya_sewa' => 'decimal:2',
            'persentase_komisi' => 'decimal:2',
            'komisi_admin' => 'decimal:2',
            'bagi_hasil_pemilik' => 'decimal:2',
            'tanggal_bagi_hasil' => 'datetime',
        ];
    }

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class, 'penyewaan_id');
    }

    public function pemilik()
    {
        return $this->belongsTo(Pengguna::class, 'pemilik_id');
    }
}
