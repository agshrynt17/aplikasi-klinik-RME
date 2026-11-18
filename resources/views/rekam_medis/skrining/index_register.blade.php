@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Pilih Peserta Skrining')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES Screenshot 3) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Pilih Peserta Skrining
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Pilih Peserta Skrining</span>
        </div>
    </div>

    <!-- Filter Pencarian Card (1:1 KONHAKES Screenshot 3) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; margin-top: 15px; margin-bottom: 20px;">
        <div class="d-flex justify-content-between align-items-center" style="padding: 9px 15px; border-bottom: 1px solid #f4f4f4;">
            <span style="font-size: 12.5px; font-weight: 600; color: #333;">Filter Pencarian</span>
            <a href="/skrining/index" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; font-size: 11.5px; padding: 2px 14px; border-radius: 3px;">
                Tutup
            </a>
        </div>
        
        <div style="padding: 15px;">
            <form method="GET" action="/skrining/index-register" id="filterForm">
                <!-- Baris 1 -->
                <div class="row g-2 align-items-end mb-2">
                    <div class="col-md-2" style="width: 125px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Tgl Awal</label>
                        <input type="text" name="tgl_awal" class="form-control form-control-sm" value="{{ $tglAwal }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                    </div>
                    <div class="col-md-2" style="width: 125px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Tgl Akhir</label>
                        <input type="text" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tglAkhir }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
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
                    <div class="col-md-2" style="width: 140px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Jenis Kunjungan</label>
                        <select name="jenis_kunjungan" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Semua--</option>
                            <option value="Kunjungan Sakit" {{ request('jenis_kunjungan') == 'Kunjungan Sakit' ? 'selected' : '' }}>Kunjungan Sakit</option>
                            <option value="Kunjungan Sehat" {{ request('jenis_kunjungan') == 'Kunjungan Sehat' ? 'selected' : '' }}>Kunjungan Sehat</option>
                        </select>
                    </div>
                    <div class="col-auto d-flex align-items-center mb-1">
                        <div class="form-check" style="margin-right: 10px;">
                            <input class="form-check-input" type="checkbox" name="reg_online" value="1" id="regOnlineCheck" {{ request('reg_online') ? 'checked' : '' }} style="border-radius: 2px;">
                            <label class="form-check-label" for="regOnlineCheck" style="font-size: 12px; color: #333;">
                                Reg Online
                            </label>
                        </div>
                        <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px; margin-right: 4px;" title="Cari">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="/skrining/index-register" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>

                <!-- Baris 2 -->
                <div class="row g-2 align-items-end">
                    <div class="col-md-2" style="width: 140px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode Reg</label>
                        <input type="text" name="kode_reg" class="form-control form-control-sm" value="{{ request('kode_reg') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                    </div>
                    <div class="col-md-2" style="width: 140px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode EMR</label>
                        <input type="text" name="kode_emr" class="form-control form-control-sm" value="{{ request('kode_emr') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                    </div>
                    <div class="col-md-2" style="width: 170px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Nama Pasien</label>
                        <input type="text" name="nama_pasien" class="form-control form-control-sm" value="{{ request('nama_pasien') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                    </div>
                    <div class="col-md-2" style="width: 140px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Tgl.Lahir</label>
                        <input type="text" name="tgl_lahir" class="form-control form-control-sm" value="{{ request('tgl_lahir') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                    </div>
                    <div class="col-md-2" style="width: 160px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Skrining Visual</label>
                        <select name="skrining_visual" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Semua--</option>
                            <option value="Sudah" {{ request('skrining_visual') == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                            <option value="Belum" {{ request('skrining_visual') == 'Belum' ? 'selected' : '' }}>Belum</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Container (1:1 KONHAKES Screenshot 3) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div style="padding: 9px 15px; font-size: 12px; color: #444; font-weight: 600; border-bottom: 1px solid #eef2f5;">
            Menampilkan {{ $pendaftarans->firstItem() ?? 0 }}-{{ $pendaftarans->lastItem() ?? 0 }} dari {{ $pendaftarans->total() }} item.
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 11.5px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; border-bottom: 2px solid #d2d6de;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; padding: 8px 6px; font-weight: 600; color: #333;">#</th>
                        <th class="text-center" style="width: 75px; padding: 8px 6px;"></th>
                        <th style="padding: 8px 12px; width: 150px; font-weight: 600; color: #337ab7;">No. Registrasi</th>
                        <th style="padding: 8px 12px; width: 160px; font-weight: 600; color: #337ab7;">No. Rekam Medis</th>
                        <th style="padding: 8px 12px; width: 260px; font-weight: 600; color: #333;">Nama &amp; J.K</th>
                        <th style="padding: 8px 10px; width: 110px; font-weight: 600; color: #333;">Tgl Lahir</th>
                        <th style="padding: 8px 12px; width: 130px; font-weight: 600; color: #333;">Penjamin</th>
                        <th style="padding: 8px 12px; width: 240px; font-weight: 600; color: #333;">Klinik &amp; Dokter</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $index => $item)
                    @php
                        $gender = ($item->pasien && ($item->pasien->jenis_kelamin == 'L' || $item->pasien->jenis_kelamin == 'Laki-laki')) ? 'Laki-Laki' : 'Perempuan';
                        $noReg = $item->nomor_registrasi ?: $item->no_rawat;
                    @endphp
                    <tr class="align-middle" style="background-color: {{ $loop->iteration % 2 == 0 ? '#ffffff' : '#fcfcfc' }};">
                        <td class="text-center" style="padding: 8px 6px; color: #555;">{{ $pendaftarans->firstItem() + $index }}</td>
                        <td class="text-center" style="padding: 8px 6px;">
                            <a href="/skrining/create/{{ $item->id }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; padding: 2px 10px; font-size: 11px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                <i class="fas fa-check"></i> Pilih
                            </a>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>
                                <a href="/skrining/create/{{ $item->id }}" style="color: #337ab7; text-decoration: none; font-weight: 500;">
                                    {{ $noReg }}
                                </a>
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                Tgl: {{ date('d/m/Y', strtotime($item->tanggal_daftar)) }}
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
                        </td>
                        <td style="padding: 8px 10px; font-size: 11.5px; color: #333;">
                            {{ ($item->pasien && $item->pasien->tanggal_lahir) ? date('d M Y', strtotime($item->pasien->tanggal_lahir)) : '-' }}
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
                                Perawat :{{ $item->nama_perawat ?? 'LALA WIJAYANTI AMD.KEB' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-1"></i> Tidak ada data pendaftaran pada filter yang dipilih.
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
