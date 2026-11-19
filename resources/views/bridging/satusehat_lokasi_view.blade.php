@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-lokasi-page {
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
        width: 240px;
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

<div class="satusehat-lokasi-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Detail Ruang Rawat Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-lokasi/index">Setting Ruang Rawat - Satu Sehat</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <span>Detail Ruangan</span>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="card-detail-box shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h5 class="fw-bold mb-0 text-dark" style="font-size:15px;">
                <i class="fas fa-bed text-info me-1"></i> Informasi Ruangan
            </h5>
            <span class="badge bg-success px-3 py-2" style="font-size:12px;">{{ $lokasi->status }}</span>
        </div>

        <table class="table table-bordered table-detail-info mb-3">
            <tr>
                <th>Nama Ruangan (Satu Sehat)</th>
                <td class="fw-bold text-primary">{{ $lokasi->nama_ruangan_satusehat }}</td>
            </tr>
            <tr>
                <th>Location ID (Satu Sehat)</th>
                <td><code>{{ $lokasi->location_id_satusehat ?? '-' }}</code></td>
            </tr>
            <tr>
                <th>Referensi Ruangan (Aplikasi)</th>
                <td><span class="text-danger fw-bold">{{ $lokasi->referensi_ruangan ?? '-' }}</span></td>
            </tr>
            <tr>
                <th>Tipe Organisasi / Ruangan</th>
                <td>{{ $lokasi->nama_tipe ?? 'Room' }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $lokasi->deskripsi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Kontak (Telp / Email)</th>
                <td>{{ $lokasi->telp ?? '-' }} / {{ $lokasi->email ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alamat Lengkap</th>
                <td>{{ $lokasi->alamat ?? '-' }}, RT {{ $lokasi->rt }}/RW {{ $lokasi->rw }}, {{ $lokasi->desa }}, {{ $lokasi->kecamatan }}, {{ $lokasi->kabupaten }}, {{ $lokasi->provinsi }} {{ $lokasi->kode_pos }}</td>
            </tr>
            <tr>
                <th>Koordinat (Long, Lat, Alt)</th>
                <td>{{ $lokasi->longitude }}, {{ $lokasi->latitude }}, {{ $lokasi->altitude }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah</th>
                <td>{{ $lokasi->diubah_pada ? date('d-m-Y H:i:s', strtotime($lokasi->diubah_pada)) : ($lokasi->updated_at ? $lokasi->updated_at->format('d-m-Y H:i:s') : '-') }}</td>
            </tr>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
            <a href="/satusehat-lokasi/index" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection

