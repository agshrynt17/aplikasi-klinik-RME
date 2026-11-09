<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanOperasi extends Model
{
    protected $table = 'laporan_operasis';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_operator_id',
        'asisten_operator', 'perawat_instrumen', 'dokter_anestesi_id', 'jenis_anestesi',
        'diagnosa_pra_bedah', 'diagnosa_pasca_bedah', 'tindakan_operasi', 'jaringan_dieksisi',
        'waktu_mulai', 'waktu_selesai',
        'laporan_operasi', 'instruksi_pasca_bedah'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokterOperator()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_operator_id');
    }
}
