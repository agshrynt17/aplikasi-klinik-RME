<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDiagnosa extends Model
{
    protected $table = 'master_diagnosas';

    protected $fillable = [
        'kode_diagnosa',
        'nama_diagnosa',
        'kode_katdiagnosa',
        'kode_kelompokdiagnosa',
        'is_non_spesialis',
        'is_diagnosa_lab',
        'is_diagnosa_radiologi',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_non_spesialis'      => 'boolean',
        'is_diagnosa_lab'        => 'boolean',
        'is_diagnosa_radiologi'  => 'boolean',
        'is_active'             => 'boolean',
    ];
}
