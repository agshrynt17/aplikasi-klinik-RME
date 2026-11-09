<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Module permission definitions
     */
    public static function getModuleList()
    {
        return [
            'Setting Awal' => [
                'profil_faskes'        => 'Profil Faskes',
                'master_poliklinik'    => 'Master Poliklinik',
                'lokasi'               => 'Lokasi (Unit, Gedung, Lantai, Ruang)',
                'sdm_pegawai'          => 'SDM / Master Pegawai',
                'master_penjamin'      => 'Master Penjamin',
                'master_tpa'           => 'Master TPA',
                'master_tindakan'      => 'Master Tindakan',
                'master_tarif_tindakan'=> 'Master Tarif Tindakan',
                'manajemen_user'       => 'Manajemen User',
                'setting_hak_akses'    => 'Setting Hak Akses (Role)',
                'akun_pembayaran'      => 'Akun Pembayaran Klaim',
                'setting_lainnya'      => 'Setting Lainnya',
            ],
            'Front Office' => [
                'master_pasien'        => 'Master Pasien',
                'pendaftaran_rajal'    => 'Pendaftaran Rawat Jalan & IGD',
                'antrian'              => 'Antrian Pelayanan',
                'general_consent'      => 'General Consent',
            ],
            'Pelayanan Medis (RME)' => [
                'rekam_medis'          => 'Rekam Medis Elektronik (RME)',
                'asesmen_rajal'        => 'Asesmen Rawat Jalan & Kontrol',
                'asesmen_igd'          => 'Asesmen & Triase IGD',
                'odontogram'           => 'Pemeriksaan Gigi (Odontogram)',
                'kebidanan'            => 'Kebidanan (ANC, INC, PNC, Partograf)',
                'skrining'             => 'Skrining Pasien & MCU',
            ],
            'Penunjang Medis' => [
                'laboratorium'         => 'Laboratorium (Master & Hasil)',
                'radiologi'            => 'Radio-Diagnostik / Radiologi',
            ],
            'Farmasi & Apotek' => [
                'farmasi_resep'        => 'Penerimaan Resep & E-Prescription',
                'penjualan_apotek'     => 'Penjualan Bebas Apotek (POS)',
                'master_obat'          => 'Gudang Farmasi & Master Obat',
            ],
            'Kasir & Billing' => [
                'kasir'                => 'Kasir Pembayaran & Kwitansi',
                'laporan_keuangan'     => 'Laporan Keuangan & Pendapatan',
            ],
            'Bridging & Integrasi' => [
                'bridging_bpjs'        => 'Bridging BPJS (PCare & i-Care)',
                'bridging_satusehat'   => 'Bridging SatuSehat Kemenkes',
            ],
        ];
    }

    /**
     * Ensure default roles exist and sync with users
     */
    private function ensureInitialRoles()
    {
        if (Role::count() === 0) {
            $allModules = [];
            foreach (self::getModuleList() as $group => $items) {
                foreach ($items as $k => $v) {
                    $allModules[] = $k;
                }
            }

            $rolesData = [
                [
                    'name'        => 'Administrator',
                    'permissions' => $allModules,
                ],
                [
                    'name'        => 'Dokter',
                    'permissions' => [
                        'master_pasien', 'rekam_medis', 'asesmen_rajal', 'asesmen_igd', 
                        'odontogram', 'laboratorium', 'radiologi', 'farmasi_resep', 'skrining',
                        'bridging_bpjs', 'bridging_satusehat'
                    ],
                ],
                [
                    'name'        => 'Perawat',
                    'permissions' => [
                        'master_pasien', 'pendaftaran_rajal', 'antrian', 'rekam_medis', 
                        'asesmen_rajal', 'asesmen_igd', 'skrining'
                    ],
                ],
                [
                    'name'        => 'Bidan',
                    'permissions' => [
                        'master_pasien', 'pendaftaran_rajal', 'antrian', 'rekam_medis', 
                        'kebidanan', 'skrining'
                    ],
                ],
                [
                    'name'        => 'Front Office / Pendaftaran',
                    'permissions' => [
                        'master_pasien', 'pendaftaran_rajal', 'antrian', 'general_consent'
                    ],
                ],
                [
                    'name'        => 'Kasir / Billing',
                    'permissions' => [
                        'kasir', 'laporan_keuangan', 'akun_pembayaran'
                    ],
                ],
                [
                    'name'        => 'Farmasi / Apoteker',
                    'permissions' => [
                        'farmasi_resep', 'penjualan_apotek', 'master_obat'
                    ],
                ],
                [
                    'name'        => 'Analis Laboratorium',
                    'permissions' => [
                        'laboratorium'
                    ],
                ],
                [
                    'name'        => 'Radiografer',
                    'permissions' => [
                        'radiologi'
                    ],
                ],
            ];

            foreach ($rolesData as $rd) {
                Role::create($rd);
            }

            // Sync existing users with their appropriate role
            $adminRole = Role::where('name', 'Administrator')->first();
            $dokterRole = Role::where('name', 'Dokter')->first();
            $perawatRole = Role::where('name', 'Perawat')->first();
            $bidanRole = Role::where('name', 'Bidan')->first();
            $foRole = Role::where('name', 'Front Office / Pendaftaran')->first();
            $labRole = Role::where('name', 'Analis Laboratorium')->first();

            if ($adminRole) {
                User::where('username', 'admin')->orWhere('name', 'like', '%Admin%')->update(['role_id' => $adminRole->id]);
            }
            if ($dokterRole) {
                User::whereIn('username', ['fahmirizal', 'bambang', 'anita'])
                    ->orWhere('name', 'like', 'dr.%')
                    ->update(['role_id' => $dokterRole->id]);
            }
            if ($perawatRole) {
                User::whereIn('username', ['NurAeni', 'niken'])
                    ->orWhere('name', 'like', '%md kep%')
                    ->update(['role_id' => $perawatRole->id]);
            }
            if ($bidanRole) {
                User::whereIn('username', ['RESIANGRAINI', 'Siska123', 'novi', 'sitimae'])
                    ->orWhere('name', 'like', '%md Keb%')
                    ->update(['role_id' => $bidanRole->id]);
            }
            if ($labRole) {
                User::where('username', 'lestari')
                    ->orWhere('name', 'like', '%md.Ak%')
                    ->update(['role_id' => $labRole->id]);
            }
            if ($foRole) {
                User::where('username', 'lusi')->update(['role_id' => $foRole->id]);
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureInitialRoles();

        $query = Role::withCount('users');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%");
        }

        $data = $query->orderBy('id', 'asc')->get();
        $moduleGroups = self::getModuleList();

        return view('user.role', compact('data', 'moduleGroups'));
    }

    public function create()
    {
        $moduleGroups = self::getModuleList();
        return view('user.role_create', compact('moduleGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|unique:roles,name|max:255',
            'permissions' => 'nullable|array',
        ]);

        $item = Role::create([
            'name'        => $request->name,
            'permissions' => $request->permissions ?? [],
        ]);

        $item->loadCount('users');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hak Akses (Role) berhasil ditambahkan.',
                'data'    => $item,
            ]);
        }

        return redirect('/setting-role')->with('success', 'Hak Akses (Role) berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = Role::with(['users:id,name,username,email,role_id'])->withCount('users')->findOrFail($id);
        return response()->json($item);
    }

    public function edit($id)
    {
        $item = Role::withCount('users')->findOrFail($id);
        $moduleGroups = self::getModuleList();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($item);
        }

        return view('user.role_edit', compact('item', 'moduleGroups'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|unique:roles,name,' . $id . '|max:255',
            'permissions' => 'nullable|array',
        ]);

        $item = Role::findOrFail($id);
        $item->update([
            'name'        => $request->name,
            'permissions' => $request->permissions ?? [],
        ]);

        $item->loadCount('users');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hak Akses (Role) berhasil diperbarui.',
                'data'    => $item->fresh()->loadCount('users'),
            ]);
        }

        return redirect('/setting-role')->with('success', 'Hak Akses (Role) berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Role::findOrFail($id);
        if ($item->users()->count() > 0) {
            // Unlink users instead of strict blocking if requested, or notify
            User::where('role_id', $id)->update(['role_id' => null]);
        }
        $item->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hak Akses (Role) berhasil dihapus.',
            ]);
        }

        return redirect('/setting-role')->with('success', 'Hak Akses (Role) berhasil dihapus.');
    }
}
