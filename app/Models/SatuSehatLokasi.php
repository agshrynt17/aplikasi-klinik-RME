<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuSehatLokasi extends Model
{
    protected $table = 'satusehat_lokasis';

    protected $guarded = ['id'];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
}

