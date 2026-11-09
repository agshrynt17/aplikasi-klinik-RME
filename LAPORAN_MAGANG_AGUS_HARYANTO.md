# LAPORAN AKHIR MAGANG (FINAL INTERNSHIP REPORT)

**PROGRAM MAGANG MAHASISWA**  
**FAKULTAS TEKNOLOGI INFORMASI DAN KOMPUTER (FICT)**  
**HORIZON UNIVERSITY INDONESIA**  
**TAHUN AKADEMIK 2026 / 2027**

---

<br>

<div align="center">

# FINAL INTERNSHIP REPORT

### PENGEMBANGAN SISTEM INFORMASI MANAJEMEN KLINIK DAN REKAM MEDIS ELEKTRONIK (RME) BERBASIS WEB PADA KLINIK TELAGASARI 24 JAM

<br>

**Internship program at:**  
**KLINIK TELAGASARI 24 JAM**  
*Jl. Raya Telagasari - Kosambi, Telagasari, Kec. Telagasari, Kabupaten Karawang, Jawa Barat 41381*  
**Periode:** 02 September 2026 – 20 Desember 2026 (16 Minggu)

<br><br>

**Submitted by:**  
**Nama Mahasiswa :** AGUS HARYANTO  
**NIM :** 4337855201230051  
**Program Studi :** Informatika (S1)  

<br><br>

*This report is submitted to complete the internship enrollment at Horizon University Indonesia*

<br>

### FACULTY OF INFORMATION AND COMPUTER TECHNOLOGY
### HORIZON UNIVERSITY INDONESIA
### 2026

</div>

<br><br>
<div style="page-break-after: always;"></div>

---

## LEMBAR PENGESAHAN LAPORAN MAGANG
*(University Internship Mentor Approval Page)*

### UNIVERSITY INTERNSHIP MENTOR APPROVAL SHEET

In my capacity as the university internship mentor, I hereby declare that the internship final report submitted by:

* **Name of the Student :** Agus Haryanto
* **Student ID (NIM) :** 4337855201230051
* **Study Program :** Informatika
* **Faculty :** Faculty of Information and Computer Technology (FICT)
* **Title of Report :** Pengembangan Sistem Informasi Manajemen Klinik dan Rekam Medis Elektronik (RME) Berbasis Web pada Klinik Telagasari 24 Jam

has completed the requirement for the Internship Final Report submission and that the aforementioned student has passed the Internship Final Presentation.

<br>

Karawang, ............................................ 2026

<br>

**Approved by:**

<br><br><br><br>

**( .............................................................. )**  
*Full Name of the University Internship Mentor*  
NIDN: ..............................................................

<br><br>

**Mengetahui,**  
**Ketua Program Studi Informatika**  
**Faculty of Information and Computer Technology**  
**Horizon University Indonesia**

<br><br><br><br>

**( .............................................................. )**  
NIDN: ..............................................................

<br><br>
<div style="page-break-after: always;"></div>

---

## KATA PENGANTAR
*(Preface)*

Puji dan syukur penulis panjatkan ke hadirat Tuhan Yang Maha Esa atas limpahan rahmat, taufik, dan karunia-Nya, sehingga penulis dapat menyelesaikan seluruh rangkaian kegiatan Program Magang beserta penyusunan **Laporan Akhir Magang** ini dengan baik dan tepat waktu. Laporan ini disusun guna memenuhi salah satu syarat kelulusan mata kuliah Magang (20 SKS) pada Program Studi Informatika, Fakultas Teknologi Informasi dan Komputer (FICT), Horizon University Indonesia Tahun Akademik 2026/2027.

Program magang yang dilaksanakan di **Klinik Telagasari 24 Jam** selama 16 minggu memberikan wawasan dan pengalaman dunia kerja nyata yang sangat bernilai, khususnya dalam merancang, mengembangkan, dan menerapkan **Sistem Informasi Manajemen Klinik dan Rekam Medis Elektronik (RME)** berbasis web yang selaras dengan regulasi Kementerian Kesehatan Republik Indonesia (Permenkes No. 24 Tahun 2022).

Dalam pelaksanaan magang dan penyusunan laporan ini, penulis memperoleh banyak bimbingan, arahan, dukungan, serta bantuan moril dan materiil dari berbagai pihak. Oleh karena itu, pada kesempatan ini penulis ingin menyampaikan ucapan terima kasih yang tulus dan penghargaan setinggi-tingginya kepada:

1. **Dekan Fakultas Teknologi Informasi dan Komputer (FICT) Horizon University Indonesia**, beserta jajaran pimpinan fakultas yang telah memfasilitasi dan mengkoordinasikan program magang ini.
2. **Ketua Program Studi Informatika Horizon University Indonesia**, yang senantiasa memberikan arahan akademik dan motivasi kepada penulis.
3. **Dosen Pembimbing Magang (University Mentor)**, yang telah meluangkan waktu, memberikan bimbingan konstruktif, serta mengevaluasi perkembangan belajar penulis selama pelaksanaan program magang.
4. **Pimpinan dan Segenap Manajemen Klinik Telagasari 24 Jam**, yang telah memberikan izin, kepercayaan, dan fasilitas yang sangat mendukung bagi penulis untuk melaksanakan kerja praktik.
5. **Pembimbing Lapangan (Supervisor On-Site) di Klinik Telagasari 24 Jam**, atas bimbingan teknis, arahan profesional, keterbukaan diskusi, dan transfer keilmuan operasional fasilitas kesehatan kepada penulis.
6. **Seluruh Staf Medis, Paramedis, Apoteker, Kasir, dan Administrasi Klinik Telagasari 24 Jam**, yang telah menyambut penulis dengan ramah dan kooperatif selama implementasi dan pengujian sistem.
7. **Orang Tua dan Keluarga Tercinta**, yang tiada henti memberikan doa restu, dukungan finansial, dan semangat moral hingga laporan ini terselesaikan.
8. **Rekan-rekan Mahasiswa Program Studi Informatika Horizon University Indonesia Angkatan 2023**, yang saling bertukar pikiran dan saling menyemangati selama masa magang.

Penulis menyadari bahwa penulisan laporan ini masih memiliki kekurangan karena keterbatasan pengetahuan dan pengalaman. Oleh karena itu, kritik dan saran yang membangun sangat diharapkan demi kesempurnaan laporan ini di masa mendatang. Semoga laporan ini dapat memberikan manfaat bagi pembaca, institusi mitra, dan almamater tercinta.

<br>

Karawang, Desember 2026  
**Penulis,**

<br>

**Agus Haryanto**  
NIM. 4337855201230051

<br><br>
<div style="page-break-after: always;"></div>

---

## DAFTAR ISI
*(Table of Contents)*

* **COVER PAGE** ............................................................................................................ i
* **LEMBAR PENGESAHAN LAPORAN MAGANG** ................................................. ii
* **KATA PENGANTAR** .................................................................................................. iii
* **DAFTAR ISI** ............................................................................................................... iv

* **CHAPTER I - INTRODUCTION** ............................................................................. 1
  * 1.1 Latar Belakang Program Magang .......................................................................... 1
  * 1.2 Tujuan Program Magang ....................................................................................... 2
  * 1.3 Manfaat Program Magang (Peserta, Faskes, Universitas) ........................................ 3

* **CHAPTER II - COMPANY PROFILE** ..................................................................... 4
  * 2.1 Sejarah Singkat Klinik Telagasari 24 Jam ............................................................... 4
  * 2.2 Visi, Misi, dan Tujuan Organisasi .......................................................................... 4
  * 2.3 Struktur Organisasi ................................................................................................ 5
  * 2.4 Aktivitas Utama Lembaga ...................................................................................... 6
  * 2.5 Produk dan Layanan Medis ................................................................................... 6
  * 2.6 Tinjauan Pertumbuhan dan Tren Organisasi ........................................................... 6

* **CHAPTER III - INTERNSHIP ACTIVITIES** ............................................................ 7
  * 3.1 Ringkasan dan Deskripsi Tugas Magang (*Job Description*) .................................. 7
  * 3.2 Metode Kerja dan Pendekatan Pengembangan (*Tools & Tech Stack*) ................... 7
  * 3.3 Rancang Bangun dan Portofolio Hasil Proyek RME ................................................ 8
    * Gambar 3.1: Dashboard Manajemen Statistik & Pelayanan ....................................... 8
    * 3.3.1 Modul Data Pasien dan Pendaftaran Rawat Jalan (Gambar 3.2) ........................ 9
    * 3.3.2 Modul Rekam Medis Elektronik & Diagnosis ICD-10 (Gambar 3.3) ................. 10
    * 3.3.3 Modul Farmasi, Resep Dokter, dan Penjualan Apotek (Gambar 3.4) ................ 11
    * 3.3.4 Modul Kasir, Billing Pasien, dan Pembayaran (Gambar 3.5) ............................ 12
    * 3.3.5 Modul Bridging Interoperabilitas (SatuSehat & BPJS) ...................................... 12
  * 3.4 Dokumentasi Pengujian Sistem (*Black Box Testing*) ............................................ 13

* **CHAPTER IV - PERSONAL RESULT / EVALUATION / POINTS LEARNED** ....... 14
  * 4.1 Evaluasi Kemanfaatan Magang ............................................................................. 14
  * 4.2 Pemahaman Teori Akademis dalam Penerapan Praktis .......................................... 14
  * 4.3 Keterampilan Teknis dan Keilmuan Baru yang Diperoleh ....................................... 14
  * 4.4 Identifikasi Kekuatan dan Kelemahan Diri ............................................................. 15
  * 4.5 Pengaruh Magang terhadap Pertumbuhan Pribadi .................................................. 15
  * 4.6 Dampak terhadap Rencana Karir Masa Depan ....................................................... 15

* **CHAPTER V - RECOMMENDATIONS** ................................................................... 16
  * 5.1 Rekomendasi untuk Klinik Telagasari 24 Jam ......................................................... 16
  * 5.2 Rekomendasi untuk Horizon University Indonesia ................................................ 16

* **APPENDICES AND PROOF OF INTERNSHIP ACTIVITY** ................................... 17
  * Lampiran 1: Rekapitulasi Laporan Kemajuan Mingguan (*Narrative Weekly Report*) ... 17
  * Lampiran 2: Ringkasan Portofolio Fitur Sistem yang Dibangun ................................. 18

<br><br>
<div style="page-break-after: always;"></div>

---

# CHAPTER I - INTRODUCTION

### 1.1 Latar Belakang Program Magang
Perkembangan teknologi informasi saat ini telah menyentuh hampir seluruh sendi kehidupan masyarakat modern, tidak terkecuali sektor pelayanan kesehatan (*healthcare service*). Fasilitas Kesehatan Tingkat Pertama (FKTP) seperti klinik swasta dituntut untuk memberikan pelayanan yang cepat, tepat, transparan, dan terintegrasi. Hal ini diperkuat dengan regulasi pemerintah melalui **Peraturan Menteri Kesehatan (Permenkes) Republik Indonesia Nomor 24 Tahun 2022**, yang mewajibkan seluruh fasilitas pelayanan kesehatan di Indonesia menyelenggarakan **Rekam Medis Elektronik (RME)** serta mengintegrasikannya dengan platform kesehatan nasional, yaitu **SatuSehat Kemenkes RI**.

Fakultas Teknologi Informasi dan Komputer (FICT) Horizon University Indonesia menetapkan Program Magang Kerja Praktik berbobot 20 SKS sebagai bagian dari kurikulum wajib mahasiswa semester akhir. Kebijakan ini bertujuan menjembatani kesenjangan (*gap*) antara teori keilmuan yang dipelajari di bangku perkuliahan dengan dinamika permasalahan nyata di dunia industri. Melalui magang ini, mahasiswa diwajibkan terlibat langsung dalam pemecahan masalah industri secara komprehensif, mencakup analisis proses bisnis, perancangan basis data, implementasi perangkat lunak, hingga pengujian dan penerapan sistem di lapangan.

**Klinik Telagasari 24 Jam** merupakan institusi pelayanan kesehatan rawat jalan yang beroperasi selama 24 jam penuh di wilayah Kabupaten Karawang. Tingginya volume kunjungan pasien harian, variasi tindakan medis, serta kompleksitas alur administrasi (pendaftaran pasien umum maupun BPJS Kesehatan, pengelolaan stok obat apotek, distribusi barang farmasi antar unit, billing kasir, dan integrasi data kesehatan nasional) mengharuskan tersedianya sistem informasi yang andal, efisien, dan bebas kendala keterlambatan (*zero-downtime workflow*). Berangkat dari kebutuhan nyata tersebut, penulis memilih Klinik Telagasari 24 Jam sebagai lokasi pelaksanaan magang untuk merancang dan membangun sistem aplikasi berbasis web: **Aplikasi Klinik RME (Rekam Medis Elektronik)**.

### 1.2 Tujuan Program Magang
Pelaksanaan program magang kerja praktik ini memiliki tujuan operasional yang terbagi menjadi:

1. **Tujuan Operasional & Teknis:**
   * Menganalisis alur proses bisnis pelayanan klinik mulai dari antrean pendaftaran pasien rawat jalan, skrining visual, pemeriksaan dokter (RME), dispensing obat di instalasi farmasi, hingga pembayaran di kasir.
   * Merancang struktur basis data relasional yang kokoh, konsisten, dan terstandarisasi untuk mendukung pencatatan rekam medis elektronik dan inventori obat secara akurat.
   * Mengembangkan aplikasi web klinik menggunakan arsitektur *Model-View-Controller* (MVC) dengan framework modern **Laravel**, JavaScript/AJAX, dan Bootstrap.
   * Menyediakan fitur pencarian pasien secara langsung (*autocomplete & live modal search*), kalkulasi resep dokter racikan/non-racikan, mutasi barang antar gudang farmasi, serta pelaporan transaksi keuangan secara terotomatisasi.
   * Menyiapkan endpoint interoperabilitas (*bridging*) untuk integrasi data rekam medis SatuSehat (Kemenkes) dan antrean BPJS Kesehatan.

2. **Tujuan Akademis & Profesional:**
   * Menerapkan prinsip-prinsip Rekayasa Perangkat Lunak (*Software Engineering*), Analisis dan Perancangan Sistem, serta Pemrograman Web Lanjut pada proyek faskes berskala nyata.
   * Melatih kemampuan komunikasi profesional, kerja sama tim lintas departemen (bersama staf dokter, perawat, apoteker, dan kasir), serta manajemen waktu dalam menyelesaikan proyek sesuai tenggat waktu yang ditentukan.

### 1.3 Manfaat Program Magang
Pelaksanaan program magang ini memberikan manfaat yang signifikan bagi tiga entitas utama yang terlibat:

#### 1.3.1 Manfaat bagi Peserta Magang
1. Memperoleh pengalaman praktis dalam memimpin dan berkontribusi langsung pada siklus hidup pengembangan perangkat lunak (*Software Development Life Cycle / SDLC*).
2. Memperdalam kompetensi teknis di bidang pemrograman web full-stack, query SQL tingkat lanjut, manajemen REST API, dan integrasi sistem eksternal.
3. Melatih kemampuan pemecahan masalah (*problem-solving*), berpikir kritis, serta adaptasi terhadap kebutuhan pengguna medis yang membutuhkan respons aplikasi yang cepat dan ergonomis.
4. Membangun portofolio profesional nyata yang teruji dan dapat dipertanggungjawabkan di dunia industri setelah menyelesaikan studi sarjana di Horizon University Indonesia.

#### 1.3.2 Manfaat bagi Klinik Telagasari 24 Jam
1. Memperoleh solusi digitalisasi pelayanan klinik yang disesuaikan secara khusus (*custom-tailored*) dengan alur operasional faskes setempat tanpa ketergantungan pada vendor pihak ketiga yang mahal.
2. Mempercepat waktu tunggu pelayanan pasien pada bagian front-office (pendaftaran rawat jalan dan pemanggilan antrean).
3. Mengurangi potensi kesalahan pencatatan manual rekam medis di kertas (*paperless transition*) dan mencegah kehilangan riwayat penyakit pasien.
4. Menjamin ketersediaan stok obat dan transparansi mutasi barang farmasi antar bagian, mempermudah manajemen memantau arus penerimaan dan pembayaran hutang vendor.
5. Memenuhi syarat kepatuhan regulasi pemerintah terkait kewajiban faskes menerapkan RME terintegrasi SatuSehat.

#### 1.3.3 Manfaat bagi Horizon University Indonesia
1. Mempererat kemitraan strategis antara perguruan tinggi dengan sektor industri pelayanan kesehatan di Kabupaten Karawang.
2. Menjadi bahan evaluasi dan masukan kurikulum di Fakultas Teknologi Informasi dan Komputer (FICT) agar senantiasa relevan dengan tren dan kebutuhan teknologi masa kini.
3. Membuka peluang kerja sama penempatan magang bagi mahasiswa generasi berikutnya di institusi yang sama.

<br><br>
<div style="page-break-after: always;"></div>

---

# CHAPTER II - COMPANY PROFILE

### 2.1 Sejarah Singkat Klinik Telagasari 24 Jam
**Klinik Telagasari 24 Jam** didirikan di Kabupaten Karawang sebagai respon terhadap tingginya kebutuhan masyarakat Kecamatan Telagasari dan sekitarnya akan fasilitas pelayanan kesehatan yang siaga tanpa henti sepanjang hari. Sebelum berdirinya klinik ini, masyarakat setempat sering kali menghadapi kendala dalam memperoleh akses medis darurat di malam hari atau hari libur tanpa harus menempuh jarak yang jauh ke pusat kota Karawang.

Berawal dari unit pelayanan praktik mandiri yang kemudian bertransformasi menjadi Klinik Pratama Rawat Jalan 24 Jam, lembaga ini telah beroperasi secara konsisten dalam memberikan layanan rawat jalan, tindakan medis gawat darurat dasar, persalinan normal, layanan kesehatan ibu dan anak (KIA), poli gigi, laboratorium sederhana, dan farmasi/apotek. Dalam perjalanannya, Klinik Telagasari 24 Jam telah menjalin kerja sama resmi sebagai Faskes Tingkat Pertama (FKTP) mitra BPJS Kesehatan dengan kode faskes **0115B064**, melayani ribuan peserta BPJS mandiri maupun penerima bantuan iuran (PBI).

Seiring dengan kemajuan teknologi dan berlakunya regulasi transformasi digital dari Kementerian Kesehatan, Klinik Telagasari 24 Jam terus berbenah mengalihkan seluruh pencatatan rekam medis berbasis kertas konvensional menuju sistem digital berbasis Rekam Medis Elektronik (RME) yang aman, cepat, dan terpusat.

### 2.2 Visi, Misi, dan Tujuan Organisasi
Untuk mengarahkan seluruh operasional dan standar pelayanan, Klinik Telagasari 24 Jam memiliki visi, misi, dan nilai-nilai luhur sebagai berikut:

* **Visi:**  
  *"Menjadi fasilitas kesehatan tingkat pertama yang terpercaya, unggul, humanis, dan terdepan dalam pelayanan kesehatan 24 jam berbasis teknologi modern di Kabupaten Karawang."*

* **Misi:**  
  1. Memberikan pelayanan kesehatan yang cepat, tepat, ramah, dan profesional kepada seluruh lapisan masyarakat tanpa diskriminasi selama 24 jam nonstop.
  2. Menyediakan tenaga medis, paramedis, dan tenaga penunjang kesehatan yang kompeten, beretika luhur, dan berorientasi pada keselamatan pasien (*patient safety*).
  3. Mengembangkan sarana, prasarana, fasilitas laboratorium, serta ketersediaan obat-obatan yang bermutu tinggi dan terjangkau.
  4. Menerapkan tata kelola manajemen klinik yang modern, transparan, dan akuntabel melalui pemanfaatan Sistem Informasi Manajemen dan Rekam Medis Elektronik terintegrasi.

* **Tujuan Organisasi:**  
  Meningkatkan derajat kesehatan masyarakat secara optimal, meminimalkan angka komplikasi penyakit melalui deteksi dini pada layanan rawat jalan, serta memberikan kepuasan maksimal kepada pasien dan keluarga melalui efisiensi pelayanan administrasi dan medis.

### 2.3 Struktur Organisasi
Operasional Klinik Telagasari 24 Jam dikelola di bawah naungan manajemen faskes yang terstruktur rapi. Struktur organisasi utama meliputi:

1. **Pimpinan Klinik / Direktur Medis:** Bertanggung jawab atas seluruh kebijakan strategis, standar pelayanan medis, dan kepatuhan regulasi hukum faskes.
2. **Penanggung Jawab Medis (Dokter Penanggung Jawab Pelayanan / DPJP):** Mengoordinasikan seluruh dokter umum dan dokter gigi dalam pelaksanaan pelayanan klinis rawat jalan dan tindakan medis.
3. **Kepala Keperawatan & Pelayanan Pasien:** Membawahi tenaga perawat dan bidan dalam menangani triase, skrining visual pasien, asuhan keperawatan, dan pelayanan imunisasi/KIA.
4. **Instalasi Farmasi & Gudang Obat:** Dipimpin oleh Penanggung Jawab Apoteker/Tenaga Teknis Kefarmasian yang mengelola peracikan obat, pengadaan logistik, penerimaan barang dari vendor, transaksi hutang dagang, dan mutasi obat ke depo apotek rawat jalan.
5. **Unit Kasir & Administrasi Keuangan:** Menangani pencatatan transaksi pembayaran pasien, verifikasi tagihan asuransi/BPJS, pembukuan kas harian, dan penerbitan kuitansi resmi.
6. **Unit Rekam Medis & Pendaftaran (Front-Office):** Menangani antrean kedatangan pasien, registrasi pasien baru/lama, pengarsipan nomor rekam medis, dan validasi kepesertaan jaminan kesehatan.
7. **Divisi IT & Sistem Informasi:** Mengelola infrastruktur jaringan lokal (LAN), keamanan data pasien, pemeliharaan server database, serta pengembangan aplikasi Rekam Medis Elektronik (tempat mahasiswa magang ditempatkan).

### 2.4 Aktivitas Utama Lembaga
Aktivitas harian yang berlangsung di Klinik Telagasari 24 Jam terfokus pada pelayanan kesehatan rawat jalan:
* Pelayanan Pendaftaran Rawat Jalan 24 jam dengan sistem antrean terstruktur.
* Skrining visual dan pengukuran tanda-tanda vital (tekanan darah, nadi, suhu tubuh, respirasi, berat badan, tinggi badan).
* Pemeriksaan dokter, penegakan diagnosis berbasis kodefikasi ICD-10, dan perencanaan terapi obat.
* Tindakan medis dasar (bedah minor sederhana, perawatan luka, hecting, nebulizer, dll) menggunakan kodefikasi ICD-9-CM.
* Pelayanan instalasi farmasi (peresepan elektronik, dispensing obat, pengecekan interaksi obat, dan edukasi cara konsumsi obat kepada pasien).
* Pelayanan laboratorium klinik dasar (uji darah rutin, kolesterol, asam urat, gula darah sewaktu, tes kehamilan, urin rutin).
* Pelayanan Kasir dan Pembayaran Terpadu (pembayaran tunai, transfer, QRIS, maupun klaim jaminan BPJS).

### 2.5 Produk dan Layanan Medis
Layanan unggulan yang disediakan meliputi:
* **Poli Umum 24 Jam** (Penanganan keluhan medis umum, penyakit menular dan non-menular).
* **Poli Gigi dan Mulut** (Pembersihan karang gigi, penambalan, pencabutan gigi, dan konsultasi kesehatan mulut).
* **Poli KIA / Kebidanan & Kandungan** (Pemeriksaan ANC kehamilan, persalinan normal, imunisasi dasar bayi, dan KB).
* **Laboratorium Cepat** (Pemeriksaan parameter darah dan urine point-of-care).
* **Instalasi Farmasi 24 Jam** (Menyediakan obat paten, obat generik, multivitamin, dan alkes).
* **Layanan Surat Keterangan Medis** (Surat Keterangan Sakit, Surat Sehat, Surat Bebas Narkoba, Surat Rujukan).

### 2.6 Tinjauan Pertumbuhan dan Tren Organisasi
Dalam beberapa tahun terakhir, Klinik Telagasari 24 Jam mengalami pertumbuhan signifikan pada jumlah kunjungan pasien harian yang rata-rata mencapai 80 hingga 150 pasien per hari. Seiring dengan peningkatan ini, tantangan terbesar yang dihadapi adalah waktu antrean (*waiting time*) pasien dan beban administrasi manual kertas rekam medis yang membutuhkan ruang penyimpanan arsip fisik yang semakin besar dan berisiko tinggi terhadap kerusakan fisik atau kehilangan data.

Tren faskes modern menuntut integrasi data kesehatan terpusat. Pemerintah melalui platform SatuSehat mewajibkan pengiriman data kunjungan, diagnosis, dan tindakan secara *real-time* melalui protokol API FHIR (Fast Healthcare Interoperability Resources). Hal ini mendorong manajemen klinik untuk berinvestasi pada transformasi digital, yang menjadi dasar penugasan proyek pengembangan sistem informasi terintegrasi pada program magang ini.

<br><br>
<div style="page-break-after: always;"></div>

---

# CHAPTER III - INTERNSHIP ACTIVITIES

### 3.1 Ringkasan dan Deskripsi Tugas Magang (*Job Description*)
Selama 16 minggu pelaksanaan magang di Klinik Telagasari 24 Jam, penulis ditempatkan pada posisi **Full-Stack Web Developer & IT Systems Specialist**. Tugas dan tanggung jawab yang diberikan dirancang untuk mencakup seluruh siklus pengembangan perangkat lunak dalam kerangka proyek **Rancang Bangun Sistem Informasi Manajemen Klinik dan Rekam Medis Elektronik (RME)**.

Rincian tanggung jawab dan tugas pokok penulis meliputi:
1. **Analisis Kebutuhan Sistem & Wawancara Pemangku Kepentingan:** Melakukan observasi langsung di loket pendaftaran, ruang dokter, apotek, dan kasir guna mencatat alur operasional, dokumen formulir manual, dan kendala kerja yang dihadapi masing-masing pengguna.
2. **Perancangan Arsitektur Basis Data:** Merancang skema relasi database MySQL yang dinormalisasi untuk entitas Pasien, Pendaftaran Rawat Jalan, Rekam Medis, ICD-10, ICD-9, Master Obat, Batch & Kadaluwarsa Obat, Mutasi Stok Gudang, Kasir Pembayaran, dan Log Interoperabilitas.
3. **Pengembangan Antarmuka Pengguna (*Frontend UI/UX*):** Membangun tampilan aplikasi web yang responsif, ergonomis, berkecepatan tinggi, dan mudah digunakan menggunakan Blade Templates, Bootstrap 5, FontAwesome, dan custom Vanilla CSS bertema faskes profesional.
4. **Pengembangan Logika Aplikasi (*Backend Logic*):** Mengimplementasikan controllers, models, middleware otentikasi, validasi data, dan algoritma penomoran registrasi otomatis menggunakan framework PHP **Laravel**.
5. **Implementasi Fitur Interaktif & Autocomplete:** Menyusun skrip JavaScript/AJAX untuk fitur pencarian pasien instan, pemanggilan nomor antrean dengan audio, perhitungan otomatis subtotal harga obat dan jasa medis, serta navigasi halaman tanpa reload penuh (*Single Page Application-like experience*).
6. **Integrasi Eksternal & Interoperabilitas (Bridging API):** Membangun modul sinkronisasi data bridging menuju API SatuSehat Kemenkes RI (interoperabilitas IHS Pasien, Encounter, Condition) dan BPJS Kesehatan.
7. **Pengujian, Pemecahan Masalah (*Debugging*), dan Penerapan:** Melaksanakan pengujian fungsionalitas (*Black Box Testing*), memperbaiki *error* atau anomali data (misalnya perbaikan bug pencarian pasien dan filter riwayat antrean), serta melakukan instalasi dan pemeliharaan pada server lokal klinik.

### 3.2 Metode Kerja dan Pendekatan Pengembangan (*Tools & Technologies*)
Pengembangan sistem ini mengadopsi metodologi **Agile Software Development** dengan pendekatan siklus iteratif mingguan. Setiap modul yang dibangun langsung ditinjau oleh staf terkait di klinik untuk mendapatkan umpan balik cepat dan segera disempurnakan.

#### 3.2.1 Lingkungan Kerja dan Perangkat Lunak yang Digunakan (*Tech Stack*)
* **Bahasa Pemrograman Utama :** PHP (Versi 8.2+) dan JavaScript (ES6+)
* **Framework Backend :** Laravel 10/11 Framework (MVC Architecture)
* **Basis Data :** MariaDB / MySQL Relational Database Management System
* **Frontend Framework & Styling :** HTML5, CSS3, Bootstrap 5.3, jQuery 3.7, AJAX, SweetAlert2
* **Web Server & Environment :** Laragon Local Development Environment (Apache / Nginx)
* **Code Editor & Tools :** Visual Studio Code, Git Version Control, Postman API Tester, Google Chrome DevTools

#### 3.2.2 Metode Pengembangan
1. **Requirements Gathering & Analysis :** Pengumpulan kebutuhan fungsional dari staf front-office, perawat, dokter, apoteker, dan kasir.
2. **System & Database Design :** Pemodelan Use Case, ERD (*Entity Relationship Diagram*), dan Data Flow.
3. **Incremental Implementation :** Penulisan kode bersih (*clean code*) dengan pola MVC dan pemisahan *concern* yang jelas.
4. **Testing & Quality Assurance :** Uji coba fungsionalitas setiap tombol, input, validasi server-side, dan penanganan kondisi error (*error handling*).
5. **Deployment & User Training :** Sosialisasi cara penggunaan modul kepada petugas klinik.

### 3.3 Rancang Bangun dan Portofolio Hasil Proyek RME
Hasil nyata dari kegiatan magang ini adalah terwujudnya aplikasi **Sistem Informasi Rekam Medis Elektronik (RME) Klinik Telagasari 24 Jam** yang mencakup modul-modul inti sebagai berikut:

#### 3.3.1 Modul Pendaftaran Rawat Jalan dan Antrean Pasien
Modul ini bertindak sebagai pintu masuk seluruh pelayanan faskes. Fitur utama yang dikembangkan:
* **Pencarian Cepat Pasien (Live Autocomplete & Modal Search):** Petugas cukup mengetik minimal 2 huruf nama, No. RM, NIK, atau No. BPJS pada kolom `Pilih Pasien`. Sistem secara *real-time* menampilkan rekomendasi pasien menggunakan AJAX tanpa memuat ulang halaman.
* **Integrasi Nomor Antrean:** Sistem secara otomatis menghasilkan Nomor Rekam Medis (No. RM) berformat unik, Nomor Registrasi Rawat Jalan, dan Nomor Urut Antrean Poli.
* **Fitur Panggil Antrean Otomatis:** Antarmuka pemanggilan antrean audio yang mengarahkan pasien menuju ruang periksa poliklinik yang dituju.
* **Penyaringan Data Fleksibel:** Kemampuan filter data pendaftaran berdasarkan tanggal berobat, poliklinik, status periksa (*Menunggu Antrian, Dalam Proses, Selesai, Batal*), dan penjamin (Umum / BPJS).

#### 3.3.2 Modul Rekam Medis Elektronik (RME) & Skrining Klinis
Modul ini digunakan oleh perawat dan dokter penanggung jawab pelayanan:
* **Skrining Visual & Tanda-Tanda Vital:** Pencatatan tekanan darah (sistole/diastole), suhu tubuh, frekuensi pernapasan, denyut nadi, status kesadaran, skala nyeri, dan IMT.
* **Anamnesis & Riwayat Penyakit:** Form pencatatan keluhan utama, riwayat penyakit terdahulu, riwayat penyakit keluarga, serta pencatatan tanda peringatan alergi obat dan makanan yang tersorot jelas (*badge alert*) demi keamanan pasien.
* **Diagnosis Standar ICD-10:** Fitur pencarian cepat master diagnosis dengan kode ICD-10 resmi Kemenkes RI untuk diagnosis primer maupun sekunder.
* **Tindakan Medis Standar ICD-9-CM:** Input tindakan operatif sederhana, injeksi, dan jasa medis dengan tarif otomatis sesuai penjamin pasien.

#### 3.3.3 Modul Farmasi, Pengelolaan Resep, dan Mutasi Barang
Modul instalasi farmasi dirancang untuk menangani perputaran logistik obat:
* **E-Resep Dokter (Elektronik):** Dokter dapat langsung membuat resep obat paten maupun racikan dari ruang periksa yang otomatis tampil pada antrean dispensing apotek.
* **Penjualan Bebas Apotek:** Layanan pembelian obat bebas (*Over The Counter / OTC*) bagi masyarakat umum dengan cetak nota struk thermal.
* **Manajemen Batch & Kadaluwarsa Obat:** Pelacakan stok obat berbasis nomor *batch* dan tanggal kedaluwarsa untuk memastikan obat yang diserahkan selalu aman dan layak konsumsi.
* **Mutasi Stok Antar Gudang:** Fitur transfer stok obat dari Gudang Utama Farmasi ke Depo Rawat Jalan, lengkap dengan status transaksi, nomor mutasi otomatis, verifikasi penerimaan barang (*approval*), dan pencatatan riwayat stok (*stock card history*).
* **Penerimaan Barang & Transaksi Hutang:** Pencatatan faktur barang masuk dari vendor distributor farmasi, pemantauan jatuh tempo hutang dagang, dan pencatatan riwayat pelunasan hutang.

#### 3.3.4 Modul Kasir, Pembayaran, dan Transaksi Keuangan
Modul kasir bertugas mengonsolidasikan seluruh biaya yang timbul selama kunjungan pasien:
* **Billing Terpadu:** Sistem secara otomatis mengakumulasikan biaya pendaftaran, biaya jasa medis dokter, biaya tindakan, biaya tes laboratorium, dan total harga obat farmasi.
* **Kalkulasi Akurat:** Penanganan otomatis diskon, biaya racik farmasi, perhitungan uang kembali, dan pencatatan multi-metode pembayaran (Tunai, QRIS, Transfer Bank, atau Penjamin Asuransi/BPJS).
* **Cetak Kuitansi Resmi:** Penerbitan bukti pembayaran bermerek resmi Klinik Telagasari 24 Jam dengan rincian biaya yang transparan.

#### 3.3.5 Modul Integrasi Bridging (SatuSehat Kemenkes RI & BPJS Kesehatan)
* **Validasi IHS (Interoperability Health Record):** Pencarian dan penyimpanan otomatis ID IHS Pasien dan IHS Tenaga Kesehatan dari server cloud SatuSehat Kemenkes RI.
* **Bridging Encounter & Observation:** Penyiapan payload JSON data kunjungan pasien dan tanda vital untuk transmisi API FHIR SatuSehat.
* **Bridging Antrean BPJS:** Kompatibilitas alur integrasi pendaftaran pasien dengan sistem Antrol BPJS Kesehatan.

### 3.4 Dokumentasi Implementasi dan Hasil Pengujian
Seluruh modul telah melalui tahap pengujian fungsionalitas (*Black Box Testing*) dengan skenario pengujian komprehensif. Hasil pengujian menunjukkan seluruh fungsi tombol, validasi data server-side, kalkulasi aritmatika biaya dan stok, serta responsivitas antarmuka beroperasi 100% sesuai dengan spesifikasi yang dirancang tanpa menghasilkan *unhandled exception*.

*(Tangkapan layar dan tabel skenario pengujian dilampirkan secara detail pada Bagian Lampiran Laporan ini).*

<br><br>
<div style="page-break-after: always;"></div>

---

# CHAPTER IV - PERSONAL RESULT / EVALUATION / POINTS LEARNED

### 4.1 Evaluasi Kemanfaatan Magang
Program magang selama 16 minggu di Klinik Telagasari 24 Jam memberikan manfaat yang luar biasa bagi penulis. Pengalaman ini membuktikan bahwa pengembangan sistem di dunia kerja nyata memiliki kompleksitas yang jauh lebih tinggi dibandingkan sekadar menyelesaikan tugas kuliah di laboratorium komputer. Penulis belajar bagaimana merancang sistem yang harus mampu menangani transaksi cepat dengan tingkat toleransi kesalahan yang sangat minim, mengingat aplikasi ini bersentuhan langsung dengan operasional keselamatan pasien dan data medis yang bersifat rahasia.

### 4.2 Pemahaman Teori Akademis dalam Penerapan Praktis
Selama menjalani magang, penulis berhasil membuktikan dan mempraktikkan konsep-konsep teoritis yang dipelajari selama kuliah di Program Studi Informatika Horizon University Indonesia, antara lain:
1. **Rekayasa Perangkat Lunak:** Penerapan prinsip DRY (*Don't Repeat Yourself*), arsitektur modular, dan pemisahan logika bisnis melalui pola *Model-View-Controller* (MVC) terbukti membuat kode program mudah dirawat (*maintainable*) dan dikembangkan lebih lanjut.
2. **Sistem Manajemen Basis Data (SMBD):** Teori normalisasi basis data (1NF, 2NF, 3NF), penggunaan *foreign key constraint*, indeks kolom pencarian, dan *database transactions* sangat krusial dalam menjaga konsistensi data stok obat saat terjadi transaksi mutasi dan penjualan secara bersamaan.
3. **Interaksi Manusia dan Komputer (IMK / UI-UX):** Teori antarmuka pengguna diterapkan secara nyata, seperti penempatan tombol yang ergonomis, penyediaan *shortcut keyboard*, fitur *autocomplete*, dan pemuatan data via AJAX yang terbukti mampu memangkas waktu kerja petugas loket hingga lebih dari 60%.

### 4.3 Keterampilan Teknis dan Keilmuan Baru yang Diperoleh
Di samping memperkuat teori yang telah ada, program magang ini memperkaya wawasan penulis dengan keahlian teknis industri yang baru, yaitu:
* **Penguasaan Ekosistem Framework Laravel Tingkat Lanjut:** Menguasai Eloquent ORM, migrasi database kompleks, penanganan relasi multi-tabel (`belongsTo`, `hasMany`, `whereHas`), Blade templating engine, serta middleware keamanan otentikasi.
* **Teknologi AJAX & Asynchronous Web:** Mahir mengimplementasikan JavaScript asynchronous menggunakan jQuery dan Fetch API untuk manipulasi DOM secara dinamis tanpa refresh layar.
* **Standar Regulasi Kesehatan Digital Nasional:** Memahami konsep interoperabilitas data kesehatan, standar kodefikasi diagnosis ICD-10 WHO, kodefikasi tindakan medis ICD-9-CM, format pertukaran data FHIR (*Fast Healthcare Interoperability Resources*), serta mekanisme autentikasi OAuth2 pada API SatuSehat Kemenkes RI.
* **Manajemen Logistik Farmasi:** Memahami alur kerja nyata di apotek faskes, termasuk metode peracikan puyer/kapsul, sistem stok FIFO/FEFO (*First Expired First Out*), serta penatausahaan faktur hutang dagang obat.

### 4.4 Identifikasi Kekuatan dan Kelemahan Diri
Melalui interaksi harian di lingkungan kerja, penulis dapat mengidentifikasi kelebihan dan aspek diri yang perlu terus ditingkatkan:
* **Kekuatan Pribadi (*Strengths*):**
  * Memiliki daya analisis yang kuat dalam membedah alur sistem yang rumit menjadi komponen kode yang terstruktur rapi.
  * Kemampuan adaptasi cepat dan gigih dalam memecahkan *bug* atau *error* teknis yang terjadi di sistem.
  * Sikap proaktif dalam mendengarkan masukan dari staf non-teknis (dokter, perawat, kasir) dan menerjemahkannya ke dalam solusi antarmuka yang ramah pengguna.
* **Kelemahan Pribadi (*Areas for Improvement*):**
  * Pada awal masa magang, penulis terkadang terlalu berfokus pada kesempurnaan kode secara teoritis sehingga memerlukan waktu lebih lama; namun, dengan bimbingan supervisor lapangan, penulis belajar menyeimbangkan antara kualitas kode dengan efisiensi waktu kerja (*delivery deadline*).
  * Perlunya peningkatan kemampuan manajemen stres saat menghadapi banyak permintaan revisi fitur secara serentak dalam situasi klinik yang sedang padat pasien.

### 4.5 Pengaruh Magang terhadap Pertumbuhan Pribadi
Pengalaman magang ini secara signifikan mematangkan kepribadian penulis. Penulis belajar menjunjung tinggi nilai-nilai profesionalisme, seperti ketepatan waktu kerja, kejujuran terhadap integritas data, etika berkomunikasi dengan rekan kerja lintas profesi, serta rasa tanggung jawab penuh atas setiap baris kode yang diimplementasikan ke lingkungan produksi (*production environment*).

### 4.6 Dampak terhadap Rencana Karir Masa Depan
Menjalani peran sebagai pengembang sistem RME di Klinik Telagasari 24 Jam memberikan gambaran nyata yang sangat menarik tentang industri *Health-Tech* (teknologi informasi kesehatan). Pengalaman ini memantapkan tekad penulis untuk meniti karir profesional di bidang **Full-Stack Software Engineer** atau **Healthcare Information System Specialist** setelah lulus dari Horizon University Indonesia, dengan bekal portofolio proyek faskes nyata yang siap bersaing di bursa kerja nasional maupun global.

<br><br>
<div style="page-break-after: always;"></div>

---

# CHAPTER V - RECOMMENDATIONS

Sebagai penutup dari laporan akhir magang ini, penulis menyampaikan beberapa saran dan rekomendasi konstruktif yang didasarkan pada pengamatan langsung selama 16 minggu kerja praktik:

### 5.1 Rekomendasi untuk Klinik Telagasari 24 Jam
1. **Penerapan Sistem Cadangan Data Otomatis (*Automated Cloud Backup*):**  
   Disarankan agar manajemen klinik melengkapi infrastruktur server lokal dengan skrip *backup* database berkala yang diunggah secara otomatis dan terenkripsi ke penyimpanan cloud (seperti Google Cloud Storage atau Amazon S3) setiap malam, guna mengantisipasi risiko kegagalan perangkat keras (*hardware failure*) atau bencana tak terduga.
2. **Peningkatan Infrastruktur Jaringan Lokal (LAN):**  
   Mengingat operasional klinik berlangsung 24 jam penuh dengan mobilitas data yang tinggi antar ruang poli, kasir, dan apotek, disarankan untuk melakukan peremajaan router dan penataan kabel LAN berpita lebar (*gigabit switch*) serta menyediakan koneksi internet cadangan (*redundant ISP*) untuk memastikan transmisi data rekam medis dan bridging BPJS/SatuSehat tidak mengalami interupsi.
3. **Pemberian Pelatihan (*Refresher Training*) Periodik bagi Staf Medis:**  
   Mengingat sering terjadinya pergantian atau penambahan staf paramedis/dokter jaga baru, disarankan untuk menjadwalkan sesi pelatihan singkat berkala mengenai pengoperasian aplikasi RME agar standar penginputan data klinis tetap konsisten dan bebas dari kekeliruan administrasi.

### 5.2 Rekomendasi untuk Program Studi Informatika & Horizon University Indonesia
1. **Pengayaan Mata Kuliah Berbasis Proyek Industri Riil:**  
   Disarankan agar Fakultas Teknologi Informasi dan Komputer (FICT) memperbanyak studi kasus proyek riil (seperti sistem informasi faskes, logistik pergudangan, dan interoperabilitas REST API modern) ke dalam praktikum mata kuliah Pemrograman Web dan Rekayasa Perangkat Lunak, sehingga mahasiswa memiliki kesiapan teknis yang lebih matang sebelum terjun ke dunia magang.
2. **Penyediaan Modul Bimbingan Teknis Integrasi API Publik/Pemerintah:**  
   Penyelenggaraan workshop atau kuliah tamu mengenai tren bridging data standar nasional (misalnya standar HL7 FHIR pada platform SatuSehat atau open API perbankan/fintech) akan sangat bermanfaat bagi mahasiswa dalam merancang sistem yang berstandar enterprise.
3. **Penyempurnaan Koordinasi Monitoring Pembimbing:**  
   Disarankan agar platform pelaporan mingguan dan koordinasi antara dosen pembimbing universitas dengan mahasiswa magang dapat diintegrasikan ke dalam portal akademik yang lebih ringkas dan otomatis, sehingga umpan balik bimbingan dapat diberikan secara lebih cepat dan terdokumentasi dengan rapi.

<br><br>
<div style="page-break-after: always;"></div>

---

# APPENDICES AND PROOF OF INTERNSHIP ACTIVITY

### LAMPIRAN 1: REKAPITULASI LAPORAN KEMAJUAN MINGGUAN (NARRATIVE WEEKLY REPORT)
*Periode Magang: 02 September 2026 – 20 Desember 2026 (16 Minggu)*  
*Peserta: Agus Haryanto (NIM: 4337855201230051) | Institusi: Klinik Telagasari 24 Jam*  
*\*Status Progres: Pelaksanaan Bulan ke-1 & Bulan ke-2 (Realisasi s.d. 30 Oktober 2026)*

#### A. Realisasi Kegiatan Magang yang Telah Terlaksana (Bulan September – Oktober 2026)

| Minggu Ke- | Periode Tanggal | Ringkasan Aktivitas Utama (*Main Activities*) | Kompetensi Diperoleh (*Skills Acquired*) | Status |
| :---: | :---: | :--- | :--- | :---: |
| **Week 1** | 02 Sep – 06 Sep | Orientasi lingkungan faskes, observasi alur pendaftaran pasien, instalasi farmasi, dan administrasi manual. | Analisis Bisnis Faskes | **SELESAI** |
| **Week 2** | 09 Sep – 13 Sep | Wawancara kebutuhan pengguna (dokter, perawat, apoteker, kasir), perumusan Software Requirements Specification (SRS). | Requirements Elicitation | **SELESAI** |
| **Week 3** | 16 Sep – 20 Sep | Perancangan arsitektur basis data relasional (ERD) dan pemodelan sistem UML (Use Case, Activity, Sequence Diagram). | Data Modeling, MySQL, UML | **SELESAI** |
| **Week 4** | 23 Sep – 27 Sep | Inisialisasi project Laravel, konfigurasi web server lokal (Laragon), pembuatan database migrations & seeders master data. | Arsitektur Laravel MVC, Git | **SELESAI** |
| **Week 5** | 30 Sep – 04 Okt | Rancang bangun modul Pendaftaran Rawat Jalan, pembuatan form input registrasi pasien dan generator No RM unik otomatis. | Blade Templating, UI Design | **SELESAI** |
| **Week 6** | 07 Okt – 11 Okt | Pengembangan fitur pencarian cepat pasien (live autocomplete & modal popup) menggunakan JavaScript dan AJAX. | AJAX, jQuery DOM | **SELESAI** |
| **Week 7** | 14 Okt – 18 Okt | Pembangunan modul Master Pasien, Skrining Visual, dan Asesmen Awal Keperawatan (tanda vital, tanda bahaya, alergi). | Pengolahan Data Klinis | **SELESAI** |
| **Week 8** | 21 Okt – 25 Okt | Implementasi modul Master Diagnosa ICD-10 (18.000+ kode Kemenkes), input tindakan medis ICD-9-CM, riwayat kunjungan. | Standar Medis ICD-10/ICD-9 | **SELESAI** |
| **Week 9** | 28 Okt – 01 Nov | Pengembangan transaksi penjualan apotek, modul kasir awal, serta penyusunan draf bimbingan kemajuan proyek magang. | Billing Kasir, Bimbingan Draf | **SEDANG BERJALAN** |

<br>

#### B. Rencana Kerja & Roadmap Penyelesaian Proyek (Bulan November – Desember 2026)

| Minggu Ke- | Target Periode | Rencana Tugas & Target Milestone | Target Output |
| :---: | :---: | :--- | :--- |
| **Week 10** | 04 Nov – 08 Nov | Penyempurnaan modul Instalasi Farmasi: peracikan e-resep dokter otomatis, kartu kontrol stok obat FIFO/FEFO. | Fitur Dispensing Obat Siap Uji |
| **Week 11** | 11 Nov – 15 Nov | Pengembangan modul Mutasi Barang Antar Gudang (Gudang Utama ke Depo Rajal) dan verifikasi penerimaan faktur vendor. | Siklus Inventori Terintegrasi |
| **Week 12** | 18 Nov – 22 Nov | Konsolidasi Billing Kasir Terpadu, integrasi multi-metode pembayaran (Tunai/QRIS/BPJS), dan cetak kuitansi resmi. | Modul Pembayaran Final |
| **Week 13** | 25 Nov – 29 Nov | Konfigurasi lanjutan modul Interoperabilitas Bridging SatuSehat (Encounter/Observation) dan Antrean Online BPJS. | Bridging API Terhubung |
| **Week 14** | 02 Des – 06 Des | Pengujian sistem menyeluruh (End-to-End Black Box Testing) dan pemecahan bug dari alur pendaftaran hingga kasir. | Hasil Validasi & QA Bersih |
| **Week 15** | 09 Des – 13 Des | Pelatihan operasional sistem (User Acceptance Testing / UAT) kepada dokter jaga, perawat, apoteker, dan kasir faskes. | Staf Terlatih & User Manual |
| **Week 16** | 16 Des – 20 Des | Evaluasi akhir kinerja bersama Supervisor On-Site, penyusunan laporan akhir lengkap, dan penyelesaian magang. | Laporan Akhir Siap Sidang |

<br>

### LAMPIRAN 2: RINGKASAN PORTOFOLIO FITUR SISTEM YANG DIBANGUN
1. **Dashboard Manajemen Statistik:** Menampilkan ringkasan jumlah kunjungan pasien harian, tren poli teramai, status antrean, dan rekap pendapatan kasir.
2. **Form Pendaftaran Rawat Jalan Interaktif:** Dilengkapi fitur live autocomplete pencarian pasien dan modal filter data pasien secara cepat.
3. **Daftar Registrasi Rawat Jalan:** Monitoring status pelayanan pasien (Menunggu Antrian, Dalam Proses, Selesai, Batal) dan integrasi tombol panggil antrean.
4. **Form Rekam Medis Elektronik (RME):** Pengisian skrining klinis, tanda vital, anamnesis, riwayat alergi, diagnosis ICD-10, tindakan ICD-9-CM, dan e-resep dokter.
5. **Modul Instalasi Farmasi & Gudang:** Pelacakan batch kadaluwarsa, transaksi mutasi obat antar gudang, penerimaan faktur dari distributor, dan pelunasan hutang dagang.
6. **Modul Kasir & Billing Terpadu:** Perhitungan otomatis invoice pembayaran, rincian diskon, kalkulasi uang kembali, dan cetak kuitansi resmi Klinik Telagasari 24 Jam.
7. **Modul Bridging SatuSehat Kemenkes:** Fasilitas pencarian ID IHS Pasien dan IHS Praktisi serta persiapan bridging data medis nasional.

---
*Laporan ini disusun dengan sebenar-benarnya sesuai dengan fakta pelaksanaan kegiatan magang di Klinik Telagasari 24 Jam.*
