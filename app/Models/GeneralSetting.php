<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';

    protected $fillable = [
        'kategori',
        'nama_setting',
        'key',
        'nilai',
        'tipe_input',
        'opsi',
        'urutan',
    ];

    protected $casts = [
        'opsi' => 'array',
    ];
}
