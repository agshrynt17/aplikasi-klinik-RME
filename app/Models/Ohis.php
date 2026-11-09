<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ohis extends Model
{
    protected $table = 'ohis';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_id',
        'skor_gigi_json', 'di_s', 'ci_s', 'ohi_s', 'kriteria', 'keterangan'
    ];
    
    protected $casts = [
        'skor_gigi_json' => 'array',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_id');
    }
}
