<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasukBarangDetail extends Model
{
    protected $guarded = [];

    public function masukBarang()
    {
        return $this->belongsTo(MasukBarang::class);
    }

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class);
    }

    public function masterGudang()
    {
        return $this->belongsTo(MasterGudang::class);
    }
}