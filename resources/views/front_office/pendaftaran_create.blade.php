@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .btn-green { background-color: #00a65a; border-color: #008d4c; color: #fff; }
    .btn-green:hover { background-color: #008d4c; border-color: #00733e; color: #fff; }
    .btn-orange { background-color: #f39c12; border-color: #e08e0b; color: #fff; }
    .btn-orange:hover { background-color: #d68910; border-color: #c77e0c; color: #fff; }
    .btn-cyan { background-color: #00c0ef; border-color: #00acd6; color: #fff; }
    .btn-cyan:hover { background-color: #00acd6; border-color: #0097bc; color: #fff; }

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

    .skrining-card {
        border-radius: 4px;
        padding: 10px 14px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .skrining-card.hijau {
        background-color: #00a65a;
        color: #fff;
    }
    .skrining-card.oranye {
        background-color: #f39c12;
        color: #fff;
    }
    .skrining-card.merah {
        background-color: #dd4b39;
        color: #fff;
    }
    .skrining-card label {
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.35;
        margin-bottom: 0;
    }

    .card-rajal-form {
        border: 1px solid #e3e6f0;
        border-top: 3px solid #f39c12;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>

@php
    $isEditing = isset($isEdit) && $isEdit && isset($item);
    $pasienObj = $selectedPasien ?? ($item->pasien ?? null);
@endphp

<!-- Header Sesuai Screenshot 2 -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">
        {{ $isEditing ? 'Edit Pendaftaran Rawat Jalan' : 'Pendaftaran Rawat Jalan' }}
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/pendaftaran-rajal" class="text-decoration-none text-muted">Pendaftaran Rawat Jalan</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">{{ $isEditing ? 'Edit' : 'Tambah Baru' }}</a></li>
        </ol>
    </nav>
</div>

<!-- Alert Error Validasi -->
@if (isset($errors) && $errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <strong><i class="fas fa-exclamation-triangle me-1"></i> Mohon lengkapi formulir pendaftaran:</strong>
    <ul class="mb-0 small mt-1">
        @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(isset($antrian) && $antrian)
<div class="alert alert-info alert-dismissible fade show shadow-sm py-2 mb-3" role="alert">
    <i class="fas fa-bullhorn me-2"></i> Memproses Antrian Nomor: <strong>{{ $antrian->no_antrian }}</strong> (Pasien: {{ $pasienObj->nama_pasien ?? '-' }}).
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card card-rajal-form p-4">
    <form action="{{ $isEditing ? '/pendaftaran-rajal/' . $item->id : '/pendaftaran-rajal' }}" method="POST" id="formPendaftaranRajal">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        @if(isset($antrian) && $antrian)
            <input type="hidden" name="antrian_id" value="{{ $antrian->id }}">
        @endif

        <div class="row">
            <!-- ============================================== -->
            <!-- KOLOM 1: DATA PASIEN & SKRINING VISUAL -->
            <!-- ============================================== -->
            <div class="col-lg-4 col-md-12 pe-lg-4 mb-3 border-end">
                <div class="mb-3 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">Pilih Pasien<span class="req">*wajib!</span></label>
                        <a href="/tambah-pasien" target="_blank" class="text-decoration-none small text-success fw-bold" style="font-size: 11.5px;">
                            <i class="fas fa-user-plus me-1"></i>+ Pasien Baru
                        </a>
                    </div>
                    <div class="input-group">
                        <input type="text" id="displayPasien" class="form-control fw-bold" 
                            placeholder="Ketik Nama Pasien / No. RM atau klik Cari..." 
                            value="{{ $pasienObj ? '[' . $pasienObj->no_rm . '] - ' . $pasienObj->nama_pasien : '' }}" autocomplete="off" required>
                        <button class="btn btn-cyan text-white" type="button" id="btnModalCariPasien" data-bs-toggle="modal" data-bs-target="#modalCariPasien" title="Buka Pencarian Tabel Pasien">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                        <button class="btn btn-outline-secondary" type="button" id="btnClearPasien" title="Kosongkan Pasien">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <input type="hidden" name="pasien_id" id="inputPasienId" value="{{ old('pasien_id', $pasienObj->id ?? '') }}">
                    
                    <!-- Autocomplete dropdown container -->
                    <div id="autocompletePasienList" class="list-group shadow-lg position-absolute w-100 mt-1 d-none" style="z-index: 1050; max-height: 260px; overflow-y: auto;">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Nomor Registrasi</label>
                        <input type="text" name="nomor_registrasi" id="inputNomorRegistrasi" class="form-control bg-light fw-bold text-primary" 
                            value="{{ old('nomor_registrasi', $item->nomor_registrasi ?? ($nextReg ?? '')) }}" readonly>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Tanggal Berobat</label>
                        <input type="date" name="tanggal_daftar" class="form-control" 
                            value="{{ old('tanggal_daftar', isset($item->tanggal_daftar) ? $item->tanggal_daftar->format('Y-m-d') : date('Y-m-d')) }}" required>
                    </div>
                </div>

                <!-- Skrining Visual Pasien Sesuai Screenshot 2 -->
                <div class="mb-2">
                    <label class="form-label fw-bold d-block">Pilih Skrining Visual Pasien<span class="req">*wajib!</span></label>
                    
                    @php 
                        $skriningVal = old('skrining_visual', $item->skrining_visual ?? 'Pasien stabil'); 
                    @endphp

                    <!-- Opsi 1: Hijau (Pasien Stabil) -->
                    <div class="skrining-card hijau d-flex align-items-center">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="skrining_visual" id="skriningStabil" value="Pasien stabil" {{ $skriningVal === 'Pasien stabil' ? 'checked' : '' }}>
                            <label class="form-check-label text-white ms-2" for="skriningStabil">
                                Pasien stabil
                            </label>
                        </div>
                    </div>

                    <!-- Opsi 2: Oranye (Pasien Prioritas/Khusus) -->
                    <div class="skrining-card oranye d-flex align-items-center">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="skrining_visual" id="skriningKhusus" value="Pasien lansia, ibu hamil, anak dibawah 1 tahun, penyakit menular, pasien diasis, pasien kemoterapi, korban tindak kekerasan dan pasien percobaan bunuh diri" {{ str_contains($skriningVal, 'lansia') ? 'checked' : '' }}>
                            <label class="form-check-label text-white ms-2" for="skriningKhusus">
                                Pasien lansia, ibu hamil, anak dibawah 1 tahun, penyakit menular, pasien diasis, pasien kemoterapi, korban tindak kekerasan dan pasien percobaan bunuh diri
                            </label>
                        </div>
                    </div>

                    <!-- Opsi 3: Merah (Kegawatdaruratan) -->
                    <div class="skrining-card merah d-flex align-items-center">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="skrining_visual" id="skriningGawat" value="Pasien kegawat daruratan" {{ $skriningVal === 'Pasien kegawat daruratan' ? 'checked' : '' }}>
                            <label class="form-check-label text-white ms-2" for="skriningGawat">
                                Pasien kegawat daruratan
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- KOLOM 2: JENIS RAWAT, ANTRIAN, PENJAMIN -->
            <!-- ============================================== -->
            <div class="col-lg-4 col-md-6 px-lg-3 mb-3 border-end">
                <div class="mb-3">
                    <label class="form-label fw-bold d-block">Jenis Rawat</label>
                    @php $rawatVal = old('jenis_rawat', $item->jenis_rawat ?? 'Rawat Jalan'); @endphp
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_rawat" id="rawatJalan" value="Rawat Jalan" {{ $rawatVal === 'Rawat Jalan' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="rawatJalan">Rawat Jalan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_rawat" id="promotifPreventif" value="Promotif Preventif" {{ $rawatVal === 'Promotif Preventif' ? 'checked' : '' }}>
                        <label class="form-check-label small" for="promotifPreventif">Promotif Preventif</label>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_kirim_pcare" id="checkPcare" value="1" {{ old('is_kirim_pcare', $item->is_kirim_pcare ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="checkPcare">Kirim Data Ke PCare</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_kirim_satusehat" id="checkSatuSehat" value="1" {{ old('is_kirim_satusehat', $item->is_kirim_satusehat ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="checkSatuSehat">Kirim ke Satu Sehat</label>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Antrian</label>
                        <select name="antrian_tipe" class="form-select">
                            @php $antVal = old('antrian_tipe', $item->antrian_tipe ?? 'Dengan Antrian'); @endphp
                            <option value="Dengan Antrian" {{ $antVal === 'Dengan Antrian' ? 'selected' : '' }}>Dengan Antrian</option>
                            <option value="Tanpa Antrian" {{ $antVal === 'Tanpa Antrian' ? 'selected' : '' }}>Tanpa Antrian</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">No. Antrian</label>
                        <input type="text" name="no_antrian" id="inputNoAntrian" class="form-control" 
                            placeholder="Cth. A001" value="{{ old('no_antrian', $item->no_antrian ?? ($nextAntrian ?? '')) }}">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-7">
                        <label class="form-label">Penjamin<span class="req">*wajib!</span></label>
                        <select name="penjamin_id" id="selectPenjamin" class="form-select" required>
                            @php $penjVal = old('penjamin_id', $item->penjamin_id ?? 1); @endphp
                            @foreach($penjamins as $penj)
                                <option value="{{ $penj->id }}" {{ $penjVal == $penj->id ? 'selected' : '' }}>
                                    {{ $penj->nama_penjamin }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5">
                        <label class="form-label">TPA</label>
                        <select name="tpa_id" class="form-select">
                            <option value="">--Pilih--</option>
                            @php $tpaVal = old('tpa_id', $item->tpa_id ?? ''); @endphp
                            @foreach($tpas as $t)
                                <option value="{{ $t->id }}" {{ $tpaVal == $t->id ? 'selected' : '' }}>
                                    {{ $t->nama_tpa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">No BPJS / Asuransi / No Polis</label>
                    <input type="text" name="no_bpjs_asuransi" id="inputNoBpjs" class="form-control bg-light" 
                        value="{{ old('no_bpjs_asuransi', $item->no_bpjs_asuransi ?? ($pasienObj->no_bpjs ?? '')) }}" placeholder="13 Digit No BPJS">
                    <small class="text-muted d-block mt-1" style="font-size: 11px;">
                        Pasien BPJS ubah No.KIS dari master pasien
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">ID Satu Sehat Pasien</label>
                    <input type="text" name="id_satusehat_pasien" id="inputIdSatuSehat" class="form-control bg-light" 
                        value="{{ $pasienObj->kode_ihs_satusehat ?? '-' }}" readonly>
                    <small class="text-muted d-block mt-1" style="font-size: 11px;">
                        Silakan dapatkan ID Satu Sehat dari master pasien
                    </small>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- KOLOM 3: POLIKLINIK, DOKTER, PERAWAT & AKSI -->
            <!-- ============================================== -->
            <div class="col-lg-4 col-md-6 ps-lg-3 mb-3">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Jenis Kunjungan<span class="req">*wajib!</span></label>
                        <select name="jenis_kunjungan" class="form-select" required>
                            @php $jkjVal = old('jenis_kunjungan', $item->jenis_kunjungan ?? 'Kunjungan Sakit'); @endphp
                            <option value="Kunjungan Sakit" {{ $jkjVal === 'Kunjungan Sakit' ? 'selected' : '' }}>Kunjungan Sakit</option>
                            <option value="Kunjungan Sehat" {{ $jkjVal === 'Kunjungan Sehat' ? 'selected' : '' }}>Kunjungan Sehat</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Poliklinik<span class="req">*wajib!</span></label>
                        <select name="poliklinik_id" class="form-select" required>
                            <option value="">--Pilih--</option>
                            @php $polVal = old('poliklinik_id', $item->poliklinik_id ?? ''); @endphp
                            @foreach($polikliniks as $poli)
                                <option value="{{ $poli->id }}" {{ $polVal == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ruang Pemeriksaan</label>
                    <select name="ruang_pemeriksaan" class="form-select">
                        @php $rgVal = old('ruang_pemeriksaan', $item->ruang_pemeriksaan ?? 'Ruang Rawat 1'); @endphp
                        <option value="Ruang Rawat 1" {{ $rgVal === 'Ruang Rawat 1' ? 'selected' : '' }}>Ruang Rawat 1</option>
                        <option value="Ruang Rawat 2" {{ $rgVal === 'Ruang Rawat 2' ? 'selected' : '' }}>Ruang Rawat 2</option>
                        <option value="Ruang Tindakan" {{ $rgVal === 'Ruang Tindakan' ? 'selected' : '' }}>Ruang Tindakan</option>
                        <option value="Poli Gigi" {{ $rgVal === 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                        <option value="Laboratorium" {{ $rgVal === 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keluhan Pasien<span class="req">*wajib untuk kunjungan sakit!</span></label>
                    <input type="text" name="keluhan" class="form-control" 
                        value="{{ old('keluhan', $item->keluhan ?? '') }}" placeholder="Ketik keluhan awal pasien...">
                </div>

                <div class="mb-3">
                    <label class="form-label">Dokter<span class="req">*wajib!</span></label>
                    <select name="dokter_id" class="form-select" required>
                        <option value="">--Pilih Dokter--</option>
                        @php $dokVal = old('dokter_id', $item->dokter_id ?? ''); @endphp
                        @foreach($dokters as $dok)
                            <option value="{{ $dok->id }}" {{ $dokVal == $dok->id ? 'selected' : '' }}>
                                {{ $dok->nama_pegawai }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Perawat</label>
                    <select name="perawat_id" id="selectPerawat" class="form-select">
                        <option value="">--Pilih Perawat--</option>
                        @php $prwVal = old('perawat_id', $item->perawat_id ?? ''); @endphp
                        @foreach($perawats as $prw)
                            <option value="{{ $prw->id }}" {{ $prwVal == $prw->id ? 'selected' : '' }}>
                                {{ $prw->nama_pegawai }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="is_prb" id="checkPrb" value="1" {{ old('is_prb', $item->is_prb ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="checkPrb">Kunjungan pasien PRB</label>
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="is_online" id="checkOnline" value="1" {{ old('is_online', $item->is_online ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="checkOnline">Kunjungan secara online</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_exclude_kasir" id="checkExcludeKasir" value="1" {{ old('is_exclude_kasir', $item->is_exclude_kasir ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="checkExcludeKasir">Tidak masuk dalam perhitungan kasir</label>
                    </div>
                </div>

                <!-- Tombol Simpan & Batal Sesuai Screenshot 2 -->
                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-green px-4 fw-semibold shadow-sm">
                        <i class="fas fa-check me-1"></i> OK! Simpan
                    </button>
                    <a href="/pendaftaran-rajal" class="btn btn-orange px-4 fw-semibold shadow-sm text-white">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- MODAL: PILIH PASIEN DENGAN AJAX SEARCH -->
<!-- ============================================== -->
<div class="modal fade" id="modalCariPasien" tabindex="-1" aria-labelledby="modalCariPasienLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-cyan text-white py-2">
                <h5 class="modal-title fw-bold" id="modalCariPasienLabel" style="font-size: 1.1rem;">
                    <i class="fas fa-user-injured me-2"></i> Pilih Data Pasien
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                    <input type="text" id="inputSearchPasienAjax" class="form-control" placeholder="Ketik Nama Pasien, No RM, NIK, No BPJS, atau Alamat...">
                    <button class="btn btn-outline-secondary btn-sm" type="button" id="btnResetSearchPasien">Reset</button>
                </div>

                <div class="table-responsive" style="max-height: 380px;">
                    <table class="table table-hover table-striped table-bordered align-middle mb-0" style="font-size: 12px;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="10%" class="text-center">Aksi</th>
                                <th width="15%">No. RM</th>
                                <th width="25%">Nama Pasien</th>
                                <th width="20%">NIK</th>
                                <th width="15%">No. BPJS/KIS</th>
                                <th width="15%">Alamat</th>
                            </tr>
                        </thead>
                        <tbody id="tbodySearchPasien">
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">
                                    <div class="spinner-border spinner-border-sm text-cyan me-1"></div> Memuat daftar pasien...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 d-flex justify-content-between">
                <a href="/tambah-pasien" target="_blank" class="btn btn-success btn-sm fw-semibold">
                    <i class="fas fa-user-plus me-1"></i> + Tambah Pasien Baru
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function initPatientSearch() {
        // Load Pasien Ajax
        function fetchPasiens(query) {
            query = query || '';
            $('#tbodySearchPasien').html('<tr><td colspan="6" class="text-center py-3 text-muted"><div class="spinner-border spinner-border-sm text-cyan me-1"></div> Memuat daftar pasien...</td></tr>');
            
            $.ajax({
                url: '/pendaftaran/ajax-search-pasien',
                type: 'GET',
                data: { q: query },
                success: function(res) {
                    var html = '';
                    if(!res || res.length === 0) {
                        html = '<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-search me-1"></i> Pasien tidak ditemukan. <a href="/tambah-pasien" target="_blank" class="text-success fw-bold ms-1">+ Tambah Pasien Baru</a></td></tr>';
                    } else {
                        res.forEach(function(p) {
                            var norm   = p.no_rm || '-';
                            var nama   = p.nama_pasien || '-';
                            var nik    = p.nik || '-';
                            var nobpjs = p.no_bpjs || '-';
                            var ihs    = p.kode_ihs_satusehat || '-';
                            var alamat = p.alamat || '-';

                            html += '<tr>' +
                                '<td class="text-center">' +
                                    '<button type="button" class="btn btn-orange btn-sm py-0 px-2 fw-semibold text-white btnSelectPasien" ' +
                                        'data-id="' + p.id + '" ' +
                                        'data-norm="' + (p.no_rm || '') + '" ' +
                                        'data-nama="' + (p.nama_pasien || '') + '" ' +
                                        'data-nik="' + (p.nik || '') + '" ' +
                                        'data-nobpjs="' + (p.no_bpjs || '') + '" ' +
                                        'data-ihs="' + ihs + '">' +
                                        '<i class="fas fa-check me-1"></i> Pilih' +
                                    '</button>' +
                                '</td>' +
                                '<td class="fw-bold text-primary">' + norm + '</td>' +
                                '<td class="fw-bold text-dark text-uppercase">' + nama + '</td>' +
                                '<td style="font-family: monospace;">' + nik + '</td>' +
                                '<td style="font-family: monospace;">' + nobpjs + '</td>' +
                                '<td class="small text-muted text-truncate" style="max-width: 150px;">' + alamat + '</td>' +
                            '</tr>';
                        });
                    }
                    $('#tbodySearchPasien').html(html);
                },
                error: function(xhr) {
                    $('#tbodySearchPasien').html('<tr><td colspan="6" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Gagal memuat data pasien. Silakan coba lagi.</td></tr>');
                }
            });
        }

        // When modal is shown
        $(document).on('shown.bs.modal', '#modalCariPasien', function () {
            var cur = $('#inputSearchPasienAjax').val() || '';
            fetchPasiens(cur);
            setTimeout(function() {
                $('#inputSearchPasienAjax').focus();
            }, 100);
        });

        $(document).on('click', '#btnModalCariPasien', function() {
            fetchPasiens($('#inputSearchPasienAjax').val() || '');
        });

        // Search in modal
        var searchTimer;
        $(document).on('input', '#inputSearchPasienAjax', function() {
            clearTimeout(searchTimer);
            var q = $(this).val();
            searchTimer = setTimeout(function() {
                fetchPasiens(q);
            }, 300);
        });

        $(document).on('keydown', '#inputSearchPasienAjax', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimer);
                fetchPasiens($(this).val());
            }
        });

        $(document).on('click', '#btnResetSearchPasien', function() {
            $('#inputSearchPasienAjax').val('');
            fetchPasiens('');
        });

        // Select Pasien
        $(document).on('click', '.btnSelectPasien', function() {
            var id    = $(this).data('id');
            var noRm  = $(this).data('norm');
            var nama  = $(this).data('nama');
            var noBpjs= $(this).data('nobpjs');
            var ihs   = $(this).data('ihs');

            $('#inputPasienId').val(id);
            $('#displayPasien').val('[' + noRm + '] - ' + nama);
            $('#inputNoBpjs').val(noBpjs);
            $('#inputIdSatuSehat').val(ihs);

            if(noBpjs) {
                $("#selectPenjamin option").each(function() {
                    if($(this).text().toUpperCase().indexOf('BPJS') !== -1) {
                        $(this).prop('selected', true);
                    }
                });
            }

            $('#modalCariPasien').modal('hide');
            $('#autocompletePasienList').addClass('d-none');
        });

        // Clear Pasien
        $(document).on('click', '#btnClearPasien', function() {
            $('#inputPasienId').val('');
            $('#displayPasien').val('');
            $('#inputNoBpjs').val('');
            $('#inputIdSatuSehat').val('-');
            $('#displayPasien').focus();
        });

        // Direct Autocomplete typing on displayPasien
        var directTimer;
        $(document).on('input', '#displayPasien', function() {
            clearTimeout(directTimer);
            var q = $(this).val().trim();
            if(q.length < 2) {
                $('#autocompletePasienList').addClass('d-none').html('');
                return;
            }
            directTimer = setTimeout(function() {
                $.get('/pendaftaran/ajax-search-pasien', { q: q }, function(res) {
                    if(res && res.length > 0) {
                        var listHtml = '';
                        res.forEach(function(p) {
                            var extra = p.no_bpjs ? 'BPJS: ' + p.no_bpjs : (p.nik ? 'NIK: ' + p.nik : '');
                            listHtml += '<a href="javascript:void(0)" class="list-group-item list-group-item-action py-2 btnSelectPasien" ' +
                                'data-id="' + p.id + '" ' +
                                'data-norm="' + (p.no_rm || '') + '" ' +
                                'data-nama="' + (p.nama_pasien || '') + '" ' +
                                'data-nik="' + (p.nik || '') + '" ' +
                                'data-nobpjs="' + (p.no_bpjs || '') + '" ' +
                                'data-ihs="' + (p.kode_ihs_satusehat || '-') + '">' +
                                '<div class="d-flex justify-content-between align-items-center">' +
                                    '<div><strong class="text-primary">[' + (p.no_rm || '-') + ']</strong> ' + p.nama_pasien + '</div>' +
                                    '<small class="text-muted">' + extra + '</small>' +
                                '</div>' +
                                '<small class="text-muted d-block text-truncate" style="max-width: 400px;">' + (p.alamat || '-') + '</small>' +
                            '</a>';
                        });
                        $('#autocompletePasienList').removeClass('d-none').html(listHtml);
                    } else {
                        $('#autocompletePasienList').removeClass('d-none').html('<div class="list-group-item text-muted py-2 small">Pasien tidak ditemukan. <a href="/tambah-pasien" target="_blank" class="fw-bold text-success">+ Tambah Pasien Baru</a></div>');
                    }
                });
            }, 300);
        });

        // Hide autocomplete when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#displayPasien, #autocompletePasienList').length) {
                $('#autocompletePasienList').addClass('d-none');
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPatientSearch);
    } else {
        initPatientSearch();
    }
})();
</script>
@endsection
