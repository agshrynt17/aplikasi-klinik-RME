<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKlaim extends Model
{
    protected $table = 'transaksi_klaims';

    protected $fillable = [
        'status_klaim',
        'tgl_klaim',
        'no_klaim',
        'penjamin_id',
        'nama_penjamin',
        'tgl_transaksi_awal',
        'tgl_transaksi_akhir',
        'jml_hari',
        'subtotal',
        'diskon',
        'total_tagihan',
        'total_realisasi',
        'selisih',
        'keterangan'
    ];

    protected $casts = [
        'tgl_klaim' => 'date',
        'tgl_transaksi_awal' => 'date',
        'tgl_transaksi_akhir' => 'date',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_tagihan' => 'decimal:2',
        'total_realisasi' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }
}
