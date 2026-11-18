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
        font-size: 13px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 3px;
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
    }
    .btn-prof-reset:hover {
        background-color: #e6e6e6;
        color: #333;
    }
    .btn-prof-green {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 3px;
    }
    .btn-prof-green:hover {
        background-color: #008d4c;
        color: #fff;
    }
    .table-banner-blue {
        background-color: #00c0ef;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 14px;
        border-radius: 3px 3px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table-batch-header th {
        background-color: #9aa0a6 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 12px;
        border: 1px solid #8c9298 !important;
        padding: 8px 10px !important;
        white-space: nowrap;
    }
    .table-batch-data td {
        font-size: 12.5px;
        vertical-align: middle;
        padding: 7px 10px;
        border: 1px solid #e3e6e8;
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
        <h3>Daftar No.Batch Barang</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <span>Daftar No.Batch Barang</span>
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

    <div id="ajaxAlert" class="alert d-none py-2 px-3 small mb-3"></div>

    <!-- Top Action Buttons -->
    <div class="d-flex align-items-center gap-2 mb-3">
        <button type="button" class="btn btn-prof-orange" id="btnToggleSearch">
            <i class="fas fa-search me-1"></i> Cari data
        </button>
        <a href="/batch/index" class="btn btn-prof-reset" title="Reset / Refresh">
            <i class="fas fa-sync-alt"></i>
        </a>
        <a href="/batch/create" class="btn btn-prof-green">
            <i class="fas fa-plus me-1"></i> Tambah Data
        </a>
    </div>

    <!-- Collapsible Search Filter Box -->
    <div class="search-collapse-box {{ request('q') || request('status') || request('gudang_id') ? '' : 'd-none' }}" id="searchFilterBox">
        <form action="/batch/index" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm fw-semibold mb-1" style="font-size: 12px;">Pilih Gudang</label>
                <select name="gudang_id" class="form-select form-select-sm">
                    <option value="">-- Semua Gudang --</option>
                    @foreach($gudangs as $g)
                        <option value="{{ $g->id }}" {{ request('gudang_id', $selectedGudang->id ?? '') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama_gudang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label form-label-sm fw-semibold mb-1" style="font-size: 12px;">Cari Kode / Nama / No.Batch</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Ketik kata kunci pencarian..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm fw-semibold mb-1" style="font-size: 12px;">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-prof-orange w-100">
                    <i class="fas fa-filter me-1"></i> Terapkan
                </button>
                <a href="/batch/index" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 4px; overflow: hidden;">
        <!-- Blue Banner -->
        <div class="table-banner-blue">
            <span>
                Daftar Stok per Batch pada Gudang : {{ $selectedGudang->nama_gudang ?? 'Gudang Farmasi' }}
            </span>
            <span class="badge bg-white text-dark small" style="font-size: 11px; font-weight: normal;">
                Total: {{ $data->total() }} Item
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 table-batch-data">
                <thead class="table-batch-header">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="14%">Kode Barang</th>
                        <th width="30%">Nama Barang</th>
                        <th width="16%">No.Batch</th>
                        <th width="12%" class="text-center">Tgl.Exp</th>
                        <th width="14%" class="text-center">Last.Updated</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td class="text-center text-muted">{{ $data->firstItem() + $index }}</td>
                        <td>
                            <span class="text-primary fw-semibold">{{ $item->masterBarang->kode_barang ?? '-' }}</span>
                        </td>
                        <td class="fw-bold text-dark">
                            {{ $item->masterBarang->nama_barang ?? '-' }}
                        </td>
                        <td>
                            <span class="fw-semibold text-secondary">{{ $item->nomor_batch }}</span>
                        </td>
                        <td class="text-center">
                            @if($item->tanggal_kadaluarsa)
                                @php
                                    $exp = \Carbon\Carbon::parse($item->tanggal_kadaluarsa);
                                    $isExpired = $exp->isPast();
                                @endphp
                                @if($isExpired)
                                    <span class="text-danger fw-bold" title="Kadaluarsa!">
                                        <i class="fas fa-exclamation-circle"></i> {{ $exp->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span>{{ $exp->format('d/m/Y') }}</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center text-muted" style="font-size: 12px;">
                            {{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($item->is_active)
                                <span class="badge bg-success" style="font-size: 11px; font-weight: 500; padding: 4px 8px;">Aktif</span>
                            @else
                                <span class="badge bg-secondary" style="font-size: 11px; font-weight: 500; padding: 4px 8px;">Non Aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/batch/{{ $item->id }}/edit" class="btn btn-sm btn-outline-primary py-0 px-2" title="Edit">
                                <i class="fas fa-edit" style="font-size: 11px;"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btnDeleteBatch" data-id="{{ $item->id }}" data-batch="{{ $item->nomor_batch }}" title="Hapus">
                                <i class="fas fa-trash" style="font-size: 11px;"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-boxes fa-3x mb-2 text-secondary opacity-50"></i>
                            <div class="fw-semibold">Tidak ada data batch barang yang tersedia.</div>
                            <small class="text-muted">Klik tombol "+ Tambah Data" di atas untuk menambahkan batch baru.</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="card-footer bg-white border-top py-2 px-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} batch
            </span>
            <div>
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapusBatch" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title mb-0"><i class="fas fa-trash me-2"></i>Konfirmasi Hapus</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3">
                <p class="mb-1">Yakin ingin menghapus Batch:</p>
                <div class="fw-bold text-danger fs-6" id="delBatchName">-</div>
                <small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>
            </div>
            <div class="modal-footer bg-light py-2 justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm px-3" id="btnConfirmDelete">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Toggle Search Box
    $('#btnToggleSearch').on('click', function() {
        $('#searchFilterBox').toggleClass('d-none');
    });

    let deleteId = null;
    $('.btnDeleteBatch').on('click', function() {
        deleteId = $(this).data('id');
        let batchName = $(this).data('batch');
        $('#delBatchName').text(batchName);
        $('#modalHapusBatch').modal('show');
    });

    $('#btnConfirmDelete').on('click', function() {
        if (!deleteId) return;
        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '/batch/' + deleteId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                $('#modalHapusBatch').modal('hide');
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Hapus');
                location.reload();
            },
            error: function(xhr) {
                $('#modalHapusBatch').modal('hide');
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Hapus');
                alert('Gagal menghapus data batch.');
            }
        });
    });
});
</script>
@endsection
