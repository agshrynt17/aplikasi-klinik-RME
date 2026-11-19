@extends('layouts.admin')

@section('title', 'Edit Hak Akses (Role)')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 text-dark fw-bold" style="font-size: 18px;">
                <i class="fas fa-pencil-alt text-info me-2"></i> Edit Hak Akses: {{ $item->name }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 12px;">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/setting-role">Hak Akses</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/setting-role" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size: 13px;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 4px;">
        <div class="card-header text-white fw-bold py-2 px-3" style="background-color: #00c0ef; font-size: 14px;">
            <i class="fas fa-edit me-1"></i> Form Edit Hak Akses
        </div>
        <div class="card-body p-4">
            <form action="{{ route('role.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="form-label text-dark fw-bold mb-1">
                        Nama Hak Akses (Role) <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $item->name) }}"
                           placeholder="Contoh: Administrator, Dokter, Perawat, Kasir, Apoteker" maxlength="100" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <label class="form-label text-dark fw-bold mb-0">
                        <i class="fas fa-key me-1 text-warning"></i> Pengaturan Izin Akses Modul Aplikasi
                    </label>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-success btn-sm py-0 px-2" id="btnSelectAllPerms">
                            <i class="fas fa-check-square me-1"></i> Pilih Semua
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" id="btnUnselectAllPerms">
                            <i class="fas fa-square me-1"></i> Batalkan Semua
                        </button>
                    </div>
                </div>

                @php
                    $currentPerms = is_array($item->permissions) ? $item->permissions : (json_decode($item->permissions, true) ?? []);
                @endphp

                <div class="row g-3">
                    @foreach($moduleGroups as $groupName => $modules)
                    <div class="col-md-6">
                        <div class="card h-100 border shadow-none" style="background-color: #fafafa; border-radius: 4px;">
                            <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                                <strong class="text-dark" style="font-size: 12px;">
                                    <i class="fas fa-folder-open text-primary me-1"></i> {{ $groupName }}
                                </strong>
                                <button type="button" class="btn btn-link p-0 text-muted btnToggleGroup" data-target="group-{{ Str::slug($groupName) }}" style="font-size: 11px; text-decoration: none;">
                                    Pilih Grup
                                </button>
                            </div>
                            <div class="card-body p-3 group-{{ Str::slug($groupName) }}">
                                @foreach($modules as $key => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input chk-perm" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $key }}"
                                           {{ in_array($key, $currentPerms) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="perm_{{ $key }}" style="font-size: 12px; cursor: pointer;">
                                        {{ $label }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <a href="/setting-role" class="btn btn-sm btn-secondary px-3 me-2">Batal</a>
                    <button type="submit" class="btn btn-sm text-white px-4 fw-bold" style="background-color: #00c0ef;">
                        <i class="fas fa-save me-1"></i> Perbarui Hak Akses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#btnSelectAllPerms').on('click', function () {
        $('.chk-perm').prop('checked', true);
    });

    $('#btnUnselectAllPerms').on('click', function () {
        $('.chk-perm').prop('checked', false);
    });

    $('.btnToggleGroup').on('click', function () {
        let targetClass = $(this).data('target');
        let $boxes = $('.' + targetClass + ' .chk-perm');
        let allChecked = $boxes.length === $boxes.filter(':checked').length;
        $boxes.prop('checked', !allChecked);
    });
});
</script>
@endsection
