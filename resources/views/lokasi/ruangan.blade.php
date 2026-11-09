@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Master Ruangan - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="master-ruangan-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">Master Ruangan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Master Ruangan</li>
            </ol>
        </nav>
    </div>

    <!-- Orange Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #f39c12;"></div>

    {{-- Alert Box --}}
    <div id="alertBox" class="alert d-none py-2 px-3 mb-3" role="alert" style="font-size: 13px;"></div>

    <!-- Action Bar: Cari Data (Left) & Tambah Ruangan (Right) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-1 align-items-center">
            <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                    id="btnToggleSearch" style="background-color: #f39c12; border-color: #e08e0b; height: 32px;">
                <i class="fas fa-search me-1"></i> Cari data
            </button>
            <a href="/master-ruangan" class="btn btn-sm btn-light border text-dark d-inline-flex align-items-center justify-content-center" 
               style="height: 32px; width: 34px;" title="Reset Filter">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>
        <div>
            <a href="/master-ruangan/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
               style="background-color: #00a65a; border-color: #008d4c; height: 32px;">
                <i class="fas fa-plus me-1"></i> Tambah Ruangan
            </a>
        </div>
    </div>

    <!-- Collapsible Search Card -->
    <div class="card border rounded-2 shadow-none bg-white p-3 mb-3 {{ request('q') || request('kode_ruangan') || request('nama_ruangan') ? '' : 'd-none' }}" id="searchCard">
        <form action="/master-ruangan" method="GET" id="filterForm">
            <div class="d-flex flex-wrap gap-2 align-items-end">
                <div style="flex: 1; max-width: 320px; min-width: 200px;">
                    <label class="form-label small fw-bold text-dark mb-1">Pencarian Umum</label>
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari kode, nama, atau gedung..." 
                           value="{{ request('q') }}" style="height: 33px;">
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4; height: 33px;">
                        Search
                    </button>
                    <a href="/master-ruangan" class="btn btn-sm text-white px-2.5 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px; height: 33px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
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

    <!-- Data Table Card with Cyan Line -->
    <div class="card border rounded-2 shadow-none bg-white p-0" style="border-top: 2px solid #00c0ef !important;">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0 text-nowrap" id="tabelRuangan" style="font-size: 13px; border-bottom: 1px solid #e2e8f0;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th width="45" class="text-center" style="color: #333; font-weight: 600;">#</th>
                        <th style="color: #337ab7; font-weight: 600;">Kode Ruang</th>
                        <th style="color: #337ab7; font-weight: 600;">Nama Ruang</th>
                        <th style="color: #337ab7; font-weight: 600;">Gedung & Lantai</th>
                        <th style="color: #337ab7; font-weight: 600;">Poli Klinik</th>
                        <th style="color: #337ab7; font-weight: 600;">Ruang Ranap</th>
                        <th style="color: #337ab7; font-weight: 600;">Kapasitas Tempat Tidur</th>
                        <th width="90" class="text-end pe-3" style="color: #337ab7; font-weight: 600;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    @php
                        $gedungNama = $item->lantai && $item->lantai->gedung ? $item->lantai->gedung->nama_gedung : '';
                        $lantaiNama = $item->lantai ? $item->lantai->nama_lantai : '';
                        $gedungLantai = ($gedungNama && $lantaiNama) ? ($gedungNama . '-' . $lantaiNama) : ($gedungNama ?: ($lantaiNama ?: '-'));
                    @endphp
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center text-muted" style="font-size: 12px;">{{ $index + 1 }}</td>
                        <td class="text-dark">{{ $item->kode_ruangan }}</td>
                        <td class="text-dark">{{ $item->nama_ruangan }}</td>
                        <td class="text-dark">{{ $gedungLantai }}</td>
                        <td class="text-dark">{{ $item->poli_klinik ?? '' }}</td>
                        <td class="text-dark">{{ $item->ruang_ranap ?? '' }}</td>
                        <td class="text-dark">{{ $item->kapasitas_tempat_tidur ?? '-' }}</td>
                        <td class="text-end pe-3">
                            <a href="javascript:void(0)" class="text-decoration-none me-2 btnDetail" data-id="{{ $item->id }}" title="Lihat Detail">
                                <i class="fas fa-eye" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                            <a href="/master-ruangan/{{ $item->id }}/edit" class="text-decoration-none me-2" title="Ubah Data">
                                <i class="fas fa-pencil-alt" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="text-decoration-none btnHapus" data-id="{{ $item->id }}" data-nama="{{ $item->nama_ruangan }}" title="Hapus">
                                <i class="fas fa-trash" style="color: #337ab7; font-size: 13px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data ruangan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Detail Ruangan --}}
<div class="modal fade" id="modalDetailRuangan" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #337ab7;">
                <h6 class="modal-title fw-bold" id="modalDetailLabel">
                    <i class="fas fa-info-circle me-1"></i> Detail Data Ruangan
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <table class="table table-bordered table-sm mb-0">
                    <tbody>
                        <tr>
                            <th width="35%" class="bg-light">Kode Ruang</th>
                            <td id="detKodeRuangan" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama Ruang</th>
                            <td id="detNamaRuangan"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Gedung & Lantai</th>
                            <td id="detGedungLantai"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Poli Klinik</th>
                            <td id="detPoliKlinik"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Ruang Ranap</th>
                            <td id="detRuangRanap"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Kapasitas Tempat Tidur</th>
                            <td id="detKapasitas"></td>
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
<div class="modal fade" id="modalHapusRuangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> Konfirmasi Hapus
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus ruangan <strong id="namaHapusRuangan" class="text-danger"></strong>?
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

    // Toggle Search Card
    $('#btnToggleSearch').on('click', function () {
        $('#searchCard').toggleClass('d-none');
    });

    // View Detail
    $(document).on('click', '.btnDetail', function () {
        let id = $(this).data('id');
        $.get('/master-ruangan/' + id, function (res) {
            $('#detKodeRuangan').text(res.kode_ruangan || '-');
            $('#detNamaRuangan').text(res.nama_ruangan || '-');
            
            let g = (res.lantai && res.lantai.gedung) ? res.lantai.gedung.nama_gedung : '';
            let l = res.lantai ? res.lantai.nama_lantai : '';
            $('#detGedungLantai').text((g && l) ? (g + '-' + l) : (g || l || '-'));
            
            $('#detPoliKlinik').text(res.poli_klinik || '-');
            $('#detRuangRanap').text(res.ruang_ranap || '-');
            $('#detKapasitas').text(res.kapasitas_tempat_tidur || '-');
            $('#detStatus').html(res.is_active 
                ? '<span class="badge bg-success">Aktif</span>' 
                : '<span class="badge bg-danger">Nonaktif</span>');
            $('#modalDetailRuangan').modal('show');
        });
    });

    // Click Delete
    $(document).on('click', '.btnHapus', function () {
        hapusId = $(this).data('id');
        $('#namaHapusRuangan').text($(this).data('nama'));
        $('#modalHapusRuangan').modal('show');
    });

    // Confirm Delete
    $('#btnKonfirmasiHapus').on('click', function () {
        if (!hapusId) return;
        $.ajax({
            url: '/master-ruangan/' + hapusId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#modalHapusRuangan').modal('hide');
                showAlert('warning', res.message);
                setTimeout(() => location.reload(), 800);
            },
            error: function () {
                $('#modalHapusRuangan').modal('hide');
                showAlert('danger', 'Gagal menghapus data ruangan.');
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
