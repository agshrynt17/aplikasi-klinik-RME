<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\DokumenPelayanan;
use App\Models\Pendaftaran;
use App\Models\Poliklinik;
use App\Models\Penjamin;

class DokumenPelayananController extends Controller
{
    public function index(Request $request)
    {
        $rawTglAwal = $request->input('tgl_daftar', $request->input('tgl_awal', '14-10-2026'));
        $rawTglAkhir = $request->input('sampai', $request->input('tgl_akhir', '14-10-2026'));

        $parseDate = function($val, $default) {
            if (!$val) return $default;
            try {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $val)) {
                    return \Carbon\Carbon::createFromFormat('d-m-Y', $val)->format('Y-m-d');
                }
                return date('Y-m-d', strtotime($val));
            } catch (\Exception $e) {
                return $default;
            }
        };

        $tglAwal = $parseDate($rawTglAwal, '2026-10-14');
        $tglAkhir = $parseDate($rawTglAkhir, '2026-10-14');

        $tglAwalFormatted = date('d-m-Y', strtotime($tglAwal));
        $tglAkhirFormatted = date('d-m-Y', strtotime($tglAkhir));

        $mode = $request->input('mode', 'rawat_jalan');

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
            ->whereDate('tanggal_daftar', '>=', $tglAwal)
            ->whereDate('tanggal_daftar', '<=', $tglAkhir);

        if ($mode === 'rawat_inap') {
            $query->where('jenis_rawat', 'Rawat Inap');
        } else {
            $query->where(function($q) {
                $q->where('jenis_rawat', 'Rawat Jalan')
                  ->orWhereNull('jenis_rawat');
            });
        }

        if ($request->filled('kode_reg')) {
            $reg = $request->input('kode_reg');
            $query->where(function($q) use ($reg) {
                $q->where('nomor_registrasi', 'like', "%{$reg}%")
                  ->orWhere('no_rawat', 'like', "%{$reg}%");
            });
        }

        if ($request->filled('nama_pasien')) {
            $nama = $request->input('nama_pasien');
            $query->whereHas('pasien', function($q) use ($nama) {
                $q->where('nama_pasien', 'like', "%{$nama}%");
            });
        }

        if ($request->filled('kode_emr')) {
            $emr = $request->input('kode_emr');
            $query->whereHas('pasien', function($q) use ($emr) {
                $q->where('no_rm', 'like', "%{$emr}%");
            });
        }

        if ($request->filled('penjamin') && $request->input('penjamin') !== '--Pilih--') {
            $penjaminVal = $request->input('penjamin');
            $query->where(function($q) use ($penjaminVal) {
                $q->where('penjamin_id', $penjaminVal)
                  ->orWhereHas('penjamin', function($pq) use ($penjaminVal) {
                      $pq->where('nama_penjamin', $penjaminVal);
                  });
            });
        }

        if ($request->filled('klinik') && $request->input('klinik') !== '--Semua--') {
            $klinikVal = $request->input('klinik');
            $query->where(function($q) use ($klinikVal) {
                $q->where('poliklinik_id', $klinikVal)
                  ->orWhereHas('poliklinik', function($pq) use ($klinikVal) {
                      $pq->where('nama_poli', $klinikVal);
                  });
            });
        }

        $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        $dokumens = DokumenPelayanan::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->get()
            ->groupBy('pendaftaran_id');

        $listKlinik = Poliklinik::orderBy('nama_poli')->get();
        $listPenjamin = Penjamin::orderBy('nama_penjamin')->get();

        return view('rekam_medis.dokumen.index', compact(
            'pendaftarans',
            'dokumens',
            'tglAwal',
            'tglAkhir',
            'tglAwalFormatted',
            'tglAkhirFormatted',
            'mode',
            'listKlinik',
            'listPenjamin'
        ));
    }

    public function showUploadForm($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])->findOrFail($pendaftaran_id);
        
        $dokumens = DokumenPelayanan::where('pendaftaran_id', $pendaftaran_id)->get();

        return view('rekam_medis.dokumen.upload', compact('pendaftaran', 'dokumens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'nama_dokumen'   => 'required',
            'jenis_dokumen'  => 'required',
            'file_dokumen'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $filename = $request->pasien_id . '_' . date('Ymd_His') . '_' . preg_replace('/[^A-Za-z0-9\-]/', '', $request->jenis_dokumen) . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('public/dokumen_pasien', $filename);
                
                DokumenPelayanan::create([
                    'pendaftaran_id' => $request->pendaftaran_id,
                    'pasien_id'      => $request->pasien_id,
                    'nama_dokumen'   => $request->nama_dokumen,
                    'jenis_dokumen'  => $request->jenis_dokumen,
                    'file_path'      => 'dokumen_pasien/' . $filename,
                    'keterangan'     => $request->keterangan,
                    'uploaded_by'    => Auth::id() ?? 1,
                ]);

                return back()->with('success', 'Dokumen berhasil diunggah.');
            }
            
            return back()->with('error', 'File tidak ditemukan.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $dokumen = DokumenPelayanan::findOrFail($id);
            
            if (Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            $dokumen->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
