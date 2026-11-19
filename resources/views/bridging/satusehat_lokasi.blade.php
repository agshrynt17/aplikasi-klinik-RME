@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-lokasi-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .konhakes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }

    /* Action Box */
    .card-action-box {
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        padding: 8px 12px;
        margin-bottom: 12px;
    }
    .btn-orange-cari {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-orange-cari:hover {
        background-color: #d68407;
    }
    .btn-reset-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-reset-light:hover {
        background-color: #f4f4f4;
    }
    .btn-green-tambah {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-green-tambah:hover {
        background-color: #008d4c;
    }
    .btn-cyan-tarik {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-cyan-tarik:hover {
        background-color: #00acd6;
    }

    /* Cyan Line */
    .cyan-divider {
        border-top: 2px solid #00c0ef;
        margin-top: 8px;
        margin-bottom: 8px;
    }

    .meta-info-line {
        font-size: 12px;
        color: #555;
        margin-bottom: 6px;
    }

    /* Table */
    .table-satusehat-lokasi {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 12px;
    }
    .table-satusehat-lokasi th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 8px 8px;
        font-weight: 700;
        color: #337ab7;
        font-size: 12px;
        text-align: left;
    }
    .table-satusehat-lokasi td {
        border: 1px solid #e4e7ea;
        padding: 6px 8px;
        vertical-align: middle;
        color: #333;
        font-size: 12px;
    }
    .table-satusehat-lokasi tbody tr:hover {
        background-color: #f5f5f5;
    }

    .btn-detail-cyan {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-detail-cyan:hover {
        background-color: #00acd6;
        color: #fff !important;
    }
    .badge-aktif {
        background-color: #00a65a;
        color: #fff;
        font-size: 10px;
        font-weight: normal;
        padding: 2px 6px;
        border-radius: 2px;
        display: inline-block;
    }
    .link-referensi {
        color: #a94442 !important;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
    }
    .link-referensi:hover {
        text-decoration: underline;
    }
</style>

<div class="satusehat-lokasi-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Setting Ruang Rawat - Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-lokasi/index">Setting Ruang Rawat - Satu Sehat</a>
        </div>
    </div>

    {{-- Alert sukses / info --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <div id="alertAjax" class="alert d-none py-2 px-3 small my-2" role="alert"></div>

    {{-- Action Box --}}
    <div class="card-action-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn-orange-cari" id="btnToggleFilter">
                    <i class="fas fa-search"></i> Cari data
                </button>
                <a href="/satusehat-lokasi/index" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
                <a href="/satusehat-lokasi/create" class="btn-green-tambah">
                    <i class="fas fa-plus"></i> Tambah Ruangan
                </a>
            </div>
            <div>
                <button type="button" class="btn-cyan-tarik" id="btnTarikData">
                    <i class="fas fa-download"></i> Tarik Data Ruang Dari Satu Sehat
                </button>
            </div>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterBox" class="mt-2 pt-2 border-top {{ request('q') ? '' : 'd-none' }}">
            <form method="GET" action="/satusehat-lokasi/index" class="d-flex align-items-center gap-2">
                <input type="text" name="q" class="form-control form-control-sm" style="max-width: 300px; height: 30px; font-size: 12px;"
                       placeholder="Cari Ruangan Satu Sehat atau Aplikasi..." value="{{ request('q') }}">
                <button type="submit" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 12px;">Cari</button>
                <a href="/satusehat-lokasi/index" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 12px;">Reset</a>
            </form>
        </div>
    </div>

    {{-- Cyan Line Divider --}}
    <div class="cyan-divider"></div>

    {{-- Meta Count Info --}}
    <div class="meta-info-line">
        Menampilkan 1-{{ $total }} dari {{ $total }} item.
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-satusehat-lokasi">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center; color: #333;">#</th>
                    <th style="width: 80px;"></th>
                    <th>Nama Ruangan (Satu Sehat)</th>
                    <th>Referensi Ruangan</th>
                    <th style="width: 80px;">Status</th>
                    <th style="width: 170px;">Diubah Pada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lokasis as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        <a href="/satusehat-lokasi/view?id={{ $item->id }}" class="btn-detail-cyan">
                            <i class="fas fa-info-circle"></i> Detail
                        </a>
                    </td>
                    <td>
                        <div class="fw-bold" style="color: #333;">{{ $item->nama_ruangan_satusehat }}</div>
                        @if($item->location_id_satusehat)
                        <div style="font-size: 11px; color: #777;">ID: {{ $item->location_id_satusehat }}</div>
                        @endif
                    </td>
                    <td>
                        <a href="javascript:void(0)" class="link-referensi btn-edit-ref"
                           data-id="{{ $item->id }}"
                           data-referensi="{{ $item->referensi_ruangan }}"
                           data-ruangan-id="{{ $item->ruangan_id }}"
                           data-satusehat="{{ $item->nama_ruangan_satusehat }}">
                            <i class="fas fa-pencil-alt" style="font-size: 10px; margin-right: 4px;"></i>{{ $item->referensi_ruangan ?? '-' }}
                        </a>
                    </td>
                    <td>
                        <span class="badge-aktif">{{ $item->status ?? 'Aktif' }}</span>
                    </td>
                    <td>
                        {{ $item->diubah_pada ? date('d-m-Y H:i:s', strtotime($item->diubah_pada)) : ($item->updated_at ? $item->updated_at->format('d-m-Y H:i:s') : '-') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Belum ada data Ruang Rawat Satu Sehat. Silakan klik tombol <strong>+ Tambah Ruangan</strong> atau <strong>Tarik Data Ruang Dari Satu Sehat</strong>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Edit Referensi Ruangan --}}
<div class="modal fade" id="modalEditRef" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2" style="background:#f4f4f4; border-bottom:1px solid #d2d6de;">
                <h6 class="modal-title fw-bold" style="font-size: 14px;">
                    <i class="fas fa-edit text-primary me-1"></i> Ubah Referensi Ruangan
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditRef">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body py-3" style="font-size: 13px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Nama Ruangan Pada Satu Sehat</label>
                        <input type="text" id="edit_satusehat" class="form-control form-control-sm" readonly style="background:#eee;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Pilih Ruangan Aplikasi (Referensi)</label>
                        <select name="ruangan_id" id="edit_ruangan_id" class="form-select form-select-sm">
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($ruangans as $r)
                                <option value="{{ $r->id }}" data-nama="{{ $r->nama_ruangan }}">{{ $r->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small">Nama Referensi Ruangan</label>
                        <input type="text" name="referensi_ruangan" id="edit_referensi" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer py-2" style="background:#f9f9f9; border-top:1px solid #d2d6de;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success" id="btnSaveRef" style="background:#00a65a; border-color:#008d4c;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Filter
    var btnToggleFilter = document.getElementById('btnToggleFilter');
    var filterBox = document.getElementById('filterBox');
    if (btnToggleFilter && filterBox) {
        btnToggleFilter.addEventListener('click', function() {
            filterBox.classList.toggle('d-none');
        });
    }

    // Modal Edit Referensi Ruangan
    var modalEditEl = document.getElementById('modalEditRef');
    var modalEdit = modalEditEl ? new bootstrap.Modal(modalEditEl) : null;
    var editId = document.getElementById('edit_id');
    var editSatusehat = document.getElementById('edit_satusehat');
    var editReferensi = document.getElementById('edit_referensi');
    var editRuanganId = document.getElementById('edit_ruangan_id');

    document.querySelectorAll('.btn-edit-ref').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var ref = this.getAttribute('data-referensi');
            var ss = this.getAttribute('data-satusehat');
            var rId = this.getAttribute('data-ruangan-id');

            editId.value = id;
            editSatusehat.value = ss;
            editReferensi.value = ref;
            if (editRuanganId) editRuanganId.value = rId || '';

            if (modalEdit) modalEdit.show();
        });
    });

    if (editRuanganId) {
        editRuanganId.addEventListener('change', function() {
            var opt = editRuanganId.options[editRuanganId.selectedIndex];
            if (opt && opt.value) {
                editReferensi.value = opt.getAttribute('data-nama') || opt.text;
            }
        });
    }

    var formEditRef = document.getElementById('formEditRef');
    if (formEditRef) {
        formEditRef.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(formEditRef);
            var btnSave = document.getElementById('btnSaveRef');
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            fetch('/satusehat-lokasi/update', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btnSave.disabled = false;
                btnSave.innerHTML = '<i class="fas fa-save me-1"></i> Simpan';
                if (modalEdit) modalEdit.hide();

                var alertAjax = document.getElementById('alertAjax');
                alertAjax.className = 'alert alert-success py-2 px-3 small my-2';
                alertAjax.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + (data.message || 'Berhasil disimpan.');
                alertAjax.classList.remove('d-none');

                setTimeout(function() {
                    window.location.reload();
                }, 800);
            })
            .catch(function(err) {
                btnSave.disabled = false;
                btnSave.innerHTML = '<i class="fas fa-save me-1"></i> Simpan';
                alert('Terjadi kesalahan saat menyimpan data.');
            });
        });
    }

    // Tarik Data Ruang Dari Satu Sehat
    var btnTarikData = document.getElementById('btnTarikData');
    if (btnTarikData) {
        btnTarikData.addEventListener('click', function() {
            var btn = this;
            var originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menarik data...';

            fetch('/satusehat-lokasi/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                var alertAjax = document.getElementById('alertAjax');
                alertAjax.className = 'alert alert-success py-2 px-3 small my-2';
                alertAjax.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + (data.message || 'Sinkronisasi selesai.');
                alertAjax.classList.remove('d-none');
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                alert('Gagal menghubungi Satu Sehat.');
            });
        });
    }
});
</script>
@endsection

