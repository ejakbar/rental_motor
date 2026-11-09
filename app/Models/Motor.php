<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $table = 'motors';

    protected $fillable = [
        'pemilik_id',
        'merk',
        'tipe_motor',
        'tahun',
        'tipe_cc',
        'no_plat',
        'warna',
        'deskripsi',
        'tarif_harian',
        'tarif_mingguan',
        'tarif_bulanan',
        'foto_motor',
        'foto_depan',
        'foto_samping',
        'foto_belakang',
        'foto_stnk',
        'dokumen_kepemilikan',
        'status_approval',
        'catatan_approval',
        'status',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tarif_harian' => 'decimal:2',
            'tarif_mingguan' => 'decimal:2',
            'tarif_bulanan' => 'decimal:2',
            'is_active' => 'boolean',
            'tahun' => 'integer',
        ];
    }

    public function pemilik()
    {
        return $this->belongsTo(Pengguna::class, 'pemilik_id');
    }

    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class, 'motor_id');
    }

    public function tarifRental()
    {
        return $this->hasOne(Tarif_rental::class, 'motor_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailableForRent($query)
    {
        return $query->where('status_approval', 'approved')
                     ->where('is_active', true)
                     ->where('status', 'tersedia');
    }

    /**
     * Cek apakah motor tersedia dalam rentang tanggal tertentu (Mencegah Double-Booking sesuai PRD)
     */
    public function isAvailableBetween($startDate, $endDate, $excludePenyewaanId = null): bool
    {
        if (!$this->is_active || $this->status_approval !== 'approved' || $this->status === 'nonaktif') {
            return false;
        }

        $query = $this->penyewaans()
            ->whereNotIn('status_penyewaan', ['batal', 'selesai'])
            ->where(function ($q) use ($startDate, $endDate) {
                // Rentang waktu bertabrakan:
                // (StartA <= EndB) and (EndA >= StartB)
                $q->where('tanggal_mulai', '<=', $endDate)
                  ->where('tanggal_selesai', '>=', $startDate);
            });

        if ($excludePenyewaanId) {
            $query->where('id', '!=', $excludePenyewaanId);
        }

        return $query->count() === 0;
    }

    // Accessor foto utama dengan fallback
    public function getFotoUtamaUrlAttribute()
    {
        if ($this->foto_motor && file_exists(public_path('storage/' . $this->foto_motor))) {
            return asset('storage/' . $this->foto_motor);
        }
        if ($this->foto_depan && file_exists(public_path('storage/' . $this->foto_depan))) {
            return asset('storage/' . $this->foto_depan);
        }
        return 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop&q=80';
    }
}
