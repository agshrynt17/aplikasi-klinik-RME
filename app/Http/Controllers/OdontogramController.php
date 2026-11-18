<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Odontogram;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class OdontogramController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default tanggal 2026-10-14 sesuai konteks KONHAKES
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

        // Get status odontogram per pendaftaran
        $odontograms = Odontogram::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->select('pendaftaran_id', DB::raw('count(*) as total'))
            ->groupBy('pendaftaran_id')
            ->get()
            ->keyBy('pendaftaran_id');

        return view('rekam_medis.odontogram.index', compact('pendaftarans', 'odontograms', 'tanggal', 'totalCount'));
    }

    public function create(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/odon/show/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/odon/show/' . $request->pendaftaran_id);
        }

        return redirect('/odon/index');
    }

    public function exportXls(Request $request)
    {
        return response("Data Odontogram Export XLS", 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="odontogram_' . ($request->tanggal ?? date('Y-m-d')) . '.xls"');
    }

    public function exportPdf(Request $request)
    {
        return response("Data Odontogram Export PDF", 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="odontogram_' . ($request->tanggal ?? date('Y-m-d')) . '.pdf"');
    }

    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        // Load existing gigi records for this session
        $records = Odontogram::where('pendaftaran_id', $pendaftaran_id)->get()->keyBy('nomor_gigi');
        
        return view('rekam_medis.odontogram.show', compact('pendaftaran', 'records'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'nomor_gigi'     => 'required',
            'kondisi'        => 'required',
        ]);

        try {
            // Check if tooth already has a record in this registration, if yes, update it
            $odontogram = Odontogram::where('pendaftaran_id', $request->pendaftaran_id)
                                    ->where('nomor_gigi', $request->nomor_gigi)
                                    ->first();

            if ($odontogram) {
                if ($request->kondisi == 'Normal' && empty($request->keterangan)) {
                    // Jika dikembalikan ke Normal dan tanpa ket, bisa dihapus agar db bersih
                    $odontogram->delete();
                } else {
                    $odontogram->update([
                        'kondisi'    => $request->kondisi,
                        'keterangan' => $request->keterangan,
                        'dokter_id'  => $request->dokter_id
                    ]);
                }
            } else {
                if ($request->kondisi != 'Normal') { // Hanya simpan jika kondisi tidak normal
                    Odontogram::create([
                        'pendaftaran_id' => $request->pendaftaran_id,
                        'pasien_id'      => $request->pasien_id,
                        'dokter_id'      => $request->dokter_id,
                        'nomor_gigi'     => $request->nomor_gigi,
                        'kondisi'        => $request->kondisi,
                        'keterangan'     => $request->keterangan,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Status gigi ' . $request->nomor_gigi . ' berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}

