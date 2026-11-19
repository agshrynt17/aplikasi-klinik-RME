<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKasir extends Model
{
    protected $table = 'transaksi_kasirs';

    protected $fillable = [
        'pendaftaran_id', 'no_kwitansi', 'waktu_bayar', 'biaya_adm', 'total_tindakan', 
        'biaya_konsultasi', 'total_obat', 'biaya_lab', 'biaya_radiologi', 'sub_total',
        'diskon', 'total_tagihan', 'dijamin', 'dibayar_pasien', 'cara_bayar',
        'uang_diterima', 'kembalian', 'akun_pembayaran_id', 'status_pembayaran', 'kasir_id'
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
