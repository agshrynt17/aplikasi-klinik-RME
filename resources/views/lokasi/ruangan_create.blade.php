@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', (isset($item) ? 'Ubah Ruangan' : 'Tambah Ruangan') . ' - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="tambah-ruangan-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">
            {{ isset($item) ? 'Ubah Ruangan' : 'Tambah Ruangan' }}
        </h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/master-ruangan" class="text-decoration-none text-muted">Ruang</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">{{ isset($item) ? 'Ubah Ruangan' : 'Tambah Ruangan' }}</a></li>
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
        <form action="{{ isset($item) ? '/master-ruangan/' . $item->id : '/master-ruangan' }}" method="POST">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label small fw-bold text-dark mb-1">Gedung & Lantai <span class="text-danger">*</span></label>
                <select name="lantai_id" class="form-select form-select-sm @error('lantai_id') is-invalid @enderror" required 
                        style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                    <option value="">-- Pilih Gedung & Lantai --</option>
                    @foreach($lantais as $l)
                        @php
                            $gNama = $l->gedung ? $l->gedung->nama_gedung : '';
                            $label = $gNama ? ($gNama . ' - ' . $l->nama_lantai) : $l->nama_lantai;
                        @endphp
                        <option value="{{ $l->id }}" {{ old('lantai_id', $item->lantai_id ?? '') == $l->id ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('lantai_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-dark mb-1">Kode Ruang <span class="text-danger">*</span></label>
                <input type="text" name="kode_ruangan" class="form-control form-control-sm @error('kode_ruangan') is-invalid @enderror" 
                       value="{{ old('kode_ruangan', $item->kode_ruangan ?? '') }}" 
                       placeholder="Contoh: R101" required style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                @error('kode_ruangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-dark mb-1">Nama Ruang <span class="text-danger">*</span></label>
                <input type="text" name="nama_ruangan" class="form-control form-control-sm @error('nama_ruangan') is-invalid @enderror" 
                       value="{{ old('nama_ruangan', $item->nama_ruangan ?? '') }}" 
                       placeholder="Contoh: Ruang Rawat 1" required style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                @error('nama_ruangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Poli Klinik</label>
                    <input type="text" name="poli_klinik" class="form-control form-control-sm" 
                           value="{{ old('poli_klinik', $item->poli_klinik ?? '') }}" 
                           placeholder="Opsional..." style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Ruang Ranap</label>
                    <input type="text" name="ruang_ranap" class="form-control form-control-sm" 
                           value="{{ old('ruang_ranap', $item->ruang_ranap ?? '') }}" 
                           placeholder="Opsional (Ya/Tidak)..." style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Kapasitas Tempat Tidur</label>
                    <input type="text" name="kapasitas_tempat_tidur" class="form-control form-control-sm" 
                           value="{{ old('kapasitas_tempat_tidur', $item->kapasitas_tempat_tidur ?? '-') }}" 
                           placeholder="Contoh: 1, 2, atau -" style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" 
                        style="background-color: #00a65a; border-color: #008d4c; height: 34px; min-width: 80px;">
                    Simpan
                </button>
                <a href="/master-ruangan" class="btn btn-sm text-white px-3 fw-semibold d-flex align-items-center justify-content-center" 
                   style="background-color: #f0ad4e; border-color: #eea236; height: 34px; min-width: 80px; text-decoration: none;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
