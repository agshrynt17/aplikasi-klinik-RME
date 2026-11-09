@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Lantai - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="data-lantai-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">Data Lantai</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Data Lantai</li>
            </ol>
        </nav>
    </div>

    <!-- Blue Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #00c0ef;"></div>

    {{-- Alert Box --}}
    <div id="alertBox" class="alert d-none py-2 px-3 mb-3" role="alert" style="font-size: 13px;"></div>

    <!-- Filter Bar Card -->
    <div class="card border rounded-2 shadow-none bg-white p-3 mb-3">
        <form action="/master-lantai" method="GET" id="filterForm">
            <div class="d-flex flex-wrap gap-3 align-items-end">
                <div>
                    <label class="form-label small fw-bold text-dark mb-1">Gedung</label>
                    <input type="text" name="gedung" class="form-control form-control-sm" id="filterGedung" 
                           value="{{ request('gedung') }}" style="height: 33px; width: 220px;">
                </div>
                <div>
                    <label class="form-label small fw-bold text-dark mb-1">Nama Lantai</label>
                    <input type="text" name="nama_lantai" class="form-control form-control-sm" id="filterNamaLantai" 
                           value="{{ request('nama_lantai') }}" style="height: 33px; width: 220px;">
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4; height: 33px;">
                        Search
                    </button>
                    <a href="/master-lantai" class="btn btn-sm text-white px-2.5 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px; height: 33px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                    <a href="/master-lantai/create" class="btn btn-sm text-white px-3 fw-semibold d-flex align-items-center" 
                       style="background-color: #5cb85c; border-color: #4cae4c; height: 33px;">
                        Tambah Data
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Total Item Count -->
    <div class="mb-2 text-dark" style="font-size: 13px;">
        @if(count($data) > 0)
            Menampilkan <strong>1-{{ count($data) }}</strong> dari <strong>{{ count($data) }}</strong> item.
        @else
            Menampilkan <strong>0</strong> item.
        @endif
    </div>

    <!-- Data Table Card -->
    <div class="card border rounded-2 shadow-none bg-white p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0 text-nowrap" id="tabelLantai" style="font-size: 13px; border-bottom: 1px solid #e2e8f0;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th width="45" class="text-center" style="color: #333; font-weight: 600;">#</th>
                        <th style="color: #337ab7; font-weight: 600; width: 20%;">Kode Lantai</th>
                        <th style="color: #337ab7; font-weight: 600; width: 35%;">Nama Lantai</th>
                        <th style="color: #337ab7; font-weight: 600; width: 35%;">Gedung</th>
                        <th width="90" class="text-end pe-3" style="color: #337ab7; font-weight: 600;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center text-muted" style="font-size: 12px;">{{ $index + 1 }}</td>
                        <td class="text-dark">{{ $item->kode_lantai }}</td>
                        <td class="text-dark">{{ $item->nama_lantai }}</td>
                        <td class="text-dark">{{ $item->gedung->nama_gedung ?? '-' }}</td>
                        <td class="text-end pe-3">
                            <a href="javascript:void(0)" class="text-decoration-none me-2 btnDetail" data-id="{{ $item->id }}" title="Lihat Detail">
                                <i class="fas fa-eye" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                            <a href="/master-lantai/{{ $item->id }}/edit" class="text-decoration-none me-2" title="Ubah Data">
                                <i class="fas fa-pencil-alt" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="text-decoration-none btnHapus" data-id="{{ $item->id }}" data-nama="{{ $item->nama_lantai }}" title="Hapus">
                                <i class="fas fa-trash" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data lantai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Detail Lantai --}}
<div class="modal fade" id="modalDetailLantai" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #337ab7;">
                <h6 class="modal-title fw-bold" id="modalDetailLabel">
                    <i class="fas fa-info-circle me-1"></i> Detail Data Lantai
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <table class="table table-bordered table-sm mb-0">
                    <tbody>
                        <tr>
                            <th width="35%" class="bg-light">Gedung</th>
                            <td id="detGedung" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Kode Lantai</th>
                            <td id="detKodeLantai" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama Lantai</th>
                            <td id="detNamaLantai"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Keterangan</th>
                            <td id="detKeterangan"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Status</th>
                            <td id="detStatus"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalHapusLantai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> Konfirmasi Hapus
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus lantai <strong id="namaHapusLantai" class="text-danger"></strong>?
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm fw-semibold" id="btnKonfirmasiHapus">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let hapusId = null;

    // View Detail
    $(document).on('click', '.btnDetail', function () {
        let id = $(this).data('id');
        $.get('/master-lantai/' + id, function (res) {
            $('#detGedung').text(res.gedung ? res.gedung.nama_gedung : '-');
            $('#detKodeLantai').text(res.kode_lantai || '-');
            $('#detNamaLantai').text(res.nama_lantai || '-');
            $('#detKeterangan').text(res.keterangan || '-');
            $('#detStatus').html(res.is_active 
                ? '<span class="badge bg-success">Aktif</span>' 
                : '<span class="badge bg-danger">Nonaktif</span>');
            $('#modalDetailLantai').modal('show');
        });
    });

    // Click Delete
    $(document).on('click', '.btnHapus', function () {
        hapusId = $(this).data('id');
        $('#namaHapusLantai').text($(this).data('nama'));
        $('#modalHapusLantai').modal('show');
    });

    // Confirm Delete
    $('#btnKonfirmasiHapus').on('click', function () {
        if (!hapusId) return;
        $.ajax({
            url: '/master-lantai/' + hapusId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#modalHapusLantai').modal('hide');
                showAlert('warning', res.message);
                setTimeout(() => location.reload(), 800);
            },
            error: function () {
                $('#modalHapusLantai').modal('hide');
                showAlert('danger', 'Gagal menghapus data lantai.');
            }
        });
    });

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
            .addClass('alert-' + type).text(msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }
});
</script>
@endsection
