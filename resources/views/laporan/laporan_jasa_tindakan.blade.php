@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .laporan-jasa-tindakan-page {
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
    .btn-cyan-cetak {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-cyan-cetak:hover {
        background-color: #00acd6;
    }

    /* Cyan Line */
    .cyan-divider {
        border-top: 2px solid #00c0ef;
        margin-top: 8px;
        margin-bottom: 12px;
    }

    /* Table */
    .table-responsive-tindakan {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #d2d6de;
        background: #fff;
    }
    .table-jasa-tindakan {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        white-space: nowrap;
    }
    .table-jasa-tindakan thead th {
        background-color: #7f8c8d;
        color: #ffffff;
        font-weight: 600;
        font-size: 11px;
        border: 1px solid #95a5a6;
        padding: 6px 8px;
        text-align: left;
        vertical-align: middle;
    }
    .table-jasa-tindakan tbody td {
        border: 1px solid #e4e7ea;
        padding: 5px 8px;
        font-size: 11px;
        color: #333;
        vertical-align: middle;
    }
    .table-jasa-tindakan tbody tr:nth-child(even) {
        background-color: #fdfdfd;
    }
    .table-jasa-tindakan tbody tr:hover {
        background-color: #f5f5f5;
    }
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .text-left {
        text-align: left;
    }
</style>

<div class="laporan-jasa-tindakan-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Laporan Jasa Tindakan Medis</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/laporan/laporan-jasa-tindakan">Laporan Jasa Tindakan Medis</a>
        </div>
    </div>

    {{-- Action Box --}}
    <div class="card-action-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn-orange-cari" id="btnToggleFilter">
                    <i class="fas fa-search"></i> Cari data
                </button>
                <a href="/laporan/laporan-jasa-tindakan" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('laporan.jasa-tindakan.pdf', ['tanggal' => $tanggal, 'q' => $q, 'dokter_id' => $dokterId, 'penjamin_id' => $penjaminId]) }}" target="_blank" class="btn-cyan-cetak">
                    <i class="fas fa-file-pdf"></i> Cetak Laporan (pdf)
                </a>
                <a href="{{ route('laporan.jasa-tindakan.xls', ['tanggal' => $tanggal, 'q' => $q, 'dokter_id' => $dokterId, 'penjamin_id' => $penjaminId]) }}" target="_blank" class="btn-cyan-cetak">
                    <i class="fas fa-file-excel"></i> Cetak Laporan (xls)
                </a>
            </div>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterBox" class="mt-2 pt-2 border-top {{ $q || $dokterId || $penjaminId || $tanggal != '2026-10-01' ? '' : 'd-none' }}">
            <form method="GET" action="/laporan/laporan-jasa-tindakan" class="d-flex align-items-center flex-wrap gap-2">
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm" style="max-width: 150px; height: 30px; font-size: 12px;"
                           value="{{ $tanggal }}">
                </div>
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Kata Kunci:</label>
                    <input type="text" name="q" class="form-control form-control-sm" style="max-width: 220px; height: 30px; font-size: 12px;"
                           placeholder="No Reg, Pasien, RM, Tindakan..." value="{{ $q }}">
                </div>
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Dokter:</label>
                    <select name="dokter_id" class="form-select form-select-sm" style="height: 30px; font-size: 12px;">
                        <option value="">-- Semua Dokter --</option>
                        @foreach($dokters as $dok)
                            <option value="{{ $dok->id }}" {{ $dokterId == $dok->id ? 'selected' : '' }}>{{ $dok->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Penjamin:</label>
                    <select name="penjamin_id" class="form-select form-select-sm" style="height: 30px; font-size: 12px;">
                        <option value="">-- Semua Penjamin --</option>
                        @foreach($penjamins as $penj)
                            <option value="{{ $penj->id }}" {{ $penjaminId == $penj->id ? 'selected' : '' }}>{{ $penj->nama_penjamin }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="align-self-end">
                    <button type="submit" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 12px; height: 30px;">Cari</button>
                    <a href="/laporan/laporan-jasa-tindakan" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 12px; height: 30px;">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Cyan Line Divider --}}
    <div class="cyan-divider"></div>

    {{-- Table Data --}}
    <div class="table-responsive-tindakan">
        <table class="table-jasa-tindakan">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">No</th>
                    <th style="width: 85px; text-align: center;">Tanggal</th>
                    <th style="width: 60px; text-align: center;">Jam</th>
                    <th style="width: 110px;">No Reg</th>
                    <th style="width: 200px;">Nama Pasien</th>
                    <th style="width: 95px;">Kode RM</th>
                    <th style="width: 100px;">Penjamin</th>
                    <th style="width: 180px;">Dokter</th>
                    <th style="width: 220px;">Tindakan</th>
                    <th style="width: 80px; text-align: right;">Jasa Medis</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                @php
                    $p = $item->pendaftaran;
                    $tgl = $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : $tanggal;
                    $jam = $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('H:i') : '08:00';
                    $jasaMedis = $item->subtotal ?: 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $tgl }}</td>
                    <td class="text-center">{{ $jam }}</td>
                    <td class="text-left">{{ $p->nomor_registrasi ?? '-' }}</td>
                    <td class="text-left" style="font-weight: 500;">{{ $p->pasien->nama_pasien ?? '-' }}</td>
                    <td class="text-left">{{ $p->pasien->no_rm ?? '-' }}</td>
                    <td class="text-left">{{ $p->penjamin->nama_penjamin ?? 'BPJS Kesehatan' }}</td>
                    <td class="text-left">{{ $item->pelaksana->nama_pegawai ?? ($p->dokter->nama_pegawai ?? '-') }}</td>
                    <td class="text-left">{{ $item->tindakan->nama_tindakan ?? '-' }}</td>
                    <td class="text-right">{{ $jasaMedis > 0 ? number_format($jasaMedis, 0, ',', '.') : '0' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">Tidak ada data jasa tindakan medis ditemukan pada tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnToggleFilter = document.getElementById('btnToggleFilter');
        const filterBox = document.getElementById('filterBox');
        if (btnToggleFilter && filterBox) {
            btnToggleFilter.addEventListener('click', function() {
                filterBox.classList.toggle('d-none');
            });
        }
    });
</script>
@endsection

