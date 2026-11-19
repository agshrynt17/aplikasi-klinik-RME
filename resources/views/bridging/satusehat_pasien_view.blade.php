@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-pasien-page {
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

<div class="satusehat-pasien-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Detail Pasien Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-pasien/index">Pencarian ID Pasien - Satu Sehat</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <span>Detail Pasien</span>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="card-detail-box shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h5 class="fw-bold mb-0 text-dark" style="font-size:15px;">
                <i class="fas fa-user-injured text-info me-1"></i> Informasi Pasien
            </h5>
            @if($pasien->kode_ihs_satusehat)
                <span class="badge bg-success px-3 py-2" style="font-size:12px;">Terdaftar di Satu Sehat</span>
            @else
                <span class="badge bg-warning text-dark px-3 py-2" style="font-size:12px;">Belum Sinkron Satu Sehat</span>
            @endif
        </div>

        <table class="table table-bordered table-detail-info mb-3">
            <tr>
                <th>No. Rekam Medis (Kode RM)</th>
                <td class="fw-bold text-primary">{{ $pasien->no_rm ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nama Pasien</th>
                <td class="fw-bold" style="text-transform: uppercase;">{{ $pasien->nama_pasien }}</td>
            </tr>
            <tr>
                <th>ID Satu Sehat (IHS ID)</th>
                <td>
                    @if($pasien->kode_ihs_satusehat)
                        <code class="fs-6 fw-bold text-success">{{ $pasien->kode_ihs_satusehat }}</code>
                        @if($pasien->nama_satusehat)
                            <span class="text-muted ms-2">(a.n: {{ $pasien->nama_satusehat }})</span>
                        @endif
                    @else
                        <span class="text-muted fst-italic">Belum ada ID Satu Sehat</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Nomor Induk Kependudukan (NIK)</th>
                <td class="fw-bold">{{ $pasien->nik ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nomor BPJS / KIS</th>
                <td>{{ $pasien->no_bpjs ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tempat &amp; Tanggal Lahir</th>
                <td>{{ $pasien->tempat_lahir ?? '-' }}, {{ $pasien->tanggal_lahir ? date('d/m/Y', strtotime($pasien->tanggal_lahir)) : '-' }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-Laki' : ($pasien->jenis_kelamin == 'P' ? 'Perempuan' : ($pasien->jenis_kelamin ?? '-')) }}</td>
            </tr>
            <tr>
                <th>No. Handphone / Telepon</th>
                <td>{{ $pasien->no_hp ?? $pasien->no_telp ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alamat Lengkap</th>
                <td>{{ $pasien->alamat ?? '-' }}, {{ $pasien->desa ?? '' }}, {{ $pasien->kecamatan ?? '' }}, {{ $pasien->kabupaten ?? '' }} {{ $pasien->kode_pos ?? '' }}</td>
            </tr>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
            <a href="/satusehat-pasien/index" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection

