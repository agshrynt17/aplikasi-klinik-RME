<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareAlergi extends Model
{
    protected $table = 'pcare_alergis';

    protected $fillable = [
        'kd_jenis',
        'nm_jenis',
        'kd_alergi',
        'nm_alergi',
        'kd_alergi_reff',
    ];
}

