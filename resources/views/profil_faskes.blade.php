@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Profil Faskes - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="profil-faskes-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">Profil Faskes</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Profil Faskes</li>
            </ol>
        </nav>
    </div>

    <!-- Blue Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #00c0ef;"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size: 13px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Main Profile Card -->
    <div class="card border rounded-2 shadow-none bg-white p-3 p-md-4">
        <div class="row g-4 align-items-start">
            <!-- Left Column: Logo, Clinic Name, Ubah Button -->
            <div class="col-12 col-md-4 col-lg-3">
                <div class="p-3 text-center border rounded-2 bg-white" style="border-color: #e2e8f0 !important;">
                    <div class="clinic-logo-circle mx-auto mb-3">
                        @if(!empty($profil->logo) && file_exists(public_path($profil->logo)))
                            <img src="{{ asset($profil->logo) }}" alt="Logo Faskes" class="img-fluid" style="max-height: 100px; object-fit: contain;">
                        @else
                            <!-- Default Clinic SVG/CSS Icon -->
                            <div style="position: relative; width: 60px; height: 60px;">
                                <i class="fas fa-leaf" style="color: #61b15a; font-size: 38px; position: absolute; bottom: 0; left: 0; transform: rotate(-45deg);"></i>
                                <i class="fas fa-leaf" style="color: #5c3c92; font-size: 38px; position: absolute; bottom: 5px; left: -10px; transform: rotate(-90deg);"></i>
                                <i class="fas fa-leaf" style="color: #3b5998; font-size: 50px; position: absolute; top: -5px; right: 0; transform: rotate(15deg);"></i>
                                <i class="fas fa-plus" style="color: #89cff0; font-size: 24px; position: absolute; top: 10px; right: 8px;"></i>
                            </div>
                        @endif
                    </div>

                    <h6 class="fw-bold text-dark mb-3" style="font-size: 1.05rem; letter-spacing: 0.3px;">
                        {{ $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}
                    </h6>

                    <button type="button" class="btn text-white w-100 fw-semibold py-2 rounded-1 btn-ubah-profil" data-bs-toggle="modal" data-bs-target="#modalUbahProfil">
                        <i class="fas fa-edit me-1"></i> Ubah
                    </button>
                </div>
            </div>

            <!-- Right Column: Details Table -->
            <div class="col-12 col-md-8 col-lg-9">
                <div class="table-responsive rounded-2 border" style="border-color: #edf2f7 !important;">
                    <table class="table table-striped table-bordered mb-0 profile-detail-table">
                        <tbody>
                            <tr>
                                <th style="width: 250px;">Nama Faskes</th>
                                <td>{{ $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Faskes</th>
                                <td>{{ $profil->jenis_faskes ?? 'Faskes Rawat Jalan' }}</td>
                            </tr>
                            <tr>
                                <th>Tingkat Pelayanan</th>
                                <td>{{ $profil->tingkat_pelayanan ?? 'Tingkat Pertama (FKTP)' }}</td>
                            </tr>
                            <tr>
                                <th>No.Surat Izin Operasional</th>
                                <td>
                                    @if(!empty($profil->no_izin_operasional))
                                        {{ $profil->no_izin_operasional }}
                                    @else
                                        <span class="text-danger fst-italic">(belum diset)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $profil->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat' }}</td>
                            </tr>
                            <tr>
                                <th>No Telp</th>
                                <td>{{ $profil->telepon ?? '(0264) 8303763' }}</td>
                            </tr>
                            <tr>
                                <th>No.Surat Izin Apotek</th>
                                <td>{{ $profil->no_izin_apotek ?? '123/019/X/KLINIK-OSS/XXX' }}</td>
                            </tr>
                            <tr>
                                <th>Penanggung Jawab Apotek</th>
                                <td>{{ $profil->penanggung_jawab_apotek ?? 'apt. Fariddon Ricky Wahyudi, S.Farm' }}</td>
                            </tr>
                            <tr>
                                <th>Kerjasama BPJS</th>
                                <td>
                                    <span class="badge" style="background-color: #00a65a; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 3px;">
                                        {{ $profil->kerjasama_bpjs ?? 'Ya' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Profil Faskes -->
<div class="modal fade" id="modalUbahProfil" tabindex="-1" aria-labelledby="modalUbahProfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3 px-4 text-white" style="background-color: #00a65a;">
                <h6 class="modal-title fw-bold" id="modalUbahProfilLabel">
                    <i class="fas fa-clinic-medical me-2"></i> Ubah Profil Fasilitas Kesehatan
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/profil-faskes" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4" style="font-size: 13px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Nama Fasilitas Kesehatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="nama_faskes" value="{{ $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Jenis Faskes</label>
                            <select class="form-select form-select-sm" name="jenis_faskes">
                                <option value="Faskes Rawat Jalan" {{ ($profil->jenis_faskes ?? '') == 'Faskes Rawat Jalan' ? 'selected' : '' }}>Faskes Rawat Jalan</option>
                                <option value="Faskes Rawat Inap" {{ ($profil->jenis_faskes ?? '') == 'Faskes Rawat Inap' ? 'selected' : '' }}>Faskes Rawat Inap</option>
                                <option value="Klinik Pratama" {{ ($profil->jenis_faskes ?? '') == 'Klinik Pratama' ? 'selected' : '' }}>Klinik Pratama</option>
                                <option value="Klinik Utama" {{ ($profil->jenis_faskes ?? '') == 'Klinik Utama' ? 'selected' : '' }}>Klinik Utama</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Tingkat Pelayanan</label>
                            <select class="form-select form-select-sm" name="tingkat_pelayanan">
                                <option value="Tingkat Pertama (FKTP)" {{ ($profil->tingkat_pelayanan ?? '') == 'Tingkat Pertama (FKTP)' ? 'selected' : '' }}>Tingkat Pertama (FKTP)</option>
                                <option value="Tingkat Lanjutan (FKRTL)" {{ ($profil->tingkat_pelayanan ?? '') == 'Tingkat Lanjutan (FKRTL)' ? 'selected' : '' }}>Tingkat Lanjutan (FKRTL)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">No. Surat Izin Operasional</label>
                            <input type="text" class="form-control form-control-sm" name="no_izin_operasional" value="{{ $profil->no_izin_operasional ?? '' }}" placeholder="Contoh: 503/012/DPMPTSP/2024">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Alamat Lengkap</label>
                            <textarea class="form-control form-control-sm" name="alamat" rows="2">{{ $profil->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat' }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">No. Telepon</label>
                            <input type="text" class="form-control form-control-sm" name="telepon" value="{{ $profil->telepon ?? '(0264) 8303763' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Kerjasama BPJS</label>
                            <select class="form-select form-select-sm" name="kerjasama_bpjs">
                                <option value="Ya" {{ ($profil->kerjasama_bpjs ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                <option value="Tidak" {{ ($profil->kerjasama_bpjs ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">No. Surat Izin Apotek</label>
                            <input type="text" class="form-control form-control-sm" name="no_izin_apotek" value="{{ $profil->no_izin_apotek ?? '123/019/X/KLINIK-OSS/XXX' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Penanggung Jawab Apotek</label>
                            <input type="text" class="form-control form-control-sm" name="penanggung_jawab_apotek" value="{{ $profil->penanggung_jawab_apotek ?? 'apt. Fariddon Ricky Wahyudi, S.Farm' }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Email Faskes</label>
                            <input type="email" class="form-control form-control-sm" name="email" value="{{ $profil->email ?? 'kliniktelagasari24jam@gmail.com' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Ganti Logo Faskes</label>
                            <input type="file" class="form-control form-control-sm" name="logo" accept="image/*">
                            <small class="text-muted" style="font-size: 11px;">Format: PNG, JPG, JPEG (Maks. 2MB)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success px-3" style="background-color: #00a65a; border-color: #00a65a;">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .profil-faskes-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .clinic-logo-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 1.5px solid #e2e8f0;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .btn-ubah-profil {
        background-color: #3498db;
        border-color: #3498db;
        font-size: 13px;
        transition: background-color 0.2s ease;
    }
    .btn-ubah-profil:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }
    .profile-detail-table th {
        width: 260px;
        font-weight: 600;
        color: #2d3748;
        font-size: 13px;
        padding: 10px 16px;
        vertical-align: middle;
        background-color: #fafbfc;
    }
    .profile-detail-table td {
        font-size: 13px;
        color: #333333;
        padding: 10px 16px;
        vertical-align: middle;
    }
    .profile-detail-table tr:nth-child(even) td {
        background-color: #f8fafc;
    }
</style>
@endsection
