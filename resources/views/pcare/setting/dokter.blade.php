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
        color: #444;
        text-decoration: none;
    }
    .konhakes-breadcrumb a:hover { text-decoration: underline; }
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
    .belum-diatur {
        color: #dd4b39;
        font-style: italic;
    }
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
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging PCare - Setting Data Dokter</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <span style="color:#777;">Bridging PCare - Setting Data Dokter</span>
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
        <button type="button" class="btn-prof-tarik" id="btnTarikData" onclick="tarikDataDokter()">
            <i class="fas fa-download"></i> Tarik Data Dokter Dari PCare
        </button>
    </div>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>1-{{ $total }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel --}}
    <div class="table-responsive" style="border: none;">
        <table class="table-pcare-grid">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th class="col-sort" style="width:150px;">
                        <a href="javascript:void(0)">Kode Dokter (Pcare) <i class="fas fa-sort ms-1" style="font-size:11px;"></i></a>
                    </th>
                    <th class="col-sort">
                        <a href="javascript:void(0)">Nama Dokter (Pcare)</a>
                    </th>
                    <th class="col-sort" style="width:250px;">
                        <a href="javascript:void(0)">Dokter (Aplikasi)</a>
                    </th>
                    <th class="col-sort" style="width:180px;">
                        <a href="javascript:void(0)">Tanggal Update</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokters as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $d->kode_dpjp_bpjs ?? '-' }}</td>
                    <td>{{ $d->nama_pcare ?? $d->nama_pegawai }}</td>
                    <td>
                        @if(!empty($d->nama_pegawai) && $d->nama_pegawai != '-' && $d->nama_pegawai != '--Belum diatur--')
                            {{ $d->nama_pegawai }}
                        @else
                            <span class="belum-diatur">--Belum diatur--</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:#666;">
                        {{ $d->pcare_updated_at ? \Carbon\Carbon::parse($d->pcare_updated_at)->format('d-m-Y H:i:s') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Belum ada data dokter PCare. Klik <strong>"Tarik Data Dokter Dari PCare"</strong>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL HASIL --}}
<div class="modal fade" id="modalHasil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white py-2 px-3" id="modalHasilHeader" style="background-color:#00a65a;">
                <h5 class="modal-title fs-6 fw-semibold text-white" id="modalHasilTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <p id="modalHasilBody" class="mb-0 small"></p>
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

function tarikDataDokter() {
    const btn  = document.getElementById('btnTarikData');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menarik data...';

    $.ajax({
        url: '/pcare/setting/dokter/tarik',
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
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memperbarui...';
    
    setTimeout(function() {
        location.reload();
    }, 600);
}
</script>
@endsection
