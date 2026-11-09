<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanApotekDetail extends Model
{
    protected $table = 'penjualan_apotek_details';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
        'harga_satuan'       => 'decimal:2',
        'subtotal'           => 'decimal:2',
        'diskon'             => 'decimal:2',
    ];

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'master_barang_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(PenjualanApotek::class, 'penjualan_apotek_id');
    }
}