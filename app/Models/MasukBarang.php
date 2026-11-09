<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasukBarang extends Model
{
    protected $guarded = [];

    public function masterVendor()
    {
        return $this->belongsTo(MasterVendor::class);
    }

    public function orderPembelian()
    {
        return $this->belongsTo(OrderPembelian::class);
    }

    public function details()
    {
        return $this->hasMany(MasukBarangDetail::class);
    }

    public function hutang()
    {
        return $this->hasOne(Hutang::class);
    }
}