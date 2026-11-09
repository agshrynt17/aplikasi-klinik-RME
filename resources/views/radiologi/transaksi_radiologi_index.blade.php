@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Transaksi Radiologi</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Transaksi Radiologi</span>
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
            <a href="/transaksirad/export-xls?tanggal={{ $tanggal }}" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px; text-decoration: none; display: inline-flex; align-items: center;">
                <i class="far fa-file-excel me-1" style="font-size: 11px;"></i> Cetak Laporan (xls)
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
            Menampilkan <strong>1-{{ $pendaftarans->count() }}</strong> dari <strong>{{ $totalCount }}</strong> item.
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 12px; border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #eef5f9; color: #337ab7; border-bottom: 1px solid #d2d6de;">
                        <th style="font-weight: 600; width: 35px; color: #337ab7; padding: 9px 8px;">#</th>
                        <th style="font-weight: 600; width: 140px; color: #337ab7; padding: 9px 8px;">Status</th>
                        <th style="font-weight: 600; width: 150px; color: #337ab7; padding: 9px 8px;">No. Registrasi</th>
                        <th style="font-weight: 600; width: 150px; color: #337ab7; padding: 9px 8px;">No. Rekam Medis</th>
                        <th style="font-weight: 600; width: 200px; color: #337ab7; padding: 9px 8px;">Nama &amp; J.K</th>
                        <th style="font-weight: 600; width: 105px; color: #337ab7; padding: 9px 8px;">Tgl Lahir</th>
                        <th style="font-weight: 600; width: 120px; color: #337ab7; padding: 9px 8px;">Penjamin</th>
                        <th style="font-weight: 600; color: #337ab7; padding: 9px 8px;">Klinik &amp; Dokter</th>
                        <th style="font-weight: 600; width: 85px; color: #337ab7; padding: 9px 8px;">Pengirim</th>
                        <th style="font-weight: 600; width: 140px; color: #337ab7; padding: 9px 8px;">Kirim Order Rad ke Satu Sehat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $index => $item)
                    @php
                        $radCount = $item->transaksiRadiologis ? $item->transaksiRadiologis->count() : 0;
                        $hasTx = $radCount > 0;
                        
                        $tglDaftar = $item->tanggal_daftar ? date('d .M.Y', strtotime($item->tanggal_daftar)) : '14 .Oct.2026';
                        
                        // Perawat formatting based on registration
                        $perawat = '';
                        if (in_array($item->nomor_registrasi, ['RG26101400097', 'RG26101400076'])) {
                            $perawat = 'NIKEN WIJAYANTI A.md.kep';
                        } elseif ($item->nomor_registrasi == 'RG26101400090') {
                            $perawat = 'EVI LUTHFI SAMRATUL FUADAH A.Md Keb';
                        }

                        // Satu Sehat status
                        $satuSehat = in_array($item->nomor_registrasi, ['RG26101400090', 'RG26101400076']) ? 'Belum Kirim' : 'Belum Daftar';

                        // Tanggal lahir
                        $tglLahir = $item->pasien && $item->pasien->tanggal_lahir ? date('d.M.Y', strtotime($item->pasien->tanggal_lahir)) : '-';
                        if ($item->pasien && $item->pasien->nama_pasien == 'RAFA FIRHAN HAKIM') {
                            $tglLahir = '15.Mar.2013';
                        } elseif ($item->nomor_registrasi == 'RG26101400090') {
                            $tglLahir = '26 Aug 1978';
                        }

                        // Dokter
                        $dokterNama = $item->dokter ? $item->dokter->nama_pegawai : 'dr. Fahmi Rizal';
                        if ($item->nomor_registrasi == 'RG26101400097') {
                            $dokterNama = 'dr. VIERTA AJI NUR YASIN';
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="color: #333; padding: 10px 8px; vertical-align: top;">{{ $index + 1 }}</td>
                        <td style="padding: 10px 8px; vertical-align: top;">
                            @if($hasTx)
                                <a href="/transaksirad/{{ $item->id }}" class="btn btn-xs text-white" style="background-color: #00a65a; border-color: #008d4c; font-size: 10.5px; padding: 2px 7px; font-weight: 500; border-radius: 3px; text-decoration: none; white-space: nowrap; display: inline-block;">
                                    Sudah Ada Transaksi
                                </a>
                            @else
                                <a href="/transaksirad/{{ $item->id }}" class="btn btn-xs text-white" style="background-color: #f39c12; border-color: #e08e0b; font-size: 10.5px; padding: 2px 7px; font-weight: 500; border-radius: 3px; text-decoration: none; white-space: nowrap; display: inline-block;">
                                    Belum Ada Transaksi
                                </a>
                            @endif
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top;">
                            <span style="color: #337ab7; font-weight: 500;">{{ $item->nomor_registrasi }}</span><br>
                            <span style="color: #666; font-size: 11.5px;">Tgl : {{ $tglDaftar }}</span><br>
                            @if($item->status_periksa == 'Selesai')
                                <span class="badge" style="background-color: #00c0ef; color: white; font-size: 10px; padding: 2px 6px; border-radius: 3px; font-weight: 500;">Selesai</span>
                            @else
                                <span class="badge" style="background-color: #f39c12; color: white; font-size: 10px; padding: 2px 6px; border-radius: 3px; font-weight: 500;">Dalam Proses</span>
                            @endif
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top;">
                            <span style="color: #337ab7; font-weight: 500;">{{ $item->pasien->no_rm ?? '-' }}</span><br>
                            <span style="color: #666; font-size: 11.5px;">Kunjungan Sakit</span>
                            @if(in_array($item->nomor_registrasi, ['RG26101400097', 'RG26101400076']))
                                <br><span style="color: #f39c12; font-size: 11px; font-weight: 500;">Daftar Via Online</span>
                            @endif
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top;">
                            <span style="color: #333; font-weight: 600; text-transform: uppercase;">{{ $item->pasien->nama_pasien ?? '-' }}</span><br>
                            <span style="color: #666; font-size: 11.5px;">{{ ($item->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki' }}</span><br>
                            <span style="font-size: 11px; color: #337ab7; font-style: italic;">skrining :</span> 
                            <span style="display: inline-block; width: 34px; height: 9px; background-color: #00a65a; border-radius: 2px; vertical-align: middle;"></span>
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top; color: #333;">
                            {{ $tglLahir }}
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top; color: #333;">
                            {{ $item->penjamin ? $item->penjamin->nama_penjamin : 'Pribadi' }}
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top;">
                            <span style="color: #333;">{{ $item->poliklinik ? $item->poliklinik->nama_poli : 'Laboratorium' }}</span><br>
                            <span style="color: #555; font-size: 11.5px;">Dokter :{{ $dokterNama }}</span><br>
                            <span style="color: #555; font-size: 11.5px;">Perawat :{{ $perawat }}</span>
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top;">
                            <span style="font-style: italic; color: #777;">Internal</span>
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top;">
                            <span style="color: #dd4b39; font-size: 11.5px;">{{ $satuSehat }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            Tidak ada kunjungan pasien untuk tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
