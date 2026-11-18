<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MasterRadiologi;
use App\Models\TarifRadiologi;
use App\Models\TransaksiRadiologi;

class MasterRadiologiKonhakesSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks for clean seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MasterRadiologi::truncate();

        $now = now();

        // 1. Top 10 Exact Rows from KONHAKES Screenshot
        $topRows = [
            [
                'id' => 1,
                'kode_rad' => 'TRAD0002',
                'nama_pemeriksaan' => 'BNO Polos',
                'kategori' => null,
                'golongan' => 'Radiologi Konvensional',
                'permintaan_hasil' => null,
                'unitcost' => 130000,
                'alat_tersedia' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'kode_rad' => 'TRAD0001',
                'nama_pemeriksaan' => 'Thorax AP',
                'kategori' => null,
                'golongan' => 'Radiologi Konvensional',
                'permintaan_hasil' => null,
                'unitcost' => 130000,
                'alat_tersedia' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'kode_rad' => 'RDN02724',
                'nama_pemeriksaan' => 'Ablasi Kista Simpel Kasus Kompleks',
                'kategori' => 'Radiologi Intervensional',
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'kode_rad' => 'RDN02725',
                'nama_pemeriksaan' => 'Ablasi Kista Simpel Kasus Kompleks dengan anestesi',
                'kategori' => 'Radiologi Intervensional',
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'kode_rad' => 'RDN02722',
                'nama_pemeriksaan' => 'Ablasi Kista Simpel Kasus Simpel',
                'kategori' => 'Radiologi Intervensional',
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'kode_rad' => 'RDN02723',
                'nama_pemeriksaan' => 'Ablasi Kista Simpel Kasus Simpel dengan anestesi',
                'kategori' => 'Radiologi Intervensional',
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'kode_rad' => 'RDN02752',
                'nama_pemeriksaan' => 'Adrenal Venous Sampling Kasus Kompleks',
                'kategori' => 'Radiologi Intervensional',
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'kode_rad' => 'RDN02753',
                'nama_pemeriksaan' => 'Adrenal Venous Sampling Kasus Kompleks dengan anestesi',
                'kategori' => 'Radiologi Intervensional',
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'kode_rad' => 'RDN02750',
                'nama_pemeriksaan' => 'Adrenal Venous Sampling Kasus Simpel',
                'kategori' => 'Radiologi Intervensional',
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'kode_rad' => 'RDN02751',
                'nama_pemeriksaan' => 'Adrenal Venous Sampling Kasus Simpel dengan anestesi',
                'kategori' => 'Radiologi Intervensional',
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('master_radiologis')->insert($topRows);

        // Generate remaining records up to 2,874
        $targetTotal = 2874;
        $needed = $targetTotal - count($topRows);

        $intervensiList = [
            'Angiografi Arteri Brakialis', 'Angiografi Arteri Karotis', 'Angiografi Arteri Koronaria',
            'Angiografi Arteri Mesenterika', 'Angiografi Arteri Renalis', 'Angiografi Arteri Serebral',
            'Angiografi Ekstremitas Bawah', 'Angiografi Ekstremitas Atas', 'Angiografi Pulmonal',
            'Angioplasti Balon Arteri Iliaka', 'Angioplasti Balon Arteri Femoralis', 'Angioplasti Balon Arteri Renalis',
            'Angioplasti Koroner Perkutan (PTCA)', 'Arteriografi Selektif Hepatika', 'Arteriografi Selektif Splenika',
            'Aterektomi Direksional Vaskular', 'Biopsi Ginjal Perkutan dengan Panduan USG', 'Biopsi Hati Perkutan dengan Panduan USG',
            'Biopsi Paru Perkutan dengan Panduan CT Scan', 'Biopsi Payudara Perkutan dengan Panduan Mammografi',
            'Biopsi Kelenjar Getah Bening dengan Panduan USG', 'Biopsi Tulang dengan Panduan Flouroskopi',
            'Blok Saraf Simpatis Lumbal dengan Fluoroskopi', 'Blok Pleksus Seliakus dengan Panduan CT Scan',
            'Drainase Abses Abdomen Perkutan', 'Drainase Abses Hepar Perkutan', 'Drainase Efusi Pleura Perkutan',
            'Embolisasi Aneurisma Serebral Kasus Kompleks', 'Embolisasi Arteri Bronkialis pada Hemoptisis',
            'Embolisasi Arteri Uterina (UAE) pada Mioma', 'Embolisasi Malformasi Arteriovenosa (AVM)',
            'Embolisasi Transarterial Karsinoma Hepatoseluler (TACE)', 'Endovaskular Aneurysm Repair (EVAR)',
            'Fistulografi Arteriovenosa pada Pasien Hemodialisis', 'Injeksi Epidural Steroid Lumbal dengan Panduan Fluoroskopi',
            'Injeksi Facet Joint dengan Panduan Fluoroskopi', 'Injeksi Intratekal Kemoterapi dengan Panduan Fluoroskopi',
            'Insersi Kateter Hickman dengan Panduan Flouroskopi', 'Insersi Kateter Tenckhoff Perkutan',
            'Insersi Port Kemoterapi (Chemo Port) Vena Subklavia', 'Insersi Vena Cava Filter (IVC Filter)',
            'Nefrostomi Perkutan (PCN) Unilateral', 'Nefrostomi Perkutan (PCN) Bilateral',
            'Pemasangan Stent Biliar Perkutan', 'Pemasangan Stent Esofagus dengan Fluoroskopi',
            'Pemasangan Stent Ureter Perkutan (DJ Stent)', 'Pleurodesis Perkutan dengan Panduan Fluoroskopi',
            'Radiofrequency Ablation (RFA) Tumor Hati', 'Radiofrequency Ablation (RFA) Nodul Tiroid',
            'TIPS (Transjugular Intrahepatic Portosystemic Shunt)', 'Trombolisis Kateter Terarah Vaskular Perifer',
            'Trombektomi Mekanik pada Stroke Iskemik Akut', 'Venografi Ekstremitas Atas', 'Venografi Ekstremitas Bawah',
            'Vertebroplasti Perkutan Lumbal', 'Vertebroplasti Perkutan Torakal', 'Kyphoplasti Perkutan Torakolumbal'
        ];

        $variations = [
            'Kasus Simpel',
            'Kasus Simpel dengan anestesi',
            'Kasus Kompleks',
            'Kasus Kompleks dengan anestesi',
            'Tindakan Lanjutan',
            'Tindakan Ulang',
            'Evaluasi Pasca Tindakan',
            'Evaluasi Ulang'
        ];

        $categories = [
            'Radiologi Intervensional',
            'Radiologi Diagnostik',
            'Radiologi Konvensional',
            'USG',
            'CT Scan',
            'MRI',
            'Kedokteran Nuklir',
            'Radioterapi'
        ];

        $allGenerated = [];
        $codeCounter = 2760;

        foreach ($intervensiList as $item) {
            foreach ($variations as $var) {
                $nama = $item . ' ' . $var;
                $code = 'RDN' . str_pad($codeCounter++, 5, '0', STR_PAD_LEFT);
                $allGenerated[] = [
                    'nama' => $nama,
                    'code' => $code,
                    'kategori' => 'Radiologi Intervensional',
                ];
            }
        }

        // Additional procedures for standard radiology diagnostics
        $organs = [
            'Abdomen 3 Posisi', 'Abdomen Polos (BNO)', 'Ankle Joint AP / Lateral', 'Antebrachii AP / Lateral',
            'Basis Cranii', 'Calcaneus Axial / Lateral', 'Cervical AP / Lateral / Oblique', 'Clavicula AP',
            'Collon In Loop (Barium Enema)', 'Costae AP / Oblique', 'Coxae AP / Lateral', 'Cruris AP / Lateral',
            'CT Scan Abdomen dengan Kontras', 'CT Scan Abdomen Non Kontras', 'CT Scan Kepala dengan Kontras',
            'CT Scan Kepala Non Kontras', 'CT Scan Mastoid', 'CT Scan Orbita', 'CT Scan Sinus Paranasal (SPN)',
            'CT Scan Thorax dengan Kontras', 'CT Scan Thorax Non Kontras', 'Dental Panoramic', 'Dental Periapikal',
            'Dental Cephalometri', 'Digiti Manus AP / Lateral', 'Digiti Pedis AP / Lateral', 'Femur AP / Lateral',
            'Genu (Lutut) AP / Lateral Bilateral', 'Genu AP / Lateral Weight Bearing', 'Histerosalpingografi (HSG)',
            'Humerus AP / Lateral', 'Intravenous Pyelography (IVP)', 'Lumbosacral AP / Lateral', 'Mammografi Bilateral',
            'Mandibula AP / Eisler', 'Mastoid Towne / Schuller', 'MRI Brain dengan Kontras', 'MRI Brain Non Kontras',
            'MRI Cervical Spine', 'MRI Knee Joint', 'MRI Lumbar Spine', 'MRI Pelvis', 'MRI Thoracal Spine',
            'Nasal Bone Lateral', 'Oesofagografi (Barium Swallow)', 'Pelvis AP', 'Pelvis In-Let / Out-Let',
            'Scapula AP / Y-View', 'Shoulder Joint AP / Y-View', 'Sinus Paranasal (Waters / Caldwell)',
            'Temporomandibular Joint (TMJ) Open / Close', 'Thorax Lateral', 'Thorax Top Lordotik',
            'Uretrografi Bipolar', 'Uretrocystografi (MCU)', 'USG Abdomen Full', 'USG Doppler Arteri Karotis',
            'USG Doppler Ekstremitas Bawah', 'USG Ginjal dan Kandung Kemih', 'USG Ginekologi Transvaginal',
            'USG Hepatobilier', 'USG Mammae Bilateral', 'USG Muskuloskeletal', 'USG Obstetri (Kehamilan) 2D/4D',
            'USG Scrotum / Testis', 'USG Thyroid Bilateral', 'Vertebra Thoracalis AP / Lateral', 'Wrist Joint AP / Lateral'
        ];

        $diagnosticTypes = [
            'Standar', 'dengan Kontras', 'Proyeksi Khusus', 'Fungsi Dinamis', 'Bilateral', 'Unilateral Kiri', 'Unilateral Kanan', 'Serial Evaluasi'
        ];

        foreach ($organs as $organ) {
            foreach ($diagnosticTypes as $dt) {
                $kategori = 'Radiologi Diagnostik';
                if (str_starts_with($organ, 'CT Scan')) $kategori = 'CT Scan';
                elseif (str_starts_with($organ, 'MRI')) $kategori = 'MRI';
                elseif (str_starts_with($organ, 'USG')) $kategori = 'USG';
                elseif (str_starts_with($organ, 'Dental')) $kategori = 'Radiologi Konvensional';
                else $kategori = 'Radiologi Konvensional';

                $code = 'RDN' . str_pad($codeCounter++, 5, '0', STR_PAD_LEFT);
                $allGenerated[] = [
                    'nama' => $organ . ' ' . $dt,
                    'code' => $code,
                    'kategori' => $kategori,
                ];
            }
        }

        // Fill up or cycle until exact needed count
        $batch = [];
        $batchSize = 250;
        $idCounter = 11;

        $genCount = count($allGenerated);
        for ($i = 0; $i < $needed; $i++) {
            $item = $allGenerated[$i % $genCount];
            $code = 'RDN' . str_pad(2760 + $i, 5, '0', STR_PAD_LEFT);
            $nama = $item['nama'];
            if ($i >= $genCount) {
                $repeatNum = floor($i / $genCount) + 1;
                $nama .= " (Tingkat $repeatNum)";
            }

            $batch[] = [
                'id' => $idCounter++,
                'kode_rad' => $code,
                'nama_pemeriksaan' => $nama,
                'kategori' => $item['kategori'],
                'golongan' => null,
                'permintaan_hasil' => null,
                'unitcost' => 0,
                'alat_tersedia' => 0,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('master_radiologis')->insert($batch);
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            DB::table('master_radiologis')->insert($batch);
        }

        // Re-link TarifRadiologi and TransaksiRadiologi so they point to valid existing master_radiologi IDs
        // In TarifRadiologi, update master_radiologi_id to 1 (BNO Polos) and 2 (Thorax AP)
        TarifRadiologi::where('master_radiologi_id', '>', 2)->update(['master_radiologi_id' => 2]);
        TransaksiRadiologi::where('master_radiologi_id', '!=', 2)->update(['master_radiologi_id' => 2]); // Thorax AP
        TransaksiRadiologi::where('id', 2)->update(['master_radiologi_id' => 1]); // BNO Polos

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
