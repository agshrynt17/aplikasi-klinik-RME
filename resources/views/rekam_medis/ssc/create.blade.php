@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/surgical/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Surgical Safety Ceklist
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/surgical" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">
    
    <!-- Informasi Utama -->
    <div class="card card-custom mb-3 border-secondary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <strong>Pasien:</strong> {{ $pendaftaran->pasien->no_rm }} - {{ $pendaftaran->pasien->nama_pasien }}
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-bold">Dokter Penanggung Jawab / Operator</label>
                    <select name="dokter_id" class="form-select form-select-sm" required>
                        <option value="">Pilih Dokter</option>
                        <option value="{{ $pendaftaran->dokter_id }}" selected>{{ $pendaftaran->dokter->nama_pegawai ?? 'Dokter' }}</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-bold">Perawat Sirkuler (Checklist Coordinator)</label>
                    <input type="text" name="perawat_sirkuler" class="form-control form-control-sm" required placeholder="Nama Perawat">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 1. SIGN IN -->
        <div class="col-md-4 mb-3">
            <div class="card card-custom h-100" style="border-top: 4px solid #f39c12;">
                <div class="card-header bg-light fw-bold text-dark"><i class="fas fa-sign-in-alt me-2 text-warning"></i>1. SIGN IN (Sebelum Induksi)</div>
                <div class="card-body" style="font-size: 0.9rem;">
                    <p class="text-muted small">Dibacakan oleh perawat secara verbal bersama dokter anestesi & pasien.</p>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_in[identitas]" value="Ya" id="si_1">
                        <label class="form-check-label fw-semibold" for="si_1">Pasien telah dikonfirmasi identitas, area operasi, prosedur, dan lembar persetujuan (informed consent).</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_in[lokasi_ditandai]" value="Ya" id="si_2">
                        <label class="form-check-label fw-semibold" for="si_2">Area operasi telah ditandai.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_in[mesin_anestesi]" value="Ya" id="si_3">
                        <label class="form-check-label fw-semibold" for="si_3">Mesin anestesi dan obat-obatan telah dicek kesiapannya.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_in[pulse_oximeter]" value="Ya" id="si_4">
                        <label class="form-check-label fw-semibold" for="si_4">Pulse oximeter pada pasien berfungsi baik.</label>
                    </div>
                    
                    <hr>
                    <strong class="d-block mb-1">Apakah pasien mempunyai alergi?</strong>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[alergi]" value="Tidak" id="si_5_tdk" checked>
                        <label class="form-check-label" for="si_5_tdk">Tidak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[alergi]" value="Ya" id="si_5_ya">
                        <label class="form-check-label" for="si_5_ya">Ya</label>
                    </div>
                    
                    <hr>
                    <strong class="d-block mb-1">Risiko kesulitan jalan napas atau aspirasi?</strong>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[risiko_napas]" value="Tidak" id="si_6_tdk" checked>
                        <label class="form-check-label" for="si_6_tdk">Tidak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[risiko_napas]" value="Ya" id="si_6_ya">
                        <label class="form-check-label" for="si_6_ya">Ya, alat & asisten tersedia</label>
                    </div>
                    
                    <hr>
                    <strong class="d-block mb-1">Risiko kehilangan darah > 500ml?</strong>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[risiko_darah]" value="Tidak" id="si_7_tdk" checked>
                        <label class="form-check-label" for="si_7_tdk">Tidak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[risiko_darah]" value="Ya" id="si_7_ya">
                        <label class="form-check-label" for="si_7_ya">Ya, akses IV cukup & cairan sedia</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. TIME OUT -->
        <div class="col-md-4 mb-3">
            <div class="card card-custom h-100" style="border-top: 4px solid #3498db;">
                <div class="card-header bg-light fw-bold text-dark"><i class="fas fa-clock me-2 text-info"></i>2. TIME OUT (Sebelum Insisi)</div>
                <div class="card-body" style="font-size: 0.9rem;">
                    <p class="text-muted small">Semua anggota tim berhenti dari kegiatan lainnya sejenak.</p>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[perkenalan_tim]" value="Ya" id="to_1">
                        <label class="form-check-label fw-semibold" for="to_1">Setiap anggota tim operasi memperkenalkan nama dan peran masing-masing.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[konfirmasi_pasien]" value="Ya" id="to_2">
                        <label class="form-check-label fw-semibold" for="to_2">Dokter bedah, anestesi, dan perawat secara verbal mengonfirmasi: Nama Pasien, Area insisi, dan Prosedur yang dilakukan.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[antibiotik_profilaksis]" value="Ya" id="to_3">
                        <label class="form-check-label fw-semibold" for="to_3">Antibiotik profilaksis telah diberikan dlm 60 menit terakhir (atau N/A).</label>
                    </div>
                    
                    <hr>
                    <strong class="d-block mb-1 text-primary">Antisipasi Kejadian Kritis</strong>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[antisipasi_bedah]" value="Ya" id="to_4">
                        <label class="form-check-label fw-semibold" for="to_4">Dokter Bedah menyampaikan kemungkinan kesulitan / lama waktu / kemungkinan darah hilang.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[antisipasi_anestesi]" value="Ya" id="to_5">
                        <label class="form-check-label fw-semibold" for="to_5">Dokter Anestesi menyampaikan concern khusus mengenai pasien.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[antisipasi_perawat]" value="Ya" id="to_6">
                        <label class="form-check-label fw-semibold" for="to_6">Perawat memastikan sterilitas alat & indikator hasil.</label>
                    </div>
                    
                    <hr>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[radiologi_tampil]" value="Ya" id="to_7">
                        <label class="form-check-label fw-semibold" for="to_7">Hasil radiologi penting sudah ditayangkan di layar (atau N/A).</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. SIGN OUT -->
        <div class="col-md-4 mb-3">
            <div class="card card-custom h-100" style="border-top: 4px solid #2ecc71;">
                <div class="card-header bg-light fw-bold text-dark"><i class="fas fa-sign-out-alt me-2 text-success"></i>3. SIGN OUT (Sebelum Pasien Keluar)</div>
                <div class="card-body" style="font-size: 0.9rem;">
                    <p class="text-muted small">Perawat membacakan secara verbal bersama dokter bedah dan anestesi.</p>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[nama_tindakan]" value="Ya" id="so_1">
                        <label class="form-check-label fw-semibold" for="so_1">Nama prosedur/tindakan telah dicatat dengan benar.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[hitung_alat]" value="Ya" id="so_2">
                        <label class="form-check-label fw-semibold" for="so_2">Penghitungan instrumen, kasa, dan jarum telah sesuai (lengkap).</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[label_spesimen]" value="Ya" id="so_3">
                        <label class="form-check-label fw-semibold" for="so_3">Pelabelan spesimen telah benar (bila ada, bacakan label tsb).</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[masalah_alat]" value="Ya" id="so_4">
                        <label class="form-check-label fw-semibold" for="so_4">Adakah masalah pada peralatan yang harus diatasi?</label>
                    </div>
                    
                    <hr>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[pesan_recovery]" value="Ya" id="so_5">
                        <label class="form-check-label fw-semibold" for="so_5">Dokter bedah, anestesi & perawat bersama mengulas kunci manajemen pasien (instruksi recovery room).</label>
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label fw-bold">Waktu Selesai (Sign Out)</label>
                        <input type="time" class="form-control form-control-sm" name="sign_out[waktu]" required value="{{ date('H:i') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <a href="/surgical/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Checklist</button>
    </div>
</form>
@endsection
