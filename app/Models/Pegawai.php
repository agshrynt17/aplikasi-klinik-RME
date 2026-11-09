<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawais';

    protected $fillable = [
        'kode_pegawai',
        'nik',
        'nama_pegawai',
        'fungsional_medis',
        'jenis_kelamin',
        'agama',
        'tempat_lahir',
        'tanggal_lahir',
        'jabatan',
        'bagian',
        'unit',
        'no_telepon',
        'email',
        'tanggal_masuk',
        'status_nikah',
        'no_kis',
        'no_sip',
        'tgl_expired_sip',
        'is_apoteker',
        'no_spk',
        'expired_spk',
        'status_pekerjaan',
        'golongan',
        'alamat',
        'propinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'kode_dpjp_bpjs',
        'kode_ihs_satusehat',
        'nama_satusehat',
        'is_active',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'is_apoteker'      => 'boolean',
        'tanggal_lahir'    => 'date',
        'tanggal_masuk'    => 'date',
        'tgl_expired_sip'  => 'date',
        'expired_spk'      => 'date',
    ];

    public function getJenisKelaminLabelAttribute(): string
    {
        if (in_array(strtoupper($this->jenis_kelamin), ['L', 'LAKI-LAKI', 'LAKI - LAKI'])) {
            return 'Laki-Laki';
        }
        return 'Perempuan';
    }
}
