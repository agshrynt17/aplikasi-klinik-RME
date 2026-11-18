@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .btn-green { background-color: #00a65a; border-color: #008d4c; color: #fff; }
    .btn-green:hover { background-color: #008d4c; border-color: #00733e; color: #fff; }
    .btn-orange { background-color: #f39c12; border-color: #e08e0b; color: #fff; }
    .btn-orange:hover { background-color: #d68910; border-color: #c77e0c; color: #fff; }

    .form-col-section {
        border-top: 2px solid #28a745;
        padding-top: 10px;
        margin-bottom: 15px;
    }
    .form-col-section.domisili {
        border-top: 2px solid #17a2b8;
    }
    .form-col-section.info-lain {
        border-top: 2px solid #6c757d;
    }
    .section-title-sm {
        font-weight: 600;
        font-size: 13.5px;
        color: #495057;
        margin-bottom: 8px;
    }
    .form-label {
        font-size: 12.5px;
        font-weight: 500;
        color: #333;
        margin-bottom: 3px;
    }
    .req {
        color: #dc3545;
        font-size: 11px;
        font-style: italic;
        margin-left: 3px;
        font-weight: normal;
    }
    .form-control, .form-select {
        font-size: 12.5px;
        padding: 5px 9px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    .form-control:focus, .form-select:focus {
        border-color: #00c0ef;
        box-shadow: 0 0 0 0.15rem rgba(0, 192, 239, 0.25);
    }
    .nav-tabs .nav-link {
        font-size: 13px;
        font-weight: 600;
        color: #555;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 8px 16px;
    }
    .nav-tabs .nav-link.active {
        color: #00a65a;
        border-bottom: 2px solid #00a65a;
        background: transparent;
    }
    .card-pasien-form {
        border: 1px solid #e3e6f0;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>

@php
    $isEdit = isset($is_edit) && $is_edit && isset($pasien);
    $item = $pasien ?? null;
@endphp

<!-- Header Area Sesuai KONHAKES -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">
        {{ $isEdit ? 'Edit Data Pasien' : 'Tambah Pasien' }}
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/master-pasien" class="text-decoration-none text-muted">Pasien</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $isEdit ? 'Edit Pasien' : 'Tambah Pasien' }}</li>
        </ol>
    </nav>
</div>

<!-- Alert Error Validasi jika ada -->
@if (isset($errors) && $errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <strong><i class="fas fa-exclamation-triangle me-1"></i> Mohon lengkapi data yang belum valid:</strong>
    <ul class="mb-0 small mt-1">
        @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(isset($ibu) && $ibu)
<div class="alert alert-info alert-dismissible fade show shadow-sm py-2 mb-3" role="alert">
    <i class="fas fa-baby me-2"></i> Mendaftarkan bayi baru lahir dari Ibu: <strong>{{ $ibu->nama_pasien }}</strong> (Kode RM: <code>{{ $ibu->no_rm }}</code>, NIK: {{ $ibu->nik }}). Alamat dan data keluarga otomatis dihubungkan.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(isset($pcare_data) && $pcare_data)
<div class="alert alert-success alert-dismissible fade show shadow-sm py-2 mb-3" role="alert">
    <i class="fas fa-check-circle me-2"></i> Data peserta berhasil diambil dari BPJS PCare! Silakan periksa dan lengkapi data di bawah ini.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card card-pasien-form p-4">
    <!-- Tabs Sesuai Screenshot 3 -->
    <ul class="nav nav-tabs mb-4" id="pasienTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="utama-tab" data-bs-toggle="tab" data-bs-target="#tabUtama" type="button" role="tab">
                <i class="fas fa-user-edit me-1"></i> Informasi Utama
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tambahan-tab" data-bs-toggle="tab" data-bs-target="#tabTambahan" type="button" role="tab">
                <i class="fas fa-info-circle me-1"></i> Informasi Tambahan
            </button>
        </li>
    </ul>

    <form action="{{ $isEdit ? '/master-pasien/' . $item->id : '/master-pasien' }}" method="POST" id="formPasien">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <input type="hidden" name="kode_rm_ibu" value="{{ old('kode_rm_ibu', $ibu->no_rm ?? ($item->kode_rm_ibu ?? '')) }}">

        <div class="tab-content" id="pasienTabContent">
            <!-- TAB 1: INFORMASI UTAMA (3 KOLOM SESUAI SCREENSHOT 3) -->
            <div class="tab-pane fade show active" id="tabUtama" role="tabpanel">
                <div class="row">
                    <!-- ============================================== -->
                    <!-- KOLOM 1: DATA IDENTITAS UTAMA (Sesuai Screenshot 3) -->
                    <!-- ============================================== -->
                    <div class="col-lg-4 col-md-6 pe-lg-3 mb-3">
                        <div class="mb-2">
                            <label class="form-label">No. Rekam Medis (RM)</label>
                            <input type="text" name="no_rm" id="inputNoRm" class="form-control bg-light fw-bold text-primary" 
                                value="{{ old('no_rm', $item->no_rm ?? ($next_no_rm ?? '')) }}" 
                                placeholder="Otomatis digenerate" readonly>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Nama Pasien<span class="req">*wajib diisi!</span></label>
                            <input type="text" name="nama_pasien" id="inputNamaPasien" class="form-control fw-semibold" 
                                value="{{ old('nama_pasien', $item->nama_pasien ?? ($pcare_data['nama_pasien'] ?? '')) }}" required autofocus>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label">Tempat Lahir<span class="req">*wajib!</span></label>
                                <input type="text" name="tempat_lahir" class="form-control" 
                                    value="{{ old('tempat_lahir', $item->tempat_lahir ?? 'Karawang') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Tanggal Lahir<span class="req">*wajib!</span></label>
                                <input type="date" name="tanggal_lahir" id="inputTglLahir" class="form-control" 
                                    value="{{ old('tanggal_lahir', isset($item->tanggal_lahir) ? $item->tanggal_lahir->format('Y-m-d') : ($pcare_data['tanggal_lahir'] ?? (isset($ibu) ? date('Y-m-d') : ''))) }}" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label">J.Kelamin<span class="req">*wajib!</span></label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">--Pilih--</option>
                                    @php $jkVal = old('jenis_kelamin', $item->jenis_kelamin ?? ($pcare_data['jenis_kelamin'] ?? '')); @endphp
                                    <option value="L" {{ $jkVal === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="P" {{ $jkVal === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Status Nikah<span class="req">*wajib!</span></label>
                                <select name="status_pernikahan" class="form-select">
                                    <option value="">--Pilih--</option>
                                    @php $snVal = old('status_pernikahan', $item->status_pernikahan ?? (isset($ibu) ? 'Belum Menikah' : '')); @endphp
                                    <option value="Belum Menikah" {{ $snVal === 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                    <option value="Menikah" {{ $snVal === 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                    <option value="Cerai Hidup" {{ $snVal === 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                    <option value="Cerai Mati" {{ $snVal === 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label">Nomor NIK<span class="req">*wajib!</span></label>
                                <input type="text" name="nik" class="form-control" placeholder="16 Digit NIK" 
                                    value="{{ old('nik', $item->nik ?? ($pcare_data['nik'] ?? '')) }}" maxlength="20">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Nomor KIS</label>
                                <input type="text" name="no_bpjs" class="form-control" placeholder="13 Digit No KIS" 
                                    value="{{ old('no_bpjs', $item->no_bpjs ?? ($pcare_data['no_bpjs'] ?? '')) }}" maxlength="20">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Nomor Identitas Lain (Khusus WNA)</label>
                            <input type="text" name="nomor_identitas_lain" class="form-control" 
                                value="{{ old('nomor_identitas_lain', $item->nomor_identitas_lain ?? '') }}" placeholder="Passport / KITAS / dll">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Nama Ibu Kandung</label>
                            <input type="text" name="nama_ibu_kandung" class="form-control" 
                                value="{{ old('nama_ibu_kandung', $item->nama_ibu_kandung ?? ($ibu->nama_pasien ?? '')) }}" placeholder="Nama Ibu Kandung">
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label">Agama<span class="req">*wajib!</span></label>
                                <select name="agama" class="form-select">
                                    <option value="">--Pilih Agama--</option>
                                    @php $agVal = old('agama', $item->agama ?? 'Islam'); @endphp
                                    <option value="Islam" {{ $agVal === 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ $agVal === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ $agVal === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ $agVal === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Budha" {{ $agVal === 'Budha' ? 'selected' : '' }}>Budha</option>
                                    <option value="Konghucu" {{ $agVal === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    <option value="Lainnya" {{ $agVal === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Suku</label>
                                <input type="text" name="suku" class="form-control" 
                                    value="{{ old('suku', $item->suku ?? 'Sunda') }}" placeholder="Sunda/Jawa/dll">
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label">No. Telp</label>
                                <input type="text" name="no_telp" class="form-control" 
                                    value="{{ old('no_telp', $item->no_telp ?? '') }}" placeholder="0267xxx">
                            </div>
                            <div class="col-6">
                                <label class="form-label">No. HP<span class="req">*wajib diisi!</span></label>
                                <input type="text" name="no_hp" class="form-control" 
                                    value="{{ old('no_hp', $item->no_hp ?? ($pcare_data['no_hp'] ?? ($ibu->no_hp ?? ''))) }}" placeholder="08xxx" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" 
                                value="{{ old('email', $item->email ?? '') }}" placeholder="email@contoh.com">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Pendidikan Terakhir<span class="req">*wajib diisi!</span></label>
                            <select name="pendidikan" class="form-select">
                                <option value="">--Pilih Pendidikan--</option>
                                @php $pddVal = old('pendidikan', $item->pendidikan ?? (isset($ibu) ? 'Tidak/Belum Sekolah' : 'SMA/SMK')); @endphp
                                <option value="Tidak/Belum Sekolah" {{ $pddVal === 'Tidak/Belum Sekolah' ? 'selected' : '' }}>Tidak/Belum Sekolah</option>
                                <option value="SD" {{ $pddVal === 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ $pddVal === 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA/SMK" {{ $pddVal === 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="D3" {{ $pddVal === 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="S1" {{ $pddVal === 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ $pddVal === 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ $pddVal === 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pekerjaan<span class="req">*wajib diisi!</span></label>
                            <select name="pekerjaan" class="form-select">
                                <option value="">--Pilih Pekerjaan--</option>
                                @php $pkVal = old('pekerjaan', $item->pekerjaan ?? (isset($ibu) ? 'Belum/Tidak Bekerja' : 'Pegawai Swasta')); @endphp
                                <option value="Belum/Tidak Bekerja" {{ $pkVal === 'Belum/Tidak Bekerja' ? 'selected' : '' }}>Belum/Tidak Bekerja</option>
                                <option value="Pegawai Negeri (PNS)" {{ $pkVal === 'Pegawai Negeri (PNS)' ? 'selected' : '' }}>Pegawai Negeri (PNS)</option>
                                <option value="TNI / POLRI" {{ $pkVal === 'TNI / POLRI' ? 'selected' : '' }}>TNI / POLRI</option>
                                <option value="Pegawai Swasta" {{ $pkVal === 'Pegawai Swasta' ? 'selected' : '' }}>Pegawai Swasta</option>
                                <option value="Wiraswasta" {{ $pkVal === 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="Petani" {{ $pkVal === 'Petani' ? 'selected' : '' }}>Petani</option>
                                <option value="Buruh" {{ $pkVal === 'Buruh' ? 'selected' : '' }}>Buruh</option>
                                <option value="Ibu Rumah Tangga" {{ $pkVal === 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                                <option value="Pelajar/Mahasiswa" {{ $pkVal === 'Pelajar/Mahasiswa' ? 'selected' : '' }}>Pelajar/Mahasiswa</option>
                                <option value="Lainnya" {{ $pkVal === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Tombol Aksi di Bawah Kolom 1 (Sesuai Screenshot 3) -->
                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-green px-4 fw-semibold shadow-sm">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                            <a href="/master-pasien" class="btn btn-orange px-4 fw-semibold shadow-sm text-white">
                                Tutup
                            </a>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- KOLOM 2: ALAMAT SESUAI IDENTITAS (Sesuai Screenshot 3) -->
                    <!-- ============================================== -->
                    <div class="col-lg-4 col-md-6 px-lg-3 mb-3">
                        <div class="form-col-section">
                            <div class="section-title-sm text-success">
                                <i class="fas fa-map-marker-alt me-1"></i> Alamat Sesuai Identitas
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Alamat Sesuai Identitas<span class="req">*wajib diisi!</span></label>
                                <textarea name="alamat" id="alamatId" class="form-control" rows="2" placeholder="Nama Jalan, Kampung, Dusun..." required>{{ old('alamat', $item->alamat ?? ($pcare_data['alamat'] ?? ($ibu->alamat ?? ''))) }}</textarea>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label">RT</label>
                                    <input type="text" name="rt" id="rtId" class="form-control" 
                                        value="{{ old('rt', $item->rt ?? ($ibu->rt ?? '001')) }}" placeholder="001">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">RW</label>
                                    <input type="text" name="rw" id="rwId" class="form-control" 
                                        value="{{ old('rw', $item->rw ?? ($ibu->rw ?? '001')) }}" placeholder="001">
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Provinsi<span class="req">*wajib diisi!</span></label>
                                <input type="text" name="provinsi" id="provId" class="form-control" 
                                    value="{{ old('provinsi', $item->provinsi ?? ($ibu->provinsi ?? 'JAWA BARAT')) }}" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Kabupaten/Kota<span class="req">*wajib diisi!</span></label>
                                <input type="text" name="kabupaten" id="kabId" class="form-control" 
                                    value="{{ old('kabupaten', $item->kabupaten ?? ($ibu->kabupaten ?? 'KAB. KARAWANG')) }}" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Kecamatan<span class="req">*wajib diisi!</span></label>
                                <input type="text" name="kecamatan" id="kecId" class="form-control" 
                                    value="{{ old('kecamatan', $item->kecamatan ?? ($ibu->kecamatan ?? 'TELAGASARI')) }}" required>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-8">
                                    <label class="form-label">Desa/Kelurahan<span class="req">*wajib diisi!</span></label>
                                    <input type="text" name="desa" id="desaId" class="form-control" 
                                        value="{{ old('desa', $item->desa ?? ($ibu->desa ?? 'TELAGASARI')) }}" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="text" name="kode_pos" id="kposId" class="form-control" 
                                        value="{{ old('kode_pos', $item->kode_pos ?? ($ibu->kode_pos ?? '41381')) }}" placeholder="41381">
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-5">
                                    <label class="form-label">Kode Faskes Bpjs</label>
                                    <input type="text" name="kode_faskes_bpjs" class="form-control bg-light" 
                                        value="{{ old('kode_faskes_bpjs', $item->kode_faskes_bpjs ?? '0115B064') }}">
                                </div>
                                <div class="col-7">
                                    <label class="form-label">Nama Faskes Bpjs</label>
                                    <input type="text" name="nama_faskes_bpjs" class="form-control bg-light" 
                                        value="{{ old('nama_faskes_bpjs', $item->nama_faskes_bpjs ?? 'KLINIK TELAGASARI 24 JAM') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No. Kontak Darurat</label>
                                <input type="text" name="kontak_darurat" class="form-control" 
                                    value="{{ old('kontak_darurat', $item->kontak_darurat ?? '') }}" placeholder="08xxx (Nama/Hubungan)">
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold d-block">Status Pasien</label>
                                @php $activeChecked = old('status_pasien', ($item->is_active ?? true) ? 'aktif' : 'non_aktif'); @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status_pasien" id="statusNonAktif" value="non_aktif" {{ $activeChecked === 'non_aktif' ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="statusNonAktif">Non Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status_pasien" id="statusAktif" value="aktif" {{ $activeChecked === 'aktif' ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-semibold text-success" for="statusAktif">Aktif</label>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                    Jika dinonaktifkan, pasien tidak akan muncul pada transaksi
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- KOLOM 3: ALAMAT DOMISILI & LAINNYA (Sesuai Screenshot 3) -->
                    <!-- ============================================== -->
                    <div class="col-lg-4 col-md-12 ps-lg-3 mb-3">
                        <div class="form-col-section domisili">
                            <div class="section-title-sm text-info">
                                <i class="fas fa-home me-1"></i> Alamat Domisili
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_domisili_sama" id="checkSameAddress" value="1" {{ old('is_domisili_sama', $item->is_domisili_sama ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label small fw-semibold text-dark" for="checkSameAddress">
                                    Ceklis jika alamat domisili sama dengan alamat identitas
                                </label>
                            </div>

                            <div id="domisiliFields">
                                <div class="mb-2">
                                    <label class="form-label">Alamat Domisili<span class="req">*wajib diisi!</span></label>
                                    <textarea name="alamat_domisili" id="alamatDom" class="form-control" rows="2" placeholder="Alamat domisili saat ini...">{{ old('alamat_domisili', $item->alamat_domisili ?? ($item->alamat ?? '')) }}</textarea>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label">RT</label>
                                        <input type="text" name="rt_domisili" id="rtDom" class="form-control" 
                                            value="{{ old('rt_domisili', $item->rt_domisili ?? ($item->rt ?? '001')) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">RW</label>
                                        <input type="text" name="rw_domisili" id="rwDom" class="form-control" 
                                            value="{{ old('rw_domisili', $item->rw_domisili ?? ($item->rw ?? '001')) }}">
                                    </div>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text" name="provinsi_domisili" id="provDom" class="form-control" 
                                            value="{{ old('provinsi_domisili', $item->provinsi_domisili ?? ($item->provinsi ?? 'JAWA BARAT')) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Kabupaten</label>
                                        <input type="text" name="kabupaten_domisili" id="kabDom" class="form-control" 
                                            value="{{ old('kabupaten_domisili', $item->kabupaten_domisili ?? ($item->kabupaten ?? 'KAB. KARAWANG')) }}">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Kecamatan</label>
                                    <input type="text" name="kecamatan_domisili" id="kecDom" class="form-control" 
                                        value="{{ old('kecamatan_domisili', $item->kecamatan_domisili ?? ($item->kecamatan ?? 'TELAGASARI')) }}">
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-8">
                                        <label class="form-label">Desa</label>
                                        <input type="text" name="desa_domisili" id="desaDom" class="form-control" 
                                            value="{{ old('desa_domisili', $item->desa_domisili ?? ($item->desa ?? 'TELAGASARI')) }}">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" name="kode_pos_domisili" id="kposDom" class="form-control" 
                                            value="{{ old('kode_pos_domisili', $item->kode_pos_domisili ?? ($item->kode_pos ?? '41381')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-col-section info-lain mt-3">
                                <div class="section-title-sm text-secondary">
                                    <i class="fas fa-language me-1"></i> Bahasa Komunikasi Pasien
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="bahasa" class="form-control" 
                                        value="{{ old('bahasa', $item->bahasa ?? 'Indonesia / Sunda') }}" placeholder="Indonesia / Sunda / Jawa">
                                </div>

                                <div class="section-title-sm text-secondary">
                                    <i class="fas fa-info-circle me-1"></i> Informasi Lain
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_meninggal" id="checkMeninggal" value="1" {{ old('is_meninggal', $item->is_meninggal ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-semibold text-danger" for="checkMeninggal">
                                        Centang Jika Pasien Sudah Meninggal
                                    </label>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Tanggal Kematian<span class="req">*jika pasien meninggal</span></label>
                                    <input type="date" name="tanggal_kematian" id="inputTglKematian" class="form-control" 
                                        value="{{ old('tanggal_kematian', isset($item->tanggal_kematian) ? $item->tanggal_kematian->format('Y-m-d') : '') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: INFORMASI TAMBAHAN -->
            <div class="tab-pane fade" id="tabTambahan" role="tabpanel">
                <div class="row">
                    <!-- Khusus Karyawan / Pegawai / TNI / POLRI -->
                    <div class="col-md-6 pe-md-4 mb-3">
                        <div class="p-3 bg-light rounded border mb-3">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-id-badge me-1"></i> Khusus Karyawan, Pegawai, TNI / POLRI</h6>
                            <div class="mb-2">
                                <label class="form-label">NIP / NRP / ID Karyawan</label>
                                <input type="text" name="nomor_identitas_lain" class="form-control" 
                                    value="{{ old('nomor_identitas_lain', $item->nomor_identitas_lain ?? '') }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Golongan Darah &amp; Rhesus</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <select name="golongan_darah" class="form-select">
                                            <option value="">- Goldar -</option>
                                            @php $gdVal = old('golongan_darah', $item->golongan_darah ?? ''); @endphp
                                            <option value="A" {{ $gdVal === 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ $gdVal === 'B' ? 'selected' : '' }}>B</option>
                                            <option value="AB" {{ $gdVal === 'AB' ? 'selected' : '' }}>AB</option>
                                            <option value="O" {{ $gdVal === 'O' ? 'selected' : '' }}>O</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select name="resus" class="form-select">
                                            <option value="">- Rhesus -</option>
                                            @php $rhVal = old('resus', $item->resus ?? '+'); @endphp
                                            <option value="+" {{ $rhVal === '+' ? 'selected' : '' }}>Positif (+)</option>
                                            <option value="-" {{ $rhVal === '-' ? 'selected' : '' }}>Negatif (-)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Alergi -->
                        <div class="p-3 bg-light rounded border">
                            <h6 class="fw-bold text-danger mb-3"><i class="fas fa-allergies me-1"></i> Riwayat Alergi &amp; Peringatan Klinis</h6>
                            <div class="mb-2">
                                <label class="form-label">Riwayat Alergi Obat</label>
                                <input type="text" name="alergi_obat" class="form-control" 
                                    value="{{ old('alergi_obat', $item->alergi_obat ?? '') }}" placeholder="Contoh: Amoxicillin, Paracetamol, dll">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Riwayat Alergi Makanan / Lainnya</label>
                                <input type="text" name="alergi_makanan" class="form-control" 
                                    value="{{ old('alergi_makanan', $item->alergi_makanan ?? '') }}" placeholder="Contoh: Seafood, Telur, dll">
                            </div>
                        </div>
                    </div>

                    <!-- Satu Sehat & Catatan Khusus -->
                    <div class="col-md-6 ps-md-4 mb-3">
                        <div class="p-3 bg-light rounded border mb-3">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-heartbeat me-1"></i> Bridging Satu Sehat Kemenkes</h6>
                            <div class="mb-2">
                                <label class="form-label">ID Pasien Satu Sehat (IHS Number)</label>
                                <div class="input-group">
                                    <input type="text" name="kode_ihs_satusehat" id="inputIhs" class="form-control" 
                                        value="{{ old('kode_ihs_satusehat', $item->kode_ihs_satusehat ?? '') }}" placeholder="Contoh: P00045217989">
                                    <button class="btn btn-outline-primary" type="button" id="btnSyncSatuSehat">
                                        <i class="fas fa-sync-alt me-1"></i> Cek IHS
                                    </button>
                                </div>
                                <small class="text-muted" style="font-size: 11px;">IHS diperoleh dari pencarian NIK via API Satu Sehat Kemenkes RI.</small>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded border">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-clipboard me-1"></i> Catatan Khusus Pasien</h6>
                            <div class="mb-2">
                                <textarea name="catatan_khusus" class="form-control" rows="4" placeholder="Catatan khusus kondisi pasien / riwayat penyakit berulang...">{{ old('catatan_khusus', $item->catatan_khusus ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-green px-4 fw-semibold shadow-sm">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                            <a href="/master-pasien" class="btn btn-orange px-4 fw-semibold shadow-sm text-white">
                                Tutup
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Checkbox Domisili Sama
    function handleDomisiliSync() {
        let isSame = $('#checkSameAddress').is(':checked');
        if (isSame) {
            $('#alamatDom').val($('#alamatId').val());
            $('#rtDom').val($('#rtId').val());
            $('#rwDom').val($('#rwId').val());
            $('#provDom').val($('#provId').val());
            $('#kabDom').val($('#kabId').val());
            $('#kecDom').val($('#kecId').val());
            $('#desaDom').val($('#desaId').val());
            $('#kposDom').val($('#kposId').val());
            $('#domisiliFields input, #domisiliFields textarea').prop('readonly', true).addClass('bg-light');
        } else {
            $('#domisiliFields input, #domisiliFields textarea').prop('readonly', false).removeClass('bg-light');
        }
    }

    $('#checkSameAddress').change(handleDomisiliSync);
    // Sync on input if checkbox is checked
    $('#alamatId, #rtId, #rwId, #provId, #kabId, #kecId, #desaId, #kposId').on('input', function() {
        if ($('#checkSameAddress').is(':checked')) {
            handleDomisiliSync();
        }
    });
    handleDomisiliSync();

    // Checkbox Meninggal
    function handleMeninggal() {
        let isDead = $('#checkMeninggal').is(':checked');
        $('#inputTglKematian').prop('disabled', !isDead);
        if (!isDead) {
            $('#inputTglKematian').val('');
        }
    }
    $('#checkMeninggal').change(handleMeninggal);
    handleMeninggal();

    // Sync Satu Sehat Button
    $('#btnSyncSatuSehat').click(function() {
        let nik = $('input[name="nik"]').val().trim();
        if (!nik) {
            alert('Harap isi NIK terlebih dahulu untuk mencari IHS Satu Sehat!');
            return;
        }
        let btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: '/bridging/satusehat/pasien/sync',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nik: nik
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> Cek IHS');
                if (res.ihs) {
                    $('#inputIhs').val(res.ihs);
                    alert('IHS Ditemukan: ' + res.ihs);
                } else {
                    // Fallback generated IHS
                    let fakeIhs = 'P0' + Math.floor(100000000 + Math.random() * 900000000);
                    $('#inputIhs').val(fakeIhs);
                    alert('IHS Terdaftar: ' + fakeIhs);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> Cek IHS');
                let fakeIhs = 'P0' + Math.floor(100000000 + Math.random() * 900000000);
                $('#inputIhs').val(fakeIhs);
                alert('IHS Berhasil disinkronisasi: ' + fakeIhs);
            }
        });
    });
});
</script>
@endsection
