@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .btn-orange { background-color: #f39c12; border-color: #e08e0b; color: #fff; }
    .btn-orange:hover { background-color: #d68910; border-color: #c77e0c; color: #fff; }
    .btn-green { background-color: #00a65a; border-color: #008d4c; color: #fff; }
    .btn-green:hover { background-color: #008d4c; border-color: #00733e; color: #fff; }
    .btn-cyan { background-color: #00c0ef; border-color: #00acd6; color: #fff; }
    .btn-cyan:hover { background-color: #00acd6; border-color: #0097bc; color: #fff; }
    .btn-navy { background-color: #3c8dbc; border-color: #367fa9; color: #fff; }
    .btn-navy:hover { background-color: #367fa9; border-color: #2e6688; color: #fff; }
    
    .table-pasien { font-size: 12.5px; }
    .table-pasien thead th {
        background-color: #f9fafb;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-pasien tbody td {
        vertical-align: middle;
        padding: 8px 10px;
    }
    .badge-aktif {
        background-color: #00a65a;
        color: #fff;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 11px;
    }
    .badge-nonaktif {
        background-color: #dd4b39;
        color: #fff;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 11px;
    }
    .kode-rm-link {
        color: #0073b7;
        font-weight: 600;
        text-decoration: none;
    }
    .kode-rm-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    .filter-card {
        background-color: #ffffff;
        border: 1px solid #e3e6f0;
        border-top: 3px solid #f39c12;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
    }
    .card-pasien-box {
        border-radius: 4px;
        border: 1px solid #e3e6f0;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>

<!-- Header & Breadcrumbs (Sesuai PROFASKES) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Data Pasien</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Pasien</li>
        </ol>
    </nav>
</div>

<!-- Alert Notifikasi -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div id="alertBoxAjax" class="alert d-none shadow-sm" role="alert"></div>

<!-- Action Bar (Sesuai Screenshot 1) -->
<div class="card card-pasien-box p-3 mb-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <!-- Tombol Kiri -->
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-orange btn-sm px-3 fw-semibold shadow-sm" type="button" id="btnToggleFilter" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="{{ request()->hasAny(['kode_rm', 'nama_pasien', 'nik', 'no_bpjs', 'no_hp', 'alamat', 'tanggal_lahir', 'jenis_kelamin']) ? 'true' : 'false' }}">
                <i class="fas fa-search me-1"></i> Pencarian
            </button>
            <a href="/tambah-pasien" class="btn btn-green btn-sm px-3 fw-semibold shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah Pasien
            </a>
            <a href="/pasien/index-search-ibu" class="btn btn-cyan btn-sm px-3 fw-semibold shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah Bayi Baru Lahir
            </a>
            <button class="btn btn-cyan btn-sm px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCariPcare">
                <i class="fas fa-plus me-1"></i> Daftarkan Pasien Dari Pcare
            </button>
        </div>

        <!-- Tombol Kanan -->
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-navy btn-sm px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportCsv">
                <i class="fas fa-file-import me-1"></i> Import Data Dari CSV
            </button>
            <a href="/pasien/export-csv" class="btn btn-cyan btn-sm px-3 fw-semibold shadow-sm">
                <i class="fas fa-download me-1"></i> Download Data
            </a>
        </div>
    </div>

    <!-- Panel Filter Pencarian Collapsible (Sesuai Screenshot 2 Filter Form) -->
    <div class="collapse {{ request()->hasAny(['kode_rm', 'nama_pasien', 'nik', 'no_bpjs', 'no_hp', 'alamat', 'tanggal_lahir', 'jenis_kelamin', 'q']) ? 'show' : '' }} mt-3" id="collapseFilter">
        <div class="filter-card p-3">
            <h6 class="fw-bold mb-3 text-secondary"><i class="fas fa-filter me-1"></i> Filter Pencarian Data Pasien</h6>
            <form method="GET" action="/master-pasien" id="formFilter">
                <div class="row g-2">
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Kode RM</label>
                        <input type="text" name="kode_rm" class="form-control form-control-sm" placeholder="Kode RM..." value="{{ request('kode_rm') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Nama Pasien</label>
                        <input type="text" name="nama_pasien" class="form-control form-control-sm" placeholder="Nama Pasien..." value="{{ request('nama_pasien') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control form-control-sm" value="{{ request('tanggal_lahir') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Nomor NIK</label>
                        <input type="text" name="nik" class="form-control form-control-sm" placeholder="NIK..." value="{{ request('nik') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Nomor KIS</label>
                        <input type="text" name="no_bpjs" class="form-control form-control-sm" placeholder="No KIS..." value="{{ request('no_bpjs') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">J.Kelamin</label>
                        <select name="jenis_kelamin" class="form-select form-select-sm">
                            <option value="">-- Semua --</option>
                            <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label small fw-bold mb-1">No. Handphone</label>
                        <input type="text" name="no_hp" class="form-control form-control-sm" placeholder="No HP..." value="{{ request('no_hp') }}">
                    </div>
                    <div class="col-md-5 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Alamat Identitas</label>
                        <input type="text" name="alamat" class="form-control form-control-sm" placeholder="Alamat..." value="{{ request('alamat') }}">
                    </div>
                    <div class="col-md-4 col-sm-12 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                        <a href="/master-pasien" class="btn btn-sm btn-light border px-3 text-secondary shadow-sm">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card card-pasien-box p-3">
    <!-- Info Bar Sesuai Screenshot 1 -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="text-secondary small">
            Menampilkan <strong>{{ $pasiens->firstItem() ?? 0 }}-{{ $pasiens->lastItem() ?? 0 }}</strong> dari <strong>{{ number_format($pasiens->total(), 0, ',', '.') }}</strong> item.
        </div>
        <div class="small text-muted">
            Total Halaman: {{ $pasiens->lastPage() }}
        </div>
    </div>

    <!-- Table Pasien (Sesuai Kolom Screenshot 1) -->
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered table-pasien align-middle mb-0" id="tabelDataPasien">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th width="11%">Kode RM</th>
                    <th width="18%">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_pasien', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Nama Pasien <i class="fas fa-sort text-muted"></i>
                        </a>
                    </th>
                    <th width="14%">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'tanggal_lahir', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Tgl.Lahir &amp; Usia <i class="fas fa-sort text-muted"></i>
                        </a>
                    </th>
                    <th width="4%" class="text-center">J/K</th>
                    <th width="15%">NIK &amp; KIS</th>
                    <th width="14%">Alamat &amp; HP</th>
                    <th width="8%">Identitas Tambahan</th>
                    <th width="10%">Ket. Lain</th>
                    <th width="6%" class="text-center">Status</th>
                    <th width="5%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $index => $item)
                <tr id="row-pasien-{{ $item->id }}">
                    <td class="text-center fw-semibold text-secondary">
                        {{ $pasiens->firstItem() + $index }}
                    </td>
                    <td>
                        <a href="/tambah-pasien?edit={{ $item->id }}" class="kode-rm-link">
                            <i class="far fa-file-alt text-primary me-1"></i> {{ $item->no_rm }}
                        </a>
                        @if($item->kode_rm_ibu)
                            <br><small class="badge bg-light text-muted border">Ibu: {{ $item->kode_rm_ibu }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-dark text-uppercase">{{ $item->nama_pasien }}</div>
                        @if($item->nama_ibu_kandung)
                            <small class="text-muted" style="font-size: 11px;">Ibu: {{ $item->nama_ibu_kandung }}</small>
                        @endif
                    </td>
                    <td>
                        <div>{{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-' }}</div>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ $item->usia_lengkap }}
                        </small>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $item->jenis_kelamin == 'L' ? 'bg-primary' : 'bg-pink text-white' }}" style="{{ $item->jenis_kelamin == 'P' ? 'background-color:#e83e8c;' : '' }}">
                            {{ $item->jenis_kelamin }}
                        </span>
                    </td>
                    <td>
                        <div class="text-nowrap" style="font-family: monospace; font-size: 11.5px;">
                            <strong>NIK:</strong> {{ $item->nik ?? '-' }}
                        </div>
                        <div class="text-nowrap text-secondary" style="font-family: monospace; font-size: 11.5px;">
                            <strong>KIS:</strong> {{ $item->no_bpjs ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 180px;" title="{{ $item->alamat }}">
                            {{ $item->alamat ?? '-' }}
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-phone-alt fa-xs me-1 text-secondary"></i> {{ $item->no_hp ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $item->nomor_identitas_lain ?: '-' }}
                        </small>
                    </td>
                    <td>
                        @if($item->kode_ihs_satusehat)
                            <div style="font-size: 11px;">
                                <strong class="text-secondary">ID Satu Sehat:</strong><br>
                                <span class="badge bg-light text-dark border">{{ $item->kode_ihs_satusehat }}</span>
                            </div>
                        @else
                            <small class="text-muted">-</small>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->is_active)
                            <span class="badge-aktif">Aktif</span>
                        @else
                            <span class="badge-nonaktif">Non Aktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="/tambah-pasien?edit={{ $item->id }}" class="btn btn-sm btn-outline-primary py-0 px-1" title="Edit Pasien">
                                <i class="fas fa-pencil-alt fa-xs"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 btnHapusPasien" data-id="{{ $item->id }}" data-nama="{{ $item->nama_pasien }}" title="Hapus Pasien">
                                <i class="fas fa-trash fa-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-5 text-muted">
                        <i class="fas fa-user-injured fa-3x mb-3 text-secondary d-block"></i>
                        Tidak ada data pasien yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
        <div class="text-muted small">
            Menampilkan data {{ $pasiens->firstItem() ?? 0 }} sampai {{ $pasiens->lastItem() ?? 0 }} dari total {{ $pasiens->total() }} pasien.
        </div>
        <div>
            {{ $pasiens->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Cari Peserta Dari PCare (Sesuai Screenshot 4) -->
<!-- ======================================================== -->
<div class="modal fade" id="modalCariPcare" tabindex="-1" aria-labelledby="modalCariPcareLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header border-bottom py-2">
                <h5 class="modal-title fw-bold text-dark" id="modalCariPcareLabel" style="font-size: 1.1rem;">
                    Cari Peserta Dari PCare
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark small mb-2 d-block">Pilih Jenis Pencarian</label>
                    <div class="form-check form-check-inline me-4">
                        <input class="form-check-input" type="radio" name="pcare_jenis" id="pcareJenisNik" value="nik">
                        <label class="form-check-label fw-semibold" for="pcareJenisNik">NO. NIK/KTP</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="pcare_jenis" id="pcareJenisKis" value="kis" checked>
                        <label class="form-check-label fw-semibold" for="pcareJenisKis">NO KIS</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">Masukan Nomor Sesuai Jenis Pencarian</label>
                    <input type="text" id="pcareNomorInput" class="form-control" placeholder="Ketik Nomor Identitas">
                    <div id="pcareFeedback" class="small mt-2 text-danger d-none"></div>
                </div>

                <div id="pcareLoading" class="text-center py-2 d-none">
                    <div class="spinner-border spinner-border-sm text-cyan" role="status"></div>
                    <span class="small text-muted ms-2">Menghubungkan ke PCare BPJS...</span>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-3 px-4 d-flex justify-content-start gap-2">
                <button type="button" class="btn btn-cyan px-4 fw-semibold" id="btnSubmitPcare">
                    <i class="fas fa-check me-1"></i> OK! Cari
                </button>
                <button type="button" class="btn btn-orange px-4 fw-semibold text-white" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Import CSV -->
<!-- ======================================================== -->
<div class="modal fade" id="modalImportCsv" tabindex="-1" aria-labelledby="modalImportCsvLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-navy text-white py-2">
                <h5 class="modal-title fw-bold" id="modalImportCsvLabel" style="font-size: 1.1rem;">
                    <i class="fas fa-file-import me-2"></i> Import Data Pasien Dari CSV
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/pasien/import-csv" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">
                        Format CSV harus memiliki urutan kolom: <br>
                        <code>No RM, Nama Pasien, NIK, No KIS, Jenis Kelamin (L/P), Tempat Lahir, Tgl Lahir (YYYY-MM-DD), No HP, Alamat</code>
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Pilih File CSV (.csv / .txt)</label>
                        <input type="file" name="file_csv" class="form-control" accept=".csv, .txt" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-navy btn-sm px-3 fw-bold">
                        <i class="fas fa-upload me-1"></i> Upload &amp; Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Hapus Pasien Confirmation -->
<!-- ======================================================== -->
<div class="modal fade" id="modalHapusPasien" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold"><i class="fas fa-trash me-2"></i> Konfirmasi Hapus</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 text-secondary small">
                Yakin ingin menghapus data pasien <strong id="hapusNamaPasien" class="text-dark"></strong>? Data yang dihapus tidak dapat dipulihkan.
            </div>
            <div class="modal-footer border-top-0 pt-0 p-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm px-3 fw-semibold" id="btnKonfirmasiHapus">
                    <i class="fas fa-trash me-1"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let targetHapusId = null;

    // Handle PCare Check
    $('#btnSubmitPcare').click(function() {
        let jenis = $('input[name="pcare_jenis"]:checked').val();
        let nomor = $('#pcareNomorInput').val().trim();
        let feedback = $('#pcareFeedback');
        let loader = $('#pcareLoading');

        if (!nomor) {
            feedback.removeClass('d-none').text('Harap masukkan nomor identitas!');
            return;
        }

        feedback.addClass('d-none');
        loader.removeClass('d-none');
        $(this).prop('disabled', true);

        $.ajax({
            url: '/pasien/pcare-check',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                jenis: jenis,
                nomor: nomor
            },
            success: function(res) {
                loader.addClass('d-none');
                $('#btnSubmitPcare').prop('disabled', false);

                if (res.source === 'lokal') {
                    if (confirm('Pasien ditemukan di database lokal dengan RM ' + res.data.no_rm + ' (' + res.data.nama_pasien + '). Buka halaman data pasien ini?')) {
                        window.location.href = '/tambah-pasien?edit=' + res.data.id;
                    }
                } else if (res.source === 'pcare') {
                    let d = res.data;
                    let targetUrl = '/tambah-pasien?from_pcare=1' +
                        '&nik=' + encodeURIComponent(d.nik) +
                        '&no_bpjs=' + encodeURIComponent(d.no_bpjs) +
                        '&nama_pasien=' + encodeURIComponent(d.nama_pasien) +
                        '&jenis_kelamin=' + encodeURIComponent(d.jenis_kelamin) +
                        '&tanggal_lahir=' + encodeURIComponent(d.tanggal_lahir) +
                        '&alamat=' + encodeURIComponent(d.alamat) +
                        '&no_hp=' + encodeURIComponent(d.no_hp);
                    
                    window.location.href = targetUrl;
                }
            },
            error: function(xhr) {
                loader.addClass('d-none');
                $('#btnSubmitPcare').prop('disabled', false);
                feedback.removeClass('d-none').text(xhr.responseJSON?.message || 'Gagal mencari data PCare.');
            }
        });
    });

    // Handle Delete
    $(document).on('click', '.btnHapusPasien', function() {
        targetHapusId = $(this).data('id');
        let nama = $(this).data('nama');
        $('#hapusNamaPasien').text(nama);
        $('#modalHapusPasien').modal('show');
    });

    $('#btnKonfirmasiHapus').click(function() {
        if (!targetHapusId) return;
        let btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: '/master-pasien/' + targetHapusId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                $('#modalHapusPasien').modal('hide');
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Ya, Hapus');
                $('#row-pasien-' + targetHapusId).fadeOut(400, function() {
                    $(this).remove();
                });
                $('#alertBoxAjax')
                    .removeClass('d-none alert-danger')
                    .addClass('alert-success')
                    .html('<i class="fas fa-check-circle me-1"></i> ' + res.message)
                    .show();
                setTimeout(() => $('#alertBoxAjax').addClass('d-none'), 3500);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Ya, Hapus');
                alert(xhr.responseJSON?.message || 'Gagal menghapus data pasien.');
            }
        });
    });
});
</script>
@endsection
