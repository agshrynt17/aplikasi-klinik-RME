<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'reseps';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_resep'   => 'date',
        'waktu_proses'    => 'datetime',
        'waktu_penyerahan'=> 'datetime',
        'is_bhp'          => 'boolean',
        'total_harga'     => 'decimal:2',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function details()
    {
        return $this->hasMany(ResepDetail::class, 'resep_id');
    }
}