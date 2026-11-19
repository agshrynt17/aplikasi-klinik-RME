<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransaksiKlaim;
use App\Models\Penjamin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KlaimController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->q ?? '');
        $statusKlaim = $request->status_klaim ?? '';
        $penjaminId = $request->penjamin_id ?? '';
        $tglAwal = $request->tgl_awal ?? '';
        $tglAkhir = $request->tgl_akhir ?? '';

        $query = TransaksiKlaim::with('penjamin');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('no_klaim', 'like', "%{$q}%")
                    ->orWhere('nama_penjamin', 'like', "%{$q}%");
            });
        }

        if ($statusKlaim) {
            $query->where('status_klaim', $statusKlaim);
        }

        if ($penjaminId) {
            $query->where('penjamin_id', $penjaminId);
        }

        if ($tglAwal && $tglAkhir) {
            $query->whereBetween('tgl_klaim', [$tglAwal, $tglAkhir]);
        }

        $klaims = $query->orderBy('id', 'asc')->paginate(20)->withQueryString();
        $penjamins = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();

        return view('klaim.index', compact(
            'klaims', 'q', 'statusKlaim', 'penjaminId', 'tglAwal', 'tglAkhir', 'penjamins'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_klaim' => 'required|unique:transaksi_klaims,no_klaim',
            'penjamin_id' => 'required',
            'tgl_klaim' => 'required|date',
        ]);

        $penj = Penjamin::find($request->penjamin_id);

        $tglAwal = $request->tgl_transaksi_awal ? Carbon::parse($request->tgl_transaksi_awal) : null;
        $tglAkhir = $request->tgl_transaksi_akhir ? Carbon::parse($request->tgl_transaksi_akhir) : null;
        $jmlHari = ($tglAwal && $tglAkhir) ? $tglAwal->diffInDays($tglAkhir) + 1 : 0;

        $subtotal = (float)($request->subtotal ?? 0);
        $diskon = (float)($request->diskon ?? 0);
        $totalTagihan = (float)($request->total_tagihan ?? ($subtotal - $diskon));
        $totalRealisasi = (float)($request->total_realisasi ?? 0);
        $selisih = $totalTagihan - $totalRealisasi;

        TransaksiKlaim::create([
            'status_klaim' => $request->status_klaim ?? 'Draft',
            'tgl_klaim' => $request->tgl_klaim,
            'no_klaim' => $request->no_klaim,
            'penjamin_id' => $penj ? $penj->id : null,
            'nama_penjamin' => $penj ? $penj->nama_penjamin : $request->nama_penjamin,
            'tgl_transaksi_awal' => $tglAwal,
            'tgl_transaksi_akhir' => $tglAkhir,
            'jml_hari' => $jmlHari,
            'subtotal' => $subtotal,
            'diskon' => $diskon,
            'total_tagihan' => $totalTagihan,
            'total_realisasi' => $totalRealisasi,
            'selisih' => $selisih,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('klaim.index')->with('success', 'Tagihan klaim berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $tk = TransaksiKlaim::findOrFail($id);

        if ($request->has('total_realisasi')) {
            $realisasi = (float)$request->total_realisasi;
            $tk->total_realisasi = $realisasi;
            $tk->selisih = (float)$tk->total_tagihan - $realisasi;
            $tk->save();
            return response()->json(['success' => true, 'message' => 'Total realisasi berhasil diperbarui.', 'selisih' => number_format($tk->selisih, 0, ',', '.')]);
        }

        $tk->update($request->all());
        return redirect()->route('klaim.index')->with('success', 'Data klaim berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tk = TransaksiKlaim::findOrFail($id);
        $tk->delete();
        return redirect()->route('klaim.index')->with('success', 'Data klaim berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $q = trim($request->q ?? '');
        $statusKlaim = $request->status_klaim ?? '';
        $penjaminId = $request->penjamin_id ?? '';
        $tglAwal = $request->tgl_awal ?? '';
        $tglAkhir = $request->tgl_akhir ?? '';

        $query = TransaksiKlaim::with('penjamin');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('no_klaim', 'like', "%{$q}%")
                    ->orWhere('nama_penjamin', 'like', "%{$q}%");
            });
        }
        if ($statusKlaim) $query->where('status_klaim', $statusKlaim);
        if ($penjaminId) $query->where('penjamin_id', $penjaminId);
        if ($tglAwal && $tglAkhir) $query->whereBetween('tgl_klaim', [$tglAwal, $tglAkhir]);

        $klaims = $query->orderBy('id', 'asc')->get();

        return view('klaim.export_pdf', compact('klaims'));
    }

    public function exportExcel(Request $request)
    {
        $filename = 'Transaksi_Klaim_Pasien_' . date('Ymd_His') . '.csv';

        $klaims = TransaksiKlaim::orderBy('id', 'asc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($klaims) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No', 'Status Klaim', 'Tgl Klaim', 'No Klaim', 'Penjamin',
                'Tgl. Transaksi', 'Jml.Hari', 'Subtotal', 'Diskon (Rp)',
                'Total Tagihan', 'Total Realisasi', 'Selisih'
            ]);

            foreach ($klaims as $idx => $item) {
                $tglTrx = ($item->tgl_transaksi_awal ? $item->tgl_transaksi_awal->format('d/m/Y') : '') . ' - ' . ($item->tgl_transaksi_akhir ? $item->tgl_transaksi_akhir->format('d/m/Y') : '');

                fputcsv($handle, [
                    $idx + 1,
                    $item->status_klaim,
                    $item->tgl_klaim ? $item->tgl_klaim->format('d/m/Y') : '',
                    $item->no_klaim,
                    $item->nama_penjamin,
                    $tglTrx,
                    $item->jml_hari . ' Hari',
                    number_format($item->subtotal, 0, ',', '.'),
                    number_format($item->diskon, 0, ',', '.'),
                    number_format($item->total_tagihan, 0, ',', '.'),
                    number_format($item->total_realisasi, 0, ',', '.'),
                    number_format($item->selisih, 0, ',', '.')
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

