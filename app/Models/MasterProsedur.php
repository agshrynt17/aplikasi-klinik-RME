<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProsedur extends Model
{
    protected $table = 'master_prosedurs';

    protected $fillable = [
        'kode_prosedur',
        'nama_prosedur',
        'kelompok_prosedur',
        'kategori_prosedur',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
