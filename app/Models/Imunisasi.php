<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imunisasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'tanggal_imunisasi',
        'jenis_imunisasi',
        'batch_vaksin',
        'dosis',
        'lokasi_suntikan',
        'pemberi_id',
        'keluhan_pasca_imunisasi',
        'keterangan',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function pemberi()
    {
        return $this->belongsTo(Pegawai::class, 'pemberi_id');
    }
}
