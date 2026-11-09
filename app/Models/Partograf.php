<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partograf extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'waktu_mulas' => 'datetime',
        'waktu_ketuban_pecah' => 'datetime',
        'observasi' => 'array',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Pegawai::class, 'petugas_id');
    }
}
