<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareTindakan extends Model
{
    protected $table = 'pcare_tindakans';

    protected $fillable = [
        'kd_tkp',
        'nm_tkp',
        'kd_tindakan',
        'nm_tindakan',
        'max_tarif',
        'with_value',
        'kd_tindakan_reff',
    ];

    protected $casts = [
        'max_tarif'  => 'integer',
        'with_value' => 'integer',
    ];
}

