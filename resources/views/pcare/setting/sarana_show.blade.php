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
    <div class="konhakes-header">
        <h3 class="konhakes-title">Detail Sarana</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-sarana/index">Bridging PCare - Setting Data Sarana</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="{{ url()->current() }}" style="color:#777;">Detail Sarana</a>
        </div>
    </div>

    <div style="margin-bottom: 12px;">
        <a href="/pcare-sarana/{{ $sarana->id }}/edit" class="btn btn-warning btn-sm" style="background:#f39c12; border:1px solid #e08e0b; color:#fff; font-size:12px; padding:4px 12px;">
            <i class="fas fa-pencil-alt me-1"></i> Update
        </a>
        <a href="/pcare-sarana/{{ $sarana->id }}/delete" class="btn btn-danger btn-sm" style="background:#dd4b39; border:1px solid #d73925; color:#fff; font-size:12px; padding:4px 12px;" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
            <i class="fas fa-trash me-1"></i> Delete
        </a>
        <a href="/pcare-sarana/index" class="btn btn-default btn-sm ms-2" style="background:#e7e7e7; border:1px solid #ccc; font-size:12px; padding:4px 12px;">
            Kembali
        </a>
    </div>

    <table class="table-detail-prof">
        <tbody>
            <tr>
                <th>Kd Sarana</th>
                <td>{{ $sarana->kd_sarana }}</td>
            </tr>
            <tr>
                <th>Nm Sarana</th>
                <td>{{ $sarana->nm_sarana }}</td>
            </tr>
            <tr>
                <th>Kd Sarana Reff</th>
                <td>{{ $sarana->kd_sarana_reff ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diubah Pada</th>
                <td>{{ $sarana->updated_at ? \Carbon\Carbon::parse($sarana->updated_at)->format('d-m-Y H:i:s') : '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

