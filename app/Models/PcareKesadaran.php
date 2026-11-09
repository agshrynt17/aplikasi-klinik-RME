<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareKesadaran extends Model
{
    protected $table = 'pcare_kesadarans';

    protected $fillable = [
        'kd_sadar',
        'nm_sadar',
        'kd_sadar_reff',
    ];
}

