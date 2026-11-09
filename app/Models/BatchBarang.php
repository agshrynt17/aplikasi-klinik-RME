<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchBarang extends Model
{
    protected $table = 'batch_barangs';

    protected $fillable = [
        'master_barang_id',
        'master_gudang_id',
        'nomor_batch',
        'tanggal_kadaluarsa',
        'stok',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_kadaluarsa' => 'date',
    ];

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'master_barang_id');
    }

    public function masterGudang()
    {
        return $this->belongsTo(MasterGudang::class, 'master_gudang_id');
    }
}
