<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ohis;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class OhisController extends Controller
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

        $ohis = Ohis::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.ohis.index', compact('pendaftarans', 'ohis', 'tanggal', 'totalCount'));
    }

    public function createOhis(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/ohis/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/ohis/create/' . $request->pendaftaran_id);
        }

        return redirect('/ohis/index');
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $ohi = Ohis::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($ohi) {
            return redirect('/ohis/' . $ohi->id . '/edit');
        }

        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();

        return view('rekam_medis.ohis.create', compact('pendaftaran', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            $gigi = ['16', '11', '26', '36', '31', '46'];
            $skor_gigi = [];
            $total_di = 0;
            $total_ci = 0;
            $count = 0;
            
            foreach ($gigi as $g) {
                if (isset($request->di[$g]) && isset($request->ci[$g])) {
                    $di = (float)$request->di[$g];
                    $ci = (float)$request->ci[$g];
                    
                    $skor_gigi[$g] = [
                        'di' => $di,
                        'ci' => $ci
                    ];
                    
                    $total_di += $di;
                    $total_ci += $ci;
                    $count++;
                }
            }
            
            $di_s = $count > 0 ? $total_di / $count : 0;
            $ci_s = $count > 0 ? $total_ci / $count : 0;
            $ohi_s = $di_s + $ci_s;
            
            $kriteria = 'Baik';
            if ($ohi_s > 1.2 && $ohi_s <= 3.0) $kriteria = 'Sedang';
            elseif ($ohi_s > 3.0) $kriteria = 'Buruk';

            Ohis::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'dokter_id'         => $request->dokter_id,
                'skor_gigi_json'    => $skor_gigi,
                'di_s'              => $di_s,
                'ci_s'              => $ci_s,
                'ohi_s'             => $ohi_s,
                'kriteria'          => $kriteria,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/ohis/index')->with('success', 'Data OHI-S berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $ohi = Ohis::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $ohi->pendaftaran;
        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();
        
        return view('rekam_medis.ohis.edit', compact('ohi', 'pendaftaran', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        try {
            $ohi = Ohis::findOrFail($id);
            
            $gigi = ['16', '11', '26', '36', '31', '46'];
            $skor_gigi = [];
            $total_di = 0;
            $total_ci = 0;
            $count = 0;
            
            foreach ($gigi as $g) {
                if (isset($request->di[$g]) && isset($request->ci[$g])) {
                    $di = (float)$request->di[$g];
                    $ci = (float)$request->ci[$g];
                    
                    $skor_gigi[$g] = [
                        'di' => $di,
                        'ci' => $ci
                    ];
                    
                    $total_di += $di;
                    $total_ci += $ci;
                    $count++;
                }
            }
            
            $di_s = $count > 0 ? $total_di / $count : 0;
            $ci_s = $count > 0 ? $total_ci / $count : 0;
            $ohi_s = $di_s + $ci_s;
            
            $kriteria = 'Baik';
            if ($ohi_s > 1.2 && $ohi_s <= 3.0) $kriteria = 'Sedang';
            elseif ($ohi_s > 3.0) $kriteria = 'Buruk';

            $ohi->update([
                'dokter_id'         => $request->dokter_id,
                'skor_gigi_json'    => $skor_gigi,
                'di_s'              => $di_s,
                'ci_s'              => $ci_s,
                'ohi_s'             => $ohi_s,
                'kriteria'          => $kriteria,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/ohis/index')->with('success', 'Data OHI-S berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Ohis::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data OHI-S berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
