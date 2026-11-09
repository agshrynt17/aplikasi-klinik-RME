<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterProsedur;

class MasterProsedurProfaskesSeeder extends Seeder
{
    public function run(): void
    {
        $prosedurs = [
            // Exact rows 1-20 matching PROFASKES media_17925597xx
            [
                'kode_prosedur' => '21',
                'nama_prosedur' => 'Operations on nose',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.0',
                'nama_prosedur' => 'Control of epistaxis',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.00',
                'nama_prosedur' => 'Control of epistaxis, not otherwise specified',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.01',
                'nama_prosedur' => 'Control of epistaxis by anterior nasal packing',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.02',
                'nama_prosedur' => 'Control of epistaxis by posterior (and anterior) packing',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.03',
                'nama_prosedur' => 'Control of epistaxis by cauterization (and packing)',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.04',
                'nama_prosedur' => 'Control of epistaxis by ligation of ethmoidal arteries',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.05',
                'nama_prosedur' => 'Control of epistaxis by (transantral) ligation of the maxillary artery',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.06',
                'nama_prosedur' => 'Control of epistaxis by ligation of the external carotid artery',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.07',
                'nama_prosedur' => 'Control of epistaxis by excision of nasal mucosa and skin grafting of septum and lateral nasal',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.09',
                'nama_prosedur' => 'Control of epistaxis by other means',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.1',
                'nama_prosedur' => 'Incision of nose',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => 'Chondrotomy Incision of skin of nose Nasal septotomy',
            ],
            [
                'kode_prosedur' => '21.2',
                'nama_prosedur' => 'Diagnostic procedures on nose',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.21',
                'nama_prosedur' => 'Rhinoscopy',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.22',
                'nama_prosedur' => 'Biopsy of nose',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.3',
                'nama_prosedur' => 'Local excision or destruction of lesion of nose',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.30',
                'nama_prosedur' => 'Excision or destruction of lesion of nose, unspecified',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.31',
                'nama_prosedur' => 'Local excision or destruction of intranasal lesion',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.32',
                'nama_prosedur' => 'Local excision or destruction of other lesion of nose',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            [
                'kode_prosedur' => '21.4',
                'nama_prosedur' => 'Resection of nose',
                'kelompok_prosedur' => null,
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => null,
            ],
            // Common primary care / clinical procedures
            [
                'kode_prosedur' => '23.09',
                'nama_prosedur' => 'Extraction of other tooth',
                'kelompok_prosedur' => 'Tindakan Gigi & Mulut',
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => 'Pencabutan Gigi Tetap',
            ],
            [
                'kode_prosedur' => '23.2',
                'nama_prosedur' => 'Restoration of tooth by filling',
                'kelompok_prosedur' => 'Tindakan Gigi & Mulut',
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => 'Penambalan Gigi / Tumpatan Komposit',
            ],
            [
                'kode_prosedur' => '57.94',
                'nama_prosedur' => 'Insertion of indwelling urinary catheter',
                'kelompok_prosedur' => 'Tindakan Keperawatan',
                'kategori_prosedur' => 'Sistem Genitourinari',
                'keterangan' => 'Pemasangan Kateter Urin / Foley Catheter',
            ],
            [
                'kode_prosedur' => '73.59',
                'nama_prosedur' => 'Other manually assisted delivery',
                'kelompok_prosedur' => 'Tindakan Kebidanan',
                'kategori_prosedur' => 'Obstetri & Ginekologi',
                'keterangan' => 'Pertolongan Persalinan Normal (APN)',
            ],
            [
                'kode_prosedur' => '86.04',
                'nama_prosedur' => 'Other incision with drainage of skin and subcutaneous tissue',
                'kelompok_prosedur' => 'Tindakan Bedah Minor',
                'kategori_prosedur' => 'Kulit & Jaringan Subkutan',
                'keterangan' => 'Insisi dan Drainase Abses',
            ],
            [
                'kode_prosedur' => '86.23',
                'nama_prosedur' => 'Removal of nail, nail bed, or nail fold',
                'kelompok_prosedur' => 'Tindakan Bedah Minor',
                'kategori_prosedur' => 'Kulit & Jaringan Subkutan',
                'keterangan' => 'Ekstraksi Kuku / Roserplasty',
            ],
            [
                'kode_prosedur' => '86.59',
                'nama_prosedur' => 'Closure of skin and subcutaneous tissue of other sites',
                'kelompok_prosedur' => 'Tindakan Bedah Minor',
                'kategori_prosedur' => 'Kulit & Jaringan Subkutan',
                'keterangan' => 'Penjahitan Luka (Hecting)',
            ],
            [
                'kode_prosedur' => '89.52',
                'nama_prosedur' => 'Electrocardiogram',
                'kelompok_prosedur' => 'Pemeriksaan Diagnostik',
                'kategori_prosedur' => 'Sistem Kardiovaskular',
                'keterangan' => 'Rekam Jantung (EKG 12 Lead)',
            ],
            [
                'kode_prosedur' => '93.94',
                'nama_prosedur' => 'Respiratory medication administered by nebulizer',
                'kelompok_prosedur' => 'Tindakan Kegawatdaruratan',
                'kategori_prosedur' => 'Sistem Respirasi',
                'keterangan' => 'Nebulisasi / Terapi Uap',
            ],
            [
                'kode_prosedur' => '93.96',
                'nama_prosedur' => 'Other oxygen enrichment',
                'kelompok_prosedur' => 'Tindakan Kegawatdaruratan',
                'kategori_prosedur' => 'Sistem Respirasi',
                'keterangan' => 'Pemberian Oksigen (Nasal Kanul / Masker)',
            ],
            [
                'kode_prosedur' => '96.07',
                'nama_prosedur' => 'Insertion of other (naso-)gastric tube',
                'kelompok_prosedur' => 'Tindakan Keperawatan',
                'kategori_prosedur' => 'Sistem Pencernaan',
                'keterangan' => 'Pemasangan Selang Lambung (NGT)',
            ],
            [
                'kode_prosedur' => '96.52',
                'nama_prosedur' => 'Irrigation of ear',
                'kelompok_prosedur' => 'Tindakan THT',
                'kategori_prosedur' => 'Telinga',
                'keterangan' => 'Spooling Telinga / Pembersihan Serumen',
            ],
            [
                'kode_prosedur' => '96.54',
                'nama_prosedur' => 'Dental scaling and polishing',
                'kelompok_prosedur' => 'Tindakan Gigi & Mulut',
                'kategori_prosedur' => 'Hidung & Mulut',
                'keterangan' => 'Scaling Karang Gigi',
            ],
            [
                'kode_prosedur' => '99.18',
                'nama_prosedur' => 'Injection or infusion of electrolytes or other solute',
                'kelompok_prosedur' => 'Tindakan Keperawatan',
                'kategori_prosedur' => 'Prosedur Diagnostik & Terapeutik Lainnya',
                'keterangan' => 'Pemasangan Infus Cairan Intravena',
            ],
            [
                'kode_prosedur' => '99.21',
                'nama_prosedur' => 'Injection of antibiotic',
                'kelompok_prosedur' => 'Tindakan Keperawatan',
                'kategori_prosedur' => 'Prosedur Diagnostik & Terapeutik Lainnya',
                'keterangan' => 'Injeksi Antibiotik / Obat Terapeutik',
            ],
        ];

        foreach ($prosedurs as $p) {
            MasterProsedur::updateOrCreate(
                ['kode_prosedur' => $p['kode_prosedur']],
                [
                    'nama_prosedur'      => $p['nama_prosedur'],
                    'kelompok_prosedur'  => $p['kelompok_prosedur'],
                    'kategori_prosedur'  => $p['kategori_prosedur'],
                    'keterangan'         => $p['keterangan'],
                    'is_active'          => true,
                ]
            );
        }
    }
}
