@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .konhakes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }
    .konhakes-summary-text {
        font-size: 13px;
        color: #333;
        margin-bottom: 8px;
    }
    .table-pcare-grid {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 15px;
    }
    .table-pcare-grid th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
    }
    .table-pcare-grid th.col-sort a {
        color: #3c8dbc !important;
        text-decoration: none;
    }
    .table-pcare-grid th.col-sort a:hover { text-decoration: underline; }
    .table-pcare-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-pcare-grid tbody tr:nth-of-type(odd) td  { background-color: #ffffff; }
    .table-pcare-grid tbody tr:nth-of-type(even) td { background-color: #f9f9f9; }
    .table-pcare-grid tbody tr:hover td             { background-color: #f5f5f5; }
    .btn-prof-update {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        line-height: 1.5;
        margin-bottom: 10px;
    }
    .btn-prof-update:hover { background-color: #d68407; }
    .btn-prof-tarik {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        line-height: 1.5;
        margin-bottom: 10px;
        float: right;
    }
    .btn-prof-tarik:hover { background-color: #008d4c; }
    .filter-input {
        width: 100%;
        border: 1px solid #d2d6de;
        padding: 3px 6px;
        font-size: 12px;
        border-radius: 2px;
        outline: none;
    }
    .filter-input:focus {
        border-color: #3c8dbc;
    }
    .belum-diset {
        color: #e08e0b;
        font-style: italic;
    }
    .action-icon {
        color: #3c8dbc;
        margin: 0 4px;
        font-size: 13px;
        text-decoration: none;
    }
    .action-icon:hover { color: #204d74; }
    .action-icon.trash { color: #3c8dbc; }
    .action-icon.trash:hover { color: #dd4b39; }
    .pagination {
        margin: 0;
    }
    .pagination .page-item .page-link {
        color: #3c8dbc;
        font-size: 12px;
        padding: 4px 10px;
    }
    .pagination .page-item.active .page-link {
        background-color: #3c8dbc;
        border-color: #3c8dbc;
        color: #fff;
    }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging PCare - Setting Data Sarana</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-sarana/index">Bridging PCare - Setting Data Sarana</a>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tombol Aksi --}}
    <div style="overflow: hidden; margin-bottom: 6px;">
        <button type="button" class="btn-prof-update" id="btnUpdateData" onclick="updateData()">
            <i class="fas fa-edit"></i> Update Data
        </button>
        <button type="button" class="btn-prof-tarik" id="btnTarikData" onclick="tarikDataSarana()">
            <i class="fas fa-download"></i> Tarik Data Sarana Dari PCare
        </button>
    </div>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>{{ $sarana->firstItem() ?? 1 }}-{{ $sarana->lastItem() ?? count($sarana) }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel dengan filter baris kedua --}}
    <div class="table-responsive" style="border: none;">
        <form method="GET" action="/pcare-sarana/index" id="formFilterSarana">
            <table class="table-pcare-grid">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th class="col-sort" style="width:140px;">
                            <a href="javascript:void(0)">Kd Sarana</a>
                        </th>
                        <th class="col-sort">
                            <a href="javascript:void(0)">Nm Sarana <i class="fas fa-sort ms-1" style="font-size:11px;"></i></a>
                        </th>
                        <th class="col-sort" style="width:200px;">
                            <a href="javascript:void(0)">Kd Sarana Reff</a>
                        </th>
                        <th class="col-sort" style="width:200px;">
                            <a href="javascript:void(0)">Diubah Pada</a>
                        </th>
                        <th style="width:100px; text-align:center;"></th>
                    </tr>
                    <tr style="background:#fff;">
                        <td></td>
                        <td>
                            <input type="text" name="kd_sarana" value="{{ request('kd_sarana') }}" class="filter-input" onchange="document.getElementById('formFilterSarana').submit()">
                        </td>
                        <td>
                            <input type="text" name="nm_sarana" value="{{ request('nm_sarana') }}" class="filter-input" onchange="document.getElementById('formFilterSarana').submit()">
                        </td>
                        <td>
                            <input type="text" name="kd_sarana_reff" value="{{ request('kd_sarana_reff') }}" class="filter-input" onchange="document.getElementById('formFilterSarana').submit()">
                        </td>
                        <td>
                            <input type="text" class="filter-input" disabled style="background:#fcfcfc;">
                        </td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sarana as $i => $item)
                    <tr>
                        <td>{{ ($sarana->firstItem() ?? 1) + $i }}</td>
                        <td>{{ $item->kd_sarana }}</td>
                        <td>{{ $item->nm_sarana }}</td>
                        <td>
                            @if(empty($item->kd_sarana_reff) || $item->kd_sarana_reff == '(belum diset)')
                                <span class="belum-diset">(belum diset)</span>
                            @else
                                {{ $item->kd_sarana_reff }}
                            @endif
                        </td>
                        <td style="font-size:12px; color:#555;">
                            {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i:s') : '-' }}
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="/pcare-sarana/{{ $item->id }}" class="action-icon" title="Lihat">
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="/pcare-sarana/{{ $item->id }}/edit" class="action-icon" title="Ubah">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="/pcare-sarana/{{ $item->id }}/delete" class="action-icon trash" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data sarana ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-1"></i> Data sarana tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-2">
        {{ $sarana->links() }}
    </div>
</div>

{{-- MODAL HASIL TARIK DATA --}}
<div class="modal fade" id="modalHasil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white py-2 px-3" id="modalHasilHeader" style="background-color:#00a65a;">
                <h5 class="modal-title fs-6 fw-semibold text-white" id="modalHasilTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <p id="modalHasilBody" class="mb-0 small" style="white-space:pre-wrap;"></p>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-sm px-4 text-white" data-bs-dismiss="modal"
                        onclick="location.reload()"
                        style="background-color:#00a65a; border-color:#008d4c;">
                    <i class="fas fa-check me-1"></i> Tutup & Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';

function tarikDataSarana() {
    const btn  = document.getElementById('btnTarikData');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menarik data...';

    $.ajax({
        url: '/pcare-sarana/tarik',
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        data: {},
        success: function(res) {
            btn.disabled = false;
            btn.innerHTML = orig;
            $('#modalHasilHeader').css('background-color', res.success ? '#00a65a' : '#dd4b39');
            $('#modalHasilTitle').html(res.success
                ? '<i class="fas fa-check-circle me-1"></i> Tarik Data Berhasil'
                : '<i class="fas fa-times-circle me-1"></i> Tarik Data Gagal');
            $('#modalHasilBody').text(res.message);
            new bootstrap.Modal(document.getElementById('modalHasil')).show();
        },
        error: function() {
            btn.disabled = false;
            btn.innerHTML = orig;
            alert('Terjadi kesalahan jaringan.');
        }
    });
}

function updateData() {
    const btn = document.getElementById('btnUpdateData');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memperbarui...';
    setTimeout(function() {
        location.reload();
    }, 600);
}
</script>
@endsection
