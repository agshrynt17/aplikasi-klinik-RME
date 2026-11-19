<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pendaftaran extends Model
{
    protected $table = 'pendaftarans';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_daftar'     => 'date',
        'is_kirim_pcare'     => 'boolean',
        'is_kirim_satusehat' => 'boolean',
        'is_prb'             => 'boolean',
        'is_online'          => 'boolean',
        'is_exclude_kasir'   => 'boolean',
    ];

    public function getNomorRegistrasiAttribute($value)
    {
        return $value ?: $this->no_rawat;
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'poliklinik_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_id');
    }

    public function perawat()
    {
        return $this->belongsTo(Pegawai::class, 'perawat_id');
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }

    public function tpa()
    {
        return $this->belongsTo(Tpa::class, 'tpa_id');
    }

    public function transaksiLabs()
    {
        return $this->hasMany(TransaksiLab::class, 'pendaftaran_id');
    }

    public function transaksiRadiologis()
    {
        return $this->hasMany(TransaksiRadiologi::class, 'pendaftaran_id');
    }

    public function ppas()
    {
        return $this->hasMany(Ppa::class, 'pendaftaran_id');
    }

    public function transaksiTindakans()
    {
        return $this->hasMany(TransaksiTindakan::class, 'pendaftaran_id');
    }

    public function generalConsent()
    {
        return $this->hasOne(GeneralConsent::class, 'pendaftaran_id');
    }

    public function surveiKepuasan()
    {
        return $this->hasOne(SurveiKepuasan::class, 'pendaftaran_id');
    }

    public function transaksiKasir()
    {
        return $this->hasOne(TransaksiKasir::class, 'pendaftaran_id');
    }

    public function suratKeterangans()
    {
        return $this->hasMany(SuratKeterangan::class, 'pendaftaran_id');
    }

    public function asesmenRajal()
    {
        return $this->hasOne(AsesmenAwalRajal::class, 'pendaftaran_id');
    }

    public function reseps()
    {
        return $this->hasMany(Resep::class, 'pendaftaran_id');
    }

    public function kamarRanaps()
    {
        return $this->hasMany(KamarRanap::class, 'pendaftaran_id');
    }

    public function rawatInapTransfers()
    {
        return $this->hasMany(RawatInapTransfer::class, 'pendaftaran_id');
    }
}
