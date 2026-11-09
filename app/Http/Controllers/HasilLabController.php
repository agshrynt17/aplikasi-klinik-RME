<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\TransaksiLab;
use App\Models\HasilLab;
use App\Models\LabNilaiNormal;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HasilLabController extends Controller
{
    // 1. Index: daftar kunjungan yang punya transaksi lab (Daftar Order Lab)
    public function index(Request $request)
    {
        // Default tanggal ke hari ini jika ada data, atau fallback ke 2026-10-14 sesuai dataset demo PROFASKES
        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
        } else {
            $hasToday = Pendaftaran::whereHas('transaksiLabs')->whereDate('tanggal_daftar', date('Y-m-d'))->exists();
            $tanggal = $hasToday ? date('Y-m-d') : '2026-10-14';
        }

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin', 'transaksiLabs.masterLab'])
                    ->whereHas('transaksiLabs');

        if ($request->filled('tanggal') || !$request->filled('q')) {
            $query->whereDate('tanggal_daftar', $tanggal);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nomor_registrasi', 'like', "%{$q}%")
                  ->orWhereHas('pasien', function($p) use ($q) {
                      $p->where('nama_pasien', 'like', "%{$q}%")
                        ->orWhere('no_rm', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('no_bpjs', 'like', "%{$q}%");
                  });
            });
        }

        $data = $query->orderBy('tanggal_daftar', 'desc')
                      ->orderBy('jam_daftar', 'desc')
                      ->get()
                      ->map(function ($p) {
                          $total   = $p->transaksiLabs->count();
                          $selesai = $p->transaksiLabs->where('status', 'Selesai')->count();
                          $p->jumlah_item    = $total;
                          $p->jumlah_selesai = $selesai;
                          $p->persen_selesai = $total > 0 ? round($selesai / $total * 100) : 0;

                          // Format waktu order
                          if ($p->transaksiLabs->isNotEmpty() && $p->transaksiLabs->first()->created_at) {
                              $p->order_time_formatted = $p->transaksiLabs->first()->created_at->format('d/m/Y H:i');
                          } elseif ($p->jam_daftar) {
                              $p->order_time_formatted = Carbon::parse($p->tanggal_daftar)->format('d/m/Y') . ' ' . substr($p->jam_daftar, 0, 5);
                          } else {
                              $p->order_time_formatted = Carbon::parse($p->tanggal_daftar)->format('d/m/Y') . ' 08:00';
                          }

                          // Pasien spesimen label resolution
                          foreach ($p->transaksiLabs as $trx) {
                              $nama = strtolower($trx->masterLab->nama_pemeriksaan ?? '');
                              if (str_contains($nama, 'narkoba') || str_contains($nama, 'urin')) {
                                  $trx->specimen_label = 'Urine specimen';
                              } elseif (str_contains($nama, 'ureum') || str_contains($nama, 'creatinin') || str_contains($nama, 'sgot') || str_contains($nama, 'sgpt') || str_contains($nama, 'serum')) {
                                  $trx->specimen_label = 'Serum specimen';
                              } else {
                                  $trx->specimen_label = 'Blood specimen';
                              }
                          }

                          return $p;
                      });

        $totalCount = $data->count();

        return view('laboratorium.hasil_lab_index', compact('data', 'tanggal', 'totalCount'));
    }

    // Export PDF Daftar Order Lab
    public function exportPdf(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-14';
        $data = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin', 'transaksiLabs.masterLab'])
                    ->whereHas('transaksiLabs')
                    ->whereDate('tanggal_daftar', $tanggal)
                    ->orderBy('tanggal_daftar', 'desc')
                    ->orderBy('jam_daftar', 'desc')
                    ->get();
        return view('laboratorium.hasil_lab_pdf', compact('data', 'tanggal'));
    }

    // 2. Detail: input / lihat hasil per kunjungan
    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                            ->findOrFail($pendaftaran_id);

        // Hitung umur untuk menentukan nilai normal
        $tglLahir = $pendaftaran->pasien->tanggal_lahir ?? null;
        $umur     = $tglLahir ? Carbon::parse($tglLahir)->age : null;
        $kategoriUmur = 'Umum';
        if ($umur !== null) {
            if ($umur < 1)        $kategoriUmur = 'Bayi';
            elseif ($umur < 12)   $kategoriUmur = 'Anak';
            else                  $kategoriUmur = 'Dewasa';
        }
        $jenisKelamin = $pendaftaran->pasien->jenis_kelamin ?? 'Umum';

        // Transaksi lab beserta hasil (jika sudah ada)
        $transaksis = TransaksiLab::with(['masterLab', 'analis', 'hasilLab.verifikator'])
                        ->where('pendaftaran_id', $pendaftaran_id)
                        ->orderBy('id')
                        ->get()
                        ->map(function ($t) use ($jenisKelamin, $kategoriUmur) {
                            // Cari nilai normal yang paling relevan
                            $nilaiNormal = LabNilaiNormal::where('master_lab_id', $t->master_lab_id)
                                ->where(function ($q) use ($jenisKelamin) {
                                    $q->where('jenis_kelamin', $jenisKelamin)
                                      ->orWhere('jenis_kelamin', 'Umum');
                                })
                                ->where(function ($q) use ($kategoriUmur) {
                                    $q->where('kategori_umur', $kategoriUmur)
                                      ->orWhere('kategori_umur', 'Umum');
                                })
                                ->orderByRaw("CASE WHEN jenis_kelamin = ? THEN 0 ELSE 1 END", [$jenisKelamin])
                                ->orderByRaw("CASE WHEN kategori_umur = ? THEN 0 ELSE 1 END", [$kategoriUmur])
                                ->first();

                            $t->nilai_normal_ref = $nilaiNormal;

                            // Bangun teks nilai normal
                            if ($nilaiNormal) {
                                if ($nilaiNormal->nilai_teks) {
                                    $t->normal_text = $nilaiNormal->nilai_teks;
                                } elseif ($nilaiNormal->nilai_min && $nilaiNormal->nilai_max) {
                                    $satuan = $t->masterLab->satuan ?? '';
                                    $t->normal_text = $nilaiNormal->nilai_min . ' - ' . $nilaiNormal->nilai_max . ($satuan ? " $satuan" : '');
                                } elseif ($nilaiNormal->nilai_min) {
                                    $t->normal_text = '≥ ' . $nilaiNormal->nilai_min;
                                } elseif ($nilaiNormal->nilai_max) {
                                    $t->normal_text = '≤ ' . $nilaiNormal->nilai_max;
                                } else {
                                    $t->normal_text = '-';
                                }
                            } else {
                                $t->normal_text = '-';
                            }

                            return $t;
                        });

        $master_pegawai = Pegawai::where('is_active', true)->orderBy('nama_pegawai', 'asc')->get();

        return view('laboratorium.hasil_lab_detail',
            compact('pendaftaran', 'transaksis', 'master_pegawai', 'jenisKelamin', 'kategoriUmur', 'umur'));
    }

    // 3. Simpan / Update hasil (upsert)
    public function simpan(Request $request)
    {
        $request->validate([
            'transaksi_lab_id'  => 'required|exists:transaksi_labs,id',
            'nilai_hasil'       => 'nullable|string|max:100',
            'flag'              => 'required|in:Normal,Tinggi,Rendah,Abnormal',
            'catatan'           => 'nullable|string|max:1000',
        ]);

        $transaksi = TransaksiLab::with('masterLab')->findOrFail($request->transaksi_lab_id);

        // Bangun teks nilai normal dari request (dikirim dari form)
        $hasil = HasilLab::updateOrCreate(
            ['transaksi_lab_id' => $request->transaksi_lab_id],
            [
                'nilai_hasil'       => $request->nilai_hasil,
                'satuan'            => $transaksi->masterLab->satuan ?? null,
                'nilai_normal_text' => $request->nilai_normal_text,
                'flag'              => $request->flag,
                'catatan'           => $request->catatan,
            ]
        );

        // Update status transaksi lab → Selesai
        $transaksi->update(['status' => 'Selesai']);

        $hasil->load(['transaksiLab.masterLab', 'verifikator']);

        return response()->json([
            'success'     => true,
            'message'     => 'Hasil pemeriksaan berhasil disimpan.',
            'data'        => $hasil,
            'flag'        => $hasil->flag,
            'nilai_hasil' => $hasil->nilai_hasil,
        ]);
    }

    // 4. Verifikasi hasil (tunggal / semua)
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'diverifikasi_oleh' => 'required|exists:pegawais,id',
        ]);

        $verifikator = Pegawai::findOrFail($request->diverifikasi_oleh);
        $now = now();

        if ($id === 'semua') {
            $pendaftaranId = $request->pendaftaran_id;
            if (!$pendaftaranId) {
                return response()->json(['success' => false, 'message' => 'ID Pendaftaran tidak valid.'], 422);
            }

            $transaksiIds = TransaksiLab::where('pendaftaran_id', $pendaftaranId)->pluck('id');
            HasilLab::whereIn('transaksi_lab_id', $transaksiIds)
                ->whereNull('verified_at')
                ->update([
                    'diverifikasi_oleh' => $request->diverifikasi_oleh,
                    'verified_at'       => $now,
                ]);

            return response()->json([
                'success'     => true,
                'message'     => 'Semua hasil pemeriksaan berhasil diverifikasi.',
                'verifikator' => $verifikator->nama_pegawai ?? '-',
                'verified_at' => $now->format('d/m/Y H:i'),
                'is_all'      => true,
            ]);
        }

        $hasil = HasilLab::findOrFail($id);
        $hasil->update([
            'diverifikasi_oleh' => $request->diverifikasi_oleh,
            'verified_at'       => $now,
        ]);

        $hasil->load('verifikator');

        return response()->json([
            'success'     => true,
            'message'     => 'Hasil pemeriksaan berhasil diverifikasi.',
            'verifikator' => $hasil->verifikator->nama_pegawai ?? '-',
            'verified_at' => $hasil->verified_at->format('d/m/Y H:i'),
            'is_all'      => false,
        ]);
    }

    // 5. Hapus hasil
    public function hapus($id)
    {
        $hasil = HasilLab::findOrFail($id);
        // Kembalikan status transaksi ke Proses
        $hasil->transaksiLab->update(['status' => 'Proses']);
        $hasil->delete();
        return response()->json(['success' => true, 'message' => 'Hasil pemeriksaan berhasil dihapus.']);
    }
}
