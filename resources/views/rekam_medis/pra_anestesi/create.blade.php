@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/praanestesi/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Pengkajian Pra Anestesi
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/pra-anestesi" method="POST">
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

    <!-- Riwayat Pasien -->
    <div class="card card-custom mb-3 border-info">
        <div class="card-header bg-info text-white fw-bold"><i class="fas fa-history me-2"></i>Anamnesis & Riwayat</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Riwayat Alergi (Obat/Makanan)</label>
                    <textarea class="form-control" name="riwayat_alergi" rows="2" placeholder="Tidak ada / Sebutkan..."></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Riwayat Penyakit Sistemik (Asma, Jantung, HT, DM)</label>
                    <textarea class="form-control" name="riwayat_penyakit" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Riwayat Operasi Sebelumnya</label>
                    <textarea class="form-control" name="riwayat_operasi" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Riwayat Anestesi Sebelumnya (Komplikasi dll)</label>
                    <textarea class="form-control" name="riwayat_anestesi" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Fisik & Penunjang -->
    <div class="card card-custom mb-3 border-success">
        <div class="card-header bg-success text-white fw-bold"><i class="fas fa-heartbeat me-2"></i>Pemeriksaan Fisik & Penunjang</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">Tensi (mmHg)</label>
                    <input type="text" name="tensi" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">Nadi (x/mnt)</label>
                    <input type="text" name="nadi" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">Suhu (°C)</label>
                    <input type="text" name="suhu" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">RR (x/mnt)</label>
                    <input type="text" name="rr" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">TB (cm)</label>
                    <input type="text" name="tb" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">BB (kg)</label>
                    <input type="text" name="bb" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Penyulit Jalan Napas (Mallampati, dll)</label>
                    <input type="text" name="jalan_napas" class="form-control" placeholder="Mallampati 1, Gigi Palsu (-)...">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Pemeriksaan Fisik Lain (Jantung, Paru)</label>
                    <textarea class="form-control" name="pemeriksaan_fisik" rows="2" placeholder="Cor: S1S2 Reguler. Pulmo: Vesikuler..."></textarea>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Pemeriksaan Penunjang (Lab/EKG/Ro)</label>
                    <textarea class="form-control" name="pemeriksaan_penunjang" rows="2" placeholder="Hb: 13, EKG: SR..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Kesimpulan Resiko ASA -->
    <div class="card card-custom mb-3 border-danger">
        <div class="card-header bg-danger text-white fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Klasifikasi ASA & Rencana Anestesi</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">ASA Score <span class="text-danger">*</span></label>
                    <select class="form-select border-danger" name="asa_score" required>
                        <option value="ASA 1">ASA 1 (Normal sehat)</option>
                        <option value="ASA 2">ASA 2 (Penyakit sistemik ringan)</option>
                        <option value="ASA 3">ASA 3 (Penyakit sistemik berat)</option>
                        <option value="ASA 4">ASA 4 (Penyakit sistemik mengancam nyawa)</option>
                        <option value="ASA 5">ASA 5 (Harapan hidup < 24 jam)</option>
                        <option value="ASA 1 E">ASA 1 E (Darurat)</option>
                        <option value="ASA 2 E">ASA 2 E (Darurat)</option>
                        <option value="ASA 3 E">ASA 3 E (Darurat)</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Rencana Teknik Anestesi <span class="text-danger">*</span></label>
                    <select class="form-select border-danger" name="rencana_anestesi" required>
                        <option value="General Anesthesia (GA)">General Anesthesia (GA)</option>
                        <option value="Spinal Anesthesia (SAB)">Spinal Anesthesia (SAB)</option>
                        <option value="Epidural">Epidural</option>
                        <option value="Lokal Anestesi">Lokal Anestesi</option>
                        <option value="TIVA">TIVA</option>
                        <option value="Lainnya">Lainnya...</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Catatan / Pre-medikasi Khusus</label>
                    <textarea class="form-control" name="keterangan" rows="2" placeholder="Puasa mulai jam..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <a href="/praanestesi/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-danger"><i class="fas fa-save me-1"></i> Simpan Pra Anestesi</button>
    </div>
</form>
@endsection
