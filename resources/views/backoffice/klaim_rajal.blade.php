@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-file-invoice-dollar text-primary me-2"></i> Klaim Rawat Jalan (BPJS / Asuransi)</h1>
    <div>
        <button class="btn btn-success me-2"><i class="fas fa-file-excel me-1"></i> Export Excel</button>
        <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak</button>
    </div>
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Tampilkan</button>
        </div>
    </form>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="text-center mb-4">
        <h4 class="fw-bold">REKAPITULASI PENGAJUAN KLAIM RAWAT JALAN</h4>
        <p class="mb-0">Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary text-center align-middle">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="10%">No Rawat</th>
                    <th width="12%">No SEP</th>
                    <th width="15%">Nama Pasien</th>
                    <th width="18%">Diagnosa Utama (ICD-10)</th>
                    <th width="10%">Tarif RS</th>
                    <th width="10%">Tarif INA-CBG</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totRS = 0; 
                    $totINA = 0; 
                @endphp
                @forelse($data as $index => $item)
                @php 
                    $totRS += $item->tarif_rs; 
                    $totINA += $item->tarif_inacbg; 
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                    <td class="text-center">{{ $item->no_rawat }}</td>
                    <td class="text-center fw-bold">{{ $item->no_sep }}</td>
                    <td class="fw-bold">{{ $item->nama_pasien }}</td>
                    <td class="small">{{ $item->diagnosa }}</td>
                    <td class="text-end">Rp {{ number_format($item->tarif_rs, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold text-success">Rp {{ number_format($item->tarif_inacbg, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->status == 'Diklaim')
                            <span class="badge bg-success"><i class="fas fa-check"></i> {{ $item->status }}</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> {{ $item->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Tidak ada data klaim pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="6" class="text-end">TOTAL POTENSI KLAIM</td>
                    <td class="text-end text-danger">Rp {{ number_format($totRS, 0, ',', '.') }}</td>
                    <td class="text-end text-success fs-5">Rp {{ number_format($totINA, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
