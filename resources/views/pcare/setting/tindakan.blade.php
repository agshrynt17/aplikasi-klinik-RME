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
    .konhakes-title { font-size: 20px; font-weight: 400; color: #333; margin: 0; }
    .konhakes-breadcrumb { font-size: 12px; color: #777; }
    .konhakes-breadcrumb a { color: #337ab7 !important; text-decoration: none; cursor: pointer; }
    .konhakes-breadcrumb a:hover { color: #23527c !important; text-decoration: underline; }
    .konhakes-summary-text { font-size: 13px; color: #333; margin-bottom: 8px; }

    /* Filter Bar */
    .filter-bar {
        background: #fff;
        border: 1px solid #f4f4f4;
        padding: 10px 14px;
        margin-bottom: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: flex-end;
    }
    .filter-bar .fb-group { display: flex; flex-direction: column; gap: 3px; }
    .filter-bar .fb-group label { font-size: 12px; font-weight: 600; color: #555; margin: 0; }
    .filter-bar .fb-input {
        height: 28px;
        padding: 3px 8px;
        font-size: 12px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        outline: none;
        min-width: 180px;
    }
    .filter-bar .fb-input:focus { border-color: #3c8dbc; }
    .fb-select { min-width: 160px; }

    .btn-search {
        background-color: #3c8dbc; border: 1px solid #367fa9;
        color: #fff; font-size: 12px; padding: 5px 14px; border-radius: 3px; cursor: pointer;
    }
    .btn-search:hover { background-color: #367fa9; }
    .btn-reset {
        background-color: #d2d6de; border: 1px solid #c5c9d0;
        color: #333; font-size: 12px; padding: 5px 14px; border-radius: 3px; cursor: pointer;
        text-decoration: none; display: inline-block;
    }
    .btn-reset:hover { background-color: #c0c5cf; color: #333; }

    /* Action buttons */
    .btn-update-data {
        background-color: #f39c12; border: 1px solid #e08e0b;
        color: #fff !important; font-size: 12px; padding: 4px 12px; border-radius: 3px;
        display: inline-flex; align-items: center; gap: 5px; cursor: pointer; line-height: 1.5;
    }
    .btn-update-data:hover { background-color: #d68407; }
    .btn-tarik {
        background-color: #00a65a; border: 1px solid #008d4c;
        color: #fff !important; font-size: 12px; padding: 4px 12px; border-radius: 3px;
        display: inline-flex; align-items: center; gap: 5px; cursor: pointer; line-height: 1.5; float: right;
    }
    .btn-tarik:hover { background-color: #008d4c; }

    /* Table */
    .table-pcare-grid {
        width: 100%; background: #fff; border: 1px solid #f4f4f4;
        border-collapse: collapse; font-size: 13px; margin-bottom: 15px;
    }
    .table-pcare-grid th {
        background-color: #fff !important; border: 1px solid #f4f4f4 !important;
        padding: 8px 10px; vertical-align: middle; white-space: nowrap; font-weight: 600;
    }
    .table-pcare-grid th.col-sort a { color: #3c8dbc !important; text-decoration: none; }
    .table-pcare-grid th.col-sort a:hover { text-decoration: underline; }
    .table-pcare-grid td {
        border: 1px solid #f4f4f4 !important; padding: 7px 10px;
        vertical-align: middle; color: #333;
    }
    .table-pcare-grid tbody tr:nth-of-type(odd) td  { background-color: #fff; }
    .table-pcare-grid tbody tr:nth-of-type(even) td { background-color: #f9f9f9; }
    .table-pcare-grid tbody tr:hover td             { background-color: #f5f5f5; }

    .belum-diatur { color: #dd4b39; font-style: italic; }
    .action-icon { color: #3c8dbc; margin: 0 3px; font-size: 13px; text-decoration: none; }
    .action-icon:hover { color: #1d6fa5; }

    .badge-tkp-jalan { background: #00a65a; color: #fff; font-size: 11px; padding: 2px 7px; border-radius: 3px; white-space: nowrap; }
    .badge-tkp-inap  { background: #3c8dbc; color: #fff; font-size: 11px; padding: 2px 7px; border-radius: 3px; white-space: nowrap; }
    .badge-tkp-other { background: #f39c12; color: #fff; font-size: 11px; padding: 2px 7px; border-radius: 3px; white-space: nowrap; }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging PCare - Setting Master Tindakan</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>&nbsp;»&nbsp;
            <a href="/pcare-tindakan/index">Bridging PCare · Setting Master Tindakan</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2 shadow-sm" style="border-radius:3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter Bar --}}
    <form method="GET" action="/pcare-tindakan/index" id="formFilter">
        <div class="filter-bar">
            <div class="fb-group">
                <label>Nm Tindakan</label>
                <input type="text" name="nm_tindakan" class="fb-input" value="{{ request('nm_tindakan') }}" placeholder="">
            </div>
            <div class="fb-group">
                <label>Kd Tindakan Reff</label>
                <input type="text" name="kd_tindakan_reff" class="fb-input" value="{{ request('kd_tindakan_reff') }}" placeholder="">
            </div>
            <div class="fb-group">
                <label>TKP</label>
                <select name="kd_tkp" class="fb-input fb-select">
                    <option value="">--Pilih--</option>
                    @foreach($tkps as $tkp)
                        <option value="{{ $tkp->kd_tkp }}" {{ request('kd_tkp') == $tkp->kd_tkp ? 'selected' : '' }}>
                            {{ $tkp->nm_tkp }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="fb-group" style="flex-direction:row; align-items:flex-end; gap:5px;">
                <button type="submit" class="btn-search"><i class="fas fa-search me-1"></i> Search</button>
                <a href="/pcare-tindakan/index" class="btn-reset">Reset</a>
                <button type="button" class="btn-update-data" data-bs-toggle="modal" data-bs-target="#modalUpdateBatch">
                    <i class="fas fa-edit"></i> Update Data
                </button>
            </div>
            <div class="fb-group" style="align-items:flex-end; margin-left:auto;">
                <button type="button" class="btn-tarik" id="btnTarikData" onclick="tarikDataTindakan()" style="float:none;">
                    <i class="fas fa-cloud-download-alt"></i> Tarik Data Tindakan Dari PCare
                </button>
            </div>
        </div>
    </form>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>1-{{ $tindakans->count() }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel --}}
    <div class="table-responsive" style="border:none;">
        <table class="table-pcare-grid">
            <thead>
                <tr>
                    <th style="width:36px;">#</th>
                    <th class="col-sort" style="width:120px;">
                        <a href="/pcare-tindakan/index?sort=kd_tkp&direction={{ request('direction')=='asc' ? 'desc' : 'asc' }}">
                            Kd Tkp <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort" style="width:120px;">
                        <a href="/pcare-tindakan/index?sort=kd_tindakan&direction={{ request('direction')=='asc' ? 'desc' : 'asc' }}">
                            Kd Tindakan <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort">
                        <a href="/pcare-tindakan/index?sort=nm_tindakan&direction={{ request('direction')=='asc' ? 'desc' : 'asc' }}">
                            Nm Tindakan <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort" style="width:110px; text-align:right;">
                        <a href="/pcare-tindakan/index?sort=max_tarif&direction={{ request('direction')=='asc' ? 'desc' : 'asc' }}">
                            Max Tarif <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort" style="width:100px; text-align:right;">
                        <a href="/pcare-tindakan/index?sort=with_value&direction={{ request('direction')=='asc' ? 'desc' : 'asc' }}">
                            With Value <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort" style="width:160px;">
                        <a href="/pcare-tindakan/index?sort=kd_tindakan_reff&direction={{ request('direction')=='asc' ? 'desc' : 'asc' }}">
                            Kd Tindakan Reff <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort" style="width:160px;">
                        <a href="/pcare-tindakan/index?sort=updated_at&direction={{ request('direction')=='asc' ? 'desc' : 'asc' }}">
                            Diubah Pada <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th style="width:60px; text-align:center;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($tindakans as $i => $item)
                <tr>
                    <td>{{ ($tindakans->firstItem() ?? 1) + $i }}</td>
                    <td>
                        @if($item->nm_tkp == 'Rawat Jalan')
                            <span class="badge-tkp-jalan">{{ $item->nm_tkp }}</span>
                        @elseif($item->nm_tkp == 'Rawat Inap')
                            <span class="badge-tkp-inap">{{ $item->nm_tkp }}</span>
                        @else
                            <span class="badge-tkp-other">{{ $item->nm_tkp }}</span>
                        @endif
                    </td>
                    <td>{{ $item->kd_tindakan }}</td>
                    <td>{{ $item->nm_tindakan }}</td>
                    <td style="text-align:right;">{{ number_format($item->max_tarif, 0, ',', '') }}</td>
                    <td style="text-align:right;">{{ number_format($item->with_value, 0, ',', '') }}</td>
                    <td>
                        @if(empty($item->kd_tindakan_reff) || $item->kd_tindakan_reff == '--Belum diatur--')
                            <span class="belum-diatur">--Belum diatur--</span>
                        @else
                            {{ $item->kd_tindakan_reff }}
                        @endif
                    </td>
                    <td style="font-size:12px; color:#555;">
                        {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i:s') : '-' }}
                    </td>
                    <td style="text-align:center;">
                        <a href="/pcare-tindakan/{{ $item->id }}/edit" class="action-icon" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-3">
                        <i class="fas fa-info-circle me-1"></i> Data tindakan tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-2">
        {{ $tindakans->links() }}
    </div>
</div>

{{-- MODAL UPDATE BATCH KD TINDAKAN REFF --}}
<div class="modal fade" id="modalUpdateBatch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white py-2 px-3" style="background-color:#f39c12;">
                <h5 class="modal-title fs-6 fw-semibold text-white">
                    <i class="fas fa-edit me-1"></i> Update Referensi Kode Tindakan Lokal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/pcare-tindakan/update-batch" method="POST">
                @csrf
                <div class="modal-body p-3" style="max-height: 65vh; overflow-y: auto;">
                    <p class="small text-muted mb-2">
                        Isi kolom <b>Kd Tindakan Reff</b> dengan kode tindakan lokal klinik yang sesuai dengan data PCare BPJS:
                    </p>
                    <table class="table table-sm table-bordered" style="font-size:12px;">
                        <thead class="table-light">
                            <tr>
                                <th style="width:90px;">Kd Tindakan</th>
                                <th>Nm Tindakan</th>
                                <th style="width:90px;">TKP</th>
                                <th style="width:220px;">Kd Tindakan Reff (Klinik)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tindakans as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->kd_tindakan }}</td>
                                <td>{{ $item->nm_tindakan }}</td>
                                <td>{{ $item->nm_tkp }}</td>
                                <td>
                                    <input type="text" name="items[{{ $item->id }}]"
                                           class="form-control form-control-sm"
                                           value="{{ $item->kd_tindakan_reff == '--Belum diatur--' ? '' : $item->kd_tindakan_reff }}"
                                           placeholder="--Belum diatur--">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" style="background-color:#f39c12; border-color:#e08e0b;">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL HASIL TARIK --}}
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
                        onclick="location.reload()" style="background-color:#00a65a; border-color:#008d4c;">
                    <i class="fas fa-check me-1"></i> Tutup & Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';

function tarikDataTindakan() {
    const btn  = document.getElementById('btnTarikData');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menarik data...';

    $.ajax({
        url: '/pcare-tindakan/tarik',
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
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
