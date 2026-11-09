<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiBarangDetail extends Model
{
    protected $guarded = [];

    public function mutasiBarang()
    {
        return $this->belongsTo(MutasiBarang::class);
    }

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class);
    }

    public function batchBarang()
    {
        return $this->belongsTo(BatchBarang::class);
    }
}