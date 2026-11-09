@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/surat-keterangan/{{ $pendaftaran->id }}" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Surat Keterangan
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/surat-keterangan/{{ $surat->id }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card card-custom mb-4">
        <div class="card-header bg-light fw-bold">
            Formulir Surat Keterangan Dokter
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Nomor Surat</label>
                    <input type="text" name="nomor_surat" class="form-control" value="{{ $surat->nomor_surat }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" class="form-control" value="{{ $surat->tanggal_surat }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Jenis Surat</label>
                    <select name="jenis_surat" class="form-select" required>
                        <option value="Surat Sakit" {{ $surat->jenis_surat == 'Surat Sakit' ? 'selected' : '' }}>Surat Keterangan Sakit (Istirahat)</option>
                        <option value="Surat Sehat" {{ $surat->jenis_surat == 'Surat Sehat' ? 'selected' : '' }}>Surat Keterangan Berbadan Sehat</option>
                        <option value="Surat Rujukan" {{ $surat->jenis_surat == 'Surat Rujukan' ? 'selected' : '' }}>Surat Rujukan Dokter</option>
                        <option value="Lainnya" {{ $surat->jenis_surat == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Dokter Pemeriksa</label>
                    <select name="dokter_id" class="form-select">
                        <option value="">- Pilih Dokter -</option>
                        <option value="{{ $surat->dokter_id }}" selected>{{ $surat->dokter->nama_pegawai ?? 'Dokter DPJP' }}</option>
                    </select>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold text-warning mb-3"><i class="fas fa-bed me-2"></i>Untuk Keterangan Sakit / Cuti (Opsional)</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Mulai Tanggal</label>
                    <input type="date" name="mulai_tanggal" class="form-control" value="{{ $surat->mulai_tanggal }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control" value="{{ $surat->sampai_tanggal }}">
                </div>
            </div>
            
            <hr>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Keterangan / Diagnosa / Alasan</label>
                    <textarea name="keterangan" class="form-control" rows="5">{{ $surat->keterangan }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-outline-danger me-2" onclick="hapusSurat({{ $surat->id }})">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/rekam-medis/surat-keterangan/{{ $pendaftaran->id }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Surat</button>
    </div>
</form>

<script>
function hapusSurat(id) {
    if(confirm('Apakah Anda yakin ingin menghapus Surat Keterangan ini?')) {
        $.ajax({
            url: '/rekam-medis/surat-keterangan/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/rekam-medis/surat-keterangan/{{ $pendaftaran->id }}';
            }
        });
    }
}
</script>
@endsection
