@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fs-4 fw-medium text-dark mb-0">Detail Stok Cancel #{{ $cancel->cancel_no }}</h3>
        <div class="small text-muted">
            <a href="/" class="text-decoration-none text-muted">Beranda</a> &gt; 
            <a href="/stok-cancel/index" class="text-decoration-none text-muted">Stok Cancels</a> &gt; 
            <span>Detail</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-info text-white py-2 px-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold"><i class="far fa-newspaper me-1"></i> Informasi Pembatalan Stok</h6>
            <a href="/stok-cancel/index" class="btn btn-sm btn-light py-0 px-2" style="font-size: 12px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <span class="text-muted small d-block">Cancel No</span>
                    <span class="fw-bold fs-6">{{ $cancel->cancel_no }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small d-block">Cancel Tanggal</span>
                    <span class="fw-bold fs-6">{{ \Carbon\Carbon::parse($cancel->cancel_tanggal)->format('d/m/Y') }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small d-block">Kode Gudang</span>
                    <span class="fw-bold fs-6">{{ $cancel->kode_gudang }} ({{ $cancel->masterGudang->nama_gudang ?? 'Gudang' }})</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small d-block">Cancel Type</span>
                    <span class="badge bg-secondary">{{ $cancel->cancel_type }}</span>
                </div>
                <div class="col-md-6">
                    <span class="text-muted small d-block">Waktu Input (Create At)</span>
                    <span>{{ \Carbon\Carbon::parse($cancel->created_at)->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="col-md-6">
                    <span class="text-muted small d-block">Keterangan / Catatan</span>
                    <span>{{ $cancel->keterangan ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2 px-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-boxes me-1 text-primary"></i> Rincian Barang yang Dibatalkan</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">#</th>
                            <th style="width: 140px;">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th class="text-center" style="width: 110px;">Jumlah Batal</th>
                            <th style="width: 100px;">Satuan</th>
                            <th>Keterangan Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cancel->details as $idx => $det)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="fw-semibold text-primary">{{ $det->masterBarang->kode_barang ?? '-' }}</td>
                            <td>{{ $det->masterBarang->nama_barang ?? '-' }}</td>
                            <td class="text-center fw-bold text-danger">-{{ $det->jumlah }}</td>
                            <td>{{ $det->satuan ?? $det->masterBarang->kemasan ?? 'Pcs' }}</td>
                            <td>{{ $det->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Tidak ada data rincian item.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
