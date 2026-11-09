<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokCancelDetail extends Model
{
    protected $table = 'stok_cancel_details';

    protected $fillable = [
        'stok_cancel_id',
        'master_barang_id',
        'jumlah',
        'satuan',
        'keterangan',
    ];

    public function stokCancel()
    {
        return $this->belongsTo(StokCancel::class, 'stok_cancel_id');
    }

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'master_barang_id');
    }
}
