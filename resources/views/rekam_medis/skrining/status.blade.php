@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Status Skrining Pasien Harian</h1>
</div>

<div class="card-custom p-4 mb-4 bg-light">
    <form action="/rekam-medis/skrining/status" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Pilih Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
        </div>
    </form>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <h6 class="card-title">Total Kunjungan</h6>
                <h2 class="display-5 fw-bold mb-0">{{ $total_pasien }}</h2>
                <small>Pasien Terdaftar</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <h6 class="card-title">Sudah Skrining</h6>
                <h2 class="display-5 fw-bold mb-0">{{ $sudah_skrining }}</h2>
                <small>Telah Diperiksa</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body text-center">
                <h6 class="card-title">Belum Skrining</h6>
                <h2 class="display-5 fw-bold mb-0">{{ $belum_skrining }}</h2>
                <small>Menunggu Pemeriksaan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body text-center">
                <h6 class="card-title">Resiko/Alert</h6>
                <h2 class="display-5 fw-bold mb-0">{{ $alergi + $resiko_jatuh + $nyeri_hebat }}</h2>
                <small>Kasus Perlu Perhatian</small>
            </div>
        </div>
    </div>
</div>

<!-- Peringatan Kritis -->
@if($alergi > 0 || $resiko_jatuh > 0 || $nyeri_hebat > 0)
<div class="alert alert-danger mb-4 shadow-sm">
    <h5 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle"></i> Peringatan Skrining Kritis Hari Ini!</h5>
    <ul class="mb-0 mt-2">
        @if($alergi > 0) <li>Ditemukan <strong>{{ $alergi }}</strong> pasien dengan riwayat alergi.</li> @endif
        @if($resiko_jatuh > 0) <li>Ditemukan <strong>{{ $resiko_jatuh }}</strong> pasien dengan resiko jatuh Sedang/Tinggi.</li> @endif
        @if($nyeri_hebat > 0) <li>Ditemukan <strong>{{ $nyeri_hebat }}</strong> pasien dengan nyeri hebat (Skor >= 7).</li> @endif
    </ul>
</div>
@endif

<div class="card-custom p-4">
    <h5 class="mb-3 border-bottom pb-2 text-primary"><i class="fas fa-users me-2"></i>Detail Status Skrining Pasien</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Pasien & No. RM</th>
                    <th width="15%">Poli / Dokter</th>
                    <th width="15%">Skrining Nyeri</th>
                    <th width="15%">Resiko Jatuh</th>
                    <th width="15%">Alergi / Gizi</th>
                    <th width="15%">Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $index => $item)
                @php
                    $skrining = $skrinings[$item->id] ?? null;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="fw-bold">{{ $item->pasien->nama_pasien ?? '-' }}</span><br>
                        <small class="text-muted">RM: {{ $item->pasien->no_rm ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ $item->poliklinik->nama_poli ?? '-' }}</span><br>
                        <small class="text-muted">{{ $item->dokter->nama_pegawai ?? '-' }}</small>
                    </td>
                    
                    @if($skrining)
                        <td class="text-center">
                            @if($skrining->skrining_nyeri == 'Ya' && $skrining->skor_nyeri >= 7)
                                <span class="badge bg-danger">Nyeri Hebat ({{ $skrining->skor_nyeri }})</span>
                            @elseif($skrining->skrining_nyeri == 'Ya')
                                <span class="badge bg-warning text-dark">Nyeri Ringan ({{ $skrining->skor_nyeri }})</span>
                            @else
                                <span class="badge bg-success">Tidak Nyeri</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(in_array($skrining->resiko_jatuh, ['Tinggi', 'Sedang']))
                                <span class="badge bg-danger">{{ $skrining->resiko_jatuh }}</span>
                            @else
                                <span class="badge bg-success">Rendah</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($skrining->status_alergi == 'Ya')
                                <span class="badge bg-danger blink">Ada Alergi</span>
                                <small class="d-block text-danger">{{ $skrining->detail_alergi }}</small>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i> Selesai</span>
                            <a href="/rekam-medis/skrining/edit/{{ $skrining->id }}" class="btn btn-sm btn-link d-block p-0 mt-1">Ubah</a>
                        </td>
                    @else
                        <td colspan="3" class="text-center text-muted fst-italic bg-light">
                            Belum dilakukan skrining awal...
                        </td>
                        <td class="text-center">
                            <a href="/rekam-medis/skrining/create/{{ $item->id }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-diagnoses"></i> Skrining
                            </a>
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-3x mb-3 text-light"></i><br>
                        Tidak ada data kunjungan pada tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.blink {
    animation: blinker 1s linear infinite;
}
@keyframes blinker {
    50% { opacity: 0; }
}
</style>
@endsection
