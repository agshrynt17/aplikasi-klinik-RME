<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjamin;

class PenjaminKonhakesSeeder extends Seeder
{
    public function run()
    {
        $defaults = [
            ['id' => 1, 'kode_penjamin' => 'P0000', 'nama_penjamin' => 'Pribadi', 'jenis_penjamin' => 'Pribadi/Umum', 'telp' => 'xx', 'is_default' => 0, 'is_active' => 1],
            ['id' => 2, 'kode_penjamin' => 'P0001', 'nama_penjamin' => 'BPJS Kesehatan', 'jenis_penjamin' => 'Asuransi', 'telp' => 'xxx', 'is_default' => 1, 'is_active' => 1],
            ['id' => 3, 'kode_penjamin' => 'P0002', 'nama_penjamin' => 'MANDIRI INHEALTH', 'jenis_penjamin' => 'Asuransi', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
            ['kode_penjamin' => '00000', 'nama_penjamin' => 'Penjualan Apotek', 'jenis_penjamin' => 'Pribadi/Umum', 'telp' => 'xx', 'is_default' => 0, 'is_active' => 1],
            ['kode_penjamin' => 'P0003', 'nama_penjamin' => 'ISOMEDIK RELIENCE', 'jenis_penjamin' => 'Asuransi', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
            ['kode_penjamin' => 'P0004', 'nama_penjamin' => 'BANSOS LINGKUNGAN KLINIK', 'jenis_penjamin' => 'Asuransi', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
            ['kode_penjamin' => 'P0005', 'nama_penjamin' => 'SURAT SEHAT', 'jenis_penjamin' => 'Pribadi/Umum', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
            ['kode_penjamin' => 'P0006', 'nama_penjamin' => 'ALAM MAKMUR', 'jenis_penjamin' => 'Perusahaan', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
            ['kode_penjamin' => 'P0007', 'nama_penjamin' => 'ADMEDIKA', 'jenis_penjamin' => 'Asuransi', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
        ];

        foreach ($defaults as $row) {
            if (isset($row['id'])) {
                $id = $row['id'];
                unset($row['id']);
                Penjamin::where('id', $id)->update($row);
            } else {
                Penjamin::updateOrCreate(['kode_penjamin' => $row['kode_penjamin']], $row);
            }
        }
    }
}
