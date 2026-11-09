@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Log Data Peserta Baru dari Mobile JKN')

@section('content')
<style>
    .page-header-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
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

    /* Main Container Box */
    .table-container-box {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 14px 16px 20px 16px;
    }

    .table-counter-text {
        font-size: 12.5px;
        color: #333333;
        margin-bottom: 8px;
    }

    .table-peserta-baru {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
    }
    .table-peserta-baru th {
        background-color: #edf4f8;
        color: #337ab7;
        font-weight: 700;
        border: 1px solid #dbe6ec;
        padding: 6px 8px;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-peserta-baru td {
        border: 1px solid #eef2f5;
        padding: 6px 8px;
        vertical-align: middle;
        color: #333333;
    }
    .table-peserta-baru tr:nth-child(even) td {
        background-color: #fbfcfd;
    }
    .table-peserta-baru tr:hover td {
        background-color: #f1f7fa;
    }

    /* Filter inputs inside table header */
    .input-header-filter {
        width: 100%;
        font-size: 12px;
        height: 28px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        padding: 2px 6px;
        background-color: #ffffff;
        color: #333333;
        font-weight: normal;
    }
    .input-header-filter:focus {
        border-color: #3c8dbc;
        outline: none;
    }

    .text-belum-diset {
        color: #dd4b39;
        font-style: italic;
    }

    /* Action eye icon */
    .btn-eye-action {
        color: #00c0ef;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 2px 4px;
        transition: color 0.15s ease;
    }
    .btn-eye-action:hover {
        color: #0097bc;
    }
</style>

<div class="container-fluid p-3">
    <!-- Page Header & Breadcrumbs -->
    <div class="page-header-box">
        <h1 class="page-title-text">Log Data Peserta Baru dari Mobile JKN</h1>
        <div class="breadcrumb-custom">
            <a href="/admin">Beranda</a> &gt; <span>Log Data Peserta Baru dari Mobile JKN</span>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="table-container-box">
        <!-- Item Counter (e.g. Menampilkan 1-20 dari 9.605 item.) -->
        <div class="table-counter-text">
            Menampilkan <strong>{{ $firstItem }}-{{ $lastItem }}</strong> dari <strong>{{ number_format($displayTotal, 0, ',', '.') }}</strong> item.
        </div>

        <form method="GET" action="{{ url()->current() }}" id="filterHeaderForm">
            <div class="table-responsive">
                <table class="table-peserta-baru">
                    <thead>
                        <!-- Header Row 1: Column Titles -->
                        <tr>
                            <th style="width: 35px; text-align: center;">#</th>
                            <th style="width: 135px;">Nomorkartu</th>
                            <th style="width: 135px;">Nik</th>
                            <th style="width: 135px;">Nomorkk</th>
                            <th>Nama</th>
                            <th style="width: 90px; text-align: center;">Jeniskelamin</th>
                            <th style="width: 110px;">Tanggallahir</th>
                            <th>Alamat</th>
                            <th style="width: 35px; text-align: center;"><i class="far fa-eye text-info"></i></th>
                        </tr>
                        <!-- Header Row 2: In-Header Search Filters -->
                        <tr style="background-color: #fbfbfb;">
                            <th></th>
                            <th>
                                <input type="text" name="nomorkartu" class="input-header-filter" value="{{ request('nomorkartu') }}" onchange="document.getElementById('filterHeaderForm').submit()">
                            </th>
                            <th>
                                <input type="text" name="nik" class="input-header-filter" value="{{ request('nik') }}" onchange="document.getElementById('filterHeaderForm').submit()">
                            </th>
                            <th>
                                <input type="text" name="nomorkk" class="input-header-filter" value="{{ request('nomorkk') }}" onchange="document.getElementById('filterHeaderForm').submit()">
                            </th>
                            <th>
                                <input type="text" name="nama" class="input-header-filter" value="{{ request('nama') }}" onchange="document.getElementById('filterHeaderForm').submit()">
                            </th>
                            <th>
                                <input type="text" name="jeniskelamin" class="input-header-filter" value="{{ request('jeniskelamin') }}" onchange="document.getElementById('filterHeaderForm').submit()">
                            </th>
                            <th>
                                <input type="text" name="tanggallahir" class="input-header-filter" value="{{ request('tanggallahir') }}" onchange="document.getElementById('filterHeaderForm').submit()">
                            </th>
                            <th>
                                <input type="text" name="alamat" class="input-header-filter" value="{{ request('alamat') }}" onchange="document.getElementById('filterHeaderForm').submit()">
                            </th>
                            <th class="text-center">
                                <a href="{{ url()->current() }}" title="Reset Filter" style="color: #888; font-size: 11px;"><i class="fas fa-times"></i></a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $item)
                        <tr>
                            <!-- 1. Nomor -->
                            <td class="text-center">{{ $firstItem + $index }}</td>

                            <!-- 2. Nomorkartu -->
                            <td>{{ $item->no_kartu }}</td>

                            <!-- 3. Nik -->
                            <td>{{ $item->nik }}</td>

                            <!-- 4. Nomorkk -->
                            <td>
                                @if(!empty($item->nomorkk))
                                    {{ $item->nomorkk }}
                                @else
                                    <span class="text-belum-diset">(belum diset)</span>
                                @endif
                            </td>

                            <!-- 5. Nama -->
                            <td style="font-weight: 600;">
                                {{ $item->nama_pasien }}
                            </td>

                            <!-- 6. Jeniskelamin -->
                            <td class="text-center">{{ $item->jeniskelamin ?? 'L' }}</td>

                            <!-- 7. Tanggallahir -->
                            <td>{{ $item->tanggal_lahir ? (is_object($item->tanggal_lahir) ? $item->tanggal_lahir->format('Y-m-d') : substr($item->tanggal_lahir, 0, 10)) : '-' }}</td>

                            <!-- 8. Alamat -->
                            <td>
                                @if(!empty($item->alamat))
                                    {{ $item->alamat }}
                                @else
                                    <span class="text-belum-diset">(belum diset)</span>
                                @endif
                            </td>

                            <!-- 9. Aksi (Eye Button) -->
                            <td class="text-center">
                                <button type="button" class="btn-eye-action btnDetail" data-id="{{ $item->id }}" title="Detail Pasien Baru">
                                    <i class="far fa-eye" style="font-size: 14px;"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Tidak ada data peserta baru Mobile JKN yang cocok dengan filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">
                Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
            </div>
            <div>
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>

<!-- Modal Detail Peserta Baru Mobile JKN -->
<div class="modal fade" id="modalDetailPeserta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2" style="background-color: #337ab7; color: #ffffff;">
                <h6 class="modal-title fw-bold" style="font-size: 14px;">
                    <i class="fas fa-user-plus me-1"></i> Detail Peserta Baru Mobile JKN
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12.5px;">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th width="35%" class="text-muted">Nama Peserta</th>
                        <td width="65%" class="fw-bold fs-6 text-dark" id="mdlNama">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">No. Kartu BPJS</th>
                        <td class="fw-bold text-primary" id="mdlNoKartu">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">NIK</th>
                        <td id="mdlNik">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">No. KK</th>
                        <td id="mdlNoKk">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Jenis Kelamin</th>
                        <td id="mdlJk">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal Lahir</th>
                        <td id="mdlTglLahir">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Alamat</th>
                        <td id="mdlAlamat">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">No. HP</th>
                        <td id="mdlNoHp">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status Import</th>
                        <td id="mdlStatusImport">-</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer py-2 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-sm fw-bold" id="btnImportMaster">
                    <i class="fas fa-file-medical me-1"></i> Buatkan Rekam Medis
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('.btnDetail');
        const modalEl = document.getElementById('modalDetailPeserta');
        const modal = new bootstrap.Modal(modalEl);
        let currentId = null;

        detailButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                currentId = this.getAttribute('data-id');

                fetch(`/bridging-log-pasien-baru/${currentId}/detail`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data) {
                            const d = res.data;
                            document.getElementById('mdlNama').textContent = d.nama_pasien || '-';
                            document.getElementById('mdlNoKartu').textContent = d.no_kartu || '-';
                            document.getElementById('mdlNik').textContent = d.nik || '-';
                            document.getElementById('mdlNoKk').textContent = d.nomorkk || '(belum diset)';
                            document.getElementById('mdlJk').textContent = (d.jeniskelamin === 'L' ? 'Laki-Laki' : 'Perempuan');
                            document.getElementById('mdlTglLahir').textContent = d.tanggal_lahir ? d.tanggal_lahir.substring(0, 10) : '-';
                            document.getElementById('mdlAlamat').textContent = d.alamat || '(belum diset)';
                            document.getElementById('mdlNoHp').textContent = d.no_hp || '-';
                            document.getElementById('mdlStatusImport').innerHTML = d.status_import 
                                ? '<span class="badge bg-success">Sudah di Master Pasien</span>' 
                                : '<span class="badge bg-warning text-dark">Belum Diimport</span>';

                            modal.show();
                        }
                    })
                    .catch(err => console.log(err));
            });
        });

        const btnImport = document.getElementById('btnImportMaster');
        if (btnImport) {
            btnImport.addEventListener('click', function () {
                if (!currentId) return;

                btnImport.disabled = true;
                btnImport.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';

                fetch(`/bridging-log-pasien-baru/${currentId}/import`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                    window.location.reload();
                })
                .catch(err => {
                    alert('Gagal memproses import data pasien.');
                    btnImport.disabled = false;
                    btnImport.innerHTML = '<i class="fas fa-file-medical me-1"></i> Buatkan Rekam Medis';
                });
            });
        }
    });
</script>
@endsection
