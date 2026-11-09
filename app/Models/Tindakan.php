<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    protected $table = 'tindakans';

    protected $fillable = [
        'poliklinik_id',
        'kode_tindakan',
        'nama_tindakan',
        'is_konsultasi',
        'is_informed_consent',
        'is_ranap',
        'bhp',
        'jasa_petugas',
        'penyusutan_gedung',
        'penyusutan_alat',
        'unit_cost',
        'kategori',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'is_konsultasi'       => 'boolean',
        'is_informed_consent' => 'boolean',
        'is_ranap'            => 'boolean',
        'bhp'                 => 'decimal:2',
        'jasa_petugas'        => 'decimal:2',
        'penyusutan_gedung'   => 'decimal:2',
        'penyusutan_alat'     => 'decimal:2',
        'unit_cost'           => 'decimal:2',
    ];

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'poliklinik_id');
    }

    public function tarif()
    {
        return $this->hasMany(TarifTindakan::class);
    }
}
