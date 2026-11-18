@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Modul PPA Medical Checkup')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Modul PPA Medical Checkup
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Modul PPA Medical Checkup</span>
        </div>
    </div>

    <!-- Divider Line with Orange Accent -->
    <div style="height: 1px; background: #f39c12; margin-bottom: 15px; margin-top: 10px;"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    <!-- Filter Pencarian Card (1:1 KONHAKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 15px 12px 15px; margin-bottom: 20px;">
        <div style="font-size: 12px; font-weight: 700; color: #337ab7; margin-bottom: 10px; letter-spacing: 0.3px;">
            FILTER PENCARIAN
        </div>

        <form method="GET" action="/moduldokter/index-mcu" id="filterMcuForm">
            <!-- Baris 1 -->
            <div class="row g-2 align-items-end mb-2">
                <div class="col" style="flex: 1.1; min-width: 120px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Tanggal</label>
                    <input type="text" name="tanggal" class="form-control form-control-sm" value="{{ $tanggal }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div class="col" style="flex: 1.1; min-width: 120px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">s.d Tanggal</label>
                    <input type="text" name="sd_tanggal" class="form-control form-control-sm" value="{{ $sdTanggal }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div class="col" style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Lokasi MCU</label>
                    <select name="lokasi_mcu" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">---</option>
                        <option value="Klinik Utama" {{ request('lokasi_mcu') == 'Klinik Utama' ? 'selected' : '' }}>Klinik Utama</option>
                        <option value="On-Site / Perusahaan" {{ request('lokasi_mcu') == 'On-Site / Perusahaan' ? 'selected' : '' }}>On-Site / Perusahaan</option>
                    </select>
                </div>
                <div class="col" style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Penjamin</label>
                    <select name="penjamin" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">---</option>
                        @foreach($listPenjamin as $p)
                            <option value="{{ $p->id }}" {{ request('penjamin') == $p->id ? 'selected' : '' }}>{{ $p->nama_penjamin }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col" style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Poli</label>
                    <select name="poli" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">---</option>
                        @foreach($listPoli as $k)
                            <option value="{{ $k->id }}" {{ request('poli') == $k->id ? 'selected' : '' }}>{{ $k->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col" style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Ruangan</label>
                    <select name="ruangan" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">---</option>
                        @foreach($listRuangan as $r)
                            <option value="{{ $r->id }}" {{ request('ruangan') == $r->id ? 'selected' : '' }}>{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-1 mb-1">
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; padding: 0 14px; border-radius: 3px; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="/moduldokter/index-mcu" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>

            <!-- Baris 2 -->
            <div class="row g-2 align-items-center">
                <div class="col" style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">No Registrasi</label>
                    <input type="text" name="no_registrasi" class="form-control form-control-sm" value="{{ request('no_registrasi') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col" style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode RM</label>
                    <input type="text" name="kode_rm" class="form-control form-control-sm" value="{{ request('kode_rm') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col" style="flex: 1.4; min-width: 150px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Nama Pasien</label>
                    <input type="text" name="nama_pasien" class="form-control form-control-sm" value="{{ request('nama_pasien') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col" style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Jenis MCU</label>
                    <select name="jenis_mcu" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">---</option>
                        <option value="MCU Standar" {{ request('jenis_mcu') == 'MCU Standar' ? 'selected' : '' }}>MCU Standar</option>
                        <option value="MCU Karyawan" {{ request('jenis_mcu') == 'MCU Karyawan' ? 'selected' : '' }}>MCU Karyawan</option>
                        <option value="MCU Pra-Nikah" {{ request('jenis_mcu') == 'MCU Pra-Nikah' ? 'selected' : '' }}>MCU Pra-Nikah</option>
                        <option value="MCU Driver" {{ request('jenis_mcu') == 'MCU Driver' ? 'selected' : '' }}>MCU Driver</option>
                        <option value="MCU Eksekutif" {{ request('jenis_mcu') == 'MCU Eksekutif' ? 'selected' : '' }}>MCU Eksekutif</option>
                    </select>
                </div>
                <div class="col" style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Project</label>
                    <select name="project" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">---</option>
                        <option value="Project Umum" {{ request('project') == 'Project Umum' ? 'selected' : '' }}>Project Umum</option>
                    </select>
                </div>
                <div class="col-auto d-flex align-items-center" style="margin-top: 15px;">
                    <div class="form-check" style="line-height: 1.1;">
                        <input class="form-check-input" type="checkbox" name="pendaftaran_batal" value="1" id="pendaftaranBatalCheck" {{ request('pendaftaran_batal') ? 'checked' : '' }} style="border-radius: 2px;">
                        <label class="form-check-label" for="pendaftaranBatalCheck" style="font-size: 11px; color: #333;">
                            Pendaftaran<br>batal
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Container (1:1 KONHAKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 11.5px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; border-bottom: 2px solid #d2d6de;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; padding: 9px 6px; font-weight: 600; color: #333;">#</th>
                        <th style="padding: 9px 12px; width: 160px; font-weight: 600; color: #337ab7;">Nomor Registrasi</th>
                        <th style="padding: 9px 12px; width: 140px; font-weight: 600; color: #333;">Poli, Ruang</th>
                        <th style="padding: 9px 12px; width: 160px; font-weight: 600; color: #333;">Pasien</th>
                        <th style="padding: 9px 12px; width: 130px; font-weight: 600; color: #333;">J.K &amp; Tgl.Lahir</th>
                        <th style="padding: 9px 12px; width: 160px; font-weight: 600; color: #333;">Penjamin /Perusahaan</th>
                        <th style="padding: 9px 12px; width: 140px; font-weight: 600; color: #333;">Jenis &amp; Project</th>
                        <th class="text-center" style="padding: 9px 12px; width: 110px; font-weight: 600; color: #333;">Asesmen</th>
                        <th class="text-center" style="padding: 9px 12px; width: 110px; font-weight: 600; color: #333;">Rekam Medis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $index => $item)
                    @php
                        $pasien = $item->pasien;
                        $gender = ($pasien && ($pasien->jenis_kelamin == 'L' || $pasien->jenis_kelamin == 'Laki-laki')) ? 'Laki-Laki' : 'Perempuan';
                        $tglLahir = $pasien && $pasien->tanggal_lahir ? date('d/m/Y', strtotime($pasien->tanggal_lahir)) : '-';
                        $noReg = $item->nomor_registrasi ?: $item->no_rawat;
                        $hasMcu = isset($mcus[$item->id]);
                    @endphp
                    <tr class="align-middle">
                        <td class="text-center" style="padding: 8px 6px; color: #555;">{{ $pendaftarans->firstItem() + $index }}</td>
                        <td style="padding: 8px 12px;">
                            <div>
                                <a href="/rekam-medis/mcu/create/{{ $item->id }}" style="color: #337ab7; text-decoration: none; font-weight: 500;">
                                    {{ $noReg }}
                                </a>
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                Tgl: {{ date('d/m/Y', strtotime($item->tanggal_daftar)) }}
                            </div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="color: #333; font-weight: 500;">
                                {{ $item->poliklinik->nama_poli ?? '-' }}
                            </div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">
                                Ruang: {{ $item->ruang_pemeriksaan ?: '-' }}
                            </div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="font-weight: 600; color: #333;">
                                {{ strtoupper($pasien->nama_pasien ?? '-') }}
                            </div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">
                                RM: {{ $pasien->no_rm ?? '-' }}
                            </div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>{{ $gender }}</div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">{{ $tglLahir }}</div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>{{ $item->penjamin->nama_penjamin ?? 'Pribadi' }}</div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">-</div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>MCU Standar</div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">-</div>
                        </td>
                        <td class="text-center" style="padding: 8px 10px;">
                            @if($hasMcu)
                                <a href="/rekam-medis/mcu/edit/{{ $mcus[$item->id]->id }}" class="btn text-white btn-sm" style="background-color: #00a65a; border-color: #008d4c; font-size: 11px; padding: 2px 10px; border-radius: 3px; text-decoration: none;">
                                    <i class="fas fa-check"></i> Selesai
                                </a>
                            @else
                                <a href="/rekam-medis/mcu/create/{{ $item->id }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 2px 10px; border-radius: 3px; text-decoration: none;">
                                    <i class="fas fa-stethoscope"></i> Isi MCU
                                </a>
                            @endif
                        </td>
                        <td class="text-center" style="padding: 8px 10px;">
                            <a href="/master-pasien?search={{ $pasien->no_rm ?? '' }}" class="btn btn-sm btn-light border" style="font-size: 11px; padding: 2px 10px; border-radius: 3px; text-decoration: none;">
                                <i class="fas fa-book-medical"></i> RME
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding: 12px 15px; color: #555; font-size: 12px;">
                            Tidak ada data yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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
