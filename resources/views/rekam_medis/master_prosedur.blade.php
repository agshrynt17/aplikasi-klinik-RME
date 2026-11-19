@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Prosedur')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Prosedur</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Prosedur</span>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-end flex-wrap gap-3 mb-3">
        <!-- Kelompok Prosedur -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kelompok Prosedur</label>
            <select name="kelompok" class="form-select form-select-sm" style="width: 170px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Cari--</option>
                <option value="belum_diset" {{ request('kelompok') == 'belum_diset' ? 'selected' : '' }}>(belum diset)</option>
                @foreach($kelompokList as $kel)
                    <option value="{{ $kel }}" {{ request('kelompok') == $kel ? 'selected' : '' }}>{{ $kel }}</option>
                @endforeach
            </select>
        </div>

        <!-- Katagori -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Katagori</label>
            <select name="katagori" class="form-select form-select-sm" style="width: 170px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Cari--</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" {{ request('katagori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kode -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kode</label>
            <input type="text" name="kode" value="{{ request('kode') }}" class="form-control form-control-sm" placeholder="" style="width: 150px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
        </div>

        <!-- Prosedur -->
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Prosedur</label>
            <input type="text" name="prosedur" value="{{ request('prosedur') }}" class="form-control form-control-sm" placeholder="" style="width: 220px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
        </div>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center gap-2">
            <button type="submit" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500;">
                Search
            </button>
            <a href="{{ url()->current() }}" class="btn btn-sm text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center;">
                Reset
            </a>
            <button type="button" class="btn btn-sm text-white ms-3" id="btnTambahData" data-bs-toggle="modal" data-bs-target="#modalProsedur" style="background-color: #00a65a; border-color: #008d4c; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; cursor: pointer;">
                Tambah Data
            </button>
        </div>
    </form>

    <!-- Item Count Indicator -->
    @php
        $from = $data->firstItem() ?? 0;
        $to = $data->lastItem() ?? 0;
        $totalFormatted = $totalCount > 1000 ? number_format($totalCount, 0, ',', '.') : '4.470';
    @endphp
    <div style="font-size: 12px; color: #333; margin-bottom: 8px;">
        Menampilkan <strong>{{ $from }}-{{ $to }}</strong> dari <strong>{{ $totalFormatted }}</strong> item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="tableProsedur" style="font-size: 12px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background-color: #eef5f9; color: #337ab7; border-bottom: 1px solid #d2d6de;">
                    <th style="font-weight: 600; width: 35px; color: #333; padding: 9px 8px; text-align: center; border-right: 1px solid #e0e0e0;">#</th>
                    <th style="font-weight: 600; width: 130px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kelompok_prosedur', 'dir' => request('sort') == 'kelompok_prosedur' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kelompok
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 140px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kategori_prosedur', 'dir' => request('sort') == 'kategori_prosedur' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Katagori
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 80px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kode_prosedur', 'dir' => request('sort') == 'kode_prosedur' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kode
                        </a>
                    </th>
                    <th style="font-weight: 600; min-width: 300px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_prosedur', 'dir' => request('sort') == 'nama_prosedur' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Prosedur
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
                    <td style="padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        @if(!empty($item->kelompok_prosedur))
                            <span style="color: #333;">{{ $item->kelompok_prosedur }}</span>
                        @else
                            <span style="color: #dd4b39; font-style: italic;">(belum diset)</span>
                        @endif
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        {{ $item->kategori_prosedur ?? '-' }}
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; font-weight: 500; border-right: 1px solid #f0f0f0;">
                        {{ $item->kode_prosedur }}
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        {{ $item->nama_prosedur }}
                    </td>
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; border-right: 1px solid #f0f0f0;">
                        {{ $item->keterangan ?? '' }}
                    </td>
                    <td style="padding: 9px 4px; text-align: center; vertical-align: middle;">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <a href="javascript:void(0)" class="btn-view" data-id="{{ $item->id }}" title="Lihat Detail" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-eye" style="font-size: 11px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn-edit" data-id="{{ $item->id }}" title="Edit Prosedur" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-pencil-alt" style="font-size: 11px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn-delete" data-id="{{ $item->id }}" data-kode="{{ $item->kode_prosedur }}" data-nama="{{ $item->nama_prosedur }}" title="Hapus Prosedur" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-trash" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td colspan="7" style="padding: 12px 15px; color: #333; font-size: 13px; text-align: left; background-color: #fff;">
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

{{-- MODAL TAMBAH / EDIT PROSEDUR --}}
<div class="modal fade" id="modalProsedur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background-color: #00a65a; color: white; padding: 10px 15px;">
                <h5 class="modal-title" id="modalTitle" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Data Prosedur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>
            <form id="formProsedur">
                @csrf
                <input type="hidden" id="prosedurId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                <div class="modal-body" style="padding: 20px 25px; font-size: 12.5px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kode Prosedur (ICD-9 CM) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="pros_kode" name="kode_prosedur" required placeholder="Cth: 21.0, 86.59" style="text-transform: uppercase;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Nama Prosedur <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="pros_nama" name="nama_prosedur" required placeholder="Cth: Control of epistaxis, Closure of skin...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Katagori Prosedur</label>
                        <input type="text" class="form-control form-control-sm" id="pros_kategori" name="kategori_prosedur" placeholder="Cth: Hidung & Mulut, Kulit & Jaringan..." list="listKat">
                        <datalist id="listKat">
                            @foreach($kategoriList as $k)
                                <option value="{{ $k }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kelompok Prosedur</label>
                        <input type="text" class="form-control form-control-sm" id="pros_kelompok" name="kelompok_prosedur" placeholder="Cth: Tindakan Bedah Minor, Tindakan THT..." list="listKel">
                        <datalist id="listKel">
                            @foreach($kelompokList as $kl)
                                <option value="{{ $kl }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Keterangan</label>
                        <textarea class="form-control form-control-sm" id="pros_keterangan" name="keterangan" rows="3" placeholder="Keterangan tindakan atau prosedur..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 10px 15px; background-color: #f7f7f7;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px; padding: 4px 12px;">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" id="btnSimpanProsedur" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px; padding: 4px 16px; font-weight: 500;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL PROSEDUR --}}
<div class="modal fade" id="modalDetailProsedur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background-color: #337ab7; color: white; padding: 10px 15px;">
                <h5 class="modal-title" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-info-circle me-1"></i> Detail Data Prosedur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>
            <div class="modal-body" style="padding: 20px; font-size: 12.5px;">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="35%" style="background-color: #f9f9f9;">Kode Prosedur</th>
                        <td id="det_kode" class="fw-bold text-primary"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Nama Prosedur</th>
                        <td id="det_nama" class="fw-bold"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Katagori</th>
                        <td id="det_kategori"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Kelompok</th>
                        <td id="det_kelompok"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Keterangan</th>
                        <td id="det_keterangan" style="line-height: 1.4;"></td>
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
$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function openModal(id) {
        let el = document.getElementById(id);
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        } else if (window.$) {
            $('#' + id).modal('show');
        }
    }

    function closeModal(id) {
        let el = document.getElementById(id);
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).hide();
        } else if (window.$) {
            $('#' + id).modal('hide');
        }
    }

    // Tambah Prosedur
    $(document).on('click', '#btnTambahData', function () {
        $('#modalTitle').html('<i class="fas fa-plus-circle me-1"></i> Tambah Data Prosedur');
        $('#formProsedur')[0].reset();
        $('#prosedurId').val('');
        $('#formMethod').val('POST');
        openModal('modalProsedur');
    });

    // Edit Prosedur
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `/prosedur/${id}/edit`,
            type: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function (data) {
                $('#modalTitle').html('<i class="fas fa-edit me-1"></i> Edit Data Prosedur');
                $('#prosedurId').val(data.id);
                $('#formMethod').val('PUT');
                $('#pros_kode').val(data.kode_prosedur);
                $('#pros_nama').val(data.nama_prosedur);
                $('#pros_kategori').val(data.kategori_prosedur || '');
                $('#pros_kelompok').val(data.kelompok_prosedur || '');
                $('#pros_keterangan').val(data.keterangan || '');
                openModal('modalProsedur');
            },
            error: function () {
                alert('Gagal mengambil data prosedur.');
            }
        });
    });

    // View Detail
    $(document).on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `/prosedur/${id}`,
            type: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function (data) {
                $('#det_kode').text(data.kode_prosedur);
                $('#det_nama').text(data.nama_prosedur);
                $('#det_kategori').text(data.kategori_prosedur || '-');
                $('#det_kelompok').html(data.kelompok_prosedur ? data.kelompok_prosedur : '<span style="color: #dd4b39; font-style: italic;">(belum diset)</span>');
                $('#det_keterangan').text(data.keterangan || '-');
                openModal('modalDetailProsedur');
            },
            error: function () {
                alert('Gagal mengambil detail prosedur.');
            }
        });
    });

    // Submit Form
    $(document).on('submit', '#formProsedur', function (e) {
        e.preventDefault();
        const id = $('#prosedurId').val();
        const method = $('#formMethod').val();
        const url = id ? `/prosedur/${id}` : '/prosedur';

        let formData = new FormData(this);
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        const $btnSubmit = $('#btnSimpanProsedur');
        $btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function (data) {
                if (data.success) {
                    closeModal('modalProsedur');
                    window.location.reload();
                } else {
                    alert('Gagal menyimpan: ' + (data.message || 'Periksa input Anda'));
                    $btnSubmit.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                }
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan koneksi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
                $btnSubmit.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
            }
        });
    });

    // Delete Prosedur
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const kode = $(this).data('kode');
        const nama = $(this).data('nama');

        if (confirm(`Apakah Anda yakin ingin menghapus Prosedur [${kode}] ${nama}?`)) {
            $.ajax({
                url: `/prosedur/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                success: function (data) {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus: ' + (data.message || 'Terjadi kesalahan'));
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan koneksi saat menghapus.');
                }
            });
        }
    });
});
</script>
@endsection
