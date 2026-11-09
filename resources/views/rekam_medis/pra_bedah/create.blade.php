@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/prabedah/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Pengkajian Pra Bedah (Pre-Op)
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/pra-bedah" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">
    <input type="hidden" name="dokter_id" value="{{ auth()->user()->pegawai_id ?? $pendaftaran->dokter_id }}">

    <!-- Informasi Pasien -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-user-injured me-2 text-primary"></i>Informasi Pasien</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>No. RM / Nama:</strong><br>
                    {{ $pendaftaran->pasien->no_rm }} - {{ $pendaftaran->pasien->nama_pasien }}
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Waktu Pengkajian</label>
                    <input type="datetime-local" class="form-control" name="waktu_pengkajian" value="{{ date('Y-m-d\TH:i') }}" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Rencana & Diagnosa -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-primary text-white fw-bold"><i class="fas fa-stethoscope me-2"></i>Diagnosa & Rencana Tindakan</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Diagnosa Pra Bedah <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="diagnosa_pra_bedah" rows="2" required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Rencana Tindakan Operasi <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="rencana_tindakan" rows="2" required></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-semibold">Riwayat Penyakit Dahulu / Penyerta (Asma, DM, Hipertensi, Alergi Obat)</label>
                    <textarea class="form-control" name="riwayat_penyakit" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Tanda Vital & Penunjang -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-success text-white fw-bold"><i class="fas fa-heartbeat me-2"></i>Tanda Vital & Persiapan</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="form-label fw-semibold">Tensi (mmHg)</label>
                    <input type="text" name="tensi" class="form-control">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label fw-semibold">Nadi (x/mnt)</label>
                    <input type="text" name="nadi" class="form-control">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label fw-semibold">Suhu (°C)</label>
                    <input type="text" name="suhu" class="form-control">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label fw-semibold">RR (x/mnt)</label>
                    <input type="text" name="rr" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Pemeriksaan Penunjang (Lab/Rad/EKG)</label>
                    <textarea class="form-control" name="pemeriksaan_penunjang" rows="2" placeholder="Hasil Lab / EKG DBN dll..."></textarea>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Persiapan Darah</label>
                    <input type="text" name="persiapan_darah" class="form-control" placeholder="Misal: 2 Kantong PRC / Tidak Ada">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Mulai Puasa Jam</label>
                    <input type="text" name="puasa_mulai" class="form-control" placeholder="Misal: Jam 22:00">
                </div>
            </div>
        </div>
    </div>

    <!-- Kesimpulan Resiko -->
    <div class="card card-custom mb-3 border-warning">
        <div class="card-header bg-warning text-dark fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Kesimpulan & Risiko Operasi</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Risiko Operasi / Pembiusan</label>
                    <select class="form-select border-warning" name="resiko_operasi">
                        <option value="Rendah">Rendah</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Tinggi">Tinggi</option>
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-semibold">Keterangan / Catatan Tambahan</label>
                    <textarea class="form-control" name="keterangan" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <a href="/prabedah/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Pra Bedah</button>
    </div>
</form>
@endsection
