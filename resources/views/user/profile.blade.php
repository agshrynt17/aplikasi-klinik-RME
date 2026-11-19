@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Profil Saya - ' . ($user->name ?? 'Pengguna'))

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-normal mb-0" style="color: #333; font-size: 22px;">
            <i class="fas fa-user-circle text-primary me-2"></i> Profil Saya
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 12px; background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Profil Saya</li>
            </ol>
        </nav>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size: 13px; border-radius: 3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size: 13px; border-radius: 3px;">
        <i class="fas fa-exclamation-triangle me-1"></i> Terjadi kesalahan:
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-3">
        <!-- Kolom Kiri: Ringkasan Kartu Profil Pengguna -->
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm border-0 mb-3" style="border-top: 3px solid #00c0ef !important; border-radius: 3px; background-color: #ffffff;">
                <div class="card-body p-4 text-center">
                    <!-- Avatar -->
                    <div class="mb-3 position-relative d-inline-block">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=00a65a&color=fff&size=120&rounded=true" 
                             alt="{{ $user->name }}" 
                             style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success p-2" title="Status Aktif" style="border: 2px solid #fff;">
                            <span class="visually-hidden">Active</span>
                        </span>
                    </div>

                    <h5 class="fw-bold mb-1 text-dark" style="font-size: 18px;">{{ $user->name }}</h5>
                    <p class="text-muted mb-2" style="font-size: 13px;">{{ '@' . ($user->username ?? 'user') }}</p>

                    <div>
                        @if($user->role)
                            <span class="badge bg-primary px-3 py-1 fw-semibold" style="font-size: 12px; border-radius: 20px;">
                                <i class="fas fa-shield-alt me-1"></i> {{ $user->role->name }}
                            </span>
                        @else
                            <span class="badge bg-secondary px-3 py-1" style="font-size: 12px; border-radius: 20px;">
                                Administrator
                            </span>
                        @endif
                    </div>
                </div>

                <div class="border-top px-3 py-3" style="background-color: #fafbfc; font-size: 12.5px;">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted fw-bold" style="width: 120px;">Email</td>
                            <td style="width: 10px;">:</td>
                            <td class="text-dark">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Karyawan</td>
                            <td>:</td>
                            <td class="text-dark">{{ $user->pegawai ? $user->pegawai->nama_pegawai : $user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">NIK</td>
                            <td>:</td>
                            <td class="text-dark">{{ $user->pegawai ? $user->pegawai->nik : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Status Akun</td>
                            <td>:</td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success" style="font-size: 11px; padding: 3px 7px;">Aktif</span>
                                @else
                                    <span class="badge bg-danger" style="font-size: 11px; padding: 3px 7px;">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Terdaftar Sejak</td>
                            <td>:</td>
                            <td class="text-muted">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Login Terakhir</td>
                            <td>:</td>
                            <td class="text-muted">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Edit Data Pribadi & Ganti Password -->
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm border-0 mb-4" style="border-top: 3px solid #f39c12 !important; border-radius: 3px; background-color: #ffffff;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">
                        <i class="fas fa-user-edit text-warning me-2"></i> Pengaturan Data Akun &amp; Password
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="/user/profile" method="POST" id="formUpdateProfile">
                        @csrf

                        <!-- Sub-section 1: Informasi Akun -->
                        <div class="mb-3">
                            <h6 class="fw-bold text-muted pb-1 mb-3" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px dashed #e2e8f0;">
                                <i class="fas fa-id-card me-1"></i> Data Identitas Akun
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label text-dark fw-bold" style="font-size: 12.5px;">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control form-control-sm" style="font-size: 13px;" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="username" class="form-label text-dark fw-bold" style="font-size: 12.5px;">
                                        Username
                                    </label>
                                    <input type="text" id="username" class="form-control form-control-sm bg-light" style="font-size: 13px;" value="{{ $user->username }}" readonly title="Username tidak dapat diubah">
                                    <small class="text-muted" style="font-size: 11px;">Username sistem bersifat permanen.</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label text-dark fw-bold" style="font-size: 12.5px;">
                                        Alamat Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" class="form-control form-control-sm" style="font-size: 13px;" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold" style="font-size: 12.5px;">
                                        Hak Akses (Role)
                                    </label>
                                    <input type="text" class="form-control form-control-sm bg-light" style="font-size: 13px;" value="{{ $user->role ? $user->role->name : 'Administrator' }}" readonly>
                                    <small class="text-muted" style="font-size: 11px;">Hak akses hanya dapat diubah oleh Administrator klinik.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-section 2: Ganti Password -->
                        <div class="mb-4 pt-2">
                            <h6 class="fw-bold text-muted pb-1 mb-2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px dashed #e2e8f0;">
                                <i class="fas fa-key me-1"></i> Ganti Password (Opsional)
                            </h6>
                            <p class="text-muted mb-3" style="font-size: 12px;">
                                Biarkan isian password kosong jika Anda tidak bermaksud mengganti password saat ini.
                            </p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label text-dark fw-bold" style="font-size: 12.5px;">
                                        Password Baru
                                    </label>
                                    <input type="password" name="password" id="password" class="form-control form-control-sm" style="font-size: 13px;" placeholder="Masukkan password baru (min. 4 karakter)">
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label text-dark fw-bold" style="font-size: 12.5px;">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-sm" style="font-size: 13px;" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 pt-2 border-top">
                            <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: #00a65a; border-color: #008d4c; border-radius: 3px; font-size: 13px;">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan Profil
                            </button>
                            <a href="/dashboard" class="btn btn-secondary px-4 py-2" style="border-radius: 3px; font-size: 13px;">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

