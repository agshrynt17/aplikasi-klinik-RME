@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/asesmen-gizi" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Asesmen Gizi (ADIME)
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/asesmen-gizi/{{ $asesmen->id }}" method="POST">
    @csrf
    @method('PUT')
    
    <input type="hidden" name="pegawai_id" value="{{ $asesmen->pegawai_id }}">

    <!-- Informasi Pasien -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-user-injured me-2"></i>Informasi Pasien</div>
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
                    <strong>Poliklinik:</strong> {{ $pendaftaran->poliklinik->nama_poli ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Dokter:</strong> {{ $pendaftaran->dokter->nama_pegawai ?? '-' }}<br>
                    <strong>Waktu Asesmen:</strong> {{ \Carbon\Carbon::parse($asesmen->tanggal_asesmen)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Assessment (A) -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-clipboard-list me-2"></i>Assessment Gizi (A)</div>
        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Antropometri (TB, BB, IMT, dll)</label>
                <textarea name="antropometri" class="form-control" rows="3">{{ old('antropometri', $asesmen->antropometri) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Biokimia (Hasil Lab Terkait)</label>
                <textarea name="biokimia" class="form-control" rows="3">{{ old('biokimia', $asesmen->biokimia) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Klinis & Fisik</label>
                <textarea name="klinis_fisik" class="form-control" rows="2">{{ old('klinis_fisik', $asesmen->klinis_fisik) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Riwayat Makan (Dietary History)</label>
                <textarea name="riwayat_makan" class="form-control" rows="2">{{ old('riwayat_makan', $asesmen->riwayat_makan) }}</textarea>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label fw-semibold">Riwayat Personal</label>
                <textarea name="riwayat_personal" class="form-control" rows="2">{{ old('riwayat_personal', $asesmen->riwayat_personal) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Diagnosis, Intervensi, Monev (DIME) -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-apple-alt me-2"></i>Diagnosis, Intervensi, Monev (D-I-ME)</div>
        <div class="card-body row">
            <div class="col-md-12 mb-3">
                <label class="form-label fw-semibold">Diagnosis Gizi (D)</label>
                <textarea name="diagnosis_gizi" class="form-control" rows="3" required>{{ old('diagnosis_gizi', $asesmen->diagnosis_gizi) }}</textarea>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label fw-semibold">Intervensi Gizi (I)</label>
                <textarea name="intervensi_gizi" class="form-control" rows="3" required>{{ old('intervensi_gizi', $asesmen->intervensi_gizi) }}</textarea>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label fw-semibold">Monitoring & Evaluasi (ME)</label>
                <textarea name="monitoring_evaluasi" class="form-control" rows="3">{{ old('monitoring_evaluasi', $asesmen->monitoring_evaluasi) }}</textarea>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-danger me-2" onclick="hapusAsesmenGizi()">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/rekam-medis/asesmen-gizi" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Asesmen Gizi</button>
    </div>
</form>

<script>
function hapusAsesmenGizi() {
    if(confirm('Apakah Anda yakin ingin menghapus data asesmen gizi ini?')) {
        $.ajax({
            url: '/rekam-medis/asesmen-gizi/{{ $asesmen->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/rekam-medis/asesmen-gizi';
            }
        });
    }
}
</script>
@endsection
