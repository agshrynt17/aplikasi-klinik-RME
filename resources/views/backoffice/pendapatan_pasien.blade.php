@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-chart-line text-success me-2"></i> Laporan Pendapatan Pasien</h1>
    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak</button>
</div>

<div class="row mb-4 d-print-none">
    <div class="col-md-7">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light h-100">
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
                    <button type="submit" class="btn btn-success"><i class="fas fa-filter me-1"></i> Tampilkan</button>
                </div>
            </form>
        </div>
    </div>
    @php
        $totalPendapatan = collect($data)->sum('sudah_dibayar');
    @endphp
    <div class="col-md-5">
        <div class="card-custom p-3 shadow-sm border-0 rounded-3 text-center bg-success text-white h-100 d-flex flex-column justify-content-center">
            <h5 class="fw-bold mb-1">Total Pendapatan (Cash IN)</h5>
            <h2 class="fw-bold display-6 mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        </div>
    </div>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="text-center mb-4">
        <h4 class="fw-bold">REKAPITULASI PENDAPATAN PASIEN UMUM & BPJS</h4>
        <p class="mb-0">Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-success align-middle text-center">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="12%">No Rawat</th>
                    <th width="18%">Nama Pasien</th>
                    <th width="10%">Jenis Bayar</th>
                    <th width="15%">Total Tagihan</th>
                    <th width="15%">Sudah Dibayar (Masuk)</th>
                    <th width="15%">Piutang (Belum Bayar)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totTagihan = 0; 
                    $totBayar = 0; 
                    $totPiutang = 0;
                @endphp
                @forelse($data as $index => $item)
                @php 
                    $totTagihan += $item->total_tagihan; 
                    $totBayar += $item->sudah_dibayar; 
                    $totPiutang += $item->piutang;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                    <td class="text-center">{{ $item->no_rawat }}</td>
                    <td class="fw-bold">{{ $item->nama_pasien }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->jenis_pasien == 'UMUM' ? 'bg-primary' : 'bg-success' }}">
                            {{ $item->jenis_pasien }}
                        </span>
                    </td>
                    <td class="text-end fw-bold">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold text-success">Rp {{ number_format($item->sudah_dibayar, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold text-danger">Rp {{ number_format($item->piutang, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Tidak ada data pendapatan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="5" class="text-end">TOTAL KESELURUHAN</td>
                    <td class="text-end fs-6">Rp {{ number_format($totTagihan, 0, ',', '.') }}</td>
                    <td class="text-end text-success fs-5">Rp {{ number_format($totBayar, 0, ',', '.') }}</td>
                    <td class="text-end text-danger fs-6">Rp {{ number_format($totPiutang, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
