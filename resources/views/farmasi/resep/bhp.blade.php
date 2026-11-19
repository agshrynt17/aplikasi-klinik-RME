@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 TRANSAKSI BAHAN HABIS PAKAI (BHP) */
    .bhp-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
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
        color: #444;
        text-decoration: none;
    }
    .konhakes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .orange-cyan-divider {
        height: 3px;
        background: linear-gradient(to right, #f39c12, #00c0ef);
        margin-top: 5px;
        margin-bottom: 15px;
    }
    .card-bhp-box {
        background-color: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 12px 15px;
    }
    .btn-bhp-search {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        height: 30px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-bhp-search:hover {
        background-color: #d68407;
    }
    .btn-bhp-reset {
        background-color: #ffffff;
        border: 1px solid #ccc;
        color: #444 !important;
        height: 30px;
        padding: 0 10px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-bhp-reset:hover {
        background-color: #f4f4f4;
    }
    .btn-bhp-pdf {
        background-color: #00c0ef;
        border: 1px solid #00a7d0;
        color: #ffffff !important;
        height: 30px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        text-decoration: none;
    }
    .btn-bhp-pdf:hover {
        background-color: #00a7d0;
    }
    .btn-bhp-xls {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
        color: #ffffff !important;
        height: 30px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        text-decoration: none;
    }
    .btn-bhp-xls:hover {
        background-color: #286090;
    }
    .table-konhakes-bhp {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        border: 1px solid #e1e1e1;
    }
    .table-konhakes-bhp thead th {
        background-color: #e6e6e6;
        color: #555;
        font-weight: 600;
        padding: 8px 10px;
        border: 1px solid #dcdcdc;
        white-space: nowrap;
    }
    .table-konhakes-bhp tbody td {
        padding: 8px 10px;
        border: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 12.5px;
    }
    .filter-ctrl {
        height: 30px;
        font-size: 12px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 3px 6px;
        width: 100%;
        color: #333;
        background-color: #fff;
    }
</style>

<div class="bhp-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h1 class="konhakes-title">Transaksi Bahan Habis Pakai (BHP)</h1>
        <div class="konhakes-breadcrumb">
            <a href="{{ url('/dashboard') }}">Beranda</a> &gt; <a href="{{ url()->current() }}">Transaksi Bahan Habis Pakai (BHP)</a>
        </div>
    </div>

    {{-- Orange/Cyan divider line --}}
    <div class="orange-cyan-divider"></div>

    {{-- Main Container Card --}}
    <div class="card-bhp-box">
        {{-- Toolbar Actions --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex gap-1 align-items-center">
                <button type="button" class="btn-bhp-search" onclick="$('#filterSearchBox').slideToggle(200);">
                    <i class="fa fa-search"></i> Cari data
                </button>
                <a href="{{ url('/transaksibhp/index') }}" class="btn-bhp-reset" title="Segarkan Data">
                    <i class="fa fa-sync-alt"></i>
                </a>
            </div>
            <div class="d-flex gap-1 align-items-center">
                <a href="{{ url('/transaksiresep/index') }}" class="btn btn-sm btn-outline-secondary" style="height: 30px; font-size: 12px; line-height: 20px;">
                    <i class="fa fa-arrow-left me-1"></i> Kembali ke Transaksi Resep
                </a>
                <a href="{{ url('/transaksiresep/cetak-pdf?is_bhp=1') }}" target="_blank" class="btn-bhp-pdf">
                    <i class="fa fa-file-pdf"></i> Cetak Laporan(PDF)
                </a>
                <a href="{{ url('/transaksiresep/cetak-xls?is_bhp=1') }}" class="btn-bhp-xls">
                    <i class="fa fa-file-excel"></i> Cetak Laporan(XLS)
                </a>
            </div>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterSearchBox" class="border p-3 rounded mb-3 bg-light" style="display: {{ request()->anyFilled(['tgl_awal', 'tgl_akhir', 'keyword']) ? 'block' : 'none' }};">
            <form action="{{ url('/transaksibhp/index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label mb-1" style="font-size: 11px; font-weight: 600;">Tgl Awal</label>
                        <input type="date" name="tgl_awal" class="filter-ctrl" value="{{ request('tgl_awal', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1" style="font-size: 11px; font-weight: 600;">Tgl Akhir</label>
                        <input type="date" name="tgl_akhir" class="filter-ctrl" value="{{ request('tgl_akhir', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1" style="font-size: 11px; font-weight: 600;">Pencarian (Pasien / No RM / Reg)</label>
                        <input type="text" name="keyword" class="filter-ctrl" placeholder="Ketik kata kunci..." value="{{ request('keyword') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold" style="height: 30px; line-height: 18px; color: #fff;">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Cyan Divider --}}
        <div style="height: 2px; background-color: #00c0ef; margin-bottom: 10px;"></div>

        {{-- Table Transaksi BHP 1:1 --}}
        <div class="table-responsive">
            <table class="table-konhakes-bhp">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th style="width: 110px;">Antrian</th>
                        <th style="width: 140px;">Proses BHP</th>
                        <th style="width: 140px;">Registrasi</th>
                        <th>Pasien</th>
                        <th>Klinik & Penjamin</th>
                        <th>Diagnosa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                        @php
                            $pendaftaran = $row->pendaftaran;
                            $pasien = $row->pasien;
                            $poliNama = $pendaftaran && $pendaftaran->poliklinik ? $pendaftaran->poliklinik->nama_poli : 'Klinik Umum 1';
                            $penjaminNama = $pendaftaran && $pendaftaran->penjamin ? $pendaftaran->penjamin->nama_penjamin : 'BPJS Kesehatan';
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $data->firstItem() + $index }}</td>
                            <td>
                                <div>Antrian: <strong>{{ $row->antrian_nomor ?: '-' }}</strong></div>
                            </td>
                            <td>
                                <span class="badge bg-success">Sudah diproses</span>
                            </td>
                            <td>
                                <strong>{{ $pendaftaran ? $pendaftaran->nomor_registrasi : $row->nomor_resep }}</strong>
                            </td>
                            <td>
                                <div><strong>{{ $pasien ? $pasien->nama_pasien : '-' }}</strong></div>
                                <small class="text-muted">No RM: {{ $pasien ? $pasien->no_rm : '-' }}</small>
                            </td>
                            <td>
                                <div>{{ $poliNama }}</div>
                                <small class="text-muted">{{ $penjaminNama }}</small>
                            </td>
                            <td>
                                <small>{{ $row->diagnosa ?: '-' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted py-3 px-3" style="background-color: #fff;">
                                Tidak ada data yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-end">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
