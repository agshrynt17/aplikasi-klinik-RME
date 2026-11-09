@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .profaskes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .profaskes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #444;
        text-decoration: none;
    }
    .profaskes-breadcrumb a:hover { text-decoration: underline; }
    .profaskes-summary-text {
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
    .btn-prof-update-cyan {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #ffffff !important;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        line-height: 1.5;
        text-decoration: none;
    }
    .btn-prof-update-cyan:hover { background-color: #00acd6; }
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
        text-decoration: none;
        margin-left: 5px;
    }
    .btn-prof-tarik:hover { background-color: #008d4c; }
    .btn-filter-orange {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        padding: 3px 9px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
    }
    .btn-filter-orange:hover { background-color: #d68407; }
    .btn-filter-refresh {
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        color: #444 !important;
        padding: 3px 9px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        margin-left: 3px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-filter-refresh:hover { background-color: #e7e7e7; }
    .icon-check {
        font-weight: bold;
        font-size: 13px;
        color: #333;
    }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="profaskes-header">
        <h3 class="profaskes-title">Bridging PCare - Setting Status Pulang</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <span style="color:#777;">Bridging PCare - Setting Status Pulang</span>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter & Action Bar --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:12px;">
        {{-- Cari Data --}}
        <div>
            <div style="font-size:12px; font-weight:600; color:#444; margin-bottom:4px;">Cari Data</div>
            <form method="GET" action="/pcare-status-pulang/index" style="display:flex; align-items:center; gap:8px;">
                <label style="font-size:13px; color:#333; cursor:pointer; margin-bottom:0; font-weight:normal;">
                    <input type="checkbox" name="rawat_inap" value="1" {{ request('rawat_inap') == '1' ? 'checked' : '' }} onchange="this.form.submit()" style="margin-right:4px;">
                    Rawat Inap
                </label>
                <button type="submit" class="btn-filter-orange" title="Cari">
                    <i class="fas fa-search"></i>
                </button>
                <a href="/pcare-status-pulang/index" class="btn-filter-refresh" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </form>
        </div>

        {{-- Tombol Aksi Kanan --}}
        <div>
            <a href="/pcare-status-pulang/update" class="btn-prof-update-cyan">
                <i class="fas fa-edit"></i> Update Data
            </a>
            <button type="button" class="btn-prof-tarik" id="btnTarikData" onclick="tarikDataStatusPulang()">
                <i class="fas fa-download"></i> Tarik Data Status Pulang Dari PCare
            </button>
        </div>
    </div>

    {{-- Summary --}}
    <div class="profaskes-summary-text">
        Menampilkan <b>1-{{ $total }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel --}}
    <div class="table-responsive" style="border: none;">
        <table class="table-pcare-grid">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th class="col-sort" style="width:140px;">
                        <a href="javascript:void(0)">Kd Status Pulang</a>
                    </th>
                    <th class="col-sort" style="width:160px;">
                        <a href="javascript:void(0)">Nm Status Pulang</a>
                    </th>
                    <th class="col-sort" style="width:180px;">
                        <a href="javascript:void(0)">Status Pulang Pada(Pcare)</a>
                    </th>
                    <th class="col-sort" style="width:100px; text-align:center;">
                        <a href="javascript:void(0)">Rawat Inap</a>
                    </th>
                    <th class="col-sort" style="width:100px; text-align:center;">
                        <a href="javascript:void(0)">Rawat Jalan</a>
                    </th>
                    <th class="col-sort">
                        <a href="javascript:void(0)">Status Pulang Pada(Aplikasi)</a>
                    </th>
                    <th class="col-sort" style="width:160px;">
                        <a href="javascript:void(0)">Diubah Pada</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($statusPulang as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->kd_status_pulang }}</td>
                    <td>{{ $item->nm_status_pulang }}</td>
                    <td>{{ $item->status_pulang_pcare ?? $item->nm_status_pulang }}</td>
                    <td style="text-align:center;">
                        @if($item->is_rawat_inap)
                            <i class="fas fa-check icon-check"></i>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($item->is_rawat_jalan)
                            <i class="fas fa-check icon-check"></i>
                        @endif
                    </td>
                    <td>{{ $item->status_pulang_aplikasi ?? '-' }}</td>
                    <td style="font-size:12px; color:#555;">
                        {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i:s') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">
                        <i class="fas fa-info-circle me-1"></i> Data status pulang tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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

function tarikDataStatusPulang() {
    const btn  = document.getElementById('btnTarikData');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menarik data...';

    $.ajax({
        url: '/pcare-status-pulang/tarik',
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
</script>
@endsection
