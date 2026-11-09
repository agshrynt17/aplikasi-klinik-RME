<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $table = 'antrians';

    protected $fillable = [
        'master_antrian_id', 'tanggal', 'nomor_antrian', 'kode_nomor', 
        'pasien_id', 'status', 'status_daftar', 'status_poli',
        'dokter_id', 'jadwal_antrian_id', 'waktu_cetak', 'waktu_tunggu',
        'waktu_pelayanan', 'sumber_data', 'no_kartu_bpjs', 'nik',
        'status_kirim_tiket', 'status_kirim_hadir', 'nama_pasien_non_rm',
        'jam_praktek', 'panggilan_ke'
    ];

    public function masterAntrian()
    {
        return $this->belongsTo(MasterAntrian::class, 'master_antrian_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_id');
    }

    public function jadwalAntrian()
    {
        return $this->belongsTo(JadwalAntrian::class, 'jadwal_antrian_id');
    }
}
