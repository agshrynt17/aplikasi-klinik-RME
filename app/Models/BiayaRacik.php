<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaRacik extends Model
{
    protected $table = 'biaya_raciks';

    protected $fillable = [
        'penjamin_id',
        'nama_tarif',
        'biaya',
    ];

    protected $casts = [
        'biaya' => 'decimal:2',
    ];

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }
}
