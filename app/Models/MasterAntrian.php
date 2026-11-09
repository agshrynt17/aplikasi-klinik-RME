<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAntrian extends Model
{
    protected $table = 'master_antrians';

    protected $fillable = [
        'kode_antrian', 'nama_antrian', 'jenis', 'poliklinik_id', 'sound_layanan', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class);
    }
}
