<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareSarana extends Model
{
    protected $table = 'pcare_saranas';

    protected $fillable = [
        'kd_sarana',
        'nm_sarana',
        'kd_sarana_reff',
    ];
}

