@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-pills text-success me-2"></i> Laporan Waktu Tunggu Pelayanan Obat (Non-Racik)</h1>
    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak</button>
</div>

<div class="row mb-4 d-print-none">
    <div class="col-md-7">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light h-100">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label fw-bold">Bulan:</label>
                </div>
                <div class="col-auto">
                    <select name="bulan" class="form-select">
                        @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="col-auto">
                    <label class="col-form-label fw-bold">Tahun:</label>
                </div>
                <div class="col-auto">
                    <input type="number" name="tahun" class="form-control" value="{{ $tahun }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success"><i class="fas fa-filter me-1"></i> Tampilkan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card-custom p-3 shadow-sm border-0 rounded-3 text-center text-white h-100 d-flex flex-column justify-content-center {{ $persentase >= 80 ? 'bg-success' : 'bg-danger' }}">
            <h5 class="fw-bold mb-1">Capaian PMKP (Standar &le; 30 Menit)</h5>
            <h2 class="fw-bold display-6 mb-0">{{ number_format($persentase, 1) }}%</h2>
            <small>{{ $totalSesuai }} dari {{ count($data) }} Resep Memenuhi Standar</small>
        </div>
    </div>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="text-center mb-4">
        <h4 class="fw-bold">LAPORAN PMKP - WAKTU TUNGGU OBAT (NON-RACIK)</h4>
        <p class="mb-0">Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</p>
        <p class="small text-muted">Indikator Mutu: Waktu tunggu penyerahan obat jadi / non-racik &le; 30 menit.</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-success align-middle text-dark">
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Tanggal</th>
                    <th width="15%">No Resep</th>
                    <th width="20%">Nama Pasien</th>
                    <th width="12%">Jam Resep Masuk</th>
                    <th width="12%">Jam Obat Diserahkan</th>
                    <th width="12%">Waktu Tunggu (Menit)</th>
                    <th width="12%">Kesesuaian Standar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td class="fw-bold">{{ $item->no_resep }}</td>
                    <td class="text-start fw-bold">{{ $item->nama_pasien }}</td>
                    <td>{{ $item->jam_resep_masuk }}</td>
                    <td>{{ $item->jam_resep_selesai }}</td>
                    <td class="fw-bold">{{ $item->waktu_tunggu }} mnt</td>
                    <td>
                        @if($item->memenuhi_standar)
                            <span class="badge bg-success"><i class="fas fa-check"></i> Sesuai</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times"></i> Melebihi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-muted py-4">Belum ada data resep non-racik pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
