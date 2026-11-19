@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    @php
        $isEdit = isset($item);
        $title = $isEdit ? 'Edit Data Perujuk Eksternal' : 'Tambah Data Perujuk Eksternal';
    @endphp

    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">{{ $title }}</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/perujuk-eksternal/index" style="color: #777; text-decoration: none;">Perujuk Eksternals</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">{{ $title }}</span>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger py-2 px-3 mb-3" style="font-size: 12px; border-radius: 3px;">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Section -->
    <form method="POST" action="{{ $isEdit ? url('/perujuk-eksternal/' . $item->id) : url('/perujuk-eksternal') }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label mb-1" style="font-size: 13px; color: #333; font-weight: 400;">Nama Pengirim / Perujuk</label>
            <input type="text" name="nama_perujuk" value="{{ old('nama_perujuk', $item->nama_perujuk ?? '') }}" required class="form-control form-control-sm" style="height: 34px; border-radius: 3px; border: 1px solid #d2d6de; width: 100%; font-size: 13px;">
        </div>

        <div class="mb-3">
            <label class="form-label mb-1" style="font-size: 13px; color: #333; font-weight: 400;">Faskes / Instansi Pengirim</label>
            <input type="text" name="institusi" value="{{ old('institusi', $item->institusi ?? '') }}" class="form-control form-control-sm" style="height: 34px; border-radius: 3px; border: 1px solid #d2d6de; width: 100%; font-size: 13px;">
        </div>

        <div class="mb-3">
            <label class="form-label mb-1" style="font-size: 13px; color: #333; font-weight: 400;">No Telp/HP</label>
            <input type="text" name="no_telepon" value="{{ old('no_telepon', $item->no_telepon ?? '') }}" class="form-control form-control-sm" style="height: 34px; border-radius: 3px; border: 1px solid #d2d6de; width: 100%; font-size: 13px;">
        </div>

        <div class="mb-3">
            <label class="form-label mb-1" style="font-size: 13px; color: #333; font-weight: 400;">Alamat Faskes/Instansi</label>
            <input type="text" name="alamat" value="{{ old('alamat', $item->alamat ?? '') }}" class="form-control form-control-sm" style="height: 34px; border-radius: 3px; border: 1px solid #d2d6de; width: 100%; font-size: 13px;">
        </div>

        <div class="mb-4">
            <label class="form-label mb-1 d-block" style="font-size: 13px; color: #333; font-weight: 400;">Status</label>
            @php
                $statusVal = old('is_active', isset($item) ? ($item->is_active ? '1' : '0') : '1');
            @endphp
            <div class="d-flex align-items-center gap-4">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="radio" name="is_active" id="statusAktif" value="1" {{ $statusVal == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusAktif" style="font-size: 13px; color: #333; cursor: pointer;">
                        Aktif
                    </label>
                </div>
                <div class="form-check mb-0">
                    <input class="form-check-input" type="radio" name="is_active" id="statusNonAktif" value="0" {{ $statusVal == '0' ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusNonAktif" style="font-size: 13px; color: #333; cursor: pointer;">
                        Non Aktif
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="submit" class="btn text-white btn-sm" style="background-color: #00a65a; border-color: #008d4c; font-size: 13px; font-weight: 500; border-radius: 3px; padding: 6px 18px; display: inline-flex; align-items: center; gap: 6px;">
                <i class="far fa-save"></i> Simpan Data
            </button>
            <a href="/perujuk-eksternal/index" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; font-size: 13px; font-weight: 500; border-radius: 3px; padding: 6px 18px; text-decoration: none;">
                Tutup
            </a>
        </div>
    </form>
</div>
@endsection
