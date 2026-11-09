@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-user-md text-primary me-2"></i> Laporan Jasa Medis Tindakan</h1>
    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak PDF</button>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light mb-4 d-print-none">
    <form action="" method="GET" class="row g-3 align-items-center">
        <div class="col-auto"><label class="col-form-label fw-bold">Bulan:</label></div>
        <div class="col-auto">
            <select name="bulan" class="form-select">
                @for($i=1; $i<=12; $i++)
                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                </option>
                @endfor
            </select>
        </div>
        <div class="col-auto"><label class="col-form-label fw-bold">Tahun:</label></div>
        <div class="col-auto">
            <input type="number" name="tahun" class="form-control" value="{{ $tahun }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="fas fa-filter me-1"></i> Filter</button>
        </div>
    </form>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="text-center mb-4">
        <h4 class="fw-bold">REKAPITULASI JASA MEDIS DOKTER & TINDAKAN</h4>
        <p class="mb-0">Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="10%">No Rawat</th>
                    <th width="15%">Nama Pasien</th>
                    <th width="15%">Nama Dokter</th>
                    <th width="15%">Tindakan</th>
                    <th width="15%">Tarif Tindakan</th>
                    <th width="15%">Jasa Medis (Fee)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalTarif = 0; 
                    $totalJasa = 0; 
                @endphp
                @forelse($data as $index => $item)
                @php 
                    $totalTarif += $item->tarif_tindakan; 
                    $totalJasa += $item->jasa_medis; 
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                    <td class="text-center">{{ $item->no_rawat }}</td>
                    <td class="fw-bold">{{ $item->nama_pasien }}</td>
                    <td>{{ $item->nama_dokter }}</td>
                    <td>{{ $item->tindakan }}</td>
                    <td class="text-end">Rp {{ number_format($item->tarif_tindakan, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold text-success">Rp {{ number_format($item->jasa_medis, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Tidak ada data jasa medis pada bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="6" class="text-end">TOTAL KESELURUHAN</td>
                    <td class="text-end text-danger">Rp {{ number_format($totalTarif, 0, ',', '.') }}</td>
                    <td class="text-end text-success fs-5">Rp {{ number_format($totalJasa, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
