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
        box-sizing: border-box;
        outline: none;
    }
    .filter-input:focus { border-color: #3c8dbc; }
    .belum-diset {
        color: #dd4b39;
        font-style: italic;
    }
    .action-icon {
        color: #3c8dbc;
        margin: 0 4px;
        font-size: 13px;
        text-decoration: none;
    }
    .action-icon:hover { color: #1d6fa5; }
    .action-icon.trash { color: #dd4b39; }
    .action-icon.trash:hover { color: #a92c1d; }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging PCare - Setting Data Khusus</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-khusus/index">Bridging PCare - Setting Data Khusus</a>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2 shadow-sm" style="border-radius:3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small my-2 shadow-sm" style="border-radius:3px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tombol Aksi --}}
    <div style="overflow: hidden; margin-bottom: 6px;">
        <button type="button" class="btn-prof-update" id="btnUpdateData" data-bs-toggle="modal" data-bs-target="#modalUpdateBatch">
            <i class="fas fa-edit"></i> Update Data
        </button>
        <button type="button" class="btn-prof-tarik" id="btnTarikData" onclick="tarikDataKhusus()">
            <i class="fas fa-cloud-download-alt"></i> Tarik Data Khusus Dari PCare
        </button>
    </div>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>{{ $khusus->firstItem() ?? 1 }}-{{ $khusus->lastItem() ?? count($khusus) }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel dengan filter baris kedua --}}
    <div class="table-responsive" style="border: none;">
        <form method="GET" action="/pcare-khusus/index" id="formFilterKhusus">
            <table class="table-pcare-grid">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th class="col-sort" style="width:160px;">
                            <a href="/pcare-khusus/index?sort=kd_khusus&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Kd Khusus <i class="fas fa-sort ms-1" style="font-size:11px;"></i>
                            </a>
                        </th>
                        <th class="col-sort">
                            <a href="/pcare-khusus/index?sort=nm_khusus&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Nm Khusus <i class="fas fa-sort ms-1" style="font-size:11px;"></i>
                            </a>
                        </th>
                        <th class="col-sort" style="width:200px;">
                            <a href="/pcare-khusus/index?sort=kd_khusus_reff&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Kd Khusus Reff <i class="fas fa-sort ms-1" style="font-size:11px;"></i>
                            </a>
                        </th>
                        <th class="col-sort" style="width:200px;">
                            <a href="/pcare-khusus/index?sort=updated_at&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Diubah Pada <i class="fas fa-sort ms-1" style="font-size:11px;"></i>
                            </a>
                        </th>
                        <th style="width:100px; text-align:center;"></th>
                    </tr>
                    <tr style="background:#fff;">
                        <td></td>
                        <td>
                            <input type="text" name="kd_khusus" value="{{ request('kd_khusus') }}" class="filter-input" onchange="document.getElementById('formFilterKhusus').submit()">
                        </td>
                        <td>
                            <input type="text" name="nm_khusus" value="{{ request('nm_khusus') }}" class="filter-input" onchange="document.getElementById('formFilterKhusus').submit()">
                        </td>
                        <td>
                            <input type="text" name="kd_khusus_reff" value="{{ request('kd_khusus_reff') }}" class="filter-input" onchange="document.getElementById('formFilterKhusus').submit()">
                        </td>
                        <td>
                            <input type="text" class="filter-input" disabled style="background:#fcfcfc;">
                        </td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($khusus as $i => $item)
                    <tr>
                        <td>{{ ($khusus->firstItem() ?? 1) + $i }}</td>
                        <td>{{ $item->kd_khusus }}</td>
                        <td>{{ $item->nm_khusus }}</td>
                        <td>
                            @if(empty($item->kd_khusus_reff) || $item->kd_khusus_reff == '(belum diset)')
                                <span class="belum-diset">(belum diset)</span>
                            @else
                                {{ $item->kd_khusus_reff }}
                            @endif
                        </td>
                        <td style="font-size:12px; color:#555;">
                            {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i:s') : '-' }}
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="/pcare-khusus/{{ $item->id }}" class="action-icon" title="Lihat">
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="/pcare-khusus/{{ $item->id }}/edit" class="action-icon" title="Ubah">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="/pcare-khusus/{{ $item->id }}/delete" class="action-icon trash" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data khusus ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-1"></i> Data khusus tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-2">
        {{ $khusus->links() }}
    </div>
</div>

{{-- MODAL UPDATE BATCH DATA KHUSUS --}}
<div class="modal fade" id="modalUpdateBatch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white py-2 px-3" style="background-color:#f39c12;">
                <h5 class="modal-title fs-6 fw-semibold text-white">
                    <i class="fas fa-edit me-1"></i> Update Referensi Data Khusus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/pcare-khusus/update-batch" method="POST">
                @csrf
                <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">
                    <p class="small text-muted mb-3">
                        Silakan atur kode referensi lokal klinik untuk masing-masing data khusus PCare berikut:
                    </p>
                    <table class="table table-sm table-bordered" style="font-size:12.5px;">
                        <thead class="table-light">
                            <tr>
                                <th style="width:100px;">Kd Khusus</th>
                                <th>Nm Khusus</th>
                                <th style="width:250px;">Kd Khusus Reff (Klinik)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($khusus as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->kd_khusus }}</td>
                                <td>{{ $item->nm_khusus }}</td>
                                <td>
                                    <input type="text" name="items[{{ $item->id }}]" class="form-control form-control-sm"
                                           value="{{ $item->kd_khusus_reff == '(belum diset)' ? '' : $item->kd_khusus_reff }}"
                                           placeholder="(belum diset)">
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

function tarikDataKhusus() {
    const btn  = document.getElementById('btnTarikData');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menarik data...';

    $.ajax({
        url: '/pcare-khusus/tarik',
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
