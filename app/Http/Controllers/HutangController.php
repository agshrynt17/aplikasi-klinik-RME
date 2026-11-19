<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hutang;
use App\Models\PembayaranHutang;
use App\Models\MasterVendor;
use App\Models\MasukBarang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HutangController extends Controller
{
    /**
     * Display a listing of Pembayaran Hutang (KONHAKES 1:1).
     */
    public function index(Request $request)
    {
        $query = Hutang::with(['masterVendor', 'masukBarang', 'pembayarans'])
            ->orderBy('status', 'asc')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->orderBy('id', 'desc');

        // Filter: No Tagihan (cek no_tagihan, nomor_faktur, nomor_penerimaan)
        if ($request->filled('no_tagihan')) {
            $tagihan = trim($request->no_tagihan);
            $query->where(function ($q) use ($tagihan) {
                $q->where('no_tagihan', 'like', "%{$tagihan}%")
                  ->orWhereHas('masukBarang', function ($mb) use ($tagihan) {
                      $mb->where('nomor_faktur', 'like', "%{$tagihan}%")
                         ->orWhere('nomor_penerimaan', 'like', "%{$tagihan}%");
                  });
            });
        }

        // Filter: Tanggal Tagihan Dibuat (Rentang Tgl Tagihan s.d Tanggal)
        if ($request->filled('tgl_tagihan')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->tgl_tagihan)
                  ->orWhereHas('masukBarang', function ($mb) use ($request) {
                      $mb->whereDate('tanggal_terima', '>=', $request->tgl_tagihan);
                  });
            });
        }
        if ($request->filled('sd_tanggal')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->sd_tanggal)
                  ->orWhereHas('masukBarang', function ($mb) use ($request) {
                      $mb->whereDate('tanggal_terima', '<=', $request->sd_tanggal);
                  });
            });
        }

        // Filter: Vendor
        if ($request->filled('vendor_id')) {
            $query->where('master_vendor_id', $request->vendor_id);
        }

        // Filter: Status Bayar
        if ($request->filled('status_bayar')) {
            $query->where('status', $request->status_bayar);
        }

        // Filter: Cara Bayar / Metode Pembayaran
        if ($request->filled('cara_bayar')) {
            $metode = $request->cara_bayar;
            $query->where(function ($q) use ($metode) {
                $q->where('cara_bayar', 'like', "%{$metode}%")
                  ->orWhereHas('pembayarans', function ($p) use ($metode) {
                      $p->where('metode_pembayaran', 'like', "%{$metode}%");
                  });
            });
        }

        // Filter: Periode Jatuh Tempo
        if ($request->filled('periode_jatuh_tempo')) {
            $today = Carbon::today()->toDateString();
            switch ($request->periode_jatuh_tempo) {
                case 'lewat':
                    $query->whereDate('tanggal_jatuh_tempo', '<', $today)
                          ->where('status', '!=', 'Lunas');
                    break;
                case 'hari_ini':
                    $query->whereDate('tanggal_jatuh_tempo', '=', $today);
                    break;
                case '7_hari':
                    $query->whereBetween('tanggal_jatuh_tempo', [$today, Carbon::today()->addDays(7)->toDateString()]);
                    break;
                case '30_hari':
                    $query->whereBetween('tanggal_jatuh_tempo', [$today, Carbon::today()->addDays(30)->toDateString()]);
                    break;
            }
        }

        $data = $query->paginate(20)->withQueryString();
        $vendors = MasterVendor::where('is_active', true)->orderBy('nama_vendor')->get();

        return view('farmasi.hutang.index', compact('data', 'vendors'));
    }

    /**
     * Show detail of hutang with payment history.
     */
    public function show($id)
    {
        $hutang = Hutang::with(['masterVendor', 'masukBarang.details.masterBarang', 'pembayarans'])->findOrFail($id);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'hutang'  => $hutang
            ]);
        }

        $profil = DB::table('profil_faskes')->first();
        return view('farmasi.hutang.show', compact('hutang', 'profil'));
    }

    /**
     * Process payment for a hutang.
     */
    public function bayar(Request $request, $id)
    {
        $request->validate([
            'nominal_bayar'     => 'required|numeric|min:1',
            'tanggal_bayar'     => 'required|date',
            'metode_pembayaran' => 'required|string|max:50'
        ], [
            'nominal_bayar.required' => 'Nominal bayar wajib diisi.',
            'nominal_bayar.min'      => 'Nominal bayar minimal Rp 1.',
            'tanggal_bayar.required' => 'Tanggal pembayaran wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.'
        ]);

        try {
            DB::beginTransaction();

            $hutang = Hutang::lockForUpdate()->findOrFail($id);
            
            if ($hutang->sisa_hutang <= 0 || $hutang->status === 'Lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan hutang ini sudah lunas sepenuhnya.'
                ], 422);
            }

            $nominal = floatval($request->nominal_bayar);
            if ($nominal > $hutang->sisa_hutang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nominal pembayaran (Rp ' . number_format($nominal, 0, ',', '.') . ') melebihi sisa hutang (Rp ' . number_format($hutang->sisa_hutang, 0, ',', '.') . ').'
                ], 422);
            }

            // Auto-generate nomor bukti jika kosong
            $nomorBukti = $request->nomor_bukti;
            if (empty($nomorBukti)) {
                $nomorBukti = 'BKT-' . date('Ymd') . '-' . str_pad(PembayaranHutang::count() + 1, 4, '0', STR_PAD_LEFT);
            }

            // 1. Create Pembayaran Record
            $pembayaran = PembayaranHutang::create([
                'hutang_id'         => $hutang->id,
                'nomor_bukti'       => $nomorBukti,
                'tanggal_bayar'     => $request->tanggal_bayar,
                'nominal_bayar'     => $nominal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan'        => $request->keterangan
            ]);

            // 2. Update Hutang Balance
            $hutang->jumlah_dibayar += $nominal;
            $hutang->sisa_hutang = max(0, $hutang->sisa_hutang - $nominal);
            $hutang->cara_bayar = $request->metode_pembayaran;

            $isLunas = ($hutang->sisa_hutang <= 0);
            if ($isLunas) {
                $hutang->status = 'Lunas';
            }
            $hutang->save();

            // 3. Sync with MasukBarang if related
            if ($hutang->masukBarang) {
                $mb = $hutang->masukBarang;
                $mb->total_bayar = ($mb->total_bayar ?? 0) + $nominal;
                $mb->sisa_bayar = max(0, ($mb->grand_total > 0 ? $mb->grand_total : $mb->total_tagihan) - $mb->total_bayar);
                if ($isLunas || $mb->sisa_bayar <= 0) {
                    $mb->status_pembayaran = 'Lunas';
                }
                $mb->save();
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Pembayaran Hutang sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' berhasil diproses.',
                'sisa_hutang'   => $hutang->sisa_hutang,
                'status'        => $hutang->status,
                'pembayaran_id' => $pembayaran->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cetak Bukti Pembayaran / Kuitansi Hutang (PDF View).
     */
    public function cetakKuitansi($id, $pembayaran_id = null)
    {
        $hutang = Hutang::with(['masterVendor', 'masukBarang.details.masterBarang', 'pembayarans'])->findOrFail($id);
        
        $pembayaran = null;
        if ($pembayaran_id) {
            $pembayaran = PembayaranHutang::where('hutang_id', $hutang->id)->find($pembayaran_id);
        }
        if (!$pembayaran) {
            $pembayaran = $hutang->pembayarans()->latest('id')->first();
        }

        $profil = DB::table('profil_faskes')->first();
        return view('farmasi.hutang.cetak_kuitansi', compact('hutang', 'pembayaran', 'profil'));
    }

    /**
     * Export Excel of Data Pembayaran Hutang.
     */
    public function exportExcel(Request $request)
    {
        $query = Hutang::with(['masterVendor', 'masukBarang', 'pembayarans'])
            ->orderBy('tanggal_jatuh_tempo', 'asc');

        if ($request->filled('no_tagihan')) {
            $tagihan = trim($request->no_tagihan);
            $query->where(function ($q) use ($tagihan) {
                $q->where('no_tagihan', 'like', "%{$tagihan}%")
                  ->orWhereHas('masukBarang', function ($mb) use ($tagihan) {
                      $mb->where('nomor_faktur', 'like', "%{$tagihan}%")
                         ->orWhere('nomor_penerimaan', 'like', "%{$tagihan}%");
                  });
            });
        }
        if ($request->filled('tgl_tagihan')) {
            $query->whereDate('created_at', '>=', $request->tgl_tagihan);
        }
        if ($request->filled('sd_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sd_tanggal);
        }
        if ($request->filled('vendor_id')) {
            $query->where('master_vendor_id', $request->vendor_id);
        }
        if ($request->filled('status_bayar')) {
            $query->where('status', $request->status_bayar);
        }

        $data = $query->get();

        $filename = 'Data_Pembayaran_Hutang_' . date('Ymd_His') . '.xls';

        $profil = DB::table('profil_faskes')->first();
        $klinikNama = $profil->nama_klinik ?? 'KLINIK TELAGASARI 24 JAM';

        $html = "<table border='1'>";
        $html .= "<tr><th colspan='10' style='font-size:16px; font-weight:bold; text-align:center;'>DATA PEMBAYARAN HUTANG - " . strtoupper($klinikNama) . "</th></tr>";
        $html .= "<tr><th colspan='10' style='text-align:center;'>Dicetak pada: " . date('d-m-Y H:i:s') . "</th></tr>";
        $html .= "<tr><th colspan='10'></th></tr>";
        $html .= "<tr style='background-color:#dff0d8; font-weight:bold;'>
                <th>No</th>
                <th>No Tagihan</th>
                <th>Tagihan Dibuat</th>
                <th>Jatuh Tempo</th>
                <th>Vendor</th>
                <th>Total Tagihan</th>
                <th>Total Pembayaran</th>
                <th>Sisa Pembayaran</th>
                <th>Cara Bayar</th>
                <th>Status Pembayaran</th>
              </tr>";

        $totalHutangSum = 0;
        $totalBayarSum = 0;
        $totalSisaSum = 0;

        foreach ($data as $idx => $row) {
            $totalHutangSum += $row->jumlah_hutang;
            $totalBayarSum += $row->jumlah_dibayar;
            $totalSisaSum += $row->sisa_hutang;

            $tglDibuat = $row->masukBarang ? Carbon::parse($row->masukBarang->tanggal_terima)->format('d-m-Y') : Carbon::parse($row->created_at)->format('d-m-Y');
            $tglJatuhTempo = $row->tanggal_jatuh_tempo ? Carbon::parse($row->tanggal_jatuh_tempo)->format('d-m-Y') : '-';

            $html .= "<tr>";
            $html .= "<td align='center'>" . ($idx + 1) . "</td>";
            $html .= "<td>" . $row->no_tagihan_display . "</td>";
            $html .= "<td align='center'>" . $tglDibuat . "</td>";
            $html .= "<td align='center'>" . $tglJatuhTempo . "</td>";
            $html .= "<td>" . ($row->masterVendor ? $row->masterVendor->nama_vendor : '-') . "</td>";
            $html .= "<td align='right'>" . number_format($row->jumlah_hutang, 0, ',', '.') . "</td>";
            $html .= "<td align='right'>" . number_format($row->jumlah_dibayar, 0, ',', '.') . "</td>";
            $html .= "<td align='right'>" . number_format($row->sisa_hutang, 0, ',', '.') . "</td>";
            $html .= "<td align='center'>" . ($row->cara_bayar ?: ($row->pembayarans->first() ? $row->pembayarans->first()->metode_pembayaran : '-')) . "</td>";
            $html .= "<td align='center'>" . $row->status . "</td>";
            $html .= "</tr>";
        }

        $html .= "<tr style='background-color:#f5f5f5; font-weight:bold;'>
                <td colspan='5' align='right'>TOTAL KESELURUHAN</td>
                <td align='right'>" . number_format($totalHutangSum, 0, ',', '.') . "</td>
                <td align='right'>" . number_format($totalBayarSum, 0, ',', '.') . "</td>
                <td align='right'>" . number_format($totalSisaSum, 0, ',', '.') . "</td>
                <td colspan='2'></td>
              </tr>";
        $html .= "</table>";

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
