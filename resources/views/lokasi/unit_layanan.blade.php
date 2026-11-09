@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Unit - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="data-unit-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">Data Unit</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Data Unit</li>
            </ol>
        </nav>
    </div>

    <!-- Blue Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #00c0ef;"></div>

    {{-- Alert Box --}}
    <div id="alertBox" class="alert d-none py-2 px-3 mb-3" role="alert" style="font-size: 13px;"></div>

    <!-- Filter Bar Card -->
    <div class="card border rounded-2 shadow-none bg-white p-3 mb-3">
        <form action="/master-unit-layanan" method="GET" id="filterForm">
            <div class="mb-1">
                <label class="form-label small fw-bold text-dark mb-1">Nama Unit</label>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div style="flex: 1; max-width: 480px; min-width: 220px;">
                    <input type="text" name="nama_unit" class="form-control form-control-sm" id="filterNamaUnit" 
                           value="{{ request('nama_unit') }}" placeholder="" style="height: 33px;">
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4; height: 33px;">
                        Search
                    </button>
                    <a href="/master-unit-layanan" class="btn btn-sm text-white px-2.5 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px; height: 33px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                    <a href="/master-unit-layanan/create" class="btn btn-sm text-white px-3 fw-semibold d-flex align-items-center" 
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
            <table class="table table-hover table-striped align-middle mb-0 text-nowrap" id="tabelUnit" style="font-size: 13px; border-bottom: 1px solid #e2e8f0;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th width="45" class="text-center" style="color: #333; font-weight: 600;">#</th>
                        <th style="color: #337ab7; font-weight: 600; width: 25%;">Kode Unit</th>
                        <th style="color: #337ab7; font-weight: 600; width: 60%;">Nama Unit</th>
                        <th width="90" class="text-end pe-3" style="color: #337ab7; font-weight: 600;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center text-muted" style="font-size: 12px;">{{ $index + 1 }}</td>
                        <td class="text-dark">{{ $item->kode_unit }}</td>
                        <td class="text-dark">{{ $item->nama_unit }}</td>
                        <td class="text-end pe-3">
                            <a href="javascript:void(0)" class="text-decoration-none me-2 btnDetail" data-id="{{ $item->id }}" title="Lihat Detail">
                                <i class="fas fa-eye" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                            <a href="/master-unit-layanan/{{ $item->id }}/edit" class="text-decoration-none me-2" title="Ubah Data">
                                <i class="fas fa-pencil-alt" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="text-decoration-none btnHapus" data-id="{{ $item->id }}" data-nama="{{ $item->nama_unit }}" title="Hapus">
                                <i class="fas fa-trash" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada data unit.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah / Ubah Unit --}}
<div class="modal fade" id="modalUnit" tabindex="-1" aria-labelledby="modalUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #00a65a;">
                <h6 class="modal-title fw-bold" id="modalUnitLabel">
                    <i class="fas fa-layer-group me-1"></i> Tambah Data Unit
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUnit">
                @csrf
                <input type="hidden" id="unitId" name="id">
                <div class="modal-body p-3" style="font-size: 13px;">
                    <div class="mb-3">
                        <label for="kode_unit" class="form-label fw-bold mb-1">Kode Unit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="kode_unit" name="kode_unit" 
                               placeholder="Contoh: 01, 02, 04" maxlength="20" required>
                        <div class="invalid-feedback" id="err_kode_unit"></div>
                    </div>
                    <div class="mb-3">
                        <label for="nama_unit" class="form-label fw-bold mb-1">Nama Unit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="nama_unit" name="nama_unit" 
                               placeholder="Contoh: Umum, Penunjang, Pelayanan" maxlength="255" required>
                        <div class="invalid-feedback" id="err_nama_unit"></div>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-bold mb-1">Keterangan</label>
                        <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="2" 
                                  placeholder="Keterangan opsional..."></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Status Aktif</label>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white fw-semibold" id="btnSimpanUnit" style="background-color: #337ab7; border-color: #2e6da4;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Detail Unit --}}
<div class="modal fade" id="modalDetailUnit" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #337ab7;">
                <h6 class="modal-title fw-bold" id="modalDetailLabel">
                    <i class="fas fa-info-circle me-1"></i> Detail Data Unit
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <table class="table table-bordered table-sm mb-0">
                    <tbody>
                        <tr>
                            <th width="35%" class="bg-light">Kode Unit</th>
                            <td id="detKodeUnit" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama Unit</th>
                            <td id="detNamaUnit"></td>
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
<div class="modal fade" id="modalHapusUnit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> Konfirmasi Hapus
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus unit <strong id="namaHapusUnit" class="text-danger"></strong>?
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

    // Reset Modal Form on Tambah
    $('#btnTambahUnit').on('click', function () {
        $('#modalUnitLabel').html('<i class="fas fa-layer-group me-1"></i> Tambah Data Unit');
        $('#formUnit')[0].reset();
        $('#unitId').val('');
        $('#is_active').prop('checked', true);
        clearErrors();
    });

    // View Detail
    $(document).on('click', '.btnDetail', function () {
        let id = $(this).data('id');
        $.get('/master-unit-layanan/' + id, function (res) {
            $('#detKodeUnit').text(res.kode_unit || '-');
            $('#detNamaUnit').text(res.nama_unit || '-');
            $('#detKeterangan').text(res.keterangan || '-');
            $('#detStatus').html(res.is_active 
                ? '<span class="badge bg-success">Aktif</span>' 
                : '<span class="badge bg-danger">Nonaktif</span>');
            $('#modalDetailUnit').modal('show');
        });
    });

    // Click Edit
    $(document).on('click', '.btnEdit', function () {
        let id = $(this).data('id');
        $('#modalUnitLabel').html('<i class="fas fa-pencil-alt me-1"></i> Ubah Data Unit');
        clearErrors();
        $.get('/master-unit-layanan/' + id + '/edit', function (res) {
            $('#unitId').val(res.id);
            $('#kode_unit').val(res.kode_unit);
            $('#nama_unit').val(res.nama_unit);
            $('#keterangan').val(res.keterangan);
            $('#is_active').prop('checked', res.is_active == 1);
        });
    });

    // Submit Form (Tambah / Edit)
    $('#formUnit').on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        let id = $('#unitId').val();
        let url = id ? '/master-unit-layanan/' + id : '/master-unit-layanan';
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function (res) {
                $('#modalUnit').modal('hide');
                showAlert('success', res.message);
                setTimeout(() => location.reload(), 800);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $('#' + field).addClass('is-invalid');
                        $('#err_' + field).text(messages[0]);
                    });
                } else {
                    showAlert('danger', 'Terjadi kesalahan sistem saat menyimpan data.');
                }
            }
        });
    });

    // Click Delete
    $(document).on('click', '.btnHapus', function () {
        hapusId = $(this).data('id');
        $('#namaHapusUnit').text($(this).data('nama'));
        $('#modalHapusUnit').modal('show');
    });

    // Confirm Delete
    $('#btnKonfirmasiHapus').on('click', function () {
        if (!hapusId) return;
        $.ajax({
            url: '/master-unit-layanan/' + hapusId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#modalHapusUnit').modal('hide');
                showAlert('warning', res.message);
                setTimeout(() => location.reload(), 800);
            },
            error: function () {
                $('#modalHapusUnit').modal('hide');
                showAlert('danger', 'Gagal menghapus data unit.');
            }
        });
    });

    function clearErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
            .addClass('alert-' + type).text(msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }
});
</script>
@endsection
