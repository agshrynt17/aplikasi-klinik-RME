<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Ensure initial data matching KONHAKES users exists.
     */
    private function ensureInitialData()
    {
        if (User::count() === 0) {
            // Seed Admin Klinik first
            $adminPegawai = Pegawai::where('nik', '0')->orWhere('nama_pegawai', 'like', '%Admin Klinik%')->first();
            if (!$adminPegawai) {
                $adminPegawai = Pegawai::create([
                    'kode_pegawai' => 'PEG-000',
                    'nik'          => '0',
                    'nama_pegawai' => 'Admin Klinik',
                    'jabatan'      => 'Administrator',
                    'is_active'    => true,
                ]);
            }

            // Create admin user
            User::create([
                'name'          => 'Admin Klinik',
                'username'      => 'admin',
                'email'         => 'admin_klinik@gmail.com',
                'password'      => Hash::make('password123'),
                'pegawai_id'    => $adminPegawai->id,
                'is_active'     => true,
                'created_at'    => Carbon::parse('2023-01-18 08:30:00'),
                'last_login_at' => Carbon::parse('2026-10-14 00:02:06'),
            ]);

            // Sample KONHAKES user seeds mapped to pegawais
            $userSeeds = [
                [
                    'username'      => 'fahmirizal',
                    'name'          => 'dr. FAHMI RIZAL',
                    'pegawai_name'  => 'dr. Fahmi Rizal',
                    'nik'           => '3215130508880001',
                    'email'         => 'fahmirizaldr@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 09:15:00',
                    'last_login_at' => '2026-10-14 07:55:33',
                ],
                [
                    'username'      => 'NurAeni',
                    'name'          => 'NUR AENI OKTAVIA A md kep',
                    'pegawai_name'  => 'NUR AENI OKTAVIA A md kep',
                    'nik'           => '332902611080002',
                    'email'         => 'nuraeni@kliniktelagasari.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 09:20:00',
                    'last_login_at' => '2026-10-14 04:20:42',
                ],
                [
                    'username'      => 'niken',
                    'name'          => 'NIKEN WIJAYANTI A md kep',
                    'pegawai_name'  => 'NIKEN WIJAYANTI A md kep',
                    'nik'           => '3215255008940015',
                    'email'         => 'nikenwijayanti43@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 09:25:00',
                    'last_login_at' => '2026-10-14 00:01:38',
                ],
                [
                    'username'      => 'RESIANGRAINI',
                    'name'          => 'RESI ANGRAINI, A.md Keb',
                    'pegawai_name'  => 'RESI ANGRAINI, A.md Keb',
                    'nik'           => '1307074511930001',
                    'email'         => 'resianggraini21@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 09:30:00',
                    'last_login_at' => '2026-10-14 06:04:43',
                ],
                [
                    'username'      => 'Siska123',
                    'name'          => 'SISKA SRI REJEKI Amd,Keb',
                    'pegawai_name'  => 'SISKA SRI REJEKI Amd,Keb',
                    'nik'           => '3215255001960002',
                    'email'         => 'siskasrirejeki23@gmail.com',
                    'is_active'     => false,
                    'created_at'    => '2023-06-28 09:35:00',
                    'last_login_at' => '2026-05-27 23:29:53',
                ],
                [
                    'username'      => 'novi',
                    'name'          => 'NOVI GANTINI, A.md Keb',
                    'pegawai_name'  => 'NOVIA GANTINI, A.md Keb',
                    'nik'           => '3215135409960001',
                    'email'         => 'novigantini45@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 09:40:00',
                    'last_login_at' => '2025-05-02 10:00:19',
                ],
                [
                    'username'      => 'lusi',
                    'name'          => 'LUSI FITRIYANI',
                    'pegawai_name'  => 'LUSI FITRIYANI',
                    'nik'           => '3215164601950001',
                    'email'         => 'lusifitriyani12@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-20 09:45:00',
                    'last_login_at' => '2026-10-14 00:22:30',
                ],
                [
                    'username'      => 'sitimae',
                    'name'          => 'SITI MAEMUNAH, A.md Keb',
                    'pegawai_name'  => 'SITI MAEMUNAH, A.md Keb',
                    'nik'           => '3213054209880004',
                    'email'         => 'sitimaemunah05@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 09:50:00',
                    'last_login_at' => '2025-04-07 01:28:23',
                ],
                [
                    'username'      => 'lestari',
                    'name'          => 'LESTARI PUJI RAHAYU A.md.Ak',
                    'pegawai_name'  => 'LESTARI PUJI RAHAYU A.md.Ak',
                    'nik'           => '3215016008980002',
                    'email'         => 'lestaripuji22@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 09:55:00',
                    'last_login_at' => '2026-10-14 00:47:52',
                ],
                [
                    'username'      => 'bambang',
                    'name'          => 'dr. Bambang Hermanto, Sp.Rad',
                    'pegawai_name'  => 'dr. Bambang Hermanto, Sp.Rad',
                    'nik'           => '3201011001850001',
                    'email'         => 'bambang.hermanto@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 10:00:00',
                    'last_login_at' => '2026-10-14 01:15:20',
                ],
                [
                    'username'      => 'anita',
                    'name'          => 'dr. Anita Wijaya',
                    'pegawai_name'  => 'dr. Anita Wijaya',
                    'nik'           => '3201011001870002',
                    'email'         => 'anita.wijaya@gmail.com',
                    'is_active'     => true,
                    'created_at'    => '2023-06-28 10:05:00',
                    'last_login_at' => '2026-10-13 18:40:11',
                ],
            ];

            foreach ($userSeeds as $seed) {
                // Find or create pegawai
                $pegawai = Pegawai::where('nik', $seed['nik'])->orWhere('nama_pegawai', $seed['pegawai_name'])->first();
                if (!$pegawai) {
                    $pegawai = Pegawai::create([
                        'kode_pegawai' => 'PEG-' . rand(100, 999),
                        'nik'          => $seed['nik'],
                        'nama_pegawai' => $seed['pegawai_name'],
                        'jabatan'      => 'Staf Medis / Paramedis',
                        'email'        => $seed['email'],
                        'is_active'    => true,
                    ]);
                }

                User::create([
                    'name'          => $seed['name'],
                    'username'      => $seed['username'],
                    'email'         => $seed['email'],
                    'password'      => Hash::make('password123'),
                    'pegawai_id'    => $pegawai->id,
                    'is_active'     => $seed['is_active'],
                    'created_at'    => Carbon::parse($seed['created_at']),
                    'last_login_at' => Carbon::parse($seed['last_login_at']),
                ]);
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureInitialData();

        $query = User::with('pegawai', 'role');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('username', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhereHas('pegawai', function ($qp) use ($s) {
                      $qp->where('nama_pegawai', 'like', "%{$s}%")
                         ->orWhere('nik', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'Active' || $request->status === '1') {
                $query->where('is_active', true);
            } elseif ($request->status === 'Inactive' || $request->status === '0') {
                $query->where('is_active', false);
            }
        }

        $data = $query->orderBy('id', 'asc')->get();
        $pegawais = Pegawai::orderBy('nama_pegawai', 'asc')->get();
        $roles = Role::all();

        return view('user.index', compact('data', 'pegawais', 'roles'));
    }

    public function create()
    {
        $pegawais = Pegawai::where('is_active', true)->orderBy('nama_pegawai', 'asc')->get();
        $roles = Role::all();
        return view('user.create', compact('pegawais', 'roles'));
    }

    public function store(Request $request)
    {
        if (!$request->filled('name') && $request->filled('pegawai_id')) {
            $peg = Pegawai::find($request->pegawai_id);
            if ($peg) {
                $request->merge(['name' => $peg->nama_pegawai]);
            }
        }

        $request->validate([
            'username'   => 'required|max:100|unique:users,username',
            'name'       => 'required|max:255',
            'email'      => 'required|email|unique:users,email|max:255',
            'password'   => 'required|min:4',
            'pegawai_id' => 'nullable|exists:pegawais,id',
            'is_active'  => 'nullable',
        ]);

        $isActive = $request->has('is_active') ? ($request->is_active == '1' || $request->is_active === 'Active' || $request->is_active === true) : false;

        $user = User::create([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'pegawai_id'    => $request->pegawai_id ?: null,
            'role_id'       => $request->role_id ?: null,
            'is_active'     => $isActive,
            'created_at'    => now(),
            'last_login_at' => null,
        ]);

        $user->load('pegawai', 'role');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan.',
                'data'    => $user,
            ]);
        }

        return redirect('/user/admin/index')->with('success', 'User berhasil ditambahkan.');
    }

    public function profile()
    {
        $user = auth()->user() ?? User::with('pegawai', 'role')->first();
        if (!$user) {
            return redirect('/login');
        }
        $user->load('pegawai', 'role');
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user() ?? User::first();
        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:4|confirmed',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.unique'       => 'Email ini sudah digunakan oleh akun lain.',
            'password.min'       => 'Password minimal harus 4 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $dataToUpdate = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->back()->with('success', 'Profil dan data akun Anda berhasil diperbarui.');
    }

    public function show($id)
    {
        $user = User::with('pegawai', 'role')->findOrFail($id);
        return response()->json($user);
    }

    public function edit($id)
    {
        $user = User::with('pegawai', 'role')->findOrFail($id);
        $pegawais = Pegawai::where('is_active', true)->orderBy('nama_pegawai', 'asc')->get();
        $roles = Role::all();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($user);
        }

        return view('user.edit', compact('user', 'pegawais', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username'   => 'required|max:100|unique:users,username,' . $id,
            'name'       => 'required|max:255',
            'email'      => 'required|email|unique:users,email,' . $id . '|max:255',
            'password'   => 'nullable|min:4',
            'pegawai_id' => 'nullable|exists:pegawais,id',
            'is_active'  => 'nullable',
        ]);

        $user = User::findOrFail($id);
        $isActive = $request->has('is_active') ? ($request->is_active == '1' || $request->is_active === 'Active' || $request->is_active === true) : false;

        $dataToUpdate = [
            'name'       => $request->name,
            'username'   => $request->username,
            'email'      => $request->email,
            'pegawai_id' => $request->pegawai_id ?: null,
            'role_id'    => $request->role_id ?: null,
            'is_active'  => $isActive,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);
        $user->load('pegawai', 'role');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
                'data'    => $user->fresh()->load('pegawai', 'role'),
            ]);
        }

        return redirect('/user/admin/index')->with('success', 'User berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $user->is_active,
                'message' => "Status akun '{$user->username}' berhasil {$statusStr}."
            ]);
        }

        return redirect()->back()->with('success', "Status akun '{$user->username}' berhasil {$statusStr}.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.',
            ]);
        }

        return redirect('/user/admin/index')->with('success', 'User berhasil dihapus.');
    }
}
