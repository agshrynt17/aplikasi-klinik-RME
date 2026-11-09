<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiBarang extends Model
{
    protected $guarded = [];

    public function gudangAsal()
    {
        return $this->belongsTo(MasterGudang::class, 'gudang_asal_id');
    }

    public function gudangTujuan()
    {
        return $this->belongsTo(MasterGudang::class, 'gudang_tujuan_id');
    }

    public function details()
    {
        return $this->hasMany(MutasiBarangDetail::class);
    }
}