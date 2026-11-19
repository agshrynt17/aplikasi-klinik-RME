@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-unit-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .konhakes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }

    /* Action Box */
    .card-action-box {
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        padding: 8px 12px;
        margin-bottom: 12px;
    }
    .btn-orange-cari {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-orange-cari:hover {
        background-color: #d68407;
    }
    .btn-reset-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-reset-light:hover {
        background-color: #f4f4f4;
    }
    .btn-green-tambah {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-green-tambah:hover {
        background-color: #008d4c;
    }
    .btn-cyan-tarik {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-cyan-tarik:hover {
        background-color: #00acd6;
    }

    /* Cyan Line */
    .cyan-divider {
        border-top: 2px solid #00c0ef;
        margin-top: 8px;
        margin-bottom: 8px;
    }

    .meta-info-line {
        font-size: 12px;
        color: #555;
        margin-bottom: 6px;
    }

    /* Table */
    .table-satusehat-unit {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 12px;
    }
    .table-satusehat-unit th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 8px 8px;
        font-weight: 700;
        color: #337ab7;
        font-size: 12px;
        text-align: left;
    }
    .table-satusehat-unit td {
        border: 1px solid #e4e7ea;
        padding: 6px 8px;
        vertical-align: middle;
        color: #333;
        font-size: 12px;
    }
    .table-satusehat-unit tbody tr:hover {
        background-color: #f5f5f5;
    }

    .btn-detail-cyan {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-detail-cyan:hover {
        background-color: #00acd6;
        color: #fff !important;
    }
    .badge-aktif {
        background-color: #00a65a;
        color: #fff;
        font-size: 10px;
        font-weight: normal;
        padding: 2px 6px;
        border-radius: 2px;
        display: inline-block;
    }
    .link-unit-aplikasi {
        color: #a94442 !important;
        text-decoration: none;
        font-weight: 500;
    }
    .link-unit-aplikasi:hover {
        text-decoration: underline;
    }
</style>

<div class="satusehat-unit-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Setting Data Unit Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-unit/index">Setting Data Unit Satu Sehat</a>
        </div>
    </div>

    {{-- Alert sukses / info --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <div id="alertAjax" class="alert d-none py-2 px-3 small my-2" role="alert"></div>

    {{-- Action Box --}}
    <div class="card-action-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn-orange-cari" id="btnToggleFilter">
                    <i class="fas fa-search"></i> Cari data
                </button>
                <a href="/satusehat-unit/index" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
                <a href="/satusehat-unit/create" class="btn-green-tambah">
                    <i class="fas fa-plus"></i> Tambah Unit
                </a>
            </div>
            <div>
                <button type="button" class="btn-cyan-tarik" id="btnTarikDataUnit">
                    <i class="fas fa-download"></i> Tarik Data Unit Dari Satu Sehat
                </button>
            </div>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterBox" class="mt-2 pt-2 border-top {{ request('q') ? '' : 'd-none' }}">
            <form method="GET" action="/satusehat-unit/index" class="d-flex align-items-center gap-2">
                <input type="text" name="q" class="form-control form-control-sm" style="max-width: 300px; height: 30px; font-size: 12px;"
                       placeholder="Cari Unit Satu Sehat atau Aplikasi..." value="{{ request('q') }}">
                <button type="submit" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 12px;">Cari</button>
                <a href="/satusehat-unit/index" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 12px;">Reset</a>
            </form>
        </div>
    </div>

    {{-- Cyan Line Divider --}}
    <div class="cyan-divider"></div>

    {{-- Meta Count Info --}}
    <div class="meta-info-line">
        Menampilkan 1-{{ $total }} dari {{ $total }} item.
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-satusehat-unit">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center; color: #333;">#</th>
                    <th style="width: 80px;"></th>
                    <th>Unit Pada (Satu Sehat)</th>
                    <th>Unit Pada (Aplikasi)</th>
                    <th style="width: 80px;">Status</th>
                    <th style="width: 170px;">Diubah Pada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $index => $item)
                @php
                    $diubah = $item->diubah_pada ? \Carbon\Carbon::parse($item->diubah_pada)->format('d-m-Y H:i:s') : '25-12-2023 10:04:44';
                @endphp
                <tr id="row-unit-{{ $item->id }}">
                    <td style="text-align: center; color: #555;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        <a href="/satusehat-unit/view?id={{ $item->id }}" class="btn-detail-cyan">
                            <i class="fas fa-info-circle"></i> Detail
                        </a>
                    </td>
                    <td style="font-weight: 500;">{{ $item->unit_satusehat }}</td>
                    <td>
                        <a href="#" class="link-unit-aplikasi btnTriggerEditUnit"
                           data-id="{{ $item->id }}"
                           data-satusehat="{{ $item->unit_satusehat }}"
                           data-aplikasi="{{ $item->unit_aplikasi }}">
                            <i class="fas fa-pencil-alt text-danger me-1"></i> <span class="text-unit-val">{{ $item->unit_aplikasi }}</span>
                        </a>
                    </td>
                    <td>
                        <span class="badge-aktif">{{ $item->status }}</span>
                    </td>
                    <td>{{ $diubah }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data unit layanan Satu Sehat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Ubah Unit Pada Aplikasi --}}
<div class="modal fade" id="modalEditUnit" tabindex="-1" aria-labelledby="modalEditUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #f39c12; color: #fff;">
                <h6 class="modal-title fw-bold" id="modalEditUnitLabel" style="font-size: 14px;">
                    <i class="fas fa-pencil-alt me-1"></i> Ubah Unit Pada Aplikasi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditUnit" method="POST" action="/satusehat-unit/update">
                @csrf
                <input type="hidden" name="id" id="editUnitId">
                <div class="modal-body p-3" style="font-size: 13px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Unit Pada Satu Sehat</label>
                        <input type="text" id="editUnitSatuSehat" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Unit Pada Aplikasi</label>
                        <select name="unit_aplikasi" id="editUnitAplikasi" class="form-select form-select-sm">
                            @foreach($unitLayanans as $ul)
                                <option value="{{ $ul->nama_unit }}">{{ $ul->nama_unit }} ({{ $ul->keterangan }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3" style="background: #f9f9f9;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" style="background-color: #00a65a; border-color: #008d4c;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah Unit --}}
<div class="modal fade" id="modalTambahUnit" tabindex="-1" aria-labelledby="modalTambahUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #00a65a; color: #fff;">
                <h6 class="modal-title fw-bold" id="modalTambahUnitLabel" style="font-size: 14px;">
                    <i class="fas fa-plus me-1"></i> Tambah Data Unit Satu Sehat
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/satusehat-unit/store">
                @csrf
                <div class="modal-body p-3" style="font-size: 13px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Unit Pada Satu Sehat</label>
                        <input type="text" name="unit_satusehat" class="form-control form-control-sm" placeholder="Contoh: Klinik Telagasari 24 Jam" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Unit Pada Aplikasi</label>
                        <select name="unit_aplikasi" class="form-select form-select-sm">
                            @foreach($unitLayanans as $ul)
                                <option value="{{ $ul->nama_unit }}">{{ $ul->nama_unit }} ({{ $ul->keterangan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Location ID Satu Sehat (Opsional)</label>
                        <input type="text" name="location_id_satusehat" class="form-control form-control-sm" placeholder="Location ID / Organization ID...">
                    </div>
                </div>
                <div class="modal-footer py-2 px-3" style="background: #f9f9f9;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Unit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Toggle Filter
    $(document).on('click', '#btnToggleFilter', function () {
        $('#filterBox').toggleClass('d-none');
    });

    // Modal Edit Unit
    $(document).on('click', '.btnTriggerEditUnit', function (e) {
        e.preventDefault();
        $('#editUnitId').val($(this).data('id'));
        $('#editUnitSatuSehat').val($(this).data('satusehat'));
        $('#editUnitAplikasi').val($(this).data('aplikasi'));
        let modalEl = document.getElementById('modalEditUnit');
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else {
            $('#modalEditUnit').modal('show');
        }
    });

    // Modal Tambah
    $(document).on('click', '#btnOpenModalTambah', function () {
        let modalEl = document.getElementById('modalTambahUnit');
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else {
            $('#modalTambahUnit').modal('show');
        }
    });

    // Tarik Data Unit Dari Satu Sehat
    $(document).on('click', '#btnTarikDataUnit', function () {
        const $btnTarik = $(this);
        $btnTarik.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menarik data...');
        $.ajax({
            url: '/satusehat-unit/sync',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(data) {
                $btnTarik.prop('disabled', false).html('<i class="fas fa-download"></i> Tarik Data Unit Dari Satu Sehat');
                $('#alertAjax').attr('class', 'alert alert-success py-2 px-3 small my-2')
                    .html('<i class="fas fa-check-circle me-1"></i> ' + (data.message || 'Berhasil menarik data unit dari Satu Sehat.'))
                    .removeClass('d-none');
                setTimeout(() => $('#alertAjax').addClass('d-none'), 4000);
            },
            error: function() {
                $btnTarik.prop('disabled', false).html('<i class="fas fa-download"></i> Tarik Data Unit Dari Satu Sehat');
                $('#alertAjax').attr('class', 'alert alert-danger py-2 px-3 small my-2')
                    .html('<i class="fas fa-exclamation-triangle me-1"></i> Terjadi kesalahan saat menarik data dari Satu Sehat.')
                    .removeClass('d-none');
            }
        });
    });
});
</script>
@endsection
