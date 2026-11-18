<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\InformedConsent;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class InformedConsentController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default tanggal 2026-10-14 sesuai context KONHAKES
            $hasDate = Pendaftaran::whereDate('tanggal_daftar', '2026-10-14')->exists();
            if ($hasDate) {
                $tanggal = '2026-10-14';
                $query->whereDate('tanggal_daftar', '2026-10-14');
            } else {
                $tanggal = date('Y-m-d');
                $query->whereDate('tanggal_daftar', $tanggal);
            }
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function($q) use ($term) {
                $q->where('nomor_registrasi', 'like', "%{$term}%")
                  ->orWhere('no_rawat', 'like', "%{$term}%")
                  ->orWhereHas('pasien', function($qp) use ($term) {
                      $qp->where('nama_pasien', 'like', "%{$term}%")
                         ->orWhere('no_rm', 'like', "%{$term}%");
                  });
            });
        }

        $query->orderBy('nomor_registrasi', 'desc');

        $pendaftarans = $query->paginate(20)->withQueryString();
        $totalCount = $pendaftarans->total();

        // Get jumlah consent per pendaftaran (bisa lebih dari satu tindakan)
        $consents = InformedConsent::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->select('pendaftaran_id', DB::raw('count(*) as total'))
            ->groupBy('pendaftaran_id')
            ->get()
            ->keyBy('pendaftaran_id');

        return view('rekam_medis.informed_consent.index', compact('pendaftarans', 'consents', 'tanggal', 'totalCount'));
    }

    public function create(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
        } elseif ($request->filled('pendaftaran_id')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->findOrFail($request->pendaftaran_id);
        } else {
            return redirect('/consent/index');
        }

        return redirect('/consent/show/' . $pendaftaran->id);
    }

    public function exportXls(Request $request)
    {
        return response("Data Informed Consent Export XLS", 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="informed_consent_' . ($request->tanggal ?? date('Y-m-d')) . '.xls"');
    }

    public function exportPdf(Request $request)
    {
        return response("Data Informed Consent Export PDF", 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="informed_consent_' . ($request->tanggal ?? date('Y-m-d')) . '.pdf"');
    }

    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        $consents = InformedConsent::with('dokter')->where('pendaftaran_id', $pendaftaran_id)->orderBy('created_at', 'desc')->get();
        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();
        
        return view('rekam_medis.informed_consent.show', compact('pendaftaran', 'consents', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'jenis_tindakan' => 'required',
            'status_persetujuan' => 'required',
            'pihak_menyatakan' => 'required'
        ]);

        try {
            InformedConsent::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'dokter_id'         => $request->dokter_id,
                'jenis_tindakan'    => $request->jenis_tindakan,
                'tanggal_persetujuan' => now(),
                'status_persetujuan'=> $request->status_persetujuan,
                'pihak_menyatakan'  => $request->pihak_menyatakan,
                'nama_wali'         => $request->nama_wali,
                'hubungan_wali'     => $request->hubungan_wali,
                'keterangan'        => $request->keterangan,
                'saksi_keluarga'    => $request->saksi_keluarga,
                'saksi_rs'          => $request->saksi_rs,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Informed Consent berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $consent = InformedConsent::findOrFail($id);
        return response()->json($consent);
    }

    public function update(Request $request, $id)
    {
        try {
            $consent = InformedConsent::findOrFail($id);
            $consent->update([
                'dokter_id'         => $request->dokter_id,
                'jenis_tindakan'    => $request->jenis_tindakan,
                'status_persetujuan'=> $request->status_persetujuan,
                'pihak_menyatakan'  => $request->pihak_menyatakan,
                'nama_wali'         => $request->nama_wali,
                'hubungan_wali'     => $request->hubungan_wali,
                'keterangan'        => $request->keterangan,
                'saksi_keluarga'    => $request->saksi_keluarga,
                'saksi_rs'          => $request->saksi_rs,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Informed Consent berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            InformedConsent::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data informed consent berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
