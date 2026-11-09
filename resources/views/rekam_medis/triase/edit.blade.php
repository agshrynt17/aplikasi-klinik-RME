@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/triase/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Triase Pasien
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/triase/{{ $triase->id }}" method="POST">
    @csrf
    @method('PUT')
    
    <input type="hidden" name="pegawai_id" value="{{ $triase->pegawai_id }}">

    <!-- Informasi Kedatangan -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-ambulance me-2 text-danger"></i>Informasi Kedatangan</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>No. RM / Nama:</strong><br>
                    {{ $pendaftaran->pasien->no_rm }} - {{ $pendaftaran->pasien->nama_pasien }}
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Waktu Triase</label>
                    <input type="datetime-local" class="form-control" name="waktu_triase" value="{{ \Carbon\Carbon::parse($triase->waktu_triase)->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Cara Datang</label>
                    <select class="form-select" name="cara_datang">
                        <option value="Sendiri" {{ $triase->cara_datang == 'Sendiri' ? 'selected' : '' }}>Sendiri</option>
                        <option value="Diantar Keluarga" {{ $triase->cara_datang == 'Diantar Keluarga' ? 'selected' : '' }}>Diantar Keluarga</option>
                        <option value="Ambulans" {{ $triase->cara_datang == 'Ambulans' ? 'selected' : '' }}>Ambulans</option>
                        <option value="Polisi" {{ $triase->cara_datang == 'Polisi' ? 'selected' : '' }}>Polisi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Asal Rujukan</label>
                    <select class="form-select" name="asal_rujukan">
                        <option value="Datang Sendiri (Non Rujukan)" {{ $triase->asal_rujukan == 'Datang Sendiri (Non Rujukan)' ? 'selected' : '' }}>Datang Sendiri (Non Rujukan)</option>
                        <option value="Puskesmas" {{ $triase->asal_rujukan == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                        <option value="Klinik" {{ $triase->asal_rujukan == 'Klinik' ? 'selected' : '' }}>Klinik</option>
                        <option value="RS Lain" {{ $triase->asal_rujukan == 'RS Lain' ? 'selected' : '' }}>RS Lain</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Keluhan Utama / Alasan Datang <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="keluhan_utama" rows="2" required>{{ $triase->keluhan_utama }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Primary Survey -->
    <div class="card card-custom mb-3 border-danger">
        <div class="card-header bg-danger text-white fw-bold"><i class="fas fa-heartbeat me-2"></i>Primary Survey & Tanda Vital</div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jalan Napas (A)</label>
                    <select name="jalan_napas" class="form-select border-danger">
                        <option value="Bebas" {{ $triase->jalan_napas == 'Bebas' ? 'selected' : '' }}>Bebas</option>
                        <option value="Sumbatan Parsial" {{ $triase->jalan_napas == 'Sumbatan Parsial' ? 'selected' : '' }}>Sumbatan Parsial</option>
                        <option value="Sumbatan Total" {{ $triase->jalan_napas == 'Sumbatan Total' ? 'selected' : '' }}>Sumbatan Total</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Pernapasan (B)</label>
                    <select name="pernapasan" class="form-select border-danger">
                        <option value="Spontan / Normal" {{ $triase->pernapasan == 'Spontan / Normal' ? 'selected' : '' }}>Spontan / Normal</option>
                        <option value="Takipnea" {{ $triase->pernapasan == 'Takipnea' ? 'selected' : '' }}>Takipnea</option>
                        <option value="Apnea" {{ $triase->pernapasan == 'Apnea' ? 'selected' : '' }}>Apnea</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sirkulasi (C)</label>
                    <select name="sirkulasi" class="form-select border-danger">
                        <option value="Normal" {{ $triase->sirkulasi == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Pucat / CRT > 2s" {{ $triase->sirkulasi == 'Pucat / CRT > 2s' ? 'selected' : '' }}>Pucat / CRT > 2s</option>
                        <option value="Sianosis" {{ $triase->sirkulasi == 'Sianosis' ? 'selected' : '' }}>Sianosis</option>
                        <option value="Henti Jantung" {{ $triase->sirkulasi == 'Henti Jantung' ? 'selected' : '' }}>Henti Jantung</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kesadaran (D)</label>
                    <select name="kesadaran" class="form-select border-danger">
                        <option value="Sadar Penuh (CM)" {{ $triase->kesadaran == 'Sadar Penuh (CM)' ? 'selected' : '' }}>Sadar Penuh (CM)</option>
                        <option value="Respon Suara (Apatis)" {{ $triase->kesadaran == 'Respon Suara (Apatis)' ? 'selected' : '' }}>Respon Suara</option>
                        <option value="Respon Nyeri (Sopor)" {{ $triase->kesadaran == 'Respon Nyeri (Sopor)' ? 'selected' : '' }}>Respon Nyeri</option>
                        <option value="Tidak Sadar (Koma)" {{ $triase->kesadaran == 'Tidak Sadar (Koma)' ? 'selected' : '' }}>Tidak Sadar</option>
                    </select>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">GCS</label>
                    <input type="text" name="gcs" class="form-control" value="{{ $triase->gcs }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Tensi (mmHg)</label>
                    <input type="text" name="tensi" class="form-control" value="{{ $triase->tensi }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Nadi (x/mnt)</label>
                    <input type="text" name="nadi" class="form-control" value="{{ $triase->nadi }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Suhu (°C)</label>
                    <input type="text" name="suhu" class="form-control" value="{{ $triase->suhu }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">RR (x/mnt)</label>
                    <input type="text" name="rr" class="form-control" value="{{ $triase->rr }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">SpO2 (%)</label>
                    <input type="text" name="spo2" class="form-control" value="{{ $triase->spo2 }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Kesimpulan Kategori -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-dark text-white fw-bold">Kesimpulan Triase</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold d-block">Kategori Triase (Berdasarkan Kegawatan) <span class="text-danger">*</span></label>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_triase" id="ats1" value="ATS 1 - Merah (Resusitasi)" {{ $triase->kategori_triase == 'ATS 1 - Merah (Resusitasi)' ? 'checked' : '' }} required>
                        <label class="form-check-label text-danger fw-bold" for="ats1">🔴 ATS 1 - Merah</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_triase" id="ats2" value="ATS 2 - Merah (Emergent)" {{ $triase->kategori_triase == 'ATS 2 - Merah (Emergent)' ? 'checked' : '' }}>
                        <label class="form-check-label text-danger fw-bold" for="ats2">🔴 ATS 2 - Merah</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_triase" id="ats3" value="ATS 3 - Kuning (Urgent)" {{ $triase->kategori_triase == 'ATS 3 - Kuning (Urgent)' ? 'checked' : '' }}>
                        <label class="form-check-label text-warning fw-bold" style="text-shadow: 0 0 1px #000;" for="ats3">🟡 ATS 3 - Kuning</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_triase" id="ats4" value="ATS 4 - Hijau (Less Urgent)" {{ $triase->kategori_triase == 'ATS 4 - Hijau (Less Urgent)' ? 'checked' : '' }}>
                        <label class="form-check-label text-success fw-bold" for="ats4">🟢 ATS 4 - Hijau</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_triase" id="ats5" value="ATS 5 - Hijau (Non Urgent)" {{ $triase->kategori_triase == 'ATS 5 - Hijau (Non Urgent)' ? 'checked' : '' }}>
                        <label class="form-check-label text-success fw-bold" for="ats5">🟢 ATS 5 - Hijau</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_triase" id="doa" value="DOA - Hitam (Meninggal)" {{ $triase->kategori_triase == 'DOA - Hitam (Meninggal)' ? 'checked' : '' }}>
                        <label class="form-check-label text-dark fw-bold" for="doa">⚫ Hitam (DOA)</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Keterangan / Tindakan Awal</label>
                    <textarea class="form-control" name="keterangan" rows="2">{{ $triase->keterangan }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-outline-danger me-2" onclick="hapusTriase()">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/rekam-medis/triase" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Triase</button>
    </div>
</form>

<script>
function hapusTriase() {
    if(confirm('Apakah Anda yakin ingin menghapus data triase ini?')) {
        $.ajax({
            url: '/rekam-medis/triase/{{ $triase->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/triase/index';
            }
        });
    }
}
</script>
@endsection
