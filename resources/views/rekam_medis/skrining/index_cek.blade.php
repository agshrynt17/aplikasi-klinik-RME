@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Pengecekan Skrining Pasien')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Pengecekan Skrining Pasien
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Pengecekan Skrining Pasien</span>
        </div>
    </div>

    <!-- Filter Pencarian Card (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; margin-top: 15px; margin-bottom: 20px;">
        <div style="padding: 10px 15px 5px 15px;">
            <span style="font-size: 12.5px; font-weight: 600; color: #333;">Filter Pencarian</span>
        </div>
        
        <div style="padding: 10px 15px 15px 15px;">
            <form method="GET" action="/skrining/index-cek" id="filterCekForm">
                <!-- Baris 1 -->
                <div class="row g-2 align-items-end mb-2">
                    <div class="col" style="flex: 1; min-width: 130px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode Rekam Medis</label>
                        <input type="text" name="kode_rm" class="form-control form-control-sm" value="{{ request('kode_rm') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                    </div>
                    <div class="col" style="flex: 1.2; min-width: 140px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Nama Pasien</label>
                        <input type="text" name="nama_pasien" class="form-control form-control-sm" value="{{ request('nama_pasien') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                    </div>
                    <div class="col" style="flex: 1.2; min-width: 140px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Nomor NIK</label>
                        <input type="text" name="nik" class="form-control form-control-sm" value="{{ request('nik') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                    </div>
                    <div class="col" style="flex: 1; min-width: 120px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Tanggal Lahir</label>
                        <input type="text" name="tanggal_lahir" class="form-control form-control-sm" value="{{ request('tanggal_lahir') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="yyyy-mm-dd">
                    </div>
                    <div class="col" style="flex: 1.2; min-width: 140px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Nomor KIS</label>
                        <input type="text" name="nomor_kis" class="form-control form-control-sm" value="{{ request('nomor_kis') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                    </div>
                    <div class="col" style="flex: 1.2; min-width: 140px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Sasaran Kluster</label>
                        <select name="sasaran_kluster" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Cari--</option>
                            <option value="Balita & Anak Prasekolah" {{ request('sasaran_kluster') == 'Balita & Anak Prasekolah' ? 'selected' : '' }}>Kluster: Balita &amp; Anak Prasekolah</option>
                            <option value="Anak Usia Sekolah dan Remaja" {{ request('sasaran_kluster') == 'Anak Usia Sekolah dan Remaja' ? 'selected' : '' }}>Kluster: Anak Usia Sekolah dan Remaja</option>
                            <option value="Dewasa" {{ request('sasaran_kluster') == 'Dewasa' ? 'selected' : '' }}>Kluster: Dewasa</option>
                            <option value="Lansia" {{ request('sasaran_kluster') == 'Lansia' ? 'selected' : '' }}>Kluster: Lansia</option>
                        </select>
                    </div>
                    <div class="col-auto d-flex gap-1 mb-1">
                        <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; font-size: 12px; padding: 0 14px; border-radius: 3px; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="/skrining/index-cek" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>

                <!-- Baris 2 -->
                <div class="row g-2 align-items-end">
                    <div class="col" style="flex: 1; min-width: 120px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Provinsi</label>
                        <select name="provinsi" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Cari--</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col" style="flex: 1.2; min-width: 130px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kabupaten/Kota</label>
                        <select name="kabupaten" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Cari--</option>
                            @foreach($kabupatens as $kab)
                                <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col" style="flex: 1.2; min-width: 130px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kecamatan</label>
                        <select name="kecamatan" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Cari--</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col" style="flex: 1.2; min-width: 130px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kel/Desa</label>
                        <select name="kel_desa" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Cari--</option>
                            @foreach($desas as $ds)
                                <option value="{{ $ds }}" {{ request('kel_desa') == $ds ? 'selected' : '' }}>{{ $ds }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col" style="flex: 2; min-width: 200px;">
                        <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Alamat Identitas</label>
                        <input type="text" name="alamat" class="form-control form-control-sm" value="{{ request('alamat') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Container (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div style="padding: 9px 15px; font-size: 12px; color: #444; font-weight: 600; border-bottom: 1px solid #eef2f5;">
            Menampilkan {{ $pasiens->firstItem() ?? 0 }}-{{ $pasiens->lastItem() ?? 0 }} dari {{ number_format($pasiens->total(), 0, ',', '.') }} item.
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 11.5px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; border-bottom: 2px solid #d2d6de;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; padding: 8px 6px; font-weight: 600; color: #333;">#</th>
                        <th style="padding: 8px 10px; width: 95px; font-weight: 600; color: #337ab7;">Kode RM</th>
                        <th style="padding: 8px 12px; width: 180px; font-weight: 600; color: #337ab7;">
                            Nama Pasien <i class="fas fa-arrows-alt-v ms-1" style="font-size: 10px; color: #888;"></i>
                        </th>
                        <th class="text-center" style="padding: 8px 6px; width: 35px; font-weight: 600; color: #337ab7;">J/K</th>
                        <th style="padding: 8px 12px; width: 180px; font-weight: 600; color: #333;">NIK &amp; KIS</th>
                        <th style="padding: 8px 10px; width: 110px; font-weight: 600; color: #337ab7;">No. HP</th>
                        <th style="padding: 8px 12px; font-weight: 600; color: #333;">Alamat Sesuai Identitas</th>
                        <th class="text-center" style="padding: 8px 8px; width: 90px; font-weight: 600; color: #337ab7;">Tanggal Lahir</th>
                        <th style="padding: 8px 10px; width: 105px; font-weight: 600; color: #333;">Usia</th>
                        <th style="padding: 8px 12px; width: 190px; font-weight: 600; color: #333;">Cluster &amp; Status Skrining</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasiens as $index => $item)
                    @php
                        $gender = ($item->jenis_kelamin == 'L' || $item->jenis_kelamin == 'Laki-laki') ? 'L' : 'P';
                        $hasScreening = $item->skrinings && $item->skrinings->count() > 0;
                        
                        // Buat format alamat identitas lengkap khas PROFASKES
                        $parts = array_filter([
                            $item->alamat,
                            ($item->rt || $item->rw) ? ('RT: ' . ($item->rt ?? '00') . ' RW: ' . ($item->rw ?? '00')) : null,
                            $item->desa ? ($item->desa) : null,
                            $item->kecamatan ? ('Kec.' . $item->kecamatan) : null,
                            $item->kabupaten ? ('Kab/Kota.' . $item->kabupaten) : null,
                            $item->provinsi ? ($item->provinsi . '.') : null
                        ]);
                        $alamatLengkap = strtoupper(implode(', ', $parts));
                    @endphp
                    <tr class="align-middle" style="background-color: {{ $loop->iteration % 2 == 0 ? '#ffffff' : '#fcfcfc' }};">
                        <td class="text-center" style="padding: 8px 6px; color: #555;">{{ $pasiens->firstItem() + $index }}</td>
                        <td style="padding: 8px 10px;">
                            <a href="/master-pasien?search={{ $item->no_rm }}" style="color: #337ab7; text-decoration: none; font-weight: 500;">
                                {{ $item->no_rm }}
                            </a>
                        </td>
                        <td style="padding: 8px 12px; font-weight: 600; color: #333;">
                            {{ strtoupper($item->nama_pasien) }}
                        </td>
                        <td class="text-center" style="padding: 8px 6px; font-weight: 500;">
                            {{ $gender }}
                        </td>
                        <td style="padding: 8px 12px; font-size: 11px;">
                            <div>NIK:{{ $item->nik ?? '-' }}</div>
                            <div style="color: #555; margin-top: 2px;">KIS:{{ $item->no_bpjs ?? '-' }}</div>
                        </td>
                        <td style="padding: 8px 10px; font-size: 11px; color: #555;">
                            {{ $item->no_hp ?: ($item->no_telp ?: '-') }}
                        </td>
                        <td style="padding: 8px 12px; font-size: 11px; color: #444; line-height: 1.4;">
                            {{ $alamatLengkap ?: '-' }}
                        </td>
                        <td class="text-center" style="padding: 8px 8px; font-size: 11px; color: #333;">
                            {{ $item->tanggal_lahir ? date('d/m/Y', strtotime($item->tanggal_lahir)) : '-' }}
                        </td>
                        <td style="padding: 8px 10px; font-size: 11px; color: #333;">
                            {{ $item->usia_lengkap }}
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="font-weight: 600; color: #333; font-size: 11px;">
                                Kluster: {{ $item->kluster }}
                            </div>
                            @if($hasScreening)
                                <div style="margin-top: 3px;">
                                    <span class="badge" style="background-color: #00a65a; color: #fff; font-size: 9.5px; font-weight: normal; padding: 2px 6px; border-radius: 2px;">
                                        Sudah Skrining
                                    </span>
                                </div>
                                <div style="margin-top: 2px;">
                                    <a href="/skrining/edit/{{ $item->skrinings->first()->id }}" style="color: #337ab7; font-size: 11px; text-decoration: none;">
                                        <i class="fas fa-edit"></i> Buka Skrining
                                    </a>
                                </div>
                            @else
                                <div style="margin-top: 3px;">
                                    <span class="badge" style="background-color: #dd4b39; color: #fff; font-size: 9.5px; font-weight: normal; padding: 2px 6px; border-radius: 2px;">
                                        Belum Pernah Skrining
                                    </span>
                                </div>
                                <div style="margin-top: 2px;">
                                    <a href="/skrining/create-pasien/{{ $item->id }}" style="color: #337ab7; font-size: 11px; text-decoration: none;">
                                        <i class="fas fa-edit"></i> Buka Skrining
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-1"></i> Tidak ada data pasien yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($pasiens->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $pasiens->firstItem() }} sampai {{ $pasiens->lastItem() }} dari {{ $pasiens->total() }} entri
            </div>
            <div>
                {{ $pasiens->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
