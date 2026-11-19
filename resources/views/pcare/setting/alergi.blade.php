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
    .action-icon.trash { color: #dd4b39; }
    .action-icon.trash:hover { color: #c23321; }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging PCare - Setting Data Alergi</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>&nbsp;»&nbsp;
            <a href="/pcare-alergi/index">Bridging PCare · Setting Data Alergi</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2 shadow-sm" style="border-radius:3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter Bar --}}
    <form method="GET" action="/pcare-alergi/index" id="formFilter">
        <div class="filter-bar">
            <div class="fb-group">
                <label>Kd Jenis</label>
                <select name="kd_jenis" class="fb-input fb-select">
                    <option value="">--Pilih--</option>
                    @foreach($jenis_list as $j)
                        <option value="{{ $j->kd_jenis }}" {{ request('kd_jenis') == $j->kd_jenis ? 'selected' : '' }}>
                            {{ $j->nm_jenis }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="fb-group" style="flex-direction:row; align-items:flex-end; gap:5px;">
                <button type="submit" class="btn-search"><i class="fas fa-search me-1"></i> Search</button>
                <a href="/pcare-alergi/index" class="btn-reset">Reset</a>
                <button type="button" class="btn-update-data" data-bs-toggle="modal" data-bs-target="#modalUpdateBatch">
                    <i class="fas fa-edit"></i> Update Data
                </button>
            </div>
            <div class="fb-group" style="align-items:flex-end; margin-left:auto;">
                <button type="button" class="btn-tarik" id="btnTarikData" onclick="tarikDataAlergi()" style="float:none;">
                    <i class="fas fa-cloud-download-alt"></i> Tarik Data Alergi Dari PCare
                </button>
            </div>
        </div>
    </form>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>1-{{ $alergis->count() }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel --}}
    <div class="table-responsive" style="border:none;">
        <table class="table-pcare-grid">
            <thead>
                <tr>
                    <th style="width:36px;">#</th>
                    <th style="width:140px;">Jenis</th>
                    <th style="width:100px;">Kd Alergi</th>
                    <th>Nm Alergi</th>
                    <th style="width:200px;">Kd Alergi Reff</th>
                    <th style="width:170px;">Diubah Pada</th>
                    <th style="width:90px; text-align:center;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($alergis as $index => $item)
                <tr>
                    <td style="color:#777;">{{ $alergis->firstItem() + $index }}</td>
                    <td>{{ $item->nm_jenis }}</td>
                    <td>{{ $item->kd_alergi }}</td>
                    <td>{{ $item->nm_alergi }}</td>
                    <td>
                        @if($item->kd_alergi_reff && $item->kd_alergi_reff !== '(belum diset)')
                            {{ $item->kd_alergi_reff }}
                        @else
                            <span class="belum-diatur">(belum diset)</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:#555;">
                        {{ $item->updated_at ? $item->updated_at->format('d-m-Y H:i:s') : '08-08-2024 14:49:15' }}
                    </td>
                    <td style="text-align:center; white-space:nowrap;">
                        <a href="javascript:void(0)" onclick="lihatDetail({{ json_encode($item) }})" class="action-icon" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/pcare-alergi/{{ $item->id }}/edit" class="action-icon" title="Edit Reff">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->nm_alergi) }}')" class="action-icon trash" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle me-1"></i> Tidak ada data alergi yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($alergis->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="small text-muted">
            Halaman {{ $alergis->currentPage() }} dari {{ $alergis->lastPage() }}
        </div>
        <div>
            {{ $alergis->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetailAlergi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:4px;">
            <div class="modal-header bg-primary text-white py-2 px-3">
                <h6 class="modal-title mb-0"><i class="fas fa-info-circle me-2"></i> Detail Data Alergi</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <table class="table table-sm table-borderless mb-0" style="font-size:13px;">
                    <tr><th style="width:130px;">Jenis</th><td>: <span id="detailJenis"></span></td></tr>
                    <tr><th>Kd Alergi</th><td>: <span id="detailKdAlergi"></span></td></tr>
                    <tr><th>Nm Alergi</th><td>: <span id="detailNmAlergi"></span></td></tr>
                    <tr><th>Kd Alergi Reff</th><td>: <span id="detailKdReff"></span></td></tr>
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
                <h6 class="modal-title mb-0"><i class="fas fa-edit me-2"></i> Update Batch Data Alergi Reff</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/pcare-alergi/update-batch">
                @csrf
                <div class="modal-body p-3" style="max-height:60vh; overflow-y:auto;">
                    <p class="text-muted small mb-3">Sesuaikan kode referensi lokal untuk setiap alergi PCare di bawah ini:</p>
                    <table class="table table-bordered table-sm" style="font-size:12px;">
                        <thead class="bg-light">
                            <tr>
                                <th style="width:40px;">#</th>
                                <th style="width:130px;">Jenis</th>
                                <th style="width:80px;">Kd Alergi</th>
                                <th>Nm Alergi</th>
                                <th style="width:220px;">Kd Alergi Reff</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alergis as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->nm_jenis }}</td>
                                <td>{{ $item->kd_alergi }}</td>
                                <td>{{ $item->nm_alergi }}</td>
                                <td>
                                    <input type="text" name="rows[{{ $item->id }}]" class="form-control form-control-sm"
                                           value="{{ $item->kd_alergi_reff !== '(belum diset)' ? $item->kd_alergi_reff : '' }}"
                                           placeholder="Masukkan Reff">
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
<form id="formDeleteAlergi" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
function lihatDetail(item) {
    document.getElementById('detailJenis').textContent = item.nm_jenis || '-';
    document.getElementById('detailKdAlergi').textContent = item.kd_alergi || '-';
    document.getElementById('detailNmAlergi').textContent = item.nm_alergi || '-';
    document.getElementById('detailKdReff').textContent = item.kd_alergi_reff || '(belum diset)';
    document.getElementById('detailDiubahPada').textContent = item.updated_at ? new Date(item.updated_at).toLocaleString('id-ID') : '08-08-2024 14:49:15';
    new bootstrap.Modal(document.getElementById('modalDetailAlergi')).show();
}

function confirmDelete(id, name) {
    if (confirm('Apakah Anda yakin ingin menghapus data alergi "' + name + '"?')) {
        var form = document.getElementById('formDeleteAlergi');
        form.action = '/pcare-alergi/' + id;
        form.submit();
    }
}

function tarikDataAlergi() {
    var btn = document.getElementById('btnTarikData');
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menarik Data...';

    fetch('/pcare-alergi/tarik', {
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
            alert('Berhasil! ' + (data.message || 'Data Alergi berhasil disinkronkan dari server PCare.'));
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
