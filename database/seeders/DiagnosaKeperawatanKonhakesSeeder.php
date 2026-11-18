<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiagnosaKeperawatan;

class DiagnosaKeperawatanKonhakesSeeder extends Seeder
{
    public function run(): void
    {
        $diagnosas = [
            [
                'kode_diagnosa' => 'D0130',
                'nama_diagnosa' => 'Hipertermia',
                'keterangan' => 'Keterangan: Suhu tubuh meningkat di atas rentang normal tubuh',
            ],
            [
                'kode_diagnosa' => 'DP001',
                'nama_diagnosa' => 'Bersihan Jalan Nafas tidak efektif',
                'keterangan' => 'Bersihan jalan napas tidak efektif adalah ketidakmampuan membersihkan sekret atau obstruksi jalan nafas untuk mempertahankan jalan nafas tetap paten. Masuk dalam kategori fisiologis, subkategori respirasi dalam Standar Diagnosis Keperawatan Indonesia (SDKI).',
            ],
            [
                'kode_diagnosa' => 'DP002',
                'nama_diagnosa' => 'Gangguan Penyapihan Ventilator',
                'keterangan' => 'Gangguan penyapihan ventilator adalah ketidakmampuan beradaptasi dengan pengurangan bantuan ventilator mekanik yang dapat menghambat dan memperlama proses penyapihan. Masuk dalam kategori fisiologis, subkategori respirasi dalam Standar Diagnosis Keperawatan Indonesia (SDKI).',
            ],
            [
                'kode_diagnosa' => 'DP003',
                'nama_diagnosa' => 'Gangguan Pertukaran Gas',
                'keterangan' => 'Gangguan pertukaran gas adalah kelebihan atau kekurangan oksigenasi dan/atau eliminasi karbondioksida pada membran alveolus-kapiler. Gangguan pertukaran gas terjadi akibat ketidakseimbangan ventilasi-perfusi, atau perubahan membran alveolus-kapiler. Masuk dalam kategori fisiologis, subkategori respirasi dalam Standar Diagnosis Keperawatan Indonesia (SDKI).',
            ],
            [
                'kode_diagnosa' => 'DP004',
                'nama_diagnosa' => 'Gangguan Ventilasi Spontan',
                'keterangan' => 'Gangguan ventilasi spontan adalah penurunan cadangan energi yang mengakibatkan pasien tidak mampu bernapas secara adekuat. Masuk dalam kategori fisiologis, subkategori respirasi dalam Standar Diagnosis Keperawatan Indonesia (SDKI).',
            ],
            [
                'kode_diagnosa' => 'DP005',
                'nama_diagnosa' => 'Pola Nafas tidak efektif',
                'keterangan' => 'Pola napas tidak efektif adalah inspirasi dan/atau ekspirasi yang tidak memberikan ventilasi adekuat. Masuk dalam kategori fisiologis, subkategori respirasi dalam Standar Diagnosis Keperawatan Indonesia (SDKI).',
            ],
            [
                'kode_diagnosa' => 'DP006',
                'nama_diagnosa' => 'Risiko Aspirasi',
                'keterangan' => 'Risiko aspirasi adalah adalah risiko mengalami masuknya sekresi gastrointestinal, sekresi orofaring, benda cair atau padat ke dalam saluran trakeobronkhial akibat disfungsi mekanisme protektif saluran napas. Masuk dalam kategori fisiologis, subkategori respirasi dalam Standar Diagnosis Keperawatan Indonesia (SDKI).',
            ],
            [
                'kode_diagnosa' => 'DP007',
                'nama_diagnosa' => 'Penurunan Curah Jantung',
                'keterangan' => 'Ketidakadekuatan jantung memompa darah untuk memenuhi kebutuhan metabolisme tubuh.',
            ],
            [
                'kode_diagnosa' => 'DP008',
                'nama_diagnosa' => 'Perfusi Perifer Tidak Efektif',
                'keterangan' => 'Penurunan sirkulasi darah pada level kapiler yang dapat mengganggu metabolisme tubuh.',
            ],
            [
                'kode_diagnosa' => 'DP009',
                'nama_diagnosa' => 'Risiko Penurunan Curah Jantung',
                'keterangan' => 'Berisiko mengalami ketidakadekuatan jantung memompa darah untuk memenuhi kebutuhan metabolisme.',
            ],
            [
                'kode_diagnosa' => 'DP010',
                'nama_diagnosa' => 'Risiko Perdarahan',
                'keterangan' => 'Berisiko mengalami kehilangan darah baik internal maupun eksternal.',
            ],
            [
                'kode_diagnosa' => 'DP011',
                'nama_diagnosa' => 'Risiko Perfusi Gastrointestinal Tidak Efektif',
                'keterangan' => 'Berisiko mengalami penurunan sirkulasi darah pada saluran cerna.',
            ],
            [
                'kode_diagnosa' => 'DP012',
                'nama_diagnosa' => 'Risiko Perfusi Miokard Tidak Efektif',
                'keterangan' => 'Berisiko mengalami penurunan sirkulasi arteri koroner yang dapat mengganggu metabolisme miokard.',
            ],
            [
                'kode_diagnosa' => 'DP013',
                'nama_diagnosa' => 'Risiko Perfusi Perifer Tidak Efektif',
                'keterangan' => 'Berisiko mengalami penurunan sirkulasi darah pada level kapiler.',
            ],
            [
                'kode_diagnosa' => 'DP014',
                'nama_diagnosa' => 'Risiko Perfusi Renal Tidak Efektif',
                'keterangan' => 'Berisiko mengalami penurunan sirkulasi darah ke ginjal.',
            ],
            [
                'kode_diagnosa' => 'DP015',
                'nama_diagnosa' => 'Risiko Perfusi Serebral Tidak Efektif',
                'keterangan' => 'Berisiko mengalami penurunan sirkulasi darah ke otak.',
            ],
            [
                'kode_diagnosa' => 'DP016',
                'nama_diagnosa' => 'Defisit Nutrisi',
                'keterangan' => 'Asupan nutrisi tidak cukup untuk memenuhi kebutuhan metabolisme.',
            ],
            [
                'kode_diagnosa' => 'DP017',
                'nama_diagnosa' => 'Diare',
                'keterangan' => 'Pengeluaran feses yang sering, konsistensi lembek atau cair.',
            ],
            [
                'kode_diagnosa' => 'DP018',
                'nama_diagnosa' => 'Disfungsi Motilitas Gastrointestinal',
                'keterangan' => 'Peningkatan, penurunan, tidak efektif atau tidak terkoordinasinya aktivitas peristaltik saluran pencernaan.',
            ],
            [
                'kode_diagnosa' => 'DP019',
                'nama_diagnosa' => 'Hipervolemia',
                'keterangan' => 'Peningkatan volume cairan intravaskular, interstisial, dan/atau intraselular.',
            ],
            [
                'kode_diagnosa' => 'DP020',
                'nama_diagnosa' => 'Hipovolemia',
                'keterangan' => 'Penurunan volume cairan intravaskular, interstisial, dan/atau intraselular.',
            ],
            [
                'kode_diagnosa' => 'DP021',
                'nama_diagnosa' => 'Ikterik Neonatus',
                'keterangan' => 'Kulit dan membran mukosa neonatus berwarna kuning setelah 24 jam kelahiran akibat bilirubin tidak terkonjugasi.',
            ],
            [
                'kode_diagnosa' => 'DP022',
                'nama_diagnosa' => 'Kesiapan Peningkatan Keseimbangan Cairan',
                'keterangan' => 'Pola ekuilibrium antara asupan dan kehilangan cairan yang dapat ditingkatkan.',
            ],
            [
                'kode_diagnosa' => 'DP023',
                'nama_diagnosa' => 'Kesiapan Peningkatan Nutrisi',
                'keterangan' => 'Pola asupan nutrisi yang dapat ditingkatkan untuk memenuhi kebutuhan metabolisme.',
            ],
            [
                'kode_diagnosa' => 'DP024',
                'nama_diagnosa' => 'Ketidakstabilan Kadar Glukosa Darah',
                'keterangan' => 'Variasi kadar glukosa darah naik atau turun dari rentang normal.',
            ],
            [
                'kode_diagnosa' => 'DP025',
                'nama_diagnosa' => 'Konstipasi',
                'keterangan' => 'Penurunan defekasi normal yang disertai kesulitan atau pengeluaran feses tidak tuntas.',
            ],
            [
                'kode_diagnosa' => 'DP026',
                'nama_diagnosa' => 'Nausea',
                'keterangan' => 'Sensasi tidak nyaman pada bagian belakang tenggorokan atau lambung yang dapat mengakibatkan muntah.',
            ],
            [
                'kode_diagnosa' => 'DP027',
                'nama_diagnosa' => 'Obesitas',
                'keterangan' => 'Akumulasi lemak berlebih atau abnormal yang tidak sesuai dengan usia dan jenis kelamin.',
            ],
            [
                'kode_diagnosa' => 'DP028',
                'nama_diagnosa' => 'Risiko Berat Badan Lebih',
                'keterangan' => 'Berisiko mengalami akumulasi lemak berlebih atau abnormal.',
            ],
            [
                'kode_diagnosa' => 'DP029',
                'nama_diagnosa' => 'Risiko Defisit Nutrisi',
                'keterangan' => 'Berisiko mengalami asupan nutrisi tidak cukup untuk memenuhi kebutuhan metabolisme.',
            ],
            [
                'kode_diagnosa' => 'DP030',
                'nama_diagnosa' => 'Risiko Disfungsi Motilitas Gastrointestinal',
                'keterangan' => 'Berisiko mengalami peningkatan, penurunan, tidak efektif atau tidak terkoordinasinya aktivitas peristaltik.',
            ],
            [
                'kode_diagnosa' => 'DP031',
                'nama_diagnosa' => 'Risiko Gangguan Integritas Kulit/Jaringan',
                'keterangan' => 'Berisiko mengalami kerusakan kulit (dermis dan/atau epidermis) atau jaringan.',
            ],
            [
                'kode_diagnosa' => 'DP032',
                'nama_diagnosa' => 'Risiko Hipovolemia',
                'keterangan' => 'Berisiko mengalami penurunan volume cairan intravaskular, interstisial, dan/atau intraselular.',
            ],
            [
                'kode_diagnosa' => 'DP033',
                'nama_diagnosa' => 'Risiko Ketidakseimbangan Cairan',
                'keterangan' => 'Berisiko mengalami penurunan, peningkatan atau percepatan perpindahan cairan intravaskular.',
            ],
            [
                'kode_diagnosa' => 'DP034',
                'nama_diagnosa' => 'Risiko Ketidakseimbangan Elektrolit',
                'keterangan' => 'Berisiko mengalami perubahan kadar elektrolit serum yang dapat mengganggu kesehatan.',
            ],
            [
                'kode_diagnosa' => 'DP035',
                'nama_diagnosa' => 'Risiko Ketidakstabilan Kadar Glukosa Darah',
                'keterangan' => 'Berisiko mengalami variasi kadar glukosa darah naik atau turun dari rentang normal.',
            ],
            [
                'kode_diagnosa' => 'DP036',
                'nama_diagnosa' => 'Gangguan Eliminasi Urin',
                'keterangan' => 'Disfungsi eliminasi urin.',
            ],
            [
                'kode_diagnosa' => 'DP037',
                'nama_diagnosa' => 'Inkontinensia Fekal',
                'keterangan' => 'Perubahan kebiasaan buang air besar normal yang ditandai dengan pengeluaran feses secara involunter.',
            ],
            [
                'kode_diagnosa' => 'DP038',
                'nama_diagnosa' => 'Inkontinensia Urin Fungsional',
                'keterangan' => 'Pengeluaran urin tidak terkendali karena kesulitan mencapai toilet tepat waktu.',
            ],
            [
                'kode_diagnosa' => 'DP039',
                'nama_diagnosa' => 'Inkontinensia Urin Urgensi',
                'keterangan' => 'Pengeluaran urin involunter yang terjadi segera setelah ada dorongan berkemih yang kuat.',
            ],
            [
                'kode_diagnosa' => 'DP040',
                'nama_diagnosa' => 'Retensi Urin',
                'keterangan' => 'Pengosongan kandung kemih yang tidak lengkap.',
            ],
            [
                'kode_diagnosa' => 'DP041',
                'nama_diagnosa' => 'Gangguan Mobilitas Fisik',
                'keterangan' => 'Keterbatasan dalam gerakan fisik dari satu atau lebih ekstremitas secara mandiri.',
            ],
            [
                'kode_diagnosa' => 'DP042',
                'nama_diagnosa' => 'Intoleransi Aktivitas',
                'keterangan' => 'Ketidakcukupan energi fisiologis atau psikologis untuk bertahan atau menyelesaikan aktivitas sehari-hari.',
            ],
            [
                'kode_diagnosa' => 'DP043',
                'nama_diagnosa' => 'Keletihan',
                'keterangan' => 'Penurunan kapasitas kerja fisik dan mental yang tidak pulih dengan istirahat.',
            ],
            [
                'kode_diagnosa' => 'DP044',
                'nama_diagnosa' => 'Risiko Intoleransi Aktivitas',
                'keterangan' => 'Berisiko mengalami ketidakcukupan energi fisiologis atau psikologis untuk beraktivitas.',
            ],
            [
                'kode_diagnosa' => 'DP045',
                'nama_diagnosa' => 'Gangguan Pola Tidur',
                'keterangan' => 'Gangguan kualitas dan kuantitas waktu tidur akibat faktor eksternal.',
            ],
            [
                'kode_diagnosa' => 'DP046',
                'nama_diagnosa' => 'Insomnia',
                'keterangan' => 'Gangguan kualitas dan kuantitas tidur yang menghambat fungsi fisik dan mental.',
            ],
            [
                'kode_diagnosa' => 'DP047',
                'nama_diagnosa' => 'Ansietas',
                'keterangan' => 'Kondisi emosi dan pengalaman subyektif individu terhadap objek yang tidak jelas dan spesifik.',
            ],
            [
                'kode_diagnosa' => 'DP048',
                'nama_diagnosa' => 'Distres Spiritual',
                'keterangan' => 'Gangguan pada keyakinan atau sistem nilai yang menjadi sumber kekuatan dan harapan.',
            ],
            [
                'kode_diagnosa' => 'DP049',
                'nama_diagnosa' => 'Gangguan Citra Tubuh',
                'keterangan' => 'Perubahan persepsi tentang tubuh yang diakibatkan oleh perubahan bentuk, struktur atau fungsi tubuh.',
            ],
            [
                'kode_diagnosa' => 'DP050',
                'nama_diagnosa' => 'Gangguan Identitas Diri',
                'keterangan' => 'Ketidakmampuan mempertahankan keutuhan persepsi tentang diri sendiri.',
            ],
            [
                'kode_diagnosa' => 'DP051',
                'nama_diagnosa' => 'Harga Diri Rendah Kronis',
                'keterangan' => 'Evaluasi diri atau perasaan negatif tentang diri sendiri atau kemampuan diri yang berlangsung lama.',
            ],
            [
                'kode_diagnosa' => 'DP052',
                'nama_diagnosa' => 'Harga Diri Rendah Situasional',
                'keterangan' => 'Evaluasi atau perasaan negatif tentang diri sendiri sebagai respon terhadap kehilangan atau perubahan.',
            ],
            [
                'kode_diagnosa' => 'DP053',
                'nama_diagnosa' => 'Keputusasaan',
                'keterangan' => 'Kondisi individu memandang keterbatasan alternatif atau pilihan yang tersedia.',
            ],
            [
                'kode_diagnosa' => 'DP054',
                'nama_diagnosa' => 'Ketidakberdayaan',
                'keterangan' => 'Persepsi bahwa tindakan seseorang tidak akan mempengaruhi hasil secara bermakna.',
            ],
            [
                'kode_diagnosa' => 'DP055',
                'nama_diagnosa' => 'Nyeri Akut',
                'keterangan' => 'Pengalaman sensorik atau emosional yang berkaitan dengan kerusakan jaringan aktual atau fungsional mendadak atau lambat kurang dari 3 bulan.',
            ],
            [
                'kode_diagnosa' => 'DP056',
                'nama_diagnosa' => 'Nyeri Kronis',
                'keterangan' => 'Pengalaman sensorik atau emosional yang berkaitan dengan kerusakan jaringan berlangsung lebih dari 3 bulan.',
            ],
            [
                'kode_diagnosa' => 'DP057',
                'nama_diagnosa' => 'Nyeri Melahirkan',
                'keterangan' => 'Pengalaman sensorik atau emosional yang bervariasi menyenangkan sampai tidak menyenangkan yang berhubungan dengan persalinan.',
            ],
            [
                'kode_diagnosa' => 'DP058',
                'nama_diagnosa' => 'Gangguan Rasa Nyaman',
                'keterangan' => 'Perasaan kurang senang, lega dan sempurna dalam dimensi fisik, psikospiritual, lingkungan dan sosial.',
            ],
            [
                'kode_diagnosa' => 'DP059',
                'nama_diagnosa' => 'Defisit Perawatan Diri',
                'keterangan' => 'Ketidakmampuan melakukan atau menyelesaikan aktivitas perawatan diri (mandi, berhias, makan, toileting).',
            ],
            [
                'kode_diagnosa' => 'DP060',
                'nama_diagnosa' => 'Gangguan Integritas Kulit/Jaringan',
                'keterangan' => 'Kerusakan kulit (dermis dan/atau epidermis) atau jaringan (membran mukosa, kornea, fasia, otot, tendon).',
            ],
            [
                'kode_diagnosa' => 'DP061',
                'nama_diagnosa' => 'Hipotermia',
                'keterangan' => 'Suhu tubuh berada di bawah rentang normal tubuh.',
            ],
            [
                'kode_diagnosa' => 'DP062',
                'nama_diagnosa' => 'Termoregulasi Tidak Efektif',
                'keterangan' => 'Kegagalan mempertahankan suhu tubuh dalam rentang normal.',
            ],
            [
                'kode_diagnosa' => 'DP063',
                'nama_diagnosa' => 'Risiko Cedera',
                'keterangan' => 'Berisiko mengalami bahaya atau kerusakan fisik yang menyebabkan seseorang memerlukan bantuan.',
            ],
            [
                'kode_diagnosa' => 'DP064',
                'nama_diagnosa' => 'Risiko Infeksi',
                'keterangan' => 'Berisiko mengalami peningkatan terserang organisme patogenik.',
            ],
            [
                'kode_diagnosa' => 'DP065',
                'nama_diagnosa' => 'Risiko Jatuh',
                'keterangan' => 'Berisiko mengalami kerusakan fisik dan gangguan kesehatan akibat jatuh.',
            ],
            [
                'kode_diagnosa' => 'DP066',
                'nama_diagnosa' => 'Risiko Luka Tekan',
                'keterangan' => 'Berisiko mengalami cedera lokal pada kulit dan/atau jaringan yang mendasarinya akibat tekanan berkepanjangan.',
            ],
            [
                'kode_diagnosa' => 'DP067',
                'nama_diagnosa' => 'Risiko Termoregulasi Tidak Efektif',
                'keterangan' => 'Berisiko mengalami kegagalan mempertahankan suhu tubuh dalam rentang normal.',
            ],
            [
                'kode_diagnosa' => 'DP068',
                'nama_diagnosa' => 'Defisit Pengetahuan',
                'keterangan' => 'Ketiadaan atau kurangnya informasi kognitif yang berkaitan dengan topik tertentu.',
            ],
            [
                'kode_diagnosa' => 'DP069',
                'nama_diagnosa' => 'Kesiapan Peningkatan Manajemen Kesehatan',
                'keterangan' => 'Pola pengaturan dan pengintegrasian program penanganan masalah kesehatan yang dapat ditingkatkan.',
            ],
            [
                'kode_diagnosa' => 'DP070',
                'nama_diagnosa' => 'Kesiapan Peningkatan Pengetahuan',
                'keterangan' => 'Perkembangan informasi kognitif yang berhubungan dengan topik spesifik cukup untuk memenuhi tujuan kesehatan.',
            ],
            [
                'kode_diagnosa' => 'DP071',
                'nama_diagnosa' => 'Ketidakpatuhan',
                'keterangan' => 'Perilaku individu dan/atau pemberi asuhan tidak mengikuti rencana perawatan/pengobatan yang disepakati.',
            ],
            [
                'kode_diagnosa' => 'DP072',
                'nama_diagnosa' => 'Manajemen Kesehatan Keluarga Tidak Efektif',
                'keterangan' => 'Pola penanganan masalah kesehatan dalam keluarga tidak memuaskan untuk memulihkan kondisi kesehatan anggota keluarga.',
            ],
            [
                'kode_diagnosa' => 'DP073',
                'nama_diagnosa' => 'Manajemen Kesehatan Tidak Efektif',
                'keterangan' => 'Pola pengaturan dan pengintegrasian penanganan masalah kesehatan ke dalam kebiasaan hidup sehari-hari tidak memuaskan.',
            ],
            [
                'kode_diagnosa' => 'DP074',
                'nama_diagnosa' => 'Pemeliharaan Kesehatan Tidak Efektif',
                'keterangan' => 'Ketidakmampuan mengidentifikasi, mengelola, dan/atau menemukan bantuan untuk mempertahankan kesehatan.',
            ],
            [
                'kode_diagnosa' => 'DP075',
                'nama_diagnosa' => 'Koping Komunitas Tidak Efektif',
                'keterangan' => 'Pola adaptasi komunitas dan penyelesaian masalah yang tidak memuaskan untuk memenuhi tuntutan atau kebutuhan komunitas.',
            ],
            [
                'kode_diagnosa' => 'DP076',
                'nama_diagnosa' => 'Koping Tidak Efektif',
                'keterangan' => 'Ketidakmampuan menilai dan merespons stresor dan/atau ketidakmampuan menggunakan sumber-sumber yang ada untuk mengatasi masalah.',
            ],
            [
                'kode_diagnosa' => 'DP077',
                'nama_diagnosa' => 'Penurunan Koping Keluarga',
                'keterangan' => 'Orang terdekat anggota keluarga memberikan dukungan, kenyamanan, atau bantuan yang tidak adekuat.',
            ],
            [
                'kode_diagnosa' => 'DP078',
                'nama_diagnosa' => 'Isolasi Sosial',
                'keterangan' => 'Ketidakmampuan untuk membina hubungan yang erat, hangat, terbuka, dan interdependen dengan orang lain.',
            ],
            [
                'kode_diagnosa' => 'DP079',
                'nama_diagnosa' => 'Gangguan Komunikasi Verbal',
                'keterangan' => 'Penurunan, perlambatan, atau ketiadaan kemampuan untuk menerima, memproses, mengirim, dan/atau menggunakan sistem simbol.',
            ],
            [
                'kode_diagnosa' => 'DP080',
                'nama_diagnosa' => 'Kesiapan Persalinan',
                'keterangan' => 'Pola adaptasi kognitif dan perilaku dalam mempersiapkan persalinan yang dapat ditingkatkan.',
            ],
            [
                'kode_diagnosa' => 'DP081',
                'nama_diagnosa' => 'Risiko Cedera Maternal',
                'keterangan' => 'Berisiko mengalami kerusakan fisik pada maternal selama masa kehamilan hingga masa nifas.',
            ],
            [
                'kode_diagnosa' => 'DP082',
                'nama_diagnosa' => 'Risiko Cedera Janin',
                'keterangan' => 'Berisiko mengalami bahaya atau kerusakan fisik pada janin selama proses kehamilan dan persalinan.',
            ],
            [
                'kode_diagnosa' => 'DP083',
                'nama_diagnosa' => 'Ketidaknyamanan Pasca Partum',
                'keterangan' => 'Sensasi tidak menyenangkan yang dialami wanita sebagai respon terhadap kelahiran anak.',
            ],
            [
                'kode_diagnosa' => 'DP084',
                'nama_diagnosa' => 'Menyusui Efektif',
                'keterangan' => 'Kemampuan ibu memberikan ASI secara adekuat untuk memenuhi kebutuhan bayi.',
            ],
            [
                'kode_diagnosa' => 'DP085',
                'nama_diagnosa' => 'Menyusui Tidak Efektif',
                'keterangan' => 'Ketidakmampuan memberikan ASI secara langsung dari payudara kepada bayi/anak untuk memenuhi kebutuhan nutrisi.',
            ],
        ];

        foreach ($diagnosas as $d) {
            DiagnosaKeperawatan::updateOrCreate(
                ['kode_diagnosa' => $d['kode_diagnosa']],
                [
                    'nama_diagnosa' => $d['nama_diagnosa'],
                    'keterangan'    => $d['keterangan'],
                    'is_active'     => true,
                ]
            );
        }
    }
}
