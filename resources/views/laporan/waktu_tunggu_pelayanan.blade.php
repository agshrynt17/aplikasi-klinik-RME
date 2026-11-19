@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .waktu-tunggu-pelayanan-page {
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
    .select-custom {
        height: 32px;
        font-size: 12px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        padding: 4px 10px;
        width: 170px;
        color: #555;
        background-color: #fff;
    }
    .select-custom:focus {
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
    .table-waktu-pelayanan {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 11.5px;
    }
    .table-waktu-pelayanan th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 8px 8px;
        font-weight: 700;
        color: #333;
        font-size: 11.5px;
        text-align: left;
    }
    .table-waktu-pelayanan td {
        border: 1px solid #d2d6de;
        padding: 6px 8px;
        vertical-align: middle;
        color: #333;
        font-size: 11.5px;
    }
    .table-waktu-pelayanan tbody tr:hover {
        background-color: #f5f5f5;
    }
    .table-waktu-pelayanan tfoot tr td {
        background-color: #fff;
        border: 1px solid #d2d6de;
        font-weight: 700;
        color: #333;
    }
</style>

<div class="waktu-tunggu-pelayanan-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">LAPORAN WAKTU TUNGGU PELAYANAN</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/laporan-pmkp/laporan-waktu-tunggu-layanan">LAPORAN WAKTU TUNGGU PELAYANAN</a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card-filter-waktu">
        <div class="filter-section-title">Filter Pencarian</div>
        <form method="GET" action="/laporan-pmkp/laporan-waktu-tunggu-layanan" class="d-flex align-items-flex-end flex-wrap gap-2">
            <div>
                <label class="filter-label">Tgl Awal</label>
                <input type="text" name="tgl_awal" class="input-date-custom" value="{{ $tglAwalInput }}">
            </div>
            <div>
                <label class="filter-label">Tgl Akhir</label>
                <input type="text" name="tgl_akhir" class="input-date-custom" value="{{ $tglAkhirInput }}">
            </div>
            <div>
                <label class="filter-label">Jenis Asesmen</label>
                <select name="jenis_asesmen" class="select-custom">
                    <option value="Asesmen Awal" {{ $jenisAsesmen == 'Asesmen Awal' ? 'selected' : '' }}>Asesmen Awal</option>
                    <option value="Asesmen Ulang" {{ $jenisAsesmen == 'Asesmen Ulang' ? 'selected' : '' }}>Asesmen Ulang</option>
                    <option value="Semua Asesmen" {{ $jenisAsesmen == 'Semua Asesmen' ? 'selected' : '' }}>Semua Asesmen</option>
                </select>
            </div>
            <div class="d-flex align-items-center gap-1 pt-3">
                <button type="submit" class="btn-search-blue" title="Cari">
                    <i class="fas fa-search"></i>
                </button>
                <a href="/laporan-pmkp/laporan-waktu-tunggu-layanan" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
                <a href="/laporan-pmkp/cetak-waktu-tunggu-layanan-pdf?tgl_awal={{ $tglAwalInput }}&tgl_akhir={{ $tglAkhirInput }}&jenis_asesmen={{ urlencode($jenisAsesmen) }}" target="_blank" class="btn-orange-pdf">
                    <i class="fas fa-print"></i> Cetak Laporan Pdf
                </a>
                <a href="/laporan-pmkp/cetak-waktu-tunggu-layanan-excel?tgl_awal={{ $tglAwalInput }}&tgl_akhir={{ $tglAkhirInput }}&jenis_asesmen={{ urlencode($jenisAsesmen) }}" class="btn-cyan-excel">
                    <i class="fas fa-file-excel"></i> Cetak Laporan Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-waktu-pelayanan">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">#</th>
                    <th style="width: 140px;">Kode Registrasi</th>
                    <th style="width: 110px;">Tanggal</th>
                    <th>Nama Pasien</th>
                    <th style="width: 120px;">No RM</th>
                    <th style="width: 160px;">Jam Pendaftaran</th>
                    <th style="width: 160px;">Jam Asesmen</th>
                    <th style="width: 120px;">Waktu Tunggu</th>
                    <th style="width: 80px;">Ket</th>
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
                    <td>{{ $item->jam_daftar ?? '-' }}</td>
                    <td>{{ $item->tanggal_asesmen ? date('d/m/Y H:i', strtotime($item->tanggal_asesmen)) : ($item->created_at ? date('d/m/Y H:i', strtotime($item->created_at)) : '-') }}</td>
                    <td>0 Jam 0 Menit</td>
                    <td>-</td>
                </tr>
                @empty
                {{-- In screenshot, when there are no individual rows, it directly transitions to the summary footer --}}
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="fw-bold" style="padding: 8px 10px;">Rata-Rata Waktu Tunggu</td>
                    <td class="fw-bold" style="padding: 8px 10px;">{{ $rataRata ?? '0 Jam 0 Menit' }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

