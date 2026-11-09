@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/surgical/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Surgical Safety Ceklist
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/surgical/{{ $ssc->id }}" method="POST">
    @csrf
    @method('PUT')
    
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
                        <option value="{{ $ssc->dokter_id }}" selected>{{ $ssc->dokter->nama_pegawai ?? 'Dokter' }}</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-bold">Perawat Sirkuler (Checklist Coordinator)</label>
                    <input type="text" name="perawat_sirkuler" class="form-control form-control-sm" required value="{{ $ssc->perawat_sirkuler }}">
                </div>
            </div>
        </div>
    </div>

    @php
        $si = $ssc->sign_in_json ?? [];
        $to = $ssc->time_out_json ?? [];
        $so = $ssc->sign_out_json ?? [];
    @endphp

    <div class="row">
        <!-- 1. SIGN IN -->
        <div class="col-md-4 mb-3">
            <div class="card card-custom h-100" style="border-top: 4px solid #f39c12;">
                <div class="card-header bg-light fw-bold text-dark"><i class="fas fa-sign-in-alt me-2 text-warning"></i>1. SIGN IN (Sebelum Induksi)</div>
                <div class="card-body" style="font-size: 0.9rem;">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_in[identitas]" value="Ya" id="si_1" {{ isset($si['identitas']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="si_1">Pasien telah dikonfirmasi identitas, area operasi, prosedur, dan lembar persetujuan (informed consent).</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_in[lokasi_ditandai]" value="Ya" id="si_2" {{ isset($si['lokasi_ditandai']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="si_2">Area operasi telah ditandai.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_in[mesin_anestesi]" value="Ya" id="si_3" {{ isset($si['mesin_anestesi']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="si_3">Mesin anestesi dan obat-obatan telah dicek kesiapannya.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_in[pulse_oximeter]" value="Ya" id="si_4" {{ isset($si['pulse_oximeter']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="si_4">Pulse oximeter pada pasien berfungsi baik.</label>
                    </div>
                    
                    <hr>
                    <strong class="d-block mb-1">Apakah pasien mempunyai alergi?</strong>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[alergi]" value="Tidak" id="si_5_tdk" {{ (isset($si['alergi']) && $si['alergi'] == 'Tidak') ? 'checked' : (empty($si['alergi']) ? 'checked' : '') }}>
                        <label class="form-check-label" for="si_5_tdk">Tidak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[alergi]" value="Ya" id="si_5_ya" {{ (isset($si['alergi']) && $si['alergi'] == 'Ya') ? 'checked' : '' }}>
                        <label class="form-check-label" for="si_5_ya">Ya</label>
                    </div>
                    
                    <hr>
                    <strong class="d-block mb-1">Risiko kesulitan jalan napas atau aspirasi?</strong>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[risiko_napas]" value="Tidak" id="si_6_tdk" {{ (isset($si['risiko_napas']) && $si['risiko_napas'] == 'Tidak') ? 'checked' : (empty($si['risiko_napas']) ? 'checked' : '') }}>
                        <label class="form-check-label" for="si_6_tdk">Tidak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[risiko_napas]" value="Ya" id="si_6_ya" {{ (isset($si['risiko_napas']) && $si['risiko_napas'] == 'Ya') ? 'checked' : '' }}>
                        <label class="form-check-label" for="si_6_ya">Ya, alat & asisten tersedia</label>
                    </div>
                    
                    <hr>
                    <strong class="d-block mb-1">Risiko kehilangan darah > 500ml?</strong>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[risiko_darah]" value="Tidak" id="si_7_tdk" {{ (isset($si['risiko_darah']) && $si['risiko_darah'] == 'Tidak') ? 'checked' : (empty($si['risiko_darah']) ? 'checked' : '') }}>
                        <label class="form-check-label" for="si_7_tdk">Tidak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sign_in[risiko_darah]" value="Ya" id="si_7_ya" {{ (isset($si['risiko_darah']) && $si['risiko_darah'] == 'Ya') ? 'checked' : '' }}>
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
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[perkenalan_tim]" value="Ya" id="to_1" {{ isset($to['perkenalan_tim']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="to_1">Setiap anggota tim operasi memperkenalkan nama dan peran masing-masing.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[konfirmasi_pasien]" value="Ya" id="to_2" {{ isset($to['konfirmasi_pasien']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="to_2">Dokter bedah, anestesi, dan perawat secara verbal mengonfirmasi: Nama Pasien, Area insisi, dan Prosedur yang dilakukan.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[antibiotik_profilaksis]" value="Ya" id="to_3" {{ isset($to['antibiotik_profilaksis']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="to_3">Antibiotik profilaksis telah diberikan dlm 60 menit terakhir (atau N/A).</label>
                    </div>
                    
                    <hr>
                    <strong class="d-block mb-1 text-primary">Antisipasi Kejadian Kritis</strong>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[antisipasi_bedah]" value="Ya" id="to_4" {{ isset($to['antisipasi_bedah']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="to_4">Dokter Bedah menyampaikan kemungkinan kesulitan / lama waktu / kemungkinan darah hilang.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[antisipasi_anestesi]" value="Ya" id="to_5" {{ isset($to['antisipasi_anestesi']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="to_5">Dokter Anestesi menyampaikan concern khusus mengenai pasien.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[antisipasi_perawat]" value="Ya" id="to_6" {{ isset($to['antisipasi_perawat']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="to_6">Perawat memastikan sterilitas alat & indikator hasil.</label>
                    </div>
                    
                    <hr>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="time_out[radiologi_tampil]" value="Ya" id="to_7" {{ isset($to['radiologi_tampil']) ? 'checked' : '' }}>
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
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[nama_tindakan]" value="Ya" id="so_1" {{ isset($so['nama_tindakan']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="so_1">Nama prosedur/tindakan telah dicatat dengan benar.</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[hitung_alat]" value="Ya" id="so_2" {{ isset($so['hitung_alat']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="so_2">Penghitungan instrumen, kasa, dan jarum telah sesuai (lengkap).</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[label_spesimen]" value="Ya" id="so_3" {{ isset($so['label_spesimen']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="so_3">Pelabelan spesimen telah benar (bila ada, bacakan label tsb).</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[masalah_alat]" value="Ya" id="so_4" {{ isset($so['masalah_alat']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="so_4">Adakah masalah pada peralatan yang harus diatasi?</label>
                    </div>
                    
                    <hr>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sign_out[pesan_recovery]" value="Ya" id="so_5" {{ isset($so['pesan_recovery']) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="so_5">Dokter bedah, anestesi & perawat bersama mengulas kunci manajemen pasien (instruksi recovery room).</label>
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label fw-bold">Waktu Selesai (Sign Out)</label>
                        <input type="time" class="form-control form-control-sm" name="sign_out[waktu]" required value="{{ $so['waktu'] ?? date('H:i') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-outline-danger me-2" onclick="hapusSSC()">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/surgical/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Checklist</button>
    </div>
</form>

<script>
function hapusSSC() {
    if(confirm('Apakah Anda yakin ingin menghapus data Surgical Safety Checklist ini?')) {
        $.ajax({
            url: '/surgical/{{ $ssc->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/surgical/index';
            }
        });
    }
}
</script>
@endsection
