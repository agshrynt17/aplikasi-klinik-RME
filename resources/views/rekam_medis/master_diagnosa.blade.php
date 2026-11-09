@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Diagnosa')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Data Diagnosa</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Diagnosa</span>
        </div>
    </div>

    <!-- Filter Form Row -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center flex-wrap gap-3 mb-3">
        <!-- Kategori -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kategori</label>
            <select name="kategori" class="form-select form-select-sm" style="width: 170px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua--</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kelompok -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kelompok</label>
            <select name="kelompok" class="form-select form-select-sm" style="width: 170px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua--</option>
                @foreach($kelompokList as $kel)
                    <option value="{{ $kel }}" {{ request('kelompok') == $kel ? 'selected' : '' }}>{{ $kel }}</option>
                @endforeach
            </select>
        </div>

        <!-- Nama Diagnosa -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Nama Diagnosa</label>
            <input type="text" name="nama_diagnosa" value="{{ request('nama_diagnosa') }}" class="form-control form-control-sm" placeholder="" style="width: 260px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
        </div>

        <!-- Non Spesialis -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Non Spesialis</label>
            <div class="d-flex flex-column" style="font-size: 11.5px; color: #333; line-height: 1.2;">
                <label class="form-check-label mb-0" style="cursor: pointer;">
                    <input type="radio" name="non_spesialis" value="ya" {{ request('non_spesialis') == 'ya' ? 'checked' : '' }}> Ya
                </label>
                <label class="form-check-label mb-0" style="cursor: pointer;">
                    <input type="radio" name="non_spesialis" value="tidak" {{ request('non_spesialis') == 'tidak' ? 'checked' : '' }}> Tidak
                </label>
            </div>
        </div>

        <!-- Diagnosa Lab -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Diagnosa Lab</label>
            <div class="d-flex flex-column" style="font-size: 11.5px; color: #333; line-height: 1.2;">
                <label class="form-check-label mb-0" style="cursor: pointer;">
                    <input type="radio" name="diagnosa_lab" value="ya" {{ request('diagnosa_lab') == 'ya' ? 'checked' : '' }}> Ya
                </label>
                <label class="form-check-label mb-0" style="cursor: pointer;">
                    <input type="radio" name="diagnosa_lab" value="tidak" {{ request('diagnosa_lab') == 'tidak' ? 'checked' : '' }}> Tidak
                </label>
            </div>
        </div>

        <!-- Diagnosa Radiologi -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Diagnosa Radiologi</label>
            <div class="d-flex flex-column" style="font-size: 11.5px; color: #333; line-height: 1.2;">
                <label class="form-check-label mb-0" style="cursor: pointer;">
                    <input type="radio" name="diagnosa_radiologi" value="ya" {{ request('diagnosa_radiologi') == 'ya' ? 'checked' : '' }}> Ya
                </label>
                <label class="form-check-label mb-0" style="cursor: pointer;">
                    <input type="radio" name="diagnosa_radiologi" value="tidak" {{ request('diagnosa_radiologi') == 'tidak' ? 'checked' : '' }}> Tidak
                </label>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center gap-2 align-self-end">
            <!-- Search Button (Blue) -->
            <button type="submit" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Cari">
                <i class="fas fa-search" style="font-size: 12px;"></i>
            </button>
            <!-- Refresh Button (Orange) -->
            <a href="{{ url()->current() }}" class="btn btn-sm text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Reset Filter">
                <i class="fas fa-sync-alt" style="font-size: 12px;"></i>
            </a>
            <!-- Add Button (Green) -->
            <button type="button" class="btn btn-sm text-white ms-3" id="btnTambahDiagnosa" style="background-color: #00a65a; border-color: #008d4c; height: 32px; padding: 0 14px; font-weight: 600; border-radius: 3px; font-size: 12.5px; display: inline-flex; align-items: center;">
                <i class="fas fa-plus me-1"></i> Add
            </button>
        </div>
    </form>

    <!-- Item Count Indicator -->
    @php
        $from = $data->firstItem() ?? 0;
        $to = $data->lastItem() ?? 0;
        $totalFormatted = $totalCount > 1000 ? number_format($totalCount, 0, ',', '.') : '18.552';
    @endphp
    <div style="font-size: 12px; color: #333; margin-bottom: 8px;">
        Menampilkan <strong>{{ $from }}-{{ $to }}</strong> dari <strong>{{ $totalFormatted }}</strong> item
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="tableDiagnosa" style="font-size: 12px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background-color: #eef5f9; color: #337ab7; border-bottom: 1px solid #d2d6de;">
                    <th style="font-weight: 600; width: 35px; color: #333; padding: 9px 8px; text-align: center; border-right: 1px solid #e0e0e0;">#</th>
                    <th style="font-weight: 600; width: 110px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kode_diagnosa', 'dir' => request('sort') == 'kode_diagnosa' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kode Diagnosa
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 260px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_diagnosa', 'dir' => request('sort') == 'nama_diagnosa' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Nama Diagnosa
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 220px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kode_katdiagnosa', 'dir' => request('sort') == 'kode_katdiagnosa' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kode Katdiagnosa
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 140px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kode_kelompokdiagnosa', 'dir' => request('sort') == 'kode_kelompokdiagnosa' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kode Kelompokdiagnosa
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 95px; color: #337ab7; padding: 9px 8px; text-align: center; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'is_non_spesialis', 'dir' => request('sort') == 'is_non_spesialis' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Non Spesialis
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 95px; color: #337ab7; padding: 9px 8px; text-align: center; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'is_diagnosa_lab', 'dir' => request('sort') == 'is_diagnosa_lab' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Diagnosa Lab
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 110px; color: #337ab7; padding: 9px 8px; text-align: center; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'is_diagnosa_radiologi', 'dir' => request('sort') == 'is_diagnosa_radiologi' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Diagnosa Radiologi
                        </a>
                    </th>
                    <th style="font-weight: 600; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'keterangan', 'dir' => request('sort') == 'keterangan' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Keterangan
                        </a>
                    </th>
                    <th style="width: 35px; padding: 9px 8px; text-align: center;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="color: #333; padding: 9px 8px; text-align: center; vertical-align: middle; border-right: 1px solid #f0f0f0;">{{ $from + $index }}</td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; font-weight: 500; border-right: 1px solid #f0f0f0;">
                        {{ $item->kode_diagnosa }}
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        {{ $item->nama_diagnosa }}
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        {{ $item->kode_katdiagnosa ?? '' }}
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        {{ $item->kode_kelompokdiagnosa ?? '' }}
                    </td>
                    <td style="padding: 9px 8px; text-align: center; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        @if($item->is_non_spesialis)
                            <i class="fas fa-check" style="color: #333; font-size: 13px;"></i>
                        @endif
                    </td>
                    <td style="padding: 9px 8px; text-align: center; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        @if($item->is_diagnosa_lab)
                            <i class="fas fa-check" style="color: #333; font-size: 13px;"></i>
                        @endif
                    </td>
                    <td style="padding: 9px 8px; text-align: center; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        @if($item->is_diagnosa_radiologi)
                            <i class="fas fa-check" style="color: #333; font-size: 13px;"></i>
                        @endif
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        {{ $item->keterangan ?? '' }}
                    </td>
                    <td style="padding: 9px 4px; text-align: center; vertical-align: middle;">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <a href="javascript:void(0)" class="btn-view" data-id="{{ $item->id }}" title="Lihat Detail" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-eye" style="font-size: 11px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn-edit" data-id="{{ $item->id }}" title="Edit Diagnosa" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-pencil-alt" style="font-size: 11px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn-delete" data-id="{{ $item->id }}" data-kode="{{ $item->kode_diagnosa }}" data-nama="{{ $item->nama_diagnosa }}" title="Hapus Diagnosa" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-trash" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td colspan="10" style="padding: 12px 15px; color: #333; font-size: 13px; text-align: left; background-color: #fff;">
                        Tidak ada data yang ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div style="font-size: 12px; color: #666;">
            Halaman {{ $data->currentPage() }} dari {{ $data->lastPage() }}
        </div>
        <div>
            {{ $data->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT DIAGNOSA --}}
<div class="modal fade" id="modalDiagnosa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background-color: #00a65a; color: white; padding: 10px 15px;">
                <h5 class="modal-title" id="modalTitle" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Data Diagnosa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>
            <form id="formDiagnosa">
                @csrf
                <input type="hidden" id="diagnosaId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                <div class="modal-body" style="padding: 20px 25px; font-size: 12.5px;">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kode Diagnosa (ICD-10) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="diag_kode" name="kode_diagnosa" required placeholder="Cth: A00.0" style="text-transform: uppercase;">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold mb-1" style="font-size: 12px;">Nama Diagnosa / Penyakit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="diag_nama" name="nama_diagnosa" required placeholder="Cth: Cholera due to Vibrio cholerae...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kode Katdiagnosa (Kategori)</label>
                            <input type="text" class="form-control form-control-sm" id="diag_kategori" name="kode_katdiagnosa" placeholder="Cth: I. Certain infectious and parasitic diseases" list="listKat">
                            <datalist id="listKat">
                                @foreach($kategoriList as $k)
                                    <option value="{{ $k }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kode Kelompokdiagnosa (Kelompok)</label>
                            <input type="text" class="form-control form-control-sm" id="diag_kelompok" name="kode_kelompokdiagnosa" placeholder="Cth: Diare, Thypoid, ISPA..." list="listKel">
                            <datalist id="listKel">
                                @foreach($kelompokList as $kl)
                                    <option value="{{ $kl }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold mb-1" style="font-size: 12px;">Klasifikasi Diagnosa</label>
                            <div class="d-flex gap-4 p-2 rounded" style="background-color: #f9f9f9; border: 1px solid #e0e0e0;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="diag_non_spesialis" name="is_non_spesialis" value="1">
                                    <label class="form-check-label" for="diag_non_spesialis">
                                        Non Spesialis (144 Penyakit FKTP)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="diag_lab" name="is_diagnosa_lab" value="1">
                                    <label class="form-check-label" for="diag_lab">
                                        Diagnosa Lab
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="diag_rad" name="is_diagnosa_radiologi" value="1">
                                    <label class="form-check-label" for="diag_rad">
                                        Diagnosa Radiologi
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold mb-1" style="font-size: 12px;">Keterangan Tambahan</label>
                            <textarea class="form-control form-control-sm" id="diag_keterangan" name="keterangan" rows="2" placeholder="Catatan penyakit atau nama sinonim bahasa Indonesia..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 10px 15px; background-color: #f7f7f7;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px; padding: 4px 12px;">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" id="btnSimpanDiagnosa" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px; padding: 4px 16px; font-weight: 500;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL DIAGNOSA --}}
<div class="modal fade" id="modalDetailDiagnosa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background-color: #337ab7; color: white; padding: 10px 15px;">
                <h5 class="modal-title" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-info-circle me-1"></i> Detail Data Diagnosa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>
            <div class="modal-body" style="padding: 20px; font-size: 12.5px;">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="35%" style="background-color: #f9f9f9;">Kode Diagnosa</th>
                        <td id="det_kode" class="fw-bold text-primary"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Nama Diagnosa</th>
                        <td id="det_nama" class="fw-bold"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Kode Katdiagnosa</th>
                        <td id="det_kategori"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Kode Kelompokdiagnosa</th>
                        <td id="det_kelompok"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Non Spesialis</th>
                        <td id="det_non_spesialis"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Diagnosa Lab</th>
                        <td id="det_lab"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Diagnosa Radiologi</th>
                        <td id="det_radiologi"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Keterangan</th>
                        <td id="det_keterangan"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="padding: 10px 15px; background-color: #f7f7f7;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px; padding: 4px 12px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const modalDiag = new bootstrap.Modal(document.getElementById('modalDiagnosa'));
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetailDiagnosa'));

    // Tambah Diagnosa
    document.getElementById('btnTambahDiagnosa')?.addEventListener('click', function () {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-1"></i> Tambah Data Diagnosa';
        document.getElementById('formDiagnosa').reset();
        document.getElementById('diagnosaId').value = '';
        document.getElementById('formMethod').value = 'POST';
        modalDiag.show();
    });

    // Edit Diagnosa
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch(`/diagnosa/${id}/edit`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-1"></i> Edit Data Diagnosa';
                document.getElementById('diagnosaId').value = data.id;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('diag_kode').value = data.kode_diagnosa;
                document.getElementById('diag_nama').value = data.nama_diagnosa;
                document.getElementById('diag_kategori').value = data.kode_katdiagnosa || '';
                document.getElementById('diag_kelompok').value = data.kode_kelompokdiagnosa || '';
                document.getElementById('diag_non_spesialis').checked = !!data.is_non_spesialis;
                document.getElementById('diag_lab').checked = !!data.is_diagnosa_lab;
                document.getElementById('diag_rad').checked = !!data.is_diagnosa_radiologi;
                document.getElementById('diag_keterangan').value = data.keterangan || '';
                modalDiag.show();
            })
            .catch(err => alert('Gagal mengambil data diagnosa'));
        });
    });

    // View Detail Diagnosa
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch(`/diagnosa/${id}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('det_kode').textContent = data.kode_diagnosa;
                document.getElementById('det_nama').textContent = data.nama_diagnosa;
                document.getElementById('det_kategori').textContent = data.kode_katdiagnosa || '-';
                document.getElementById('det_kelompok').textContent = data.kode_kelompokdiagnosa || '-';
                document.getElementById('det_non_spesialis').innerHTML = data.is_non_spesialis ? '<span class="badge bg-success">Ya (Non Spesialis)</span>' : '<span class="badge bg-secondary">Tidak</span>';
                document.getElementById('det_lab').innerHTML = data.is_diagnosa_lab ? '<span class="badge bg-info text-dark">Ya</span>' : '<span class="badge bg-secondary">Tidak</span>';
                document.getElementById('det_radiologi').innerHTML = data.is_diagnosa_radiologi ? '<span class="badge bg-info text-dark">Ya</span>' : '<span class="badge bg-secondary">Tidak</span>';
                document.getElementById('det_keterangan').textContent = data.keterangan || '-';
                modalDetail.show();
            })
            .catch(err => alert('Gagal mengambil detail diagnosa'));
        });
    });

    // Submit Form
    document.getElementById('formDiagnosa').addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('diagnosaId').value;
        const method = document.getElementById('formMethod').value;
        const url = id ? `/diagnosa/${id}` : '/diagnosa';

        const formData = new FormData(this);
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        const btnSubmit = document.getElementById('btnSimpanDiagnosa');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                modalDiag.hide();
                window.location.reload();
            } else {
                alert('Gagal menyimpan: ' + (data.message || 'Periksa input Anda'));
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-save me-1"></i> Simpan';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan koneksi.');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-save me-1"></i> Simpan';
        });
    });

    // Delete Diagnosa
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const kode = this.getAttribute('data-kode');
            const nama = this.getAttribute('data-nama');

            if (confirm(`Apakah Anda yakin ingin menghapus Diagnosa [${kode}] ${nama}?`)) {
                fetch(`/diagnosa/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(err => alert('Terjadi kesalahan koneksi saat menghapus.'));
            }
        });
    });
});
</script>
@endsection
