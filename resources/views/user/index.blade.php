@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="fw-normal mb-0" style="color: #333; font-size: 24px;">Data Pengguna Aplikasi</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 12px; background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Data Pengguna Aplikasi</li>
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
            <a href="/user/admin/create" class="btn text-white fw-bold px-3 shadow-sm" style="background-color: #00a65a; border-color: #00a65a; border-radius: 3px; font-size: 13px;">
                <i class="fas fa-plus me-1"></i> Tambah User
            </a>
        </div>
        <div>
            <a href="/setting-role" class="btn text-white fw-bold px-3 shadow-sm" style="background-color: #00c0ef; border-color: #00c0ef; border-radius: 3px; font-size: 13px;">
                <i class="fas fa-sitemap me-1"></i> Alur Hak Akses User
            </a>
        </div>
    </div>

    <!-- Collapsible Filter Panel -->
    <div id="filterPanel" class="card shadow-sm border-0 mb-3 d-none" style="border-top: 3px solid #f39c12 !important; border-radius: 3px;">
        <div class="card-body p-3">
            <form method="GET" action="/user/admin/index" id="filterForm" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold small mb-1">Cari (Username / Nama / Email / NIK)</label>
                    <input type="text" name="search" id="filterKeyword" class="form-control form-control-sm" placeholder="Ketik kata kunci pencarian..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small mb-1">Status</label>
                    <select name="status" id="filterStatus" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-bold" style="background-color: #f39c12;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="/user/admin/index" class="btn btn-sm btn-light border px-3">
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
                <table class="table table-bordered table-striped table-hover align-middle mb-0" id="tabelUser" style="font-size: 12px; border-color: #e9ecef;">
                    <thead style="background-color: #fdfdfd; border-bottom: 2px solid #dee2e6;">
                        <tr style="color: #333;">
                            <th style="width: 45px;" class="text-center">#</th>
                            <th style="width: 80px;" class="text-center">Detail</th>
                            <th style="min-width: 130px;">Username</th>
                            <th style="min-width: 180px;">Profil Name</th>
                            <th style="min-width: 200px;">Karyawan &amp; NIK</th>
                            <th style="min-width: 200px;">Email</th>
                            <th style="width: 90px;" class="text-center">Status</th>
                            <th style="width: 110px;" class="text-center">Dibuat pada</th>
                            <th style="width: 150px;" class="text-center">Login terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        <tr data-id="{{ $item->id }}">
                            <td class="text-center fw-normal" style="color: #555;">{{ $index + 1 }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm text-white btnDetail" data-id="{{ $item->id }}" style="background-color: #00c0ef; border-color: #00c0ef; border-radius: 3px; font-size: 11px; padding: 2px 9px;">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </button>
                            </td>
                            <td class="fw-normal" style="color: #333;">{{ $item->username ?? '-' }}</td>
                            <td style="color: #333;">{{ $item->name }}</td>
                            <td>
                                <div style="color: #333;">{{ $item->pegawai ? $item->pegawai->nama_pegawai : $item->name }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $item->pegawai ? $item->pegawai->nik : '0' }}</div>
                            </td>
                            <td style="color: #333;">{{ $item->email }}</td>
                            <td class="text-center" style="color: #333;">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </td>
                            <td class="text-center" style="color: #333;">
                                {{ $item->created_at ? $item->created_at->format('d-m-Y') : '-' }}
                            </td>
                            <td class="text-center" style="color: #333;">
                                {{ $item->last_login_at ? \Carbon\Carbon::parse($item->last_login_at)->format('d-m-Y H:i:s') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data Pengguna Aplikasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail User -->
<div class="modal fade" id="modalDetailUser" tabindex="-1" aria-labelledby="modalDetailUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #00c0ef; border-radius: 3px 3px 0 0;">
                <h5 class="modal-title fs-6 fw-bold" id="modalDetailUserLabel">
                    <i class="fas fa-user-circle me-2"></i> Detail Pengguna Aplikasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 13px;">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-bold text-muted" style="width: 140px;">Username</td>
                            <td style="width: 10px;">:</td>
                            <td class="fw-bold text-dark" id="detailUsername">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Profil Name</td>
                            <td>:</td>
                            <td id="detailName">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Karyawan Terkait</td>
                            <td>:</td>
                            <td id="detailPegawai">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">NIK Karyawan</td>
                            <td>:</td>
                            <td id="detailNik">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Email</td>
                            <td>:</td>
                            <td id="detailEmail">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Status Akun</td>
                            <td>:</td>
                            <td id="detailStatus">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Hak Akses (Role)</td>
                            <td>:</td>
                            <td id="detailRole">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Dibuat Pada</td>
                            <td>:</td>
                            <td id="detailCreatedAt">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Login Terakhir</td>
                            <td>:</td>
                            <td id="detailLastLogin">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-light py-2 d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-sm btn-danger px-3" id="btnHapusFromDetail">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                    <a href="#" id="btnEditFromDetail" class="btn btn-sm text-white px-3" style="background-color: #f39c12;">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                </div>
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapusUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> Hapus Pengguna
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus user <br><strong id="namaHapusUser" class="text-danger"></strong>?
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
    let currentUserId = null;
    let currentUserName = '';

    // Toggle Search Filter Panel
    $('#btnToggleFilter').on('click', function () {
        $('#filterPanel').toggleClass('d-none');
        if (!$('#filterPanel').hasClass('d-none')) {
            $('#filterKeyword').focus();
        }
    });

    // Detail Button Click
    $(document).on('click', '.btnDetail', function () {
        let id = $(this).data('id');
        currentUserId = id;

        $.get('/user/admin/' + id, function (res) {
            currentUserName = res.username || res.name;
            $('#detailUsername').text(res.username || '-');
            $('#detailName').text(res.name || '-');
            $('#detailPegawai').text(res.pegawai ? res.pegawai.nama_pegawai : (res.name || '-'));
            $('#detailNik').text(res.pegawai ? res.pegawai.nik : '0');
            $('#detailEmail').text(res.email || '-');
            
            if (res.is_active) {
                $('#detailStatus').html('<span class="badge bg-success">Active</span>');
            } else {
                $('#detailStatus').html('<span class="badge bg-danger">Inactive</span>');
            }

            if (res.role) {
                $('#detailRole').html('<span class="badge bg-primary px-2 py-1">' + res.role.name + '</span>');
            } else {
                $('#detailRole').html('<span class="text-muted fst-italic">Belum diatur</span>');
            }

            let createdAt = res.created_at ? new Date(res.created_at) : null;
            let createdAtStr = createdAt ? createdAt.getDate().toString().padStart(2, '0') + '-' + (createdAt.getMonth()+1).toString().padStart(2, '0') + '-' + createdAt.getFullYear() : '-';
            $('#detailCreatedAt').text(createdAtStr);

            let lastLoginStr = '-';
            if (res.last_login_at) {
                let d = new Date(res.last_login_at);
                lastLoginStr = d.getDate().toString().padStart(2, '0') + '-' + (d.getMonth()+1).toString().padStart(2, '0') + '-' + d.getFullYear() + ' ' +
                               d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0') + ':' + d.getSeconds().toString().padStart(2, '0');
            }
            $('#detailLastLogin').text(lastLoginStr);

            $('#btnEditFromDetail').attr('href', '/user/admin/edit/' + res.id);
            $('#modalDetailUser').modal('show');
        });
    });

    // Hapus from detail modal
    $('#btnHapusFromDetail').on('click', function () {
        $('#modalDetailUser').modal('hide');
        $('#namaHapusUser').text(currentUserName);
        $('#modalHapusUser').modal('show');
    });

    // Confirm Hapus
    $('#btnKonfirmasiHapus').on('click', function () {
        if (!currentUserId) return;
        $.ajax({
            url: '/user/admin/delete/' + currentUserId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#modalHapusUser').modal('hide');
                showAlert('success', res.message || 'User berhasil dihapus.');
                $(`tr[data-id="${currentUserId}"]`).fadeOut(300, function () {
                    $(this).remove();
                });
            },
            error: function () {
                alert('Gagal menghapus user.');
            }
        });
    });

    // Client-side quick filter on keyup
    $('#filterKeyword').on('keyup', function () {
        let keyword = $(this).val().toLowerCase();
        $('#tabelUser tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(keyword) > -1);
        });
    });

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
            .addClass('alert-' + type).html('<i class="fas fa-check-circle me-1"></i> ' + msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }
});
</script>
@endsection
