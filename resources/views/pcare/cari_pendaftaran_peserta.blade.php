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
        margin-bottom: 15px;
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

    .form-group-pcare {
        margin-bottom: 15px;
    }
    .form-group-pcare label {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
        display: block;
    }
    .input-pcare-full {
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 13px;
        color: #555;
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        outline: none;
        box-sizing: border-box;
    }
    .input-pcare-full:focus {
        border-color: #3c8dbc;
    }

    .btn-blue-ok {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 12px;
        padding: 6px 16px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-blue-ok:hover { background-color: #00acd6; }

    .btn-orange-batal {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 6px 16px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-orange-batal:hover { background-color: #d68407; }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Cari Pendaftaran BPJS Berdasarkan No Antrian/No Urut</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-pendaftaran/index">Cek Data Terdaftar Pada PCare</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-pendaftaran/cari-peserta">Cari Pendaftaran BPJS Berdasarkan No Antrian/No Urut</a>
        </div>
    </div>

    {{-- Main Form Card --}}
    <form method="GET" action="/pcare-pendaftaran/cari-peserta">
        <div class="form-group-pcare">
            <label>Tanggal Daftar</label>
            <input type="text" name="tanggal_daftar" class="input-pcare-full" value="{{ $tanggal_daftar }}">
        </div>

        <div class="form-group-pcare">
            <label>Masukan Nomor Urut/ Antrian Peserta sesuai data dari BPJS</label>
            <input type="text" name="no_urut" class="input-pcare-full" value="{{ $no_urut }}" placeholder="Ketik No Antrian/No Urut Peserta">
        </div>

        <div style="display:flex; gap:6px; margin-top:15px;">
            <button type="submit" class="btn-blue-ok">
                <i class="fas fa-check"></i> OK! Cari
            </button>
            <a href="/pcare-pendaftaran/index" class="btn-orange-batal">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>

    {{-- Hasil Pencarian jika ada --}}
    @if($searched)
    <div class="card mt-4 shadow-sm border-0" style="border-radius:3px;">
        <div class="card-header bg-primary text-white py-2 px-3" style="background:#3c8dbc !important;">
            <h6 class="mb-0 small fw-bold"><i class="fas fa-info-circle me-1"></i> Hasil Pencarian Pendaftaran Peserta</h6>
        </div>
        <div class="card-body p-3">
            @if($hasil)
            <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                <tr><th style="width:160px; background:#f9f9f9;">Nomor Urut</th><td><b>{{ $hasil->no_urut }}</b></td></tr>
                <tr><th style="background:#f9f9f9;">Nomor Kartu (KIS)</th><td>{{ $hasil->no_kartu }}</td></tr>
                <tr><th style="background:#f9f9f9;">Nama Pasien</th><td class="fw-bold">{{ $hasil->nama_pasien }}</td></tr>
                <tr><th style="background:#f9f9f9;">Poli Tujuan</th><td>{{ $hasil->poli }}</td></tr>
                <tr><th style="background:#f9f9f9;">Keluhan</th><td>{{ $hasil->keluhan }}</td></tr>
                <tr><th style="background:#f9f9f9;">Status</th><td><span class="badge bg-success">{{ $hasil->status }}</span></td></tr>
            </table>
            @else
            <div class="alert alert-warning py-2 mb-0 small">
                <i class="fas fa-exclamation-triangle me-1"></i> Data pendaftaran dengan nomor urut <b>{{ $no_urut }}</b> pada tanggal <b>{{ $tanggal_daftar }}</b> tidak ditemukan.
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

