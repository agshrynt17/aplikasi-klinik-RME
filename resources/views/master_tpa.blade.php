@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Third Party Administration (TPA) - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="data-tpa-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.2rem; letter-spacing: 0.3px;">Third Party Administration (TPA)</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Third Party Administration (TPA)</a></li>
            </ol>
        </nav>
    </div>

    <!-- Orange Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #f39c12;"></div>

    {{-- Alert Box --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size: 13px;">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div id="alertBox" class="alert d-none py-2 px-3 mb-3" role="alert" style="font-size: 13px;"></div>

    <!-- Action Bar: Cari Data (Left) & Tambah Data (Right) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-1 align-items-center">
            <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                    id="btnToggleSearch" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-search me-1"></i> Cari data
            </button>
            <a href="/master-tpa" class="btn btn-sm btn-light border text-dark d-inline-flex align-items-center justify-content-center" 
               style="height: 32px; width: 34px; border-radius: 3px;" title="Reset Filter">
                <i class="fas fa-sync-alt" style="font-size: 12px;"></i>
            </a>
        </div>
        <div>
            <a href="/master-tpa/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
               style="background-color: #00a65a; border-color: #008d4c; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        </div>
    </div>

    <!-- Collapsible Search Card -->
    <div class="card border rounded-2 shadow-none bg-white p-3 mb-3 {{ request('q') || request('status') ? '' : 'd-none' }}" id="searchCard">
        <form action="/master-tpa" method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-dark mb-1">Pencarian TPA</label>
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari kode, nama, alamat, atau telp..." 
                           value="{{ request('q') }}" style="height: 33px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm" style="height: 33px;">
                        <option value="">--Semua--</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4; height: 33px;">
                        Search
                    </button>
                    <a href="/master-tpa" class="btn btn-sm text-white px-2.5 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px; height: 33px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Counter Text -->
    <div class="mb-2 text-dark" style="font-size: 13px;">
        Menampilkan <strong>1-{{ count($data) }}</strong> dari <strong>{{ count($data) }}</strong> item
    </div>

    <!-- Data Table Card with Cyan Line -->
    <div class="card border rounded-1 shadow-none bg-white p-0" style="border-top: 2px solid #00c0ef !important;">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0 text-nowrap" id="tabelTpa" style="font-size: 12.5px; border-bottom: 1px solid #e2e8f0;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th width="40" class="text-center" style="color: #333; font-weight: 600;">#</th>
                        <th width="80" class="text-center"></th>
                        <th style="color: #337ab7; font-weight: 600;">Kode TPA</th>
                        <th style="color: #337ab7; font-weight: 600;">Nama TPA</th>
                        <th style="color: #337ab7; font-weight: 600;">Alamat</th>
                        <th style="color: #337ab7; font-weight: 600;">Telp</th>
                        <th style="color: #337ab7; font-weight: 600;">Fax</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center fw-bold" style="color: #555;">{{ $index + 1 }}</td>
                        <td class="text-center">
                            <a href="javascript:void(0)" class="btn-detail d-inline-flex align-items-center gap-1 text-decoration-none" 
                               data-id="{{ $item->id }}" style="color: #00c0ef; font-size: 12px; font-weight: 500;">
                                <i class="fas fa-info-circle" style="color: #00c0ef; font-size: 13px;"></i> Detail
                            </a>
                        </td>
                        <td style="color: #333;">{{ $item->kode_tpa }}</td>
                        <td style="color: #333; font-weight: 500;">{{ $item->nama_tpa }}</td>
                        <td style="color: #555;">{{ $item->alamat ?? '' }}</td>
                        <td style="color: #555;">{{ $item->telp ?? '' }}</td>
                        <td style="color: #555;">{{ $item->fax ?? '' }}</td>
                        <td class="text-center">
                            @if($item->is_active)
                                <span class="badge" style="background-color: #00a65a; font-weight: 500; font-size: 11px; padding: 3px 7px; border-radius: 3px;">Aktif</span>
                            @else
                                <span class="badge" style="background-color: #dd4b39; font-weight: 500; font-size: 11px; padding: 3px 7px; border-radius: 3px;">Non Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i> Belum ada data TPA.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Detail Modal --}}
<div class="modal fade" id="modalDetailTpa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-2 border-0 shadow">
            <div class="modal-header py-2 px-3" style="background-color: #00c0ef; color: white;">
                <h6 class="modal-title fw-bold" id="modalDetailLabel">
                    <i class="fas fa-info-circle me-1"></i> Detail Data TPA
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td width="130" class="text-muted fw-semibold">Kode TPA</td>
                                    <td width="10">:</td>
                                    <td id="detKode" class="fw-bold text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Nama TPA</td>
                                    <td>:</td>
                                    <td id="detNama" class="fw-bold text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Alamat</td>
                                    <td>:</td>
                                    <td id="detAlamat" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Telp</td>
                                    <td>:</td>
                                    <td id="detTelp" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Fax</td>
                                    <td>:</td>
                                    <td id="detFax" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Email</td>
                                    <td>:</td>
                                    <td id="detEmail" class="text-dark"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td width="130" class="text-muted fw-semibold">Join Date</td>
                                    <td width="10">:</td>
                                    <td id="detJoinDate" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Nomor SPK</td>
                                    <td>:</td>
                                    <td id="detNomorSpk" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Expired Date</td>
                                    <td>:</td>
                                    <td id="detExpiredDate" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Nama PIC</td>
                                    <td>:</td>
                                    <td id="detNamaPic" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Telp PIC</td>
                                    <td>:</td>
                                    <td id="detTelpPic" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Email PIC</td>
                                    <td>:</td>
                                    <td id="detEmailPic" class="text-dark"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Status</td>
                                    <td>:</td>
                                    <td id="detStatus"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-sm btn-danger px-3" id="btnHapusFromDetail" style="border-radius: 3px; font-size: 12px;">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                    <a href="#" class="btn btn-sm btn-primary px-3 text-white" id="btnEditFromDetail" style="border-radius: 3px; font-size: 12px;">
                        <i class="fas fa-edit me-1"></i> Edit Data
                    </a>
                </div>
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 3px; font-size: 12px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus TPA --}}
<div class="modal fade" id="modalHapusTpa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content rounded-2 border-0 shadow">
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-exclamation-triangle me-1"></i> Konfirmasi Hapus</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 px-3 text-dark" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus data TPA <strong id="namaHapusTpa"></strong>?
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: 12px; border-radius: 3px;">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnKonfirmasiHapus" style="font-size: 12px; border-radius: 3px;">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentTpaId = null;
    let currentTpaNama = '';

    // Toggle Search Card
    $('#btnToggleSearch').on('click', function() {
        $('#searchCard').toggleClass('d-none');
    });

    // Detail button clicked
    $(document).on('click', '.btn-detail', function() {
        let id = $(this).data('id');
        currentTpaId = id;
        
        $.get('/master-tpa/' + id, function(res) {
            currentTpaNama = res.nama_tpa;
            $('#detKode').text(res.kode_tpa || '-');
            $('#detNama').text(res.nama_tpa || '-');
            $('#detAlamat').text(res.alamat || '-');
            $('#detTelp').text(res.telp || '-');
            $('#detFax').text(res.fax || '-');
            $('#detEmail').text(res.email || '-');
            $('#detNomorSpk').text(res.nomor_spk || '-');
            $('#detNamaPic').text(res.nama_pic || '-');
            $('#detTelpPic').text(res.telp_pic || '-');
            $('#detEmailPic').text(res.email_pic || '-');

            let joinDate = res.join_date ? res.join_date.substring(0, 10) : '-';
            let expDate = res.expired_date ? res.expired_date.substring(0, 10) : '-';
            $('#detJoinDate').text(joinDate);
            $('#detExpiredDate').text(expDate);

            $('#detStatus').html(res.is_active 
                ? '<span class="badge" style="background-color: #00a65a;">Aktif</span>' 
                : '<span class="badge" style="background-color: #dd4b39;">Non Aktif</span>');

            $('#btnEditFromDetail').attr('href', '/master-tpa/' + res.id + '/edit');
            $('#modalDetailTpa').modal('show');
        });
    });

    // Hapus from detail modal
    $('#btnHapusFromDetail').on('click', function() {
        $('#modalDetailTpa').modal('hide');
        $('#namaHapusTpa').text(currentTpaNama);
        $('#modalHapusTpa').modal('show');
    });

    // Konfirmasi Hapus
    $('#btnKonfirmasiHapus').on('click', function() {
        if (!currentTpaId) return;
        $.ajax({
            url: '/master-tpa/' + currentTpaId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                $('#modalHapusTpa').modal('hide');
                showAlert('success', res.message);
                $(`tr[data-id="${currentTpaId}"]`).fadeOut(300, function() {
                    $(this).remove();
                    renumber();
                });
            },
            error: function() {
                alert('Gagal menghapus data TPA.');
            }
        });
    });

    function renumber() {
        $('#tabelTpa tbody tr').each(function(i) {
            $(this).find('td:first').text(i + 1);
        });
    }

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
            .addClass('alert-' + type).html('<i class="fas fa-check-circle me-1"></i> ' + msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }
});
</script>
@endsection
