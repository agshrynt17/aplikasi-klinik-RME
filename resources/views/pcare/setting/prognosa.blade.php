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
        <h3 class="konhakes-title">Bridging PCare - Setting Data Prognosa</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-prognosa/index">Bridging PCare · Setting Data Prognosa</a>
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
        <button type="button" class="btn-prof-tarik" id="btnTarikData" onclick="tarikDataPrognosa()">
            <i class="fas fa-cloud-download-alt"></i> Tarik Data Prognosa Dari PCare
        </button>
    </div>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>{{ $prognosa->firstItem() ?? 1 }}-{{ $prognosa->lastItem() ?? count($prognosa) }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel dengan filter baris kedua --}}
    <form method="GET" action="/pcare-prognosa/index" id="formFilterPrognosa">
        <div class="table-responsive" style="border:none;">
            <table class="table-pcare-grid">
                <thead>
                    <tr>
                        <th style="width:36px; text-align:center;">#</th>
                        <th class="col-sort" style="width:180px;">
                            <a href="/pcare-prognosa/index?sort=kd_prognosa&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Kd Prognosa <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                            </a>
                        </th>
                        <th class="col-sort">
                            <a href="/pcare-prognosa/index?sort=nm_prognosa&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Nm Prognosa <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                            </a>
                        </th>
                        <th class="col-sort" style="width:240px;">
                            <a href="/pcare-prognosa/index?sort=kd_prognosa_reff&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Kd Prognosa Reff <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                            </a>
                        </th>
                        <th class="col-sort" style="width:200px;">
                            <a href="/pcare-prognosa/index?sort=updated_at&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                Diubah Pada <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                            </a>
                        </th>
                        <th style="width:90px; text-align:center;"></th>
                    </tr>
                    {{-- Baris Filter Input --}}
                    <tr>
                        <td></td>
                        <td>
                            <input type="text" name="kd_prognosa" class="filter-input" value="{{ request('kd_prognosa') }}" onchange="document.getElementById('formFilterPrognosa').submit()">
                        </td>
                        <td>
                            <input type="text" name="nm_prognosa" class="filter-input" value="{{ request('nm_prognosa') }}" onchange="document.getElementById('formFilterPrognosa').submit()">
                        </td>
                        <td>
                            <input type="text" name="kd_prognosa_reff" class="filter-input" value="{{ request('kd_prognosa_reff') }}" onchange="document.getElementById('formFilterPrognosa').submit()">
                        </td>
                        <td>
                            <input type="text" name="diubah_pada" class="filter-input" value="{{ request('diubah_pada') }}" placeholder="" onchange="document.getElementById('formFilterPrognosa').submit()">
                        </td>
                        <td style="text-align:center;"></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prognosa as $index => $item)
                    <tr>
                        <td style="color:#777; text-align:center;">{{ $prognosa->firstItem() + $index }}</td>
                        <td>{{ $item->kd_prognosa }}</td>
                        <td>{{ $item->nm_prognosa }}</td>
                        <td>
                            @if($item->kd_prognosa_reff && $item->kd_prognosa_reff !== '(belum diset)')
                                {{ $item->kd_prognosa_reff }}
                            @else
                                <span class="belum-diset">(belum diset)</span>
                            @endif
                        </td>
                        <td style="color:#555; font-size:12px;">
                            {{ $item->updated_at ? $item->updated_at->format('d-m-Y H:i:s') : '07-08-2024 15:10:21' }}
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="javascript:void(0)" onclick="lihatDetail({{ json_encode($item) }})" class="action-icon" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/pcare-prognosa/{{ $item->id }}/edit" class="action-icon" title="Ubah">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->nm_prognosa) }}')" class="action-icon trash" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-1"></i> Data prognosa tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    {{-- Pagination --}}
    @if($prognosa->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="small text-muted">
            Halaman {{ $prognosa->currentPage() }} dari {{ $prognosa->lastPage() }}
        </div>
        <div>
            {{ $prognosa->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetailPrognosa" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:4px;">
            <div class="modal-header bg-primary text-white py-2 px-3">
                <h6 class="modal-title mb-0"><i class="fas fa-info-circle me-2"></i> Detail Data Prognosa</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <table class="table table-sm table-borderless mb-0" style="font-size:13px;">
                    <tr><th style="width:140px;">Kd Prognosa</th><td>: <span id="detailKdPrognosa"></span></td></tr>
                    <tr><th>Nm Prognosa</th><td>: <span id="detailNmPrognosa"></span></td></tr>
                    <tr><th>Kd Prognosa Reff</th><td>: <span id="detailKdReff"></span></td></tr>
                    <tr><th>Diubah Pada</th><td>: <span id="detailDiubahPada"></span></td></tr>
                </table>
            </div>
            <div class="modal-footer py-1 px-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Update Batch --}}
<div class="modal fade" id="modalUpdateBatch" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:4px;">
            <div class="modal-header" style="background:#f39c12; color:#fff; padding:10px 15px;">
                <h6 class="modal-title mb-0"><i class="fas fa-edit me-2"></i> Update Batch Data Prognosa Reff</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/pcare-prognosa/update-batch">
                @csrf
                <div class="modal-body p-3" style="max-height:60vh; overflow-y:auto;">
                    <p class="text-muted small mb-3">Sesuaikan kode referensi lokal klinik untuk setiap prognosa PCare di bawah ini:</p>
                    <table class="table table-bordered table-sm" style="font-size:12px;">
                        <thead class="bg-light">
                            <tr>
                                <th style="width:40px; text-align:center;">#</th>
                                <th style="width:100px;">Kd Prognosa</th>
                                <th>Nm Prognosa</th>
                                <th style="width:240px;">Kd Prognosa Reff</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prognosa as $i => $item)
                            <tr>
                                <td style="text-align:center;">{{ $i + 1 }}</td>
                                <td>{{ $item->kd_prognosa }}</td>
                                <td>{{ $item->nm_prognosa }}</td>
                                <td>
                                    <input type="text" name="rows[{{ $item->id }}]" class="form-control form-control-sm"
                                           value="{{ $item->kd_prognosa_reff && $item->kd_prognosa_reff !== '(belum diset)' ? $item->kd_prognosa_reff : '' }}"
                                           placeholder="--Masukkan Reff--">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer py-2 px-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white btn-sm" style="background:#f39c12; border-color:#e08e0b;">
                        <i class="fas fa-save me-1"></i> Simpan Semua Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Form Delete (hidden) --}}
<form id="formDeletePrognosa" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
function lihatDetail(item) {
    document.getElementById('detailKdPrognosa').textContent = item.kd_prognosa || '-';
    document.getElementById('detailNmPrognosa').textContent = item.nm_prognosa || '-';
    document.getElementById('detailKdReff').textContent = item.kd_prognosa_reff || '(belum diset)';
    document.getElementById('detailDiubahPada').textContent = item.updated_at ? new Date(item.updated_at).toLocaleString('id-ID') : '07-08-2024 15:10:21';
    new bootstrap.Modal(document.getElementById('modalDetailPrognosa')).show();
}

function confirmDelete(id, name) {
    if (confirm('Apakah Anda yakin ingin menghapus data prognosa "' + name + '"?')) {
        var form = document.getElementById('formDeletePrognosa');
        form.action = '/pcare-prognosa/' + id;
        form.submit();
    }
}

function tarikDataPrognosa() {
    var btn = document.getElementById('btnTarikData');
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menarik Data...';

    fetch('/pcare-prognosa/tarik', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        if (data.success) {
            alert('Berhasil! ' + (data.message || 'Data Prognosa berhasil disinkronkan dari server PCare.'));
            window.location.reload();
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan saat menarik data.'));
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        alert('Gagal menghubungkan ke server PCare atau server lokal.');
    });
}
</script>
@endsection
