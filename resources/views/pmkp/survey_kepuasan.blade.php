@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-smile text-warning me-2"></i> Laporan Survey Kepuasan Pasien</h1>
    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak</button>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light mb-4 d-print-none">
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

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="text-center mb-4">
        <h4 class="fw-bold">REKAPITULASI HASIL SURVEY KEPUASAN PASIEN</h4>
        <p class="mb-0">Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-primary align-middle">
                <tr>
                    <th rowspan="2" width="5%">No</th>
                    <th rowspan="2" width="15%">Tanggal</th>
                    <th rowspan="2" width="20%">Nama Pasien</th>
                    <th rowspan="2" width="15%">Unit Pelayanan</th>
                    <th colspan="4">Kriteria Penilaian (1-5)</th>
                    <th rowspan="2" width="10%">Rata-rata</th>
                </tr>
                <tr>
                    <th>Pelayanan Dokter</th>
                    <th>Pelayanan Perawat</th>
                    <th>Fasilitas</th>
                    <th>Kebersihan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td class="text-start fw-bold">{{ $item->nama_pasien }}</td>
                    <td>{{ $item->poli }}</td>
                    <td>{{ $item->pelayanan_dokter }}</td>
                    <td>{{ $item->pelayanan_perawat }}</td>
                    <td>{{ $item->fasilitas }}</td>
                    <td>{{ $item->kebersihan }}</td>
                    <td class="fw-bold {{ $item->rata_rata >= 4 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($item->rata_rata, 1) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-muted py-4">Belum ada data survey pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
