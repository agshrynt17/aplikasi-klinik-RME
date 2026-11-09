<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $table = 'slides';

    protected $fillable = [
        'judul', 'tipe', 'url_media', 'is_active', 'urutan',
        'durasi', 'tampilkan_di', 'tampilkan_layanan_ids', 'text_slide'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
        'durasi' => 'integer',
        'tampilkan_layanan_ids' => 'array',
    ];
}
