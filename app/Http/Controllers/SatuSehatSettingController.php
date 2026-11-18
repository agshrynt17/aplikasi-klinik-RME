<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;
use App\Models\BridgingConfig;
use Illuminate\Support\Facades\Http;

class SatuSehatSettingController extends Controller
{
    // =====================================
    // 1. DATA FASKES
    // =====================================
    public function faskes()
    {
        $config = BridgingConfig::where('provider', 'satusehat')->first();
        $profil = DB::table('profil_faskes')->first();
        
        $orgId = $config->organization_id ?? $profil->kode_ihs_satusehat ?? '100064741';
        $namaKlinik = $profil->nama_faskes ?? 'Klinik 24 Jam Telagasari';
        $type = 'Organization';
        $email = $profil->email ?? 'kliniktelagasari24jam@gmail.com';
        $telp = $profil->telepon ?? '(0264) 8303763';

        $faskes = (object)[
            'id'    => $orgId,
            'nama'  => $namaKlinik,
            'type'  => $type,
            'email' => $email,
            'telp'  => $telp,
        ];

        return view('bridging.satusehat_faskes', compact('faskes'));
    }

    public function storeFaskes(Request $request)
    {
        $request->validate([
            'kode_ihs_satusehat' => 'nullable|string'
        ]);

        $profil = DB::table('profil_faskes')->first();
        if ($profil) {
            DB::table('profil_faskes')->where('id', $profil->id)->update([
                'kode_ihs_satusehat' => $request->kode_ihs_satusehat,
                'updated_at' => now()
            ]);
        } else {
            DB::table('profil_faskes')->insert([
                'nama_faskes' => 'Klinik RME',
                'kode_ihs_satusehat' => $request->kode_ihs_satusehat,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Kode IHS/Organization ID Faskes berhasil disimpan.');
    }

    // =====================================
    // 2. DATA NAKES (KONHAKES 1:1)
    // =====================================
    public function nakes(Request $request)
    {
        $query = Pegawai::query()
            ->whereNotNull('nik')
            ->where('nik', '!=', '0')
            ->where('nik', '!=', '');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function($w) use ($q) {
                $w->where('nik', 'like', "%{$q}%")
                  ->orWhere('nama_pegawai', 'like', "%{$q}%")
                  ->orWhere('nama_satusehat', 'like', "%{$q}%")
                  ->orWhere('fungsional_medis', 'like', "%{$q}%")
                  ->orWhere('kode_ihs_satusehat', 'like', "%{$q}%");
            });
        }

        if ($request->filled('fungsional') && $request->fungsional !== 'ALL') {
            $query->where('fungsional_medis', $request->fungsional);
        }

        if ($request->filled('status_ihs')) {
            if ($request->status_ihs === 'SUDAH') {
                $query->whereNotNull('kode_ihs_satusehat')->where('kode_ihs_satusehat', '!=', '');
            } elseif ($request->status_ihs === 'BELUM') {
                $query->where(function($w) {
                    $w->whereNull('kode_ihs_satusehat')->orWhere('kode_ihs_satusehat', '');
                });
            }
        }

        $nakes = $query->orderBy('nik', 'asc')->paginate(20)->withQueryString();

        return view('bridging.satusehat_nakes', compact('nakes'));
    }

    private function getSatuSehatToken()
    {
        $config = BridgingConfig::where('provider', 'satusehat')->first();
        if ($config && !empty($config->token)) {
            return $config->token;
        }

        if ($config) {
            $authUrl = rtrim($config->auth_url ?? 'https://api-satusehat.kemkes.go.id/oauth2/v1', '/');
            $tokenUrl = $authUrl . '/accesstoken?grant_type=client_credentials';

            try {
                $res = Http::asForm()->timeout(10)->post($tokenUrl, [
                    'client_id'     => $config->client_id,
                    'client_secret' => $config->client_secret,
                ]);
                if ($res->successful()) {
                    $token = $res->json('access_token');
                    $config->token = $token;
                    $config->save();
                    return $token;
                }
            } catch (\Exception $e) {
                // ignore
            }
        }

        return null;
    }

    public function syncNakes(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        if (!$pegawai->nik) {
            return response()->json(['success' => false, 'message' => 'Kode NIK pegawai belum diisi.']);
        }

        $config = BridgingConfig::where('provider', 'satusehat')->first();
        $baseUrl = rtrim($config->base_url ?? 'https://api-satusehat.kemkes.go.id/fhir-r4/v1', '/');
        $token = $this->getSatuSehatToken();

        $ihsId = null;
        $namaSatuSehat = null;

        if ($token) {
            try {
                $url = $baseUrl . '/Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $pegawai->nik;
                $res = Http::withToken($token)->timeout(10)->get($url);

                if ($res->successful()) {
                    $json = $res->json();
                    if (!empty($json['entry'][0]['resource'])) {
                        $resObj = $json['entry'][0]['resource'];
                        $ihsId = $resObj['id'] ?? null;
                        if (!empty($resObj['name'][0]['text'])) {
                            $namaSatuSehat = $resObj['name'][0]['text'];
                        }
                    }
                }
            } catch (\Exception $e) {
                // error
            }
        }

        if ($ihsId) {
            $pegawai->kode_ihs_satusehat = $ihsId;
            if ($namaSatuSehat && !$pegawai->nama_satusehat) {
                $pegawai->nama_satusehat = $namaSatuSehat;
            }
            $pegawai->save();

            return response()->json([
                'success'        => true,
                'id_satusehat'   => $ihsId,
                'nama_satusehat' => $pegawai->nama_satusehat ?? $namaSatuSehat,
                'message'        => 'Berhasil sinkronisasi ID Satu Sehat: ' . $ihsId
            ]);
        }

        // Fallback jika NIK tidak terdaftar di SISDMK Kemenkes
        return response()->json([
            'success' => false,
            'message' => 'Data NIK ' . $pegawai->nik . ' tidak ditemukan di database SISDMK / Satu Sehat Kemenkes.'
        ]);
    }

    public function updateNamaNakes(Request $request)
    {
        $request->validate([
            'pegawai_id'     => 'required|exists:pegawais,id',
            'nama_satusehat' => 'required|string|max:255',
        ]);

        $pegawai = Pegawai::findOrFail($request->pegawai_id);
        $pegawai->nama_satusehat = $request->nama_satusehat;
        $pegawai->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nama pada Satu Sehat berhasil diperbarui.',
                'nama'    => $pegawai->nama_satusehat
            ]);
        }

        return redirect()->back()->with('success', 'Nama pada Satu Sehat berhasil diperbarui.');
    }

    public function storeNakes(Request $request)
    {
        if ($request->has('kode_ihs')) {
            foreach ($request->kode_ihs as $id => $kode) {
                Pegawai::where('id', $id)->update(['kode_ihs_satusehat' => $kode]);
            }
        }
        return redirect()->back()->with('success', 'Data Kode IHS Nakes berhasil diperbarui.');
    }

    // =====================================
    // 3. UNIT LAYANAN
    // =====================================
    public function unit()
    {
        $units = DB::table('unit_layanans')
                    ->select('id', 'nama_unit', 'keterangan', 'kode_ihs_satusehat')
                    ->where('is_active', true)
                    ->orderBy('nama_unit')
                    ->get();
        return view('bridging.satusehat_unit', compact('units'));
    }

    public function storeUnit(Request $request)
    {
        if ($request->has('kode_ihs')) {
            foreach ($request->kode_ihs as $id => $kode) {
                DB::table('unit_layanans')->where('id', $id)->update(['kode_ihs_satusehat' => $kode]);
            }
        }
        return redirect()->back()->with('success', 'Data Location ID Unit Layanan berhasil diperbarui.');
    }

    // =====================================
    // 4. RUANG RAWAT
    // =====================================
    public function ruangan()
    {
        $ruangans = DB::table('ruangans')
                    ->select('id', 'nama_ruangan', 'keterangan', 'kode_ihs_satusehat')
                    ->where('is_active', true)
                    ->orderBy('nama_ruangan')
                    ->get();
        return view('bridging.satusehat_ruangan', compact('ruangans'));
    }

    public function storeRuangan(Request $request)
    {
        if ($request->has('kode_ihs')) {
            foreach ($request->kode_ihs as $id => $kode) {
                DB::table('ruangans')->where('id', $id)->update(['kode_ihs_satusehat' => $kode]);
            }
        }
        return redirect()->back()->with('success', 'Data Location ID Ruang Rawat berhasil diperbarui.');
    }
}
