@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@php
    $isEdit = isset($tpa);
    $pageTitle = $isEdit ? 'Edit TPA' : 'Tambah TPA';
@endphp

@section('title', $pageTitle . ' - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="tambah-tpa-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; letter-spacing: 0.3px;">{{ $pageTitle }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/master-tpa" class="text-decoration-none text-muted">TPA</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>

    <!-- Main Card with Cyan Line -->
    <div class="card border rounded-1 shadow-none bg-white p-4" style="border-top: 2px solid #00c0ef !important;">
        <form action="{{ $isEdit ? '/master-tpa/' . $tpa->id : '/master-tpa' }}" method="POST" id="formTpa">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="row gx-4 gy-3">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <div class="row g-2 mb-3">
                        <div class="col-5">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">
                                Kode <span style="color: #dd4b39; font-style: italic; font-size: 11px;">*wajib!</span>
                            </label>
                            <input type="text" name="kode_tpa" class="form-control form-control-sm @error('kode_tpa') is-invalid @enderror" 
                                   value="{{ old('kode_tpa', $tpa->kode_tpa ?? '') }}" 
                                   style="height: 34px; font-size: 12.5px;" required>
                            @error('kode_tpa')
                                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-7">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">
                                Nama TPA <span style="color: #dd4b39; font-style: italic; font-size: 11px;">*wajib diisi!</span>
                            </label>
                            <input type="text" name="nama_tpa" class="form-control form-control-sm @error('nama_tpa') is-invalid @enderror" 
                                   value="{{ old('nama_tpa', $tpa->nama_tpa ?? '') }}" 
                                   style="height: 34px; font-size: 12.5px;" required>
                            @error('nama_tpa')
                                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Alamat</label>
                        <input type="text" name="alamat" class="form-control form-control-sm" 
                               value="{{ old('alamat', $tpa->alamat ?? '') }}" style="height: 34px; font-size: 12.5px;">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Telp</label>
                            <input type="text" name="telp" class="form-control form-control-sm" 
                                   value="{{ old('telp', $tpa->telp ?? '') }}" style="height: 34px; font-size: 12.5px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Fax</label>
                            <input type="text" name="fax" class="form-control form-control-sm" 
                                   value="{{ old('fax', $tpa->fax ?? '') }}" style="height: 34px; font-size: 12.5px;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-dark mb-1 d-block" style="font-size: 12.5px; font-weight: 500;">Status Penjamin</label>
                        @php
                            $statusVal = old('is_active', isset($tpa) ? ($tpa->is_active ? 1 : 0) : 1);
                        @endphp
                        <div class="d-flex gap-3 align-items-center mt-1">
                            <label class="form-check-label d-flex align-items-center gap-1.5" style="font-size: 12.5px; cursor: pointer;">
                                <input type="radio" name="is_active" value="1" {{ $statusVal == 1 ? 'checked' : '' }} style="cursor: pointer;"> 
                                <span>Aktif</span>
                            </label>
                            <label class="form-check-label d-flex align-items-center gap-1.5" style="font-size: 12.5px; cursor: pointer;">
                                <input type="radio" name="is_active" value="0" {{ $statusVal == 0 ? 'checked' : '' }} style="cursor: pointer;"> 
                                <span>Non Aktif</span>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm text-white px-3" 
                                style="background-color: #00a65a; border-color: #008d4c; border-radius: 3px; font-size: 12.5px; font-weight: 500; height: 32px;">
                            Simpan
                        </button>
                        <a href="/master-tpa" class="btn btn-sm text-white px-3 d-inline-flex align-items-center" 
                           style="background-color: #f39c12; border-color: #e08e0b; border-radius: 3px; font-size: 12.5px; font-weight: 500; height: 32px;">
                            Batal
                        </a>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm" 
                               value="{{ old('email', $tpa->email ?? '') }}" style="height: 34px; font-size: 12.5px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Join Date</label>
                        @php
                            $joinVal = old('join_date', isset($tpa->join_date) ? (\Carbon\Carbon::parse($tpa->join_date)->format('Y-m-d')) : '');
                        @endphp
                        <input type="date" name="join_date" class="form-control form-control-sm" 
                               value="{{ $joinVal }}" style="height: 34px; font-size: 12.5px;">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Nomor SPK</label>
                            <input type="text" name="nomor_spk" class="form-control form-control-sm" 
                                   value="{{ old('nomor_spk', $tpa->nomor_spk ?? '') }}" style="height: 34px; font-size: 12.5px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Expired Date</label>
                            @php
                                $expVal = old('expired_date', isset($tpa->expired_date) ? (\Carbon\Carbon::parse($tpa->expired_date)->format('Y-m-d')) : '');
                            @endphp
                            <input type="date" name="expired_date" class="form-control form-control-sm" 
                                   value="{{ $expVal }}" style="height: 34px; font-size: 12.5px;">
                        </div>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Nama PIC</label>
                        <input type="text" name="nama_pic" class="form-control form-control-sm" 
                               value="{{ old('nama_pic', $tpa->nama_pic ?? '') }}" style="height: 34px; font-size: 12.5px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Telp PIC</label>
                        <input type="text" name="telp_pic" class="form-control form-control-sm" 
                               value="{{ old('telp_pic', $tpa->telp_pic ?? '') }}" style="height: 34px; font-size: 12.5px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Email PIC</label>
                        <input type="email" name="email_pic" class="form-control form-control-sm" 
                               value="{{ old('email_pic', $tpa->email_pic ?? '') }}" style="height: 34px; font-size: 12.5px;">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
