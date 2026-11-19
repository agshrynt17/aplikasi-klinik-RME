@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Setting Data Tindakan - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="data-tindakan-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.2rem; letter-spacing: 0.3px;">Setting Data Tindakan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Setting Data Tindakan</a></li>
            </ol>
        </nav>
    </div>

    <!-- Cyan Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #00c0ef;"></div>

    {{-- Alert Box --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size: 13px;">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div id="alertBox" class="alert d-none py-2 px-3 mb-3" role="alert" style="font-size: 13px;"></div>

    <!-- Filter Bar & Action Buttons -->
    <div class="card border rounded-1 shadow-none bg-white p-3 mb-3">
        <form action="/master-tindakan" method="GET" id="filterForm">
            <div class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label class="form-label small text-dark mb-1" style="font-size: 12px; font-weight: 500;">PoliKlinik</label>
                    <select name="poliklinik_id" class="form-select form-select-sm" style="height: 33px; font-size: 12px; min-width: 130px;">
                        <option value="">--Pilih--</option>
                        @foreach($polikliniks as $poli)
                            <option value="{{ $poli->id }}" {{ request('poliklinik_id') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label small text-dark mb-1" style="font-size: 12px; font-weight: 500;">Nama Tindakan</label>
                    <input type="text" name="nama_tindakan" class="form-control form-control-sm" 
                           value="{{ request('nama_tindakan') }}" style="height: 33px; font-size: 12px; width: 140px;">
                </div>

                <div>
                    <label class="form-label small text-dark mb-1" style="font-size: 12px; font-weight: 500;">Status</label>
                    <select name="status" class="form-select form-select-sm" style="height: 33px; font-size: 12px; width: 120px;">
                        <option value="">--Semua--</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="form-label small text-dark mb-1" style="font-size: 12px; font-weight: 500;">Tindakan Ranap</label>
                    <select name="is_ranap" class="form-select form-select-sm" style="height: 33px; font-size: 12px; width: 120px;">
                        <option value="">--Semua--</option>
                        <option value="1" {{ request('is_ranap') === '1' ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ request('is_ranap') === '0' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white d-inline-flex align-items-center justify-content-center" 
                            style="background-color: #f39c12; border-color: #e08e0b; height: 33px; width: 34px; border-radius: 3px;" title="Cari">
                        <i class="fas fa-search" style="font-size: 12px;"></i>
                    </button>
                    <a href="/master-tindakan" class="btn btn-sm btn-light border text-dark d-inline-flex align-items-center justify-content-center" 
                       style="height: 33px; width: 34px; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt" style="font-size: 12px;"></i>
                    </a>
                </div>

                <div class="d-flex gap-2 ms-auto">
                    <a href="/master-tindakan/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                       style="background-color: #00a65a; border-color: #008d4c; height: 33px; font-size: 12px; border-radius: 3px;">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </a>
                    <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                            data-bs-toggle="modal" data-bs-target="#modalImportCsv"
                            style="background-color: #337ab7; border-color: #2e6da4; height: 33px; font-size: 12px; border-radius: 3px;">
                        <i class="fas fa-upload me-1"></i> Import Data Dari CSV
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Total Item Count -->
    <div class="mb-2 text-dark" style="font-size: 13px;">
        Menampilkan <strong>{{ $data->firstItem() ?? 0 }}-{{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> item.
    </div>

    <!-- Data Table Card with Cyan Line -->
    <div class="card border rounded-1 shadow-none bg-white p-0" style="border-top: 2px solid #00c0ef !important;">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0 text-nowrap" id="tabelTindakan" style="font-size: 12.5px; border-bottom: 1px solid #e2e8f0;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th width="40" class="text-center" style="color: #333; font-weight: 600;">#</th>
                        <th width="50" class="text-center"></th>
                        <th style="color: #337ab7; font-weight: 600;">Kode Klinik <i class="fas fa-sort-alpha-down fa-xs"></i></th>
                        <th style="color: #337ab7; font-weight: 600;">Kode Tindakan</th>
                        <th style="color: #337ab7; font-weight: 600;">Nama Tindakan <i class="fas fa-sort-alpha-down fa-xs"></i></th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Unitcost</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Konsultasi ?</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Informed Consett?</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Tindakan Pada Ranap</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center fw-bold" style="color: #555;">{{ $data->firstItem() + $index }}</td>
                        <td class="text-center">
                            <a href="/master-tindakan/{{ $item->id }}/edit" class="text-primary me-2 text-decoration-none" title="Edit">
                                <i class="fas fa-pencil-alt" style="font-size: 12px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="text-primary btn-delete text-decoration-none" 
                               data-id="{{ $item->id }}" data-nama="{{ $item->nama_tindakan }}" title="Hapus">
                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                            </a>
                        </td>
                        <td>
                            @if($item->poliklinik)
                                <span style="color: #333;">{{ $item->poliklinik->nama_poli }}</span>
                            @else
                                <span style="color: #dd4b39; font-style: italic;">(belum diset)</span>
                            @endif
                        </td>
                        <td style="color: #333;">{{ $item->kode_tindakan }}</td>
                        <td style="color: #333; font-weight: 500;">{{ $item->nama_tindakan }}</td>
                        <td class="text-center" style="color: #333;">{{ (int)$item->unit_cost }}</td>
                        <td class="text-center">
                            @if($item->is_konsultasi)
                                <span class="fw-bold text-dark" style="font-size: 13px;">&#10004;</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_informed_consent)
                                <span class="fw-bold text-dark" style="font-size: 13px;">&#10004;</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_ranap)
                                <span class="fw-bold text-dark" style="font-size: 13px;">&#10004;</span>
                            @endif
                        </td>
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
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i> Belum ada data Tindakan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="px-3 py-2 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Halaman {{ $data->currentPage() }} dari {{ $data->lastPage() }}
            </small>
            <div>
                {{ $data->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal Hapus Tindakan --}}
<div class="modal fade" id="modalHapusTindakan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content rounded-2 border-0 shadow">
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-exclamation-triangle me-1"></i> Konfirmasi Hapus</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 px-3 text-dark" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus tindakan <strong id="namaHapusTindakan"></strong>?
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

{{-- Modal Import CSV Dummy --}}
<div class="modal fade" id="modalImportCsv" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content rounded-2 border-0 shadow">
            <div class="modal-header py-2 px-3" style="background-color: #337ab7; color: white;">
                <h6 class="modal-title fw-bold"><i class="fas fa-file-csv me-1"></i> Import Tindakan Dari CSV</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <p class="text-muted mb-2">Pilih file CSV berformat data tindakan untuk diimpor ke sistem:</p>
                <input type="file" class="form-control form-control-sm mb-2" accept=".csv">
                <small class="text-muted">Format kolom: Kode Tindakan, Nama Tindakan, Kode Poli, Unit Cost, Konsultasi, Informed Consent, Ranap.</small>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="alert('Fitur import CSV siap digunakan!')">
                    <i class="fas fa-upload me-1"></i> Upload & Import
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentTindakanId = null;

    $(document).on('click', '.btn-delete', function() {
        currentTindakanId = $(this).data('id');
        let nama = $(this).data('nama');
        $('#namaHapusTindakan').text(nama);
        $('#modalHapusTindakan').modal('show');
    });

    $('#btnKonfirmasiHapus').on('click', function() {
        if (!currentTindakanId) return;
        $.ajax({
            url: '/master-tindakan/' + currentTindakanId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                $('#modalHapusTindakan').modal('hide');
                showAlert('success', res.message);
                $(`tr[data-id="${currentTindakanId}"]`).fadeOut(300, function() {
                    $(this).remove();
                });
            },
            error: function() {
                alert('Gagal menghapus data tindakan.');
            }
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
