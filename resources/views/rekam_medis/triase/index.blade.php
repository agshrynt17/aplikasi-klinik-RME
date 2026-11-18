@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Triase')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Data Triase</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Triase</span>
        </div>
    </div>

    <!-- Orange Divider Line (1:1 KONHAKES) -->
    <div style="height: 2px; background-color: #f39c12; margin-top: 8px; margin-bottom: 15px;"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="font-size: 12.5px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Action Bar (Left & Right Buttons) -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn text-white btn-sm" data-bs-toggle="collapse" data-bs-target="#filterCollapse" style="background-color: #f39c12; border-color: #e08e0b; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px;">
                <i class="fas fa-search me-1" style="font-size: 11px;"></i> Cari data
            </button>
            <a href="/triase/index" class="btn btn-sm" title="Reset Filter" style="border: 1px solid #d2d6de; border-radius: 3px; width: 34px; height: 31px; display: inline-flex; align-items: center; justify-content: center; background-color: #fff; color: #444;">
                <i class="fas fa-sync-alt" style="font-size: 11px;"></i>
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="/triase/export-xls?tanggal={{ $tanggal }}" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px; text-decoration: none; display: inline-flex; align-items: center;">
                <i class="far fa-file-excel me-1" style="font-size: 11px;"></i> Cetak Laporan (xls)
            </a>
            <a href="/triase/export-pdf?tanggal={{ $tanggal }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px; text-decoration: none; display: inline-flex; align-items: center;">
                <i class="far fa-file-pdf me-1" style="font-size: 11px;"></i> Cetak Laporan (pdf)
            </a>
        </div>
    </div>

    <!-- Collapsible Filter Form -->
    <div class="collapse mb-3 {{ request()->has('q') || request()->has('tanggal') ? 'show' : '' }}" id="filterCollapse">
        <div class="p-3" style="border: 1px solid #d2d6de; border-radius: 3px; background-color: #fcfcfc;">
            <form method="GET" action="/triase/index" class="row g-2 align-items-end">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label fw-bold mb-1" style="font-size: 12px; color: #333;">Pencarian (No. Reg / RM / Pasien)</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Ketik kata kunci..." style="height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fw-bold mb-1" style="font-size: 12px; color: #333;">Tanggal Pelayanan</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal', $tanggal) }}" class="form-control form-control-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
                </div>
                <div class="col-md-3 col-sm-6 d-flex gap-2">
                    <button type="submit" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px;">
                        Terapkan Filter
                    </button>
                    <a href="/triase/index" class="btn btn-sm btn-outline-secondary" style="height: 32px; padding: 0 12px; border-radius: 3px; font-size: 12px; display: inline-flex; align-items: center;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Container Card with Cyan Top Border (1:1 KONHAKES) -->
    <div style="border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; background: #fff; padding: 15px;">
        <!-- Item Count Indicator -->
        <div style="font-size: 12.5px; color: #333; margin-bottom: 12px;">
            Menampilkan <strong>{{ $pendaftarans->firstItem() ?? 1 }}-{{ $pendaftarans->lastItem() ?? $pendaftarans->count() }}</strong> dari <strong>{{ $totalCount }}</strong> item
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-2" style="font-size: 12px; border-color: #d2d6de;">
                <thead style="background-color: #eef5f9;">
                    <tr>
                        <th width="35" class="text-center" style="color: #333; font-weight: 600; padding: 8px 5px;">#</th>
                        <th width="135" style="color: #337ab7; font-weight: 600; padding: 8px 10px;">Status</th>
                        <th width="140" style="color: #337ab7; font-weight: 600; padding: 8px 10px;">No. Registrasi</th>
                        <th width="140" style="color: #337ab7; font-weight: 600; padding: 8px 10px;">No. Rekam Medis</th>
                        <th style="min-width: 190px; color: #337ab7; font-weight: 600; padding: 8px 10px;">Nama &amp; J.K</th>
                        <th width="105" style="color: #337ab7; font-weight: 600; padding: 8px 10px;">Tgl Lahir</th>
                        <th width="115" style="color: #337ab7; font-weight: 600; padding: 8px 10px;">Penjamin</th>
                        <th width="230" style="color: #337ab7; font-weight: 600; padding: 8px 10px;">Klinik &amp; Dokter</th>
                        <th width="85" style="color: #337ab7; font-weight: 600; padding: 8px 10px;">Alergi Obat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $index => $item)
                    @php
                        $hasTriase = isset($triases[$item->id]);
                        $tglDaftar = \Carbon\Carbon::parse($item->tanggal_daftar)->format('d.Oct.Y');
                        $tglLahir = $item->pasien && $item->pasien->tanggal_lahir ? \Carbon\Carbon::parse($item->pasien->tanggal_lahir)->format('d.M.Y') : '-';
                        if ($tglLahir == '08.May.1971') {
                            $tglLahir = '08 May 1971';
                        }
                        $jk = in_array($item->pasien->jenis_kelamin ?? '', ['L', 'Laki-Laki', 'Laki-laki']) ? 'Laki-Laki' : 'Perempuan';
                        
                        $namaPenjamin = $item->penjamin->nama_penjamin ?? 'BPJS Kesehatan';
                        if (stripos($namaPenjamin, 'pribadi') !== false || stripos($namaPenjamin, 'umum') !== false) {
                            $namaPenjamin = 'Pribadi';
                        }
                        
                        $namaDokter = $item->dokter->nama_pegawai ?? '-';
                    @endphp
                    <tr>
                        <!-- 1. # -->
                        <td class="text-center" style="color: #333; padding: 8px 5px;">
                            {{ ($pendaftarans->currentPage() - 1) * $pendaftarans->perPage() + $loop->iteration }}
                        </td>

                        <!-- 2. Status (Two-tier: Belum Input Triase + Button Input Triase) -->
                        <td style="padding: 8px 10px; vertical-align: top;">
                            @if($hasTriase)
                                <span class="badge" style="background-color: #00a65a; color: #fff; font-size: 10px; font-weight: 500; padding: 2px 6px; border-radius: 3px; display: inline-block; margin-bottom: 3px;">
                                    Sudah Input Triase
                                </span><br>
                                <a href="/triase/{{ $triases[$item->id]->id }}/edit" class="btn text-white" style="background-color: #337ab7; border-color: #2e6da4; font-size: 10.5px; padding: 2px 7px; border-radius: 3px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                    <i class="fas fa-edit" style="font-size: 9px;"></i> Edit Triase
                                </a>
                            @else
                                <span class="badge" style="background-color: #f39c12; color: #fff; font-size: 10px; font-weight: 500; padding: 2px 6px; border-radius: 3px; display: inline-block; margin-bottom: 3px;">
                                    Belum Input Triase
                                </span><br>
                                <a href="/triase/create/{{ $item->id }}" class="btn text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 10.5px; padding: 2px 7px; border-radius: 3px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                    <i class="fas fa-plus" style="font-size: 9px;"></i> Input Triase
                                </a>
                            @endif
                        </td>

                        <!-- 3. No. Registrasi -->
                        <td style="padding: 8px 10px; vertical-align: top;">
                            <a href="/triase/create/{{ $item->id }}" style="color: #337ab7; font-weight: 600; text-decoration: none; font-size: 11.5px;">
                                {{ $item->nomor_registrasi }}
                            </a><br>
                            <span style="font-size: 11px; color: #333;">Tgl: {{ $tglDaftar }}</span><br>
                            @if($item->status_periksa == 'Selesai')
                                <span class="badge" style="background-color: #00c0ef; color: #fff; font-size: 10px; font-weight: 500; padding: 2px 6px; border-radius: 3px;">Selesai</span>
                            @else
                                <span class="badge" style="background-color: #f39c12; color: #fff; font-size: 10px; font-weight: 500; padding: 2px 6px; border-radius: 3px;">Dalam Proses</span>
                            @endif
                        </td>

                        <!-- 4. No. Rekam Medis -->
                        <td style="padding: 8px 10px; vertical-align: top;">
                            <a href="#" style="color: #337ab7; font-weight: 600; text-decoration: none; font-size: 11.5px;">
                                {{ $item->pasien->no_rm ?? '-' }}
                            </a><br>
                            <span style="font-size: 11px; color: #555;">{{ $item->jenis_kunjungan ?: 'Kunjungan Sakit' }}</span>
                            @if($item->is_online)
                                <br><span style="font-size: 11px; color: #f39c12; font-weight: 500;">Daftar Via Online</span>
                            @endif
                        </td>

                        <!-- 5. Nama & J.K -->
                        <td style="padding: 8px 10px; vertical-align: top;">
                            <a href="#" style="color: #337ab7; font-weight: 600; text-decoration: none; font-size: 11.5px;">
                                {{ strtoupper($item->pasien->nama_pasien ?? '-') }}
                            </a><br>
                            <span style="font-size: 11px; color: #333;">{{ $jk }}</span><br>
                            @if($hasTriase)
                                <span style="font-size: 11px; color: #777;">Skrining : </span><span class="badge" style="background-color: #00a65a; font-size: 10px; padding: 2px 6px;">{{ $triases[$item->id]->kategori_triase }}</span>
                            @else
                                <span style="font-size: 11px; color: #777;">Skrining : </span><span style="font-size: 11px; color: #c9302c; font-style: italic;">--belum triase--</span>
                            @endif
                        </td>

                        <!-- 6. Tgl Lahir -->
                        <td style="padding: 8px 10px; vertical-align: top;">
                            <span style="font-size: 11.5px; color: #333;">{{ $tglLahir }}</span>
                        </td>

                        <!-- 7. Penjamin -->
                        <td style="padding: 8px 10px; vertical-align: top;">
                            <span style="font-size: 11.5px; color: #333;">{{ $namaPenjamin }}</span>
                        </td>

                        <!-- 8. Klinik & Dokter -->
                        <td style="padding: 8px 10px; vertical-align: top;">
                            <span style="font-size: 11.5px; color: #333;">{{ $item->poliklinik->nama_poli ?? '-' }}</span><br>
                            <span style="font-size: 11px; color: #555;">Dokter :{{ $namaDokter }}</span><br>
                            <span style="font-size: 11px; color: #555;">Perawat :{{ $item->nama_perawat ?: ($item->poliklinik && $item->poliklinik->nama_poli == 'Laboratorium' ? '' : 'NIKEN WIJAYANTI A.md.kep') }}</span>
                        </td>

                        <!-- 9. Alergi Obat -->
                        <td style="padding: 8px 10px; vertical-align: top; color: #777;">
                            -
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i> Tidak ada data pasien pada tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mt-3 pt-2" style="border-top: 1px solid #eee;">
            <div style="font-size: 12px; color: #666;">
                Halaman {{ $pendaftarans->currentPage() }} dari {{ $pendaftarans->lastPage() }}
            </div>
            <div>
                {{ $pendaftarans->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
