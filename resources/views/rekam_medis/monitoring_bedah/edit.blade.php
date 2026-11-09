@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/monitoring/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Monitoring Durante Bedah
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/monitoring/{{ $monitoring->id }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="dokter_id" value="{{ $monitoring->dokter_id }}">

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
                    <input type="datetime-local" class="form-control" name="waktu_mulai" value="{{ $monitoring->waktu_mulai ? \Carbon\Carbon::parse($monitoring->waktu_mulai)->format('Y-m-d\TH:i') : '' }}" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Waktu Selesai Monitoring</label>
                    <input type="datetime-local" class="form-control" name="waktu_selesai" value="{{ $monitoring->waktu_selesai ? \Carbon\Carbon::parse($monitoring->waktu_selesai)->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-semibold">Cairan Masuk (Input)</label>
                    <input type="text" class="form-control" name="cairan_masuk" value="{{ $monitoring->cairan_masuk }}">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-semibold">Cairan Keluar (Output)</label>
                    <input type="text" class="form-control" name="cairan_keluar" value="{{ $monitoring->cairan_keluar }}">
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
                        @if(is_array($monitoring->observasi_json) && count($monitoring->observasi_json) > 0)
                            @foreach($monitoring->observasi_json as $obs)
                            <tr>
                                <td><input type="time" class="form-control form-control-sm" name="waktu[]" value="{{ $obs['waktu'] ?? '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="tensi[]" value="{{ $obs['tensi'] ?? '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="nadi[]" value="{{ $obs['nadi'] ?? '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="rr[]" value="{{ $obs['rr'] ?? '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="suhu[]" value="{{ $obs['suhu'] ?? '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="spo2[]" value="{{ $obs['spo2'] ?? '' }}"></td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td><input type="time" class="form-control form-control-sm" name="waktu[]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="tensi[]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="nadi[]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="rr[]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="suhu[]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="spo2[]"></td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Catatan Tambahan -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold">Catatan Selama Monitoring</div>
        <div class="card-body">
            <textarea class="form-control" name="keterangan" rows="3">{{ $monitoring->keterangan }}</textarea>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-outline-danger me-2" onclick="hapusMonitoring()">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/monitoring/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Monitoring</button>
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

function hapusMonitoring() {
    if(confirm('Apakah Anda yakin ingin menghapus data monitoring bedah ini?')) {
        $.ajax({
            url: '/monitoring/{{ $monitoring->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/monitoring/index';
            }
        });
    }
}
</script>
@endsection
