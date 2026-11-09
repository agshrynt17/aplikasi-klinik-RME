<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryStok extends Model
{
    protected $table = 'history_stoks';

    protected $fillable = [
        'master_barang_id',
        'master_gudang_id',
        'jenis_penyesuaian',
        'tipe_penyesuaian',
        'tanggal',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
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
