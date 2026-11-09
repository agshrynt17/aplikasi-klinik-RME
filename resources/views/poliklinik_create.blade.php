@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', (isset($item) ? 'Ubah Poliklinik' : 'Tambah Poliklinik') . ' - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="tambah-poliklinik-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">
            {{ isset($item) ? 'Ubah Poliklinik' : 'Tambah Poliklinik' }}
        </h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/master-poliklinik" class="text-decoration-none text-muted">Poliklinik</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">
                    {{ isset($item) ? 'Ubah Poliklinik' : 'Tambah Poliklinik' }}
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
        <form action="{{ isset($item) ? '/master-poliklinik/' . $item->id : '/master-poliklinik' }}" method="POST">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            <!-- Kode Poliklinik -->
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark mb-1">
                    Kode Poliklinik <span class="text-danger">*</span>
                </label>
                <input type="text" name="kode_poli" class="form-control form-control-sm @error('kode_poli') is-invalid @enderror" 
                       value="{{ old('kode_poli', $item->kode_poli ?? ($nextCode ?? '')) }}" 
                       placeholder="Contoh: KL021" required style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px; text-transform: uppercase;">
                @error('kode_poli')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama Poliklinik -->
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark mb-1">
                    Nama Poliklinik <span class="text-danger">*</span>
                </label>
                <input type="text" name="nama_poli" class="form-control form-control-sm @error('nama_poli') is-invalid @enderror" 
                       value="{{ old('nama_poli', $item->nama_poli ?? '') }}" 
                       placeholder="Contoh: Klinik Penyakit Dalam" required style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                @error('nama_poli')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Row: Unit, Wajib PPA, Jenis Poliklinik -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Unit</label>
                    <select name="unit" class="form-select form-select-sm" style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        <option value="Pelayanan" {{ old('unit', $item->unit ?? 'Pelayanan') == 'Pelayanan' ? 'selected' : '' }}>Pelayanan</option>
                        <option value="Penunjang" {{ old('unit', $item->unit ?? '') == 'Penunjang' ? 'selected' : '' }}>Penunjang</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Wajib PPA</label>
                    <select name="wajib_ppa" class="form-select form-select-sm" style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        <option value="Ya" {{ old('wajib_ppa', $item->wajib_ppa ?? 'Ya') == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ old('wajib_ppa', $item->wajib_ppa ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Jenis Poliklinik</label>
                    <select name="jenis_poli" class="form-select form-select-sm" style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        <option value="Poli Sakit" {{ old('jenis_poli', $item->jenis_poli ?? 'Poli Sakit') == 'Poli Sakit' ? 'selected' : '' }}>Poli Sakit</option>
                        <option value="Poli Sehat" {{ old('jenis_poli', $item->jenis_poli ?? '') == 'Poli Sehat' ? 'selected' : '' }}>Poli Sehat</option>
                    </select>
                </div>
            </div>

            <!-- Layanan Khusus Box -->
            <div class="p-3 border rounded-2 mb-3 bg-light">
                <label class="form-label fw-bold text-dark mb-2" style="font-size: 13px;">Layanan Khusus</label>
                <div class="row g-2">
                    <div class="col-md-4 col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_igd" name="is_igd" value="1" 
                                   {{ old('is_igd', $item->is_igd ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label text-dark small" for="is_igd">IGD / UGD</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_rehab_medik" name="is_rehab_medik" value="1" 
                                   {{ old('is_rehab_medik', $item->is_rehab_medik ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label text-dark small" for="is_rehab_medik">Rehab Medik</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_mcu" name="is_mcu" value="1" 
                                   {{ old('is_mcu', $item->is_mcu ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label text-dark small" for="is_mcu">MCU</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_lab" name="is_lab" value="1" 
                                   {{ old('is_lab', $item->is_lab ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label text-dark small" for="is_lab">Laboratorium</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_radiologi" name="is_radiologi" value="1" 
                                   {{ old('is_radiologi', $item->is_radiologi ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label text-dark small" for="is_radiologi">Radiologi</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label for="keterangan" class="form-label small fw-bold text-dark mb-1">Keterangan</label>
                <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="2" 
                          placeholder="Keterangan tambahan (opsional)" style="border: 1px solid #d2d6de; border-radius: 2px;">{{ old('keterangan', $item->keterangan ?? '') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" 
                            style="background-color: #00a65a; border-color: #008d4c; height: 34px; min-width: 80px;">
                        Simpan
                    </button>
                    <a href="/master-poliklinik" class="btn btn-sm text-white px-3 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; height: 34px; min-width: 80px; text-decoration: none;">
                        Batal
                    </a>
                </div>
                @if(isset($item))
                <div>
                    <button type="button" class="btn btn-sm text-white px-3 fw-semibold" id="btnHapusEditPage"
                            style="background-color: #d9534f; border-color: #d43f3a; height: 34px;">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </div>
                @endif
            </div>
        </form>

        @if(isset($item))
        <form id="formDeletePoli" action="/master-poliklinik/{{ $item->id }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        <script>
        document.getElementById('btnHapusEditPage')?.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus Poliklinik "{{ $item->nama_poli }}"?')) {
                document.getElementById('formDeletePoli').submit();
            }
        });
        </script>
        @endif
    </div>
</div>
@endsection
