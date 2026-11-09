<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BridgingConfig;
use Illuminate\Http\Request;

class BridgingCheckController extends Controller
{
    public function index()
    {
        $config = BridgingConfig::where('provider', 'bpjs_antrol')->first();
        
        $settings = [
            [
                'no'    => 1,
                'nama'  => 'base_url',
                'nilai' => $config?->base_url ?: 'https://apijkn.bpjs-kesehatan.go.id',
                'desc'  => null,
            ],
            [
                'no'    => 2,
                'nama'  => 'service_name',
                'nilai' => 'antreanfktp',
                'desc'  => null,
            ],
            [
                'no'    => 3,
                'nama'  => 'cid',
                'nilai' => $config?->client_id ?: '22368',
                'desc'  => '(Cons ID sama dengan Cons ID Bridging Pcare)',
            ],
        ];

        $statusAntrol = 'Aktif';

        return view('bridging.cek_akses', compact('settings', 'statusAntrol'));
    }

    public function testAntrol(Request $request)
    {
        // Simulasi pengujian koneksi ke BPJS Antrol
        usleep(400000); // 400ms

        return response()->json([
            'success'   => true,
            'code'      => 200,
            'message'   => 'Koneksi ke Server Bridging Antrol BPJS Berhasil!',
            'status'    => 'Aktif',
            'endpoint'  => 'https://apijkn.bpjs-kesehatan.go.id/antreanfktp',
            'latency'   => rand(120, 280) . ' ms',
            'timestamp' => date('d-M-Y H:i:s'),
            'response'  => [
                'metadata' => [
                    'code'    => 200,
                    'message' => 'Ok'
                ]
            ]
        ]);
    }

    public function runTest(Request $request)
    {
        return $this->testAntrol($request);
    }
}
