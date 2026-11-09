@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-cash-register text-primary me-2"></i> Laporan Transaksi Kasir</h1>
    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak Bukti Setor</button>
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
                    <button type="submit" class="btn btn-success"><i class="fas fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>
    @php
        $totalSetor = collect($data)->sum('total_tagihan');
    @endphp
    <div class="col-md-5">
        <div class="card-custom p-3 shadow-sm border-0 rounded-3 text-center bg-primary text-white h-100 d-flex flex-column justify-content-center">
            <h5 class="fw-bold mb-1">Total Uang di Kasir (Setoran)</h5>
            <h2 class="fw-bold display-6 mb-0">Rp {{ number_format($totalSetor, 0, ',', '.') }}</h2>
        </div>
    </div>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="text-center mb-4">
        <h4 class="fw-bold">REKAPITULASI PENERIMAAN UANG KASIR (BILLING)</h4>
        <p class="mb-0">Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary align-middle text-center">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="12%">No Kwitansi</th>
                    <th width="12%">No Rawat</th>
                    <th width="15%">Nama Pasien</th>
                    <th width="12%">Metode Bayar</th>
                    <th width="15%">Total (Rp)</th>
                    <th width="14%">Petugas Kasir</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @forelse($data as $index => $item)
                @php $grandTotal += $item->total_tagihan; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                    <td class="text-center fw-bold">{{ $item->no_kwitansi }}</td>
                    <td class="text-center">{{ $item->no_rawat }}</td>
                    <td class="fw-bold">{{ $item->nama_pasien }}</td>
                    <td class="text-center">
                        <span class="badge bg-secondary">{{ $item->pembayaran }}</span>
                    </td>
                    <td class="text-end fw-bold">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-center small">{{ $item->kasir }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Tidak ada data transaksi kasir.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="6" class="text-end">TOTAL SETORAN KASIR</td>
                    <td class="text-end text-primary fs-5">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
