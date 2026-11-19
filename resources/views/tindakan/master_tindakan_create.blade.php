@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@php
    $isEdit = isset($tindakan);
    $pageTitle = $isEdit ? 'Edit Tindakan' : 'Tambah Tindakan';
@endphp

@section('title', $pageTitle . ' - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="tambah-tindakan-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; letter-spacing: 0.3px;">{{ $pageTitle }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/master-tindakan" class="text-decoration-none text-muted">Tindakan</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">{{ $pageTitle }}</a></li>
            </ol>
        </nav>
    </div>

    <!-- Orange Accent Top Line -->
    <div class="mb-4" style="border-top: 2px solid #f39c12;"></div>

    <!-- Main Card -->
    <div class="card border rounded-1 shadow-none bg-white p-4">
        <form action="{{ $isEdit ? '/master-tindakan/' . $tindakan->id : '/master-tindakan' }}" method="POST" id="formTindakan">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="row gx-5 gy-3">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Poli Klinik</label>
                        <select name="poliklinik_id" class="form-select form-select-sm" style="height: 34px; font-size: 12.5px;">
                            <option value="">--Pilih--</option>
                            @php
                                $selectedPoli = old('poliklinik_id', $tindakan->poliklinik_id ?? '');
                            @endphp
                            @foreach($polikliniks as $poli)
                                <option value="{{ $poli->id }}" {{ $selectedPoli == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Kode Tindakan</label>
                            <input type="text" name="kode_tindakan" class="form-control form-control-sm" 
                                   value="{{ old('kode_tindakan', $isEdit ? $tindakan->kode_tindakan : $nextKode) }}" 
                                   style="background-color: #eee; height: 34px; font-size: 12.5px;" readonly>
                        </div>
                        <div class="col-8">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Nama Tindakan</label>
                            <input type="text" name="nama_tindakan" class="form-control form-control-sm @error('nama_tindakan') is-invalid @enderror" 
                                   value="{{ old('nama_tindakan', $tindakan->nama_tindakan ?? '') }}" 
                                   style="height: 34px; font-size: 12.5px;" required>
                            @error('nama_tindakan')
                                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Tindakan Ini adalah konsultasi ?</label>
                        @php
                            $isKonsul = old('is_konsultasi', isset($tindakan) ? ($tindakan->is_konsultasi ? 1 : 0) : 0);
                        @endphp
                        <select name="is_konsultasi" class="form-select form-select-sm" style="height: 34px; font-size: 12.5px;">
                            <option value="0" {{ $isKonsul == 0 ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ $isKonsul == 1 ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Tindakan Wajib diisi pada informed consent ?</label>
                        @php
                            $isInformed = old('is_informed_consent', isset($tindakan) ? ($tindakan->is_informed_consent ? 1 : 0) : 0);
                        @endphp
                        <select name="is_informed_consent" class="form-select form-select-sm" style="height: 34px; font-size: 12.5px;">
                            <option value="0" {{ $isInformed == 0 ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ $isInformed == 1 ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-2" style="color: #337ab7; font-weight: 600; font-style: italic; font-size: 13px;">
                        Setting Unit Cost Tindakan
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">BHP /Bahan Habis Pakai (Rp)</label>
                            <input type="number" id="bhp" name="bhp" class="form-control form-control-sm cost-calc" 
                                   value="{{ old('bhp', isset($tindakan) ? (int)$tindakan->bhp : 0) }}" style="height: 34px; font-size: 12.5px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Jasa Petugas (Rp)</label>
                            <input type="number" id="jasa_petugas" name="jasa_petugas" class="form-control form-control-sm cost-calc" 
                                   value="{{ old('jasa_petugas', isset($tindakan) ? (int)$tindakan->jasa_petugas : 0) }}" style="height: 34px; font-size: 12.5px;">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Penyusutan Gedung (Rp)</label>
                            <input type="number" id="penyusutan_gedung" name="penyusutan_gedung" class="form-control form-control-sm cost-calc" 
                                   value="{{ old('penyusutan_gedung', isset($tindakan) ? (int)$tindakan->penyusutan_gedung : 0) }}" style="height: 34px; font-size: 12.5px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Penyusutan Alat (Rp)</label>
                            <input type="number" id="penyusutan_alat" name="penyusutan_alat" class="form-control form-control-sm cost-calc" 
                                   value="{{ old('penyusutan_alat', isset($tindakan) ? (int)$tindakan->penyusutan_alat : 0) }}" style="height: 34px; font-size: 12.5px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark mb-1" style="font-size: 12.5px; font-weight: 500;">Unit Cost (Rp)</label>
                        <input type="text" id="unit_cost" name="unit_cost" class="form-control form-control-sm" 
                               value="{{ old('unit_cost', isset($tindakan) ? (int)$tindakan->unit_cost : 0) }}" 
                               style="background-color: #eee; height: 34px; font-size: 12.5px;" readonly>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <label class="form-label text-dark mb-1 d-block" style="font-size: 12.5px; font-weight: 500;">Status Tindakan</label>
                            @php
                                $statusVal = old('is_active', isset($tindakan) ? ($tindakan->is_active ? 1 : 0) : 1);
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

                        <div class="col-6">
                            <label class="form-label text-dark mb-1 d-block" style="font-size: 12.5px; font-weight: 500;">Tampilkan Tindakan Ini Pada Rawat Inap</label>
                            @php
                                $ranapVal = old('is_ranap', isset($tindakan) ? ($tindakan->is_ranap ? 1 : 0) : 1);
                            @endphp
                            <div class="d-flex gap-3 align-items-center mt-1">
                                <label class="form-check-label d-flex align-items-center gap-1.5" style="font-size: 12.5px; cursor: pointer;">
                                    <input type="radio" name="is_ranap" value="1" {{ $ranapVal == 1 ? 'checked' : '' }} style="cursor: pointer;"> 
                                    <span>Ya</span>
                                </label>
                                <label class="form-check-label d-flex align-items-center gap-1.5" style="font-size: 12.5px; cursor: pointer;">
                                    <input type="radio" name="is_ranap" value="0" {{ $ranapVal == 0 ? 'checked' : '' }} style="cursor: pointer;"> 
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm text-white px-3" 
                                style="background-color: #00a65a; border-color: #008d4c; border-radius: 3px; font-size: 12.5px; font-weight: 500; height: 32px;">
                            Simpan
                        </button>
                        <a href="/master-tindakan" class="btn btn-sm text-white px-3 d-inline-flex align-items-center" 
                           style="background-color: #f39c12; border-color: #e08e0b; border-radius: 3px; font-size: 12.5px; font-weight: 500; height: 32px;">
                            Tutup
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    function calculateUnitCost() {
        let bhp = parseFloat($('#bhp').val()) || 0;
        let jasa = parseFloat($('#jasa_petugas').val()) || 0;
        let gedung = parseFloat($('#penyusutan_gedung').val()) || 0;
        let alat = parseFloat($('#penyusutan_alat').val()) || 0;
        let total = bhp + jasa + gedung + alat;
        $('#unit_cost').val(total);
    }

    $('.cost-calc').on('input change', function() {
        calculateUnitCost();
    });
});
</script>
@endsection
