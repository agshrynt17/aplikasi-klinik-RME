<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareTkp extends Model
{
    protected $table = 'pcare_tkps';

    protected $fillable = [
        'kd_tkp',
        'nm_tkp',
    ];
}

