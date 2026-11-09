@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fs-4 fw-medium text-dark mb-0">Rincian Penyesuaian Stok #{{ $adjustment->no_penyesuaian }}</h3>
        <div class="small text-muted">
            <a href="/" class="text-decoration-none text-muted">Beranda</a> &gt; 
            <a href="/stok-adjustment/index" class="text-decoration-none text-muted">Histori Penyesuaian Stok</a> &gt; 
            <span>Detail</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-info text-white py-2 px-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold"><i class="far fa-file-alt me-1"></i> Informasi Penyesuaian</h6>
            <a href="/stok-adjustment/index" class="btn btn-sm btn-light py-0 px-2" style="font-size: 12px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <span class="text-muted small d-block">No Penyesuaian</span>
                    <span class="fw-bold fs-6 text-primary">{{ $adjustment->no_penyesuaian }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small d-block">Tgl. Penyesuaian</span>
                    <span class="fw-bold fs-6">{{ $adjustment->tanggal_penyesuaian ? $adjustment->tanggal_penyesuaian->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small d-block">Gudang</span>
                    <span class="fw-bold fs-6">{{ $adjustment->masterGudang->nama_gudang ?? $adjustment->kode_gudang }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small d-block">Tipe</span>
                    <span class="badge bg-secondary">{{ $adjustment->tipe }}</span>
                </div>
                <div class="col-md-6">
                    <span class="text-muted small d-block">User Input / Petugas</span>
                    <span>{{ $adjustment->user_name ?? ($adjustment->user->name ?? '-') }} ({{ $adjustment->created_at ? $adjustment->created_at->format('d/m/Y H:i:s') : '-' }})</span>
                </div>
                <div class="col-md-6">
                    <span class="text-muted small d-block">Keterangan / Catatan</span>
                    <span>{{ $adjustment->keterangan ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2 px-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-boxes me-1 text-primary"></i> Rincian Barang yang Disesuaikan</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">#</th>
                            <th style="width: 140px;">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th class="text-center" style="width: 100px;">Stok Sistem</th>
                            <th class="text-center" style="width: 100px;">Stok Fisik</th>
                            <th class="text-center" style="width: 90px;">Selisih</th>
                            <th style="width: 90px;">Satuan</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustment->details as $idx => $det)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="fw-semibold text-primary">{{ $det->masterBarang->kode_barang ?? '-' }}</td>
                            <td>{{ $det->masterBarang->nama_barang ?? '-' }}</td>
                            <td class="text-center">{{ $det->stok_sistem }}</td>
                            <td class="text-center fw-bold">{{ $det->stok_fisik }}</td>
                            <td class="text-center fw-bold {{ $det->selisih > 0 ? 'text-success' : ($det->selisih < 0 ? 'text-danger' : 'text-muted') }}">
                                {{ $det->selisih > 0 ? '+' : '' }}{{ $det->selisih }}
                            </td>
                            <td>{{ $det->satuan ?? $det->masterBarang->kemasan ?? 'Pcs' }}</td>
                            <td>{{ $det->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">Tidak ada data rincian item.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
