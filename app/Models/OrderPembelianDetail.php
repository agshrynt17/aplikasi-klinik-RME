<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPembelianDetail extends Model
{
    protected $guarded = [];

    public function orderPembelian()
    {
        return $this->belongsTo(OrderPembelian::class);
    }

    public function masterBarang()
    {
        return $this->belongsTo(\App\Models\MasterBarang::class);
    }
}