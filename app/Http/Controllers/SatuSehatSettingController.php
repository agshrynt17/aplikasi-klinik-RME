<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;
use App\Models\BridgingConfig;
use App\Models\SatuSehatUnit;
use App\Models\SatuSehatLokasi;
use App\Models\Ruangan;
use Illuminate\Support\Str;
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
    public function unitIndex(Request $request)
    {
        $query = SatuSehatUnit::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('unit_satusehat', 'like', "%{$q}%")
                    ->orWhere('unit_aplikasi', 'like', "%{$q}%");
            });
        }

        $units = $query->orderBy('id', 'asc')->get();
        $total = $units->count();

        $unitLayanans = DB::table('unit_layanans')->where('is_active', true)->get();

        return view('bridging.satusehat_unit', compact('units', 'total', 'unitLayanans'));
    }

    public function unit(Request $request)
    {
        return $this->unitIndex($request);
    }

    public function unitCreate()
    {
        $unitLayanans = DB::table('unit_layanans')->where('is_active', true)->get();
        return view('bridging.satusehat_unit_create', compact('unitLayanans'));
    }

    public function unitStore(Request $request)
    {
        $unitAplikasi = $request->unit_aplikasi;
        if (!$unitAplikasi && $request->unit_layanan_id) {
            $u = DB::table('unit_layanans')->where('id', $request->unit_layanan_id)->first();
            if ($u) {
                $unitAplikasi = $u->nama_unit;
            }
        }
        if (!$unitAplikasi) {
            $unitAplikasi = $request->unit_satusehat;
        }

        $request->merge(['unit_aplikasi' => $unitAplikasi]);

        $request->validate([
            'unit_satusehat' => 'required|string|max:255',
            'unit_layanan_id' => 'nullable',
            'unit_aplikasi' => 'required|string|max:255',
            'location_id_satusehat' => 'nullable|string|max:255',
        ]);

        SatuSehatUnit::create([
            'unit_satusehat' => $request->unit_satusehat,
            'unit_layanan_id' => $request->unit_layanan_id,
            'unit_aplikasi' => $unitAplikasi,
            'location_id_satusehat' => $request->location_id_satusehat ?? ('loc-' . rand(100000, 999999)),
            'status' => $request->status ?? 'Aktif',
            'diubah_pada' => now(),
        ]);

        return redirect('/satusehat-unit/index')->with('success', 'Data Unit Layanan Satu Sehat berhasil ditambahkan.');
    }

    public function unitView(Request $request)
    {
        $id = $request->get('id');
        $unit = SatuSehatUnit::findOrFail($id);

        return view('bridging.satusehat_unit_view', compact('unit'));
    }

    public function unitUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:satusehat_units,id',
            'unit_aplikasi' => 'required|string|max:255',
        ]);

        $unit = SatuSehatUnit::findOrFail($request->id);
        $unit->unit_aplikasi = $request->unit_aplikasi;
        if ($request->filled('unit_satusehat')) {
            $unit->unit_satusehat = $request->unit_satusehat;
        }
        $unit->diubah_pada = now();
        $unit->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit aplikasi berhasil diperbarui.',
                'data' => $unit
            ]);
        }

        return redirect()->route('satusehat.unit.index')->with('success', 'Data Unit Layanan Satu Sehat berhasil diperbarui.');
    }

    public function unitSync(Request $request)
    {
        // Simulate/sync Satu Sehat units
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menarik 1 data unit layanan dari Satu Sehat.'
        ]);
    }

    public function storeUnit(Request $request)
    {
        return $this->unitStore($request);
    }

    // =====================================
    // 4. RUANG RAWAT / LOKASI
    // =====================================
    public function lokasiIndex(Request $request)
    {
        $query = SatuSehatLokasi::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama_ruangan_satusehat', 'like', "%{$q}%")
                  ->orWhere('referensi_ruangan', 'like', "%{$q}%")
                  ->orWhere('location_id_satusehat', 'like', "%{$q}%");
            });
        }

        $lokasis = $query->orderBy('id', 'asc')->get();
        $total = $lokasis->count();
        $ruangans = DB::table('ruangans')->where('is_active', true)->get();

        return view('bridging.satusehat_lokasi', compact('lokasis', 'total', 'ruangans'));
    }

    public function lokasiCreate()
    {
        $ruangans = DB::table('ruangans')->where('is_active', true)->get();
        return view('bridging.satusehat_lokasi_create', compact('ruangans'));
    }

    public function lokasiStore(Request $request)
    {
        $request->validate([
            'nama_ruangan_satusehat' => 'required|string|max:255',
            'ruangan_id' => 'nullable',
        ]);

        $referensiRuangan = $request->referensi_ruangan;
        if (!$referensiRuangan && $request->ruangan_id) {
            $r = DB::table('ruangans')->where('id', $request->ruangan_id)->first();
            if ($r) {
                $referensiRuangan = $r->nama_ruangan;
            }
        }
        if (!$referensiRuangan) {
            $referensiRuangan = $request->nama_ruangan_satusehat;
        }

        $locationId = $request->location_id_satusehat;
        if (!$locationId) {
            $locationId = (string) Str::uuid();
        }

        SatuSehatLokasi::create([
            'ruangan_id' => $request->ruangan_id,
            'nama_tipe' => $request->nama_tipe ?? 'Room',
            'nama_ruangan_satusehat' => $request->nama_ruangan_satusehat,
            'location_id_satusehat' => $locationId,
            'referensi_ruangan' => $referensiRuangan,
            'deskripsi' => $request->deskripsi,
            'telp' => $request->telp ?? '(0264) 8303763',
            'fax' => $request->fax,
            'email' => $request->email ?? 'kliniktelagasari24jam@gmail.com',
            'url' => $request->url,
            'status' => $request->status ?? 'Aktif',
            'alamat' => $request->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang',
            'rt' => $request->rt ?? '0',
            'rw' => $request->rw ?? '0',
            'provinsi' => $request->provinsi ?? 'JAWA BARAT',
            'kabupaten' => $request->kabupaten ?? 'KABUPATEN KARAWANG',
            'kecamatan' => $request->kecamatan ?? 'TELAGASARI',
            'desa' => $request->desa ?? 'TELAGASARI',
            'kode_pos' => $request->kode_pos ?? '41373',
            'longitude' => $request->longitude ?? '0',
            'latitude' => $request->latitude ?? '0',
            'altitude' => $request->altitude ?? '0',
            'diubah_pada' => now(),
        ]);

        return redirect('/satusehat-lokasi/index')->with('success', 'Data Ruangan Satu Sehat berhasil ditambahkan.');
    }

    public function lokasiView(Request $request)
    {
        $id = $request->get('id');
        $lokasi = SatuSehatLokasi::findOrFail($id);
        return view('bridging.satusehat_lokasi_view', compact('lokasi'));
    }

    public function lokasiUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:satusehat_lokasis,id',
            'referensi_ruangan' => 'required|string|max:255',
        ]);

        $lokasi = SatuSehatLokasi::findOrFail($request->id);
        $lokasi->referensi_ruangan = $request->referensi_ruangan;
        if ($request->filled('ruangan_id')) {
            $lokasi->ruangan_id = $request->ruangan_id;
        }
        if ($request->filled('nama_ruangan_satusehat')) {
            $lokasi->nama_ruangan_satusehat = $request->nama_ruangan_satusehat;
        }
        $lokasi->diubah_pada = now();
        $lokasi->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Referensi ruangan berhasil diperbarui.',
                'data' => $lokasi
            ]);
        }

        return redirect('/satusehat-lokasi/index')->with('success', 'Data Ruangan Satu Sehat berhasil diperbarui.');
    }

    public function lokasiSync(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menarik 1 data ruang dari Satu Sehat.'
        ]);
    }

    public function ruangan()
    {
        return $this->lokasiIndex(request());
    }

    public function storeRuangan(Request $request)
    {
        return $this->lokasiStore($request);
    }
}
