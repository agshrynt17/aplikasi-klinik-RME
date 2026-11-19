@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .btn-orange { background-color: #f39c12; border-color: #e08e0b; color: #fff; }
    .btn-orange:hover { background-color: #d68910; border-color: #c77e0c; color: #fff; }
    .btn-green { background-color: #00a65a; border-color: #008d4c; color: #fff; }
    .btn-green:hover { background-color: #008d4c; border-color: #00733e; color: #fff; }
    
    .table-ibu { font-size: 12.5px; }
    .table-ibu thead th {
        background-color: #f9fafb;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-ibu tbody td {
        vertical-align: middle;
        padding: 8px 10px;
    }
    .filter-box-ibu {
        border-radius: 4px;
        border: 1px solid #e3e6f0;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>

<!-- Header Area Sesuai Screenshot 2 -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Pilih Data Ibu Dari Bayi</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Pilih Data Ibu Dari Bayi</a></li>
        </ol>
    </nav>
</div>

<!-- Card Filter Pencarian (Sesuai Screenshot 2) -->
<div class="card filter-box-ibu p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h6 class="fw-bold mb-0 text-secondary">
            Filter Pencarian
        </h6>
        <div class="d-flex gap-2">
            <a href="/tambah-pasien" class="btn btn-green btn-sm px-3 fw-semibold shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah Baru
            </a>
            <a href="/master-pasien" class="btn btn-orange btn-sm px-3 fw-semibold shadow-sm text-white">
                Tutup
            </a>
        </div>
    </div>

    <form method="GET" action="/pasien/index-search-ibu">
        <div class="row g-2 mb-2">
            <div class="col-md-2 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1">Kode RM</label>
                <input type="text" name="kode_rm" class="form-control form-control-sm" value="{{ request('kode_rm') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1">Nama Pasien</label>
                <input type="text" name="nama_pasien" class="form-control form-control-sm" value="{{ request('nama_pasien') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control form-control-sm" value="{{ request('tanggal_lahir') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1">Nomor NIK</label>
                <input type="text" name="nik" class="form-control form-control-sm" value="{{ request('nik') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1">Nomor KIS</label>
                <input type="text" name="no_bpjs" class="form-control form-control-sm" value="{{ request('no_bpjs') }}">
            </div>
        </div>

        <div class="row g-2 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1">No. Handphone</label>
                <input type="text" name="no_hp" class="form-control form-control-sm" value="{{ request('no_hp') }}">
            </div>
            <div class="col-md-7 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1">Alamat Identitas</label>
                <input type="text" name="alamat" class="form-control form-control-sm" value="{{ request('alamat') }}">
            </div>
            <div class="col-md-2 col-sm-12 d-flex gap-1 justify-content-end">
                <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm" title="Cari">
                    <i class="fas fa-search"></i>
                </button>
                <a href="/pasien/index-search-ibu" class="btn btn-sm btn-light border px-3 text-secondary shadow-sm" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Table Ibu Card -->
<div class="card filter-box-ibu p-3">
    <!-- Info Bar Sesuai Screenshot 2 -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="text-secondary small">
            Menampilkan <strong>{{ $ibus->firstItem() ?? 0 }}-{{ $ibus->lastItem() ?? 0 }}</strong> dari <strong>{{ number_format($ibus->total(), 0, ',', '.') }}</strong> item.
        </div>
        <div class="small text-muted">
            Halaman {{ $ibus->currentPage() }} dari {{ $ibus->lastPage() }}
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered table-ibu align-middle mb-0">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th width="10%" class="text-center">Aksi</th>
                    <th width="11%">Kode RM</th>
                    <th width="18%">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_pasien', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Nama Pasien <i class="fas fa-sort text-muted"></i>
                        </a>
                    </th>
                    <th width="10%">Tanggal Lahir</th>
                    <th width="4%" class="text-center">J/K</th>
                    <th width="17%">NIK &amp; KIS</th>
                    <th width="12%">No. HP</th>
                    <th width="15%">Alamat Identitas</th>
                    <th width="10%">Informasi Lain</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ibus as $index => $ibu)
                <tr>
                    <td class="text-center fw-semibold text-secondary">
                        {{ $ibus->firstItem() + $index }}
                    </td>
                    <td class="text-center">
                        <div class="d-inline-flex align-items-center gap-1">
                            <a href="/pasien/create?kode_rm_ibus={{ urlencode($ibu->no_rm) }}" class="btn btn-orange btn-sm py-0 px-2 fw-semibold text-white shadow-sm" style="font-size: 11px;">
                                <i class="fas fa-check me-1"></i> Pilih
                            </a>
                            <a href="/tambah-pasien?edit={{ $ibu->id }}" class="text-secondary p-1" title="Edit Pasien">
                                <i class="fas fa-pencil-alt fa-xs"></i>
                            </a>
                        </div>
                    </td>
                    <td class="fw-semibold text-primary">
                        {{ $ibu->no_rm }}
                    </td>
                    <td class="fw-bold text-dark text-uppercase">
                        {{ $ibu->nama_pasien }}
                    </td>
                    <td>
                        {{ $ibu->tanggal_lahir ? $ibu->tanggal_lahir->format('d/m/Y') : '-' }}
                    </td>
                    <td class="text-center">
                        <span class="badge" style="background-color: #e83e8c; color: white;">P</span>
                    </td>
                    <td>
                        <div class="text-nowrap" style="font-family: monospace; font-size: 11.5px;">
                            <strong>NIK:</strong> {{ $ibu->nik ?? '-' }}
                        </div>
                        <div class="text-nowrap text-secondary" style="font-family: monospace; font-size: 11.5px;">
                            <strong>KIS:</strong> {{ $ibu->no_bpjs ?? '-' }}
                        </div>
                    </td>
                    <td>
                        {{ $ibu->no_hp ?? '-' }}
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 200px;" title="{{ $ibu->alamat }}">
                            {{ $ibu->alamat ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <small class="text-muted">{{ $ibu->catatan_khusus ?: '-' }}</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">
                        <i class="fas fa-female fa-3x mb-3 text-secondary d-block"></i>
                        Tidak ada data calon ibu yang cocok dengan filter pencarian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
        <div class="text-muted small">
            Menampilkan data {{ $ibus->firstItem() ?? 0 }} sampai {{ $ibus->lastItem() ?? 0 }} dari total {{ $ibus->total() }} calon ibu.
        </div>
        <div>
            {{ $ibus->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
