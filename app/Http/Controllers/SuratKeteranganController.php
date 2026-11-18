<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SuratKeterangan;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuratKeteranganController extends Controller
{
    public function index(Request $request)
    {
        return redirect('/skb/index');
    }

    // 1. Surat Keterangan Berobat (SKB)
    public function skb(Request $request)
    {
        return $this->renderSuratList($request, 'SKB', 'Surat Keterangan Berobat', 'Surat Keterangan Berobat', '/skb');
    }

    // 2. Surat Keterangan Dokter (SKD)
    public function skd(Request $request)
    {
        return $this->renderSuratList($request, 'SKD', 'Surat Keterangan Dokter', 'Surat Keterangan Dokter', '/skd');
    }

    // 3. Surat Keterangan Sehat (SKS)
    public function sks(Request $request)
    {
        return $this->renderSuratList($request, 'SKS', 'Surat Keterangan Sehat', 'Surat Keterangan Sehat', '/sks');
    }

    // 4. Surat Rujukan Eksternal (SRE)
    public function sre(Request $request)
    {
        return $this->renderSuratList($request, 'SRE', 'Surat Rujukan Eksternal', 'Surat Rujukan Eksternal', '/sre');
    }

    // 5. Surat Keterangan Bebas Narkoba (SKBN)
    public function skbn(Request $request)
    {
        return $this->renderSuratList($request, 'SKBN', 'Surat Keterangan Bebas Narkoba', 'Surat Keterangan Bebas Narkoba', '/skbn');
    }

    // 6. Surat Keterangan Kematian (SKM)
    public function skm(Request $request)
    {
        return $this->renderSuratList($request, 'SKM', 'Surat Keterangan Kematian', 'Surat Keterangan Kematian', '/skm');
    }

    // 7. Surat Keterangan Hamil (SKH)
    public function skh(Request $request)
    {
        return $this->renderSuratList($request, 'SKH', 'Surat Keterangan Hamil', 'Surat Keterangan Hamil', '/skh');
    }

    // Shared list query
    private function renderSuratList(Request $request, string $type, string $title, string $breadcrumb, string $baseUrl)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($type == 'SKM') {
            $query->where(function($q) {
                $q->where('status_pulang', 'Meninggal')
                  ->orWhere('status_pulang', 'Mati')
                  ->orWhereHas('suratKeterangans', function($sq) {
                      $sq->where('jenis_surat', 'SKM');
                  });
            });
        } elseif ($type == 'SKH') {
            $query->whereHas('pasien', function($qp) {
                $qp->whereIn('jenis_kelamin', ['P', 'Perempuan', 'Wanita']);
            });
        }

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

        $suratKeterangans = SuratKeterangan::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->where('jenis_surat', $type)
            ->get()
            ->keyBy('pendaftaran_id');

        return view('rekam_medis.surat_keterangan.index', compact(
            'pendaftarans',
            'suratKeterangans',
            'tanggal',
            'totalCount',
            'type',
            'title',
            'breadcrumb',
            'baseUrl'
        ));
    }

    // Create forms
    public function createSkb($pendaftaran_id)
    {
        return $this->createForm($pendaftaran_id, 'SKB', 'Buat Surat Keterangan Berobat', '/skb/index');
    }

    public function createSkd($pendaftaran_id)
    {
        return $this->createForm($pendaftaran_id, 'SKD', 'Buat Surat Keterangan Dokter', '/skd/index');
    }

    public function createSks($pendaftaran_id)
    {
        return $this->createForm($pendaftaran_id, 'SKS', 'Buat Surat Keterangan Sehat', '/sks/index');
    }

    public function createSre($pendaftaran_id)
    {
        return $this->createForm($pendaftaran_id, 'SRE', 'Buat Surat Rujukan Eksternal', '/sre/index');
    }

    public function createSkbn($pendaftaran_id)
    {
        return $this->createForm($pendaftaran_id, 'SKBN', 'Buat Surat Keterangan Bebas Narkoba', '/skbn/index');
    }

    public function createSkm($pendaftaran_id)
    {
        return $this->createForm($pendaftaran_id, 'SKM', 'Buat Surat Keterangan Kematian', '/skm/index');
    }

    public function createSkh($pendaftaran_id)
    {
        return $this->createForm($pendaftaran_id, 'SKH', 'Buat Surat Keterangan Hamil', '/skh/index');
    }

    private function createForm($pendaftaran_id, string $type, string $title, string $backUrl)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        $dokters = Pegawai::where('fungsional_medis', 'like', '%Dokter%')
            ->orWhere('jabatan', 'like', '%Dokter%')
            ->get();
        if ($dokters->isEmpty()) {
            $dokters = Pegawai::all();
        }

        return view('rekam_medis.surat_keterangan.create', compact('pendaftaran', 'dokters', 'type', 'title', 'backUrl'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'jenis_surat'    => 'required',
        ]);

        try {
            $type = strtoupper($request->jenis_surat);
            $nomorSurat = $request->nomor_surat;
            
            // Auto generate nomor surat if blank
            if (empty($nomorSurat)) {
                $count = SuratKeterangan::where('jenis_surat', $type)->count() + 1;
                $nomorSurat = sprintf('%03d/%s/%s/%s', $count, $type, date('m'), date('Y'));
            }

            SuratKeterangan::create([
                'pendaftaran_id' => $request->pendaftaran_id,
                'pasien_id'      => $request->pasien_id,
                'dokter_id'      => $request->dokter_id,
                'jenis_surat'    => $type,
                'nomor_surat'    => $nomorSurat,
                'tanggal_surat'  => $request->tanggal_surat ?? date('Y-m-d'),
                'mulai_tanggal'  => $request->mulai_tanggal,
                'sampai_tanggal' => $request->sampai_tanggal,
                'keterangan'     => $request->keterangan,
            ]);

            $redirectUrl = '/' . strtolower($type) . '/index';
            return redirect($redirectUrl)->with('success', "Data {$type} berhasil disimpan dengan nomor {$nomorSurat}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $surat = SuratKeterangan::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'dokter'])->findOrFail($id);
        return view('rekam_medis.surat_keterangan.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = SuratKeterangan::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'dokter'])->findOrFail($id);
        $pendaftaran = $surat->pendaftaran;
        $dokters = Pegawai::where('fungsional_medis', 'like', '%Dokter%')
            ->orWhere('jabatan', 'like', '%Dokter%')
            ->get();
        if ($dokters->isEmpty()) {
            $dokters = Pegawai::all();
        }
        $backUrl = '/' . strtolower($surat->jenis_surat) . '/index';

        return view('rekam_medis.surat_keterangan.edit', compact('surat', 'pendaftaran', 'dokters', 'backUrl'));
    }

    public function update(Request $request, $id)
    {
        try {
            $surat = SuratKeterangan::findOrFail($id);
            
            $surat->update([
                'dokter_id'      => $request->dokter_id,
                'nomor_surat'    => $request->nomor_surat,
                'tanggal_surat'  => $request->tanggal_surat,
                'mulai_tanggal'  => $request->mulai_tanggal,
                'sampai_tanggal' => $request->sampai_tanggal,
                'keterangan'     => $request->keterangan,
            ]);

            $redirectUrl = '/' . strtolower($surat->jenis_surat) . '/index';
            return redirect($redirectUrl)->with('success', 'Data Surat Keterangan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $surat = SuratKeterangan::findOrFail($id);
            $type = $surat->jenis_surat;
            $surat->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Surat Keterangan berhasil dihapus.'
                ]);
            }

            return redirect('/' . strtolower($type) . '/index')->with('success', 'Surat Keterangan berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function export(Request $request, $type)
    {
        $type = strtoupper($type);
        $tanggal = $request->input('tanggal', '2026-10-14');
        $pendaftarans = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
            ->whereDate('tanggal_daftar', $tanggal)
            ->orderBy('nomor_registrasi', 'desc')
            ->get();

        $surats = SuratKeterangan::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->where('jenis_surat', $type)
            ->get()
            ->keyBy('pendaftaran_id');

        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=Laporan_{$type}_{$tanggal}.xls",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($pendaftarans, $surats, $type) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'No Registrasi', 'No RM', 'Nama Pasien', 'Tgl Lahir', 'Penjamin', 'Klinik', 'Dokter', "Nomor {$type}"]);

            foreach ($pendaftarans as $idx => $item) {
                $suratNo = isset($surats[$item->id]) ? $surats[$item->id]->nomor_surat : 'Belum diset';
                fputcsv($file, [
                    $idx + 1,
                    $item->nomor_registrasi,
                    $item->pasien->no_rm ?? '-',
                    $item->pasien->nama_pasien ?? '-',
                    $item->pasien->tanggal_lahir ?? '-',
                    $item->penjamin->nama_penjamin ?? 'BPJS Kesehatan',
                    $item->poliklinik->nama_poli ?? '-',
                    $item->dokter->nama_pegawai ?? '-',
                    $suratNo
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
