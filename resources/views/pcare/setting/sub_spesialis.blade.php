@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .profaskes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .profaskes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #444;
        text-decoration: none;
    }
    .profaskes-breadcrumb a:hover { text-decoration: underline; }
    .table-pcare-grid {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 15px;
    }
    .table-pcare-grid th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
    }
    .table-pcare-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
</style>

<div class="pcare-page">
    <div class="profaskes-header">
        <h3 class="profaskes-title">Sub Spesialis: {{ $spesialis->nm_spesialis }} ({{ $spesialis->kd_spesialis }})</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <a href="/pcare-spesialis/index">Bridging PCare - Setting Data Spesialis</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <span style="color:#777;">Sub Spesialis</span>
        </div>
    </div>

    <div style="margin-bottom: 12px;">
        <a href="/pcare-spesialis/index" class="btn btn-default btn-sm" style="background:#e7e7e7; border:1px solid #ccc; font-size:12px; padding:4px 12px;">
            &larr; Kembali ke Spesialis
        </a>
    </div>

    <div class="table-responsive" style="border: none;">
        <table class="table-pcare-grid">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Kode Sub Spesialis</th>
                    <th>Nama Sub Spesialis</th>
                    <th>Jumlah Sarana</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">
                        <i class="fas fa-info-circle me-1"></i> Data sub spesialis untuk <strong>{{ $spesialis->nm_spesialis }}</strong> sinkron dengan referensi PCare BPJS (Total {{ $spesialis->jumlah_sub }} data).
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

