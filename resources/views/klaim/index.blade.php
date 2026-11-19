@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .klaim-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
        font-size: 12px;
    }
    .klaim-top-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .klaim-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .klaim-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .klaim-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
    }
    .klaim-breadcrumb a:hover {
        text-decoration: underline;
    }

    /* Box 1: Action Box (Orange Top) */
    .box-action-klaim {
        background: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #f39c12;
        border-radius: 2px;
        padding: 8px 12px;
        margin-bottom: 12px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    }
    .btn-klaim-orange {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: normal;
    }
    .btn-klaim-orange:hover {
        background-color: #d68407;
    }
    .btn-klaim-green {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #fff !important;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: normal;
    }
    .btn-klaim-green:hover {
        background-color: #008d4c;
    }
    .btn-klaim-reset {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #444 !important;
        font-size: 12px;
        padding: 4px 9px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-klaim-reset:hover {
        background-color: #f4f4f4;
    }
    .btn-klaim-excel {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #fff !important;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: normal;
    }
    .btn-klaim-excel:hover {
        background-color: #008d4c;
    }
    .btn-klaim-pdf {
        background-color: #dd4b39;
        border: 1px solid #d73925;
        color: #fff !important;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: normal;
    }
    .btn-klaim-pdf:hover {
        background-color: #c9302c;
    }

    /* Box 2: Table Data Box (Cyan Top) */
    .box-table-klaim {
        background: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #00c0ef;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .box-table-klaim-header {
        padding: 8px 12px;
        font-size: 12px;
        color: #444;
        border-bottom: 1px solid #f4f4f4;
    }

    /* Table Styling */
    .table-klaim {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        white-space: nowrap;
        margin: 0;
    }
    .table-klaim th {
        background-color: #fff;
        border: 1px solid #e4e7ea;
        padding: 8px 10px;
        font-weight: 700;
        color: #337ab7;
        font-size: 12px;
        vertical-align: middle;
        text-align: left;
    }
    .table-klaim th.th-center {
        text-align: center;
    }
    .table-klaim th.th-right {
        text-align: right;
    }
    .table-klaim td {
        border: 1px solid #e4e7ea;
        padding: 7px 10px;
        font-size: 12px;
        color: #333;
        vertical-align: middle;
    }
    .table-klaim tbody tr:hover {
        background-color: #f7f9fa;
    }

    /* Badges */
    .badge-status-draft {
        background-color: #777;
        color: #fff;
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 3px;
        font-weight: normal;
        display: inline-block;
    }
    .badge-status-diajukan {
        background-color: #f39c12;
        color: #fff;
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 3px;
        font-weight: normal;
        display: inline-block;
    }
    .badge-status-disetujui {
        background-color: #00a65a;
        color: #fff;
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 3px;
        font-weight: normal;
        display: inline-block;
    }

    /* Action Icons */
    .btn-act-icon {
        color: #337ab7;
        text-decoration: none;
        margin: 0 3px;
        font-size: 13px;
        cursor: pointer;
        display: inline-block;
    }
    .btn-act-icon:hover {
        color: #23527c;
    }
    .pencil-realisasi {
        color: #337ab7;
        text-decoration: none;
        margin-left: 4px;
        font-size: 11px;
        cursor: pointer;
    }
    .pencil-realisasi:hover {
        color: #23527c;
    }
</style>

<div class="klaim-wrapper">
    {{-- Header & Breadcrumbs --}}
    <div class="klaim-top-header">
        <h3 class="klaim-title">Transaksi Klaim Pasien</h3>
        <div class="klaim-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 4px;">&gt;</span>
            <span style="color:#777;">Transaksi Klaim Pasien</span>
        </div>
    </div>

    {{-- Box 1: Action Box --}}
    <div class="box-action-klaim">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn-klaim-orange" id="btnToggleFilter">
                    <i class="fas fa-search" style="font-size: 11px;"></i> Cari data
                </button>
                <button type="button" class="btn-klaim-green" data-bs-toggle="modal" data-bs-target="#modalBuatTagihan">
                    <i class="fas fa-plus" style="font-size: 11px;"></i> + Buat tagihan
                </button>
                <a href="{{ route('klaim.index') }}" class="btn-klaim-reset" title="Reset">
                    <i class="fas fa-sync-alt" style="font-size: 11px;"></i>
                </a>
            </div>
            <div class="d-flex align-items-center gap-1">
                <a href="{{ route('klaim.exportExcel') }}" class="btn-klaim-excel">
                    <i class="fas fa-file-excel" style="font-size: 11px;"></i> Export Excel
                </a>
                <a href="{{ route('klaim.exportPdf') }}" target="_blank" class="btn-klaim-pdf">
                    <i class="fas fa-file-pdf" style="font-size: 11px;"></i> Export PDF
                </a>
            </div>
        </div>

        {{-- Filter Collapsible Drawer --}}
        <div id="filterSection" style="display: {{ ($q || $statusKlaim || $penjaminId || $tglAwal || $tglAkhir) ? 'block' : 'none' }}; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #d2d6de;">
            <form action="{{ route('klaim.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label mb-1" style="font-size: 11px; font-weight: 600; color: #555;">No Klaim / Kata Kunci</label>
                    <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="KL...">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1" style="font-size: 11px; font-weight: 600; color: #555;">Status Klaim</label>
                    <select name="status_klaim" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="Draft" {{ $statusKlaim == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Diajukan" {{ $statusKlaim == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="Disetujui" {{ $statusKlaim == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1" style="font-size: 11px; font-weight: 600; color: #555;">Penjamin</label>
                    <select name="penjamin_id" class="form-select form-select-sm">
                        <option value="">-- Semua Penjamin --</option>
                        @foreach($penjamins as $pj)
                            <option value="{{ $pj->id }}" {{ $penjaminId == $pj->id ? 'selected' : '' }}>{{ $pj->nama_penjamin }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1" style="font-size: 11px; font-weight: 600; color: #555;">Tgl Klaim Awal</label>
                    <input type="date" name="tgl_awal" value="{{ $tglAwal }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1" style="font-size: 11px; font-weight: 600; color: #555;">Tgl Klaim Akhir</label>
                    <input type="date" name="tgl_akhir" value="{{ $tglAkhir }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100" style="font-size: 12px; padding: 4px 8px;">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Box 2: Table Data Box --}}
    <div class="box-table-klaim">
        <div class="box-table-klaim-header">
            Menampilkan {{ $klaims->firstItem() ?? 0 }}-{{ $klaims->lastItem() ?? 0 }} dari {{ $klaims->total() }} item.
        </div>

        <div class="table-responsive" style="margin: 0; padding: 0;">
            <table class="table-klaim">
                <thead>
                    <tr>
                        <th class="th-center" style="width: 35px; color: #333;">#</th>
                        <th class="th-center" style="width: 100px;">Status Klaim</th>
                        <th style="width: 105px;">
                            Tgl Klaim <i class="fas fa-sort" style="color: #337ab7; font-size: 11px; margin-left: 2px;"></i>
                        </th>
                        <th style="width: 100px;">No Klaim</th>
                        <th style="width: 140px;">Penjamin</th>
                        <th style="width: 170px;">Tgl. Transaksi</th>
                        <th style="width: 80px;">Jml.Hari</th>
                        <th class="th-right" style="width: 90px;">Subtotal</th>
                        <th class="th-right" style="width: 90px;">Diskon (Rp)</th>
                        <th class="th-right" style="width: 105px;">Total Tagihan</th>
                        <th class="th-right" style="width: 110px;">Total Realisasi</th>
                        <th class="th-right" style="width: 105px;">Selisih</th>
                        <th class="th-center" style="width: 85px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($klaims as $idx => $item)
                    <tr>
                        <td style="text-align: center; color: #555;">{{ $klaims->firstItem() + $idx }}</td>
                        <td style="text-align: center;">
                            @if($item->status_klaim == 'Draft')
                                <span class="badge-status-draft">Draft</span>
                            @elseif($item->status_klaim == 'Diajukan')
                                <span class="badge-status-diajukan">Diajukan</span>
                            @elseif($item->status_klaim == 'Disetujui')
                                <span class="badge-status-disetujui">Disetujui</span>
                            @else
                                <span class="badge bg-secondary">{{ $item->status_klaim }}</span>
                            @endif
                        </td>
                        <td>{{ $item->tgl_klaim ? $item->tgl_klaim->format('d/m/Y') : '-' }}</td>
                        <td style="color: #333;">{{ $item->no_klaim }}</td>
                        <td style="color: #333;">{{ $item->nama_penjamin ?? ($item->penjamin->nama_penjamin ?? '-') }}</td>
                        <td style="color: #333;">
                            @if($item->tgl_transaksi_awal && $item->tgl_transaksi_akhir)
                                {{ $item->tgl_transaksi_awal->format('d/m/Y') }} - {{ $item->tgl_transaksi_akhir->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="color: #333;">{{ $item->jml_hari }} Hari</td>
                        <td style="text-align: right;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        <td style="text-align: right;">{{ number_format($item->diskon, 0, ',', '.') }}</td>
                        <td style="text-align: right;">{{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                        <td style="text-align: right;">
                            <span id="txt-realisasi-{{ $item->id }}">{{ number_format($item->total_realisasi, 0, ',', '.') }}</span>
                            <a href="javascript:void(0)" class="pencil-realisasi" onclick="quickEditRealisasi({{ $item->id }}, {{ $item->total_realisasi }})" title="Ubah Total Realisasi">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                        </td>
                        <td style="text-align: right;" id="txt-selisih-{{ $item->id }}">
                            {{ number_format($item->selisih, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            <a href="javascript:void(0)" class="btn-act-icon" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn-act-icon" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}" title="Edit Data">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn-act-icon" onclick="deleteKlaim({{ $item->id }}, '{{ $item->no_klaim }}')" title="Hapus Klaim">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center py-4 text-muted">
                            Tidak ada data klaim yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Hidden Form for Delete Action --}}
<form id="formDeleteKlaim" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Modals Container (Placed outside table to prevent browser DOM distortion) --}}
@foreach($klaims as $item)
    {{-- Modal Detail --}}
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 3px; font-size: 12px;">
                <div class="modal-header py-2" style="background-color: #f7f9fa; border-bottom: 1px solid #d2d6de;">
                    <h6 class="modal-title font-weight-bold" style="font-size: 13px; color: #333;">
                        Detail Transaksi Klaim: {{ $item->no_klaim }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td style="width: 130px; font-weight: 600; color: #555;">No Klaim</td>
                            <td>: <strong style="color: #333;">{{ $item->no_klaim }}</strong></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Status Klaim</td>
                            <td>: 
                                @if($item->status_klaim == 'Draft')
                                    <span class="badge-status-draft">Draft</span>
                                @elseif($item->status_klaim == 'Diajukan')
                                    <span class="badge-status-diajukan">Diajukan</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status_klaim }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Penjamin</td>
                            <td>: {{ $item->nama_penjamin }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Tgl Klaim</td>
                            <td>: {{ $item->tgl_klaim ? $item->tgl_klaim->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Periode Transaksi</td>
                            <td>: {{ $item->tgl_transaksi_awal ? $item->tgl_transaksi_awal->format('d/m/Y') : '' }} s/d {{ $item->tgl_transaksi_akhir ? $item->tgl_transaksi_akhir->format('d/m/Y') : '' }} ({{ $item->jml_hari }} Hari)</td>
                        </tr>
                        <tr style="border-top: 1px solid #eee;">
                            <td style="font-weight: 600; color: #555;">Subtotal</td>
                            <td>: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Diskon (Rp)</td>
                            <td>: Rp {{ number_format($item->diskon, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Total Tagihan</td>
                            <td style="font-weight: 700; color: #00a65a;">: Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Total Realisasi</td>
                            <td style="font-weight: 700; color: #337ab7;">: Rp {{ number_format($item->total_realisasi, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Selisih</td>
                            <td style="font-weight: 700; color: #dd4b39;">: Rp {{ number_format($item->selisih, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Keterangan</td>
                            <td>: {{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer py-2" style="background-color: #f7f9fa; border-top: 1px solid #d2d6de;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: 12px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 3px; font-size: 12px;">
                <form action="{{ route('klaim.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header py-2" style="background-color: #f7f9fa; border-bottom: 1px solid #d2d6de;">
                        <h6 class="modal-title font-weight-bold" style="font-size: 13px; color: #333;">Edit Data Klaim: {{ $item->no_klaim }}</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="mb-2">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Status Klaim</label>
                            <select name="status_klaim" class="form-select form-select-sm">
                                <option value="Draft" {{ $item->status_klaim == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Diajukan" {{ $item->status_klaim == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="Disetujui" {{ $item->status_klaim == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Penjamin</label>
                            <select name="penjamin_id" class="form-select form-select-sm" required>
                                @foreach($penjamins as $pj)
                                    <option value="{{ $pj->id }}" {{ $item->penjamin_id == $pj->id ? 'selected' : '' }}>{{ $pj->nama_penjamin }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label mb-1 font-weight-bold" style="color: #444;">Total Tagihan (Rp)</label>
                                <input type="number" step="any" name="total_tagihan" value="{{ $item->total_tagihan }}" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label mb-1 font-weight-bold" style="color: #444;">Total Realisasi (Rp)</label>
                                <input type="number" step="any" name="total_realisasi" value="{{ $item->total_realisasi }}" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="form-control form-control-sm">{{ $item->keterangan }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer py-2" style="background-color: #f7f9fa; border-top: 1px solid #d2d6de;">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: 12px;">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm" style="font-size: 12px;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- Modal Buat Tagihan --}}
<div class="modal fade" id="modalBuatTagihan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 3px; font-size: 12px;">
            <form action="{{ route('klaim.store') }}" method="POST">
                @csrf
                <div class="modal-header py-2" style="background-color: #f7f9fa; border-bottom: 1px solid #d2d6de;">
                    <h6 class="modal-title font-weight-bold" style="font-size: 13px; color: #333;">+ Buat Tagihan Klaim Pasien</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">No. Klaim</label>
                            <input type="text" name="no_klaim" value="KL{{ date('ymd') }}{{ rand(10, 99) }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Status Klaim</label>
                            <select name="status_klaim" class="form-select form-select-sm">
                                <option value="Draft">Draft</option>
                                <option value="Diajukan" selected>Diajukan</option>
                                <option value="Disetujui">Disetujui</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Tgl Klaim</label>
                            <input type="date" name="tgl_klaim" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Penjamin</label>
                            <select name="penjamin_id" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Penjamin --</option>
                                @foreach($penjamins as $pj)
                                    <option value="{{ $pj->id }}">{{ $pj->nama_penjamin }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Tgl Transaksi Awal</label>
                            <input type="date" name="tgl_transaksi_awal" value="{{ date('Y-m-01') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Tgl Transaksi Akhir</label>
                            <input type="date" name="tgl_transaksi_akhir" value="{{ date('Y-m-d') }}" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Subtotal (Rp)</label>
                            <input type="number" step="any" name="subtotal" id="inpSubtotal" value="0" class="form-control form-control-sm" oninput="calcTagihan()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Diskon (Rp)</label>
                            <input type="number" step="any" name="diskon" id="inpDiskon" value="0" class="form-control form-control-sm" oninput="calcTagihan()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Total Tagihan (Rp)</label>
                            <input type="number" step="any" name="total_tagihan" id="inpTagihan" value="0" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 font-weight-bold" style="color: #444;">Total Realisasi (Rp)</label>
                            <input type="number" step="any" name="total_realisasi" id="inpRealisasi" value="0" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mb-1 font-weight-bold" style="color: #444;">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control form-control-sm" placeholder="Catatan tagihan klaim..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2" style="background-color: #f7f9fa; border-top: 1px solid #d2d6de;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: 12px;">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" style="font-size: 12px; background-color: #00a65a; border-color: #008d4c;">
                        <i class="fas fa-save me-1"></i> Simpan Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnToggle = document.getElementById('btnToggleFilter');
        const filterSection = document.getElementById('filterSection');
        if (btnToggle && filterSection) {
            btnToggle.addEventListener('click', function() {
                if (filterSection.style.display === 'none' || filterSection.style.display === '') {
                    filterSection.style.display = 'block';
                } else {
                    filterSection.style.display = 'none';
                }
            });
        }
    });

    function calcTagihan() {
        const subtotal = parseFloat(document.getElementById('inpSubtotal').value) || 0;
        const diskon = parseFloat(document.getElementById('inpDiskon').value) || 0;
        const total = Math.max(0, subtotal - diskon);
        document.getElementById('inpTagihan').value = total;
    }

    function openDetailModal(id) {
        const modalEl = document.getElementById('modalDetail' + id);
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function openEditModal(id) {
        const modalEl = document.getElementById('modalEdit' + id);
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function deleteKlaim(id, noKlaim) {
        if (confirm('Apakah Anda yakin ingin menghapus klaim ' + noKlaim + '?')) {
            const form = document.getElementById('formDeleteKlaim');
            form.action = '/klaim/' + id;
            form.submit();
        }
    }

    function quickEditRealisasi(id, currentVal) {
        const input = prompt("Masukkan Total Realisasi baru (Rp):", currentVal);
        if (input !== null) {
            const num = parseFloat(input);
            if (isNaN(num)) {
                alert("Nilai harus berupa angka!");
                return;
            }

            fetch('/klaim/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ total_realisasi: num })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const formatted = new Intl.NumberFormat('id-ID').format(num);
                    document.getElementById('txt-realisasi-' + id).textContent = formatted;
                    document.getElementById('txt-selisih-' + id).textContent = data.selisih;
                } else {
                    alert("Gagal memperbarui total realisasi.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan saat menyimpan data.");
            });
        }
    }
</script>
@endsection
