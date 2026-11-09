@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/anc/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Asesmen ANC Rawat Jalan
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/anc/{{ $anc->id }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Informasi Utama -->
    <div class="card card-custom mb-3 border-secondary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Pasien:</strong> {{ $pendaftaran->pasien->no_rm }} - {{ $pendaftaran->pasien->nama_pasien }}
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-bold">Dokter / Bidan Penanggung Jawab</label>
                    <select name="dokter_id" class="form-select form-select-sm" required>
                        <option value="">Pilih Dokter / Bidan</option>
                        <option value="{{ $anc->dokter_id }}" selected>{{ $anc->dokter->nama_pegawai ?? 'Bidan/Dokter' }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Kehamilan & Menstruasi -->
    <div class="card card-custom mb-3 border-primary">
        <div class="card-header bg-primary text-white fw-bold"><i class="fas fa-history me-2"></i>Riwayat Kehamilan (Obstetri)</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">HPHT</label>
                    <input type="date" class="form-control" name="hpht" value="{{ $anc->hpht }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">HPL</label>
                    <input type="date" class="form-control" name="hpl" value="{{ $anc->hpl }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Usia Kehamilan (Minggu)</label>
                    <input type="number" class="form-control" name="usia_kehamilan" placeholder="Mgg" value="{{ $anc->usia_kehamilan }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">GPA (Riwayat Hamil)</label>
                    <input type="text" class="form-control" name="gpa" placeholder="G1 P0 A0" value="{{ $anc->gpa }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Fisik Ibu & Janin -->
    <div class="card card-custom mb-3 border-success">
        <div class="card-header bg-success text-white fw-bold"><i class="fas fa-stethoscope me-2"></i>Pemeriksaan Ibu & Janin</div>
        <div class="card-body">
            <h6 class="text-success fw-bold border-bottom pb-2 mb-3">Pemeriksaan Ibu</h6>
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Tekanan Darah (mmHg)</label>
                    <input type="text" class="form-control" name="tensi" placeholder="120/80" value="{{ $anc->tensi }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Berat Badan (Kg)</label>
                    <input type="number" step="0.1" class="form-control" name="bb" value="{{ $anc->bb }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Tinggi Badan (Cm)</label>
                    <input type="number" step="0.1" class="form-control" name="tb" value="{{ $anc->tb }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">LILA (Cm)</label>
                    <input type="number" step="0.1" class="form-control" name="lila" value="{{ $anc->lila }}">
                </div>
            </div>

            <h6 class="text-success fw-bold border-bottom pb-2 mb-3">Pemeriksaan Janin</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Tinggi Fundus Uteri (TFU)</label>
                    <input type="text" class="form-control" name="tfu" placeholder="... cm / jr" value="{{ $anc->tfu }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Denyut Jantung Janin (DJJ)</label>
                    <input type="text" class="form-control" name="djj" placeholder="... x/mnt" value="{{ $anc->djj }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Letak Janin</label>
                    <select class="form-select" name="letak_janin">
                        <option value="">- Pilih Letak -</option>
                        <option value="Kepala" {{ $anc->letak_janin == 'Kepala' ? 'selected' : '' }}>Kepala</option>
                        <option value="Sungsang" {{ $anc->letak_janin == 'Sungsang' ? 'selected' : '' }}>Sungsang</option>
                        <option value="Melintang" {{ $anc->letak_janin == 'Melintang' ? 'selected' : '' }}>Melintang</option>
                        <option value="Lainnya" {{ $anc->letak_janin == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Status Imunisasi TT</label>
                    <select class="form-select" name="imunisasi_tt">
                        <option value="">- Pilih TT -</option>
                        <option value="TT1" {{ $anc->imunisasi_tt == 'TT1' ? 'selected' : '' }}>TT1</option>
                        <option value="TT2" {{ $anc->imunisasi_tt == 'TT2' ? 'selected' : '' }}>TT2</option>
                        <option value="TT3" {{ $anc->imunisasi_tt == 'TT3' ? 'selected' : '' }}>TT3</option>
                        <option value="TT4" {{ $anc->imunisasi_tt == 'TT4' ? 'selected' : '' }}>TT4</option>
                        <option value="TT5" {{ $anc->imunisasi_tt == 'TT5' ? 'selected' : '' }}>TT5</option>
                        <option value="Lengkap" {{ $anc->imunisasi_tt == 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                        <option value="Belum" {{ $anc->imunisasi_tt == 'Belum' ? 'selected' : '' }}>Belum</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Keluhan & Keterangan Tambahan -->
    <div class="card card-custom mb-3 border-info">
        <div class="card-header bg-info text-white fw-bold"><i class="fas fa-file-alt me-2"></i>Keluhan & Keterangan</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Keluhan Utama</label>
                    <textarea class="form-control" name="keluhan" rows="3">{{ $anc->keluhan }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Keterangan / Terapi / Nasihat</label>
                    <textarea class="form-control" name="keterangan" rows="3">{{ $anc->keterangan }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-outline-danger me-2" onclick="hapusANC()">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/anc/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui ANC</button>
    </div>
</form>

<script>
function hapusANC() {
    if(confirm('Apakah Anda yakin ingin menghapus data Ante Natal Care (ANC) ini?')) {
        $.ajax({
            url: '/anc/{{ $anc->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/anc/index';
            }
        });
    }
}
</script>
@endsection
