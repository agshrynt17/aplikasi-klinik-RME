@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Pengaturan Tarif Tindakan - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="data-tarif-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.2rem; letter-spacing: 0.3px;">Pengaturan Tarif Tindakan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Pengaturan Tarif Tindakan</li>
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
        <form action="/master-tarif-tindakan" method="GET" id="filterForm">
            <div class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label class="form-label small text-dark mb-1" style="font-size: 12px; font-weight: 500;">PoliKlinik</label>
                    <select name="poliklinik_id" class="form-select form-select-sm" style="height: 33px; font-size: 12px; min-width: 140px;">
                        <option value="">--Pilih--</option>
                        @foreach($polikliniks as $poli)
                            <option value="{{ $poli->id }}" {{ request('poliklinik_id') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label small text-dark mb-1" style="font-size: 12px; font-weight: 500;">Penjamin</label>
                    <select name="penjamin_id" class="form-select form-select-sm" style="height: 33px; font-size: 12px; min-width: 140px;">
                        <option value="">--Pilih--</option>
                        @foreach($penjamins as $pj)
                            <option value="{{ $pj->id }}" {{ request('penjamin_id') == $pj->id ? 'selected' : '' }}>
                                {{ $pj->nama_penjamin }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label small text-dark mb-1" style="font-size: 12px; font-weight: 500;">Nama Tindakan</label>
                    <input type="text" name="nama_tindakan" class="form-control form-control-sm" placeholder="Cari" 
                           value="{{ request('nama_tindakan') }}" style="height: 33px; font-size: 12px; width: 150px;">
                </div>

                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" 
                            style="background-color: #337ab7; border-color: #2e6da4; height: 33px; font-size: 12px; border-radius: 3px;">
                        Search
                    </button>
                    <a href="/master-tarif-tindakan" class="btn btn-sm btn-light border text-dark px-3 fw-semibold d-inline-flex align-items-center" 
                       style="height: 33px; font-size: 12px; border-radius: 3px;" title="Reset Filter">
                        Reset
                    </a>
                </div>

                <div class="d-flex gap-2 ms-auto">
                    <a href="/master-tarif-tindakan/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                       style="background-color: #00a65a; border-color: #008d4c; height: 33px; font-size: 12px; border-radius: 3px;">
                        <i class="fas fa-plus me-1"></i> Buat Tarif
                    </a>
                    <a href="/master-tarif-tindakan/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                       style="background-color: #f39c12; border-color: #e08e0b; height: 33px; font-size: 12px; border-radius: 3px;">
                        <i class="fas fa-plus me-1"></i> Ubah Tarif
                    </a>
                    <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                            data-bs-toggle="modal" data-bs-target="#modalImportTarif"
                            style="background-color: #337ab7; border-color: #2e6da4; height: 33px; font-size: 12px; border-radius: 3px;">
                        <i class="fas fa-upload me-1"></i> Import Tarif Dari File
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
            <table class="table table-hover table-striped align-middle mb-0 text-nowrap" id="tabelTarif" style="font-size: 12.5px; border-bottom: 1px solid #e2e8f0;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th width="40" class="text-center" style="color: #333; font-weight: 600;">#</th>
                        <th style="color: #337ab7; font-weight: 600;">Poli Klinik <i class="fas fa-sort-alpha-down fa-xs"></i></th>
                        <th style="color: #337ab7; font-weight: 600;">Nama Penjamin</th>
                        <th style="color: #337ab7; font-weight: 600;">Nama Tindakan <i class="fas fa-sort-alpha-down fa-xs"></i></th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Unitcost</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Jasa Medis</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Jasa Paramedis</th>
                        <th class="text-end" style="color: #337ab7; font-weight: 600;">Margin</th>
                        <th class="text-end" style="color: #337ab7; font-weight: 600;">Total Tarif</th>
                        <th width="40" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center fw-bold" style="color: #555;">{{ $data->firstItem() + $index }}</td>
                        <td style="color: #333;">
                            {{ $item->tindakan && $item->tindakan->poliklinik ? $item->tindakan->poliklinik->nama_poli : '-' }}
                        </td>
                        <td style="color: #333;">
                            {{ $item->penjamin ? $item->penjamin->nama_penjamin : 'Pribadi' }}
                        </td>
                        <td style="color: #333; font-weight: 500;">
                            {{ $item->tindakan ? $item->tindakan->nama_tindakan : '-' }}
                        </td>
                        <td class="text-center" style="color: #333;">
                            {{ $item->unit_cost > 0 ? number_format($item->unit_cost, 0, ',', '.') : '0' }}
                        </td>
                        <td class="text-center" style="color: #333;">
                            {{ number_format($item->jasa_medis, 0, ',', '.') }}
                        </td>
                        <td class="text-center" style="color: #333;">
                            {{ number_format($item->jasa_paramedis, 0, ',', '.') }}
                        </td>
                        <td class="text-end" style="color: #333;">
                            {{ number_format($item->margin, 0, ',', '.') }}
                        </td>
                        <td class="text-end fw-semibold" style="color: #333;">
                            {{ number_format($item->total_tarif, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" class="text-info btn-delete text-decoration-none" 
                               data-id="{{ $item->id }}" 
                               data-nama="{{ ($item->tindakan ? $item->tindakan->nama_tindakan : 'Tarif') . ' (' . ($item->penjamin ? $item->penjamin->nama_penjamin : 'Pribadi') . ')' }}" 
                               title="Hapus Tarif">
                                <i class="fas fa-trash" style="font-size: 13px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i> Belum ada data Pengaturan Tarif Tindakan.
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

{{-- Modal Hapus Tarif --}}
<div class="modal fade" id="modalHapusTarif" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content rounded-2 border-0 shadow">
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-exclamation-triangle me-1"></i> Konfirmasi Hapus</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 px-3 text-dark" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus <strong id="namaHapusTarif"></strong>?
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

{{-- Modal Import CSV --}}
<div class="modal fade" id="modalImportTarif" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content rounded-2 border-0 shadow">
            <div class="modal-header py-2 px-3" style="background-color: #337ab7; color: white;">
                <h6 class="modal-title fw-bold"><i class="fas fa-file-excel me-1"></i> Import Tarif Dari File</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <p class="text-muted mb-2">Pilih file Excel/CSV data tarif tindakan:</p>
                <input type="file" class="form-control form-control-sm mb-2" accept=".xlsx,.xls,.csv">
                <small class="text-muted">Format kolom: Kode Tindakan, Kode Penjamin, Unit Cost, Jasa Medis, Jasa Paramedis, Margin, Total Tarif.</small>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="alert('Fitur import tarif siap digunakan!')">
                    <i class="fas fa-upload me-1"></i> Upload & Import
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentTarifId = null;

    $(document).on('click', '.btn-delete', function() {
        currentTarifId = $(this).data('id');
        let nama = $(this).data('nama');
        $('#namaHapusTarif').text(nama);
        $('#modalHapusTarif').modal('show');
    });

    $('#btnKonfirmasiHapus').on('click', function() {
        if (!currentTarifId) return;
        $.ajax({
            url: '/master-tarif-tindakan/' + currentTarifId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                $('#modalHapusTarif').modal('hide');
                showAlert('success', res.message);
                $(`tr[data-id="${currentTarifId}"]`).fadeOut(300, function() {
                    $(this).remove();
                });
            },
            error: function() {
                alert('Gagal menghapus data tarif.');
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
