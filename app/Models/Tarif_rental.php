<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif_rental extends Model
{
    use HasFactory;

    protected $table = 'tarif_rentals';

    protected $fillable = [
        'motor_id',
        'tarif_harian',
        'tarif_mingguan',
        'tarif_bulanan',
    ];

    protected function casts(): array
    {
        return [
            'tarif_harian' => 'decimal:2',
            'tarif_mingguan' => 'decimal:2',
            'tarif_bulanan' => 'decimal:2',
        ];
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }
}
