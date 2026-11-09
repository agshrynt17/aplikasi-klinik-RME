<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabNilaiNormal extends Model
{
    protected $table = 'lab_nilai_normals';

    protected $fillable = [
        'master_lab_id',
        'kategori',
        'detail_lab',
        'nama_setting',
        'satuan',
        'bentuk_nilai',
        'nilai_normal',
        'jenis_kelamin',
        'kategori_umur',
        'nilai_min',
        'nilai_max',
        'nilai_teks',
        'keterangan',
    ];

    public function masterLab()
    {
        return $this->belongsTo(MasterLab::class, 'master_lab_id');
    }
}
