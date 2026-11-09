<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;

    protected $table = 'penyewaans';

    protected $fillable = [
        'kode_booking',
        'penyewa_id',
        'motor_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_hari',
        'tipe_durasi',
        'tarif_per_hari',
        'subtotal',
        'biaya_layanan',
        'total_harga',
        'status_penyewaan',
        'waktu_diambil',
        'waktu_dikembalikan',
        'denda_keterlambatan',
        'catatan_penyewa',
        'catatan_pemilik',
        'catatan_resolusi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'waktu_diambil' => 'datetime',
            'waktu_dikembalikan' => 'datetime',
            'tarif_per_hari' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'biaya_layanan' => 'decimal:2',
            'total_harga' => 'decimal:2',
            'denda_keterlambatan' => 'decimal:2',
            'durasi_hari' => 'integer',
        ];
    }

    public function penyewa()
    {
        return $this->belongsTo(Pengguna::class, 'penyewa_id');
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'penyewaan_id');
    }

    public function bagiHasil()
    {
        return $this->hasOne(Bagi_hasil::class, 'penyewaan_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status_penyewaan) {
            'menunggu_pembayaran' => '<span class="badge badge-warning text-dark font-weight-bold"><i class="fas fa-clock mr-1"></i>Menunggu Pembayaran</span>',
            'menunggu_konfirmasi' => '<span class="badge badge-dark text-warning font-weight-bold"><i class="fas fa-hourglass-half mr-1"></i>Menunggu Konfirmasi</span>',
            'disetujui' => '<span class="badge badge-primary font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Disetujui / Siap Diambil</span>',
            'on_rent' => '<span class="badge badge-success font-weight-bold"><i class="fas fa-motorcycle mr-1"></i>Sedang Digunakan (On Rent)</span>',
            'selesai' => '<span class="badge badge-secondary"><i class="fas fa-flag-checkered mr-1"></i>Selesai</span>',
            'batal' => '<span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Dibatalkan</span>',
            'dispute' => '<span class="badge badge-danger"><i class="fas fa-exclamation-triangle mr-1"></i>Perselisihan (Dispute)</span>',
            default => '<span class="badge badge-light">' . $this->status_penyewaan . '</span>',
        };
    }
}
