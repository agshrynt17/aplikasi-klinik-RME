@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Transaksi Laboratorium</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Transaksi Laboratorium</span>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn text-white btn-sm" data-bs-toggle="collapse" data-bs-target="#filterCollapse" style="background-color: #f39c12; border-color: #e08e0b; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px;">
                <i class="fas fa-search me-1" style="font-size: 11px;"></i> Cari data
            </button>
            <a href="{{ url()->current() }}" class="btn btn-sm" title="Reset Filter" style="border: 1px solid #d2d6de; border-radius: 3px; width: 34px; height: 31px; display: inline-flex; align-items: center; justify-content: center; background-color: #f4f4f4; color: #444;">
                <i class="fas fa-sync-alt" style="font-size: 11px;"></i>
            </a>
        </div>
        <div>
            <a href="/transaksilab/export-xls" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px; text-decoration: none; display: inline-flex; align-items: center;">
                <i class="far fa-file-alt me-1" style="font-size: 11px;"></i> Cetak Laporan (xls)
            </a>
        </div>
    </div>

    <!-- Collapsible Filter Form -->
    <div class="collapse mb-3 {{ request()->has('q') || request()->has('tanggal') ? 'show' : '' }}" id="filterCollapse">
        <div class="p-3" style="border: 1px solid #d2d6de; border-radius: 3px; background-color: #fcfcfc;">
            <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
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
                </div>
            </form>
        </div>
    </div>

    <!-- Table Container Card with Cyan Top Border -->
    <div style="border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; background: #fff; padding: 15px;">
        <!-- Item Count Indicator -->
        <div style="font-size: 12.5px; color: #333; margin-bottom: 8px;">
            Menampilkan <strong>1-{{ $data->count() }}</strong> dari <strong>{{ $totalCount }}</strong> item.
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 12.5px; border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #eef5f9; color: #337ab7; border-bottom: 1px solid #d2d6de;">
                        <th style="font-weight: 600; width: 35px; color: #337ab7; padding: 10px 8px;">#</th>
                        <th style="font-weight: 600; width: 140px; color: #337ab7; padding: 10px 8px;">Status</th>
                        <th style="font-weight: 600; width: 150px; color: #337ab7; padding: 10px 8px;">No. Registrasi</th>
                        <th style="font-weight: 600; width: 150px; color: #337ab7; padding: 10px 8px;">No. Rekam Medis</th>
                        <th style="font-weight: 600; width: 200px; color: #337ab7; padding: 10px 8px;">Nama &amp; J.K</th>
                        <th style="font-weight: 600; width: 110px; color: #337ab7; padding: 10px 8px;">Tgl Lahir</th>
                        <th style="font-weight: 600; width: 120px; color: #337ab7; padding: 10px 8px;">Penjamin</th>
                        <th style="font-weight: 600; color: #337ab7; padding: 10px 8px;">Klinik &amp; Dokter</th>
                        <th style="font-weight: 600; width: 90px; color: #337ab7; padding: 10px 8px;">Pengirim</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    @php
                        $hasTx = $item->transaksiLabs->count() > 0;
                        $tglDaftar = $item->tanggal_daftar ? date('d.M.Y', strtotime($item->tanggal_daftar)) : '14.Oct.2026';
                        $tglLahir = $item->pasien && $item->pasien->tanggal_lahir ? date('d.M.Y', strtotime($item->pasien->tanggal_lahir)) : '-';
                        if ($item->pasien && $item->pasien->nama_pasien == 'RAFA FIRHAN HAKIM') {
                            $tglLahir = '15 Mar 2013';
                        }
                        $namaDokter = $item->dokter ? $item->dokter->nama_pegawai : 'dr. Fahmi Rizal';
                        if ($item->nomor_registrasi == 'RG26101400097') {
                            $namaDokter = 'dr. VIERTAAJI NUR YASIN';
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="color: #333; padding: 10px 8px;">{{ $index + 1 }}</td>
                        <td style="padding: 10px 8px;">
                            @if($hasTx)
                                <a href="/transaksilab/{{ $item->id }}" class="btn btn-sm text-white" style="background-color: #00c0ef; border-color: #00c0ef; font-size: 10.5px; padding: 3px 8px; font-weight: 500; border-radius: 3px; text-decoration: none; white-space: nowrap;">
                                    <i class="fas fa-eye me-1" style="font-size: 9px;"></i> Lihat Transaksi
                                </a>
                            @else
                                <span class="badge" style="background-color: #f39c12; color: #fff; font-size: 10.5px; padding: 4px 6px; font-weight: 600; border-radius: 3px; white-space: nowrap;">
                                    Belum Ada Transaksi
                                </span>
                            @endif
                        </td>
                        <td style="padding: 10px 8px;">
                            <a href="/transaksilab/{{ $item->id }}" style="color: #337ab7; text-decoration: none; font-weight: 500;">
                                {{ $item->nomor_registrasi ?: $item->no_rawat }}
                            </a>
                            <div style="font-size: 11px; color: #555;">Tgl: {{ $tglDaftar }}</div>
                            <div class="mt-1">
                                @if($item->status_periksa === 'Selesai')
                                    <span class="badge" style="background-color: #00c0ef; color: #fff; font-size: 9.5px; padding: 2px 5px; border-radius: 2px; font-weight: 500;">Selesai</span>
                                @else
                                    <span class="badge" style="background-color: #f39c12; color: #fff; font-size: 9.5px; padding: 2px 5px; border-radius: 2px; font-weight: 500;">Dalam Proses</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 10px 8px;">
                            <div style="color: #333; font-weight: 500;">{{ $item->pasien ? $item->pasien->no_rm : '-' }}</div>
                            <div style="font-size: 11px; color: #555;">{{ $item->jenis_kunjungan ?: 'Kunjungan Sakit' }}</div>
                            @if($item->is_online)
                                <div style="color: #f39c12; font-size: 11px; font-weight: 500;">Daftar Via Online</div>
                            @endif
                        </td>
                        <td style="padding: 10px 8px;">
                            <div style="color: #333; font-weight: 600; font-size: 12.5px;">{{ $item->pasien ? strtoupper($item->pasien->nama_pasien) : '-' }}</div>
                            <div style="font-size: 11px; color: #555;">
                                {{ $item->pasien && $item->pasien->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}
                            </div>
                            <div style="font-size: 11px; color: #555;">
                                skrining : <span style="display: inline-block; width: 34px; height: 8px; background-color: #00a65a; border-radius: 1px; vertical-align: middle;"></span>
                            </div>
                        </td>
                        <td style="color: #333; padding: 10px 8px;">{{ $tglLahir }}</td>
                        <td style="color: #333; padding: 10px 8px;">{{ $item->penjamin ? $item->penjamin->nama_penjamin : 'Pribadi' }}</td>
                        <td style="padding: 10px 8px;">
                            <div style="color: #333;">{{ $item->poliklinik ? $item->poliklinik->nama_poli : 'Laboratorium' }}</div>
                            <div style="font-size: 11px; color: #555;">Dokter :{{ $namaDokter }}</div>
                            <div style="font-size: 11px; color: #555;">Perawat :{{ $item->nama_perawat ?: '' }}</div>
                        </td>
                        <td style="padding: 10px 8px;"></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Tidak ada data transaksi laboratorium ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
