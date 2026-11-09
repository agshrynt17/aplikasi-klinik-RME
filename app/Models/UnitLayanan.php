<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitLayanan extends Model
{
    protected $table = 'unit_layanans';
    protected $fillable = ['kode_unit', 'nama_unit', 'keterangan', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
