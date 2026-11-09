@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Master Diagnosa Keperawatan')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Master Diagnosa Keperawatan</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Master Diagnosa Keperawatan</span>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-end flex-wrap gap-2 mb-3">
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Nama Diagnosa</label>
            <input type="text" name="nama_diagnosa" value="{{ request('nama_diagnosa') }}" class="form-control form-control-sm" placeholder="" style="width: 260px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
        </div>
        <button type="submit" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500;">
            Search
        </button>
        <a href="{{ url()->current() }}" class="btn btn-sm text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center;">
            Reset
        </a>
        <button type="button" class="btn btn-sm text-white" id="btnTambahData" style="background-color: #00a65a; border-color: #008d4c; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center;">
            Tambah Data
        </button>
    </form>

    <!-- Item Count Indicator -->
    @php
        $from = $data->firstItem() ?? 0;
        $to = $data->lastItem() ?? 0;
    @endphp
    <div style="font-size: 12px; color: #333; margin-bottom: 8px;">
        Menampilkan <strong>{{ $from }}-{{ $to }}</strong> dari <strong>{{ $totalCount }}</strong> item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="tableDiagnosaKep" style="font-size: 12px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background-color: #eef5f9; color: #337ab7; border-bottom: 1px solid #d2d6de;">
                    <th style="font-weight: 600; width: 35px; color: #333; padding: 9px 8px; text-align: center; border-right: 1px solid #e0e0e0;">#</th>
                    <th style="font-weight: 600; width: 120px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kode_diagnosa', 'dir' => request('sort') == 'kode_diagnosa' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kode Diagnosa
                        </a>
                    </th>
                    <th style="font-weight: 600; width: 220px; color: #337ab7; padding: 9px 8px; border-right: 1px solid #e0e0e0;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_diagnosa', 'dir' => request('sort') == 'nama_diagnosa' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Nama Diagnosa
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
                    <td style="color: #333; padding: 9px 8px; vertical-align: middle; line-height: 1.4; border-right: 1px solid #f0f0f0;">
                        {{ $item->keterangan }}
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
                    <td colspan="5" style="padding: 12px 15px; color: #333; font-size: 13px; text-align: left; background-color: #fff;">
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

{{-- MODAL TAMBAH / EDIT DIAGNOSA KEPERAWATAN --}}
<div class="modal fade" id="modalDiagnosaKep" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background-color: #00a65a; color: white; padding: 10px 15px;">
                <h5 class="modal-title" id="modalTitle" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Data Diagnosa Keperawatan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>
            <form id="formDiagnosaKep">
                @csrf
                <input type="hidden" id="diagnosaKepId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                <div class="modal-body" style="padding: 20px 25px; font-size: 12.5px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kode Diagnosa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="diag_kode" name="kode_diagnosa" required placeholder="Cth: DP001, D0130" style="text-transform: uppercase;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Nama Diagnosa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="diag_nama" name="nama_diagnosa" required placeholder="Cth: Bersihan Jalan Nafas tidak efektif">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Keterangan</label>
                        <textarea class="form-control form-control-sm" id="diag_keterangan" name="keterangan" rows="4" placeholder="Penjelasan atau definisi diagnosis keperawatan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 10px 15px; background-color: #f7f7f7;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px; padding: 4px 12px;">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" id="btnSimpanDiagnosaKep" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px; padding: 4px 16px; font-weight: 500;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL DIAGNOSA KEPERAWATAN --}}
<div class="modal fade" id="modalDetailDiagnosaKep" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background-color: #337ab7; color: white; padding: 10px 15px;">
                <h5 class="modal-title" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-info-circle me-1"></i> Detail Diagnosa Keperawatan
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
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const modalDiag = new bootstrap.Modal(document.getElementById('modalDiagnosaKep'));
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetailDiagnosaKep'));

    // Tambah Diagnosa Keperawatan
    document.getElementById('btnTambahData')?.addEventListener('click', function () {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-1"></i> Tambah Data Diagnosa Keperawatan';
        document.getElementById('formDiagnosaKep').reset();
        document.getElementById('diagnosaKepId').value = '';
        document.getElementById('formMethod').value = 'POST';
        modalDiag.show();
    });

    // Edit Diagnosa Keperawatan
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch(`/diagnosa-keperawatan/${id}/edit`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-1"></i> Edit Data Diagnosa Keperawatan';
                document.getElementById('diagnosaKepId').value = data.id;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('diag_kode').value = data.kode_diagnosa;
                document.getElementById('diag_nama').value = data.nama_diagnosa;
                document.getElementById('diag_keterangan').value = data.keterangan || '';
                modalDiag.show();
            })
            .catch(err => alert('Gagal mengambil data diagnosa keperawatan'));
        });
    });

    // View Detail
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch(`/diagnosa-keperawatan/${id}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('det_kode').textContent = data.kode_diagnosa;
                document.getElementById('det_nama').textContent = data.nama_diagnosa;
                document.getElementById('det_keterangan').textContent = data.keterangan || '-';
                modalDetail.show();
            })
            .catch(err => alert('Gagal mengambil detail diagnosa keperawatan'));
        });
    });

    // Submit Form
    document.getElementById('formDiagnosaKep').addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('diagnosaKepId').value;
        const method = document.getElementById('formMethod').value;
        const url = id ? `/diagnosa-keperawatan/${id}` : '/diagnosa-keperawatan';

        const formData = new FormData(this);
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        const btnSubmit = document.getElementById('btnSimpanDiagnosaKep');
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

            if (confirm(`Apakah Anda yakin ingin menghapus Diagnosa Keperawatan [${kode}] ${nama}?`)) {
                fetch(`/diagnosa-keperawatan/${id}`, {
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
