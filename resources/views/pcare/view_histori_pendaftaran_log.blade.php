@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-histori-page {
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
        border-top: 3px solid #3c8dbc;
        border-radius: 3px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .table-detail-info th {
        background-color: #f9f9f9;
        width: 190px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        padding: 6px 12px;
        border: 1px solid #e4e7ea;
    }
    .table-detail-info td {
        font-size: 13px;
        color: #333;
        padding: 6px 12px;
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

<div class="pcare-histori-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Detail Log Pendaftaran Ke PCare</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-pendaftaran/index-log">Histori Pendaftaran Data Ke Pcare</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="{{ url()->current() }}?id={{ $pendaftaran->id }}">Detail Log</a>
        </div>
    </div>

    {{-- Detail Info Card --}}
    <div class="card-detail-box shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h5 class="fw-bold mb-0 text-dark" style="font-size:15px;">
                <i class="fas fa-history text-primary me-1"></i> Data Transaksi Registrasi & Bridging PCare
            </h5>
            <span class="badge bg-primary px-3 py-2" style="font-size:12px;">Status PCare: {{ $pendaftaran->status_pcare ?: 'Sukses Terdaftar' }}</span>
        </div>

        <table class="table table-bordered table-detail-info mb-3">
            <tr>
                <th>Kode Registrasi</th>
                <td class="fw-bold text-primary">{{ $pendaftaran->no_rawat ?? $pendaftaran->nomor_registrasi }}</td>
                <th>Tanggal & Jam Daftar</th>
                <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d F Y') }} {{ $pendaftaran->jam_daftar }}</td>
            </tr>
            <tr>
                <th>Nama Pasien</th>
                <td class="fw-bold text-dark">{{ strtoupper($pendaftaran->pasien->nama_pasien ?? '-') }}</td>
                <th>No. Rekam Medis (RM)</th>
                <td><span class="badge bg-secondary">{{ $pendaftaran->pasien->no_rm ?? '-' }}</span></td>
            </tr>
            <tr>
                <th>No. Kartu BPJS (KIS)</th>
                <td class="fw-bold text-success">{{ $pendaftaran->pasien->no_bpjs ?: ($pendaftaran->no_bpjs_asuransi ?: '-') }}</td>
                <th>NIK / No KTP</th>
                <td>{{ $pendaftaran->pasien->nik ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin / Tgl Lahir</th>
                <td>
                    {{ ($pendaftaran->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki' }} /
                    {{ $pendaftaran->pasien->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->pasien->tanggal_lahir)->format('d-m-Y') : '-' }}
                </td>
                <th>Poliklinik Tujuan</th>
                <td class="fw-bold">{{ strtoupper($pendaftaran->poliklinik->nama_poli ?? 'POLI UMUM') }}</td>
            </tr>
            <tr>
                <th>Dokter Penanggung Jawab</th>
                <td>{{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</td>
                <th>Penjamin</th>
                <td>{{ $pendaftaran->penjamin->nama_penjamin ?? 'BPJS Kesehatan' }}</td>
            </tr>
            <tr>
                <th>No Urut Antrian BPJS</th>
                <td class="fw-bold">{{ $pendaftaran->no_antrian ?: 'A109' }}</td>
                <th>Status Pelayanan</th>
                <td><span class="badge bg-success">Sudah Terkirim</span></td>
            </tr>
            <tr>
                <th>Keluhan Pasien</th>
                <td colspan="3">{{ $pendaftaran->keluhan ?? '-' }}</td>
            </tr>
        </table>

        <div class="mt-3">
            <a href="/pcare-pendaftaran/index-log" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali ke Histori Pendaftaran
            </a>
        </div>
    </div>
</div>
@endsection

