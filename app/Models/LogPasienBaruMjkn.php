<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPasienBaruMjkn extends Model
{
    protected $table = 'log_pasien_baru_mjkns';

    protected $fillable = [
        'no_kartu',
        'nik',
        'nomorkk',
        'nama_pasien',
        'jeniskelamin',
        'tanggal_lahir',
        'no_hp',
        'alamat',
        'status_import',
        'pesan_error'
    ];

    protected $casts = [
        'status_import' => 'boolean',
        'tanggal_lahir' => 'date',
    ];

    // Accessors for KONHAKES field names
    public function getNomorkartuAttribute()
    {
        return $this->attributes['no_kartu'] ?? null;
    }

    public function getNamaAttribute()
    {
        return $this->attributes['nama_pasien'] ?? null;
    }

    public function getTanggallahirAttribute()
    {
        $val = $this->attributes['tanggal_lahir'] ?? null;
        return $val ? date('Y-m-d', strtotime($val)) : null;
    }
}
