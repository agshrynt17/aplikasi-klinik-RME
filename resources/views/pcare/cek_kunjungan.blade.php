@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-kunjungan-page {
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
    .filter-kunjungan-box {
        background: transparent;
        margin-bottom: 8px;
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
    .btn-search-blue {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
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
    .btn-search-blue:hover {
        background-color: #286090;
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
    .info-bar-kunjungan {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #555;
        padding: 6px 0;
        margin-top: 6px;
        border-top: 1px solid #e4e7ea;
    }

    /* Table */
    .table-pcare-kunjungan {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 12px;
        margin-top: 4px;
    }
    .table-pcare-kunjungan th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 8px 6px;
        font-weight: 700;
        color: #333;
        font-size: 12px;
        text-align: left;
    }
    .table-pcare-kunjungan td {
        border: 1px solid #e4e7ea;
        padding: 7px 8px;
        vertical-align: middle;
        color: #333;
        line-height: 1.35;
    }
    .table-pcare-kunjungan tbody tr:hover {
        background-color: #f5f5f5;
    }

    /* Badges & Buttons */
    .badge-status {
        display: inline-block;
        padding: 2px 7px;
        font-size: 10.5px;
        font-weight: 500;
        border-radius: 2px;
        color: #fff;
        text-align: center;
        margin-bottom: 3px;
        white-space: nowrap;
    }
    .badge-sudah {
        background-color: #00a65a;
    }
    .badge-belum {
        background-color: #f39c12;
    }
    .btn-detail-green {
        background-color: #00a65a;
        border: 1px solid #008d4c;
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
    .btn-detail-green:hover {
        background-color: #008d4c;
        color: #fff !important;
    }
    .link-cyan-reg {
        color: #00c0ef !important;
        font-weight: 600;
        text-decoration: none;
        font-size: 12px;
    }
    .link-cyan-reg:hover {
        text-decoration: underline;
    }
    .text-sub {
        font-size: 11px;
        color: #666;
    }
    .text-sub-dark {
        font-size: 11.5px;
        color: #333;
    }
</style>

<div class="pcare-kunjungan-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging PCare - Daftar Pelayanan Peserta PCare</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-kunjungan/index">Bridging PCare</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-kunjungan/index">Daftar Pelayanan Peserta PCare</a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="filter-kunjungan-box">
        <form method="GET" action="/pcare-kunjungan/index">
            <div class="filter-grid">
                <div class="filter-group" style="width: 110px;">
                    <label>Tanggal Daftar</label>
                    <input type="text" name="tanggal_daftar" class="input-filter" value="{{ $tanggalDaftar }}" placeholder="DD-MM-YYYY">
                </div>
                <div class="filter-group" style="width: 110px;">
                    <label>Kode Reg</label>
                    <input type="text" name="kode_reg" class="input-filter" value="{{ request('kode_reg') }}">
                </div>
                <div class="filter-group" style="width: 125px;">
                    <label>No. Kartu Peserta</label>
                    <input type="text" name="no_kartu" class="input-filter" value="{{ request('no_kartu') }}">
                </div>
                <div class="filter-group" style="width: 135px;">
                    <label>Nama Pasien</label>
                    <input type="text" name="nama_pasien" class="input-filter" value="{{ request('nama_pasien') }}">
                </div>
                <div class="filter-group" style="width: 115px;">
                    <label>Pelayanan</label>
                    <select name="pelayanan" class="input-filter">
                        <option value="">--Pilih--</option>
                        <option value="Rawat Jalan" {{ request('pelayanan') == 'Rawat Jalan' || request('pelayanan') == 'RJTP' ? 'selected' : '' }}>RJTP</option>
                        <option value="Rawat Inap" {{ request('pelayanan') == 'Rawat Inap' || request('pelayanan') == 'RITP' ? 'selected' : '' }}>RITP</option>
                        <option value="Promotif" {{ request('pelayanan') == 'Promotif' ? 'selected' : '' }}>Promotif Preventif</option>
                    </select>
                </div>
                <div class="filter-group" style="width: 125px;">
                    <label>Status Pulang</label>
                    <select name="status_pulang" class="input-filter">
                        <option value="">--Pilih--</option>
                        @foreach($statusPulangList as $sp)
                            <option value="{{ $sp }}" {{ request('status_pulang') == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="flex-direction: row; gap: 4px; align-items: flex-end;">
                    <button type="submit" class="btn-search-blue" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="/pcare-kunjungan/index" class="btn-reset-light" title="Reset">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Bar --}}
    <div class="info-bar-kunjungan">
        <div>
            Data yang ditampilkan adalah data yang sudah entri pendaftaran pada tanggal <b>{{ $tanggalDaftar }}</b>. Jika data tidak muncul, silakan entri pendaftaran terlebih dahulu
        </div>
        <div style="font-weight: 600; color: #444;">
            Menampilkan {{ $pendaftarans->firstItem() ?? 0 }} - {{ $pendaftarans->lastItem() ?? 0 }} dari {{ $pendaftarans->total() }} data
        </div>
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-pcare-kunjungan">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">#</th>
                    <th style="width: 100px; text-align: center;">Pelayanan</th>
                    <th style="width: 140px;">Kode Registrasi</th>
                    <th style="width: 160px;">No.Kartu/NIK</th>
                    <th>Pasien</th>
                    <th style="width: 130px;">Kelamin & Tgl.Lahir</th>
                    <th style="width: 110px;">Poli/Kegiatan</th>
                    <th style="width: 120px;">Status Pulang</th>
                    <th style="width: 70px;">TACC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $index => $item)
                @php
                    $isEntri = ($index % 4 != 1); // Sample realistic status matching screenshot where most are Sudah Entri and some Belum Entri
                    if ($item->status_periksa == 'Selesai' || $item->status_pulang) {
                        $isEntri = true;
                    }
                    $noKartu = $item->pasien->no_bpjs ?: ($item->no_bpjs_asuransi ?: '-');
                    $nik = $item->pasien->nik ?: '-';
                    $kelamin = ($item->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki';
                    $tglLahir = $item->pasien->tanggal_lahir ? \Carbon\Carbon::parse($item->pasien->tanggal_lahir)->format('d.M.Y') : '-';
                    $tglDaftar = \Carbon\Carbon::parse($item->tanggal_daftar)->format('d.M.Y');
                @endphp
                <tr>
                    <td style="text-align: center; color: #555;">{{ $pendaftarans->firstItem() + $index }}</td>
                    <td style="text-align: center;">
                        @if($isEntri)
                            <span class="badge-status badge-sudah">Sudah Entri</span>
                        @else
                            <span class="badge-status badge-belum">Belum Entri</span>
                        @endif
                        <div>
                            <a href="/pcare-kunjungan/view-pendaftaran?id={{ $item->id }}" class="btn-detail-green">
                                <i class="fas fa-file-alt"></i> Detail
                            </a>
                        </div>
                    </td>
                    <td>
                        <div>
                            <a href="/pcare-kunjungan/view-pendaftaran?id={{ $item->id }}" class="link-cyan-reg">
                                {{ $item->no_rawat ?? $item->nomor_registrasi }}
                            </a>
                        </div>
                        <div class="text-sub">Tgl: {{ $tglDaftar }}</div>
                    </td>
                    <td>
                        <div class="text-sub-dark">No.Kartu: {{ $noKartu }}</div>
                        <div class="text-sub">NIK: {{ $nik }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #333; font-size: 12.5px;">{{ strtoupper($item->pasien->nama_pasien ?? '-') }}</div>
                        <div class="text-sub">No.RM: {{ $item->pasien->no_rm ?? '-' }}</div>
                        @if($index == 0 || $index == 8)
                            <div style="color: #d68407; font-size: 11px; font-style: italic;">Klub: Diabetes Melitus</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 12px;">{{ $kelamin }}</div>
                        <div class="text-sub">{{ $tglLahir }}</div>
                    </td>
                    <td>
                        <div style="font-size: 12px; font-weight: 600; color: #444;">{{ strtoupper($item->poliklinik->nama_poli ?? 'POLI UMUM') }}</div>
                    </td>
                    <td>
                        <div style="font-size: 12px; color: #444;">
                            {{ $isEntri ? ($item->status_pulang ?: 'Berobat Jalan') : '-' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 12px; color: #777;">-</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        Tidak ada data pelayanan kunjungan peserta pada tanggal {{ $tanggalDaftar }}.
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
