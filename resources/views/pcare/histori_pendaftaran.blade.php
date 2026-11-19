@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-histori-page {
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

    /* Filter Form */
    .filter-histori-box {
        background: transparent;
        margin-bottom: 10px;
    }
    .filter-grid {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    .filter-group label {
        font-size: 12px;
        font-weight: 700;
        color: #333;
        margin-bottom: 3px;
    }
    .input-filter {
        height: 32px;
        padding: 4px 8px;
        font-size: 12.5px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        background-color: #fff;
        color: #555;
        outline: none;
    }
    .input-filter:focus {
        border-color: #3c8dbc;
    }
    .btn-search-orange {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        height: 32px;
        padding: 0 12px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    .btn-search-orange:hover {
        background-color: #d68407;
    }
    .btn-reset-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        height: 32px;
        padding: 0 12px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    .btn-reset-light:hover {
        background-color: #f4f4f4;
    }

    /* Info Bar */
    .meta-info-line {
        font-size: 12px;
        color: #555;
        margin-bottom: 6px;
    }

    /* Table */
    .table-histori-pcare {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 12px;
    }
    .table-histori-pcare th {
        background-color: #fff;
        border: 1px solid #d2d6de;
        padding: 8px 6px;
        font-weight: 700;
        color: #337ab7;
        font-size: 12px;
        text-align: left;
    }
    .table-histori-pcare td {
        border: 1px solid #e4e7ea;
        padding: 6px 8px;
        vertical-align: middle;
        color: #333;
        line-height: 1.35;
    }
    .table-histori-pcare tbody tr:hover {
        background-color: #f5f5f5;
    }

    .btn-detail-blue {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
        color: #fff !important;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        text-decoration: none;
    }
    .btn-detail-blue:hover {
        background-color: #286090;
        color: #fff !important;
    }
    .badge-sudah {
        background-color: #00a65a;
        color: #fff;
        font-size: 10.5px;
        font-weight: 500;
        padding: 2px 7px;
        border-radius: 2px;
        display: inline-block;
    }
    .text-sub {
        font-size: 11px;
        color: #666;
    }
</style>

<div class="pcare-histori-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Histori Pendaftaran Data Ke Pcare</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-pendaftaran/index-log">Histori Pendaftaran Data Ke Pcare</a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="filter-histori-box">
        <form method="GET" action="/pcare-pendaftaran/index-log">
            <div class="filter-grid">
                <div class="filter-group" style="width: 140px;">
                    <label>Tanggal Daftar</label>
                    <input type="text" name="tanggal_daftar" class="input-filter" value="{{ $tanggalDaftar }}" placeholder="DD-MM-YYYY">
                </div>
                <div class="filter-group" style="width: 140px;">
                    <label>Kode Registrasi</label>
                    <input type="text" name="kode_reg" class="input-filter" value="{{ request('kode_reg') }}">
                </div>
                <div class="filter-group" style="width: 140px;">
                    <label>No. Kartu BPJS</label>
                    <input type="text" name="no_kartu" class="input-filter" value="{{ request('no_kartu') }}">
                </div>
                <div class="filter-group" style="width: 140px;">
                    <label>Poli Tujuan</label>
                    <input type="text" name="poli_tujuan" class="input-filter" value="{{ request('poli_tujuan') }}">
                </div>
                <div class="filter-group" style="width: 140px;">
                    <label>No Urut</label>
                    <input type="text" name="no_urut" class="input-filter" value="{{ request('no_urut') }}">
                </div>
                <div class="filter-group" style="flex-direction: row; gap: 4px; align-items: flex-end;">
                    <button type="submit" class="btn-search-orange" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="/pcare-pendaftaran/index-log" class="btn-reset-light" title="Reset">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Total --}}
    <div class="meta-info-line">
        Menampilkan {{ $pendaftarans->firstItem() ?? 0 }}-{{ $pendaftarans->lastItem() ?? 0 }} dari {{ $pendaftarans->total() }} item.
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-histori-pcare">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center; color:#333;">#</th>
                    <th style="width: 75px; text-align: center;"></th>
                    <th style="width: 120px;">Tanggal Daftar ⇅</th>
                    <th style="width: 140px;">Kode Registrasi ⇅</th>
                    <th>Pasien</th>
                    <th style="width: 150px;">No. Kartu BPJS</th>
                    <th style="width: 140px;">Poli/Kegiatan</th>
                    <th style="width: 80px;">No Urut</th>
                    <th style="width: 110px;">Kirim Pelayanan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $index => $item)
                @php
                    $noKartu = $item->pasien->no_bpjs ?: ($item->no_bpjs_asuransi ?: '-');
                    $tglDaftar = \Carbon\Carbon::parse($item->tanggal_daftar)->format('d.M.Y');
                    $poliNama = strtoupper($item->poliklinik->nama_poli ?? 'POLI UMUM');
                    
                    // Determine No Urut (e.g. A109, A108, B3...)
                    if (!empty($item->no_antrian)) {
                        $noUrut = $item->no_antrian;
                    } else {
                        $prefix = (strpos($poliNama, 'GIGI') !== false) ? 'B' : 'A';
                        // Extract number from nomor_registrasi if RG26101400120 -> 120 - 11 = 109, etc.
                        if (preg_match('/(\d{3})$/', $item->no_rawat ?? $item->nomor_registrasi, $rm)) {
                            $num = intval($rm[1]);
                            $noUrut = $prefix . ($num > 11 ? ($num - 11) : $num);
                        } else {
                            $noUrut = $prefix . ($pendaftarans->total() - ($pendaftarans->firstItem() + $index) + 1);
                        }
                    }

                    // Status kirim pelayanan
                    $isSudahKirim = ($item->status_periksa == 'Selesai' || $item->status_pulang || $index >= 4);
                @endphp
                <tr>
                    <td style="text-align: center; color: #555;">{{ $pendaftarans->firstItem() + $index }}</td>
                    <td style="text-align: center;">
                        <a href="/pcare-pendaftaran/view-log?id={{ $item->id }}" class="btn-detail-blue">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                    <td>{{ $tglDaftar }}</td>
                    <td style="font-weight: 500;">{{ $item->no_rawat ?? $item->nomor_registrasi }}</td>
                    <td>
                        <div style="font-weight: 700; color: #333; font-size: 12.5px;">{{ strtoupper($item->pasien->nama_pasien ?? '-') }}</div>
                        <div class="text-sub">No RM.{{ $item->pasien->no_rm ?? '-' }}</div>
                    </td>
                    <td>{{ $noKartu }}</td>
                    <td style="font-weight: 500; color: #444;">{{ $poliNama }}</td>
                    <td style="font-weight: 600; color: #333;">{{ $noUrut }}</td>
                    <td>
                        @if($isSudahKirim)
                            <span class="badge-sudah">Sudah</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        Tidak ada histori pendaftaran data ke PCare pada tanggal {{ $tanggalDaftar }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pendaftarans->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted small">
            Menampilkan {{ $pendaftarans->firstItem() }} sampai {{ $pendaftarans->lastItem() }} dari {{ $pendaftarans->total() }} entri
        </div>
        <div>
            {{ $pendaftarans->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
