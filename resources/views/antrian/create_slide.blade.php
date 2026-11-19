@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@php
    $isEdit = isset($slide);
    $pageTitle = $isEdit ? 'Edit Slide Antrian' : 'Create Slide Antrian';
@endphp

@section('title', $pageTitle . ' - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<style>
    .page-header-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .page-title-text {
        font-size: 20px;
        font-weight: 700;
        color: #333333;
        margin: 0;
    }
    .breadcrumb-custom {
        font-size: 11.5px;
        margin: 0;
        color: #777;
    }
    .breadcrumb-custom a {
        color: #337ab7;
        text-decoration: none;
    }
    .form-label-custom {
        font-size: 12.5px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 5px;
    }
    .form-control-custom {
        font-size: 13px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        padding: 6px 10px;
        background-color: #fff;
    }
    .form-control-custom:focus {
        border-color: #3c8dbc;
        box-shadow: none;
    }
    .btn-save-slide {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 18px;
        border-radius: 3px;
    }
    .btn-save-slide:hover {
        background-color: #008d4c;
        color: #ffffff;
    }
    .btn-tutup-slide {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 18px;
        border-radius: 3px;
        text-decoration: none;
    }
    .btn-tutup-slide:hover {
        background-color: #e08e0b;
        color: #ffffff;
    }
    .text-wajib {
        color: #dd4b39;
        font-size: 11px;
        font-weight: normal;
        margin-left: 4px;
    }
    .hint-subtext {
        font-size: 11px;
        color: #777;
        margin-top: 4px;
    }
    .radio-custom-group {
        display: flex;
        align-items: center;
        gap: 18px;
        font-size: 13px;
        font-weight: 600;
        color: #333;
    }
    .radio-custom-group input[type="radio"] {
        margin-right: 5px;
    }
</style>

<div class="container-fluid p-3">
    <!-- Header Box & Breadcrumbs -->
    <div class="page-header-box">
        <h1 class="page-title-text">{{ $pageTitle }}</h1>
        <div class="breadcrumb-custom">
            <a href="/dashboard">Beranda</a> &gt; <a href="/slide-antrian">Slide Antrians</a> &gt; <span>{{ $pageTitle }}</span>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 3px;">
        <div class="card-body p-4">
            <form action="{{ $isEdit ? '/slide-antrian/' . $slide->id : '/slide-antrian' }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <!-- Row 1: Urutan Tampil & Tipe Slide -->
                <div class="row g-4 mb-3">
                    <div class="col-md-3">
                        <label class="form-label-custom">Urutan Tampil</label>
                        <input type="number" 
                               name="urutan" 
                               class="form-control form-control-custom" 
                               value="{{ old('urutan', $isEdit ? $slide->urutan : ($nextUrutan ?? 15)) }}" 
                               required>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label-custom">Tipe Slide</label>
                        <div class="radio-custom-group pt-1">
                            <label class="d-inline-flex align-items-center">
                                <input type="radio" 
                                       name="tipe" 
                                       value="image" 
                                       id="tipe_gambar"
                                       {{ old('tipe', $isEdit ? $slide->tipe : 'image') == 'image' ? 'checked' : '' }}>
                                Gambar
                            </label>
                            <label class="d-inline-flex align-items-center">
                                <input type="radio" 
                                       name="tipe" 
                                       value="video" 
                                       id="tipe_video"
                                       {{ old('tipe', $isEdit ? $slide->tipe : 'image') == 'video' ? 'checked' : '' }}>
                                Video
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Upload Slide -->
                <div class="mb-3" id="group_upload_image">
                    <label class="form-label-custom">Upload Slide</label>
                    <input type="file" 
                           name="upload_slide" 
                           class="form-control form-control-custom" 
                           accept="image/*">
                    @if($isEdit && $slide->url_media && !str_starts_with($slide->url_media, 'doctor_') && $slide->url_media != 'logo')
                        <div class="mt-2">
                            <span class="small text-muted">File saat ini:</span>
                            <a href="{{ $slide->url_media }}" target="_blank" class="small text-primary text-decoration-none ms-1">
                                <i class="fas fa-image me-1"></i> Lihat Media
                            </a>
                        </div>
                    @endif
                </div>

                <!-- URL Video (shown if Video is chosen) -->
                <div class="mb-3 d-none" id="group_video_url">
                    <label class="form-label-custom">URL / ID YouTube Video</label>
                    <input type="text" 
                           name="url_media" 
                           class="form-control form-control-custom" 
                           placeholder="Cth: https://youtube.com/watch?v=dQw4w9WgXcQ atau dQw4w9WgXcQ"
                           value="{{ old('url_media', $isEdit && $slide->tipe == 'video' ? $slide->url_media : '') }}">
                </div>

                <!-- Row 3: Durasi Slide (Detik) -->
                <div class="mb-3">
                    <label class="form-label-custom">
                        Durasi Slide (Detik)<span class="text-wajib">*Wajib Diisi</span>
                    </label>
                    <input type="number" 
                           name="durasi" 
                           class="form-control form-control-custom" 
                           value="{{ old('durasi', $isEdit ? ($slide->durasi ?? 5) : 5) }}" 
                           required>
                    <div class="hint-subtext">Untuk Video dari youtube isi dengan "0"</div>
                </div>

                <!-- Row 4: Tampilkan di -->
                <div class="mb-3">
                    <label class="form-label-custom">Tampilkan di</label>
                    <div class="radio-custom-group flex-wrap">
                        <label class="d-inline-flex align-items-center">
                            <input type="radio" 
                                   name="tampilkan_di" 
                                   value="Semua Layanan" 
                                   id="tampilkan_semua"
                                   {{ old('tampilkan_di', $isEdit ? $slide->tampilkan_di : 'Semua Layanan') == 'Semua Layanan' ? 'checked' : '' }}>
                            Tampilkan slide di semua Antrian
                        </label>
                        <label class="d-inline-flex align-items-center">
                            <input type="radio" 
                                   name="tampilkan_di" 
                                   value="Beberapa Antrian" 
                                   id="tampilkan_beberapa"
                                   {{ old('tampilkan_di', $isEdit ? $slide->tampilkan_di : 'Semua Layanan') == 'Beberapa Antrian' ? 'checked' : '' }}>
                            Tampilkan slide di beberapa Antrian
                        </label>
                    </div>

                    <!-- Pilihan Layanan Antrian (shown if beberapa antrian) -->
                    <div class="card p-3 mt-2 bg-light border {{ old('tampilkan_di', $isEdit ? $slide->tampilkan_di : 'Semua Layanan') == 'Beberapa Antrian' ? '' : 'd-none' }}" id="group_layanan_antrian">
                        <span class="small fw-bold mb-2 text-dark">Pilih Layanan / Loket yang Dituju:</span>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($lokets as $lok)
                            @php
                                $selectedIds = $isEdit && is_array($slide->tampilkan_layanan_ids) ? $slide->tampilkan_layanan_ids : [];
                                $isChecked = in_array($lok->id, $selectedIds);
                            @endphp
                            <label class="d-inline-flex align-items-center small">
                                <input type="checkbox" name="layanan_ids[]" value="{{ $lok->id }}" {{ $isChecked ? 'checked' : '' }} class="me-1">
                                {{ $lok->nama_antrian }} (Kode: {{ $lok->kode_antrian }})
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Row 5: Text Pada Slide -->
                <div class="mb-3">
                    <label class="form-label-custom">Text Pada Slide</label>
                    <textarea name="text_slide" 
                              class="form-control form-control-custom" 
                              rows="3" 
                              placeholder="Masukkan teks judul dokter atau informasi yang tampil pada slide...">{{ old('text_slide', $isEdit ? $slide->text_slide : '') }}</textarea>
                </div>

                <!-- Row 6: Status -->
                <div class="mb-4">
                    <label class="form-label-custom">Status</label>
                    <div class="radio-custom-group">
                        <label class="d-inline-flex align-items-center">
                            <input type="radio" 
                                   name="status" 
                                   value="1" 
                                   {{ old('status', $isEdit ? ($slide->is_active ? '1' : '0') : '1') == '1' ? 'checked' : '' }}>
                            Aktif
                        </label>
                        <label class="d-inline-flex align-items-center">
                            <input type="radio" 
                                   name="status" 
                                   value="0" 
                                   {{ old('status', $isEdit ? ($slide->is_active ? '1' : '0') : '1') == '0' ? 'checked' : '' }}>
                            Non-Aktif
                        </label>
                    </div>
                </div>

                <!-- Row 7: Action Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-save-slide">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                    <a href="/slide-antrian" class="btn btn-tutup-slide">
                        Tutup
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioGambar = document.getElementById('tipe_gambar');
    const radioVideo = document.getElementById('tipe_video');
    const groupUpload = document.getElementById('group_upload_image');
    const groupVideo = document.getElementById('group_video_url');

    function toggleMediaType() {
        if (radioVideo && radioVideo.checked) {
            groupVideo.classList.remove('d-none');
        } else {
            groupVideo.classList.add('d-none');
        }
    }

    if (radioGambar) radioGambar.addEventListener('change', toggleMediaType);
    if (radioVideo) radioVideo.addEventListener('change', toggleMediaType);
    toggleMediaType();

    // Toggle Tampilkan di
    const radioSemua = document.getElementById('tampilkan_semua');
    const radioBeberapa = document.getElementById('tampilkan_beberapa');
    const groupLayanan = document.getElementById('group_layanan_antrian');

    function toggleLayanan() {
        if (radioBeberapa && radioBeberapa.checked) {
            groupLayanan.classList.remove('d-none');
        } else {
            groupLayanan.classList.add('d-none');
        }
    }

    if (radioSemua) radioSemua.addEventListener('change', toggleLayanan);
    if (radioBeberapa) radioBeberapa.addEventListener('change', toggleLayanan);
    toggleLayanan();
});
</script>
@endsection
