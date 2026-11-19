<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\TransaksiLab;
use App\Models\MasterLab;
use App\Models\TarifLab;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class TransaksiLabController extends Controller
{
    // 1. Daftar kunjungan pasien (index - pilih pasien)
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin', 'transaksiLabs'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_daftar', $request->tanggal);
        } else {
            // Jika tanggal kosong, tampilkan data pendaftaran terbaru (misal tanggal 2026-10-14 sesuai screenshot jika ada)
            $hasDate = Pendaftaran::whereDate('tanggal_daftar', '2026-10-14')->exists();
            if ($hasDate) {
                $query->whereDate('tanggal_daftar', '2026-10-14');
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

        $totalCount = $query->count();
        $data = $query->orderBy('nomor_registrasi', 'desc')->get();

        $tanggal = $request->get('tanggal', '2026-10-14');

        return view('laboratorium.transaksi_lab_index', compact('data', 'tanggal', 'totalCount'));
    }

    // 2. Detail: input pemeriksaan lab per kunjungan
    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                            ->findOrFail($pendaftaran_id);

        // Pemeriksaan lab yang sudah diinput
        $transaksis = TransaksiLab::with(['masterLab', 'analis'])
                        ->where('pendaftaran_id', $pendaftaran_id)
                        ->latest()
                        ->get();

        // Master data
        $master_labs = MasterLab::where('is_active', true)
                          ->orderBy('golongan_lab')
                          ->orderBy('nama_pemeriksaan')
                          ->get();
        $master_analis = Pegawai::where('is_active', true)->get();

        return view('laboratorium.transaksi_lab_detail',
            compact('pendaftaran', 'transaksis', 'master_labs', 'master_analis'));
    }

    // 3. API: ambil tarif lab berdasarkan master_lab + penjamin pasien
    public function getTarif(Request $request)
    {
        $master_lab_id = $request->master_lab_id;
        $penjamin_id   = $request->penjamin_id; // bisa null (UMUM)

        // Cari tarif khusus penjamin
        $tarif = TarifLab::where('master_lab_id', $master_lab_id)
                    ->where('penjamin_id', $penjamin_id)
                    ->where('is_active', true)
                    ->first();

        // Fallback ke tarif UMUM
        if (!$tarif) {
            $tarif = TarifLab::where('master_lab_id', $master_lab_id)
                        ->whereNull('penjamin_id')
                        ->where('is_active', true)
                        ->first();
        }

        $lab = MasterLab::find($master_lab_id);

        return response()->json([
            'harga' => $tarif ? $tarif->harga : ($lab && $lab->unitcost > 0 ? $lab->unitcost : 25000)
        ]);
    }

    // 4. Simpan pemeriksaan ke daftar transaksi
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'master_lab_id'  => 'required|exists:master_labs,id',
            'analis_id'      => 'nullable|exists:pegawais,id',
            'tarif'          => 'required|numeric|min:0',
            'qty'            => 'required|integer|min:1',
            'keterangan'     => 'nullable|string|max:500',
        ]);

        $item = TransaksiLab::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'master_lab_id'  => $request->master_lab_id,
            'analis_id'      => $request->analis_id,
            'tarif'          => $request->tarif,
            'qty'            => $request->qty,
            'subtotal'       => $request->tarif * $request->qty,
            'status'         => 'Pending',
            'keterangan'     => $request->keterangan,
        ]);

        $item->load(['masterLab', 'analis']);

        return response()->json([
            'success' => true,
            'message' => 'Pemeriksaan Lab berhasil ditambahkan.',
            'data'    => $item,
        ]);
    }

    // 5. Update status pemeriksaan (Pending -> Proses -> Selesai)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Proses,Selesai',
        ]);

        $item = TransaksiLab::findOrFail($id);
        $item->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status pemeriksaan diperbarui.',
            'status'  => $item->status,
        ]);
    }

    // 6. Hapus pemeriksaan
    public function destroy($id)
    {
        TransaksiLab::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Pemeriksaan berhasil dihapus.']);
    }

    // 7. Export XLS
    public function exportXls(Request $request)
    {
        $pendaftarans = Pendaftaran::with(['pasien', 'poliklinik', 'penjamin'])
                            ->where('status_periksa', '!=', 'Batal')
                            ->orderBy('id', 'desc')
                            ->get();

        $filename = "Laporan_Transaksi_Laboratorium_" . date('Ymd_His') . ".xls";

        $html = "<table border='1'>";
        $html .= "<tr style='background:#f2f2f2; font-weight:bold;'>";
        $html .= "<th>No</th><th>No Registrasi</th><th>No RM</th><th>Nama Pasien</th><th>JK</th><th>Tgl Lahir</th><th>Penjamin</th><th>Poli</th><th>Status</th>";
        $html .= "</tr>";

        foreach ($pendaftarans as $idx => $p) {
            $html .= "<tr>";
            $html .= "<td>" . ($idx + 1) . "</td>";
            $html .= "<td>" . ($p->nomor_registrasi ?: $p->no_rawat) . "</td>";
            $html .= "<td>" . ($p->pasien ? $p->pasien->no_rm : '-') . "</td>";
            $html .= "<td>" . ($p->pasien ? $p->pasien->nama_pasien : '-') . "</td>";
            $html .= "<td>" . ($p->pasien ? $p->pasien->jenis_kelamin : '-') . "</td>";
            $html .= "<td>" . ($p->pasien && $p->pasien->tanggal_lahir ? date('d M Y', strtotime($p->pasien->tanggal_lahir)) : '-') . "</td>";
            $html .= "<td>" . ($p->penjamin ? $p->penjamin->nama_penjamin : 'Pribadi') . "</td>";
            $html .= "<td>" . ($p->poliklinik ? $p->poliklinik->nama_poli : '-') . "</td>";
            $html .= "<td>" . $p->status_periksa . "</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
