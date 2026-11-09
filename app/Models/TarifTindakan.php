<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifTindakan extends Model
{
    protected $table = 'tarif_tindakans';

    protected $fillable = [
        'tindakan_id',
        'penjamin_id',
        'unit_cost',
        'jasa_medis',
        'jasa_paramedis',
        'margin',
        'total_tarif',
        'harga',
        'is_active',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'unit_cost'      => 'decimal:2',
        'jasa_medis'     => 'decimal:2',
        'jasa_paramedis' => 'decimal:2',
        'margin'         => 'decimal:2',
        'total_tarif'    => 'decimal:2',
        'harga'          => 'decimal:2',
    ];

    public function tindakan()
    {
        return $this->belongsTo(Tindakan::class, 'tindakan_id');
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }
}
