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
    .btn-prof-save {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 20px;
        border-radius: 3px;
    }
    .btn-prof-save:hover {
        background-color: #008d4c;
        color: #fff;
    }
    .btn-prof-batal {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 20px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-prof-batal:hover {
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
        <h3>{{ isset($item) ? 'Edit Batch & ED Barang' : 'Tambah Batch & ED Barang' }}</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <a href="/batch/index">Batches</a> <span class="mx-1">&gt;</span> <span>{{ isset($item) ? 'Edit Batch & ED Barang' : 'Tambah Batch & ED Barang' }}</span>
        </div>
    </div>
    <div class="profaskes-orange-line"></div>

    <!-- Error Alert -->
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
            <form action="{{ isset($item) ? '/batch/' . $item->id : '/batch/store' }}" method="POST" id="formBatch">
                @csrf
                @if(isset($item))
                    @method('PUT')
                @endif

                <input type="hidden" name="master_gudang_id" value="{{ old('master_gudang_id', $item->master_gudang_id ?? ($defaultGudang->id ?? '')) }}">

                <!-- Kode - Nama Barang -->
                <div class="form-group-prof">
                    <label for="master_barang_id">Kode - Nama Barang</label>
                    <select name="master_barang_id" id="master_barang_id" class="form-control-prof" required>
                        <option value="">Pilih barang</option>
                        @foreach($barangs as $brg)
                            <option value="{{ $brg->id }}" {{ old('master_barang_id', $item->master_barang_id ?? '') == $brg->id ? 'selected' : '' }}>
                                {{ $brg->kode_barang }} - {{ $brg->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- No Batch -->
                <div class="form-group-prof">
                    <label for="nomor_batch">No Batch</label>
                    <input type="text" name="nomor_batch" id="nomor_batch" class="form-control-prof" value="{{ old('nomor_batch', $item->nomor_batch ?? '') }}" required autocomplete="off">
                </div>

                <!-- Tgl. Expire -->
                <div class="form-group-prof">
                    <label for="tanggal_kadaluarsa">Tgl. Expire</label>
                    <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" class="form-control-prof" value="{{ old('tanggal_kadaluarsa', isset($item) && $item->tanggal_kadaluarsa ? \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('Y-m-d') : '') }}">
                </div>

                <!-- Status Batch -->
                <div class="form-group-prof">
                    <label>Status Batch</label>
                    <div class="d-flex align-items-center gap-4 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_active" id="statusAktif" value="1" {{ !isset($item) || $item->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusAktif" style="font-size: 13px; cursor: pointer;">
                                Aktif (Digunakan)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_active" id="statusNonAktif" value="0" {{ isset($item) && !$item->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusNonAktif" style="font-size: 13px; cursor: pointer;">
                                Non Aktif (Tidak Digunakan)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex align-items-center gap-2 mt-4 pt-2">
                    <button type="submit" class="btn btn-prof-save">
                        Save
                    </button>
                    <a href="/batch/index" class="btn-prof-batal">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
