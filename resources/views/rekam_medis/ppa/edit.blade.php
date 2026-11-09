@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/ppa/{{ $pendaftaran->id }}" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Catatan CPPT
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/ppa/{{ $ppa->id }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card card-custom mb-4">
        <div class="card-header bg-light fw-bold">
            Form Catatan Perkembangan Pasien Terintegrasi (CPPT)
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Tanggal & Jam</label>
                    <input type="datetime-local" name="waktu" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($ppa->waktu)) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Profesi PPA</label>
                    <select name="profesi" class="form-select" required>
                        <option value="Dokter" {{ $ppa->profesi == 'Dokter' ? 'selected' : '' }}>Dokter / DPJP</option>
                        <option value="Perawat" {{ $ppa->profesi == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                        <option value="Bidan" {{ $ppa->profesi == 'Bidan' ? 'selected' : '' }}>Bidan</option>
                        <option value="Apoteker" {{ $ppa->profesi == 'Apoteker' ? 'selected' : '' }}>Apoteker</option>
                        <option value="Ahli Gizi" {{ $ppa->profesi == 'Ahli Gizi' ? 'selected' : '' }}>Ahli Gizi</option>
                        <option value="Lainnya" {{ $ppa->profesi == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-primary">S - Subjective (Subjektif)</label>
                    <textarea name="subjective" class="form-control" rows="4">{{ $ppa->subjective }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-success">O - Objective (Objektif)</label>
                    <textarea name="objective" class="form-control" rows="4">{{ $ppa->objective }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-danger">A - Assessment (Asesmen)</label>
                    <textarea name="assessment" class="form-control" rows="4">{{ $ppa->assessment }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-info">P - Plan (Perencanaan)</label>
                    <textarea name="plan" class="form-control" rows="4">{{ $ppa->plan }}</textarea>
                </div>
            </div>
            
            <hr>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Instruksi PPA / Tatalaksana Medis</label>
                    <textarea name="instruksi" class="form-control" rows="3">{{ $ppa->instruksi }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-outline-danger me-2" onclick="hapusPpa({{ $ppa->id }})">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/rekam-medis/ppa/{{ $pendaftaran->id }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Catatan</button>
    </div>
</form>

<script>
function hapusPpa(id) {
    if(confirm('Apakah Anda yakin ingin menghapus catatan CPPT ini?')) {
        $.ajax({
            url: '/rekam-medis/ppa/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/rekam-medis/ppa/{{ $pendaftaran->id }}';
            }
        });
    }
}
</script>
@endsection
