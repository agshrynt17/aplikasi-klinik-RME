<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poliklinik;
use App\Models\Pegawai;
use App\Models\Pasien;
use App\Models\Penjamin;
use App\Models\Pendaftaran;
use App\Models\MasterRadiologi;
use App\Models\TransaksiRadiologi;
use Carbon\Carbon;

class TransaksiRadiologiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Poliklinik
        $poliUmum = Poliklinik::firstOrCreate(
            ['kode_poli' => 'POLI-UMUM'],
            ['nama_poli' => 'Poli Umum', 'keterangan' => 'Pelayanan Umum', 'is_active' => true]
        );
        $poliIgd = Poliklinik::firstOrCreate(
            ['kode_poli' => 'IGD-24'],
            ['nama_poli' => 'IGD 24 Jam', 'keterangan' => 'Instalasi Gawat Darurat', 'is_active' => true]
        );
        $poliGigi = Poliklinik::firstOrCreate(
            ['kode_poli' => 'POLI-GIGI'],
            ['nama_poli' => 'Poli Gigi & Mulut', 'keterangan' => 'Pelayanan Gigi', 'is_active' => true]
        );
        $poliAnak = Poliklinik::firstOrCreate(
            ['kode_poli' => 'POLI-ANAK'],
            ['nama_poli' => 'Poli Anak', 'keterangan' => 'Pelayanan Spesialis Anak', 'is_active' => true]
        );

        // Update dummy poli if exists
        $dummy = Poliklinik::find(1);
        if ($dummy && $dummy->kode_poli === 'DSED') {
            $dummy->update(['kode_poli' => 'POLI-INT', 'nama_poli' => 'Poli Penyakit Dalam', 'keterangan' => 'Spesialis Interna', 'is_active' => true]);
        }

        // 2. Pegawai (Dokter & Radiografer)
        $drBambang = Pegawai::firstOrCreate(
            ['kode_pegawai' => 'DOK-001'],
            [
                'nik' => '3201011001850001',
                'nama_pegawai' => 'dr. Bambang Hermanto, Sp.Rad',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Karawang',
                'tanggal_lahir' => '1980-05-12',
                'jabatan' => 'Dokter Spesialis Radiologi',
                'bagian' => 'Radiologi',
                'no_telepon' => '08123456701',
                'is_active' => true,
            ]
        );

        $drAnita = Pegawai::firstOrCreate(
            ['kode_pegawai' => 'DOK-002'],
            [
                'nik' => '3201011001870002',
                'nama_pegawai' => 'dr. Anita Wijaya',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1987-08-20',
                'jabatan' => 'Dokter Umum',
                'bagian' => 'Pelayanan Medis',
                'no_telepon' => '08123456702',
                'is_active' => true,
            ]
        );

        $drHendra = Pegawai::firstOrCreate(
            ['kode_pegawai' => 'DOK-003'],
            [
                'nik' => '3201011001890003',
                'nama_pegawai' => 'dr. Hendra Kurniawan',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1989-11-15',
                'jabatan' => 'Dokter Jaga IGD',
                'bagian' => 'IGD',
                'no_telepon' => '08123456703',
                'is_active' => true,
            ]
        );

        $radAhmad = Pegawai::firstOrCreate(
            ['kode_pegawai' => 'RAD-001'],
            [
                'nik' => '3201011001920004',
                'nama_pegawai' => 'Ahmad Sudirman, A.Md.Rad',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Karawang',
                'tanggal_lahir' => '1992-03-10',
                'jabatan' => 'Radiografer Pelaksana',
                'bagian' => 'Radiologi',
                'no_telepon' => '08123456704',
                'is_active' => true,
            ]
        );

        $radSiti = Pegawai::firstOrCreate(
            ['kode_pegawai' => 'RAD-002'],
            [
                'nik' => '3201011001950005',
                'nama_pegawai' => 'Siti Rahmawati, A.Md.Rad',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bekasi',
                'tanggal_lahir' => '1995-07-25',
                'jabatan' => 'Radiografer',
                'bagian' => 'Radiologi',
                'no_telepon' => '08123456705',
                'is_active' => true,
            ]
        );

        // 3. Pasien
        $p1 = Pasien::firstOrCreate(
            ['no_rm' => 'RM-00101'],
            [
                'nik' => '3215011201880001',
                'nama_pasien' => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Karawang',
                'tanggal_lahir' => '1988-01-12',
                'alamat' => 'Jl. Raya Telagasari No. 12',
                'no_hp' => '085711223344',
                'is_active' => true,
            ]
        );

        $p2 = Pasien::firstOrCreate(
            ['no_rm' => 'RM-00102'],
            [
                'nik' => '3215015504950002',
                'nama_pasien' => 'Sri Wahyuni',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Subang',
                'tanggal_lahir' => '1995-04-15',
                'alamat' => 'Dusun Krajan RT 02/01 Telagasari',
                'no_hp' => '085722334455',
                'is_active' => true,
            ]
        );

        $p3 = Pasien::firstOrCreate(
            ['no_rm' => 'RM-00103'],
            [
                'nik' => '3215012006700003',
                'nama_pasien' => 'Joko Widodo',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Solo',
                'tanggal_lahir' => '1970-06-20',
                'alamat' => 'Perum Telagasari Permai Blok C4',
                'no_hp' => '085733445566',
                'is_active' => true,
            ]
        );

        $p4 = Pasien::firstOrCreate(
            ['no_rm' => 'RM-00104'],
            [
                'nik' => '3215016209820004',
                'nama_pasien' => 'Rina Agustina',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Karawang',
                'tanggal_lahir' => '1982-09-22',
                'alamat' => 'Jl. Pasirkamuning No. 45',
                'no_hp' => '085744556677',
                'is_active' => true,
            ]
        );

        $p5 = Pasien::firstOrCreate(
            ['no_rm' => 'RM-00105'],
            [
                'nik' => '3215011803050005',
                'nama_pasien' => 'Muhammad Rizky',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Purwakarta',
                'tanggal_lahir' => '2005-03-18',
                'alamat' => 'Kampung Baru RT 04/02 Telagasari',
                'no_hp' => '085755667788',
                'is_active' => true,
            ]
        );

        // Penjamins
        $penjUmum = Penjamin::where('nama_penjamin', 'LIKE', '%UMUM%')->first() ?? Penjamin::find(1);
        $penjBpjs = Penjamin::where('nama_penjamin', 'LIKE', '%BPJS%')->first() ?? Penjamin::find(2);
        $penjAsuransi = Penjamin::where('nama_penjamin', 'LIKE', '%Asuransi%')->first() ?? Penjamin::find(3);

        $today = date('Y-m-d');
        $prefix = date('Y/m/d');

        // 4. Pendaftarans
        $reg1 = Pendaftaran::firstOrCreate(
            ['no_rawat' => "{$prefix}/000001"],
            [
                'pasien_id' => $p1->id,
                'tanggal_daftar' => $today,
                'jam_daftar' => '08:15:00',
                'poliklinik_id' => $poliUmum->id,
                'dokter_id' => $drAnita->id,
                'penjamin_id' => $penjUmum->id,
                'status_periksa' => 'Sedang Diperiksa',
                'keluhan' => 'Nyeri dada sebelah kanan setelah terjatuh dari tangga',
            ]
        );

        $reg2 = Pendaftaran::firstOrCreate(
            ['no_rawat' => "{$prefix}/000002"],
            [
                'pasien_id' => $p2->id,
                'tanggal_daftar' => $today,
                'jam_daftar' => '08:45:00',
                'poliklinik_id' => $poliIgd->id,
                'dokter_id' => $drHendra->id,
                'penjamin_id' => $penjBpjs->id,
                'status_periksa' => 'Sedang Diperiksa',
                'keluhan' => 'Nyeri perut bawah kuadran kanan akut',
            ]
        );

        $reg3 = Pendaftaran::firstOrCreate(
            ['no_rawat' => "{$prefix}/000003"],
            [
                'pasien_id' => $p3->id,
                'tanggal_daftar' => $today,
                'jam_daftar' => '09:20:00',
                'poliklinik_id' => $poliUmum->id,
                'dokter_id' => $drAnita->id,
                'penjamin_id' => $penjAsuransi->id,
                'status_periksa' => 'Belum Diperiksa',
                'keluhan' => 'Batuk kronis berdahak lebih dari 2 minggu',
            ]
        );

        $reg4 = Pendaftaran::firstOrCreate(
            ['no_rawat' => "{$prefix}/000004"],
            [
                'pasien_id' => $p4->id,
                'tanggal_daftar' => $today,
                'jam_daftar' => '09:50:00',
                'poliklinik_id' => $poliGigi->id,
                'dokter_id' => $drHendra->id,
                'penjamin_id' => $penjBpjs->id,
                'status_periksa' => 'Belum Diperiksa',
                'keluhan' => 'Gigi geraham impaksi sakit menjalar ke telinga',
            ]
        );

        $reg5 = Pendaftaran::firstOrCreate(
            ['no_rawat' => "{$prefix}/000005"],
            [
                'pasien_id' => $p5->id,
                'tanggal_daftar' => $today,
                'jam_daftar' => '10:15:00',
                'poliklinik_id' => $poliIgd->id,
                'dokter_id' => $drHendra->id,
                'penjamin_id' => $penjUmum->id,
                'status_periksa' => 'Sedang Diperiksa',
                'keluhan' => 'Kecelakaan motor, nyeri pergelangan tangan kiri',
            ]
        );

        // 5. Transaksi Radiologi Sample
        $radThorax = MasterRadiologi::where('kode_rad', 'RAD-001')->first() ?? MasterRadiologi::first();
        $radUsg    = MasterRadiologi::where('kode_rad', 'RAD-004')->first() ?? MasterRadiologi::skip(1)->first();

        if ($radThorax && TransaksiRadiologi::where('pendaftaran_id', $reg1->id)->count() === 0) {
            TransaksiRadiologi::create([
                'pendaftaran_id' => $reg1->id,
                'master_radiologi_id' => $radThorax->id,
                'radiografer_id' => $radAhmad->id,
                'subtotal' => 150000,
                'status' => 'Proses',
                'keterangan' => 'Suspect fraktur Costa VI-VII dekstra post trauma',
            ]);
        }

        if ($radUsg && TransaksiRadiologi::where('pendaftaran_id', $reg2->id)->count() === 0) {
            TransaksiRadiologi::create([
                'pendaftaran_id' => $reg2->id,
                'master_radiologi_id' => $radUsg->id,
                'radiografer_id' => $radSiti->id,
                'subtotal' => 200000,
                'status' => 'Pending',
                'keterangan' => 'Suspect Appendicitis akut',
            ]);
        }
    }
}
