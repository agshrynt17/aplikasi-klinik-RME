<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\SurveiKepuasan;
use App\Models\Poliklinik;
use App\Models\Penjamin;
use Database\Seeders\PendaftaranKonhakesSeeder;
use Illuminate\Http\Request;

class SurveiKepuasanController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan ada data pendaftaran jika kosong
        if (Pendaftaran::count() === 0) {
            try {
                (new PendaftaranKonhakesSeeder())->run();
            } catch (\Throwable $e) {}
        }

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'surveiKepuasan'])
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

        // Filter Status Survei
        if ($request->filled('status_survei')) {
            if ($request->status_survei === 'sudah') {
                $query->whereHas('surveiKepuasan');
            } elseif ($request->status_survei === 'belum') {
                $query->whereDoesntHave('surveiKepuasan');
            }
        }

        $data = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $total = $data->total();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins   = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();
        $tanggal     = $request->tanggal;

        return view('front_office.survei_kepuasan_index', compact('data', 'total', 'polikliniks', 'penjamins', 'tanggal'));
    }

    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with('pasien', 'dokter', 'poliklinik', 'penjamin')->findOrFail($pendaftaran_id);
        $survei = SurveiKepuasan::where('pendaftaran_id', $pendaftaran_id)->first();

        return view('front_office.survei_kepuasan_form', compact('pendaftaran', 'survei'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pelayanan'      => 'required|integer|min:1|max:5',
            'fasilitas'      => 'required|integer|min:1|max:5',
            'dokter'         => 'required|integer|min:1|max:5',
            'komentar'       => 'nullable|string'
        ], [
            'pelayanan.min' => 'Mohon berikan rating (Bintang) untuk Pelayanan.',
            'fasilitas.min' => 'Mohon berikan rating (Bintang) untuk Fasilitas.',
            'dokter.min' => 'Mohon berikan rating (Bintang) untuk Dokter.',
        ]);

        $survei = SurveiKepuasan::updateOrCreate(
            ['pendaftaran_id' => $request->pendaftaran_id],
            [
                'pelayanan' => $request->pelayanan,
                'fasilitas' => $request->fasilitas,
                'dokter'    => $request->dokter,
                'komentar'  => $request->komentar,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih, Survei Kepuasan berhasil disimpan!',
            'data'    => $survei,
        ]);
    }
}
