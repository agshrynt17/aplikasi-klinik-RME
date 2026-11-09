<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MonitoringBedah;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class MonitoringBedahController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default tanggal 2026-10-14 sesuai konteks PROFASKES
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

        $monitorings = MonitoringBedah::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.monitoring_bedah.index', compact('pendaftarans', 'monitorings', 'tanggal', 'totalCount'));
    }

    public function createMonitoring(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/monitoring/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/monitoring/create/' . $request->pendaftaran_id);
        }

        return redirect('/monitoring/index');
    }

    public function exportXls(Request $request)
    {
        return response("Data Monitoring Durante Bedah Export XLS", 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="monitoring_durante_bedah_' . ($request->tanggal ?? date('Y-m-d')) . '.xls"');
    }

    public function exportPdf(Request $request)
    {
        return response("Data Monitoring Durante Bedah Export PDF", 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="monitoring_durante_bedah_' . ($request->tanggal ?? date('Y-m-d')) . '.pdf"');
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $monitoring = MonitoringBedah::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($monitoring) {
            return redirect('/monitoring/' . $monitoring->id . '/edit');
        }

        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();

        return view('rekam_medis.monitoring_bedah.create', compact('pendaftaran', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            // Encode the observation array to JSON
            $observasi = [];
            if ($request->waktu) {
                for ($i = 0; $i < count($request->waktu); $i++) {
                    if (!empty($request->waktu[$i])) {
                        $observasi[] = [
                            'waktu' => $request->waktu[$i],
                            'tensi' => $request->tensi[$i] ?? '',
                            'nadi'  => $request->nadi[$i] ?? '',
                            'suhu'  => $request->suhu[$i] ?? '',
                            'rr'    => $request->rr[$i] ?? '',
                            'spo2'  => $request->spo2[$i] ?? '',
                        ];
                    }
                }
            }

            MonitoringBedah::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'dokter_id'         => $request->dokter_id,
                'waktu_mulai'       => $request->waktu_mulai,
                'waktu_selesai'     => $request->waktu_selesai,
                'cairan_masuk'      => $request->cairan_masuk,
                'cairan_keluar'     => $request->cairan_keluar,
                'observasi_json'    => $observasi,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/monitoring/index')->with('success', 'Monitoring Durante Bedah berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $monitoring = MonitoringBedah::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $monitoring->pendaftaran;
        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();
        
        return view('rekam_medis.monitoring_bedah.edit', compact('monitoring', 'pendaftaran', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        try {
            $monitoring = MonitoringBedah::findOrFail($id);

            // Encode the observation array to JSON
            $observasi = [];
            if ($request->waktu) {
                for ($i = 0; $i < count($request->waktu); $i++) {
                    if (!empty($request->waktu[$i])) {
                        $observasi[] = [
                            'waktu' => $request->waktu[$i],
                            'tensi' => $request->tensi[$i] ?? '',
                            'nadi'  => $request->nadi[$i] ?? '',
                            'suhu'  => $request->suhu[$i] ?? '',
                            'rr'    => $request->rr[$i] ?? '',
                            'spo2'  => $request->spo2[$i] ?? '',
                        ];
                    }
                }
            }

            $monitoring->update([
                'dokter_id'         => $request->dokter_id,
                'waktu_mulai'       => $request->waktu_mulai,
                'waktu_selesai'     => $request->waktu_selesai,
                'cairan_masuk'      => $request->cairan_masuk,
                'cairan_keluar'     => $request->cairan_keluar,
                'observasi_json'    => $observasi,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/monitoring/index')->with('success', 'Monitoring Durante Bedah berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            MonitoringBedah::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Monitoring Bedah berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
