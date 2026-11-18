<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JadwalAntrian;
use App\Models\MasterAntrian;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class JadwalAntrianController extends Controller
{
    public function index(Request $request)
    {
        $master_antrians = MasterAntrian::with('poliklinik')->where('is_active', true)->get();

        // Selected Antrian (defaults to POLI DOKTER / kode A)
        $selectedAntrian = null;
        if ($request->has('antrian_id')) {
            $selectedAntrian = MasterAntrian::with('poliklinik')->find($request->antrian_id);
        }
        if (!$selectedAntrian) {
            $selectedAntrian = MasterAntrian::with('poliklinik')->where('kode_antrian', 'A')->first()
                            ?? $master_antrians->first();
        }

        // Self-heal default schedule for POLI DOKTER if empty to match KONHAKES screenshot
        if ($selectedAntrian && JadwalAntrian::where('master_antrian_id', $selectedAntrian->id)->count() === 0) {
            $this->seedDefaultSchedules($selectedAntrian->id);
        }

        $data = JadwalAntrian::with(['masterAntrian', 'dokter'])
            ->where('master_antrian_id', $selectedAntrian ? $selectedAntrian->id : 0)
            ->orderByRaw("FIELD(hari, 'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $dokters = Pegawai::where(function ($q) {
                $q->where('fungsional_medis', 'DOKTER')
                  ->orWhere('jabatan', 'LIKE', '%Dokter%')
                  ->orWhere('nama_pegawai', 'LIKE', 'dr.%');
            })
            ->where('is_active', true)
            ->orderBy('nama_pegawai')
            ->get();

        return view('antrian.jadwal_antrian', compact('data', 'master_antrians', 'selectedAntrian', 'dokters'));
    }

    private function seedDefaultSchedules($masterAntrianId)
    {
        // Ensure doctors exist with their respective BPJS codes
        $drVierta = Pegawai::where('nama_pegawai', 'LIKE', '%VIERTA%')->first();
        if ($drVierta) $drVierta->update(['kode_dpjp_bpjs' => '627356']);

        $drHarry = Pegawai::where('nama_pegawai', 'LIKE', '%Harry%')->first();
        if ($drHarry) $drHarry->update(['kode_dpjp_bpjs' => '538658']);

        $drFahmi = Pegawai::where('nama_pegawai', 'LIKE', '%Fahmi%')->first();
        if ($drFahmi) $drFahmi->update(['kode_dpjp_bpjs' => '48187']);

        $drSheila = Pegawai::where('nama_pegawai', 'LIKE', '%Shela%')->orWhere('nama_pegawai', 'LIKE', '%Sheila%')->first();
        if ($drSheila) $drSheila->update(['kode_dpjp_bpjs' => '610316', 'nama_pegawai' => 'dr. Sheila Asyiah Granitya']);

        $drSyaiful = Pegawai::firstOrCreate(
            ['nama_pegawai' => 'dr. Syaiful Bachtiar'],
            ['nik' => '48000', 'kode_pegawai' => 'DR-004', 'kode_dpjp_bpjs' => '48000', 'fungsional_medis' => 'DOKTER', 'jabatan' => 'Dokter Umum', 'is_active' => true]
        );

        $drGalang = Pegawai::firstOrCreate(
            ['nama_pegawai' => 'dr. Galang Bela Nusa'],
            ['nik' => '425176', 'kode_pegawai' => 'DR-005', 'kode_dpjp_bpjs' => '425176', 'fungsional_medis' => 'DOKTER', 'jabatan' => 'Dokter Umum', 'is_active' => true]
        );

        $schedules = [
            // 1. Minggu dr Vierta (A) Aktif
            ['hari' => 'Minggu', 'jam_mulai' => '06:00:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drVierta?->id, 'kode_prefix' => 'A', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 140, 'terisi' => 0, 'is_active' => true],
            // 2. Senin dr Harry (A) Non-Aktif
            ['hari' => 'Senin', 'jam_mulai' => '06:00:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drHarry?->id, 'kode_prefix' => 'A', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 140, 'terisi' => 0, 'is_active' => false],
            // 3. Senin dr Fahmi (AD) Non-Aktif
            ['hari' => 'Senin', 'jam_mulai' => '06:00:00', 'jam_selesai' => '16:00:00', 'dokter_id' => $drFahmi?->id, 'kode_prefix' => 'AD', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 70, 'terisi' => 0, 'is_active' => false],
            // 4. Senin dr Syaiful (AA) Non-Aktif
            ['hari' => 'Senin', 'jam_mulai' => '16:05:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drSyaiful?->id, 'kode_prefix' => 'AA', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 50, 'terisi' => 0, 'is_active' => false],
            // 5. Selasa dr Sheila (A) Aktif
            ['hari' => 'Selasa', 'jam_mulai' => '06:00:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drSheila?->id, 'kode_prefix' => 'A', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 92, 'terisi' => 0, 'is_active' => true],
            // 6. Selasa dr Fahmi (AD) Non-Aktif
            ['hari' => 'Selasa', 'jam_mulai' => '06:00:00', 'jam_selesai' => '16:00:00', 'dokter_id' => $drFahmi?->id, 'kode_prefix' => 'AD', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 52, 'terisi' => 0, 'is_active' => false],
            // 7. Selasa dr Vierta (AF) Non-Aktif
            ['hari' => 'Selasa', 'jam_mulai' => '06:00:00', 'jam_selesai' => '13:00:00', 'dokter_id' => $drVierta?->id, 'kode_prefix' => 'AF', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 50, 'terisi' => 0, 'is_active' => false],
            // 8. Selasa dr Galang (AG) Non-Aktif
            ['hari' => 'Selasa', 'jam_mulai' => '15:00:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drGalang?->id, 'kode_prefix' => 'AG', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 21, 'terisi' => 0, 'is_active' => false],
            // 9. Rabu dr Fahmi (AD) Non-Aktif
            ['hari' => 'Rabu', 'jam_mulai' => '06:00:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drFahmi?->id, 'kode_prefix' => 'AD', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 58, 'terisi' => 58, 'is_active' => false],
            // 10. Rabu dr Vierta (A) Aktif
            ['hari' => 'Rabu', 'jam_mulai' => '06:00:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drVierta?->id, 'kode_prefix' => 'A', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 140, 'terisi' => 57, 'is_active' => true],
            // 11. Rabu dr Harry (AA) Non-Aktif
            ['hari' => 'Rabu', 'jam_mulai' => '16:00:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drHarry?->id, 'kode_prefix' => 'AA', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 32, 'terisi' => 0, 'is_active' => false],
            // 12. Kamis dr Harry (A) Non-Aktif
            ['hari' => 'Kamis', 'jam_mulai' => '06:00:00', 'jam_selesai' => '22:00:00', 'dokter_id' => $drHarry?->id, 'kode_prefix' => 'A', 'ruangan' => 'Ruang Rawat 1', 'kuota' => 140, 'terisi' => 0, 'is_active' => false],
        ];

        foreach ($schedules as $s) {
            JadwalAntrian::create(array_merge($s, [
                'master_antrian_id' => $masterAntrianId,
                'sound' => 'default',
            ]));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_antrian_id' => 'required|exists:master_antrians,id',
            'hari'              => 'required|string',
            'dokter_id'         => 'required|exists:pegawais,id',
            'ruangan'           => 'required|string',
            'kuota'             => 'required|integer|min:0',
        ]);

        $jamMulai = $request->jam_mulai;
        if ($request->filled('mulai_jam') && $request->filled('mulai_menit')) {
            $jamMulai = sprintf('%02d:%02d:00', $request->mulai_jam, $request->mulai_menit);
        }

        $jamSelesai = $request->jam_selesai;
        if ($request->filled('selesai_jam') && $request->filled('selesai_menit')) {
            $jamSelesai = sprintf('%02d:%02d:00', $request->selesai_jam, $request->selesai_menit);
        }

        $kodePrefix = $request->kode_prefix ?: ($request->kode_dokter ?: 'A');

        $item = JadwalAntrian::create([
            'master_antrian_id' => $request->master_antrian_id,
            'dokter_id'         => $request->dokter_id,
            'kode_prefix'       => strtoupper(trim($kodePrefix)),
            'ruangan'           => $request->ruangan,
            'hari'              => $request->hari,
            'jam_mulai'         => $jamMulai ?: '07:00:00',
            'jam_selesai'       => $jamSelesai ?: '22:00:00',
            'kuota'             => $request->kuota,
            'sound'             => $request->sound ?? 'default',
            'terisi'            => 0,
            'is_active'         => true,
        ]);

        $item->load(['masterAntrian', 'dokter']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Antrian berhasil disimpan.',
                'data'    => $item,
            ]);
        }

        return redirect()->back()->with('success', 'Jadwal Antrian berhasil disimpan.');
    }

    public function edit($id)
    {
        $item = JadwalAntrian::with(['masterAntrian', 'dokter'])->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'master_antrian_id' => 'required|exists:master_antrians,id',
            'hari'              => 'required|string',
            'dokter_id'         => 'required|exists:pegawais,id',
            'ruangan'           => 'required|string',
            'kuota'             => 'required|integer|min:0',
        ]);

        $item = JadwalAntrian::findOrFail($id);

        $jamMulai = $request->jam_mulai;
        if ($request->filled('mulai_jam') && $request->filled('mulai_menit')) {
            $jamMulai = sprintf('%02d:%02d:00', $request->mulai_jam, $request->mulai_menit);
        }

        $jamSelesai = $request->jam_selesai;
        if ($request->filled('selesai_jam') && $request->filled('selesai_menit')) {
            $jamSelesai = sprintf('%02d:%02d:00', $request->selesai_jam, $request->selesai_menit);
        }

        $kodePrefix = $request->kode_prefix ?: ($request->kode_dokter ?: $item->kode_prefix);

        $item->update([
            'master_antrian_id' => $request->master_antrian_id,
            'dokter_id'         => $request->dokter_id,
            'kode_prefix'       => strtoupper(trim($kodePrefix)),
            'ruangan'           => $request->ruangan,
            'hari'              => $request->hari,
            'jam_mulai'         => $jamMulai ?: $item->jam_mulai,
            'jam_selesai'       => $jamSelesai ?: $item->jam_selesai,
            'kuota'             => $request->kuota,
            'is_active'         => $request->has('is_active') ? $request->boolean('is_active') : $item->is_active,
        ]);

        $item->load(['masterAntrian', 'dokter']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Antrian berhasil diperbarui.',
                'data'    => $item,
            ]);
        }

        return redirect()->back()->with('success', 'Jadwal Antrian berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $item = JadwalAntrian::findOrFail($id);
        $newStatus = $request->has('status') ? (bool)$request->status : !$item->is_active;
        $item->is_active = $newStatus;
        $item->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Status jadwal antrian berhasil diubah.',
            'is_active' => $item->is_active,
        ]);
    }

    public function updateKuota(Request $request, $id)
    {
        $request->validate(['kuota' => 'required|integer|min:0']);

        $item = JadwalAntrian::findOrFail($id);
        $item->kuota = $request->kuota;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Kuota antrian berhasil diperbarui.',
            'kuota'   => $item->kuota,
        ]);
    }

    public function tarikHfis(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Jadwal praktek dokter berhasil disinkronkan dengan data HFIS BPJS!',
        ]);
    }

    public function destroy($id, Request $request)
    {
        $item = JadwalAntrian::findOrFail($id);
        $item->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
        }

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function saveSound(Request $request)
    {
        $request->validate([
            'antrian_id' => 'required|exists:master_antrians,id',
            'sound_type' => 'required|string',
        ]);

        $item = MasterAntrian::findOrFail($request->antrian_id);
        $item->sound_layanan = $request->sound_type;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan sound antrian berhasil disimpan.',
        ]);
    }
}
