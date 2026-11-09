<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tindakan;
use App\Models\Poliklinik;

class TindakanProfaskesSeeder extends Seeder
{
    public function run()
    {
        $poliUmum = Poliklinik::where('nama_poli', 'LIKE', '%Umum 1%')->first() 
                 ?? Poliklinik::where('nama_poli', 'LIKE', '%Umum%')->first();
        $poliId = $poliUmum ? $poliUmum->id : null;

        $poliGigi = Poliklinik::where('nama_poli', 'LIKE', '%Gigi%')->first();
        $gigiId = $poliGigi ? $poliGigi->id : $poliId;

        $poliKIA = Poliklinik::where('nama_poli', 'LIKE', '%KIA%')->first();
        $kiaId = $poliKIA ? $poliKIA->id : $poliId;

        $tindakans = [
            // Shown in Screenshot 1
            ['poliklinik_id' => null,   'kode_tindakan' => 'TK035', 'nama_tindakan' => 'HECTING 2',        'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK000', 'nama_tindakan' => 'Administrasi',    'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK033', 'nama_tindakan' => 'DEBRIDEMENT/WT',  'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK036', 'nama_tindakan' => 'EXTRASI CEPE',    'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK034', 'nama_tindakan' => 'EXTRASI CLAPIUS', 'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK059', 'nama_tindakan' => 'EXTRASI CORPUS',  'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK031', 'nama_tindakan' => 'EXTRASI KUKU',    'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK074', 'nama_tindakan' => 'EXTRASI RECUVA',  'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK085', 'nama_tindakan' => 'EXTRASI SERUMEN', 'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK076', 'nama_tindakan' => 'GANTI PERBAN',     'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK032', 'nama_tindakan' => 'HECTING 1',        'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK037', 'nama_tindakan' => 'HECTING 3',        'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK038', 'nama_tindakan' => 'HECTING 4',        'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK039', 'nama_tindakan' => 'HECTING 5',        'is_konsultasi' => 1, 'is_informed_consent' => 1, 'is_ranap' => 1],

            // Additional realistic clinic medical procedures
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK001', 'nama_tindakan' => 'Pemeriksaan Dokter Umum',      'is_konsultasi' => 1, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK002', 'nama_tindakan' => 'Injeksi Intramuskular (IM)',    'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK003', 'nama_tindakan' => 'Injeksi Intravena (IV)',        'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK004', 'nama_tindakan' => 'Nebulisasi',                    'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK005', 'nama_tindakan' => 'Pasang Infus Dewasa',           'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK006', 'nama_tindakan' => 'Pasang Infus Anak',             'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK007', 'nama_tindakan' => 'Lepas Infus',                   'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK008', 'nama_tindakan' => 'Pasang Kateter Urin',           'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK009', 'nama_tindakan' => 'Lepas Kateter Urin',            'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK010', 'nama_tindakan' => 'Pasang NGT',                    'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK011', 'nama_tindakan' => 'EKG (Elektrokardiografi)',      'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK012', 'nama_tindakan' => 'Oksigenasi Per Jam',            'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK013', 'nama_tindakan' => 'Rawat Luka Ringan',             'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK014', 'nama_tindakan' => 'Rawat Luka Sedang',             'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK015', 'nama_tindakan' => 'Rawat Luka Berat / Gangren',    'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK016', 'nama_tindakan' => 'Insisi Abses Kecil',           'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK017', 'nama_tindakan' => 'Angkat Jahitan (Aff Hecting)', 'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK018', 'nama_tindakan' => 'Irigasi Mata',                  'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK019', 'nama_tindakan' => 'Irigasi Telinga',                'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK020', 'nama_tindakan' => 'Skin Test Alergi',              'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $gigiId,'kode_tindakan' => 'TK021', 'nama_tindakan' => 'Pemeriksaan Gigi Rutin',        'is_konsultasi' => 1, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $gigiId,'kode_tindakan' => 'TK022', 'nama_tindakan' => 'Pembersihan Karang Gigi (Scaling)', 'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $gigiId,'kode_tindakan' => 'TK023', 'nama_tindakan' => 'Tambal Gigi Sementara',        'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $gigiId,'kode_tindakan' => 'TK024', 'nama_tindakan' => 'Tambal Gigi Tetap Komposit',   'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $gigiId,'kode_tindakan' => 'TK025', 'nama_tindakan' => 'Cabut Gigi Susu',              'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $gigiId,'kode_tindakan' => 'TK026', 'nama_tindakan' => 'Cabut Gigi Tetap',             'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $kiaId, 'kode_tindakan' => 'TK027', 'nama_tindakan' => 'Pemeriksaan Kehamilan (ANC)',   'is_konsultasi' => 1, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $kiaId, 'kode_tindakan' => 'TK028', 'nama_tindakan' => 'Pemeriksaan Nifas (PNC)',       'is_konsultasi' => 1, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $kiaId, 'kode_tindakan' => 'TK029', 'nama_tindakan' => 'Pasang KB Suntik 1 Bulan',     'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $kiaId, 'kode_tindakan' => 'TK030', 'nama_tindakan' => 'Pasang KB Suntik 3 Bulan',     'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK040', 'nama_tindakan' => 'USG Abdomen',                  'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK041', 'nama_tindakan' => 'Pemeriksaan Darah Rutin',      'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK042', 'nama_tindakan' => 'Pemeriksaan Gula Darah Sewaktu','is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK043', 'nama_tindakan' => 'Pemeriksaan Asam Urat',        'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK044', 'nama_tindakan' => 'Pemeriksaan Kolesterol',       'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK045', 'nama_tindakan' => 'Pemeriksaan Golongan Darah',   'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK046', 'nama_tindakan' => 'Suntik Vitamin C',             'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK047', 'nama_tindakan' => 'Suntik Neurobion',             'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK048', 'nama_tindakan' => 'Tindakan Sirkumsisi / Khitan', 'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK049', 'nama_tindakan' => 'Suction Lendot / Dahak',       'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK050', 'nama_tindakan' => 'Kumbah Lambung',               'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK051', 'nama_tindakan' => 'Pemasangan Spalk / Bidai',    'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK052', 'nama_tindakan' => 'Pemasangan Gips Ringan',       'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK053', 'nama_tindakan' => 'Resusitasi Jantung Paru (RJP)','is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK054', 'nama_tindakan' => 'Fisioterapi Dada',             'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK055', 'nama_tindakan' => 'Tes Buta Warna',              'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK056', 'nama_tindakan' => 'Tes Visus Mata',              'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK057', 'nama_tindakan' => 'Surat Keterangan Sehat',       'is_konsultasi' => 1, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK058', 'nama_tindakan' => 'Surat Keterangan Bebas Narkoba','is_konsultasi'=> 1, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK060', 'nama_tindakan' => 'Imunisasi Polio Tetes',       'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK061', 'nama_tindakan' => 'Imunisasi BCG',               'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK062', 'nama_tindakan' => 'Imunisasi DPT-HB-Hib',        'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK063', 'nama_tindakan' => 'Imunisasi Campak / MR',        'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK064', 'nama_tindakan' => 'Pemasangan IUD',              'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK065', 'nama_tindakan' => 'Pelepasan IUD',                'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK066', 'nama_tindakan' => 'Pemasangan Implan / Susuk',   'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK067', 'nama_tindakan' => 'Pelepasan Implan / Susuk',     'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK068', 'nama_tindakan' => 'Pap Smear',                   'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK069', 'nama_tindakan' => 'IVA Test',                    'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK070', 'nama_tindakan' => 'Tindakan Partus Normal',      'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK071', 'nama_tindakan' => 'Manual Plasenta',             'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK072', 'nama_tindakan' => 'Episiotomi & Hecting Perineum','is_konsultasi'=> 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK073', 'nama_tindakan' => 'Resusitasi Bayi Baru Lahir',  'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK075', 'nama_tindakan' => 'Pemeriksaan Spirometri',      'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK077', 'nama_tindakan' => 'Perawatan Luka Bakar Grade I', 'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK078', 'nama_tindakan' => 'Perawatan Luka Bakar Grade II','is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK079', 'nama_tindakan' => 'Kauterisasi Kutil / Veruka',  'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK080', 'nama_tindakan' => 'Ekstirpasi Kista Ateroma',    'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK081', 'nama_tindakan' => 'Ekstirpasi Lipoma Kecil',     'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK082', 'nama_tindakan' => 'Vena Seksi / Venaseksi',      'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK083', 'nama_tindakan' => 'Pungsi Sendi (Artrosentesis)','is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK084', 'nama_tindakan' => 'Pemasangan Tampon Anterior Hidung', 'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK086', 'nama_tindakan' => 'Irigasi Kateter',              'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK087', 'nama_tindakan' => 'Enema / Klisma',              'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK088', 'nama_tindakan' => 'Pemberian Obat Supositoria',  'is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK089', 'nama_tindakan' => 'Observasi Pasien IGD (per jam)','is_konsultasi' => 0, 'is_informed_consent' => 0, 'is_ranap' => 1],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK090', 'nama_tindakan' => 'Konsultasi Gizi Klinis',      'is_konsultasi' => 1, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK091', 'nama_tindakan' => 'Konsultasi Psikologi',        'is_konsultasi' => 1, 'is_informed_consent' => 0, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK092', 'nama_tindakan' => 'Visum et Repertum Luar',      'is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 0],
            ['poliklinik_id' => $poliId,'kode_tindakan' => 'TK093', 'nama_tindakan' => 'Ambulance Rujukan (dalam kota)','is_konsultasi' => 0, 'is_informed_consent' => 1, 'is_ranap' => 1],
        ];

        foreach ($tindakans as $item) {
            $item['bhp'] = 0;
            $item['jasa_petugas'] = 0;
            $item['penyusutan_gedung'] = 0;
            $item['penyusutan_alat'] = 0;
            $item['unit_cost'] = 0;
            $item['is_active'] = 1;

            Tindakan::updateOrCreate(['kode_tindakan' => $item['kode_tindakan']], $item);
        }
    }
}
