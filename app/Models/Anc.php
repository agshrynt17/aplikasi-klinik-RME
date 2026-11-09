<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anc extends Model
{
    protected $table = 'ancs';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_id',
        'hpht', 'hpl', 'usia_kehamilan', 'gpa',
        'tensi', 'bb', 'tb', 'lila',
        'tfu', 'djj', 'letak_janin', 'imunisasi_tt',
        'keluhan', 'keterangan'
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
