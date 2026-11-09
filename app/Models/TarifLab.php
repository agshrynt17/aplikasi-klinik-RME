<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifLab extends Model
{
    protected $fillable = ['master_lab_id', 'penjamin_id', 'harga', 'is_active'];

    public function masterLab()
    {
        return $this->belongsTo(MasterLab::class, 'master_lab_id');
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }
}
