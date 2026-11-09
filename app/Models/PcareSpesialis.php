<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareSpesialis extends Model
{
    protected $table = 'pcare_spesialis';

    protected $fillable = [
        'kd_spesialis',
        'nm_spesialis',
        'jumlah_sub',
    ];
}

