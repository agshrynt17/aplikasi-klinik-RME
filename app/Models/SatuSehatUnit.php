<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuSehatUnit extends Model
{
    use HasFactory;

    protected $table = 'satusehat_units';

    protected $fillable = [
        'unit_satusehat',
        'unit_layanan_id',
        'unit_aplikasi',
        'location_id_satusehat',
        'status',
        'diubah_pada',
    ];

    protected $casts = [
        'diubah_pada' => 'datetime',
    ];

    public function unitLayanan()
    {
        return $this->belongsTo(UnitLayanan::class, 'unit_layanan_id');
    }
}

