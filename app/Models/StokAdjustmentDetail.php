<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokAdjustmentDetail extends Model
{
    protected $table = 'stok_adjustment_details';

    protected $fillable = [
        'stok_adjustment_id',
        'master_barang_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'satuan',
        'catatan',
    ];

    public function stokAdjustment()
    {
        return $this->belongsTo(StokAdjustment::class, 'stok_adjustment_id');
    }

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'master_barang_id');
    }
}
