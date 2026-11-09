@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/ppa/{{ $pendaftaran->id }}" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Tambah Catatan CPPT
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/ppa" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">
    
    <div class="card card-custom mb-4">
        <div class="card-header bg-light fw-bold">
            Form Catatan Perkembangan Pasien Terintegrasi (CPPT)
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Tanggal & Jam</label>
                    <input type="datetime-local" name="waktu" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Profesi PPA</label>
                    <select name="profesi" class="form-select" required>
                        <option value="Dokter">Dokter / DPJP</option>
                        <option value="Perawat">Perawat</option>
                        <option value="Bidan">Bidan</option>
                        <option value="Apoteker">Apoteker</option>
                        <option value="Ahli Gizi">Ahli Gizi</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label fw-bold">Nama PPA</label>
                    <!-- In a real app, this might be auto-filled by auth user. We allow manual select for demo -->
                    <select name="pegawai_id" class="form-select">
                        <option value="">- Sesuai Login -</option>
                        <option value="{{ $pendaftaran->dokter_id }}">{{ $pendaftaran->dokter->nama_pegawai ?? 'Dokter DPJP' }}</option>
                    </select>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-primary">S - Subjective (Subjektif)</label>
                    <textarea name="subjective" class="form-control" rows="4" placeholder="Keluhan pasien, anamnesa..."></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-success">O - Objective (Objektif)</label>
                    <textarea name="objective" class="form-control" rows="4" placeholder="Hasil pemeriksaan fisik, tanda vital, lab..."></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-danger">A - Assessment (Asesmen)</label>
                    <textarea name="assessment" class="form-control" rows="4" placeholder="Diagnosis, masalah..."></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-info">P - Plan (Perencanaan)</label>
                    <textarea name="plan" class="form-control" rows="4" placeholder="Tindakan, terapi, rencana asuhan..."></textarea>
                </div>
            </div>
            
            <hr>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Instruksi PPA / Tatalaksana Medis</label>
                    <textarea name="instruksi" class="form-control" rows="3" placeholder="Instruksi spesifik..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <a href="/rekam-medis/ppa/{{ $pendaftaran->id }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Catatan</button>
    </div>
</form>
@endsection
