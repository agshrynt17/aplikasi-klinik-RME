@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-normal mb-0 text-dark" style="font-size: 24px; color: #333;">
        {{ $isEdit ? 'Edit Layanan Antrian' : 'Tambah Layanan Antrian' }}
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/master-antrian" class="text-decoration-none text-muted">Master Antrians</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">
                {{ $isEdit ? 'Edit Layanan Antrian' : 'Tambah Layanan Antrian' }}
            </li>
        </ol>
    </nav>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 3px;">
    <ul class="mb-0 small">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Main Form Card Box -->
<div class="bg-white shadow-sm rounded-1 p-4 mb-4" style="border-top: 3px solid #00c0ef; border-radius: 3px;">
    <form action="{{ $isEdit ? url('/master-antrian/' . $item->id) : url('/master-antrian') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <!-- Row 1: Kode Antrian & Poli Klinik -->
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3 col-lg-3">
                <label for="kode_antrian" class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">
                    Kode Antrian <span style="color: #e74c3c; font-style: italic; font-weight: normal; font-size: 12px;">*wajib diisi!</span>
                </label>
                <select class="form-select" id="kode_antrian" name="kode_antrian" required style="font-size: 13px; border-radius: 3px; height: 36px;">
                    <option value="">--Kode Antrian--</option>
                    @php
                        $currentKode = old('kode_antrian', $item->kode_antrian ?? '');
                        $kodes = array_merge(range(0, 9), range('A', 'Z'));
                    @endphp
                    @foreach($kodes as $k)
                        <option value="{{ $k }}" {{ (string)$currentKode === (string)$k ? 'selected' : '' }}>
                            {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-4 col-lg-4">
                <label for="poliklinik_id" class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">
                    Poli Klinik
                </label>
                <select class="form-select" id="poliklinik_id" name="poliklinik_id" style="font-size: 13px; border-radius: 3px; height: 36px;">
                    <option value="">--Pilih layanan/Klinik</option>
                    @php
                        $selectedPoli = old('poliklinik_id', $item->poliklinik_id ?? '');
                    @endphp
                    @foreach($polikliniks as $poli)
                        <option value="{{ $poli->id }}" {{ (string)$selectedPoli === (string)$poli->id ? 'selected' : '' }}>
                            {{ $poli->nama_poli }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Row 2: Nama Antrian/Pelayanan -->
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-6">
                <label for="nama_antrian" class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">
                    Nama Antrian/Pelayanan <span style="color: #e74c3c; font-style: italic; font-weight: normal; font-size: 12px;">*wajib diisi!</span>
                </label>
                <input type="text" class="form-control" id="nama_antrian" name="nama_antrian" 
                    value="{{ old('nama_antrian', $item->nama_antrian ?? '') }}" 
                    placeholder="Contoh : Pendaftaran" 
                    required 
                    style="font-size: 13px; border-radius: 3px; height: 36px;">
                <div class="form-text mt-1" style="font-size: 12px; color: #777;">
                    Nama antrian akan muncul pada ticket antrian
                </div>
            </div>
        </div>

        <!-- Buttons Row -->
        <div class="d-flex align-items-center gap-2">
            <button type="submit" class="btn text-white fw-normal" style="background-color: #00a65a; border-color: #008d4c; font-size: 14px; padding: 6px 18px; border-radius: 3px;">
                Simpan
            </button>
            <a href="/master-antrian" class="btn text-white fw-normal" style="background-color: #f39c12; border-color: #e08e0b; font-size: 14px; padding: 6px 18px; border-radius: 3px;">
                Tutup
            </a>

            @if($isEdit)
            <button type="button" class="btn btn-outline-danger ms-auto fw-normal" id="btnHapusLayanan" style="font-size: 13px; padding: 6px 14px; border-radius: 3px;">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
            @endif
        </div>
    </form>

    @if($isEdit)
    <form id="formHapusLayanan" action="{{ url('/master-antrian/' . $item->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>

@if($isEdit)
<script>
$(document).ready(function() {
    $('#btnHapusLayanan').click(function() {
        if(confirm('Yakin ingin menghapus layanan antrian ini?')) {
            $('#formHapusLayanan').submit();
        }
    });
});
</script>
@endif

@endsection
