<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PasienController extends Controller
{
    /**
     * Tampilan utama Master Data Pasien (Screenshot 1)
     */
    public function index(Request $request)
    {
        $query = Pasien::query();

        // Filter Pencarian
        if ($request->filled('kode_rm')) {
            $query->where('no_rm', 'like', '%' . trim($request->kode_rm) . '%');
        }
        if ($request->filled('nama_pasien')) {
            $query->where('nama_pasien', 'like', '%' . trim($request->nama_pasien) . '%');
        }
        if ($request->filled('nik')) {
            $query->where('nik', 'like', '%' . trim($request->nik) . '%');
        }
        if ($request->filled('no_bpjs')) {
            $query->where('no_bpjs', 'like', '%' . trim($request->no_bpjs) . '%');
        }
        if ($request->filled('no_hp')) {
            $query->where('no_hp', 'like', '%' . trim($request->no_hp) . '%');
        }
        if ($request->filled('alamat')) {
            $query->where('alamat', 'like', '%' . trim($request->alamat) . '%');
        }
        if ($request->filled('tanggal_lahir')) {
            $query->whereDate('tanggal_lahir', $request->tanggal_lahir);
        }
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        // General search keyword (fallback)
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('no_rm', 'like', "%{$keyword}%")
                  ->orWhere('nama_pasien', 'like', "%{$keyword}%")
                  ->orWhere('nik', 'like', "%{$keyword}%")
                  ->orWhere('no_bpjs', 'like', "%{$keyword}%")
                  ->orWhere('no_hp', 'like', "%{$keyword}%")
                  ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        }

        // Sorting & Pagination
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        if (!in_array($sortField, ['no_rm', 'nama_pasien', 'tanggal_lahir', 'created_at'])) {
            $sortField = 'created_at';
        }

        $pasiens = $query->orderBy($sortField, $sortDirection)->paginate(20)->withQueryString();

        // AJAX request for partial table
        if ($request->ajax() && $request->has('ajax_table')) {
            return view('front_office.partials.table_pasien', compact('pasiens'));
        }

        return view('master_pasien', compact('pasiens'));
    }

    /**
     * Halaman Tambah Pasien (Screenshot 3)
     */
    public function create(Request $request)
    {
        $ibu = null;
        $kode_rm_ibu = $request->get('kode_rm_ibus');
        if ($kode_rm_ibu) {
            $ibu = Pasien::where('no_rm', $kode_rm_ibu)->first();
        }

        $next_no_rm = $this->generateNextNoRm();

        // PCare prefill if available
        $pcare_data = null;
        if ($request->filled('from_pcare')) {
            $pcare_data = [
                'nik'           => $request->get('nik'),
                'no_bpjs'       => $request->get('no_bpjs'),
                'nama_pasien'   => $request->get('nama_pasien'),
                'jenis_kelamin' => $request->get('jenis_kelamin', 'L'),
                'tanggal_lahir' => $request->get('tanggal_lahir'),
                'alamat'        => $request->get('alamat'),
                'no_hp'         => $request->get('no_hp'),
            ];
        }

        return view('tambah_pasien', compact('ibu', 'next_no_rm', 'pcare_data'));
    }

    /**
     * Simpan Data Pasien Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien'   => 'required|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'nik'           => 'nullable|max:25',
            'no_bpjs'       => 'nullable|max:30',
        ]);

        $no_rm = $request->no_rm;
        if (empty($no_rm)) {
            $no_rm = $this->generateNextNoRm();
        }

        // Format is_active
        $isActive = $request->has('is_active') ? ($request->is_active == '1' || $request->is_active == 'on') : true;
        if ($request->filled('status_pasien')) {
            $isActive = $request->status_pasien === 'aktif';
        }

        $isDomisiliSama = $request->has('is_domisili_sama') ? 1 : 0;
        $isMeninggal = $request->has('is_meninggal') ? 1 : 0;

        $data = $request->except(['_token', 'is_active', 'is_domisili_sama', 'is_meninggal']);
        $data['no_rm'] = strtoupper(trim($no_rm));
        $data['is_active'] = $isActive;
        $data['is_domisili_sama'] = $isDomisiliSama;
        $data['is_meninggal'] = $isMeninggal;

        // If domisili sama, copy identitas address
        if ($isDomisiliSama) {
            $data['alamat_domisili'] = $data['alamat'] ?? null;
            $data['rt_domisili'] = $data['rt'] ?? null;
            $data['rw_domisili'] = $data['rw'] ?? null;
            $data['provinsi_domisili'] = $data['provinsi'] ?? null;
            $data['kabupaten_domisili'] = $data['kabupaten'] ?? null;
            $data['kecamatan_domisili'] = $data['kecamatan'] ?? null;
            $data['desa_domisili'] = $data['desa'] ?? null;
            $data['kode_pos_domisili'] = $data['kode_pos'] ?? null;
        }

        // If not meninggal, clear tanggal_kematian
        if (!$isMeninggal) {
            $data['tanggal_kematian'] = null;
        }

        // Set default faskes bpjs if empty
        if (empty($data['kode_faskes_bpjs'])) {
            $data['kode_faskes_bpjs'] = '0115B064';
            $data['nama_faskes_bpjs'] = 'KLINIK TELAGASARI 24 JAM';
        }

        $pasien = Pasien::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Pasien berhasil disimpan.',
                'data'    => $pasien,
            ]);
        }

        return redirect('/master-pasien')->with('success', 'Data Pasien ' . $pasien->nama_pasien . ' berhasil ditambahkan.');
    }

    /**
     * Halaman / Data Edit Pasien
     */
    public function edit(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($pasien);
        }

        $ibu = null;
        if ($pasien->kode_rm_ibu) {
            $ibu = Pasien::where('no_rm', $pasien->kode_rm_ibu)->first();
        }

        return view('tambah_pasien', [
            'pasien'     => $pasien,
            'ibu'        => $ibu,
            'next_no_rm' => $pasien->no_rm,
            'is_edit'    => true,
        ]);
    }

    /**
     * Update Data Pasien
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pasien'   => 'required|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'nik'           => 'nullable|max:25',
            'no_bpjs'       => 'nullable|max:30',
            'no_rm'         => 'required|max:30',
        ]);

        $pasien = Pasien::findOrFail($id);

        $isActive = $request->has('is_active') ? ($request->is_active == '1' || $request->is_active == 'on') : true;
        if ($request->filled('status_pasien')) {
            $isActive = $request->status_pasien === 'aktif';
        }

        $isDomisiliSama = $request->has('is_domisili_sama') ? 1 : 0;
        $isMeninggal = $request->has('is_meninggal') ? 1 : 0;

        $data = $request->except(['_token', '_method', 'is_active', 'is_domisili_sama', 'is_meninggal']);
        $data['no_rm'] = strtoupper(trim($request->no_rm));
        $data['is_active'] = $isActive;
        $data['is_domisili_sama'] = $isDomisiliSama;
        $data['is_meninggal'] = $isMeninggal;

        if ($isDomisiliSama) {
            $data['alamat_domisili'] = $data['alamat'] ?? null;
            $data['rt_domisili'] = $data['rt'] ?? null;
            $data['rw_domisili'] = $data['rw'] ?? null;
            $data['provinsi_domisili'] = $data['provinsi'] ?? null;
            $data['kabupaten_domisili'] = $data['kabupaten'] ?? null;
            $data['kecamatan_domisili'] = $data['kecamatan'] ?? null;
            $data['desa_domisili'] = $data['desa'] ?? null;
            $data['kode_pos_domisili'] = $data['kode_pos'] ?? null;
        }

        if (!$isMeninggal) {
            $data['tanggal_kematian'] = null;
        }

        $pasien->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Pasien berhasil diperbarui.',
                'data'    => $pasien->fresh(),
            ]);
        }

        return redirect('/master-pasien')->with('success', 'Data Pasien ' . $pasien->nama_pasien . ' berhasil diperbarui.');
    }

    /**
     * Hapus Pasien
     */
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $nama = $pasien->nama_pasien;
        $pasien->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Pasien ' . $nama . ' berhasil dihapus.',
        ]);
    }

    /**
     * Halaman Pilih Data Ibu Dari Bayi (Screenshot 2)
     */
    public function searchIbu(Request $request)
    {
        $query = Pasien::where('jenis_kelamin', 'P');

        if ($request->filled('kode_rm')) {
            $query->where('no_rm', 'like', '%' . trim($request->kode_rm) . '%');
        }
        if ($request->filled('nama_pasien')) {
            $query->where('nama_pasien', 'like', '%' . trim($request->nama_pasien) . '%');
        }
        if ($request->filled('nik')) {
            $query->where('nik', 'like', '%' . trim($request->nik) . '%');
        }
        if ($request->filled('no_bpjs')) {
            $query->where('no_bpjs', 'like', '%' . trim($request->no_bpjs) . '%');
        }
        if ($request->filled('no_hp')) {
            $query->where('no_hp', 'like', '%' . trim($request->no_hp) . '%');
        }
        if ($request->filled('alamat')) {
            $query->where('alamat', 'like', '%' . trim($request->alamat) . '%');
        }
        if ($request->filled('tanggal_lahir')) {
            $query->whereDate('tanggal_lahir', $request->tanggal_lahir);
        }

        $sortField = $request->get('sort', 'nama_pasien');
        $sortDirection = $request->get('direction', 'asc');

        $ibus = $query->orderBy($sortField, $sortDirection)->paginate(20)->withQueryString();

        return view('front_office.search_ibu', compact('ibus'));
    }

    /**
     * Cari Peserta Dari PCare (Screenshot 4)
     */
    public function checkPcare(Request $request)
    {
        $jenis = $request->get('jenis', 'kis'); // 'nik' or 'kis'
        $nomor = trim($request->get('nomor', ''));

        if (empty($nomor)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nomor ' . ($jenis === 'nik' ? 'NIK' : 'KIS') . '.',
            ], 422);
        }

        // 1. Cek di database lokal dulu
        $local = Pasien::query();
        if ($jenis === 'nik') {
            $local->where('nik', $nomor);
        } else {
            $local->where('no_bpjs', $nomor);
        }
        $existing = $local->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'source'  => 'lokal',
                'message' => 'Pasien sudah terdaftar di sistem dengan No. RM: ' . $existing->no_rm,
                'data'    => $existing,
            ]);
        }

        // 2. Simulasi pencarian PCare BPJS yang realistis
        $fakerNama = [
            'SITI AISYAH NURJANAH', 'DEWI ANGGRAENI', 'RINA ROSMALA', 
            'WAWAN HERMAWAN', 'DUDI SUPRIATNA', 'ASEP KURNIAWAN'
        ];
        $namaRandom = $fakerNama[array_rand($fakerNama)];
        $jkRandom = (str_contains($namaRandom, 'SITI') || str_contains($namaRandom, 'DEWI') || str_contains($namaRandom, 'RINA')) ? 'P' : 'L';

        $mockData = [
            'nik'              => $jenis === 'nik' ? $nomor : '3215' . rand(100000000000, 999999999999),
            'no_bpjs'          => $jenis === 'kis' ? $nomor : '000' . rand(1000000000, 9999999999),
            'nama_pasien'      => $namaRandom,
            'jenis_kelamin'    => $jkRandom,
            'tanggal_lahir'    => Carbon::now()->subYears(rand(20, 55))->subDays(rand(10, 300))->format('Y-m-d'),
            'alamat'           => 'DUSUN KRAJAN RT 02/01',
            'no_hp'            => '08' . rand(1111111111, 9999999999),
            'provinsi'         => 'JAWA BARAT',
            'kabupaten'        => 'KAB. KARAWANG',
            'kecamatan'        => 'TELAGASARI',
            'desa'             => 'TELAGASARI',
            'kode_faskes_bpjs' => '0115B064',
            'nama_faskes_bpjs' => 'KLINIK TELAGASARI 24 JAM',
            'status_peserta'   => 'AKTIF',
        ];

        return response()->json([
            'success' => true,
            'source'  => 'pcare',
            'message' => 'Peserta BPJS PCare ditemukan.',
            'data'    => $mockData,
        ]);
    }

    /**
     * Download / Export Data CSV
     */
    public function exportCsv()
    {
        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=data_pasien_' . date('Ymd_His') . '.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'No. RM', 'Nama Pasien', 'NIK', 'No. KIS/BPJS', 'Jenis Kelamin',
            'Tempat Lahir', 'Tanggal Lahir', 'No. HP', 'Alamat', 'RT', 'RW',
            'Desa', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Status Pasien', 'ID Satu Sehat'
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            Pasien::chunk(200, function ($pasiens) use ($file) {
                foreach ($pasiens as $p) {
                    fputcsv($file, [
                        $p->no_rm,
                        $p->nama_pasien,
                        $p->nik ? "'" . $p->nik : '-',
                        $p->no_bpjs ? "'" . $p->no_bpjs : '-',
                        $p->jenis_kelamin,
                        $p->tempat_lahir ?? '-',
                        $p->tanggal_lahir ? $p->tanggal_lahir->format('d/m/Y') : '-',
                        $p->no_hp ?? '-',
                        $p->alamat ?? '-',
                        $p->rt ?? '-',
                        $p->rw ?? '-',
                        $p->desa ?? '-',
                        $p->kecamatan ?? '-',
                        $p->kabupaten ?? '-',
                        $p->provinsi ?? '-',
                        $p->is_active ? 'Aktif' : 'Non Aktif',
                        $p->kode_ihs_satusehat ?? '-',
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Import Data Dari CSV
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');

        $count = 0;
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if (empty($row[0]) && empty($row[1])) continue;

            $no_rm = trim($row[0] ?? '');
            if (empty($no_rm)) {
                $no_rm = $this->generateNextNoRm();
            }

            Pasien::updateOrCreate(
                ['no_rm' => $no_rm],
                [
                    'nama_pasien'   => trim($row[1] ?? 'Pasien Import'),
                    'nik'           => ltrim(trim($row[2] ?? ''), "'"),
                    'no_bpjs'       => ltrim(trim($row[3] ?? ''), "'"),
                    'jenis_kelamin' => in_array(strtoupper(trim($row[4] ?? '')), ['L', 'P']) ? strtoupper(trim($row[4])) : 'L',
                    'alamat'        => trim($row[8] ?? ''),
                    'no_hp'         => trim($row[7] ?? ''),
                    'is_active'     => true,
                ]
            );
            $count++;
        }
        fclose($handle);

        return redirect('/master-pasien')->with('success', "Berhasil mengimpor {$count} data pasien.");
    }

    /**
     * Helper: Generate Next No RM format YYYY-XXXXXX
     */
    private function generateNextNoRm(): string
    {
        $year = date('Y');
        $prefix = $year . '-';

        $last = Pasien::where('no_rm', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(no_rm) DESC, no_rm DESC')
            ->first();

        if ($last) {
            $parts = explode('-', $last->no_rm);
            $num = intval(end($parts)) + 1;
        } else {
            $num = 1;
        }

        return $prefix . str_pad($num, 6, '0', STR_PAD_LEFT);
    }
}
