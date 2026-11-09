<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerujukEksternal extends Model
{
    protected $table = 'perujuk_eksternals';

    protected $fillable = [
        'kode_perujuk', 'nama_perujuk', 'jenis_perujuk',
        'institusi', 'spesialisasi', 'alamat',
        'no_telepon', 'email', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
