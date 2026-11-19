@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-pasien-page {
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
    .table-satusehat-pasien {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 12px;
    }
    .table-satusehat-pasien th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 8px 8px;
        font-weight: 700;
        color: #337ab7;
        font-size: 12px;
        text-align: left;
    }
    .table-satusehat-pasien td {
        border: 1px solid #e4e7ea;
        padding: 6px 8px;
        vertical-align: middle;
        color: #333;
        font-size: 12px;
    }
    .table-satusehat-pasien tbody tr:hover {
        background-color: #f5f5f5;
    }

    .link-detail-pasien {
        color: #337ab7 !important;
        font-size: 11px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .link-detail-pasien:hover {
        text-decoration: underline;
    }

    .btn-dapatkan-ihs {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-dapatkan-ihs:hover {
        background-color: #d68407;
    }

    .btn-delete-ihs {
        color: #dd4b39 !important;
        font-size: 11px;
        text-decoration: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .btn-delete-ihs:hover {
        text-decoration: underline;
    }
</style>

<div class="satusehat-pasien-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Pencarian ID Pasien - Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-pasien/index">Pencarian ID Pasien - Satu Sehat</a>
        </div>
    </div>

    {{-- Alert info / sukses --}}
    <div id="alertAjax" class="alert d-none py-2 px-3 small my-2" role="alert"></div>

    {{-- Action Box --}}
    <div class="card-action-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn-orange-cari" id="btnToggleFilter">
                    <i class="fas fa-search"></i> Cari data
                </button>
                <a href="/satusehat-pasien/index" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterBox" class="mt-2 pt-2 border-top {{ request('q') ? '' : 'd-none' }}">
            <form method="GET" action="/satusehat-pasien/index" class="d-flex align-items-center gap-2">
                <input type="text" name="q" class="form-control form-control-sm" style="max-width: 350px; height: 30px; font-size: 12px;"
                       placeholder="Cari Nama Pasien, No RM, NIK, KIS, atau ID Satu Sehat..." value="{{ request('q') }}">
                <button type="submit" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 12px;">Cari</button>
                <a href="/satusehat-pasien/index" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 12px;">Reset</a>
            </form>
        </div>
    </div>

    {{-- Cyan Line Divider --}}
    <div class="cyan-divider"></div>

    {{-- Meta Count Info --}}
    <div class="meta-info-line">
        Menampilkan {{ $pasiens->firstItem() ?? 0 }}-{{ $pasiens->lastItem() ?? 0 }} dari {{ number_format($pasiens->total(), 0, ',', '.') }} item.
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-satusehat-pasien">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center; color: #333;">#</th>
                    <th style="width: 120px;">Kode RM</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Nama Pasien <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th style="width: 220px;">ID Satu Sehat</th>
                    <th style="width: 150px;">Tgl Lahir &amp; JK</th>
                    <th style="width: 190px;">NIK &amp; KIS</th>
                    <th style="width: 120px;">No. HP</th>
                    <th>Alamat Identitas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $index => $item)
                <tr id="row_pasien_{{ $item->id }}">
                    <td style="text-align: center;">{{ $pasiens->firstItem() + $index }}</td>
                    <td>
                        <div class="fw-bold" style="color: #333;">{{ $item->no_rm ?? '-' }}</div>
                        <a href="/satusehat-pasien/view?id={{ $item->id }}" class="link-detail-pasien">
                            <i class="fas fa-info-circle"></i> Detail
                        </a>
                    </td>
                    <td>
                        <div class="fw-bold" style="color: #333; text-transform: uppercase;">{{ $item->nama_pasien }}</div>
                    </td>
                    <td id="ihs_cell_{{ $item->id }}">
                        @if($item->kode_ihs_satusehat)
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold text-dark">{{ $item->kode_ihs_satusehat }}</span>
                                <a href="javascript:void(0)" class="text-primary btn-sync-ihs" data-id="{{ $item->id }}" data-nik="{{ $item->nik }}" title="Sinkronkan Ulang">
                                    <i class="fas fa-sync-alt fa-xs"></i>
                                </a>
                            </div>
                            <div style="font-size: 11px; color: #666;">
                                a.n: {{ $item->nama_satusehat ?? $item->nama_pasien }}
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="btn-delete-ihs" data-id="{{ $item->id }}">
                                    <i class="fas fa-trash-alt"></i> Hapus ID SS
                                </a>
                            </div>
                        @else
                            <button type="button" class="btn-dapatkan-ihs btn-sync-ihs" data-id="{{ $item->id }}" data-nik="{{ $item->nik }}">
                                <i class="fas fa-id-card"></i> Dapatkan ID IHS by NIK
                            </button>
                        @endif
                    </td>
                    <td>
                        <div>{{ $item->tanggal_lahir ? date('d/m/Y', strtotime($item->tanggal_lahir)) : '-' }}</div>
                        <div style="font-size: 11px; color: #777;">
                            JK: {{ $item->jenis_kelamin == 'L' ? 'Laki-Laki' : ($item->jenis_kelamin == 'P' ? 'Perempuan' : ($item->jenis_kelamin ?? '-')) }}
                        </div>
                    </td>
                    <td>
                        <div>NIK: {{ $item->nik ?? '-' }}</div>
                        <div style="font-size: 11px; color: #777;">
                            KIS: {{ $item->no_bpjs ?? '-' }}
                        </div>
                    </td>
                    <td>
                        {{ $item->no_hp ?? '-' }}
                    </td>
                    <td>
                        {{ $item->alamat ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        Data pasien tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
        <div style="font-size: 12px; color: #777;">
            Menampilkan data {{ $pasiens->firstItem() ?? 0 }} sampai {{ $pasiens->lastItem() ?? 0 }}
        </div>
        <div>
            {{ $pasiens->links('pagination::bootstrap-4') }}
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

    var alertAjax = document.getElementById('alertAjax');

    function showAlert(type, message) {
        alertAjax.className = 'alert alert-' + type + ' py-2 px-3 small my-2';
        alertAjax.innerHTML = (type === 'success' ? '<i class="fas fa-check-circle me-1"></i> ' : '<i class="fas fa-exclamation-circle me-1"></i> ') + message;
        alertAjax.classList.remove('d-none');
        setTimeout(function() {
            alertAjax.classList.add('d-none');
        }, 4000);
    }

    // Delegate Dapatkan ID IHS / Sync
    document.addEventListener('click', function(e) {
        var target = e.target.closest('.btn-sync-ihs');
        if (target) {
            e.preventDefault();
            var id = target.getAttribute('data-id');
            var nik = target.getAttribute('data-nik');

            if (!nik) {
                showAlert('danger', 'Gagal: Pasien tidak memiliki NIK.');
                return;
            }

            var originalHtml = target.innerHTML;
            target.disabled = true;
            target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            fetch('/satusehat-pasien/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, nik: nik })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                target.disabled = false;
                target.innerHTML = originalHtml;
                if (data.success) {
                    showAlert('success', data.message);
                    var cell = document.getElementById('ihs_cell_' + id);
                    if (cell) {
                        cell.innerHTML = `
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold text-dark">${data.ihs}</span>
                                <a href="javascript:void(0)" class="text-primary btn-sync-ihs" data-id="${id}" data-nik="${nik}" title="Sinkronkan Ulang">
                                    <i class="fas fa-sync-alt fa-xs"></i>
                                </a>
                            </div>
                            <div style="font-size: 11px; color: #666;">
                                a.n: ${data.nama || '-'}
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="btn-delete-ihs" data-id="${id}">
                                    <i class="fas fa-trash-alt"></i> Hapus ID SS
                                </a>
                            </div>
                        `;
                    }
                } else {
                    showAlert('danger', data.message || 'Gagal mendapatkan ID Satu Sehat.');
                }
            })
            .catch(function() {
                target.disabled = false;
                target.innerHTML = originalHtml;
                showAlert('danger', 'Terjadi kesalahan koneksi.');
            });
        }
    });

    // Delegate Hapus ID SS
    document.addEventListener('click', function(e) {
        var target = e.target.closest('.btn-delete-ihs');
        if (target) {
            e.preventDefault();
            if (!confirm('Yakin ingin menghapus ID Satu Sehat pasien ini?')) return;

            var id = target.getAttribute('data-id');
            fetch('/satusehat-pasien/delete-ihs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    showAlert('success', data.message);
                    var cell = document.getElementById('ihs_cell_' + id);
                    if (cell) {
                        var nik = cell.closest('tr').querySelector('.btn-sync-ihs') ? cell.closest('tr').querySelector('.btn-sync-ihs').getAttribute('data-nik') : '';
                        cell.innerHTML = `
                            <button type="button" class="btn-dapatkan-ihs btn-sync-ihs" data-id="${id}" data-nik="${nik}">
                                <i class="fas fa-id-card"></i> Dapatkan ID IHS by NIK
                            </button>
                        `;
                    }
                } else {
                    showAlert('danger', data.message || 'Gagal menghapus ID Satu Sehat.');
                }
            })
            .catch(function() {
                showAlert('danger', 'Terjadi kesalahan koneksi.');
            });
        }
    });
});
</script>
@endsection
