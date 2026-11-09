@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* 1:1 PROFASKES HISTORI PENYESUAIAN STOK */
    .profaskes-title-box {
        padding-bottom: 6px;
        margin-bottom: 6px;
    }
    .profaskes-title {
        font-size: 20px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .profaskes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .profaskes-orange-line {
        height: 2px;
        background-color: #f39c12;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 15px;
    }
    .btn-prof-orange {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .btn-prof-orange:hover {
        background-color: #d68407;
        color: #fff;
    }
    .btn-prof-reset {
        background-color: #fff;
        border: 1px solid #ccc;
        color: #333;
        font-size: 13px;
        padding: 6px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-prof-reset:hover {
        background-color: #e6e6e6;
        color: #333;
    }
    .btn-prof-pdf {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .btn-prof-pdf:hover {
        background-color: #0097bc;
        color: #fff;
    }
    .btn-prof-xls {
        background-color: #3c8dbc;
        border: 1px solid #367fa9;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .btn-prof-xls:hover {
        background-color: #286090;
        color: #fff;
    }
    .table-box-cyan {
        background-color: #fff;
        border-top: 3px solid #00c0ef !important;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .table-histori-grid {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
        font-size: 13px;
    }
    .table-histori-grid th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
    }
    .table-histori-grid th a {
        color: #3c8dbc !important;
        text-decoration: none;
    }
    .table-histori-grid th a:hover {
        text-decoration: underline;
    }
    .table-histori-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-histori-grid tbody tr:hover td {
        background-color: #f9f9f9;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb (PROFASKES 1:1) -->
    <div class="d-flex justify-content-between align-items-center profaskes-title-box">
        <h3 class="profaskes-title">Histori Penyesuaian Stok</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <span>Histori Penyesuaian Stok</span>
        </div>
    </div>
    <div class="profaskes-orange-line"></div>

    <!-- Alert Box -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Toolbar Buttons (PROFASKES 1:1) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-prof-orange" id="btnToggleCari">
                <i class="fa fa-search"></i> Cari data
            </button>
            <a href="{{ url('/stok-adjustment/index') }}" class="btn btn-prof-reset" title="Refresh">
                <i class="fa fa-sync-alt"></i>
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/stok-adjustment/export-pdf') }}" target="_blank" class="btn btn-prof-pdf">
                <i class="far fa-file-alt"></i> Cetak Laporan(PDF)
            </a>
            <a href="{{ url('/stok-adjustment/export-xls') }}" class="btn btn-prof-xls">
                <i class="far fa-file-excel"></i> Cetak Laporan(XLS)
            </a>
        </div>
    </div>

    <!-- Collapsible Search Filter Box -->
    <div id="filterCollapseBox" class="card border-0 shadow-sm mb-3" style="display: {{ request()->hasAny(['q', 'gudang_id', 'tipe', 'tgl_mulai', 'tgl_selesai']) ? 'block' : 'none' }}; border-radius: 3px;">
        <div class="card-body p-3 bg-light border">
            <form method="GET" action="{{ url('/stok-adjustment/index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small mb-1 fw-semibold">Kata Kunci (No / User / Ket.)</label>
                        <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}" placeholder="Cari penyesuaian...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-1 fw-semibold">Gudang</label>
                        <select name="gudang_id" class="form-select form-select-sm">
                            <option value="">-- Semua Gudang --</option>
                            @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ request('gudang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1 fw-semibold">Tipe</label>
                        <select name="tipe" class="form-select form-select-sm">
                            <option value="">-- Semua Tipe --</option>
                            <option value="Penyesuaian Stok" {{ request('tipe') == 'Penyesuaian Stok' ? 'selected' : '' }}>Penyesuaian Stok</option>
                            <option value="Stok Opname" {{ request('tipe') == 'Stok Opname' ? 'selected' : '' }}>Stok Opname</option>
                            <option value="Koreksi Stok" {{ request('tipe') == 'Koreksi Stok' ? 'selected' : '' }}>Koreksi Stok</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1 fw-semibold">Tgl. Penyesuaian</label>
                        <input type="date" name="tgl_mulai" class="form-control form-control-sm" value="{{ request('tgl_mulai') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa fa-search me-1"></i> Filter</button>
                        <a href="{{ url('/stok-adjustment/index') }}" class="btn btn-secondary btn-sm px-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Box with Cyan Top Line (PROFASKES 1:1) -->
    <div class="table-box-cyan">
        <div class="table-responsive">
            <table class="table-histori-grid">
                <thead>
                    <tr>
                        <th style="width: 45px; color: #333;">#</th>
                        <th style="width: 140px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'no' ? '-no' : 'no')]) }}">No</a>
                        </th>
                        <th style="width: 150px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'tanggal' ? '-tanggal' : 'tanggal')]) }}">Tgl.Penyesuaian</a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'gudang' ? '-gudang' : 'gudang')]) }}">Gudang</a>
                        </th>
                        <th style="width: 140px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'tipe' ? '-tipe' : 'tipe')]) }}">Tipe</a>
                        </th>
                        <th style="width: 130px; color: #333;">Jumlah Item</th>
                        <th style="width: 160px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'created_at' ? '-created_at' : 'created_at')]) }}">
                                Tgl. Input <i class="fas fa-sort ms-1" style="font-size: 11px;"></i>
                            </a>
                        </th>
                        <th style="width: 130px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'user' ? '-user' : 'user')]) }}">User</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $index => $item)
                    <tr>
                        <td>{{ $adjustments->firstItem() + $index }}</td>
                        <td>
                            <a href="/stok-adjustment/view?id={{ $item->id }}" onclick="openAdjustmentDetail(event, {{ $item->id }})" style="color: #3c8dbc; text-decoration: none; font-weight: 500;">
                                {{ $item->no_penyesuaian }}
                            </a>
                        </td>
                        <td>{{ $item->tanggal_penyesuaian ? $item->tanggal_penyesuaian->format('Y-m-d') : '-' }}</td>
                        <td>{{ $item->masterGudang->nama_gudang ?? $item->kode_gudang }}</td>
                        <td>{{ $item->tipe }}</td>
                        <td class="fw-semibold">{{ $item->details->count() }}</td>
                        <td>{{ $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                        <td>{{ $item->user_name ?? ($item->user->name ?? '-') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 10px 12px; color: #333; background-color: #fff;">
                            Tidak ada data yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($adjustments->hasPages())
    <div class="mt-2">
        {{ $adjustments->links() }}
    </div>
    @endif
</div>

<!-- MODAL DETAIL PENYESUAIAN STOK -->
<div class="modal fade" id="modalDetailAdjustment" tabindex="-1" aria-labelledby="modalDetailAdjustmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-2 px-3">
                <h5 class="modal-title fs-6 fw-semibold" id="modalDetailAdjustmentLabel">
                    <i class="far fa-file-alt me-1"></i> Rincian Penyesuaian Stok
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="modalLoadingAdjSpinner" class="text-center py-4">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="text-muted mt-2 small">Memuat rincian penyesuaian...</div>
                </div>

                <div id="modalDetailAdjContent" style="display: none;">
                    <div class="row g-2 mb-3 bg-light p-2 rounded border">
                        <div class="col-md-3">
                            <span class="text-muted small d-block">No Penyesuaian</span>
                            <span class="fw-bold text-primary" id="adjNo">-</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Tgl. Penyesuaian</span>
                            <span class="fw-bold" id="adjTanggal">-</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Gudang</span>
                            <span class="fw-bold" id="adjGudang">-</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Tipe</span>
                            <span class="badge bg-secondary" id="adjTipe">-</span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <span class="text-muted small d-block">User Input</span>
                            <span id="adjUser">-</span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <span class="text-muted small d-block">Keterangan</span>
                            <span id="adjKeterangan">-</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2 text-dark border-bottom pb-1" style="font-size: 13px;">
                        <i class="fas fa-boxes me-1 text-primary"></i> Daftar Item yang Disesuaikan
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 12.5px;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;" class="text-center">#</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center" style="width: 90px;">Stok Sistem</th>
                                    <th class="text-center" style="width: 90px;">Stok Fisik</th>
                                    <th class="text-center" style="width: 80px;">Selisih</th>
                                    <th style="width: 80px;">Satuan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="adjItemsTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <a href="#" id="linkDirectAdjView" class="btn btn-outline-primary btn-sm me-auto">Buka Halaman Penuh</a>
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#btnToggleCari').on('click', function() {
        $('#filterCollapseBox').slideToggle(150);
    });
});

function openAdjustmentDetail(event, adjId) {
    event.preventDefault();
    const modalEl = document.getElementById('modalDetailAdjustment');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    $('#linkDirectAdjView').attr('href', `/stok-adjustment/view?id=${adjId}`);
    $('#modalLoadingAdjSpinner').show();
    $('#modalDetailAdjContent').hide();

    $.ajax({
        url: `/stok-adjustment/${adjId}`,
        type: 'GET',
        headers: { 'Accept': 'application/json' },
        success: function(res) {
            if (res.success && res.data) {
                const data = res.data;
                $('#adjNo').text(data.no_penyesuaian || '-');
                $('#adjTanggal').text(data.tanggal_penyesuaian ? data.tanggal_penyesuaian.substring(0, 10) : '-');
                const gudangName = data.master_gudang ? data.master_gudang.nama_gudang : data.kode_gudang;
                $('#adjGudang').text(gudangName || '-');
                $('#adjTipe').text(data.tipe || '-');
                $('#adjUser').text(data.user_name || (data.user ? data.user.name : '-'));
                $('#adjKeterangan').text(data.keterangan || '-');

                let rowsHtml = '';
                if (data.details && data.details.length > 0) {
                    data.details.forEach((item, idx) => {
                        const barang = item.master_barang || {};
                        const selisihClass = item.selisih > 0 ? 'text-success' : (item.selisih < 0 ? 'text-danger' : 'text-muted');
                        rowsHtml += `
                            <tr>
                                <td class="text-center">${idx + 1}</td>
                                <td class="fw-semibold text-primary">${barang.kode_barang || '-'}</td>
                                <td>${barang.nama_barang || '-'}</td>
                                <td class="text-center">${item.stok_sistem}</td>
                                <td class="text-center fw-bold">${item.stok_fisik}</td>
                                <td class="text-center fw-bold ${selisihClass}">${item.selisih > 0 ? '+' : ''}${item.selisih}</td>
                                <td>${item.satuan || barang.kemasan || 'Pcs'}</td>
                                <td>${item.catatan || '-'}</td>
                            </tr>
                        `;
                    });
                } else {
                    rowsHtml = `<tr><td colspan="8" class="text-center text-muted py-2">Tidak ada rincian item.</td></tr>`;
                }

                $('#adjItemsTableBody').html(rowsHtml);
                $('#modalLoadingAdjSpinner').hide();
                $('#modalDetailAdjContent').show();
            } else {
                window.location.href = `/stok-adjustment/view?id=${adjId}`;
            }
        },
        error: function() {
            window.location.href = `/stok-adjustment/view?id=${adjId}`;
        }
    });
}
</script>
@endsection
