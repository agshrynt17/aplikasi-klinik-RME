@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/laptindakan/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Laporan Tindakan / Operasi
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/laptindakan/{{ $laporan->id }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Informasi Pasien & Tim -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-users me-2 text-primary"></i>Informasi Pasien & Tim Operasi</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <strong>Pasien:</strong> 
                    <span class="badge bg-secondary ms-2">{{ $pendaftaran->pasien->no_rm }}</span>
                    {{ $pendaftaran->pasien->nama_pasien }}
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Dokter Operator (Bedah) <span class="text-danger">*</span></label>
                    <select class="form-select" name="dokter_operator_id" required>
                        <option value="">-- Pilih Dokter Bedah --</option>
                        @foreach($dokters as $d)
                        <option value="{{ $d->id }}" {{ ($laporan->dokter_operator_id == $d->id) ? 'selected' : '' }}>{{ $d->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Asisten Bedah</label>
                    <input type="text" class="form-control" name="asisten_operator" value="{{ $laporan->asisten_operator }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Perawat Instrumen</label>
                    <input type="text" class="form-control" name="perawat_instrumen" value="{{ $laporan->perawat_instrumen }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Dokter Anestesi</label>
                    <select class="form-select" name="dokter_anestesi_id">
                        <option value="">-- Pilih Dokter Anestesi --</option>
                        @foreach($dokters as $d)
                        <option value="{{ $d->id }}" {{ ($laporan->dokter_anestesi_id == $d->id) ? 'selected' : '' }}>{{ $d->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Jenis Anestesi</label>
                    <input type="text" class="form-control" name="jenis_anestesi" value="{{ $laporan->jenis_anestesi }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Diagnosa & Tindakan -->
    <div class="card card-custom mb-3 border-primary">
        <div class="card-header bg-primary text-white fw-bold"><i class="fas fa-stethoscope me-2"></i>Diagnosa & Tindakan</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Diagnosa Pra-Bedah</label>
                    <textarea class="form-control" name="diagnosa_pra_bedah" rows="2">{{ $laporan->diagnosa_pra_bedah }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Diagnosa Pasca-Bedah</label>
                    <textarea class="form-control" name="diagnosa_pasca_bedah" rows="2">{{ $laporan->diagnosa_pasca_bedah }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Tindakan Operasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control border-primary" name="tindakan_operasi" value="{{ $laporan->tindakan_operasi }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jaringan yang dieksisi (PA)</label>
                    <input type="text" class="form-control" name="jaringan_dieksisi" value="{{ $laporan->jaringan_dieksisi }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan & Instruksi -->
    <div class="card card-custom mb-3 border-success">
        <div class="card-header bg-success text-white fw-bold"><i class="fas fa-file-medical-alt me-2"></i>Laporan Operasi</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Waktu Mulai Operasi</label>
                    <input type="datetime-local" class="form-control" name="waktu_mulai" value="{{ $laporan->waktu_mulai ? \Carbon\Carbon::parse($laporan->waktu_mulai)->format('Y-m-d\TH:i') : '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Waktu Selesai Operasi</label>
                    <input type="datetime-local" class="form-control" name="waktu_selesai" value="{{ $laporan->waktu_selesai ? \Carbon\Carbon::parse($laporan->waktu_selesai)->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold text-success">Rincian Laporan Operasi (Langkah-langkah) <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="laporan_operasi" rows="6" required>{{ $laporan->laporan_operasi }}</textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-semibold">Instruksi Pasca Bedah (Monitoring / Terapi)</label>
                    <textarea class="form-control" name="instruksi_pasca_bedah" rows="3">{{ $laporan->instruksi_pasca_bedah }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-outline-danger me-2" onclick="hapusLaporan()">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/laptindakan/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Laporan Operasi</button>
    </div>
</form>

<script>
function hapusLaporan() {
    if(confirm('Apakah Anda yakin ingin menghapus Laporan Operasi ini?')) {
        $.ajax({
            url: '/laptindakan/{{ $laporan->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/laptindakan/index';
            }
        });
    }
}
</script>
@endsection
