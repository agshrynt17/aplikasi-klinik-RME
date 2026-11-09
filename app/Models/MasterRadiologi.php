<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRadiologi extends Model
{
    protected $table = 'master_radiologis';

    protected $fillable = [
        'kode_rad', 'kategori', 'nama_pemeriksaan', 'golongan', 'permintaan_hasil', 'unitcost', 'alat_tersedia', 'is_active'
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'alat_tersedia' => 'boolean',
        'unitcost'      => 'float',
    ];
}
