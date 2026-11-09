<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareStatusPulang extends Model
{
    protected $table = 'pcare_status_pulangs';

    protected $fillable = [
        'kd_status_pulang',
        'nm_status_pulang',
        'status_pulang_pcare',
        'is_rawat_inap',
        'is_rawat_jalan',
        'status_pulang_aplikasi',
    ];

    protected $casts = [
        'is_rawat_inap'  => 'boolean',
        'is_rawat_jalan' => 'boolean',
    ];
}

