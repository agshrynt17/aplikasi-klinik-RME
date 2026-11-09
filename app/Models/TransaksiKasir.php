<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKasir extends Model
{
    protected $table = 'transaksi_kasirs';

    protected $fillable = [
        'pendaftaran_id', 'no_kwitansi', 'waktu_bayar', 'total_tindakan', 
        'total_obat', 'diskon', 'total_tagihan', 'uang_diterima', 
        'kembalian', 'akun_pembayaran_id', 'status_pembayaran', 'kasir_id'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function akunPembayaran()
    {
        return $this->belongsTo(AkunPembayaran::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
