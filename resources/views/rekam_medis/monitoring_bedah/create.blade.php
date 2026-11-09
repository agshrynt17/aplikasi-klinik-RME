@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/monitoring/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Monitoring Durante Bedah
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/monitoring" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">
    <input type="hidden" name="dokter_id" value="{{ auth()->user()->pegawai_id ?? $pendaftaran->dokter_id }}">

    <!-- Informasi Utama -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Umum</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <strong>Pasien:</strong><br>
                    {{ $pendaftaran->pasien->no_rm }} - {{ $pendaftaran->pasien->nama_pasien }}
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Waktu Mulai Monitoring</label>
                    <input type="datetime-local" class="form-control" name="waktu_mulai" value="{{ date('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Waktu Selesai Monitoring</label>
                    <input type="datetime-local" class="form-control" name="waktu_selesai">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-semibold">Cairan Masuk (Input)</label>
                    <input type="text" class="form-control" name="cairan_masuk" placeholder="Misal: RL 500cc, PRC 250cc">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-semibold">Cairan Keluar (Output)</label>
                    <input type="text" class="form-control" name="cairan_keluar" placeholder="Misal: Perdarahan 200cc, Urine 100cc">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Observasi (Time Series) -->
    <div class="card card-custom mb-3 border-success">
        <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
            <div><i class="fas fa-heartbeat me-2"></i>Tabel Pemantauan Tanda Vital (Intra-Operatif)</div>
            <button type="button" class="btn btn-sm btn-light text-success fw-bold" onclick="addRow()">
                <i class="fas fa-plus"></i> Tambah Baris
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center mb-0" id="tabel-observasi">
                    <thead class="table-light">
                        <tr>
                            <th width="15%">Waktu (Jam)</th>
                            <th width="15%">Tensi (mmHg)</th>
                            <th width="15%">Nadi (x/m)</th>
                            <th width="15%">RR (x/m)</th>
                            <th width="15%">Suhu (°C)</th>
                            <th width="15%">SpO2 (%)</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Minimal 1 baris kosong -->
                        <tr>
                            <td><input type="time" class="form-control form-control-sm" name="waktu[]"></td>
                            <td><input type="text" class="form-control form-control-sm" name="tensi[]"></td>
                            <td><input type="text" class="form-control form-control-sm" name="nadi[]"></td>
                            <td><input type="text" class="form-control form-control-sm" name="rr[]"></td>
                            <td><input type="text" class="form-control form-control-sm" name="suhu[]"></td>
                            <td><input type="text" class="form-control form-control-sm" name="spo2[]"></td>
                            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Catatan Tambahan -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold">Catatan Selama Monitoring</div>
        <div class="card-body">
            <textarea class="form-control" name="keterangan" rows="3" placeholder="Kejadian penting selama operasi/tindakan..."></textarea>
        </div>
    </div>

    <div class="text-end mb-5">
        <a href="/monitoring/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Data Monitoring</button>
    </div>
</form>

<script>
function addRow() {
    var html = `<tr>
        <td><input type="time" class="form-control form-control-sm" name="waktu[]"></td>
        <td><input type="text" class="form-control form-control-sm" name="tensi[]"></td>
        <td><input type="text" class="form-control form-control-sm" name="nadi[]"></td>
        <td><input type="text" class="form-control form-control-sm" name="rr[]"></td>
        <td><input type="text" class="form-control form-control-sm" name="suhu[]"></td>
        <td><input type="text" class="form-control form-control-sm" name="spo2[]"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
    </tr>`;
    $('#tabel-observasi tbody').append(html);
}

function removeRow(btn) {
    $(btn).closest('tr').remove();
}
</script>
@endsection
