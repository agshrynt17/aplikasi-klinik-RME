@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/asesmen-awal-rajal" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Asesmen Awal Rawat Jalan
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/asesmen-awal-rajal/{{ $asesmen->id }}" method="POST">
    @csrf
    @method('PUT')

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

    <!-- Anamnesa (Subjektif) -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-comments-medical me-2"></i>Anamnesa (Subjektif)</div>
        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Keluhan Utama</label>
                <textarea name="keluhan_utama" class="form-control" rows="3" required>{{ old('keluhan_utama', $asesmen->keluhan_utama) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Riwayat Penyakit Sekarang</label>
                <textarea name="riwayat_penyakit_sekarang" class="form-control" rows="3">{{ old('riwayat_penyakit_sekarang', $asesmen->riwayat_penyakit_sekarang) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Riwayat Penyakit Dahulu</label>
                <textarea name="riwayat_penyakit_dahulu" class="form-control" rows="2">{{ old('riwayat_penyakit_dahulu', $asesmen->riwayat_penyakit_dahulu) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Riwayat Alergi</label>
                <input type="text" name="riwayat_alergi" class="form-control" value="{{ old('riwayat_alergi', $asesmen->riwayat_alergi) }}" placeholder="Obat / Makanan (jika ada)">
            </div>
        </div>
    </div>

    <!-- Tanda Vital & Pemeriksaan Fisik (Objektif) -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-heartbeat me-2"></i>Tanda Vital & Pemeriksaan Fisik (Objektif)</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tensi (mmHg)</label>
                    <input type="text" name="tensi" class="form-control" value="{{ old('tensi', $asesmen->tensi) }}" placeholder="120/80">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Nadi (x/mnt)</label>
                    <input type="text" name="nadi" class="form-control" value="{{ old('nadi', $asesmen->nadi) }}" placeholder="80">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Suhu (°C)</label>
                    <input type="text" name="suhu" class="form-control" value="{{ old('suhu', $asesmen->suhu) }}" placeholder="36.5">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">RR (x/mnt)</label>
                    <input type="text" name="rr" class="form-control" value="{{ old('rr', $asesmen->rr) }}" placeholder="20">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Berat Badan (kg)</label>
                    <input type="text" name="berat_badan" class="form-control" value="{{ old('berat_badan', $asesmen->berat_badan) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tinggi Badan (cm)</label>
                    <input type="text" name="tinggi_badan" class="form-control" value="{{ old('tinggi_badan', $asesmen->tinggi_badan) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Pemeriksaan Fisik Lainnya</label>
                <textarea name="pemeriksaan_fisik" class="form-control" rows="3">{{ old('pemeriksaan_fisik', $asesmen->pemeriksaan_fisik) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Asesmen & Rencana Tindakan -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-stethoscope me-2"></i>Asesmen & Rencana (A & P)</div>
        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Diagnosis / Asesmen Medis</label>
                <textarea name="diagnosis" class="form-control" rows="4" required>{{ old('diagnosis', $asesmen->diagnosis) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Rencana Tindakan / Terapi</label>
                <textarea name="tindakan_rencana" class="form-control" rows="4" required>{{ old('tindakan_rencana', $asesmen->tindakan_rencana) }}</textarea>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-danger me-2" onclick="hapusAsesmen()">
            <i class="fas fa-trash me-1"></i> Hapus Asesmen
        </button>
        <a href="/rekam-medis/asesmen-awal-rajal" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Asesmen</button>
    </div>
</form>

<script>
function hapusAsesmen() {
    if(confirm('Apakah Anda yakin ingin menghapus data asesmen ini?')) {
        $.ajax({
            url: '/rekam-medis/asesmen-awal-rajal/{{ $asesmen->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/rekam-medis/asesmen-awal-rajal';
            }
        });
    }
}
</script>
@endsection
