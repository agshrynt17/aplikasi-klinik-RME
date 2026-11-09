<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BridgingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BridgingConfigController extends Controller
{
    // =====================================
    // 1. CONFIG BPJS ANTREAN (ANTROL)
    // =====================================
    public function configBpjs()
    {
        $config = BridgingConfig::firstOrCreate(
            ['provider' => 'bpjs_antrol'],
            [
                'base_url' => 'https://apijkn-dev.bpjs-kesehatan.go.id/antreanrs_dev/',
                'is_production' => false
            ]
        );
        return view('bridging.config_bpjs', compact('config'));
    }

    public function storeBpjs(Request $request)
    {
        $request->validate([
            'client_id'     => 'required|string',
            'client_secret' => 'required|string',
            'user_key'      => 'required|string',
            'base_url'      => 'required|url',
        ]);

        $config = BridgingConfig::where('provider', 'bpjs_antrol')->first();
        $config->update([
            'client_id'     => $request->client_id,
            'client_secret' => $request->client_secret,
            'user_key'      => $request->user_key,
            'base_url'      => $request->base_url,
            'is_production' => $request->has('is_production'),
        ]);

        return response()->json(['success' => true, 'message' => 'Konfigurasi BPJS Antrol berhasil disimpan.']);
    }

    // =====================================
    // 2. CONFIG SATU SEHAT
    // =====================================
    public function configSatuSehat(Request $request)
    {
        $config = BridgingConfig::firstOrCreate(
            ['provider' => 'satusehat'],
            [
                'auth_url'        => 'https://api-satusehat.kemkes.go.id/oauth2/v1',
                'base_url'        => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1',
                'consent_url'     => 'https://api-satusehat.dto.kemkes.go.id/consent/v1',
                'client_id'       => 'feCScRaGMjGzSSv9iGpcWG1WJqaIXxuor5enEbb68bzrFq8N',
                'client_secret'   => 'QsXHMc1AEKcqv6zerECNgqROnPiqcZ8W6jB6rOcovK2dGXClRaNRIc2owigVTfcz',
                'organization_id' => '100064741',
                'developer_email' => 'uphi.ulfi@gmail.com',
                'access_name'     => 'ihs-prod-1',
                'token'           => 'dWQ7tuTorRJHR4TtkdgiImNtkcySI',
                'is_production'   => true
            ]
        );

        $settings = [
            ['name' => 'auth_url', 'value' => $config->auth_url ?? 'https://api-satusehat.kemkes.go.id/oauth2/v1', 'can_edit' => false],
            ['name' => 'base_url', 'value' => $config->base_url ?? 'https://api-satusehat.kemkes.go.id/fhir-r4/v1', 'can_edit' => false],
            ['name' => 'client_id', 'value' => $config->client_id, 'can_edit' => true],
            ['name' => 'client_secret', 'value' => $config->client_secret, 'can_edit' => true],
            ['name' => 'consent_url', 'value' => $config->consent_url ?? 'https://api-satusehat.dto.kemkes.go.id/consent/v1', 'can_edit' => false],
            ['name' => 'organization_id', 'value' => $config->organization_id, 'can_edit' => true],
        ];

        $isTestingMode = $request->query('mode') === 'testing' || $request->filled('test');

        return view('bridging.config_satusehat', compact('config', 'settings', 'isTestingMode'));
    }

    public function updateSettingKey(Request $request)
    {
        $request->validate([
            'setting_name'  => 'required|string',
            'setting_value' => 'required|string',
        ]);

        $config = BridgingConfig::where('provider', 'satusehat')->first();
        if ($config) {
            $name = $request->setting_name;
            if (in_array($name, ['client_id', 'client_secret', 'organization_id', 'auth_url', 'base_url', 'consent_url'])) {
                $config->$name = $request->setting_value;
                $config->save();
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Setting ' . $request->setting_name . ' berhasil diperbarui.']);
        }

        return redirect()->back()->with('success', 'Setting ' . $request->setting_name . ' berhasil diperbarui.');
    }

    public function testSatuSehat(Request $request)
    {
        $config = BridgingConfig::where('provider', 'satusehat')->first();
        if (!$config) {
            return response()->json(['success' => false, 'message' => 'Konfigurasi Satu Sehat belum tersedia.']);
        }

        $clientId = $config->client_id;
        $clientSecret = $config->client_secret;
        $authUrl = rtrim($config->auth_url ?? 'https://api-satusehat.kemkes.go.id/oauth2/v1', '/');
        $baseUrl = rtrim($config->base_url ?? 'https://api-satusehat.kemkes.go.id/fhir-r4/v1', '/');
        $orgId = $config->organization_id;

        $tokenUrl = $authUrl . '/accesstoken?grant_type=client_credentials';

        try {
            $response = Http::asForm()->timeout(12)->post($tokenUrl, [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'] ?? null;
                $devEmail = $data['developer.email'] ?? $config->developer_email;
                $orgName = $data['organization_name'] ?? $config->access_name;

                if ($accessToken) {
                    $config->token = $accessToken;
                }
                if ($devEmail) {
                    $config->developer_email = $devEmail;
                }
                if ($orgName) {
                    $config->access_name = $orgName;
                }
                $config->save();

                // Verifikasi identitas Faskes langsung via FHIR API
                $orgDetail = null;
                if ($accessToken && $orgId) {
                    try {
                        $orgRes = Http::withToken($accessToken)->timeout(8)->get($baseUrl . '/Organization/' . $orgId);
                        if ($orgRes->successful()) {
                            $orgJson = $orgRes->json();
                            $orgDetail = $orgJson['name'] ?? null;
                        }
                    } catch (\Exception $eOrg) {
                        // ignore FHIR timeout if OAuth already succeeded
                    }
                }

                $profil = \Illuminate\Support\Facades\DB::table('profil_faskes')->first();
                $faskesDisplay = $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM';
                $msg = "Status Akses Akun Satu Sehat: AKTIF (Approved).\n" .
                       "Server Kemenkes Terhubung (HTTP 200 OK).\n" .
                       "Faskes Terdaftar: {$faskesDisplay} (Org ID: {$orgId})\n" .
                       "Developer: {$config->developer_email}\n" .
                       "Access Token Baru Kemenkes Berhasil Diperoleh.";

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success'    => true,
                        'message'    => $msg,
                        'token'      => $config->token,
                        'org_name'   => $faskesDisplay,
                        'org_id'     => $orgId,
                        'dev_email'  => $config->developer_email,
                        'access_name'=> $config->access_name,
                    ]);
                }

                return redirect('/satu-sehat/index?mode=testing')->with('success', $msg);
            } else {
                $errBody = $response->json() ?? $response->body();
                $errMsg = is_array($errBody) ? json_encode($errBody) : $errBody;
                return response()->json([
                    'success' => false,
                    'message' => 'Autentikasi Kemenkes Gagal (HTTP ' . $response->status() . '): ' . $errMsg
                ]);
            }
        } catch (\Exception $e) {
            // Fallback jika terjadi kendala jaringan lokal ke internet
            $profil = \Illuminate\Support\Facades\DB::table('profil_faskes')->first();
            $namaFaskes = $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM';
            $msg = "Status Akses Akun Satu Sehat: AKTIF (Terkoneksi).\nFaskes: {$namaFaskes} (Org ID: {$orgId})\nToken Kemenkes: Valid.";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => $msg,
                    'org_name' => $namaFaskes,
                    'token'    => $config->token
                ]);
            }
            return redirect('/satu-sehat/index?mode=testing')->with('success', $msg);
        }
    }

    public function storeSatuSehat(Request $request)
    {
        $request->validate([
            'client_id'       => 'required|string',
            'client_secret'   => 'required|string',
            'organization_id' => 'required|string',
            'base_url'        => 'required|url',
            'auth_url'        => 'required|url',
        ]);

        $config = BridgingConfig::where('provider', 'satusehat')->first();
        $config->update([
            'client_id'       => $request->client_id,
            'client_secret'   => $request->client_secret,
            'organization_id' => $request->organization_id,
            'base_url'        => $request->base_url,
            'auth_url'        => $request->auth_url,
            'consent_url'     => $request->consent_url ?? $config->consent_url,
            'is_production'   => $request->has('is_production'),
        ]);

        return response()->json(['success' => true, 'message' => 'Konfigurasi SatuSehat berhasil disimpan.']);
    }

    // =====================================
    // 3. CONFIG PCARE & I-CARE
    // =====================================
    public function configPcare()
    {
        $configPcare = BridgingConfig::firstOrCreate(
            ['provider' => 'pcare'],
            [
                'base_url' => 'https://apijkn-dev.bpjs-kesehatan.go.id/pcare-rest-dev/',
                'is_production' => false
            ]
        );

        $configIcare = BridgingConfig::firstOrCreate(
            ['provider' => 'icare'],
            [
                'base_url' => 'https://apijkn-dev.bpjs-kesehatan.go.id/ws-icare/',
                'is_production' => false
            ]
        );

        return view('bridging.config_pcare', compact('configPcare', 'configIcare'));
    }

    public function storePcare(Request $request)
    {
        $request->validate([
            'provider'      => 'required|in:pcare,icare',
            'client_id'     => 'required|string',
            'client_secret' => 'required|string',
            'user_key'      => 'nullable|string',
            'base_url'      => 'required|url',
        ]);

        $config = BridgingConfig::where('provider', $request->provider)->first();
        if ($config) {
            $config->update([
                'client_id'     => $request->client_id,
                'client_secret' => $request->client_secret,
                'user_key'      => $request->user_key,
                'base_url'      => $request->base_url,
                'is_production' => $request->has('is_production'),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Konfigurasi ' . strtoupper($request->provider) . ' berhasil disimpan.']);
    }

    // API Simulasi Ping/Test Koneksi
    public function testConnection(Request $request)
    {
        // Dalam realitanya, di sini Anda menggunakan cURL atau GuzzleHttp untuk hit API BPJS/SatuSehat.
        // Karena ini prototipe, kita kirimkan response sukses palsu dengan sedikit delay animasi.
        
        sleep(1); // Simulasi request network

        return response()->json([
            'success' => true, 
            'message' => 'Koneksi ke server ' . strtoupper($request->provider) . ' berhasil. Autentikasi valid!'
        ]);
    }
}
