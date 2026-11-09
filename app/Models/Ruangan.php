<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangans';
    protected $fillable = [
        'lantai_id',
        'kode_ruangan',
        'nama_ruangan',
        'poli_klinik',
        'ruang_ranap',
        'kapasitas_tempat_tidur',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }
}
