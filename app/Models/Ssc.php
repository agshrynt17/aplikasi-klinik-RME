<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ssc extends Model
{
    protected $table = 'sscs';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_id',
        'sign_in_json', 'time_out_json', 'sign_out_json',
        'perawat_sirkuler'
    ];
    
    protected $casts = [
        'sign_in_json' => 'array',
        'time_out_json' => 'array',
        'sign_out_json' => 'array',
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
