<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    protected $table = 'polikliniks';
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_igd' => 'boolean',
        'is_rehab_medik' => 'boolean',
        'is_mcu' => 'boolean',
        'is_lab' => 'boolean',
        'is_radiologi' => 'boolean',
    ];
}
