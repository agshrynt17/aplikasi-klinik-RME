<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BridgingSatuSehatDataController extends Controller
{
    // =====================================
    // 1. DATA PASIEN SATU SEHAT
    // =====================================
    public function pasien(Request $request)
    {
        $query = DB::table('pasiens')->orderBy('created_at', 'desc');
        
        if ($request->has('q') && $request->q != '') {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_pasien', 'like', "%{$q}%")
                  ->orWhere('no_rm', 'like', "%{$q}%")
                  ->orWhere('nik', 'like', "%{$q}%");
            });
        }

        $pasiens = $query->paginate(20);
        return view('bridging.satusehat_pasien', compact('pasiens'));
    }

    // Simulasi Pencarian IHS by NIK (untuk production, ini memanggil API Kemkes)
    public function cariIhsPasien(Request $request)
    {
        $nik = $request->nik;
        if (!$nik) {
            return response()->json(['success' => false, 'message' => 'NIK tidak valid.']);
        }

        // Simulasi network latency
        sleep(1);

        // Simulasi response sukses dari DTO Kemenkes
        // Dalam implementasi nyata: $response = Http::withToken(...)->get($baseUrl . '/Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik);
        $dummyIhs = 'P' . rand(1000000000, 9999999999);

        // Update database lokal
        DB::table('pasiens')->where('nik', $nik)->update([
            'kode_ihs_satusehat' => $dummyIhs
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan IHS ID (Simulasi)',
            'ihs' => $dummyIhs
        ]);
    }

    // =====================================
    // 2. DATA KUNJUNGAN SATU SEHAT
    // =====================================
    public function kunjungan(Request $request)
    {
        $query = DB::table('pendaftarans')
                    ->join('pasiens', 'pendaftarans.pasien_id', '=', 'pasiens.id')
                    ->join('pegawais', 'pendaftarans.dokter_id', '=', 'pegawais.id')
                    ->join('unit_layanans', 'pendaftarans.poliklinik_id', '=', 'unit_layanans.id')
                    ->select(
                        'pendaftarans.*', 
                        'pasiens.nama_pasien', 
                        'pasiens.no_rm', 
                        'pegawais.nama_pegawai as nama_dokter',
                        'unit_layanans.nama_unit'
                    )
                    ->orderBy('pendaftarans.created_at', 'desc');

        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('pendaftarans.tanggal_daftar', $request->tanggal);
        } else {
            // Default hari ini
            $query->whereDate('pendaftarans.tanggal_daftar', date('Y-m-d'));
        }

        $kunjungans = $query->paginate(30);
        return view('bridging.satusehat_kunjungan', compact('kunjungans'));
    }

    // Simulasi Pengiriman Kunjungan (Encounter) ke Satu Sehat
    public function kirimKunjungan(Request $request)
    {
        $id = $request->id;
        $kunjungan = DB::table('pendaftarans')->where('id', $id)->first();

        if (!$kunjungan) {
            return response()->json(['success' => false, 'message' => 'Data pendaftaran tidak ditemukan.']);
        }

        // Simulasi network latency
        sleep(1);

        // Simulasi response sukses pengiriman Encounter
        $dummyEncounterId = 'ENC' . rand(1000000, 9999999) . '-' . time();

        DB::table('pendaftarans')->where('id', $id)->update([
            'kode_ihs_satusehat' => $dummyEncounterId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kunjungan berhasil dikirim ke Satu Sehat (Simulasi).',
            'encounter_id' => $dummyEncounterId
        ]);
    }
}
