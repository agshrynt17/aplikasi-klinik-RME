@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Master Diagnosa Keperawatan')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Master Diagnosa Keperawatan</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Master Diagnosa Keperawatan</span>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-end flex-wrap gap-2 mb-3">
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Nama Diagnosa</label>
            <input type="text" name="nama_diagnosa" value="{{ request('nama_diagnosa') }}" class="form-control form-control-sm" placeholder="" style="width: 260px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
        </div>
        <button type="submit" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500;">
            Search
        </button>
        <a href="{{ url()->current() }}" class="btn btn-sm text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center;">
            Reset
        </a>
        <button type="button" class="btn btn-sm text-white" id="btnTambahData" data-bs-toggle="modal" data-bs-target="#modalDiagnosaKep" style="background-color: #00a65a; border-color: #008d4c; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; cursor: pointer;">
            Tambah Data
        </button>
    </form>

    <!-- Item Count Indicator -->
    @php
        $from = $data->firstItem() ?? 0;
        $to = $data->lastItem() ?? 0;
    @endphp
    <div style="font-size: 12px; color: #333; margin-bottom: 8px;">
        Menampilkan <strong>{{ $from }}-{{ $to }}</strong> dari <strong>{{ $totalCount }}</strong> item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="tableDiagnosaKep" style="font-size: 12px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background-color: #eef5f9; color: #337ab7; border-bottom: 1px solid #d2d6de;">
                    <th style="font-weight: 600; width: 35px; color: #333; padding: 9px 8px; text-align: center; border-right: 1px solid #e0e0e0;">#</th>
                    <th style="font-weight: 600; width: 120px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kode_diagnosa', 'dir' => request('sort') == 'kode_diagnosa' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kode Diagnosa
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 220px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_diagnosa', 'dir' => request('sort') == 'nama_diagnosa' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Nama Diagnosa
                        </a>
                    </th>
                    <th style="font-weight: 600; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'keterangan', 'dir' => request('sort') == 'keterangan' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Keterangan
                        </a>
                    </th>
                    <th style="width: 35px; padding: 9px 8px; text-align: center;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="color: #333; padding: 9px 8px; text-align: center; vertical-align: middle; border-right: 1px solid #f0f0f0;">{{ $from + $index }}</td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; font-weight: 500; border-right: 1px solid #f0f0f0;">
                        {{ $item->kode_diagnosa }}
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        {{ $item->nama_diagnosa }}
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; line-height: 1.4; border-right: 1px solid #f0f0f0;">
                        {{ $item->keterangan }}
                    </td>
                    <td style="padding: 9px 4px; text-align: center; vertical-align: middle;">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <a href="javascript:void(0)" class="btn-view" data-id="{{ $item->id }}" title="Lihat Detail" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-eye" style="font-size: 11px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn-edit" data-id="{{ $item->id }}" title="Edit Diagnosa" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-pencil-alt" style="font-size: 11px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn-delete" data-id="{{ $item->id }}" data-kode="{{ $item->kode_diagnosa }}" data-nama="{{ $item->nama_diagnosa }}" title="Hapus Diagnosa" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-trash" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td colspan="5" style="padding: 12px 15px; color: #333; font-size: 13px; text-align: left; background-color: #fff;">
                        Tidak ada data yang ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div style="font-size: 12px; color: #666;">
            Halaman {{ $data->currentPage() }} dari {{ $data->lastPage() }}
        </div>
        <div>
            {{ $data->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT DIAGNOSA KEPERAWATAN --}}
<div class="modal fade" id="modalDiagnosaKep" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background-color: #00a65a; color: white; padding: 10px 15px;">
                <h5 class="modal-title" id="modalTitle" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Data Diagnosa Keperawatan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>
            <form id="formDiagnosaKep">
                @csrf
                <input type="hidden" id="diagnosaKepId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                <div class="modal-body" style="padding: 20px 25px; font-size: 12.5px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kode Diagnosa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="diag_kode" name="kode_diagnosa" required placeholder="Cth: DP001, D0130" style="text-transform: uppercase;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Nama Diagnosa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="diag_nama" name="nama_diagnosa" required placeholder="Cth: Bersihan Jalan Nafas tidak efektif">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Keterangan</label>
                        <textarea class="form-control form-control-sm" id="diag_keterangan" name="keterangan" rows="4" placeholder="Penjelasan atau definisi diagnosis keperawatan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 10px 15px; background-color: #f7f7f7;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px; padding: 4px 12px;">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" id="btnSimpanDiagnosaKep" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px; padding: 4px 16px; font-weight: 500;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL DIAGNOSA KEPERAWATAN --}}
<div class="modal fade" id="modalDetailDiagnosaKep" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background-color: #337ab7; color: white; padding: 10px 15px;">
                <h5 class="modal-title" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-info-circle me-1"></i> Detail Diagnosa Keperawatan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>
            <div class="modal-body" style="padding: 20px; font-size: 12.5px;">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="35%" style="background-color: #f9f9f9;">Kode Diagnosa</th>
                        <td id="det_kode" class="fw-bold text-primary"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Nama Diagnosa</th>
                        <td id="det_nama" class="fw-bold"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Keterangan</th>
                        <td id="det_keterangan" style="line-height: 1.4;"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="padding: 10px 15px; background-color: #f7f7f7;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px; padding: 4px 12px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function openModal(id) {
        let el = document.getElementById(id);
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        } else if (window.$) {
            $('#' + id).modal('show');
        }
    }

    function closeModal(id) {
        let el = document.getElementById(id);
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).hide();
        } else if (window.$) {
            $('#' + id).modal('hide');
        }
    }

    // Tambah Diagnosa Keperawatan
    $(document).on('click', '#btnTambahData', function () {
        $('#modalTitle').html('<i class="fas fa-plus-circle me-1"></i> Tambah Data Diagnosa Keperawatan');
        $('#formDiagnosaKep')[0].reset();
        $('#diagnosaKepId').val('');
        $('#formMethod').val('POST');
        openModal('modalDiagnosaKep');
    });

    // Edit Diagnosa Keperawatan
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `/diagnosa-keperawatan/${id}/edit`,
            type: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function (data) {
                $('#modalTitle').html('<i class="fas fa-edit me-1"></i> Edit Data Diagnosa Keperawatan');
                $('#diagnosaKepId').val(data.id);
                $('#formMethod').val('PUT');
                $('#diag_kode').val(data.kode_diagnosa);
                $('#diag_nama').val(data.nama_diagnosa);
                $('#diag_keterangan').val(data.keterangan || '');
                openModal('modalDiagnosaKep');
            },
            error: function () {
                alert('Gagal mengambil data diagnosa keperawatan.');
            }
        });
    });

    // View Detail
    $(document).on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `/diagnosa-keperawatan/${id}`,
            type: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function (data) {
                $('#det_kode').text(data.kode_diagnosa);
                $('#det_nama').text(data.nama_diagnosa);
                $('#det_keterangan').text(data.keterangan || '-');
                openModal('modalDetailDiagnosaKep');
            },
            error: function () {
                alert('Gagal mengambil detail diagnosa keperawatan.');
            }
        });
    });

    // Submit Form
    $(document).on('submit', '#formDiagnosaKep', function (e) {
        e.preventDefault();
        const id = $('#diagnosaKepId').val();
        const method = $('#formMethod').val();
        const url = id ? `/diagnosa-keperawatan/${id}` : '/diagnosa-keperawatan';

        let formData = new FormData(this);
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        const $btnSubmit = $('#btnSimpanDiagnosaKep');
        $btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function (data) {
                if (data.success) {
                    closeModal('modalDiagnosaKep');
                    window.location.reload();
                } else {
                    alert('Gagal menyimpan: ' + (data.message || 'Periksa input Anda'));
                    $btnSubmit.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                }
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan koneksi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
                $btnSubmit.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
            }
        });
    });

    // Delete Diagnosa
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const kode = $(this).data('kode');
        const nama = $(this).data('nama');

        if (confirm(`Apakah Anda yakin ingin menghapus Diagnosa Keperawatan [${kode}] ${nama}?`)) {
            $.ajax({
                url: `/diagnosa-keperawatan/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                success: function (data) {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus: ' + (data.message || 'Terjadi kesalahan'));
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan koneksi saat menghapus.');
                }
            });
        }
    });
});
</script>
@endsection
