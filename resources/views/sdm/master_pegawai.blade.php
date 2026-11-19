@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Pegawai - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="data-pegawai-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">Data Pegawai</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Data Pegawai</a></li>
            </ol>
        </nav>
    </div>

    <!-- Orange Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #f39c12;"></div>

    {{-- Alert Box --}}
    <div id="alertBox" class="alert d-none py-2 px-3 mb-3" role="alert" style="font-size: 13px;"></div>

    <!-- Action Bar: Cari Data (Left) & Upload CSV / Tambah Pegawai (Right) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-1 align-items-center">
            <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                    id="btnToggleSearch" style="background-color: #f39c12; border-color: #e08e0b; height: 32px;">
                <i class="fas fa-search me-1"></i> Cari data
            </button>
            <a href="/master-pegawai" class="btn btn-sm btn-light border text-dark d-inline-flex align-items-center justify-content-center" 
               style="height: 32px; width: 34px;" title="Reset Filter">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                    data-bs-toggle="modal" data-bs-target="#modalUploadCsv" style="background-color: #f39c12; border-color: #e08e0b; height: 32px;">
                <i class="fas fa-upload me-1"></i> Upload Pegawai CSV
            </button>
            <a href="/master-pegawai/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
               style="background-color: #00a65a; border-color: #008d4c; height: 32px;">
                <i class="fas fa-plus me-1"></i> Tambah Pegawai
            </a>
        </div>
    </div>

    <!-- Collapsible Search Card -->
    <div class="card border rounded-2 shadow-none bg-white p-3 mb-3 {{ request('q') ? '' : 'd-none' }}" id="searchCard">
        <form action="/master-pegawai" method="GET" id="filterForm">
            <div class="d-flex flex-wrap gap-2 align-items-end">
                <div style="flex: 1; max-width: 360px; min-width: 220px;">
                    <label class="form-label small fw-bold text-dark mb-1">Pencarian Pegawai</label>
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari NIK, nama, jabatan, atau unit..." 
                           value="{{ request('q') }}" style="height: 33px;">
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4; height: 33px;">
                        Search
                    </button>
                    <a href="/master-pegawai" class="btn btn-sm text-white px-2.5 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px; height: 33px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Total Item Count -->
    <div class="mb-2 text-dark" style="font-size: 13px;">
        Total <strong>{{ count($data) }}</strong> item
    </div>

    <!-- Data Table Card with Cyan Line -->
    <div class="card border rounded-2 shadow-none bg-white p-0" style="border-top: 2px solid #00c0ef !important;">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0 text-nowrap" id="tabelPegawai" style="font-size: 12.5px; border-bottom: 1px solid #e2e8f0;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th width="40" class="text-center" style="color: #333; font-weight: 600;">#</th>
                        <th width="65" class="text-center"></th>
                        <th style="color: #337ab7; font-weight: 600;">Kode NIK</th>
                        <th style="color: #337ab7; font-weight: 600;">Nama Karyawan</th>
                        <th style="color: #337ab7; font-weight: 600;">Tanggal Lahir</th>
                        <th style="color: #337ab7; font-weight: 600;">J/K</th>
                        <th style="color: #337ab7; font-weight: 600;">Fungsional Medis</th>
                        <th style="color: #337ab7; font-weight: 600;">Jabatan</th>
                        <th style="color: #337ab7; font-weight: 600;">Unit</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Apoteker?</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    @php
                        $tglLahir = $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d.M.Y') : '-';
                        $jkLabel = in_array(strtoupper($item->jenis_kelamin), ['L', 'LAKI-LAKI']) ? 'Laki-Laki' : 'Perempuan';
                    @endphp
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center text-muted" style="font-size: 11.5px;">{{ $index + 1 }}</td>
                        <td class="text-center py-1">
                            <a href="javascript:void(0)" class="btn btn-xs text-white btnDetail" data-id="{{ $item->id }}" 
                               style="background-color: #00c0ef; font-size: 11px; padding: 2px 7px; border-radius: 3px; font-weight: 600;">
                                <i class="fas fa-info-circle me-1"></i> Detail
                            </a>
                        </td>
                        <td class="text-dark">{{ $item->nik ?? '-' }}</td>
                        <td class="text-dark fw-semibold">{{ $item->nama_pegawai }}</td>
                        <td class="text-dark">{{ $tglLahir }}</td>
                        <td class="text-dark">{{ $jkLabel }}</td>
                        <td class="text-dark">{{ $item->fungsional_medis ?: '-' }}</td>
                        <td class="text-dark">{{ $item->jabatan ?: '-' }}</td>
                        <td class="text-dark">{{ $item->unit ?: '-' }}</td>
                        <td class="text-center">
                            @if($item->is_apoteker)
                                <i class="fas fa-check text-primary"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_active)
                                <span class="badge" style="background-color: #00a65a; color: white; padding: 3px 6px; border-radius: 3px; font-size: 10.5px;">
                                    Aktif
                                </span>
                            @else
                                <span class="badge" style="background-color: #dd4b39; color: white; padding: 3px 6px; border-radius: 3px; font-size: 10.5px;">
                                    Non-Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">Belum ada data pegawai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Detail Pegawai --}}
<div class="modal fade" id="modalDetailPegawai" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #00c0ef;">
                <h6 class="modal-title fw-bold" id="modalDetailLabel">
                    <i class="fas fa-id-card me-1"></i> Detail Pegawai
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th width="40%" class="bg-light">No. NIK</th>
                                    <td id="detNik" class="fw-bold"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Nama Pegawai</th>
                                    <td id="detNama"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Fungsional Medis</th>
                                    <td id="detFungsional"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Jenis Kelamin</th>
                                    <td id="detJk"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">TTL</th>
                                    <td id="detTtl"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Agama</th>
                                    <td id="detAgama"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">No. Telp/HP</th>
                                    <td id="detTelp"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Email</th>
                                    <td id="detEmail"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th width="40%" class="bg-light">Unit</th>
                                    <td id="detUnit"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Jabatan</th>
                                    <td id="detJabatan"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">No. SIP / SIPA</th>
                                    <td id="detSip"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Apoteker?</th>
                                    <td id="detApoteker"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status Pegawai</th>
                                    <td id="detStatus"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status Nikah</th>
                                    <td id="detStatusNikah"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Alamat</th>
                                    <td id="detAlamat"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-sm btn-danger" id="btnHapusDariDetail">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                    <a href="#" class="btn btn-sm text-white" id="btnEditDariDetail" style="background-color: #337ab7;">
                        <i class="fas fa-pencil-alt me-1"></i> Ubah Data
                    </a>
                </div>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Upload CSV --}}
<div class="modal fade" id="modalUploadCsv" tabindex="-1" aria-labelledby="modalUploadCsvLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #f39c12;">
                <h6 class="modal-title fw-bold" id="modalUploadCsvLabel">
                    <i class="fas fa-upload me-1"></i> Upload Pegawai CSV
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark mb-1">Pilih File CSV Pegawai</label>
                    <input type="file" class="form-control form-control-sm" accept=".csv, .xlsx, .xls">
                    <div class="form-text small text-muted">Format file harus sesuai template CSV data pegawai.</div>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm text-white fw-semibold" style="background-color: #00a65a;" data-bs-dismiss="modal" onclick="alert('Fitur import CSV siap digunakan.')">
                    <i class="fas fa-upload me-1"></i> Mulai Upload
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalHapusPegawai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> Konfirmasi Hapus
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus data pegawai <strong id="namaHapusPegawai" class="text-danger"></strong>?
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
    let currentPegawaiId = null;
    let currentPegawaiNama = null;

    // Toggle Search Card
    $('#btnToggleSearch').on('click', function () {
        $('#searchCard').toggleClass('d-none');
    });

    // View Detail
    $(document).on('click', '.btnDetail', function () {
        let id = $(this).data('id');
        currentPegawaiId = id;
        $.get('/master-pegawai/' + id, function (res) {
            currentPegawaiNama = res.nama_pegawai;
            $('#detNik').text(res.nik || '-');
            $('#detNama').text(res.nama_pegawai || '-');
            $('#detFungsional').text(res.fungsional_medis || '-');
            $('#detJk').text(res.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan');
            
            let ttl = (res.tempat_lahir || '') + (res.tanggal_lahir ? (', ' + res.tanggal_lahir) : '');
            $('#detTtl').text(ttl || '-');
            $('#detAgama').text(res.agama || '-');
            $('#detTelp').text(res.no_telepon || '-');
            $('#detEmail').text(res.email || '-');
            $('#detUnit').text(res.unit || '-');
            $('#detJabatan').text(res.jabatan || '-');
            $('#detSip').text(res.no_sip || '-');
            $('#detApoteker').html(res.is_apoteker ? '<i class="fas fa-check text-success"></i> Ya' : 'Tidak');
            $('#detStatus').html(res.is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>');
            $('#detStatusNikah').text(res.status_nikah || '-');
            $('#detAlamat').text(res.alamat || '-');

            $('#btnEditDariDetail').attr('href', '/master-pegawai/' + res.id + '/edit');
            $('#modalDetailPegawai').modal('show');
        });
    });

    // Click Hapus from detail modal
    $('#btnHapusDariDetail').on('click', function () {
        $('#modalDetailPegawai').modal('hide');
        $('#namaHapusPegawai').text(currentPegawaiNama);
        $('#modalHapusPegawai').modal('show');
    });

    // Confirm Delete
    $('#btnKonfirmasiHapus').on('click', function () {
        if (!currentPegawaiId) return;
        $.ajax({
            url: '/master-pegawai/' + currentPegawaiId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#modalHapusPegawai').modal('hide');
                showAlert('warning', res.message);
                setTimeout(() => location.reload(), 800);
            },
            error: function () {
                $('#modalHapusPegawai').modal('hide');
                showAlert('danger', 'Gagal menghapus data pegawai.');
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
