@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-unit-page {
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
    .card-detail-box {
        background: #fff;
        border: 1px solid #d2d6de;
        border-top: 3px solid #00c0ef;
        border-radius: 3px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .table-detail-info th {
        background-color: #f9f9f9;
        width: 220px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        padding: 8px 12px;
        border: 1px solid #e4e7ea;
    }
    .table-detail-info td {
        font-size: 13px;
        color: #333;
        padding: 8px 12px;
        border: 1px solid #e4e7ea;
    }
    .btn-kembali {
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        color: #444 !important;
        font-size: 12.5px;
        padding: 6px 16px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-kembali:hover {
        background-color: #e7e7e7;
    }
</style>

<div class="satusehat-unit-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Detail Unit Layanan Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-unit/index">Setting Data Unit Satu Sehat</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="{{ url()->current() }}?id={{ $unit->id }}">Detail Unit</a>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="card-detail-box shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h5 class="fw-bold mb-0 text-dark" style="font-size:15px;">
                <i class="fas fa-sitemap text-info me-1"></i> Informasi Pemetaan Unit Layanan
            </h5>
            <span class="badge bg-success px-3 py-2" style="font-size:12px;">{{ $unit->status }}</span>
        </div>

        <table class="table table-bordered table-detail-info mb-3">
            <tr>
                <th>Unit Pada (Satu Sehat)</th>
                <td class="fw-bold text-primary">{{ $unit->unit_satusehat }}</td>
            </tr>
            <tr>
                <th>Unit Pada (Aplikasi)</th>
                <td class="fw-bold text-dark">{{ $unit->unit_aplikasi }}</td>
            </tr>
            <tr>
                <th>Location ID Satu Sehat</th>
                <td>{{ $unit->location_id_satusehat ?: '-' }}</td>
            </tr>
            <tr>
                <th>Status Integrasi</th>
                <td><span class="badge bg-success">{{ $unit->status }}</span></td>
            </tr>
            <tr>
                <th>Terakhir Diubah Pada</th>
                <td>{{ $unit->diubah_pada ? \Carbon\Carbon::parse($unit->diubah_pada)->format('d-m-Y H:i:s') : '25-12-2023 10:04:44' }}</td>
            </tr>
        </table>

        <div class="mt-3">
            <a href="/satusehat-unit/index" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali ke Setting Data Unit
            </a>
        </div>
    </div>
</div>
@endsection

