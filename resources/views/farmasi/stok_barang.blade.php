@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .konhakes-title-box {
        padding-bottom: 8px;
        margin-bottom: 12px;
        position: relative;
    }
    .konhakes-title-box h3 {
        font-size: 20px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .konhakes-orange-line {
        height: 2px;
        background-color: #f39c12;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 15px;
    }
    .btn-prof-orange {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: #fff;
        font-size: 12.5px;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-prof-orange:hover {
        background-color: #d68407;
        color: #fff;
    }
    .btn-prof-reset {
        background-color: #fff;
        border: 1px solid #ccc;
        color: #333;
        font-size: 12.5px;
        padding: 5px 11px;
        border-radius: 3px;
    }
    .btn-prof-reset:hover {
        background-color: #e6e6e6;
        color: #333;
    }
    .btn-prof-green {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #fff;
        font-size: 12.5px;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-prof-green:hover {
        background-color: #008d4c;
        color: #fff;
    }
    .btn-prof-cyan {
        background-color: #00c0ef;
        border-color: #00acd6;
        color: #fff;
        font-size: 12.5px;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-prof-cyan:hover {
        background-color: #00acd6;
        color: #fff;
    }
    .btn-prof-steel {
        background-color: #3c8dbc;
        border-color: #367fa9;
        color: #fff;
        font-size: 12.5px;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-prof-steel:hover {
        background-color: #367fa9;
        color: #fff;
    }
    .banner-stok-cyan {
        border-top: 2px solid #00c0ef;
        padding: 8px 12px;
        background-color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }
    .table-stok-header th {
        background-color: #ffffff !important;
        color: #333 !important;
        font-weight: 600 !important;
        font-size: 12px;
        border-bottom: 2px solid #ddd !important;
        padding: 8px 10px !important;
        white-space: nowrap;
    }
    .table-stok-data td {
        font-size: 12.5px;
        vertical-align: middle;
        padding: 7px 10px;
        border-top: 1px solid #f4f4f4;
    }
    .search-collapse-box {
        background: #fdfdfd;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 12px 15px;
        margin-bottom: 15px;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center konhakes-title-box">
        <h3>Data Stok Barang Per-Gudang</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <span>Data Stok Barang Per-Gudang</span>
        </div>
    </div>
    <div class="konhakes-orange-line"></div>

    <!-- Alert Box -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small" role="alert">
        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Action Bar -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <!-- Left Buttons -->
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-prof-orange" id="btnToggleSearch">
                <i class="fas fa-search"></i> Cari data
            </button>
            <a href="/stok/index" class="btn btn-prof-reset" title="Reset">
                <i class="fas fa-sync-alt"></i>
            </a>
            <a href="/stok/create?kode_gudang={{ $currentGudang->kode_gudang ?? '001' }}" class="btn btn-prof-green">
                <i class="fas fa-plus"></i> Tambah Stok Awal
            </a>
            <a href="/stok/upload?kode_gudang={{ $currentGudang->kode_gudang ?? '001' }}" class="btn btn-prof-cyan">
                <i class="fas fa-upload"></i> Upload Stok Awal
            </a>
        </div>

        <!-- Right Buttons -->
        <div class="d-flex align-items-center gap-2">
            <a href="/stok-adjustment/create?kode_gudang={{ $currentGudang->kode_gudang ?? '001' }}" class="btn btn-prof-steel">
                <i class="fas fa-wrench"></i> Penyesuaian Stok
            </a>
            <a href="/stok-adjustment/create?kode_gudang={{ $currentGudang->kode_gudang ?? '001' }}&tipe=Stok+Opname" class="btn btn-prof-orange">
                <i class="fas fa-clipboard-list"></i> Stock Opname
            </a>
            <a href="/stok/export?gudang_id={{ $currentGudang->id ?? 1 }}" class="btn btn-prof-cyan">
                <i class="fas fa-file-excel"></i> Export Data(xls)
            </a>
        </div>
    </div>

    <!-- Collapsible Search Filter Box -->
    <div class="search-collapse-box {{ request('q') || request('jenis_barang') || request('gudang_id') || request('kelas_terapi') ? '' : 'd-none' }}" id="searchFilterBox">
        <form action="/stok/index" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm fw-semibold mb-1" style="font-size: 12px;">Pilih Gudang</label>
                <select name="gudang_id" class="form-select form-select-sm">
                    @foreach($gudangs as $g)
                        <option value="{{ $g->id }}" {{ ($currentGudang->id ?? '') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama_gudang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm fw-semibold mb-1" style="font-size: 12px;">Jenis Barang</label>
                <select name="jenis_barang" class="form-select form-select-sm">
                    <option value="">-- Semua Jenis --</option>
                    @foreach($jenisList as $j)
                        <option value="{{ $j }}" {{ request('jenis_barang') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm fw-semibold mb-1" style="font-size: 12px;">Cari Nama / Kode Item</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Ketik kata kunci pencarian..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-prof-orange w-100">
                    <i class="fas fa-filter me-1"></i> Terapkan
                </button>
                <a href="/stok/index" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 4px; overflow: hidden;">
        <!-- Banner Line -->
        <div class="banner-stok-cyan">
            <span class="fw-semibold text-dark">
                Menampilkan Stok Pada <strong>Gudang: {{ $currentGudang->nama_gudang ?? 'Gudang Farmasi' }}</strong>
            </span>
            <span class="text-muted" style="font-size: 12px;">
                Data No <strong>{{ $barangs->firstItem() ?? 1 }} - {{ $barangs->lastItem() ?? $barangs->count() }}</strong> dari <strong>2996</strong> item
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0 table-stok-data">
                <thead class="table-stok-header">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="15%">Jenis Barang <span class="text-muted">⇅</span></th>
                        <th width="16%">Kelas Terapi <span class="text-muted">⇅</span></th>
                        <th width="14%">Kode Item</th>
                        <th width="26%">Nama Barang <span class="text-muted">⇅</span></th>
                        <th width="11%" class="text-center">Stok Tersedia</th>
                        <th width="14%" class="text-center">Daftar Batch</th>
                        <th width="10%" class="text-center">Kartu Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $index => $b)
                    @php
                        $stokVal = $b->stokGudangs->first()->stok ?? $b->stok;
                        $hasBatch = $b->batches && $b->batches->count() > 0;
                    @endphp
                    <tr>
                        <td class="text-center text-muted">{{ $barangs->firstItem() + $index }}</td>
                        <td class="text-muted">{{ $b->jenis ?? 'Obat' }}</td>
                        <td class="text-muted">{{ $b->kelas_terapi ?? '-' }}</td>
                        <td>
                            <span class="text-primary fw-semibold">{{ $b->kode_barang }}</span>
                        </td>
                        <td class="text-dark">
                            {{ $b->nama_barang }}
                        </td>
                        <td class="text-center fw-bold {{ $stokVal <= 0 ? 'text-danger' : 'text-success' }}">
                            {{ $stokVal }}
                        </td>
                        <td class="text-center" style="font-size: 11.5px;">
                            @if($hasBatch)
                                @foreach($b->batches as $batch)
                                    <span class="badge bg-info text-dark me-1">{{ $batch->nomor_batch }} ({{ $batch->stok }})</span>
                                @endforeach
                            @else
                                <span class="text-danger">--Tidak Ada Batch--</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/stok/kartu-stok/{{ $b->id }}?gudang_id={{ $currentGudang->id ?? '' }}" class="text-primary text-decoration-none" style="font-size: 12px;">
                                <i class="fas fa-file-alt me-1"></i> Kartu Stok
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-boxes fa-3x mb-2 text-secondary opacity-50"></i>
                            <div>Tidak ada data stok barang yang tersedia.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($barangs->hasPages())
        <div class="card-footer bg-white border-top py-2 px-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                Menampilkan {{ $barangs->firstItem() ?? 0 }} - {{ $barangs->lastItem() ?? 0 }} dari {{ $barangs->total() }} barang
            </span>
            <div>
                {{ $barangs->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Upload Stok Awal -->
<div class="modal fade" id="modalUploadStok" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-cyan text-white py-2" style="background-color: #00c0ef;">
                <h6 class="modal-title mb-0"><i class="fas fa-upload me-2"></i>Upload Stok Awal CSV</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/stok/upload" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Gudang Target</label>
                        <select name="gudang_id" class="form-select">
                            @foreach($gudangs as $g)
                                <option value="{{ $g->id }}" {{ ($currentGudang->id ?? '') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <a href="/stok/template-upload" class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-download me-1"></i> Download Format Template (CSV)
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File CSV / Excel</label>
                        <input type="file" name="file_stok" class="form-control" accept=".csv, .xlsx, .xls" required>
                        <small class="text-muted">Format file: Kode Item, Nama Barang, Stok Awal</small>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-prof-green btn-sm">Upload Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#btnToggleSearch').on('click', function() {
        $('#searchFilterBox').toggleClass('d-none');
    });
});
</script>
@endsection
