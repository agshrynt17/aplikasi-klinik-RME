@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Setting Slide Antrian - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

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
    .btn-tambah-slide {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 3px;
        transition: all 0.2s;
    }
    .btn-tambah-slide:hover {
        background-color: #008d4c;
        color: #ffffff;
    }
    .btn-preview-tv {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 3px;
        transition: all 0.2s;
    }
    .btn-preview-tv:hover {
        background-color: #e08e0b;
        color: #ffffff;
    }
    .table-slide {
        font-size: 12.5px;
        color: #333;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-slide th {
        background-color: #f4f6f9;
        color: #337ab7;
        font-weight: 700;
        border-top: 1px solid #d2d6de;
        border-bottom: 2px solid #d2d6de;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-slide td {
        padding: 8px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    .table-slide tbody tr:hover {
        background-color: #f9fbfd;
    }
    .filter-row input {
        width: 100%;
        font-size: 11.5px;
        padding: 4px 6px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        background: #fff;
    }
    .badge-status-aktif {
        background-color: #337ab7;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 3px;
    }
    .badge-status-nonaktif {
        background-color: #777;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 3px;
    }
    .slide-thumb {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 3px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fafafa;
        cursor: pointer;
    }
    .action-icons {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        align-items: center;
    }
    .action-btn {
        font-size: 14px;
        cursor: pointer;
        background: none;
        border: none;
        padding: 2px;
        line-height: 1;
        transition: transform 0.15s ease;
    }
    .action-btn:hover {
        transform: scale(1.15);
    }
    .btn-action-view {
        color: #00c0ef;
    }
    .btn-action-edit {
        color: #337ab7;
    }
    .btn-action-delete {
        color: #00c0ef;
    }
    .btn-action-delete:hover {
        color: #dd4b39;
    }
</style>

<div class="container-fluid p-3">
    <!-- Top Header and Breadcrumbs -->
    <div class="page-header-box">
        <h1 class="page-title-text">Setting Slide Antrian</h1>
        <div class="breadcrumb-custom">
            <a href="/dashboard">Beranda</a> &gt; <span>Setting Slide Antrian</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3 small" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Container -->
    <div class="card border-0 shadow-sm" style="border-radius: 3px;">
        <div class="card-body p-3">
            <!-- Buttons Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="/slide-antrian/create" class="btn btn-tambah-slide text-decoration-none">
                    <i class="fas fa-plus me-1"></i> Tambah Slide
                </a>
                <a href="/tv-antrian" target="_blank" class="btn btn-preview-tv text-decoration-none">
                    <i class="fas fa-eye me-1"></i> Preview
                </a>
            </div>

            <!-- Items Counter -->
            <div class="small text-muted mb-2">
                Menampilkan 1-{{ $data->count() }} dari {{ $total }} item.
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-slide w-100 mb-0">
                    <thead>
                        <tr>
                            <th width="35" class="text-center">#</th>
                            <th width="80" class="text-center">Status</th>
                            <th width="150">Tampilkan Pada</th>
                            <th width="110" class="text-center">Urutan Tampil</th>
                            <th width="100">Tipe Slide</th>
                            <th width="90" class="text-center">Slide</th>
                            <th width="150" class="text-center">Durasi Slide (Detik)</th>
                            <th>Text Pada Slide</th>
                            <th width="160">Diubah Pada</th>
                            <th width="80" class="text-end">Aksi</th>
                        </tr>
                        <!-- Filter Inputs (Matching Screenshot 1) -->
                        <form method="GET" action="/slide-antrian" id="formFilterSlide">
                            <tr class="filter-row">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <input type="text" name="urutan" value="{{ request('urutan') }}" onchange="document.getElementById('formFilterSlide').submit();">
                                </td>
                                <td>
                                    <input type="text" name="tipe" value="{{ request('tipe') }}" onchange="document.getElementById('formFilterSlide').submit();">
                                </td>
                                <td>
                                    <input type="text" name="slide_search" disabled style="background: #f8f9fa;">
                                </td>
                                <td></td>
                                <td>
                                    <input type="text" name="text_slide" value="{{ request('text_slide') }}" onchange="document.getElementById('formFilterSlide').submit();">
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </form>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="text-center">
                                @if($item->is_active)
                                    <span class="badge-status-aktif">Aktif</span>
                                @else
                                    <span class="badge-status-nonaktif">Non-Aktif</span>
                                @endif
                            </td>
                            <td>{{ $item->tampilkan_di ?? 'Semua Layanan' }}</td>
                            <td class="text-center fw-bold">{{ $item->urutan }}</td>
                            <td>{{ $item->tipe }}</td>
                            <td class="text-center">
                                @if($item->tipe == 'image')
                                    @if($item->url_media == 'logo')
                                        <!-- Logo Thumbnail -->
                                        <div class="slide-thumb btn-preview-modal" 
                                             data-title="Logo Klinik Telagasari" 
                                             data-type="logo" 
                                             data-text="{{ $item->text_slide }}">
                                            <div style="position: relative; width: 34px; height: 34px;">
                                                <i class="fas fa-leaf" style="color: #61b15a; font-size: 20px; position: absolute; bottom: 0; left: 0; transform: rotate(-45deg);"></i>
                                                <i class="fas fa-leaf" style="color: #5c3c92; font-size: 20px; position: absolute; bottom: 3px; left: -5px; transform: rotate(-90deg);"></i>
                                                <i class="fas fa-leaf" style="color: #3b5998; font-size: 28px; position: absolute; top: -3px; right: 0; transform: rotate(15deg);"></i>
                                                <i class="fas fa-plus" style="color: #e60000; font-size: 14px; position: absolute; top: 6px; right: 6px; font-weight: 900;"></i>
                                            </div>
                                        </div>
                                    @elseif(str_starts_with($item->url_media, 'doctor_') || str_starts_with($item->url_media, 'farmasi_') || str_starts_with($item->url_media, 'igd_'))
                                        <!-- Doctor Poster Card Thumbnail -->
                                        <div class="slide-thumb btn-preview-modal" 
                                             data-title="{{ $item->text_slide ?? 'Slide Dokter' }}" 
                                             data-type="doctor" 
                                             data-text="{{ $item->text_slide }}">
                                            <div style="text-align: center; line-height: 1;">
                                                <i class="fas fa-user-md" style="font-size: 22px; color: #337ab7;"></i>
                                                <div style="font-size: 7.5px; font-weight: bold; color: #00a65a; margin-top: 2px;">DOKTER</div>
                                            </div>
                                        </div>
                                    @elseif($item->url_media)
                                        <!-- Uploaded Image Thumbnail -->
                                        <img src="{{ $item->url_media }}" 
                                             alt="Slide" 
                                             class="slide-thumb btn-preview-modal"
                                             data-title="{{ $item->text_slide ?? 'Slide' }}"
                                             data-type="image"
                                             data-url="{{ $item->url_media }}"
                                             data-text="{{ $item->text_slide }}">
                                    @else
                                        <div class="slide-thumb text-muted"><i class="fas fa-image"></i></div>
                                    @endif
                                @else
                                    <!-- Video Thumbnail -->
                                    <div class="slide-thumb text-danger btn-preview-modal"
                                         data-title="{{ $item->text_slide ?? 'Video' }}"
                                         data-type="video"
                                         data-url="{{ $item->url_media }}"
                                         data-text="{{ $item->text_slide }}">
                                        <i class="fab fa-youtube fa-2x"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center text-muted">
                                {{ $item->durasi ? $item->durasi : '-' }}
                            </td>
                            <td>{{ $item->text_slide ?? '-' }}</td>
                            <td class="small text-muted">
                                {{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '-' }}
                            </td>
                            <td class="text-end">
                                <div class="action-icons">
                                    <!-- Eye (Preview) -->
                                    <button type="button" 
                                            class="action-btn btn-action-view btn-preview-modal" 
                                            title="Lihat Pratinjau"
                                            data-title="{{ $item->text_slide ?? 'Slide ' . $item->urutan }}"
                                            data-type="{{ $item->url_media == 'logo' ? 'logo' : (str_starts_with($item->url_media, 'doctor_') ? 'doctor' : $item->tipe) }}"
                                            data-url="{{ $item->url_media }}"
                                            data-text="{{ $item->text_slide }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- Pencil (Edit) -->
                                    <a href="/slide-antrian/{{ $item->id }}/edit" class="action-btn btn-action-edit" title="Ubah Slide">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Trash (Delete) -->
                                    <form action="/slide-antrian/{{ $item->id }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide urutan {{ $item->urutan }} ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-action-delete" title="Hapus Slide">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Tidak ada data slide ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pratinjau Slide TV -->
<div class="modal fade" id="modalPreviewSlide" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 px-3" style="background-color: #337ab7; color: white;">
                <h6 class="modal-title fw-bold" id="previewModalTitle">Pratinjau Slide Antrian</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light" id="previewModalBody" style="min-height: 320px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <!-- Content dynamically populated -->
            </div>
            <div class="modal-footer py-1 px-3 bg-white">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewModal = new bootstrap.Modal(document.getElementById('modalPreviewSlide'));
    const modalTitle = document.getElementById('previewModalTitle');
    const modalBody = document.getElementById('previewModalBody');

    document.querySelectorAll('.btn-preview-modal').forEach(el => {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            const title = this.getAttribute('data-title') || 'Pratinjau Slide';
            const type = this.getAttribute('data-type') || 'image';
            const url = this.getAttribute('data-url') || '';
            const text = this.getAttribute('data-text') || '';

            modalTitle.textContent = title;
            modalBody.innerHTML = '';

            if (type === 'logo') {
                modalBody.innerHTML = `
                    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 450px; border-radius: 8px;">
                        <div class="mb-3 text-center">
                            <div style="position: relative; width: 80px; height: 80px; display: inline-block;">
                                <i class="fas fa-leaf" style="color: #61b15a; font-size: 50px; position: absolute; bottom: 0; left: 5px; transform: rotate(-45deg);"></i>
                                <i class="fas fa-leaf" style="color: #5c3c92; font-size: 50px; position: absolute; bottom: 10px; left: -10px; transform: rotate(-90deg);"></i>
                                <i class="fas fa-leaf" style="color: #3b5998; font-size: 70px; position: absolute; top: -10px; right: 0; transform: rotate(15deg);"></i>
                                <i class="fas fa-plus" style="color: #e60000; font-size: 34px; position: absolute; top: 15px; right: 15px; font-weight: 900;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">KLINIK TELAGASARI 24 JAM</h4>
                        <p class="text-muted small mb-0">Melayani dengan Sepenuh Hati, Terpadu & Terintegrasi SatuSehat</p>
                    </div>
                `;
            } else if (type === 'doctor') {
                modalBody.innerHTML = `
                    <div class="card p-4 shadow-sm border-0 bg-white text-center" style="max-width: 480px; border-radius: 8px; border-top: 5px solid #00a65a;">
                        <div class="mb-3">
                            <span class="badge bg-success px-3 py-1 mb-2" style="font-size: 13px;">JADWAL PRAKTEK DOKTER</span>
                            <div style="width: 100px; height: 100px; border-radius: 50%; background: #e8f5e9; display: inline-flex; align-items: center; justify-content: center; margin: 10px auto;">
                                <i class="fas fa-user-md" style="font-size: 55px; color: #2e7d32;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">${text || title}</h4>
                        <p class="text-muted small mb-3">Dokter Penanggung Jawab Pelayanan Medik</p>
                        <div class="alert alert-light border small py-2 mb-0">
                            <strong>Klinik Telagasari 24 Jam</strong> - Siap Melayani Pasien Umum & BPJS Kesehatan
                        </div>
                    </div>
                `;
            } else if (type === 'video') {
                let videoSrc = url;
                if (!url.startsWith('http')) {
                    videoSrc = `https://www.youtube.com/embed/${url}?autoplay=1`;
                } else if (url.includes('youtube.com/watch?v=')) {
                    videoSrc = url.replace('watch?v=', 'embed/');
                }
                modalBody.innerHTML = `
                    <div class="w-100" style="max-width: 650px;">
                        <div class="ratio ratio-16x9 shadow rounded overflow-hidden">
                            <iframe src="${videoSrc}" title="Video Slide" allowfullscreen></iframe>
                        </div>
                        ${text ? `<div class="mt-3 fw-bold text-dark">${text}</div>` : ''}
                    </div>
                `;
            } else {
                modalBody.innerHTML = `
                    <div class="text-center" style="max-width: 600px;">
                        <img src="${url}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 400px;" alt="Slide Preview">
                        ${text ? `<h5 class="fw-bold text-dark">${text}</h5>` : ''}
                    </div>
                `;
            }

            previewModal.show();
        });
    });
});
</script>
@endsection
