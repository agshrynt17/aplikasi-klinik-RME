@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .btn-orange { background-color: #f39c12; border-color: #e08e0b; color: #fff; }
    .btn-orange:hover { background-color: #d68910; border-color: #c77e0c; color: #fff; }
    .btn-green { background-color: #00a65a; border-color: #008d4c; color: #fff; }
    .btn-green:hover { background-color: #008d4c; border-color: #00733e; color: #fff; }
    .btn-cyan { background-color: #00c0ef; border-color: #00acd6; color: #fff; }
    .btn-cyan:hover { background-color: #00acd6; border-color: #0097bc; color: #fff; }

    .table-pendaftaran { font-size: 12px; }
    .table-pendaftaran thead th {
        background-color: #f9fafb;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        vertical-align: middle;
        white-space: nowrap;
        padding: 9px 8px;
    }
    .table-pendaftaran tbody td {
        vertical-align: middle;
        padding: 8px;
    }

    .badge-status-proses {
        background-color: #f39c12;
        color: #fff;
        font-weight: 500;
        padding: 3px 6px;
        border-radius: 3px;
        font-size: 10.5px;
    }
    .badge-status-selesai {
        background-color: #00c0ef;
        color: #fff;
        font-weight: 500;
        padding: 3px 6px;
        border-radius: 3px;
        font-size: 10.5px;
    }
    .badge-status-batal {
        background-color: #dd4b39;
        color: #fff;
        font-weight: 500;
        padding: 3px 6px;
        border-radius: 3px;
        font-size: 10.5px;
    }

    .btn-skrining {
        color: #00a65a;
        font-weight: 600;
        text-decoration: none;
        display: block;
        font-size: 11px;
    }
    .btn-skrining:hover { color: #00733e; text-decoration: underline; }
    
    .btn-detail-link {
        color: #0073b7;
        font-weight: 600;
        text-decoration: none;
        display: block;
        font-size: 11px;
    }
    .btn-detail-link:hover { color: #0056b3; text-decoration: underline; }

    .card-box-rajal {
        border-radius: 4px;
        border: 1px solid #e3e6f0;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .filter-card-rajal {
        background: #fff;
        border: 1px solid #e3e6f0;
        border-top: 3px solid #f39c12;
        border-radius: 4px;
    }
</style>

<!-- Header & Breadcrumb Sesuai Screenshot 1 -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Pendaftaran</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Pendaftaran</a></li>
        </ol>
    </nav>
</div>

<!-- Alert Notifikasi -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div id="alertBoxRajal" class="alert d-none shadow-sm" role="alert"></div>

<!-- Top Action Bar Sesuai Screenshot 1 -->
<div class="card card-box-rajal p-3 mb-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <!-- Tombol Kiri -->
        <div class="d-flex flex-wrap align-items-center gap-2">
            <button class="btn btn-orange btn-sm px-3 fw-semibold shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilterRajal" aria-expanded="{{ request()->hasAny(['nomor_registrasi', 'no_rm', 'nama_pasien', 'poliklinik_id', 'penjamin_id', 'status_periksa', 'tanggal', 'q']) ? 'true' : 'false' }}">
                <i class="fas fa-filter me-1"></i> Filter Lengkap
            </button>
            <a href="{{ url()->current() }}" class="btn btn-light border btn-sm px-3 text-secondary shadow-sm" title="Reset Pencarian">
                <i class="fas fa-sync-alt"></i>
            </a>
            <a href="/pendaftaran/create" class="btn btn-green btn-sm px-3 fw-semibold shadow-sm">
                <i class="fas fa-plus me-1"></i> Pendaftaran
            </a>
            <a href="/pendaftaran/antrian" class="btn btn-orange btn-sm px-3 fw-semibold shadow-sm text-white">
                <i class="fas fa-bullhorn me-1"></i> Panggil Antrian
            </a>

            <!-- Quick Search Input Langsung -->
            <form method="GET" action="{{ url()->current() }}" class="d-inline-flex align-items-center ms-lg-2">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Cari Nama / No RM / No Reg / NIK..." value="{{ request('q') }}" style="min-width: 250px;">
                    <button class="btn btn-primary" type="submit" title="Cari Cepat">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('q'))
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary" title="Hapus Filter">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tombol Kanan -->
        <div>
            <a href="/pendaftaran/cetak-pdf{{ request()->has('tanggal') ? '?tanggal=' . request('tanggal') : '' }}" target="_blank" class="btn btn-cyan btn-sm px-3 fw-semibold shadow-sm">
                <i class="fas fa-print me-1"></i> Cetak Laporan (pdf)
            </a>
        </div>
    </div>

    <!-- Panel Filter Collapsible -->
    <div class="collapse {{ request()->hasAny(['nomor_registrasi', 'no_rm', 'nama_pasien', 'poliklinik_id', 'penjamin_id', 'status_periksa', 'tanggal', 'q']) ? 'show' : '' }} mt-3" id="collapseFilterRajal">
        <div class="filter-card-rajal p-3">
            <h6 class="fw-bold mb-3 text-secondary"><i class="fas fa-filter me-1"></i> Filter Pencarian Data Pendaftaran</h6>
            <form method="GET" action="{{ url()->current() }}">
                <div class="row g-2">
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">No. Registrasi</label>
                        <input type="text" name="nomor_registrasi" class="form-control form-control-sm" placeholder="RG..." value="{{ request('nomor_registrasi') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">No. Rekam Medis</label>
                        <input type="text" name="no_rm" class="form-control form-control-sm" placeholder="RM..." value="{{ request('no_rm') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Nama Pasien</label>
                        <input type="text" name="nama_pasien" class="form-control form-control-sm" placeholder="Nama Pasien..." value="{{ request('nama_pasien') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Poliklinik</label>
                        <select name="poliklinik_id" class="form-select form-select-sm">
                            <option value="">-- Semua Poli --</option>
                            @foreach($polikliniks as $poli)
                                <option value="{{ $poli->id }}" {{ request('poliklinik_id') == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small fw-bold mb-1">Status Periksa</label>
                        <select name="status_periksa" class="form-select form-select-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="Menunggu Antrian" {{ request('status_periksa') == 'Menunggu Antrian' ? 'selected' : '' }}>Menunggu Antrian</option>
                            <option value="Dalam Proses" {{ request('status_periksa') == 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="Selesai" {{ request('status_periksa') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Batal" {{ request('status_periksa') == 'Batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end gap-2 mt-2">
                        <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">
                            <i class="fas fa-search me-1"></i> Terapkan Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-sm btn-light border px-3 text-secondary shadow-sm">
                            <i class="fas fa-times me-1"></i> Bersihkan
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Table Card Sesuai Screenshot 1 -->
<div class="card card-box-rajal p-3">
    <!-- Counter Bar Sesuai Screenshot 1 -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="text-secondary small">
            Menampilkan <strong>{{ $pendaftarans->firstItem() ?? 0 }}-{{ $pendaftarans->lastItem() ?? 0 }}</strong> dari <strong>{{ number_format($pendaftarans->total(), 0, ',', '.') }}</strong> item
        </div>
        <div class="small text-muted">
            Total Halaman: {{ $pendaftarans->lastPage() }}
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered table-pendaftaran align-middle mb-0" id="tabelPendaftaran">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th width="6%" class="text-center">Aksi</th>
                    <th width="12%">Nomor Registrasi</th>
                    <th width="14%">No. Rekam Medis</th>
                    <th width="18%">Data Pasien</th>
                    <th width="10%">Informasi Tambahan</th>
                    <th width="12%">Penjamin</th>
                    <th width="15%">Poli &amp; Tenkes</th>
                    <th width="10%">Status Bridging</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $index => $item)
                <tr id="row-rajal-{{ $item->id }}">
                    <td class="text-center fw-semibold text-secondary">
                        {{ $pendaftarans->firstItem() + $index }}
                    </td>
                    <td class="text-center">
                        <a href="/rekam-medis/skrining/create/{{ $item->id }}" class="btn-skrining" title="Skrining Pasien">
                            <i class="fas fa-file-medical-alt me-1"></i> skrining
                        </a>
                        <a href="/pendaftaran/{{ $item->id }}/edit" class="btn-detail-link" title="Detail / Edit Pendaftaran">
                            <i class="fas fa-external-link-alt me-1"></i> Detail
                        </a>
                    </td>
                    <td>
                        <div class="fw-bold text-dark" style="font-family: monospace; font-size: 12px;">
                            {{ $item->nomor_registrasi ?: $item->no_rawat }}
                        </div>
                        <div class="text-muted small" style="font-size: 11px;">
                            Tgl: {{ $item->tanggal_daftar ? $item->tanggal_daftar->format('d.M.Y') : '-' }}
                        </div>
                        <div class="mt-1">
                            @if($item->status_periksa === 'Selesai')
                                <span class="badge-status-selesai">Selesai</span>
                            @elseif($item->status_periksa === 'Batal')
                                <span class="badge-status-batal">Batal</span>
                            @else
                                <span class="badge-status-proses">Dalam Proses</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-primary">
                            {{ $item->pasien ? $item->pasien->no_rm : '-' }}
                        </div>
                        <div class="text-secondary small">
                            {{ $item->jenis_kunjungan ?: 'Kunjungan Sakit' }}
                        </div>
                        @if($item->is_online)
                            <div class="small fw-bold" style="color: #f39c12; font-size: 11px;">
                                Daftar Via Online
                            </div>
                        @endif
                        <div class="small text-muted" style="font-size: 11px;">
                            @if($item->no_antrian)
                                Antrian No. <span class="fw-bold text-dark">{{ $item->no_antrian }}</span>
                            @else
                                Tanpa Antrian
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark text-uppercase">
                            {{ $item->pasien ? $item->pasien->nama_pasien : 'Pasien Tidak Ditemukan' }}
                        </div>
                        <div class="text-muted small" style="font-size: 11px;">
                            @if($item->pasien)
                                {{ $item->pasien->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }} 
                                {{ $item->pasien->tanggal_lahir ? $item->pasien->tanggal_lahir->format('d.M.Y') : '' }}
                            @endif
                        </div>
                        <div class="text-muted small" style="font-size: 11px; font-family: monospace;">
                            NIK: {{ $item->pasien->nik ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $item->keluhan ?: ($item->keterangan ?: '-') }}
                        </small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">
                            {{ $item->penjamin ? $item->penjamin->nama_penjamin : 'Pribadi' }}
                        </div>
                        <div class="text-muted small" style="font-size: 11px; font-family: monospace;">
                            @if($item->no_bpjs_asuransi)
                                No. {{ $item->no_bpjs_asuransi }}
                            @elseif($item->pasien && $item->pasien->no_bpjs)
                                No. {{ $item->pasien->no_bpjs }}
                            @else
                                No. -
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-secondary" style="font-size: 11.5px;">
                            {{ $item->poliklinik ? $item->poliklinik->nama_poli : 'Umum' }} | {{ $item->ruang_pemeriksaan ?: 'Ruang Rawat 1' }}
                        </div>
                        <div class="small text-muted" style="font-size: 11px;">
                            <strong>Dokter :</strong>{{ $item->dokter ? $item->dokter->nama_pegawai : '-' }}
                        </div>
                        <div class="small text-muted" style="font-size: 11px;">
                            <strong>Perawat :</strong>{{ $item->nama_perawat ?: ($item->perawat ? $item->perawat->nama_pegawai : '-') }}
                        </div>
                    </td>
                    <td>
                        @if($item->status_antrol_bpjs)
                            <div style="font-size: 10.5px; color: #00a65a;">
                                <strong>Antrol BPJS:</strong> {{ $item->status_antrol_bpjs }}
                            </div>
                        @endif
                        @if($item->status_pcare)
                            <div style="font-size: 10.5px; color: #0073b7;">
                                <strong>PCare:</strong> {{ $item->status_pcare }}
                            </div>
                        @endif
                        @if($item->status_satusehat)
                            @php
                                $ssColor = '#dd4b39';
                                if($item->status_satusehat == 'In Progress') $ssColor = '#f39c12';
                                if($item->status_satusehat == 'Finished' || $item->status_satusehat == 'Sudah Kirim') $ssColor = '#00a65a';
                            @endphp
                            <div style="font-size: 10.5px; color: {{ $ssColor }};">
                                <strong>Satu Sehat:</strong> {{ $item->status_satusehat }}
                            </div>
                        @else
                            <div style="font-size: 10.5px; color: #dd4b39;">
                                <strong>Satu Sehat:</strong> Belum Kirim
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-list fa-3x mb-3 text-secondary d-block"></i>
                        Belum ada data pendaftaran yang sesuai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
        <div class="text-muted small">
            Menampilkan data {{ $pendaftarans->firstItem() ?? 0 }} sampai {{ $pendaftarans->lastItem() ?? 0 }} dari total {{ $pendaftarans->total() }} pendaftaran.
        </div>
        <div>
            {{ $pendaftarans->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
