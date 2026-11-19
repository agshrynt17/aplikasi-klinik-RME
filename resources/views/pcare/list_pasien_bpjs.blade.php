@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page {
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

    /* Action bar */
    .action-bar-pasien {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 10px;
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
    }
    .btn-orange-cari:hover { background-color: #d68407; }

    .btn-blue-tambah {
        background-color: #3c8dbc;
        border: 1px solid #367fa9;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .btn-blue-tambah:hover { background-color: #367fa9; }

    .btn-cyan-cetak {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .btn-cyan-cetak:hover { background-color: #00acd6; }

    .konhakes-summary-text {
        font-size: 13px;
        color: #333;
        margin-bottom: 8px;
    }

    /* Table Grid */
    .table-pasien-grid {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 12.5px;
        margin-bottom: 15px;
    }
    .table-pasien-grid th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 8px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
        color: #333;
    }
    .table-pasien-grid th.col-sort a {
        color: #3c8dbc !important;
        text-decoration: none;
    }
    .table-pasien-grid th.col-sort a:hover { text-decoration: underline; }
    .table-pasien-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 7px 8px;
        vertical-align: middle;
        color: #333;
    }
    .table-pasien-grid tbody tr:nth-of-type(odd) td  { background-color: #fff; }
    .table-pasien-grid tbody tr:nth-of-type(even) td { background-color: #f9f9f9; }
    .table-pasien-grid tbody tr:hover td             { background-color: #f5f5f5; }

    .rm-link {
        color: #3c8dbc;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
    }
    .rm-link:hover {
        color: #23527c;
        text-decoration: underline;
    }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Data Pasien BPJS</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-pasien/index">Data Pasien BPJS</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2 shadow-sm" style="border-radius:3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small my-2 shadow-sm" style="border-radius:3px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Top Action Buttons --}}
    <div class="action-bar-pasien">
        <div style="display:flex; gap:6px; flex-wrap:wrap;">
            <button type="button" class="btn-orange-cari" data-bs-toggle="collapse" data-bs-target="#collapseFilter">
                <i class="fas fa-search"></i> Cari data
            </button>
            <button type="button" class="btn-blue-tambah" data-bs-toggle="modal" data-bs-target="#modalTambahPasienPcare">
                <i class="fas fa-plus"></i> Tambah Pasien Dari PCare
            </button>
        </div>
        <div style="display:flex; gap:6px; flex-wrap:wrap;">
            <a href="/pcare-pasien/export-pdf" target="_blank" class="btn-cyan-cetak">
                <i class="fas fa-file-pdf"></i> Cetak Laporan(pdf)
            </a>
            <a href="/pcare-pasien/export-excel" class="btn-cyan-cetak">
                <i class="fas fa-file-excel"></i> Cetak Laporan (xls)
            </a>
        </div>
    </div>

    {{-- Filter Search Collapse --}}
    <div class="collapse {{ request()->hasAny(['q', 'no_rm', 'no_bpjs', 'nama_pasien']) ? 'show' : '' }} mb-3" id="collapseFilter">
        <div class="card card-body p-3 bg-light border-0 shadow-sm" style="border-radius:3px;">
            <form method="GET" action="/pcare-pasien/index">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1">Cari Kata Kunci</label>
                        <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}" placeholder="No RM / KIS / Nama / NIK">
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted mb-1">No RM</label>
                        <input type="text" name="no_rm" class="form-control form-control-sm" value="{{ request('no_rm') }}" placeholder="2024-...">
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted mb-1">Nomor KIS</label>
                        <input type="text" name="no_bpjs" class="form-control form-control-sm" value="{{ request('no_bpjs') }}" placeholder="000...">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1">Nama Pasien</label>
                        <input type="text" name="nama_pasien" class="form-control form-control-sm" value="{{ request('nama_pasien') }}" placeholder="Nama...">
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill" style="background:#3c8dbc; border-color:#367fa9;">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                        <a href="/pcare-pasien/index" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>{{ $pasiens->firstItem() ?? 1 }}-{{ $pasiens->lastItem() ?? count($pasiens) }}</b> dari <b>{{ number_format($total, 0, ',', '.') }}</b> item.
    </div>

    {{-- Tabel Pasien BPJS --}}
    <div class="table-responsive" style="border:none;">
        <table class="table-pasien-grid">
            <thead>
                <tr>
                    <th style="width:35px; text-align:center;">#</th>
                    <th class="col-sort" style="width:130px;">
                        <a href="/pcare-pasien/index?sort=no_rm&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                            Kode Rekam Medis <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort" style="width:130px;">
                        <a href="/pcare-pasien/index?sort=no_bpjs&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                            Nomor KIS <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort">
                        <a href="/pcare-pasien/index?sort=nama_pasien&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                            Nama Pasien <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th style="width:100px;">Faskes BPJS</th>
                    <th class="col-sort" style="width:105px;">
                        <a href="/pcare-pasien/index?sort=tanggal_lahir&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                            Tanggal Lahir <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th class="col-sort" style="width:90px;">
                        <a href="/pcare-pasien/index?sort=jenis_kelamin&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                            J/K <i class="fas fa-sort ms-1" style="font-size:10px;"></i>
                        </a>
                    </th>
                    <th style="width:115px;">No. Handphone</th>
                    <th style="width:220px;">Alamat Sesuai Identitas</th>
                    <th style="width:160px;">Nama Ibu Kandung</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $index => $item)
                <tr>
                    <td style="color:#777; text-align:center;">{{ $pasiens->firstItem() + $index }}</td>
                    <td>
                        <a href="/pasien/view?id={{ $item->no_rm }}" class="rm-link" title="Lihat Rekam Medis {{ $item->no_rm }}">
                            <i class="far fa-newspaper text-muted" style="font-size:11px;"></i> {{ $item->no_rm }}
                        </a>
                    </td>
                    <td>{{ $item->no_bpjs }}</td>
                    <td style="font-weight:500;">{{ $item->nama_pasien }}</td>
                    <td>{{ $item->nama_faskes_bpjs ?? '' }}</td>
                    <td style="white-space:nowrap;">
                        {{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d.M.Y') : '-' }}
                    </td>
                    <td>
                        {{ $item->jenis_kelamin == 'P' || strtolower($item->jenis_kelamin) == 'perempuan' ? 'Perempuan' : 'Laki-Laki' }}
                    </td>
                    <td>{{ $item->no_hp ?? '-' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->alamat, 28) }}</td>
                    <td>{{ $item->nama_ibu_kandung ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle me-1"></i> Tidak ada data pasien BPJS yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pasiens->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="small text-muted">
            Halaman {{ $pasiens->currentPage() }} dari {{ $pasiens->lastPage() }}
        </div>
        <div>
            {{ $pasiens->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Modal Tambah Pasien Dari PCare --}}
<div class="modal fade" id="modalTambahPasienPcare" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
        <div class="modal-content" style="border-radius: 4px; border: 1px solid rgba(0,0,0,.2); box-shadow: 0 3px 9px rgba(0,0,0,.5);">
            <div class="modal-header" style="padding: 12px 15px; border-bottom: 1px solid #e5e5e5; background: #fff;">
                <h5 class="modal-title" style="font-size: 15px; font-weight: 600; color: #333; margin: 0;">Cari Peserta Dari PCare</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
            </div>
            <div class="modal-body" style="padding: 15px 20px;">
                <form id="formCariPesertaPcare" onsubmit="event.preventDefault(); prosesCariPesertaPcare();">
                    <div class="mb-3">
                        <label style="font-size: 13px; font-weight: 700; color: #333; margin-bottom: 6px; display: block;">Pilih Jenis Pencarian</label>
                        <div style="display: flex; gap: 20px; align-items: center;">
                            <label style="font-size: 13px; font-weight: normal; color: #333; cursor: pointer; display: flex; align-items: center; gap: 5px; margin: 0;">
                                <input type="radio" name="jenisPencarian" value="nik" id="radioNik">
                                NO. NIK/KTP
                            </label>
                            <label style="font-size: 13px; font-weight: normal; color: #333; cursor: pointer; display: flex; align-items: center; gap: 5px; margin: 0;">
                                <input type="radio" name="jenisPencarian" value="kis" id="radioKis" checked>
                                NO KIS
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 13px; font-weight: 700; color: #333; margin-bottom: 6px; display: block;">Masukan Nomor Sesuai Jenis Pencarian</label>
                        <input type="text" id="inputNomorIdentitas" class="form-control"
                               placeholder="Ketik Nomor Identitas"
                               style="height: 34px; font-size: 13px; border: 1px solid #d2d6de; border-radius: 2px; padding: 6px 12px;">
                        <div id="pesanErrorPcare" class="text-danger small mt-2" style="display: none;"></div>
                    </div>

                    <div style="display: flex; gap: 6px; align-items: center; margin-top: 15px;">
                        <button type="submit" id="btnSubmitCariPcare" class="btn btn-primary"
                                style="background-color: #00c0ef; border-color: #00acd6; color: #fff; font-size: 12px; padding: 5px 14px; border-radius: 3px; font-weight: 500;">
                            <i class="fas fa-check me-1"></i> OK! Cari
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal"
                                style="background-color: #f39c12; border-color: #e08e0b; color: #fff; font-size: 12px; padding: 5px 14px; border-radius: 3px; font-weight: 500;">
                            <i class="fas fa-times me-1"></i> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function prosesCariPesertaPcare() {
    var jenis = document.querySelector('input[name="jenisPencarian"]:checked').value;
    var nomor = document.getElementById('inputNomorIdentitas').value.trim();
    var errDiv = document.getElementById('pesanErrorPcare');
    errDiv.style.display = 'none';

    if (!nomor) {
        errDiv.textContent = 'Silakan ketik nomor identitas terlebih dahulu.';
        errDiv.style.display = 'block';
        return;
    }

    var btn = document.getElementById('btnSubmitCariPcare');
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mencari...';

    fetch('/pasien/pcare-check', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ jenis: jenis, nomor: nomor })
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = origText;

        if (res.success && res.data) {
            var p = res.data;
            if (res.source === 'lokal') {
                if (confirm('Pasien sudah terdaftar di sistem dengan No. RM: ' + (p.no_rm || '') + ' (' + (p.nama_pasien || '') + ').\n\nBuka data pasien?')) {
                    window.location.href = '/pasien/' + p.id + '/edit';
                }
            } else {
                var url = '/tambah-pasien?from_pcare=1'
                    + '&nik=' + encodeURIComponent(p.nik || '')
                    + '&no_bpjs=' + encodeURIComponent(p.no_bpjs || '')
                    + '&nama_pasien=' + encodeURIComponent(p.nama_pasien || '')
                    + '&jenis_kelamin=' + encodeURIComponent(p.jenis_kelamin || 'L')
                    + '&tanggal_lahir=' + encodeURIComponent(p.tanggal_lahir || '')
                    + '&alamat=' + encodeURIComponent(p.alamat || '')
                    + '&no_hp=' + encodeURIComponent(p.no_hp || '');
                window.location.href = url;
            }
        } else {
            errDiv.textContent = res.message || 'Peserta tidak ditemukan.';
            errDiv.style.display = 'block';
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = origText;
        errDiv.textContent = 'Terjadi kesalahan saat memeriksa peserta ke server.';
        errDiv.style.display = 'block';
    });
}
</script>
@endsection
