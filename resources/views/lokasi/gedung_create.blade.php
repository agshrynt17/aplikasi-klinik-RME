@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', (isset($item) ? 'Ubah Gedung' : 'Tambah Gedung') . ' - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="tambah-gedung-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">
            {{ isset($item) ? 'Ubah Gedung' : 'Tambah Gedung' }}
        </h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/master-gedung" class="text-decoration-none text-muted">Gedung</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">
                    {{ isset($item) ? 'Ubah Gedung' : 'Tambah Gedung' }}
                </li>
            </ol>
        </nav>
    </div>

    <!-- Blue Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #00c0ef;"></div>

    @if($errors->any())
    <div class="alert alert-danger py-2 px-3 mb-3 small">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Content Card -->
    <div class="card border rounded-2 shadow-none bg-white p-4">
        <form action="{{ isset($item) ? '/master-gedung/' . $item->id : '/master-gedung' }}" method="POST">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label small fw-bold text-dark mb-1">Kode Gedung</label>
                <input type="text" name="kode_gedung" class="form-control form-control-sm @error('kode_gedung') is-invalid @enderror" 
                       value="{{ old('kode_gedung', $item->kode_gedung ?? '') }}" 
                       placeholder="" required style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                @error('kode_gedung')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-dark mb-1">Nama Gedung</label>
                <input type="text" name="nama_gedung" class="form-control form-control-sm @error('nama_gedung') is-invalid @enderror" 
                       value="{{ old('nama_gedung', $item->nama_gedung ?? '') }}" 
                       placeholder="" required style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                @error('nama_gedung')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" 
                        style="background-color: #00a65a; border-color: #008d4c; height: 34px; min-width: 80px;">
                    Simpan
                </button>
                <a href="/master-gedung" class="btn btn-sm text-white px-3 fw-semibold d-flex align-items-center justify-content-center" 
                   style="background-color: #f0ad4e; border-color: #eea236; height: 34px; min-width: 80px; text-decoration: none;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
