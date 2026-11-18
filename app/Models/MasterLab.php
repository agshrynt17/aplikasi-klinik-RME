<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLab extends Model
{
    protected $table = 'master_labs';

    protected $fillable = [
        'kode_lab',
        'golongan_lab',
        'kategori',
        'nama_pemeriksaan',
        'kelompok_lab',
        'unitcost',
        'satuan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'unitcost'  => 'decimal:2',
    ];

    // Accessors for KONHAKES 1:1 view naming
    public function getNamaLabAttribute()
    {
        return $this->nama_pemeriksaan;
    }

    public function getGolonganLabAttribute($value)
    {
        return $value ?: $this->kategori ?: 'Hematologi';
    }

    public function nilaiNormals()
    {
        return $this->hasMany(LabNilaiNormal::class, 'master_lab_id');
    }

    public function tarifs()
    {
        return $this->hasMany(TarifLab::class, 'master_lab_id');
    }

    public function transaksiLabs()
    {
        return $this->hasMany(TransaksiLab::class, 'master_lab_id');
    }
}
