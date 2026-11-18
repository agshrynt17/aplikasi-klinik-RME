@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* 1:1 KONHAKES STOK CANCELS */
    .stok-cancel-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .konhakes-page-title {
        font-size: 24px;
        font-weight: 400;
        color: #333;
        margin: 0;
        line-height: 1.2;
    }
    .konhakes-breadcrumb-nav {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb-nav a {
        color: #444;
        text-decoration: none;
    }
    .konhakes-breadcrumb-nav a:hover {
        text-decoration: underline;
    }
    .konhakes-summary-info {
        font-size: 13px;
        color: #333;
        margin-bottom: 8px;
    }
    /* Table Styling matching Yii2 GridView AdminLTE */
    .table-konhakes-grid {
        background-color: transparent;
        width: auto;
        min-width: 850px;
        max-width: 100%;
        margin-bottom: 0;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
    }
    .table-konhakes-grid th {
        background-color: #ecf0f5 !important;
        border: 1px solid #f4f4f4 !important;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-konhakes-grid th.col-hash {
        color: #333 !important;
        width: 100px;
    }
    .table-konhakes-grid th a {
        color: #3c8dbc !important;
        text-decoration: none;
    }
    .table-konhakes-grid th a:hover {
        text-decoration: underline;
    }
    .table-konhakes-grid tr.filters td {
        background-color: #ecf0f5 !important;
        border: 1px solid #f4f4f4 !important;
        padding: 4px 6px;
        vertical-align: middle;
    }
    .table-konhakes-grid .grid-filter-input {
        background-color: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 0;
        height: 30px;
        font-size: 13px;
        padding: 4px 8px;
        width: 100%;
        min-width: 135px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        color: #555;
    }
    .table-konhakes-grid .grid-filter-input:focus {
        border-color: #3c8dbc;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(60,141,188,.6);
    }
    .table-konhakes-grid tbody td {
        border: 1px solid #f4f4f4 !important;
        font-size: 13px;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-konhakes-grid tbody tr:nth-of-type(odd) td {
        background-color: #f9f9f9;
    }
    .table-konhakes-grid tbody tr:nth-of-type(even) td {
        background-color: #ffffff;
    }
    .table-konhakes-grid tbody tr:hover td {
        background-color: #f5f5f5;
    }
    .konhakes-detail-link {
        color: #3c8dbc !important;
        text-decoration: none !important;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: 12px;
    }
    .konhakes-detail-link:hover {
        color: #23527c !important;
        text-decoration: underline !important;
    }
    .konhakes-detail-link i {
        color: #3c8dbc;
        font-size: 13px;
    }
</style>

<div class="stok-cancel-page">
    <!-- Header Title & Breadcrumb (KONHAKES 1:1) -->
    <div class="konhakes-header">
        <h3 class="konhakes-page-title">Stok Cancels</h3>
        <div class="konhakes-breadcrumb-nav">
            <a href="/">Beranda</a> <span style="color: #ccc; margin: 0 2px;">&gt;</span> <span style="color: #777;">Stok Cancels</span>
        </div>
    </div>

    <!-- Summary (KONHAKES 1:1) -->
    <div class="konhakes-summary-info">
        Menampilkan <b>{{ $cancels->total() > 0 ? $cancels->firstItem() : 0 }}-{{ $cancels->total() > 0 ? $cancels->lastItem() : 0 }}</b> dari <b>{{ $cancels->total() }}</b> item.
    </div>

    <!-- GridView Table Form (KONHAKES 1:1) -->
    <form method="GET" action="{{ url('/stok-cancel/index') }}" id="gridFilterForm">
        <div class="table-responsive" style="border: none;">
            <table class="table-konhakes-grid">
                <thead>
                    <!-- Header Labels Row -->
                    <tr>
                        <th class="col-hash">#</th>
                        <th style="min-width: 145px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'cancel_no' ? '-cancel_no' : 'cancel_no')]) }}">Cancel No</a>
                        </th>
                        <th style="min-width: 145px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'cancel_tanggal' ? '-cancel_tanggal' : 'cancel_tanggal')]) }}">Cancel Tanggal</a>
                        </th>
                        <th style="min-width: 145px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'kode_gudang' ? '-kode_gudang' : 'kode_gudang')]) }}">Kode Gudang</a>
                        </th>
                        <th style="min-width: 145px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'cancel_type' ? '-cancel_type' : 'cancel_type')]) }}">Cancel Type</a>
                        </th>
                        <th style="min-width: 145px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => (request('sort') == 'create_at' ? '-create_at' : 'create_at')]) }}">Create At</a>
                        </th>
                    </tr>
                    <!-- Filter Row 2 (KONHAKES 1:1) -->
                    <tr class="filters">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" name="cancel_no" value="{{ request('cancel_no') }}" class="grid-filter-input">
                        </td>
                        <td>
                            <input type="text" name="cancel_tanggal" value="{{ request('cancel_tanggal') }}" class="grid-filter-input">
                        </td>
                        <td>
                            <input type="text" name="kode_gudang" value="{{ request('kode_gudang') }}" class="grid-filter-input">
                        </td>
                        <td>
                            <input type="text" name="cancel_type" value="{{ request('cancel_type') }}" class="grid-filter-input">
                        </td>
                        <td>
                            <input type="text" name="create_at" value="{{ request('create_at') }}" class="grid-filter-input">
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cancels as $index => $item)
                    <tr>
                        <td style="white-space: nowrap;">
                            <span>{{ $cancels->firstItem() + $index }}</span>
                            <a href="/stok-cancel/view?id={{ $item->id }}" class="konhakes-detail-link" onclick="openCancelDetail(event, {{ $item->id }})">
                                <i class="far fa-id-card"></i> Detail
                            </a>
                        </td>
                        <td>{{ $item->cancel_no }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->cancel_tanggal)->format('Y-m-d') }}</td>
                        <td>{{ $item->kode_gudang }}</td>
                        <td>{{ $item->cancel_type }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">(tidak ada data)</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    @if($cancels->hasPages())
    <div class="mt-3">
        {{ $cancels->links() }}
    </div>
    @endif
</div>

<!-- MODAL DETAIL (Dapat dibuka instan saat klik Detail) -->
<div class="modal fade" id="modalDetailCancel" tabindex="-1" aria-labelledby="modalDetailCancelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-2 px-3">
                <h5 class="modal-title fs-6 fw-semibold" id="modalDetailCancelLabel">
                    <i class="far fa-id-card me-1"></i> Detail Pembatalan Stok
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="modalLoadingSpinner" class="text-center py-4">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="text-muted mt-2 small">Memuat data detail pembatalan...</div>
                </div>

                <div id="modalDetailContent" style="display: none;">
                    <div class="row g-2 mb-3 bg-light p-2 rounded border">
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Cancel No</span>
                            <span class="fw-bold" id="detailCancelNo">-</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Cancel Tanggal</span>
                            <span class="fw-bold" id="detailCancelTanggal">-</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Kode & Nama Gudang</span>
                            <span class="fw-bold" id="detailKodeGudang">-</span>
                        </div>
                        <div class="col-md-4 mt-2">
                            <span class="text-muted small d-block">Cancel Type</span>
                            <span class="badge bg-secondary" id="detailCancelType">-</span>
                        </div>
                        <div class="col-md-4 mt-2">
                            <span class="text-muted small d-block">Waktu Input (Create At)</span>
                            <span id="detailCreateAt">-</span>
                        </div>
                        <div class="col-md-4 mt-2">
                            <span class="text-muted small d-block">Keterangan / Alasan</span>
                            <span id="detailKeterangan">-</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2 text-dark border-bottom pb-1" style="font-size: 13px;">
                        <i class="fas fa-boxes me-1 text-primary"></i> Daftar Item Barang yang Dibatalkan
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 12.5px;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;" class="text-center">#</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center" style="width: 90px;">Jumlah</th>
                                    <th style="width: 80px;">Satuan</th>
                                    <th>Keterangan Item</th>
                                </tr>
                            </thead>
                            <tbody id="detailItemsTableBody">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <a href="#" id="linkDirectView" class="btn btn-outline-primary btn-sm me-auto">Buka Halaman Penuh</a>
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-submit filter on Enter
    $('.grid-filter-input').on('keypress', function(e) {
        if (e.which === 13) {
            $('#gridFilterForm').submit();
        }
    });
});

function openCancelDetail(event, cancelId) {
    event.preventDefault();
    const modalEl = document.getElementById('modalDetailCancel');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    $('#linkDirectView').attr('href', `/stok-cancel/view?id=${cancelId}`);
    $('#modalLoadingSpinner').show();
    $('#modalDetailContent').hide();

    $.ajax({
        url: `/stok-cancel/${cancelId}`,
        type: 'GET',
        headers: { 'Accept': 'application/json' },
        success: function(res) {
            if (res.success && res.data) {
                const data = res.data;
                $('#detailCancelNo').text(data.cancel_no || '-');
                $('#detailCancelTanggal').text(data.cancel_tanggal ? data.cancel_tanggal.substring(0, 10) : '-');
                const gudangName = data.master_gudang ? data.master_gudang.nama_gudang : '';
                $('#detailKodeGudang').text((data.kode_gudang || '-') + (gudangName ? ' (' + gudangName + ')' : ''));
                $('#detailCancelType').text(data.cancel_type || '-');
                $('#detailCreateAt').text(data.created_at ? new Date(data.created_at).toLocaleString('id-ID') : '-');
                $('#detailKeterangan').text(data.keterangan || '-');

                let rowsHtml = '';
                if (data.details && data.details.length > 0) {
                    data.details.forEach((item, idx) => {
                        const barang = item.master_barang || {};
                        rowsHtml += `
                            <tr>
                                <td class="text-center">${idx + 1}</td>
                                <td class="fw-semibold text-primary">${barang.kode_barang || '-'}</td>
                                <td>${barang.nama_barang || '-'}</td>
                                <td class="text-center fw-bold text-danger">-${item.jumlah}</td>
                                <td>${item.satuan || barang.kemasan || 'Pcs'}</td>
                                <td>${item.keterangan || '-'}</td>
                            </tr>
                        `;
                    });
                } else {
                    rowsHtml = `<tr><td colspan="6" class="text-center text-muted py-2">Tidak ada rincian item.</td></tr>`;
                }

                $('#detailItemsTableBody').html(rowsHtml);
                $('#modalLoadingSpinner').hide();
                $('#modalDetailContent').show();
            } else {
                window.location.href = `/stok-cancel/view?id=${cancelId}`;
            }
        },
        error: function() {
            window.location.href = `/stok-cancel/view?id=${cancelId}`;
        }
    });
}
</script>
@endsection
