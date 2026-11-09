<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\GeneralConsent;
use App\Models\Poliklinik;
use App\Models\Penjamin;
use Database\Seeders\PendaftaranProfaskesSeeder;
use Illuminate\Http\Request;

class GeneralConsentController extends Controller
{
    // Menampilkan daftar kunjungan
    public function index(Request $request)
    {
        // Pastikan ada data pendaftaran jika kosong
        if (Pendaftaran::count() === 0) {
            try {
                (new PendaftaranProfaskesSeeder())->run();
            } catch (\Throwable $e) {}
        }

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'generalConsent'])
                    ->where('status_periksa', '!=', 'Batal');

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_daftar', $request->tanggal);
        }

        // Filter Pencarian
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('no_rawat', 'like', "%{$search}%")
                  ->orWhereHas('pasien', function($qp) use ($search) {
                      $qp->where('nama_pasien', 'like', "%{$search}%")
                         ->orWhere('no_rm', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Poliklinik
        if ($request->filled('poliklinik_id')) {
            $query->where('poliklinik_id', $request->poliklinik_id);
        }

        // Filter Penjamin
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter Status Consent
        if ($request->filled('status_consent')) {
            if ($request->status_consent === 'sudah') {
                $query->whereHas('generalConsent');
            } elseif ($request->status_consent === 'belum') {
                $query->whereDoesntHave('generalConsent');
            }
        }

        $data = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $total = $data->total();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins   = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();
        $tanggal     = $request->tanggal;

        return view('front_office.general_consent_index', compact('data', 'total', 'polikliniks', 'penjamins', 'tanggal'));
    }

    // Export data to CSV
    public function export(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'generalConsent'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_daftar', $request->tanggal);
        }
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('no_rawat', 'like', "%{$search}%")
                  ->orWhereHas('pasien', function($qp) use ($search) {
                      $qp->where('nama_pasien', 'like', "%{$search}%")
                         ->orWhere('no_rm', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('poliklinik_id')) {
            $query->where('poliklinik_id', $request->poliklinik_id);
        }
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        $records = $query->orderBy('id', 'desc')->get();

        $filename = 'general_consent_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'No', 'Status Consent', 'No. Registrasi', 'Tanggal', 'Status Periksa',
                'No. Rekam Medis', 'Jenis Kunjungan', 'Nama Pasien', 'Jenis Kelamin',
                'Tgl Lahir', 'Penjamin', 'Poliklinik', 'Dokter', 'Perawat',
                'Nama PJ', 'Hubungan PJ'
            ]);

            foreach ($records as $idx => $r) {
                $hasConsent = $r->generalConsent ? 'Sudah Persetujuan' : 'Belum Persetujuan';
                fputcsv($file, [
                    $idx + 1,
                    $hasConsent,
                    $r->nomor_registrasi ?: $r->no_rawat,
                    $r->tanggal_daftar ? $r->tanggal_daftar->format('Y-m-d') : '',
                    $r->status_periksa,
                    $r->pasien->no_rm ?? '-',
                    $r->jenis_kunjungan ?? 'Kunjungan Sakit',
                    $r->pasien->nama_pasien ?? '-',
                    ($r->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki',
                    $r->pasien && $r->pasien->tanggal_lahir ? $r->pasien->tanggal_lahir->format('Y-m-d') : '-',
                    $r->penjamin->nama_penjamin ?? 'Pribadi',
                    $r->poliklinik->nama_poli ?? '-',
                    $r->dokter->nama_pegawai ?? '-',
                    $r->perawat->nama_pegawai ?? ($r->nama_perawat ?: '-'),
                    $r->generalConsent->nama_pj ?? '-',
                    $r->generalConsent->hubungan_pj ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Menampilkan form General Consent untuk spesifik kunjungan
    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with('pasien')->findOrFail($pendaftaran_id);
        
        // Cek apakah sudah ada datanya
        $consent = GeneralConsent::where('pendaftaran_id', $pendaftaran_id)->first();

        return view('front_office.general_consent_form', compact('pendaftaran', 'consent'));
    }

    // Menyimpan / Mengupdate General Consent
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'nama_pj'        => 'required|max:100',
            'hubungan_pj'    => 'required|max:50',
            'no_hp_pj'       => 'nullable|max:20',
        ]);

        $consent = GeneralConsent::updateOrCreate(
            ['pendaftaran_id' => $request->pendaftaran_id],
            [
                'nama_pj'     => $request->nama_pj,
                'hubungan_pj' => $request->hubungan_pj,
                'no_hp_pj'    => $request->no_hp_pj,
                'alamat_pj'   => $request->alamat_pj,
                'ttd_pj'      => $request->ttd_pj,
                'ttd_petugas' => $request->ttd_petugas,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'General Consent berhasil disimpan!',
            'data'    => $consent,
        ]);
    }
}
