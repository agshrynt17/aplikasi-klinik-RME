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
    .table-detail-prof {
        width: 100%;
        font-size: 13px;
        border-collapse: collapse;
        background: #fff;
    }
    .table-detail-prof th, .table-detail-prof td {
        padding: 8px 12px;
        border: 1px solid #f4f4f4;
    }
    .table-detail-prof th {
        width: 180px;
        background-color: #f9f9f9;
        font-weight: 600;
    }
</style>

<div class="pcare-page">
    <div class="profaskes-header">
        <h3 class="profaskes-title">Detail TACC</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <a href="/pcare-tacc/index">Bridging PCare - Setting TACC</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <span style="color:#777;">Detail TACC</span>
        </div>
    </div>

    <div style="margin-bottom: 12px;">
        <a href="/pcare-tacc/{{ $tacc->id }}/edit" class="btn btn-warning btn-sm" style="background:#f39c12; border:1px solid #e08e0b; color:#fff; font-size:12px; padding:4px 12px;">
            <i class="fas fa-pencil-alt me-1"></i> Update
        </a>
        <a href="/pcare-tacc/{{ $tacc->id }}/delete" class="btn btn-danger btn-sm" style="background:#dd4b39; border:1px solid #d73925; color:#fff; font-size:12px; padding:4px 12px;" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
            <i class="fas fa-trash me-1"></i> Delete
        </a>
        <a href="/pcare-tacc/index" class="btn btn-default btn-sm ms-2" style="background:#e7e7e7; border:1px solid #ccc; font-size:12px; padding:4px 12px;">
            Kembali
        </a>
    </div>

    <table class="table-detail-prof">
        <tbody>
            <tr>
                <th>Kode TACC</th>
                <td>{{ $tacc->kode_tacc }}</td>
            </tr>
            <tr>
                <th>Nama TACC</th>
                <td>{{ $tacc->nama_tacc }}</td>
            </tr>
            <tr>
                <th>Alasan TACC</th>
                <td style="word-break: break-all;">{{ $tacc->alasan_tacc }}</td>
            </tr>
            <tr>
                <th>Diubah Pada</th>
                <td>{{ $tacc->updated_at ? \Carbon\Carbon::parse($tacc->updated_at)->format('Y-m-d H:i:s') : '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

