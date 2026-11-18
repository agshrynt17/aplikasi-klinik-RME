@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', isset($gudang) ? 'Edit Gudang' : 'Tambah Gudang')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES Screenshot 2) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            {{ isset($gudang) ? 'Edit Gudang' : 'Tambah Gudang' }}
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/gudang/index" style="color: #777; text-decoration: none;">Gudang</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">{{ isset($gudang) ? 'Edit Gudang' : 'Tambah Gudang' }}</span>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    <!-- Form Container (1:1 KONHAKES Screenshot 2) -->
    <form action="{{ isset($gudang) ? url('/gudang/' . $gudang->id) : url('/gudang/store') }}" method="POST">
        @csrf
        @if(isset($gudang))
            @method('PUT')
        @endif

        <!-- Kode Gudang -->
        <div class="mb-3">
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kode Gudang</label>
            <input type="text" name="kode_gudang" class="form-control" 
                value="{{ old('kode_gudang', $kode_gudang ?? ($gudang->kode_gudang ?? '')) }}" 
                required 
                style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
        </div>

        <!-- Nama Gudang -->
        <div class="mb-4">
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Nama Gudang</label>
            <input type="text" name="nama_gudang" class="form-control" 
                value="{{ old('nama_gudang', $gudang->nama_gudang ?? '') }}" 
                required 
                placeholder="Contoh: Gudang Farmasi"
                style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
        </div>

        <!-- Action Buttons (Simpan & Batal 1:1 KONHAKES Screenshot 2) -->
        <div class="d-flex align-items-center gap-1">
            <button type="submit" class="btn text-white" style="background-color: #00a65a; border-color: #008d4c; height: 34px; padding: 0 20px; font-size: 13px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center;">
                Simpan
            </button>
            <a href="/gudang/index" class="btn text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 34px; padding: 0 18px; font-size: 13px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
