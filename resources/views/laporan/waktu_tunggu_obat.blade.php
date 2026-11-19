@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .waktu-tunggu-obat-page {
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
        font-size: 19px;
        font-weight: 500;
        color: #333;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    /* Filter Card */
    .card-filter-waktu {
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #00c0ef !important;
        border-radius: 2px;
        padding: 12px 16px;
        margin-bottom: 15px;
    }
    .filter-section-title {
        font-size: 11.5px;
        color: #555;
        margin-bottom: 6px;
        font-weight: 600;
    }
    .filter-label {
        font-size: 11.5px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
        display: block;
    }
    .input-date-custom {
        height: 32px;
        font-size: 12px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        padding: 4px 10px;
        width: 140px;
        color: #555;
        background-color: #fff;
    }
    .input-date-custom:focus {
        border-color: #3c8dbc;
        outline: 0;
    }

    .btn-search-blue {
        background-color: #3c8dbc;
        border: 1px solid #367fa9;
        color: #fff !important;
        font-size: 12px;
        height: 32px;
        padding: 0 14px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-search-blue:hover {
        background-color: #367fa9;
    }
    .btn-reset-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        font-size: 12px;
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

    .btn-orange-pdf {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        height: 32px;
        padding: 0 14px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-orange-pdf:hover {
        background-color: #d68407;
    }

    .btn-cyan-excel {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 12px;
        height: 32px;
        padding: 0 14px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-cyan-excel:hover {
        background-color: #00acd6;
    }

    /* Table */
    .table-waktu-tunggu {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 11.5px;
    }
    .table-waktu-tunggu th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 8px 8px;
        font-weight: 700;
        color: #333;
        font-size: 11.5px;
        text-align: left;
    }
    .table-waktu-tunggu td {
        border: 1px solid #d2d6de;
        padding: 6px 8px;
        vertical-align: middle;
        color: #333;
        font-size: 11.5px;
    }
    .table-waktu-tunggu tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

<div class="waktu-tunggu-obat-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">LAPORAN WAKTU TUNGGU OBAT</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/laporan-farmasi/laporan-waktu-tunggu-obat">LAPORAN WAKTU TUNGGU OBAT</a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card-filter-waktu">
        <div class="filter-section-title">Filter Pencarian</div>
        <form method="GET" action="/laporan-farmasi/laporan-waktu-tunggu-obat" class="d-flex align-items-flex-end flex-wrap gap-2">
            <div>
                <label class="filter-label">Tgl Awal</label>
                <input type="text" name="tgl_awal" class="input-date-custom" value="{{ $tglAwalInput }}">
            </div>
            <div>
                <label class="filter-label">Tgl Akhir</label>
                <input type="text" name="tgl_akhir" class="input-date-custom" value="{{ $tglAkhirInput }}">
            </div>
            <div class="d-flex align-items-center gap-1 pt-3">
                <button type="submit" class="btn-search-blue" title="Cari">
                    <i class="fas fa-search"></i>
                </button>
                <a href="/laporan-farmasi/laporan-waktu-tunggu-obat" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
                <a href="/laporan-farmasi/cetak-waktu-tunggu-obat-pdf?tgl_awal={{ $tglAwalInput }}&tgl_akhir={{ $tglAkhirInput }}" target="_blank" class="btn-orange-pdf">
                    <i class="fas fa-print"></i> Cetak Laporan Pdf
                </a>
                <a href="/laporan-farmasi/cetak-waktu-tunggu-obat-excel?tgl_awal={{ $tglAwalInput }}&tgl_akhir={{ $tglAkhirInput }}" class="btn-cyan-excel">
                    <i class="fas fa-file-excel"></i> Cetak Laporan Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-waktu-tunggu">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">#</th>
                    <th style="width: 140px;">Kode Registrasi</th>
                    <th style="width: 110px;">Tanggal</th>
                    <th>Nama Pasien</th>
                    <th style="width: 130px;">No RM</th>
                    <th style="width: 180px;">Waktu Penerimaan Resep</th>
                    <th style="width: 180px;">Waktu Penyerahan Obat</th>
                    <th style="width: 120px;">Waktu Tunggu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->nomor_registrasi }}</td>
                    <td>{{ date('d-M-Y', strtotime($item->tanggal_daftar)) }}</td>
                    <td style="text-transform: uppercase;">{{ $item->nama_pasien }}</td>
                    <td>{{ $item->no_rm }}</td>
                    <td>{{ $item->waktu_proses ? date('d/m/Y H:i', strtotime($item->waktu_proses)) : '-' }}</td>
                    <td>{{ $item->waktu_penyerahan ? date('d/m/Y H:i', strtotime($item->waktu_penyerahan)) : '-' }}</td>
                    <td>{{ $item->waktu_tunggu ?? '0 Jam 0 Menit' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        Tidak ada data waktu tunggu obat pada periode tanggal yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

