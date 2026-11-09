@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Dokumen Pelayanan Pasien Rawat Jalan')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Data Dokumen Pelayanan Pasien {{ $mode == 'rawat_inap' ? 'Rawat Inap' : 'Rawat Jalan' }}
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Dokumen Pelayanan Pasien {{ $mode == 'rawat_inap' ? 'Rawat Inap' : 'Rawat Jalan' }}</span>
        </div>
    </div>

    <!-- Mode Switcher Buttons (1:1 PROFASKES) -->
    <div class="d-flex justify-content-center gap-2 mb-3 mt-2">
        <a href="/dokumen-pasien/index?mode=rawat_jalan" class="btn btn-sm" style="{{ $mode != 'rawat_inap' ? 'background-color: #337ab7; color: #fff; border-color: #2e6da4;' : 'background-color: #fff; color: #337ab7; border: 1px solid #337ab7;' }} padding: 6px 16px; font-size: 12px; font-weight: 500; border-radius: 4px; text-decoration: none;">
            Dokumen Pasien Rawat Jalan
        </a>
        <a href="/dokumen-pasien/index?mode=rawat_inap" class="btn btn-sm" style="{{ $mode == 'rawat_inap' ? 'background-color: #337ab7; color: #fff; border-color: #2e6da4;' : 'background-color: #fff; color: #337ab7; border: 1px solid #337ab7;' }} padding: 6px 16px; font-size: 12px; font-weight: 500; border-radius: 4px; text-decoration: none;">
            Dokumen Pasien Rawat Inap
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    <!-- Filter Bar Card with Cyan Top Border (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; padding: 15px 15px; margin-bottom: 20px;">
        <form method="GET" action="/dokumen-pasien/index" id="filterForm">
            <input type="hidden" name="mode" value="{{ $mode }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-2" style="width: 120px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Tgl.Daftar</label>
                    <input type="text" name="tgl_daftar" class="form-control form-control-sm" value="{{ $tglAwalFormatted }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div class="col-md-2" style="width: 120px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Sampai</label>
                    <input type="text" name="sampai" class="form-control form-control-sm" value="{{ $tglAkhirFormatted }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div class="col-md-2" style="width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode Reg</label>
                    <input type="text" name="kode_reg" class="form-control form-control-sm" value="{{ request('kode_reg') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-2" style="flex: 1; min-width: 140px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Nama Pasien</label>
                    <input type="text" name="nama_pasien" class="form-control form-control-sm" value="{{ request('nama_pasien') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-2" style="width: 120px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode EMR</label>
                    <input type="text" name="kode_emr" class="form-control form-control-sm" value="{{ request('kode_emr') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-2" style="width: 140px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Penjamin</label>
                    <select name="penjamin" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih--</option>
                        @foreach($listPenjamin as $p)
                            <option value="{{ $p->id }}" {{ request('penjamin') == $p->id || request('penjamin') == $p->nama_penjamin ? 'selected' : '' }}>{{ $p->nama_penjamin }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2" style="width: 140px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Klinik</label>
                    <select name="klinik" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Semua--</option>
                        @foreach($listKlinik as $k)
                            <option value="{{ $k->id }}" {{ request('klinik') == $k->id || request('klinik') == $k->nama_poli ? 'selected' : '' }}>{{ $k->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; padding: 0 14px; border-radius: 3px; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="/dokumen-pasien/index?mode={{ $mode }}" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Container (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div style="padding: 9px 15px; font-size: 12px; color: #444; font-weight: 600; border-bottom: 1px solid #eef2f5;">
            Menampilkan {{ $pendaftarans->firstItem() ?? 0 }}-{{ $pendaftarans->lastItem() ?? 0 }} dari {{ $pendaftarans->total() }} item.
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 11.5px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; color: #333; font-weight: 600; font-size: 11px; border-bottom: 2px solid #d2d6de;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; padding: 8px 6px;">#</th>
                        <th style="padding: 8px 12px; width: 160px;">NO. REGISTRASI</th>
                        <th style="padding: 8px 12px; width: 160px;">NO. REKAM MEDIS</th>
                        <th style="padding: 8px 12px; width: 260px;">NAMA &amp; J.K</th>
                        <th class="text-center" style="padding: 8px 10px; width: 110px;">TGL LAHIR</th>
                        <th style="padding: 8px 12px; width: 130px;">PENJAMIN</th>
                        <th style="padding: 8px 12px; width: 240px;">KLINIK &amp; DOKTER</th>
                        <th class="text-center" style="padding: 8px 10px; width: 140px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $index => $item)
                    @php
                        $gender = ($item->pasien && ($item->pasien->jenis_kelamin == 'L' || $item->pasien->jenis_kelamin == 'Laki-laki')) ? 'Laki-Laki' : 'Perempuan';
                        $statusPeriksa = $item->status_periksa ?: 'Dalam Proses';
                        $noRk = $item->nomor_registrasi ?: $item->no_rawat;
                        $countDocs = isset($dokumens[$item->id]) ? count($dokumens[$item->id]) : 0;
                    @endphp
                    <tr class="align-middle" style="background-color: {{ $loop->iteration % 2 == 0 ? '#ffffff' : '#fcfcfc' }};">
                        <td class="text-center" style="padding: 8px 6px; color: #555;">{{ $pendaftarans->firstItem() + $index }}</td>
                        <td style="padding: 8px 12px;">
                            <div>
                                <a href="/dokumen-pasien/detail/{{ $item->id }}" style="color: #337ab7; text-decoration: none; font-weight: 500;">
                                    {{ $noRk }}
                                </a>
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                Tgl: {{ date('d.M.Y', strtotime($item->tanggal_daftar)) }}
                            </div>
                            <div style="margin-top: 3px;">
                                <span class="badge" style="background-color: {{ $statusPeriksa == 'Selesai' ? '#00c0ef' : '#f39c12' }}; color: #fff; font-size: 9.5px; font-weight: normal; padding: 2px 5px; border-radius: 2px;">
                                    {{ $statusPeriksa }}
                                </span>
                            </div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>
                                <a href="/master-pasien?search={{ $item->pasien->no_rm ?? '' }}" style="color: #337ab7; text-decoration: none; font-weight: 500;">
                                    {{ $item->pasien->no_rm ?? '-' }}
                                </a>
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                {{ $item->jenis_kunjungan ?: 'Kunjungan Sakit' }}
                            </div>
                            @if($item->is_online)
                                <div style="color: #e67e22; font-size: 10.5px; margin-top: 2px;">
                                    Daftar Via Online
                                </div>
                            @endif
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="font-weight: 600; color: #333; font-size: 11.5px;">
                                {{ strtoupper($item->pasien->nama_pasien ?? '-') }}
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                {{ $gender }}
                            </div>
                            <div style="font-size: 10.5px; color: #777; margin-top: 3px;">
                                skrining : <span style="display: inline-block; width: 45px; height: 10px; background-color: #00a65a; border-radius: 2px; vertical-align: middle; margin-left: 3px;"></span>
                            </div>
                        </td>
                        <td class="text-center" style="padding: 8px 10px; font-size: 11.5px; color: #333;">
                            {{ ($item->pasien && $item->pasien->tanggal_lahir) ? date('d.M.Y', strtotime($item->pasien->tanggal_lahir)) : '-' }}
                        </td>
                        <td style="padding: 8px 12px; font-size: 11.5px; color: #333;">
                            {{ $item->penjamin->nama_penjamin ?? 'Pribadi' }}
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="color: #333; font-weight: 500;">
                                {{ $item->poliklinik->nama_poli ?? '-' }}
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                Dokter :{{ $item->dokter->nama_pegawai ?? '-' }}
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                Perawat :{{ $item->nama_perawat ?? '' }}
                            </div>
                        </td>
                        <td class="text-center" style="padding: 8px 10px;">
                            <a href="/dokumen-pasien/detail/{{ $item->id }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; font-weight: 500; padding: 4px 10px; border-radius: 3px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-eye"></i> Lihat Dokumen
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-1"></i> Tidak ada data pasien rawat jalan pada tanggal dan filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($pendaftarans->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $pendaftarans->firstItem() }} sampai {{ $pendaftarans->lastItem() }} dari {{ $pendaftarans->total() }} entri
            </div>
            <div>
                {{ $pendaftarans->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
