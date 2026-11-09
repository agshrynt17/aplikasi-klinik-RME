<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalAntrian extends Model
{
    protected $table = 'jadwal_antrians';

    protected $fillable = [
        'master_antrian_id', 'dokter_id', 'kode_prefix', 'ruangan', 'hari', 'jam_mulai', 'jam_selesai', 'kuota', 'sound', 'terisi', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function masterAntrian()
    {
        return $this->belongsTo(MasterAntrian::class, 'master_antrian_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_id');
    }
}
