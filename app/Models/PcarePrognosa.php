<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcarePrognosa extends Model
{
    protected $table = 'pcare_prognosas';

    protected $fillable = [
        'kd_prognosa',
        'nm_prognosa',
        'kd_prognosa_reff',
    ];
}

