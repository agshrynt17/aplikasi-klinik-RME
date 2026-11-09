@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .profaskes-title-box {
        padding-bottom: 8px;
        margin-bottom: 12px;
        position: relative;
    }
    .profaskes-title-box h3 {
        font-size: 20px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .profaskes-orange-line {
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
        padding: 6px 18px;
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
        padding: 6px 18px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-prof-tutup:hover {
        background-color: #d68407;
        color: #fff;
    }
    .form-group-prof {
        margin-bottom: 18px;
    }
    .form-group-prof label {
        font-size: 13px;
        color: #333;
        margin-bottom: 6px;
        display: block;
    }
    .form-control-prof {
        border-radius: 3px;
        border: 1px solid #d2d6de;
        padding: 7px 12px;
        font-size: 13px;
        color: #555;
        width: 100%;
    }
    .form-control-prof:focus {
        border-color: #3c8dbc;
        box-shadow: none;
        outline: none;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center profaskes-title-box">
        <h3>Tambah Stok Awal Per Gudang</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <a href="/stok/index">Stoks</a> <span class="mx-1">&gt;</span> <span>Tambah Stok Awal Per Gudang</span>
        </div>
    </div>
    <div class="profaskes-orange-line"></div>

    <!-- Error Alerts -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small" role="alert">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 4px;">
        <div class="card-body p-4">
            <form action="/stok/store" method="POST">
                @csrf

                <!-- Kode Gudang -->
                <div class="form-group-prof">
                    <label for="gudang_id">Kode Gudang</label>
                    <select name="gudang_id" id="gudang_id" class="form-control-prof" required>
                        <option value="">--Pilih--</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ old('gudang_id', $selectedGudang->id ?? '') == $g->id ? 'selected' : '' }}>
                                {{ $g->nama_gudang }} ({{ $g->kode_gudang ?? '00' . $g->id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Barang -->
                <div class="form-group-prof">
                    <label for="master_barang_id">Nama Barang</label>
                    <select name="master_barang_id" id="master_barang_id" class="form-control-prof" required>
                        <option value="">Ketik Nama Barang</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}" {{ old('master_barang_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->kode_barang }} - {{ $b->nama_barang }} ({{ $b->jenis ?? 'Obat' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stok Awal -->
                <div class="form-group-prof">
                    <label for="stok_awal">Stok Awal</label>
                    <input type="number" name="stok_awal" id="stok_awal" class="form-control-prof" placeholder="" value="{{ old('stok_awal') }}" required>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex align-items-center gap-2 mt-4 pt-2">
                    <button type="submit" class="btn btn-prof-simpan">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="/stok/index" class="btn btn-prof-tutup">
                        Tutup
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
