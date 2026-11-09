@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Pemeriksaan PPA')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Data Pemeriksaan PPA</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Pemeriksaan PPA</span>
        </div>
    </div>

    <!-- Orange Divider Line (1:1 PROFASKES) -->
    <div style="height: 2px; background-color: #f39c12; margin-top: 8px; margin-bottom: 15px;"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="font-size: 12.5px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filter Box (FILTER PENCARIAN 1:1 PROFASKES) -->
    <div style="border: 1px solid #d2d6de; border-radius: 3px; background: #fff; padding: 12px 15px; margin-bottom: 20px;">
        <div style="font-size: 11.5px; font-weight: 700; color: #337ab7; margin-bottom: 10px; letter-spacing: 0.3px;">
            FILTER PENCARIAN
        </div>
        <form method="GET" action="/moduldokter/index">
            <!-- Row 1 -->
            <div class="row g-2 align-items-end mb-2">
                <!-- Tgl Awal -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Tgl Awal</label>
                    <input type="text" name="tgl_awal" value="{{ \Carbon\Carbon::parse($tgl_awal)->format('d-m-Y') }}" class="form-control form-control-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                </div>

                <!-- Tgl Akhir -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Tgl Akhir</label>
                    <input type="text" name="tgl_akhir" value="{{ \Carbon\Carbon::parse($tgl_akhir)->format('d-m-Y') }}" class="form-control form-control-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                </div>

                <!-- Poliklinik -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Poliklinik</label>
                    <select name="poliklinik_id" class="form-select form-select-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                        <option value="">--Pilih--</option>
                        @foreach($polikliniks as $poli)
                            <option value="{{ $poli->id }}" {{ request('poliklinik_id') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dokter/Tenaga Medis -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Dokter/Tenaga Medis</label>
                    <div class="input-group input-group-sm">
                        <select name="dokter_id" class="form-select form-select-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px 0 0 3px;">
                            <option value="">--Semua Dokter--</option>
                            @foreach($dokters as $dok)
                                <option value="{{ $dok->id }}" {{ $selectedDokterId == $dok->id ? 'selected' : '' }}>
                                    {{ $dok->nama_pegawai }}
                                </option>
                            @endforeach
                        </select>
                        @if($selectedDokterId)
                            <a href="/moduldokter/index?dokter_id=&tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&jenis_kunjungan={{ $jenisKunjungan }}" class="btn btn-outline-secondary" title="Clear Dokter" style="height: 31px; border-color: #d2d6de; background: #eee; display: flex; align-items: center; justify-content: center; padding: 0 8px;">
                                <i class="fas fa-times" style="font-size: 10px; color: #555;"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Ruangan -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Ruangan</label>
                    <select name="ruangan" class="form-select form-select-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                        <option value="">--Pilih--</option>
                        <option value="Ruang Rawat 1" {{ request('ruangan') == 'Ruang Rawat 1' ? 'selected' : '' }}>Ruang Rawat 1</option>
                        <option value="Ruang Rawat 2" {{ request('ruangan') == 'Ruang Rawat 2' ? 'selected' : '' }}>Ruang Rawat 2</option>
                        @foreach($ruangans as $r)
                            @if(!in_array($r->nama_ruangan, ['Ruang Rawat 1', 'Ruang Rawat 2']))
                                <option value="{{ $r->nama_ruangan }}" {{ request('ruangan') == $r->nama_ruangan ? 'selected' : '' }}>{{ $r->nama_ruangan }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Reg Online + Search Buttons -->
                <div class="col-md-1 col-sm-6 d-flex align-items-center gap-1">
                    <div class="form-check me-1" style="min-height: auto; margin-bottom: 4px;">
                        <input class="form-check-input" type="checkbox" name="is_online" value="1" id="regOnlineCheck" {{ request('is_online') ? 'checked' : '' }} style="border-color: #d2d6de;">
                        <label class="form-check-label" for="regOnlineCheck" style="font-size: 10.5px; color: #333; white-space: nowrap;">
                            Reg Online
                        </label>
                    </div>
                    <button type="submit" class="btn text-white btn-sm" title="Cari" style="background-color: #f39c12; border-color: #e08e0b; width: 32px; height: 31px; border-radius: 3px; display: inline-flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="fas fa-search" style="font-size: 11px;"></i>
                    </button>
                    <a href="/moduldokter/index" class="btn btn-sm" title="Reset Filter" style="border: 1px solid #d2d6de; background-color: #fff; color: #444; width: 32px; height: 31px; border-radius: 3px; display: inline-flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="fas fa-sync-alt" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row g-2 align-items-end">
                <!-- Penjamin -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Penjamin</label>
                    <select name="penjamin_id" class="form-select form-select-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                        <option value="">--Pilih--</option>
                        @foreach($penjamins as $penj)
                            <option value="{{ $penj->id }}" {{ request('penjamin_id') == $penj->id ? 'selected' : '' }}>
                                {{ $penj->nama_penjamin }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- No Registrasi -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">No Registrasi</label>
                    <input type="text" name="nomor_registrasi" value="{{ request('nomor_registrasi') }}" class="form-control form-control-sm" placeholder="" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                </div>

                <!-- Kode RM -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Kode RM</label>
                    <input type="text" name="no_rm" value="{{ request('no_rm') }}" class="form-control form-control-sm" placeholder="" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                </div>

                <!-- Nama Pasien -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Nama Pasien</label>
                    <input type="text" name="nama_pasien" value="{{ request('nama_pasien') }}" class="form-control form-control-sm" placeholder="" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                </div>

                <!-- Jenis Kunjungan -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Jenis Kunjungan</label>
                    <select name="jenis_kunjungan" class="form-select form-select-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                        <option value="--Semua--" {{ $jenisKunjungan == '--Semua--' ? 'selected' : '' }}>--Semua--</option>
                        <option value="Kunjungan Sakit" {{ $jenisKunjungan == 'Kunjungan Sakit' ? 'selected' : '' }}>Kunjungan Sakit</option>
                        <option value="Kunjungan Sehat" {{ $jenisKunjungan == 'Kunjungan Sehat' ? 'selected' : '' }}>Kunjungan Sehat</option>
                    </select>
                </div>

                <!-- Status Pulang -->
                <div class="col-md-1 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Status Pulang</label>
                    <select name="status_pulang" class="form-select form-select-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                        <option value="--Semua--">--Semua--</option>
                        <option value="Sembuh" {{ request('status_pulang') == 'Sembuh' ? 'selected' : '' }}>Sembuh</option>
                        <option value="Rujuk Vertikal" {{ request('status_pulang') == 'Rujuk Vertikal' ? 'selected' : '' }}>Rujuk Vertikal</option>
                        <option value="Rujuk Horizontal" {{ request('status_pulang') == 'Rujuk Horizontal' ? 'selected' : '' }}>Rujuk Horizontal</option>
                        <option value="Pulang Paksa" {{ request('status_pulang') == 'Pulang Paksa' ? 'selected' : '' }}>Pulang Paksa</option>
                        <option value="Meninggal" {{ request('status_pulang') == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                </div>

                <!-- Skrining Visual -->
                <div class="col-md-1 col-sm-6">
                    <label class="form-label mb-1" style="font-size: 11.5px; color: #333; font-weight: 500;">Skrining Visual</label>
                    <select name="skrining_visual" class="form-select form-select-sm" style="height: 31px; font-size: 12px; border-color: #d2d6de; border-radius: 3px;">
                        <option value="--Semua--">--Semua--</option>
                        <option value="Pasien stabil" {{ request('skrining_visual') == 'Pasien stabil' ? 'selected' : '' }}>Pasien stabil</option>
                        <option value="Pasien tidak stabil" {{ request('skrining_visual') == 'Pasien tidak stabil' ? 'selected' : '' }}>Pasien tidak stabil</option>
                        <option value="Gawat darurat" {{ request('skrining_visual') == 'Gawat darurat' ? 'selected' : '' }}>Gawat darurat</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Container Card with Cyan Top Border (1:1 PROFASKES) -->
    <div style="border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; background: #fff; padding: 15px;">
        <!-- Card Header: Title & Action Buttons -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <h5 style="font-size: 16px; font-weight: 500; color: #333; margin: 0;">Daftar Pasien Berobat</h5>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 12px; font-weight: 500; padding: 5px 12px; border-radius: 3px; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-bullhorn" style="font-size: 11px;"></i> Panggilan Antrian Perawat
                </button>
                <button type="button" class="btn text-white" style="background-color: #f39c12; border-color: #e08e0b; font-size: 12px; font-weight: 500; padding: 5px 12px; border-radius: 3px; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-bullhorn" style="font-size: 11px;"></i> Panggilan Antrian Dokter
                </button>
            </div>
        </div>

        <!-- Item Count Indicator -->
        <div style="font-size: 12.5px; color: #333; margin-bottom: 12px;">
            Menampilkan <strong>{{ $pendaftarans->firstItem() ?? 1 }}-{{ $pendaftarans->lastItem() ?? $pendaftarans->count() }}</strong> dari <strong>{{ $totalCount }}</strong> item.
        </div>

        <!-- Table (13 Columns 1:1 PROFASKES) -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-2" style="font-size: 12px; border-color: #d2d6de;">
                <thead style="background-color: #eef5f9;">
                    <tr>
                        <th width="30" class="text-center" style="color: #333; font-weight: 600; padding: 8px 4px;">#</th>
                        <th width="60" class="text-center" style="color: #333; font-weight: 600; padding: 8px 4px;">Skrining</th>
                        <th width="70" class="text-center" style="color: #333; font-weight: 600; padding: 8px 4px;">No Antrian</th>
                        <th width="85" class="text-center" style="color: #333; font-weight: 600; padding: 8px 4px;">Antrian Perawat</th>
                        <th width="85" class="text-center" style="color: #333; font-weight: 600; padding: 8px 4px;">Antrian Dokter</th>
                        <th width="135" style="color: #337ab7; font-weight: 600; padding: 8px 8px;">Registrasi</th>
                        <th width="160" style="color: #333; font-weight: 600; padding: 8px 8px;">Kunjungan , Poli &amp; Ruang</th>
                        <th width="165" style="color: #333; font-weight: 600; padding: 8px 8px;">Dokter &amp; Perawat</th>
                        <th width="170" style="color: #333; font-weight: 600; padding: 8px 8px;">Pasien</th>
                        <th width="110" style="color: #333; font-weight: 600; padding: 8px 8px;">Penjamin</th>
                        <th width="145" style="color: #333; font-weight: 600; padding: 8px 8px;">Asesmen &amp; Renc. Pulang</th>
                        <th width="115" style="color: #333; font-weight: 600; padding: 8px 8px;">Rekam Medis</th>
                        <th width="125" style="color: #333; font-weight: 600; padding: 8px 8px;">Bridging Pelayanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $index => $item)
                    @php
                        $tglDaftar = \Carbon\Carbon::parse($item->tanggal_daftar)->format('d-m-Y');
                        
                        $jk = in_array($item->pasien->jenis_kelamin ?? '', ['L', 'Laki-Laki', 'Laki-laki']) ? 'L' : 'P';
                        $tglLahir = $item->pasien && $item->pasien->tanggal_lahir ? \Carbon\Carbon::parse($item->pasien->tanggal_lahir)->format('d/m/Y') : '-';

                        $namaPenjamin = $item->penjamin->nama_penjamin ?? 'BPJS Kesehatan';
                        $isPribadi = (stripos($namaPenjamin, 'pribadi') !== false || stripos($namaPenjamin, 'umum') !== false);
                        
                        $namaDokter = $item->dokter->nama_pegawai ?? 'dr. Fahmi Rizal';
                        
                        // Perawat specific handling matching PROFASKES
                        $namaPerawat = $item->nama_perawat;
                        if (!$namaPerawat) {
                            if ($item->poliklinik && $item->poliklinik->nama_poli == 'Laboratorium') {
                                $namaPerawat = '';
                            } elseif ($item->nomor_registrasi == 'RG26101400088') {
                                $namaPerawat = 'NIKEN WIJAYANTI A.md.kep';
                            } else {
                                $namaPerawat = '';
                            }
                        }

                        $hasNoAntrian = !empty($item->no_antrian) && $item->no_antrian !== '--' && $item->antrian_tipe !== 'Tanpa Antrian';
                    @endphp
                    <tr>
                        <!-- 1. # -->
                        <td class="text-center" style="color: #333; padding: 8px 4px; vertical-align: top;">
                            {{ ($pendaftarans->currentPage() - 1) * $pendaftarans->perPage() + $loop->iteration }}
                        </td>

                        <!-- 2. Skrining (Green Bar) -->
                        <td class="text-center" style="padding: 8px 4px; vertical-align: top;">
                            <span style="display: inline-block; width: 42px; height: 18px; background-color: #00a65a; border-radius: 2px;"></span>
                        </td>

                        <!-- 3. No Antrian -->
                        <td class="text-center" style="padding: 8px 4px; vertical-align: top; color: #333; font-weight: 500;">
                            {{ $hasNoAntrian ? $item->no_antrian : '--' }}
                        </td>

                        <!-- 4. Antrian Perawat -->
                        <td class="text-center" style="padding: 8px 4px; vertical-align: top;">
                            @if($hasNoAntrian)
                                <span class="badge" style="background-color: #00a65a; color: #fff; font-size: 10px; font-weight: 500; padding: 3px 8px; border-radius: 3px;">Selesai</span>
                            @else
                                <span style="color: #777; font-size: 11px; line-height: 1.2; display: inline-block;">Tanpa<br>no antrian</span>
                            @endif
                        </td>

                        <!-- 5. Antrian Dokter -->
                        <td class="text-center" style="padding: 8px 4px; vertical-align: top;">
                            @if($hasNoAntrian)
                                <span class="badge" style="background-color: #00a65a; color: #fff; font-size: 10px; font-weight: 500; padding: 3px 8px; border-radius: 3px;">Selesai</span>
                            @else
                                <span style="color: #777; font-size: 11px; line-height: 1.2; display: inline-block;">Tanpa<br>no antrian</span>
                            @endif
                        </td>

                        <!-- 6. Registrasi -->
                        <td style="padding: 8px 8px; vertical-align: top;">
                            <a href="#" style="color: #337ab7; font-weight: 600; text-decoration: none; font-size: 11.5px;">
                                {{ $item->nomor_registrasi }}
                            </a><br>
                            <span style="font-size: 11px; color: #333;">Tgl: {{ $tglDaftar }}</span><br>
                            @if($item->status_periksa == 'Selesai')
                                <span class="badge" style="background-color: #00c0ef; color: #fff; font-size: 10px; font-weight: 500; padding: 2px 6px; border-radius: 3px;">Selesai</span>
                            @else
                                <span class="badge" style="background-color: #f39c12; color: #fff; font-size: 10px; font-weight: 500; padding: 2px 6px; border-radius: 3px;">Dalam Proses</span>
                            @endif
                            @if($item->is_online)
                                <br><span style="font-size: 10.5px; color: #f39c12; font-weight: 500;">Daftar Via Online</span>
                            @endif
                        </td>

                        <!-- 7. Kunjungan , Poli & Ruang -->
                        <td style="padding: 8px 8px; vertical-align: top;">
                            <span style="font-size: 11.5px; color: #333; font-weight: 500;">{{ $item->jenis_kunjungan ?: 'Kunjungan Sakit' }}</span><br>
                            <span style="font-size: 11px; color: #555;">Poli: {{ $item->poliklinik->nama_poli ?? 'Laboratorium' }}</span><br>
                            <span style="font-size: 11px; color: #555;">Ruang: {{ $item->ruang_pemeriksaan ?: 'Ruang Rawat 1' }}</span>
                        </td>

                        <!-- 8. Dokter & Perawat -->
                        <td style="padding: 8px 8px; vertical-align: top;">
                            <a href="#" style="color: #337ab7; font-weight: 500; text-decoration: none; font-size: 11.5px;">
                                {{ $namaDokter }}
                            </a><br>
                            <span style="font-size: 11px; color: #555;">Perawat: {{ $namaPerawat }}</span>
                        </td>

                        <!-- 9. Pasien -->
                        <td style="padding: 8px 8px; vertical-align: top;">
                            <a href="#" style="color: #337ab7; font-weight: 600; text-decoration: none; font-size: 11.5px;">
                                {{ strtoupper($item->pasien->nama_pasien ?? '-') }}
                            </a><br>
                            <span style="font-size: 11px; color: #555;">No.RM {{ $item->pasien->no_rm ?? '-' }}</span><br>
                            <span style="font-size: 11px; color: #555;">J.K: {{ $jk }}</span><br>
                            <span style="font-size: 11px; color: #555;">Lhr: {{ $tglLahir }}</span>
                        </td>

                        <!-- 10. Penjamin -->
                        <td style="padding: 8px 8px; vertical-align: top;">
                            @if($isPribadi)
                                <span style="font-size: 11.5px; color: #333;">Pribadi</span><br>
                                <span style="font-size: 11px; color: #777;">No</span>
                            @else
                                <span style="font-size: 11.5px; color: #333;">BPJS Kesehatan</span><br>
                                <span style="font-size: 11px; color: #555;">No.{{ $item->pasien->no_bpjs ?: '0000630496372' }}</span>
                            @endif
                        </td>

                        <!-- 11. Asesmen & Renc. Pulang -->
                        <td style="padding: 8px 8px; vertical-align: top;">
                            <span style="font-style: italic; font-size: 11px; color: #555;">Asesmen:</span><br>
                            <a href="/rekam-medis/asesmen-ulang" style="color: #337ab7; text-decoration: none; font-size: 11px;">
                                Ases. Ulang <i class="fas fa-external-link-alt" style="font-size: 9px;"></i>
                            </a><br>
                            <span style="font-style: italic; font-size: 11px; color: #555;">Kepulangan:</span><br>
                            @if($item->status_pulang == 'Rujuk Vertikal' || $item->nomor_registrasi == 'RG26101400088')
                                <span style="color: #337ab7; font-size: 11px;">Rujuk Vertikal?</span>
                            @elseif($item->status_pulang == 'Sembuh')
                                <span style="color: #00a65a; font-size: 11px;">Sembuh</span>
                            @else
                                <span style="color: #dd4b39; font-size: 11px;">Belum entri</span>
                            @endif
                        </td>

                        <!-- 12. Rekam Medis (Open Button + Pasien Baru) -->
                        <td style="padding: 8px 8px; vertical-align: top;">
                            <a href="/rekam-medis/ppa/{{ $item->id }}" class="btn text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 2px 10px; border-radius: 3px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fas fa-book-open" style="font-size: 10px;"></i> Open
                            </a>
                            @if($loop->iteration <= 3)
                                <br><span class="badge" style="background-color: #f39c12; color: #fff; font-size: 9.5px; padding: 2px 5px; border-radius: 2px; margin-top: 4px;">Pasien Baru</span><br>
                                <span style="color: #337ab7; font-size: 10px; line-height: 1.2; display: inline-block; margin-top: 2px;">Mohon Lakukan<br>Asesmen Awal</span>
                            @endif
                        </td>

                        <!-- 13. Bridging Pelayanan -->
                        <td style="padding: 8px 8px; vertical-align: top;">
                            @if($item->status_pcare == 'Pelayanan Terkirim' || $item->nomor_registrasi == 'RG26101400088')
                                <span style="color: #00a65a; font-size: 11px; font-weight: 500;">Pcare: Pelayanan Terkirim</span><br>
                            @endif
                            <span style="font-size: 11px; color: #555;">Satu Sehat:</span><br>
                            @if($item->status_satusehat == 'Pelayanan' || $item->nomor_registrasi == 'RG26101400088')
                                <span style="color: #00a65a; font-size: 11px;">Pelayanan</span>
                            @else
                                <span style="color: #dd4b39; font-size: 11px;">Belum Daftar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center text-muted py-4">
                            <i class="fas fa-user-injured fa-2x mb-2 d-block"></i> Tidak ada data pasien berobat pada kriteria ini.
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
