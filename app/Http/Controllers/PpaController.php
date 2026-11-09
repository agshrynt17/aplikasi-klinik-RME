<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ppa;
use App\Models\Pendaftaran;
use App\Models\Poliklinik;
use App\Models\Pegawai;
use App\Models\Penjamin;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PpaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tanggal Filter
        if ($request->filled('tgl_awal')) {
            try {
                $tgl_awal = Carbon::parse($request->tgl_awal)->format('Y-m-d');
            } catch (\Exception $e) {
                $tgl_awal = '2026-10-14';
            }
        } else {
            $hasDate = Pendaftaran::whereDate('tanggal_daftar', '2026-10-14')->exists();
            $tgl_awal = $hasDate ? '2026-10-14' : date('Y-m-d');
        }

        if ($request->filled('tgl_akhir')) {
            try {
                $tgl_akhir = Carbon::parse($request->tgl_akhir)->format('Y-m-d');
            } catch (\Exception $e) {
                $tgl_akhir = $tgl_awal;
            }
        } else {
            $tgl_akhir = $tgl_awal;
        }

        // 2. Dokter Filter (Default ke dr. Fahmi Rizal sesuai screenshot PROFASKES)
        $defaultDokter = Pegawai::where('nama_pegawai', 'like', '%Fahmi%')->first();
        if ($request->has('dokter_id')) {
            $selectedDokterId = $request->dokter_id;
        } else {
            $selectedDokterId = $defaultDokter ? (string)$defaultDokter->id : '';
        }

        // 3. Dropdown Lookups
        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $dokters = Pegawai::where('fungsional_medis', 'like', '%Dokter%')
            ->orWhere('jabatan', 'like', '%Dokter%')
            ->orderBy('nama_pegawai')
            ->get();
        if ($dokters->isEmpty()) {
            $dokters = Pegawai::orderBy('nama_pegawai')->get();
        }
        $ruangans = Ruangan::where('is_active', true)->orderBy('nama_ruangan')->get();
        $penjamins = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();

        // 4. Query Pendaftaran
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'ppas'])
            ->where('status_periksa', '!=', 'Batal');

        $query->whereBetween(DB::raw('DATE(tanggal_daftar)'), [$tgl_awal, $tgl_akhir]);

        if (!empty($selectedDokterId)) {
            $query->where('dokter_id', $selectedDokterId);
        }

        if ($request->filled('poliklinik_id')) {
            $query->where('poliklinik_id', $request->poliklinik_id);
        }

        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->filled('ruangan')) {
            $query->where('ruang_pemeriksaan', 'like', "%{$request->ruangan}%");
        }

        if ($request->filled('nomor_registrasi')) {
            $query->where('nomor_registrasi', 'like', "%{$request->nomor_registrasi}%");
        }

        if ($request->filled('no_rm')) {
            $term = $request->no_rm;
            $query->whereHas('pasien', fn($q) => $q->where('no_rm', 'like', "%{$term}%"));
        }

        if ($request->filled('nama_pasien')) {
            $term = $request->nama_pasien;
            $query->whereHas('pasien', fn($q) => $q->where('nama_pasien', 'like', "%{$term}%"));
        }

        // Jenis Kunjungan (default 'Kunjungan Sakit' sesuai screenshot jika belum diset atau pilih 'Kunjungan Sakit')
        $jenisKunjungan = $request->input('jenis_kunjungan', 'Kunjungan Sakit');
        if (!empty($jenisKunjungan) && $jenisKunjungan !== '--Semua--') {
            $query->where('jenis_kunjungan', 'like', "%{$jenisKunjungan}%");
        }

        if ($request->filled('status_pulang') && $request->status_pulang !== '--Semua--') {
            $query->where('status_pulang', 'like', "%{$request->status_pulang}%");
        }

        if ($request->filled('skrining_visual') && $request->skrining_visual !== '--Semua--') {
            $query->where('skrining_visual', 'like', "%{$request->skrining_visual}%");
        }

        if ($request->boolean('is_online')) {
            $query->where('is_online', true);
        }

        $query->orderBy('nomor_registrasi', 'desc');

        $pendaftarans = $query->paginate(20)->withQueryString();
        $totalCount = $pendaftarans->total();

        return view('rekam_medis.ppa.index', compact(
            'pendaftarans',
            'polikliniks',
            'dokters',
            'ruangans',
            'penjamins',
            'totalCount',
            'tgl_awal',
            'tgl_akhir',
            'selectedDokterId',
            'jenisKunjungan',
            'defaultDokter'
        ));
    }

    // Tampilkan daftar CPPT untuk satu pendaftaran
    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        $ppas = Ppa::with('pegawai')->where('pendaftaran_id', $pendaftaran_id)->orderBy('waktu', 'asc')->get();
        
        return view('rekam_medis.ppa.show', compact('pendaftaran', 'ppas'));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        return view('rekam_medis.ppa.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'waktu'          => 'required',
        ]);

        try {
            Ppa::create([
                'pendaftaran_id' => $request->pendaftaran_id,
                'pasien_id'      => $request->pasien_id,
                'pegawai_id'     => $request->pegawai_id ?? auth()->user()->pegawai_id,
                'waktu'          => $request->waktu,
                'profesi'        => $request->profesi,
                'subjective'     => $request->subjective,
                'objective'      => $request->objective,
                'assessment'     => $request->assessment,
                'plan'           => $request->plan,
                'instruksi'      => $request->instruksi,
            ]);

            return redirect('/rekam-medis/ppa/' . $request->pendaftaran_id)->with('success', 'Catatan PPA (CPPT) berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $ppa = Ppa::with(['pendaftaran.pasien'])->findOrFail($id);
        $pendaftaran = $ppa->pendaftaran;
        
        return view('rekam_medis.ppa.edit', compact('ppa', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        try {
            $ppa = Ppa::findOrFail($id);
            
            $ppa->update([
                'waktu'          => $request->waktu,
                'profesi'        => $request->profesi,
                'subjective'     => $request->subjective,
                'objective'      => $request->objective,
                'assessment'     => $request->assessment,
                'plan'           => $request->plan,
                'instruksi'      => $request->instruksi,
            ]);

            return redirect('/rekam-medis/ppa/' . $ppa->pendaftaran_id)->with('success', 'Catatan PPA (CPPT) berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Ppa::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Catatan PPA (CPPT) berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
