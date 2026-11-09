<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifRadiologi extends Model
{
    protected $table = 'tarif_radiologis';

    protected $fillable = ['master_radiologi_id', 'penjamin_id', 'harga', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function masterRadiologi()
    {
        return $this->belongsTo(MasterRadiologi::class, 'master_radiologi_id');
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }
}
