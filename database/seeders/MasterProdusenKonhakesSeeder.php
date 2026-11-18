<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterProdusen;

class MasterProdusenKonhakesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produsens = [
            [
                'kode_produsen' => 'PROD0001',
                'nama_produsen' => 'Sanbe Farma , PT',
                'telp'          => 'xxx',
                'email'         => 'xxx',
                'alamat'        => 'Jakarta',
            ],
            [
                'kode_produsen' => 'PROD0002',
                'nama_produsen' => 'Interbat ,PT',
                'telp'          => 'xxx',
                'email'         => 'xxx',
                'alamat'        => 'xxx',
            ],
            [
                'kode_produsen' => 'PROD0003',
                'nama_produsen' => 'Kimia Farma ,PT',
                'telp'          => 'xxx',
                'email'         => 'xxx',
                'alamat'        => 'xxx',
            ],
            [
                'kode_produsen' => 'PROD0004',
                'nama_produsen' => '3M PT',
                'telp'          => '02129974000',
                'email'         => 'xx',
                'alamat'        => 'Perkantoran Hijau Arkadia, Tower F, 8th Floor Jl. TB. Simatupang Kav. 88 Jakarta Selatan 12520 Indon',
            ],
            [
                'kode_produsen' => 'PROD0005',
                'nama_produsen' => 'ABBOTH, PT',
                'telp'          => '021-27587888',
                'email'         => 'XXX',
                'alamat'        => 'PT Abbott Indonesia Wisma Pondok Indah 2, Suite 1000 Jl. Sultan Iskandar Muda Kav.V – TA Pondok Inda',
            ],
            [
                'kode_produsen' => 'PROD0006',
                'nama_produsen' => 'ACTAVIS, PT.',
                'telp'          => '021 8710311',
                'email'         => 'XXX',
                'alamat'        => 'Jl Raya Bogor km 28 Jakarta Timur 13710',
            ],
            [
                'kode_produsen' => 'PROD0007',
                'nama_produsen' => 'ADITAMA, PT',
                'telp'          => '(021) 89834008',
                'email'         => 'XXX',
                'alamat'        => 'JL. Pasir Kunci, No. 1 RT. 017/05, Pasirsari, Cikarang Sel., Bekasi, Jawa Barat 17530',
            ],
            [
                'kode_produsen' => 'PROD0008',
                'nama_produsen' => 'AIR MANCUR, PT.',
                'telp'          => '(021) 42600575',
                'email'         => 'XXX',
                'alamat'        => 'JL. Letjen Suprapto, Kemayoran, Komplek Rukan Graha Cempaka Mas Blok E No.1, Jakarta, 10640, RW.8',
            ],
            [
                'kode_produsen' => 'PROD0009',
                'nama_produsen' => 'ALPARMA, PT',
                'telp'          => '(021) 5633602',
                'email'         => 'XXX',
                'alamat'        => 'Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta',
            ],
            [
                'kode_produsen' => 'PROD0010',
                'nama_produsen' => 'ALTANA PHARMA, PT',
                'telp'          => 'XXX',
                'email'         => 'XXX',
                'alamat'        => 'XXX',
            ],
            [
                'kode_produsen' => 'PROD0011',
                'nama_produsen' => 'ENSEVAL',
                'telp'          => 'XXX',
                'email'         => 'XXX',
                'alamat'        => 'BEKASI',
            ],
            [
                'kode_produsen' => 'PROD0012',
                'nama_produsen' => 'INDOFARMA',
                'telp'          => 'XXX',
                'email'         => 'XXX',
                'alamat'        => 'BEKASI',
            ],
            [
                'kode_produsen' => 'PROD0013',
                'nama_produsen' => 'DEXA MEDICA',
                'telp'          => 'XXX',
                'email'         => 'XXX',
                'alamat'        => 'CIKARANG',
            ],
            [
                'kode_produsen' => 'PROD0015',
                'nama_produsen' => 'KALBE FARMA',
                'telp'          => 'XXX',
                'email'         => 'XXX',
                'alamat'        => 'BEKASI',
            ],
            [
                'kode_produsen' => 'PROD0016',
                'nama_produsen' => 'MERCK, PT',
                'telp'          => '021-8400081',
                'email'         => 'merck@merck.co.id',
                'alamat'        => 'Jl. TB Simatupang No.8, Pasar Rebo, Jakarta Timur',
            ],
            [
                'kode_produsen' => 'PROD0017',
                'nama_produsen' => 'MEPROPHARM',
                'telp'          => '022-6011707',
                'email'         => 'info@mepropharm.co.id',
                'alamat'        => 'Jl. Soekarno-Hatta No. 789, Bandung',
            ],
            [
                'kode_produsen' => 'PROD0018',
                'nama_produsen' => 'TEMPO SCAN PACIFIC',
                'telp'          => '021-29208888',
                'email'         => 'corp@temposcan.com',
                'alamat'        => 'Tempo Scan Tower, Jl. H.R. Rasuna Said, Jakarta Selatan',
            ],
            [
                'kode_produsen' => 'PROD0019',
                'nama_produsen' => 'PHAPROS, PT',
                'telp'          => '024-3543761',
                'email'         => 'phapros@phapros.co.id',
                'alamat'        => 'Jl. Simongan No. 131, Semarang',
            ],
            [
                'kode_produsen' => 'PROD0020',
                'nama_produsen' => 'NOVELL PHARMACEUTICAL',
                'telp'          => '021-58304000',
                'email'         => 'info@novellpharm.com',
                'alamat'        => 'Jl. Pos Pengumben Raya No. 40, Jakarta Barat',
            ],
        ];

        foreach ($produsens as $item) {
            MasterProdusen::firstOrCreate(
                ['kode_produsen' => $item['kode_produsen']],
                $item
            );
        }
    }
}
