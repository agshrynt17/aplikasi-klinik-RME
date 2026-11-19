@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .laporan-kasir-page {
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
    .table-responsive-kasir {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #d2d6de;
        background: #fff;
    }
    .table-kasir {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        white-space: nowrap;
    }
    .table-kasir thead th {
        background-color: #7f8c8d;
        color: #ffffff;
        font-weight: 600;
        font-size: 11px;
        border: 1px solid #95a5a6;
        padding: 6px 8px;
        text-align: center;
        vertical-align: middle;
    }
    .table-kasir tbody td {
        border: 1px solid #e4e7ea;
        padding: 5px 8px;
        font-size: 11px;
        color: #333;
        vertical-align: middle;
    }
    .table-kasir tbody tr:nth-child(even) {
        background-color: #fdfdfd;
    }
    .table-kasir tbody tr:hover {
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

<div class="laporan-kasir-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Laporan Transaksi Kasir</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/laporan/laporan-transaksi-kasir">Laporan Transaksi Kasir</a>
        </div>
    </div>

    {{-- Action Box --}}
    <div class="card-action-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn-orange-cari" id="btnToggleFilter">
                    <i class="fas fa-search"></i> Cari data
                </button>
                <a href="/laporan/laporan-transaksi-kasir" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('laporan.transaksi-kasir.pdf', ['tanggal' => $tanggal, 'q' => $q, 'poliklinik_id' => $poliklinikId, 'penjamin_id' => $penjaminId, 'cara_bayar' => $caraBayar]) }}" target="_blank" class="btn-cyan-cetak">
                    <i class="fas fa-file-pdf"></i> Cetak Laporan (pdf)
                </a>
                <a href="{{ route('laporan.transaksi-kasir.excel', ['tanggal' => $tanggal, 'q' => $q, 'poliklinik_id' => $poliklinikId, 'penjamin_id' => $penjaminId, 'cara_bayar' => $caraBayar]) }}" target="_blank" class="btn-cyan-cetak">
                    <i class="fas fa-file-excel"></i> Cetak Laporan (Excel)
                </a>
            </div>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterBox" class="mt-2 pt-2 border-top {{ $q || $poliklinikId || $penjaminId || $caraBayar || $tanggal != '2026-10-14' ? '' : 'd-none' }}">
            <form method="GET" action="/laporan/laporan-transaksi-kasir" class="d-flex align-items-center flex-wrap gap-2">
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm" style="max-width: 150px; height: 30px; font-size: 12px;"
                           value="{{ $tanggal }}">
                </div>
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Kata Kunci:</label>
                    <input type="text" name="q" class="form-control form-control-sm" style="max-width: 220px; height: 30px; font-size: 12px;"
                           placeholder="No Reg, Pasien, RM, Kartu..." value="{{ $q }}">
                </div>
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Klinik:</label>
                    <select name="poliklinik_id" class="form-select form-select-sm" style="height: 30px; font-size: 12px;">
                        <option value="">-- Semua Klinik --</option>
                        @foreach($polikliniks as $poli)
                            <option value="{{ $poli->id }}" {{ $poliklinikId == $poli->id ? 'selected' : '' }}>{{ $poli->nama_poli }}</option>
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
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Cara Bayar:</label>
                    <select name="cara_bayar" class="form-select form-select-sm" style="height: 30px; font-size: 12px;">
                        <option value="">-- Semua Cara Bayar --</option>
                        <option value="Cash" {{ $caraBayar == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Belum Bayar" {{ $caraBayar == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="align-self-end">
                    <button type="submit" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 12px; height: 30px;">Cari</button>
                    <a href="/laporan/laporan-transaksi-kasir" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 12px; height: 30px;">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Cyan Line Divider --}}
    <div class="cyan-divider"></div>

    {{-- Table Data --}}
    <div class="table-responsive-kasir">
        <table class="table-kasir">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 75px;">Tanggal</th>
                    <th style="width: 95px;">No Reg</th>
                    <th style="width: 170px;">Nama</th>
                    <th style="width: 85px;">No. RM</th>
                    <th style="width: 65px;">J.K</th>
                    <th style="width: 95px;">Penjamin</th>
                    <th style="width: 95px;">No Kartu</th>
                    <th style="width: 85px;">Klinik</th>
                    <th style="width: 65px;">Adm</th>
                    <th style="width: 65px;">Tindakan</th>
                    <th style="width: 70px;">Konsultasi</th>
                    <th style="width: 65px;">Farmasi</th>
                    <th style="width: 65px;">Lab</th>
                    <th style="width: 65px;">Radiologi</th>
                    <th style="width: 75px;">Sub Total</th>
                    <th style="width: 55px;">Disc(%)</th>
                    <th style="width: 80px;">Total Tagihan</th>
                    <th style="width: 75px;">Dijamin</th>
                    <th style="width: 85px;">Dibayar Pasien</th>
                    <th style="width: 80px;">Cara Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                <tr>
                    <td class="text-center">{{ $row->no }}</td>
                    <td class="text-center">{{ $row->tanggal }}</td>
                    <td class="text-left">{{ $row->no_reg }}</td>
                    <td class="text-left" style="font-weight: 500;">{{ $row->nama }}</td>
                    <td class="text-left">{{ $row->no_rm }}</td>
                    <td class="text-center">{{ $row->jk }}</td>
                    <td class="text-left">{{ $row->penjamin }}</td>
                    <td class="text-left">{{ $row->no_kartu }}</td>
                    <td class="text-left">{{ $row->klinik }}</td>
                    <td class="text-right">{{ number_format($row->adm, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $row->tindakan > 0 ? number_format($row->tindakan, 0, ',', '.') : '0' }}</td>
                    <td class="text-right">{{ $row->konsultasi > 0 ? number_format($row->konsultasi, 0, ',', '.') : '0' }}</td>
                    <td class="text-right">{{ $row->farmasi > 0 ? number_format($row->farmasi, 0, ',', '.') : '0' }}</td>
                    <td class="text-right">{{ $row->lab > 0 ? number_format($row->lab, 0, ',', '.') : '0' }}</td>
                    <td class="text-right">{{ $row->radiologi > 0 ? number_format($row->radiologi, 0, ',', '.') : '0' }}</td>
                    <td class="text-right">{{ number_format($row->sub_total, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $row->disc }}</td>
                    <td class="text-right">{{ $row->total_tagihan > 0 ? number_format($row->total_tagihan, 0, ',', '.') : '0' }}</td>
                    <td class="text-right">{{ $row->dijamin > 0 ? number_format($row->dijamin, 0, ',', '.') : '0' }}</td>
                    <td class="text-right">{{ $row->dibayar_pasien > 0 ? number_format($row->dibayar_pasien, 0, ',', '.') : '0' }}</td>
                    <td class="text-center">{{ $row->cara_bayar }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="21" class="text-center py-4 text-muted">Tidak ada data transaksi kasir ditemukan pada tanggal ini.</td>
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

