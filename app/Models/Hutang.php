<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    protected $guarded = [];

    public function masukBarang()
    {
        return $this->belongsTo(MasukBarang::class);
    }

    public function masterVendor()
    {
        return $this->belongsTo(MasterVendor::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(PembayaranHutang::class);
    }

    public function getNoTagihanDisplayAttribute()
    {
        if (!empty($this->no_tagihan)) {
            return $this->no_tagihan;
        }
        if ($this->masukBarang) {
            return $this->masukBarang->nomor_faktur ?: $this->masukBarang->nomor_penerimaan;
        }
        return 'TAG-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }
}