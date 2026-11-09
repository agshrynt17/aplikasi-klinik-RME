@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/asesmen-igd" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Asesmen Awal Medis IGD
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/asesmen-igd/{{ $asesmen->id }}" method="POST">
    @csrf
    @method('PUT')
    
    <input type="hidden" name="dokter_id" value="{{ $asesmen->dokter_id }}">

    <!-- Informasi Pasien -->
    <div class="card card-custom border-danger mb-3">
        <div class="card-header bg-danger text-white fw-bold"><i class="fas fa-user-injured me-2"></i>Informasi Pasien IGD</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>No. Rawat:</strong> {{ $pendaftaran->no_rawat }}<br>
                    <strong>No. RM:</strong> {{ $pendaftaran->pasien->no_rm }}<br>
                    <strong>Nama:</strong> {{ $pendaftaran->pasien->nama_pasien }}
                </div>
                <div class="col-md-4">
                    <strong>Tgl Lahir:</strong> {{ $pendaftaran->pasien->tanggal_lahir ? $pendaftaran->pasien->tanggal_lahir->format('d/m/Y') : '-' }}<br>
                    <strong>Jenis Kelamin:</strong> {{ $pendaftaran->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}<br>
                </div>
                <div class="col-md-4">
                    <strong>Waktu Asesmen:</strong> {{ \Carbon\Carbon::parse($asesmen->tanggal_asesmen)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Triage & Primary Survey -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-ambulance me-2 text-danger"></i>Triage & Primary Survey</div>
        <div class="card-body row">
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Skala Triage (Warna)</label>
                <select name="skala_triage" class="form-select">
                    <option value="">Pilih...</option>
                    <option value="Merah (Gawat Darurat)" {{ old('skala_triage', $asesmen->skala_triage) == 'Merah (Gawat Darurat)' ? 'selected' : '' }}>🔴 Merah (Gawat Darurat)</option>
                    <option value="Kuning (Gawat Tidak Darurat)" {{ old('skala_triage', $asesmen->skala_triage) == 'Kuning (Gawat Tidak Darurat)' ? 'selected' : '' }}>🟡 Kuning (Gawat Tidak Darurat)</option>
                    <option value="Hijau (Darurat Tidak Gawat)" {{ old('skala_triage', $asesmen->skala_triage) == 'Hijau (Darurat Tidak Gawat)' ? 'selected' : '' }}>🟢 Hijau (Darurat Tidak Gawat)</option>
                    <option value="Hitam (Meninggal)" {{ old('skala_triage', $asesmen->skala_triage) == 'Hitam (Meninggal)' ? 'selected' : '' }}>⚫ Hitam (Meninggal)</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Jalan Napas (Airway)</label>
                <select name="jalan_napas" class="form-select">
                    <option value="Bebas" {{ old('jalan_napas', $asesmen->jalan_napas) == 'Bebas' ? 'selected' : '' }}>Bebas</option>
                    <option value="Tersumbat Sebagian" {{ old('jalan_napas', $asesmen->jalan_napas) == 'Tersumbat Sebagian' ? 'selected' : '' }}>Tersumbat Sebagian</option>
                    <option value="Tersumbat Total" {{ old('jalan_napas', $asesmen->jalan_napas) == 'Tersumbat Total' ? 'selected' : '' }}>Tersumbat Total</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Pernapasan (Breathing)</label>
                <select name="pernapasan" class="form-select">
                    <option value="Normal" {{ old('pernapasan', $asesmen->pernapasan) == 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Sesak" {{ old('pernapasan', $asesmen->pernapasan) == 'Sesak' ? 'selected' : '' }}>Sesak</option>
                    <option value="Henti Napas" {{ old('pernapasan', $asesmen->pernapasan) == 'Henti Napas' ? 'selected' : '' }}>Henti Napas</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Sirkulasi (Circulation)</label>
                <select name="sirkulasi" class="form-select">
                    <option value="Normal" {{ old('sirkulasi', $asesmen->sirkulasi) == 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Pucat" {{ old('sirkulasi', $asesmen->sirkulasi) == 'Pucat' ? 'selected' : '' }}>Pucat</option>
                    <option value="Sianosis" {{ old('sirkulasi', $asesmen->sirkulasi) == 'Sianosis' ? 'selected' : '' }}>Sianosis</option>
                    <option value="Henti Jantung" {{ old('sirkulasi', $asesmen->sirkulasi) == 'Henti Jantung' ? 'selected' : '' }}>Henti Jantung</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Anamnesa & Tanda Vital -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-stethoscope me-2"></i>Anamnesa & Tanda Vital</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Keluhan Utama</label>
                    <textarea name="keluhan_utama" class="form-control" rows="2" required>{{ old('keluhan_utama', $asesmen->keluhan_utama) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Riwayat Penyakit (Sekarang/Dahulu/Alergi)</label>
                    <textarea name="riwayat_penyakit" class="form-control" rows="2">{{ old('riwayat_penyakit', $asesmen->riwayat_penyakit) }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Kesadaran (GCS)</label>
                    <input type="text" name="kesadaran" class="form-control" value="{{ old('kesadaran', $asesmen->kesadaran) }}" placeholder="E4 V5 M6">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Tensi (mmHg)</label>
                    <input type="text" name="tensi" class="form-control" value="{{ old('tensi', $asesmen->tensi) }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Nadi (x/mnt)</label>
                    <input type="text" name="nadi" class="form-control" value="{{ old('nadi', $asesmen->nadi) }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Suhu (°C)</label>
                    <input type="text" name="suhu" class="form-control" value="{{ old('suhu', $asesmen->suhu) }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">RR (x/mnt)</label>
                    <input type="text" name="rr" class="form-control" value="{{ old('rr', $asesmen->rr) }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Fisik, Diagnosis, Plan -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-file-medical me-2"></i>Pemeriksaan, Diagnosis & Rencana</div>
        <div class="card-body row">
            <div class="col-md-12 mb-3">
                <label class="form-label fw-semibold">Pemeriksaan Fisik (Head to Toe)</label>
                <textarea name="pemeriksaan_fisik" class="form-control" rows="3">{{ old('pemeriksaan_fisik', $asesmen->pemeriksaan_fisik) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Diagnosis Medis / Kerja (Asesmen)</label>
                <textarea name="diagnosis" class="form-control" rows="3" required>{{ old('diagnosis', $asesmen->diagnosis) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Rencana Tindakan / Terapi (Plan)</label>
                <textarea name="tindakan_rencana" class="form-control" rows="3" required>{{ old('tindakan_rencana', $asesmen->tindakan_rencana) }}</textarea>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-danger me-2" onclick="hapusAsesmenIgd()">
            <i class="fas fa-trash me-1"></i> Hapus Asesmen
        </button>
        <a href="/rekam-medis/asesmen-igd" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Asesmen IGD</button>
    </div>
</form>

<script>
function hapusAsesmenIgd() {
    if(confirm('Apakah Anda yakin ingin menghapus data asesmen IGD ini?')) {
        $.ajax({
            url: '/rekam-medis/asesmen-igd/{{ $asesmen->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/rekam-medis/asesmen-igd';
            }
        });
    }
}
</script>
@endsection
