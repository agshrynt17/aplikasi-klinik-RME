@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .konhakes-title-box {
        padding-bottom: 8px;
        margin-bottom: 12px;
        position: relative;
    }
    .konhakes-title-box h3 {
        font-size: 20px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .konhakes-orange-line {
        height: 2px;
        background-color: #f39c12;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .btn-prof-simpan {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 20px;
        border-radius: 3px;
    }
    .btn-prof-simpan:hover {
        background-color: #008d4c;
        color: #fff;
    }
    .btn-prof-tutup {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 20px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-prof-tutup:hover {
        background-color: #d68407;
        color: #fff;
    }
    .btn-prof-template {
        background-color: #00c0ef;
        border-color: #00acd6;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 16px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-prof-template:hover {
        background-color: #00acd6;
        color: #fff;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center konhakes-title-box">
        <h3>Upload Stok Awal Per Gudang</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <a href="/stok/index">Stoks</a> <span class="mx-1">&gt;</span> <span>Upload Stok Awal</span>
        </div>
    </div>
    <div class="konhakes-orange-line"></div>

    <!-- Error / Success Alert -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small" role="alert">
        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm" style="border-radius: 4px; border-top: 3px solid #00c0ef;">
                <div class="card-body p-4">
                    <form action="/stok/upload" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Pilih Gudang -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 13px;">Gudang Tujuan <span class="text-danger">*</span></label>
                            <select name="gudang_id" id="gudang_id" class="form-select form-select-sm" style="font-size: 13px;" required>
                                @foreach($gudangs as $g)
                                    <option value="{{ $g->id }}" {{ ($selectedGudang->id ?? '') == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama_gudang }} ({{ $g->kode_gudang ?? '00' . $g->id }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih gudang tempat stok awal ini akan dimasukkan.</small>
                        </div>

                        <!-- File Input -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="font-size: 13px;">File CSV / Excel Stok Awal <span class="text-danger">*</span></label>
                            <input type="file" name="file_stok" id="file_stok" class="form-control form-control-sm" accept=".csv, .txt, .xlsx, .xls" required>
                            <small class="text-muted">Gunakan file format <strong>.csv</strong> sesuai template yang disediakan.</small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex align-items-center gap-2 pt-2 border-top">
                            <button type="submit" class="btn btn-prof-simpan">
                                <i class="fas fa-upload me-1"></i> Upload & Simpan Stok
                            </button>
                            <a href="/stok/index" class="btn btn-prof-tutup">
                                Tutup
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Guide & Template Download Box -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius: 4px; border-top: 3px solid #f39c12;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-warning me-2"></i>Petunjuk Upload Stok</h6>
                    <p class="small text-muted mb-3">
                        Untuk mempermudah pengisian, silakan unduh format template yang sudah terisi otomatis dengan seluruh produk obat/barang yang aktif di klinik Anda.
                    </p>

                    <div class="mb-4">
                        <a href="/stok/template-upload" class="btn btn-prof-template w-100 justify-content-center">
                            <i class="fas fa-download"></i> Download Format Template (CSV)
                        </a>
                    </div>

                    <div class="small">
                        <div class="fw-semibold mb-1">Susunan Kolom File CSV:</div>
                        <ul class="text-muted ps-3 mb-3">
                            <li><strong>Kode Item</strong>: Kode barang (contoh: <code>BRO25-000001</code>)</li>
                            <li><strong>Nama Barang</strong>: Nama produk obat/barang</li>
                            <li><strong>Jenis Barang</strong>: Obat / Alkes / ATK</li>
                            <li><strong>Stok Awal</strong>: Jumlah kuantitas stok fisik</li>
                        </ul>
                        <div class="alert alert-info py-2 px-3 small mb-0">
                            <i class="fas fa-lightbulb me-1"></i> <strong>Tips:</strong> Anda juga dapat mengedit langsung file hasil dari tombol <strong>Export Data(xls)</strong> lalu mengunggahnya kembali di sini.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
