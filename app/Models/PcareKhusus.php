<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareKhusus extends Model
{
    protected $table = 'pcare_khususes';

    protected $fillable = [
        'kd_khusus',
        'nm_khusus',
        'kd_khusus_reff',
    ];
}

