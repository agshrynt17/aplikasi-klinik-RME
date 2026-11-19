@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page {
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
    .filter-pendaftaran-box {
        margin-bottom: 15px;
    }
    .filter-pendaftaran-box label {
        font-size: 12px;
        font-weight: 700;
        color: #333;
        margin-bottom: 4px;
        display: block;
    }
    .input-tgl {
        height: 32px;
        padding: 4px 10px;
        font-size: 13px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        outline: none;
        width: 220px;
    }
    .input-tgl:focus { border-color: #3c8dbc; }

    .btn-orange-cari {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        height: 32px;
    }
    .btn-orange-cari:hover { background-color: #d68407; }

    .btn-reset-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        font-size: 12px;
        padding: 5px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        height: 32px;
    }
    .btn-reset-light:hover { background-color: #f4f4f4; }

    .btn-cyan-nourut {
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
        height: 32px;
    }
    .btn-cyan-nourut:hover { background-color: #00acd6; }

    /* Result Card */
    .card-riwayat-box {
        background: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #3c8dbc;
        padding: 12px 18px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.06);
        border-radius: 2px;
        margin-top: 8px;
    }
    .riwayat-title {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .riwayat-meta {
        font-size: 12px;
        color: #555;
        line-height: 1.6;
    }

    /* Table */
    .table-pcare-grid {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 12.5px;
        margin-top: 10px;
        margin-bottom: 5px;
    }
    .table-pcare-grid th {
        background-color: #f9f9f9 !important;
        border: 1px solid #e8e8e8 !important;
        padding: 8px 10px;
        font-weight: 600;
        font-size: 12px;
    }
    .table-pcare-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 7px 10px;
    }
    .table-pcare-grid tbody tr:nth-of-type(odd) td  { background-color: #fff; }
    .table-pcare-grid tbody tr:nth-of-type(even) td { background-color: #fafafa; }
    .table-pcare-grid tbody tr:hover td             { background-color: #f5f5f5; }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Cek Data Terdaftar Pada PCare</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-pendaftaran/index">Cek Data Terdaftar Pada PCare</a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="filter-pendaftaran-box">
        <form method="GET" action="/pcare-pendaftaran/index">
            <label>Tanggal Daftar</label>
            <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                <input type="date" name="tanggal_daftar" class="input-tgl" value="{{ $tanggal_daftar ?: date('Y-m-d') }}">
                <button type="submit" class="btn-orange-cari">
                    <i class="fas fa-search"></i> Cari Data
                </button>
                <a href="/pcare-pendaftaran/index" class="btn-reset-light">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="/pcare-pendaftaran/cari-peserta" class="btn-cyan-nourut">
                    <i class="fas fa-search"></i> Cari Dengan No Urut
                </a>
            </div>
        </form>
    </div>

    {{-- Result Box --}}
    <div class="card-riwayat-box">
        <div class="riwayat-title">
            <i class="fas fa-search" style="font-size:13px; color:#555;"></i> Riwayat Pendaftaran Peserta
        </div>
        <div class="riwayat-meta">
            <div>Jumlah Data: {{ $total }}</div>
            @if(!$searched || $total === 0)
                <div class="mt-1">{{ $pesanInfo }}</div>
            @endif
        </div>

        @if($searched && count($pendaftarans) > 0)
        <div class="table-responsive mt-3" style="border:none;">
            <table class="table-pcare-grid">
                <thead>
                    <tr>
                        <th style="width:35px; text-align:center;">#</th>
                        <th style="width:90px; text-align:center;">No Urut</th>
                        <th style="width:130px;">No Kartu (KIS)</th>
                        <th>Nama Pasien</th>
                        <th style="width:140px;">Poli</th>
                        <th>Keluhan</th>
                        <th style="width:130px;">Jenis Kunjungan</th>
                        <th style="width:140px; text-align:center;">Status PCare</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftarans as $idx => $item)
                    <tr>
                        <td style="text-align:center; color:#777;">{{ $idx + 1 }}</td>
                        <td style="text-align:center; font-weight:bold; color:#3c8dbc;">{{ $item->no_urut }}</td>
                        <td>{{ $item->no_kartu }}</td>
                        <td style="font-weight:500;">{{ $item->nama_pasien }}</td>
                        <td>{{ $item->poli }}</td>
                        <td>{{ $item->keluhan }}</td>
                        <td>{{ $item->kunjungan_sakit }}</td>
                        <td style="text-align:center;">
                            <span class="badge bg-success" style="font-size:11px; font-weight:normal;">
                                <i class="fas fa-check-circle me-1"></i> {{ $item->status_pcare }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
