<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IcareUser;
use App\Models\Pendaftaran;
use App\Models\Pasien;

class IcareController extends Controller
{
    public function index()
    {
        return redirect()->route('icare.user.index');
    }

    public function akun(Request $request)
    {
        $users = IcareUser::orderBy('id')->get();
        $total = $users->count();

        return view('icare.akun', [
            'title' => 'Setting Akun User Bridging I-Care',
            'users' => $users,
            'total' => $total,
        ]);
    }

    public function updateAkun(Request $request, $id)
    {
        $request->validate([
            'user_icare' => 'nullable|string|max:100',
            'password_icare' => 'nullable|string|max:100',
        ]);

        $user = IcareUser::findOrFail($id);
        $user->user_icare = $request->user_icare;
        if ($request->filled('password_icare')) {
            $user->password_icare = $request->password_icare;
        }
        $user->diubah_pada = now();
        $user->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Akun i-Care untuk ' . $user->nama_karyawan . ' berhasil diperbarui.',
                'data' => $user,
            ]);
        }

        return redirect()->route('icare.user.index')->with('success', 'Akun i-Care untuk ' . $user->nama_karyawan . ' berhasil diperbarui.');
    }

    public function editAkun($id)
    {
        $user = IcareUser::findOrFail($id);
        return response()->json($user);
    }

    public function cekRiwayatKunjungan(Request $request)
    {
        $noKartu = $request->get('no_kartu');
        $namaPasien = $request->get('nama_pasien');
        $hasil = null;
        $searched = false;

        // Query today's or active date registered patients for the modal selection
        $hasToday = Pendaftaran::whereDate('tanggal_daftar', date('Y-m-d'))->exists();
        $tglActive = $hasToday ? date('Y-m-d') : '2026-10-14';

        $pasienList = Pendaftaran::with(['pasien', 'poliklinik'])
            ->whereDate('tanggal_daftar', $tglActive)
            ->whereHas('pasien', function($q) {
                $q->whereNotNull('no_bpjs')->where('no_bpjs', '!=', '');
            })
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get();

        if ($request->filled('no_kartu')) {
            $searched = true;
            $pasien = Pasien::where('no_bpjs', $noKartu)->first();

            // Fetch visit history for this patient
            $riwayatKunjungan = [];
            if ($pasien) {
                $riwayatKunjungan = Pendaftaran::with(['poliklinik', 'dokter'])
                    ->where('pasien_id', $pasien->id)
                    ->orderBy('tanggal_daftar', 'desc')
                    ->get();
            }

            $hasil = [
                'pasien' => $pasien,
                'no_kartu' => $noKartu,
                'riwayat' => $riwayatKunjungan,
            ];
        }

        return view('icare.cek_riwayat_kunjungan', [
            'title' => 'I-Care : Pencarian Data Riwayat Berobat Pasien',
            'noKartu' => $noKartu,
            'namaPasien' => $namaPasien,
            'searched' => $searched,
            'hasil' => $hasil,
            'pasienList' => $pasienList,
            'tglActive' => $tglActive,
        ]);
    }

    public function apiPasienToday(Request $request)
    {
        $q = $request->get('q');
        $hasToday = Pendaftaran::whereDate('tanggal_daftar', date('Y-m-d'))->exists();
        $tglActive = $hasToday ? date('Y-m-d') : '2026-10-14';

        $query = Pendaftaran::with(['pasien', 'poliklinik'])
            ->whereDate('tanggal_daftar', $tglActive)
            ->whereHas('pasien', function($qp) use ($q) {
                if ($q) {
                    $qp->where(function($qq) use ($q) {
                        $qq->where('nama_pasien', 'like', "%{$q}%")
                           ->orWhere('no_rm', 'like', "%{$q}%")
                           ->orWhere('no_bpjs', 'like', "%{$q}%");
                    });
                }
            });

        $pendaftarans = $query->orderBy('id', 'desc')->limit(30)->get();

        $data = $pendaftarans->map(function($item) {
            return [
                'id' => $item->id,
                'no_rawat' => $item->no_rawat,
                'nama_pasien' => $item->pasien->nama_pasien ?? '-',
                'no_rm' => $item->pasien->no_rm ?? '-',
                'no_bpjs' => $item->pasien->no_bpjs ?: ($item->no_bpjs_asuransi ?: ''),
                'poli' => $item->poliklinik->nama_poli ?? '-',
            ];
        });

        return response()->json($data);
    }

    public function viewDoctorAccount(Request $request)
    {
        $user = IcareUser::where('nama_karyawan', 'like', '%Fahmi%')
            ->orWhere('kode_nik', '3215130508880001')
            ->first() ?? IcareUser::first();

        return view('icare.update_password', [
            'title' => 'Akun i-Care Dokter: ' . ($user->nama_karyawan ?? 'dr. Fahmi Rizal'),
            'user' => $user,
        ]);
    }

    public function storeDoctorPassword(Request $request)
    {
        $id = $request->get('id');
        $user = $id ? IcareUser::findOrFail($id) : (IcareUser::where('nama_karyawan', 'like', '%Fahmi%')->first() ?? IcareUser::first());

        $request->validate([
            'user_icare' => 'nullable|string|max:100',
            'password_icare' => 'nullable|string|max:100',
        ]);

        if ($request->filled('user_icare')) {
            $user->user_icare = $request->user_icare;
        }
        if ($request->filled('password_icare')) {
            $user->password_icare = $request->password_icare;
        }
        $user->diubah_pada = now();
        $user->save();

        return redirect()->route('icare.user.view')->with('success', 'Akun & Password i-Care berhasil diperbarui.');
    }

    public function updatePassword()
    {
        return $this->viewDoctorAccount(request());
    }

    public function storeUpdatePassword(Request $request)
    {
        return $this->storeDoctorPassword($request);
    }
}

