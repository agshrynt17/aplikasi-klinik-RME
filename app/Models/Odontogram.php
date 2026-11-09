<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odontogram extends Model
{
    protected $table = 'odontograms';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_id',
        'nomor_gigi', 'kondisi', 'keterangan'
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
