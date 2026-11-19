@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="fw-normal mb-0" style="color: #333; font-size: 24px;">Setting Hak Akses Pengguna</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 12px; background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/user/admin/index" class="text-decoration-none text-muted">Data Pengguna Aplikasi</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Hak Akses</a></li>
            </ol>
        </nav>
    </div>
    
    <!-- Orange accent line below header -->
    <div style="height: 3px; background-color: #f39c12; margin-bottom: 18px; width: 100%;"></div>

    <!-- Alert Box -->
    <div id="alertBox" class="alert d-none mb-3" role="alert"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Action Buttons Row -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <button type="button" class="btn text-white fw-bold px-3 shadow-sm" id="btnToggleFilter" style="background-color: #f39c12; border-color: #f39c12; border-radius: 3px; font-size: 13px;">
                <i class="fas fa-search me-1"></i> Cari data
            </button>
            <button type="button" class="btn text-white fw-bold px-3 shadow-sm" id="btnTambahRole" data-bs-toggle="modal" data-bs-target="#modalRole" style="background-color: #00a65a; border-color: #00a65a; border-radius: 3px; font-size: 13px;">
                <i class="fas fa-plus me-1"></i> Tambah Hak Akses
            </button>
        </div>
        <div>
            <a href="/user/admin/index" class="btn text-white fw-bold px-3 shadow-sm" style="background-color: #00c0ef; border-color: #00c0ef; border-radius: 3px; font-size: 13px;">
                <i class="fas fa-users me-1"></i> Manajemen User
            </a>
        </div>
    </div>

    <!-- Collapsible Filter Panel -->
    <div id="filterPanel" class="card shadow-sm border-0 mb-3 d-none" style="border-top: 3px solid #f39c12 !important; border-radius: 3px;">
        <div class="card-body p-3">
            <form method="GET" action="/setting-role" id="filterForm" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">Cari Nama Hak Akses (Role)</label>
                    <input type="text" name="search" id="filterKeyword" class="form-control form-control-sm" placeholder="Ketik nama role..." value="{{ request('search') }}">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-bold" style="background-color: #f39c12;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="/setting-role" class="btn btn-sm btn-light border px-3">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Box with Cyan Top Border -->
    <div class="card shadow-sm border-0 mb-4" style="border-top: 3px solid #00c0ef !important; border-radius: 3px; background-color: #ffffff;">
        <div class="card-body p-3">
            <!-- Counter Text -->
            <div class="mb-2 text-dark" style="font-size: 13px;">
                Total <strong>{{ $data->count() }} item</strong>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0" id="tabelRole" style="font-size: 12px; border-color: #e9ecef;">
                    <thead style="background-color: #fdfdfd; border-bottom: 2px solid #dee2e6;">
                        <tr style="color: #333;">
                            <th style="width: 45px;" class="text-center">#</th>
                            <th style="width: 90px;" class="text-center">Aksi</th>
                            <th style="min-width: 180px;">Nama Hak Akses (Role)</th>
                            <th style="min-width: 280px;">Cakupan Modul / Hak Akses</th>
                            <th style="width: 140px;" class="text-center">Total Pengguna</th>
                            <th style="width: 120px;" class="text-center">Dibuat pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        @php
                            $perms = is_array($item->permissions) ? $item->permissions : [];
                            $totalPerms = count($perms);
                        @endphp
                        <tr data-id="{{ $item->id }}">
                            <td class="text-center fw-normal" style="color: #555;">{{ $index + 1 }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary btnEdit px-2 py-0" data-id="{{ $item->id }}" title="Edit Role" style="font-size: 11px;">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btnHapus px-2 py-0" data-id="{{ $item->id }}" data-nama="{{ $item->name }}" title="Hapus Role" style="font-size: 11px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="fw-bold" style="color: #2c3e50;">
                                <i class="fas fa-shield-alt text-primary me-1"></i> {{ $item->name }}
                            </td>
                            <td>
                                @if(in_array('profil_faskes', $perms) && count($perms) > 20)
                                    <span class="badge bg-success py-1 px-2"><i class="fas fa-check-double me-1"></i> Akses Penuh Seluruh Sistem (Administrator)</span>
                                @elseif($totalPerms > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(array_slice($perms, 0, 5) as $p)
                                            <span class="badge bg-light text-dark border py-1 px-2" style="font-weight: 500; font-size: 11px;">
                                                {{ ucwords(str_replace('_', ' ', $p)) }}
                                            </span>
                                        @endforeach
                                        @if($totalPerms > 5)
                                            <span class="badge bg-secondary py-1 px-2" style="font-size: 11px;">+{{ $totalPerms - 5 }} modul lainnya</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">Belum ada modul yang dipilih</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge px-3 py-1" style="background-color: #00c0ef; font-size: 12px; font-weight: 500;">
                                    <i class="fas fa-user me-1"></i> {{ $item->users_count }} User
                                </span>
                            </td>
                            <td class="text-center" style="color: #555;">
                                {{ $item->created_at ? $item->created_at->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data Hak Akses (Role).</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit Role -->
<div class="modal fade" id="modalRole" tabindex="-1" aria-labelledby="modalRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" id="modalRoleHeader" style="background-color: #00a65a; border-radius: 3px 3px 0 0;">
                <h5 class="modal-title fs-6 fw-bold" id="modalRoleLabel">
                    <i class="fas fa-shield-alt me-2"></i> Tambah Hak Akses (Role)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRole">
                @csrf
                <input type="hidden" id="roleId" name="id">
                <div class="modal-body p-4" style="font-size: 13px;">
                    <!-- Input Nama Role -->
                    <div class="mb-4">
                        <label for="name" class="form-label text-dark fw-bold mb-1">
                            Nama Hak Akses (Role) <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name" 
                               placeholder="Contoh: Administrator, Dokter, Perawat, Kasir, Apoteker" maxlength="100" required>
                        <div class="invalid-feedback" id="err_name"></div>
                    </div>

                    <!-- Header Permission Matrix with Select All Action -->
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <label class="form-label text-dark fw-bold mb-0">
                            <i class="fas fa-key me-1 text-warning"></i> Pengaturan Izin Akses Modul Aplikasi
                        </label>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-success btn-sm py-0 px-2" id="btnSelectAllPerms">
                                <i class="fas fa-check-square me-1"></i> Pilih Semua
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" id="btnUnselectAllPerms">
                                <i class="fas fa-square me-1"></i> Batalkan Semua
                            </button>
                        </div>
                    </div>

                    <!-- Permission Matrix Groups -->
                    <div class="row g-3">
                        @foreach($moduleGroups as $groupName => $modules)
                        <div class="col-md-6">
                            <div class="card h-100 border shadow-none" style="background-color: #fafafa; border-radius: 4px;">
                                <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                                    <strong class="text-dark" style="font-size: 12px;">
                                        <i class="fas fa-folder-open text-primary me-1"></i> {{ $groupName }}
                                    </strong>
                                    <button type="button" class="btn btn-link p-0 text-muted btnToggleGroup" data-target="group-{{ Str::slug($groupName) }}" style="font-size: 11px; text-decoration: none;">
                                        Pilih Grup
                                    </button>
                                </div>
                                <div class="card-body p-3 group-{{ Str::slug($groupName) }}">
                                    @foreach($modules as $key => $label)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input chk-perm" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $key }}">
                                        <label class="form-check-label text-dark" for="perm_{{ $key }}" style="font-size: 12px; cursor: pointer;">
                                            {{ $label }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white px-4 fw-bold" id="btnSubmitRole" style="background-color: #00a65a;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapusRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> Hapus Hak Akses
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus hak akses <br><strong id="namaHapusRole" class="text-danger"></strong>?
            </div>
            <div class="modal-footer bg-light py-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-danger px-3" id="btnKonfirmasiHapus">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let hapusId = null;
    let hapusRow = null;

    // Toggle Filter
    $('#btnToggleFilter').on('click', function () {
        $('#filterPanel').toggleClass('d-none');
        if (!$('#filterPanel').hasClass('d-none')) {
            $('#filterKeyword').focus();
        }
    });

    // Client-side quick filter on keyup
    $('#filterKeyword').on('keyup', function () {
        let keyword = $(this).val().toLowerCase();
        $('#tabelRole tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(keyword) > -1);
        });
    });

    // Select all permissions
    $('#btnSelectAllPerms').on('click', function () {
        $('.chk-perm').prop('checked', true);
    });

    // Unselect all permissions
    $('#btnUnselectAllPerms').on('click', function () {
        $('.chk-perm').prop('checked', false);
    });

    // Toggle permissions by group
    $('.btnToggleGroup').on('click', function () {
        let groupClass = $(this).data('target');
        let checkboxes = $('.' + groupClass + ' .chk-perm');
        let allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        checkboxes.prop('checked', !allChecked);
    });

    // Reset modal on Tambah Role
    $('#btnTambahRole').on('click', function () {
        $('#modalRoleHeader').css('background-color', '#00a65a');
        $('#btnSubmitRole').css('background-color', '#00a65a');
        $('#modalRoleLabel').html('<i class="fas fa-shield-alt me-2"></i> Tambah Hak Akses (Role)');
        $('#formRole')[0].reset();
        $('#roleId').val('');
        $('.chk-perm').prop('checked', false);
        clearErrors();
    });

    // Click Edit Role
    $(document).on('click', '.btnEdit', function () {
        let id = $(this).data('id');
        clearErrors();
        $('#modalRoleHeader').css('background-color', '#00c0ef');
        $('#btnSubmitRole').css('background-color', '#00c0ef');
        $('#modalRoleLabel').html('<i class="fas fa-pencil-alt me-2"></i> Edit Hak Akses (Role)');

        $.get('/setting-role/' + id + '/edit', function (res) {
            $('#roleId').val(res.id);
            $('#name').val(res.name);
            $('.chk-perm').prop('checked', false);

            if (res.permissions && Array.isArray(res.permissions)) {
                res.permissions.forEach(function (perm) {
                    $(`input.chk-perm[value="${perm}"]`).prop('checked', true);
                });
            }
            $('#modalRole').modal('show');
        });
    });

    // Submit form (Create / Update)
    $('#formRole').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        let id = $('#roleId').val();
        let url = id ? '/setting-role/' + id : '/setting-role';
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function (res) {
                $('#modalRole').modal('hide');
                showAlert('success', res.message);
                setTimeout(function () {
                    location.reload();
                }, 800);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $('#' + field).addClass('is-invalid');
                        $('#err_' + field).text(messages[0]);
                    });
                } else {
                    alert('Terjadi kesalahan saat menyimpan data.');
                }
            }
        });
    });

    // Click Hapus Role
    $(document).on('click', '.btnHapus', function () {
        hapusId = $(this).data('id');
        hapusRow = $(this).closest('tr');
        $('#namaHapusRole').text($(this).data('nama'));
        $('#modalHapusRole').modal('show');
    });

    // Confirm Hapus
    $('#btnKonfirmasiHapus').on('click', function () {
        if (!hapusId) return;

        $.ajax({
            url: '/setting-role/' + hapusId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#modalHapusRole').modal('hide');
                showAlert('success', res.message);
                hapusRow.fadeOut(300, function () {
                    $(this).remove();
                });
            },
            error: function (xhr) {
                $('#modalHapusRole').modal('hide');
                showAlert('danger', xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menghapus data.');
            }
        });
    });

    function clearErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
            .addClass('alert-' + type).html('<i class="fas fa-check-circle me-1"></i> ' + msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }
});
</script>
@endsection
