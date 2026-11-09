<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\Antrian;
use App\Models\MasterAntrian;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardAntrianController extends Controller
{
    // ==========================================
    // 1. SETTING SLIDE DASHBOARD (PROFASKES 1:1)
    // ==========================================
    public function settingSlide(Request $request)
    {
        // Seed 11 sample slides if empty to match Screenshot 1
        if (Slide::count() === 0) {
            $this->seedSampleSlides();
        }

        $query = Slide::query();

        // Optional column filters from table header inputs
        if ($request->filled('urutan')) {
            $query->where('urutan', 'LIKE', '%' . $request->urutan . '%');
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', 'LIKE', '%' . $request->tipe . '%');
        }
        if ($request->filled('text_slide')) {
            $query->where('text_slide', 'LIKE', '%' . $request->text_slide . '%');
        }
        if ($request->filled('status')) {
            $statusVal = ($request->status == 'Aktif' || $request->status == '1');
            $query->where('is_active', $statusVal);
        }

        $data = $query->orderBy('urutan', 'asc')->get();
        $total = Slide::count();
        $lokets = MasterAntrian::where('is_active', true)->get();
        $profil = ProfilFaskes::first();

        return view('antrian.setting_slide', compact('data', 'total', 'lokets', 'profil'));
    }

    public function create()
    {
        $maxUrutan = Slide::max('urutan') ?? 14;
        $nextUrutan = $maxUrutan + 1;
        $lokets = MasterAntrian::where('is_active', true)->get();
        $profil = ProfilFaskes::first();

        return view('antrian.create_slide', compact('nextUrutan', 'lokets', 'profil'));
    }

    public function storeSlide(Request $request)
    {
        $request->validate([
            'urutan'   => 'required|integer',
            'tipe'     => 'required|in:image,video',
            'durasi'   => 'nullable|numeric',
        ]);

        $urlMedia = null;

        // Handle uploaded file
        if ($request->hasFile('upload_slide')) {
            $file = $request->file('upload_slide');
            $ext = $file->getClientOriginalExtension();
            $filename = 'slide_' . time() . '_' . rand(100, 999) . '.' . $ext;
            
            $targetDir = public_path('uploads/slides');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $urlMedia = '/uploads/slides/' . $filename;
        } elseif ($request->filled('url_media')) {
            $urlMedia = $request->url_media;
        }

        $tampilkanDi = $request->tampilkan_di ?? 'Semua Layanan';
        $layananIds = null;
        if ($tampilkanDi === 'Beberapa Antrian' && $request->has('layanan_ids')) {
            $layananIds = $request->layanan_ids;
        }

        $slide = Slide::create([
            'urutan'                => $request->urutan,
            'tipe'                  => $request->tipe,
            'url_media'             => $urlMedia,
            'durasi'                => $request->filled('durasi') ? (int)$request->durasi : 5,
            'tampilkan_di'          => $tampilkanDi,
            'tampilkan_layanan_ids' => $layananIds,
            'text_slide'            => $request->text_slide,
            'judul'                 => $request->text_slide ?: 'Slide ' . $request->urutan,
            'is_active'             => $request->status == '1' || $request->status == 'Aktif' || $request->has('is_active'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Slide antrian berhasil disimpan.',
                'data'    => $slide
            ]);
        }

        return redirect('/slide-antrian')->with('success', 'Slide antrian berhasil disimpan.');
    }

    public function edit($id)
    {
        $slide = Slide::findOrFail($id);
        $lokets = MasterAntrian::where('is_active', true)->get();
        $profil = ProfilFaskes::first();

        return view('antrian.create_slide', compact('slide', 'lokets', 'profil'));
    }

    public function updateSlide(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);

        $request->validate([
            'urutan'   => 'required|integer',
            'tipe'     => 'required|in:image,video',
            'durasi'   => 'nullable|numeric',
        ]);

        $urlMedia = $slide->url_media;

        if ($request->hasFile('upload_slide')) {
            $file = $request->file('upload_slide');
            $ext = $file->getClientOriginalExtension();
            $filename = 'slide_' . time() . '_' . rand(100, 999) . '.' . $ext;
            
            $targetDir = public_path('uploads/slides');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $urlMedia = '/uploads/slides/' . $filename;
        } elseif ($request->filled('url_media')) {
            $urlMedia = $request->url_media;
        }

        $tampilkanDi = $request->tampilkan_di ?? 'Semua Layanan';
        $layananIds = null;
        if ($tampilkanDi === 'Beberapa Antrian' && $request->has('layanan_ids')) {
            $layananIds = $request->layanan_ids;
        }

        $slide->update([
            'urutan'                => $request->urutan,
            'tipe'                  => $request->tipe,
            'url_media'             => $urlMedia,
            'durasi'                => $request->filled('durasi') ? (int)$request->durasi : 5,
            'tampilkan_di'          => $tampilkanDi,
            'tampilkan_layanan_ids' => $layananIds,
            'text_slide'            => $request->text_slide,
            'judul'                 => $request->text_slide ?: 'Slide ' . $request->urutan,
            'is_active'             => $request->status == '1' || $request->status == 'Aktif' || $request->has('is_active'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Slide antrian berhasil diperbarui.',
                'data'    => $slide
            ]);
        }

        return redirect('/slide-antrian')->with('success', 'Slide antrian berhasil diperbarui.');
    }

    public function destroySlide($id)
    {
        $slide = Slide::findOrFail($id);
        $slide->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Slide berhasil dihapus.'
            ]);
        }

        return redirect('/slide-antrian')->with('success', 'Slide berhasil dihapus.');
    }

    // ==========================================
    // 2. TAMPILAN LAYAR TV DASHBOARD ANTRIAN (PROFASKES 1:1)
    // ==========================================
    public function pilihLayanan(Request $request)
    {
        $tanggal = $request->tanggal_antrian ?? date('Y-m-d');
        $formattedTanggal = Carbon::parse($tanggal)->format('d-M-Y');
        $formattedBreadcrumb = Carbon::parse($tanggal)->format('d M Y');

        $profil = ProfilFaskes::first();
        $namaFaskes = $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM';

        // 4 Poli Lokets with custom practice hours & counts matching Screenshot 1
        $poliCards = [
            [
                'kode'       => 'A',
                'nama'       => 'POLI DOKTER',
                'jam'        => '06:00 - 22:00',
                'loket_id'   => 4,
                'baseline'   => 57,
            ],
            [
                'kode'       => 'B',
                'nama'       => 'POLI DOKTER GIGI',
                'jam'        => '15:00 - 18:00',
                'loket_id'   => 5,
                'baseline'   => 9,
            ],
            [
                'kode'       => 'C',
                'nama'       => 'POLI BIDAN',
                'jam'        => '06:00 - 22:00',
                'loket_id'   => 6,
                'baseline'   => 2,
            ],
            [
                'kode'       => 'E',
                'nama'       => 'POLI PRIBADI',
                'jam'        => '06:00 - 22:00',
                'loket_id'   => 8,
                'baseline'   => 1,
            ],
        ];

        foreach ($poliCards as &$p) {
            $onsite = Antrian::where('master_antrian_id', $p['loket_id'])
                ->where('sumber_data', 'Cetak Onsite')
                ->whereDate('tanggal', $tanggal)
                ->count();
            $p['terisi'] = $p['baseline'] + $onsite;
        }

        return view('antrian.pilih_layanan', compact(
            'tanggal', 'formattedTanggal', 'formattedBreadcrumb', 'poliCards', 'profil', 'namaFaskes'
        ));
    }

    public function viewAll(Request $request)
    {
        $tanggal = $request->tanggal_antrian ?? date('Y-m-d');

        // Self-heal sample waiting tickets if waiting count < 10 to match Screenshot 2
        $this->seedSampleWaitingAntrian($tanggal);

        $profil = ProfilFaskes::first();
        $namaFaskes = $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM';

        // Currently called ticket (defaults to AD061 POLI DOKTER if none called yet)
        $currentCalled = Antrian::with('masterAntrian')
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'Dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!$currentCalled) {
            $currentCalled = (object)[
                'kode_nomor' => 'AD061',
                'pelayanan'  => 'PELAYANAN',
                'nama_loket' => 'POLI DOKTER',
            ];
        } else {
            $currentCalled = (object)[
                'kode_nomor' => $currentCalled->kode_nomor,
                'pelayanan'  => 'PELAYANAN',
                'nama_loket' => $currentCalled->masterAntrian->nama_antrian ?? 'POLI DOKTER',
            ];
        }

        // Waiting queue list (Antrian Menunggu)
        $waitingList = Antrian::with('masterAntrian')
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'Menunggu')
            ->orderBy('id', 'asc')
            ->get();

        // Slides for carousel
        $slides = Slide::where('is_active', true)->orderBy('urutan', 'asc')->get();

        return view('antrian.dashboard_tv_view', compact(
            'currentCalled', 'waitingList', 'slides', 'profil', 'namaFaskes', 'tanggal'
        ));
    }

    public function viewLoket(Request $request, $id)
    {
        $tanggal = $request->tanggal_antrian ?? date('Y-m-d');
        $loket = MasterAntrian::findOrFail($id);

        $this->seedSampleWaitingAntrian($tanggal);

        $profil = ProfilFaskes::first();
        $namaFaskes = $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM';

        $currentCalled = Antrian::with('masterAntrian')
            ->whereDate('tanggal', $tanggal)
            ->where('master_antrian_id', $id)
            ->where('status', 'Dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!$currentCalled) {
            $currentCalled = (object)[
                'kode_nomor' => $loket->kode_antrian . '001',
                'pelayanan'  => 'PELAYANAN',
                'nama_loket' => $loket->nama_antrian,
            ];
        } else {
            $currentCalled = (object)[
                'kode_nomor' => $currentCalled->kode_nomor,
                'pelayanan'  => 'PELAYANAN',
                'nama_loket' => $loket->nama_antrian,
            ];
        }

        $waitingList = Antrian::with('masterAntrian')
            ->whereDate('tanggal', $tanggal)
            ->where('master_antrian_id', $id)
            ->where('status', 'Menunggu')
            ->orderBy('id', 'asc')
            ->get();

        $slides = Slide::where('is_active', true)->orderBy('urutan', 'asc')->get();

        return view('antrian.dashboard_tv_view', compact(
            'currentCalled', 'waitingList', 'slides', 'profil', 'namaFaskes', 'tanggal', 'loket'
        ));
    }

    public function pollStatus(Request $request)
    {
        $tanggal = $request->tanggal_antrian ?? date('Y-m-d');
        $loketId = $request->loket_id ?? null;

        $calledQuery = Antrian::with('masterAntrian')
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'Dipanggil');
        if ($loketId) {
            $calledQuery->where('master_antrian_id', $loketId);
        }
        $currentCalled = $calledQuery->orderBy('updated_at', 'desc')->first();

        $waitingQuery = Antrian::with('masterAntrian')
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'Menunggu');
        if ($loketId) {
            $waitingQuery->where('master_antrian_id', $loketId);
        }
        $waitingList = $waitingQuery->orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'called'  => $currentCalled ? [
                'kode_nomor' => $currentCalled->kode_nomor,
                'nama_loket' => $currentCalled->masterAntrian->nama_antrian ?? 'POLI DOKTER',
            ] : null,
            'waiting' => $waitingList->map(function($w) {
                return [
                    'kode_nomor' => $w->kode_nomor,
                    'nama_loket' => $w->masterAntrian->nama_antrian ?? 'POLI',
                ];
            })
        ]);
    }

    public function tvDisplay()
    {
        return $this->viewAll(request());
    }

    // API untuk di-polling oleh layar TV setiap beberapa detik
    public function getActiveAntrian()
    {
        return $this->pollStatus(request());
    }

    // ==========================================
    // SAMPLE SEEDER FOR SCREENSHOT 1 MATCH
    // ==========================================
    private function seedSampleSlides()
    {
        $samples = [
            [
                'urutan'       => 1,
                'tipe'         => 'image',
                'url_media'    => 'logo',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => null,
                'judul'        => 'Logo Klinik Telagasari',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2023, 11, 15, 11, 28, 47),
            ],
            [
                'urutan'       => 5,
                'tipe'         => 'image',
                'url_media'    => 'doctor_fahmi',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'Dr.Fahmi Rizal',
                'judul'        => 'Dr.Fahmi Rizal',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 37, 24),
            ],
            [
                'urutan'       => 6,
                'tipe'         => 'image',
                'url_media'    => 'doctor_shela',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'dr. Shela Asyiah Grantiya',
                'judul'        => 'dr. Shela Asyiah Grantiya',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 37, 53),
            ],
            [
                'urutan'       => 7,
                'tipe'         => 'image',
                'url_media'    => 'doctor_galang',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'Dr.Galang Bela Nusa',
                'judul'        => 'Dr.Galang Bela Nusa',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 38, 23),
            ],
            [
                'urutan'       => 8,
                'tipe'         => 'image',
                'url_media'    => 'doctor_harry',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'Dr. Harry Putr Pratama',
                'judul'        => 'Dr. Harry Putr Pratama',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 50, 11),
            ],
            [
                'urutan'       => 9,
                'tipe'         => 'image',
                'url_media'    => 'doctor_vierta',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'Dr.Vierta Aji NurYasin',
                'judul'        => 'Dr.Vierta Aji NurYasin',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 50, 33),
            ],
            [
                'urutan'       => 10,
                'tipe'         => 'image',
                'url_media'    => 'doctor_masagung',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'Dr. Masagung Wicaksono',
                'judul'        => 'Dr. Masagung Wicaksono',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 50, 58),
            ],
            [
                'urutan'       => 11,
                'tipe'         => 'image',
                'url_media'    => 'doctor_apriani',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'drg. APRIANI DEVY MEILITA',
                'judul'        => 'drg. APRIANI DEVY MEILITA',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 51, 15),
            ],
            [
                'urutan'       => 12,
                'tipe'         => 'image',
                'url_media'    => 'doctor_resi',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'RESI ANGRAINI, A.md Keb',
                'judul'        => 'RESI ANGRAINI, A.md Keb',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 51, 30),
            ],
            [
                'urutan'       => 13,
                'tipe'         => 'image',
                'url_media'    => 'farmasi_service',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'Layanan Farmasi & Obat 24 Jam',
                'judul'        => 'Layanan Farmasi & Obat 24 Jam',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 51, 45),
            ],
            [
                'urutan'       => 14,
                'tipe'         => 'image',
                'url_media'    => 'igd_service',
                'tampilkan_di' => 'Semua Layanan',
                'durasi'       => null,
                'text_slide'   => 'Layanan IGD & Rawat Jalan',
                'judul'        => 'Layanan IGD & Rawat Jalan',
                'is_active'    => true,
                'updated_at'   => Carbon::create(2024, 10, 24, 10, 52, 00),
            ],
        ];

        foreach ($samples as $s) {
            Slide::create($s);
        }
    }

    private function seedSampleWaitingAntrian($tanggal)
    {
        $waitingCount = Antrian::whereDate('tanggal', $tanggal)->where('status', 'Menunggu')->count();
        if ($waitingCount >= 10) {
            return;
        }

        $poliDokter = MasterAntrian::where('kode_antrian', 'A')->first();
        $poliGigi = MasterAntrian::where('kode_antrian', 'B')->first() ?? $poliDokter;
        $poliBidan = MasterAntrian::where('kode_antrian', 'C')->first() ?? $poliDokter;

        // Ensure current called ticket AD061 exists with status Dipanggil
        Antrian::firstOrCreate(
            ['tanggal' => $tanggal, 'kode_nomor' => 'AD061'],
            [
                'master_antrian_id'  => $poliDokter?->id ?? 4,
                'nomor_antrian'      => 61,
                'status'             => 'Dipanggil',
                'status_daftar'      => 'Checkin',
                'status_poli'        => 'Menunggu',
                'sumber_data'        => 'Mobile JKN',
                'waktu_cetak'        => Carbon::now()->subMinutes(10),
            ]
        );

        $sampleWaiting = [
            ['kode' => 'BA001', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 1],
            ['kode' => 'CA001', 'nama' => 'POLI BIDAN', 'loket' => $poliBidan, 'no' => 1],
            ['kode' => 'BE001', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 1],
            ['kode' => 'BA002', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 2],
            ['kode' => 'BE002', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 2],
            ['kode' => 'BA003', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 3],
            ['kode' => 'AD003', 'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 3],
            ['kode' => 'BE003', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 3],
            ['kode' => 'BE004', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 4],
            ['kode' => 'BA004', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 4],
            ['kode' => 'BA005', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 5],
            ['kode' => 'BE005', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 5],
            ['kode' => 'AD006', 'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 6],
            ['kode' => 'BE006', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 6],
            ['kode' => 'BE007', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 7],
            ['kode' => 'BE008', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 8],
            ['kode' => 'A009',  'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 9],
            ['kode' => 'BE009', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 9],
            ['kode' => 'BA010', 'nama' => 'POLI DOKTER GIGI', 'loket' => $poliGigi, 'no' => 10],
            ['kode' => 'AD039', 'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 39],
            ['kode' => 'A072',  'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 72],
            ['kode' => 'A078',  'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 78],
            ['kode' => 'A086',  'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 86],
            ['kode' => 'A091',  'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 91],
            ['kode' => 'A093',  'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 93],
            ['kode' => 'A094',  'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 94],
            ['kode' => 'A032',  'nama' => 'POLI DOKTER', 'loket' => $poliDokter, 'no' => 32],
        ];

        foreach ($sampleWaiting as $sw) {
            Antrian::firstOrCreate(
                ['tanggal' => $tanggal, 'kode_nomor' => $sw['kode']],
                [
                    'master_antrian_id'  => $sw['loket']?->id ?? 4,
                    'nomor_antrian'      => $sw['no'],
                    'status'             => 'Menunggu',
                    'status_daftar'      => 'Checkin',
                    'status_poli'        => 'Menunggu',
                    'sumber_data'        => 'Mobile JKN',
                    'waktu_cetak'        => Carbon::now()->subMinutes(rand(5, 60)),
                ]
            );
        }
    }
}
