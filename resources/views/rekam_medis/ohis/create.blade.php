@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/ohis/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Pemeriksaan OHI-S
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i> <strong>Simplified Oral Hygiene Index (OHI-S)</strong> digunakan untuk mengukur status kebersihan mulut berdasarkan debris dan kalkulus pada 6 gigi indeks (16, 11, 26, 36, 31, 46).
</div>

<form action="/ohis" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">
    <input type="hidden" name="dokter_id" value="{{ auth()->user()->pegawai_id ?? $pendaftaran->dokter_id }}">

    <!-- Informasi Utama -->
    <div class="card card-custom mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Pasien:</strong> {{ $pendaftaran->pasien->no_rm }} - {{ $pendaftaran->pasien->nama_pasien }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Dokter Gigi:</strong> {{ $pendaftaran->dokter->nama_pegawai ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Form OHI-S -->
    <div class="card card-custom mb-3 border-primary">
        <div class="card-header bg-primary text-white fw-bold"><i class="fas fa-tooth me-2"></i>Pengukuran Debris (DI) & Kalkulus (CI)</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" width="20%">Gigi Indeks</th>
                            <th colspan="2">Rahang Atas</th>
                            <th rowspan="2" width="20%">Gigi Indeks</th>
                            <th colspan="2">Rahang Bawah</th>
                        </tr>
                        <tr>
                            <th>DI (0-3)</th>
                            <th>CI (0-3)</th>
                            <th>DI (0-3)</th>
                            <th>CI (0-3)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold bg-light">16 (B)</td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="di[16]"></td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="ci[16]"></td>
                            <td class="fw-bold bg-light">36 (L)</td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="di[36]"></td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="ci[36]"></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">11 (L)</td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="di[11]"></td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="ci[11]"></td>
                            <td class="fw-bold bg-light">31 (L)</td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="di[31]"></td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="ci[31]"></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">26 (B)</td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="di[26]"></td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="ci[26]"></td>
                            <td class="fw-bold bg-light">46 (L)</td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="di[46]"></td>
                            <td><input type="number" min="0" max="3" class="form-control text-center ohi-input" name="ci[46]"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="row text-center fw-bold text-primary">
                <div class="col-md-3">DI-S = <span id="val-di">0.00</span></div>
                <div class="col-md-3">CI-S = <span id="val-ci">0.00</span></div>
                <div class="col-md-3">OHI-S = <span id="val-ohi">0.00</span></div>
                <div class="col-md-3">Kriteria = <span id="val-kriteria" class="badge bg-success">Baik</span></div>
            </div>
        </div>
    </div>

    <!-- Catatan Tambahan -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold">Catatan (Bila ada)</div>
        <div class="card-body">
            <textarea class="form-control" name="keterangan" rows="2" placeholder="Gigi 16 tidak ada, diganti 17 dll..."></textarea>
        </div>
    </div>

    <div class="text-end mb-5">
        <a href="/ohis/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan OHI-S</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('.ohi-input').on('input', function() {
        let total_di = 0, total_ci = 0;
        let count_di = 0, count_ci = 0;
        
        $('input[name^="di["]').each(function() {
            if($(this).val() !== '') {
                total_di += parseFloat($(this).val());
                count_di++;
            }
        });
        
        $('input[name^="ci["]').each(function() {
            if($(this).val() !== '') {
                total_ci += parseFloat($(this).val());
                count_ci++;
            }
        });
        
        let di_s = count_di > 0 ? (total_di / count_di) : 0;
        let ci_s = count_ci > 0 ? (total_ci / count_ci) : 0;
        let ohi_s = di_s + ci_s;
        
        $('#val-di').text(di_s.toFixed(2));
        $('#val-ci').text(ci_s.toFixed(2));
        $('#val-ohi').text(ohi_s.toFixed(2));
        
        let kriteria = 'Baik';
        let badge_class = 'bg-success';
        
        if (ohi_s > 1.2 && ohi_s <= 3.0) {
            kriteria = 'Sedang';
            badge_class = 'bg-warning text-dark';
        } else if (ohi_s > 3.0) {
            kriteria = 'Buruk';
            badge_class = 'bg-danger';
        }
        
        $('#val-kriteria').text(kriteria).removeClass('bg-success bg-warning text-dark bg-danger').addClass(badge_class);
    });
});
</script>
@endsection
