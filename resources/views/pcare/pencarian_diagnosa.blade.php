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

    /* Card Box */
    .card-pcare-box {
        background: #fff;
        border: 1px solid #d2d6de;
        border-top: 3px solid #3c8dbc;
        padding: 15px 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.08);
        border-radius: 2px;
    }

    /* Form Fields */
    .form-row-pcare {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
        margin-bottom: 16px;
    }
    .form-group-pcare {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .form-group-pcare label {
        font-size: 12px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }
    .input-pcare {
        height: 32px;
        padding: 4px 10px;
        font-size: 13px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        outline: none;
    }
    .input-pcare:focus {
        border-color: #3c8dbc;
    }

    /* Buttons */
    .btn-cari-pcare {
        background-color: #3c8dbc;
        border: 1px solid #367fa9;
        color: #fff;
        font-size: 13px;
        padding: 5px 16px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 32px;
    }
    .btn-cari-pcare:hover {
        background-color: #367fa9;
    }
    .btn-reset-icon {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #444;
        font-size: 13px;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        text-decoration: none;
    }
    .btn-reset-icon:hover {
        background-color: #f4f4f4;
        color: #222;
    }

    .btn-orange-refresh {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .btn-orange-refresh:hover {
        background-color: #d68407;
    }

    /* Response Info */
    .response-info-text {
        font-size: 12px;
        color: #333;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    /* Empty Result Area */
    .empty-result-box {
        border: 1px solid #e1e4e8;
        background-color: #fafbfc;
        height: 38px;
        margin-bottom: 15px;
        border-radius: 2px;
    }

    /* Table */
    .table-pcare-diag {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 15px;
    }
    .table-pcare-diag th {
        background-color: #f9f9f9;
        border: 1px solid #e8e8e8;
        padding: 8px 10px;
        font-weight: 600;
        font-size: 12px;
    }
    .table-pcare-diag td {
        border: 1px solid #f4f4f4;
        padding: 7px 10px;
        font-size: 13px;
    }
    .table-pcare-diag tbody tr:nth-of-type(odd) td  { background-color: #fff; }
    .table-pcare-diag tbody tr:nth-of-type(even) td { background-color: #fafafa; }
    .table-pcare-diag tbody tr:hover td             { background-color: #f5f5f5; }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging PCare - Cek Diagnosa</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-diagnosa/index">Bridging PCare · Cek Diagnosa</a>
        </div>
    </div>

    {{-- Main Box Container --}}
    <div class="card-pcare-box">
        {{-- Search Form --}}
        <form method="GET" action="/pcare-diagnosa/index" id="formCariDiagnosa">
            <div class="form-row-pcare">
                <div class="form-group-pcare" style="flex: 1; min-width: 220px;">
                    <label>Ketik Nama / Diagnosa</label>
                    <input type="text" name="keyword" class="input-pcare" value="{{ $keyword }}" placeholder="">
                </div>
                <div class="form-group-pcare" style="width: 70px;">
                    <label>Halaman</label>
                    <input type="number" name="page" class="input-pcare" value="{{ $page }}" min="1">
                </div>
                <div class="form-group-pcare" style="width: 170px;">
                    <label>Jumlah Data</label>
                    <input type="number" name="limit" class="input-pcare" value="{{ $limit }}" min="1">
                </div>
                <div class="form-group-pcare" style="flex-direction: row; gap: 6px;">
                    <button type="submit" class="btn-cari-pcare">
                        <i class="fas fa-search"></i> Cari Data
                    </button>
                    <a href="/pcare-diagnosa/index" class="btn-reset-icon" title="Reset">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>

        {{-- Status / Response Message Section --}}
        <div class="response-info-text">
            <div>Jumlah Data: {{ $searched ? $total : '' }} Kode: {{ $kodeRes }}, Pesan: {{ $pesanRes }}</div>
            <div>Detail Error: {{ $detailError }}</div>
        </div>

        {{-- Result Table or Empty Box Container --}}
        @if($searched && count($results) > 0)
        <div class="table-responsive" style="margin-bottom: 15px;">
            <table class="table-pcare-diag">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th style="width: 140px;">Kode Diagnosa</th>
                        <th>Nama Diagnosa</th>
                        <th style="width: 120px; text-align: center;">Non Spesialis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $idx => $r)
                    <tr>
                        <td style="text-align: center; color: #777;">{{ (($page - 1) * $limit) + $idx + 1 }}</td>
                        <td style="font-weight: 600; color: #3c8dbc;">{{ $r['kdDiag'] }}</td>
                        <td>{{ $r['nmDiag'] }}</td>
                        <td style="text-align: center;">
                            @if($r['nonSpesialis'] === 'True')
                                <span class="badge bg-success" style="font-weight: normal; font-size: 11px;">Ya</span>
                            @else
                                <span class="badge bg-secondary" style="font-weight: normal; font-size: 11px;">Tidak</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-result-box"></div>
        @endif

        {{-- Refresh Button --}}
        <div>
            <a href="/pcare-diagnosa/index" class="btn-orange-refresh">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
        </div>
    </div>
</div>
@endsection
