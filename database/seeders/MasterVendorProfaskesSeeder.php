<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterVendor;

class MasterVendorProfaskesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'kode_vendor' => '01',
                'nama_vendor' => 'PT DEWI SRI PIRISTI MAHKOTA',
                'alamat'      => 'JL MANGGA KARAWANG BARAT',
                'telp'        => null,
                'email'       => null,
            ],
            [
                'kode_vendor' => '02',
                'nama_vendor' => 'PT BINA SAN PRIMA',
                'alamat'      => 'JL RANGGA GEDE TANJUNG PURA KARAWANG',
                'telp'        => null,
                'email'       => null,
            ],
            [
                'kode_vendor' => '03',
                'nama_vendor' => 'PT MARGA NUSANTARA JAYA',
                'alamat'      => 'JL RAYA KOSAMBI, KLARI KARAWANG',
                'telp'        => null,
                'email'       => null,
            ],
            [
                'kode_vendor' => '04',
                'nama_vendor' => 'PT ENSEVAL PUTERA MEGATRADING Tbk',
                'alamat'      => 'JL TOYOGIRI SELATAN, BEKASI',
                'telp'        => null,
                'email'       => null,
            ],
            [
                'kode_vendor' => '05',
                'nama_vendor' => 'PT SAPTA SARI TAMA',
                'alamat'      => 'JL CARINGIN, BEKASI',
                'telp'        => null,
                'email'       => null,
            ],
        ];

        foreach ($vendors as $item) {
            MasterVendor::firstOrCreate(
                ['kode_vendor' => $item['kode_vendor']],
                $item
            );
        }
    }
}
