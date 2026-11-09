<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareTacc extends Model
{
    protected $table = 'pcare_taccs';

    protected $fillable = [
        'kode_tacc',
        'nama_tacc',
        'alasan_tacc',
    ];
}

