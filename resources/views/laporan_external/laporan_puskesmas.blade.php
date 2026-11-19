@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'LAPORAN PUSKESMAS')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0; text-transform: uppercase;">LAPORAN PUSKESMAS</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999; text-transform: uppercase;">LAPORAN PUSKESMAS</span>
        </div>
    </div>

    <!-- Cyan Divider Line (1:1 KONHAKES) -->
    <div style="height: 2px; background-color: #00c0ef; margin-top: 8px; margin-bottom: 15px;"></div>

    <!-- Filter Pencarian Card (1:1 KONHAKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px;">Filter Pencarian</div>
        
        <form method="GET" action="/laporan-eksternal/laporan-puskesmas" id="filterForm">
            <!-- Row 1: Tanggal, Klinik, Penjamin, Status Pasien & Action Buttons -->
            <div class="d-flex align-items-end flex-wrap gap-2 mb-3">
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tgl Awal</label>
                    <input type="text" name="tgl_awal" class="form-control form-control-sm" value="{{ $tglAwalFormatted }}" style="width: 120px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tgl Akhir</label>
                    <input type="text" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tglAkhirFormatted }}" style="width: 120px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Klinik</label>
                    <select name="klinik" class="form-select form-select-sm" style="width: 150px; font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Semua--</option>
                        @foreach($listKlinik as $k)
                            <option value="{{ $k->id }}" {{ $klinik == $k->id || $klinik == $k->nama_poli ? 'selected' : '' }}>{{ $k->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Penjamin</label>
                    <select name="penjamin" class="form-select form-select-sm" style="width: 150px; font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Semua--</option>
                        @foreach($listPenjamin as $p)
                            <option value="{{ $p->id }}" {{ $penjamin == $p->id || $penjamin == $p->nama_penjamin ? 'selected' : '' }}>{{ $p->nama_penjamin }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Status Pasien</label>
                    <select name="status_pasien" class="form-select form-select-sm" style="width: 130px; font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Semua--</option>
                        <option value="Pasien Baru" {{ $statusPasien == 'Pasien Baru' ? 'selected' : '' }}>Pasien Baru</option>
                        <option value="Pasien Lama" {{ $statusPasien == 'Pasien Lama' ? 'selected' : '' }}>Pasien Lama</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div>
                    <a href="/laporan-eksternal/laporan-puskesmas" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/laporan-puskesmas/export-pdf?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&klinik={{ urlencode($klinik) }}&penjamin={{ urlencode($penjamin) }}&status_pasien={{ urlencode($statusPasien) }}&provinsi={{ urlencode($provinsi) }}&kabupaten={{ urlencode($kabupaten) }}&kecamatan={{ urlencode($kecamatan) }}&desa={{ urlencode($desa) }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;" target="_blank">
                        <i class="far fa-file-pdf"></i> Cetak (Pdf)
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/laporan-puskesmas/export-excel?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&klinik={{ urlencode($klinik) }}&penjamin={{ urlencode($penjamin) }}&status_pasien={{ urlencode($statusPasien) }}&provinsi={{ urlencode($provinsi) }}&kabupaten={{ urlencode($kabupaten) }}&kecamatan={{ urlencode($kecamatan) }}&desa={{ urlencode($desa) }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;">
                        <i class="far fa-file-excel"></i> Cetak (Excel)
                    </a>
                </div>
            </div>

            <!-- Row 2: Wilayah Dropdowns -->
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Provinsi</label>
                    <select name="provinsi" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Propinsi--</option>
                        <option value="Jawa Barat" {{ $provinsi == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                        <option value="DKI Jakarta" {{ $provinsi == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                        <option value="Jawa Tengah" {{ $provinsi == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                        <option value="Jawa Timur" {{ $provinsi == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                        <option value="Banten" {{ $provinsi == 'Banten' ? 'selected' : '' }}>Banten</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kabupaten/Kota</label>
                    <select name="kabupaten" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Kabupaten--</option>
                        <option value="Karawang" {{ $kabupaten == 'Karawang' ? 'selected' : '' }}>Kab. Karawang</option>
                        <option value="Bekasi" {{ $kabupaten == 'Bekasi' ? 'selected' : '' }}>Kab. Bekasi</option>
                        <option value="Purwakarta" {{ $kabupaten == 'Purwakarta' ? 'selected' : '' }}>Kab. Purwakarta</option>
                        <option value="Subang" {{ $kabupaten == 'Subang' ? 'selected' : '' }}>Kab. Subang</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kecamatan</label>
                    <select name="kecamatan" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Kecamatan--</option>
                        <option value="Kotabaru" {{ $kecamatan == 'Kotabaru' ? 'selected' : '' }}>Kotabaru</option>
                        <option value="Cikampek" {{ $kecamatan == 'Cikampek' ? 'selected' : '' }}>Cikampek</option>
                        <option value="Telagasari" {{ $kecamatan == 'Telagasari' ? 'selected' : '' }}>Telagasari</option>
                        <option value="Lemahabang" {{ $kecamatan == 'Lemahabang' ? 'selected' : '' }}>Lemahabang</option>
                        <option value="Klari" {{ $kecamatan == 'Klari' ? 'selected' : '' }}>Klari</option>
                        <option value="Karawang Barat" {{ $kecamatan == 'Karawang Barat' ? 'selected' : '' }}>Karawang Barat</option>
                        <option value="Karawang Timur" {{ $kecamatan == 'Karawang Timur' ? 'selected' : '' }}>Karawang Timur</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Desa/Kelurahan</label>
                    <select name="desa" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Desa--</option>
                        <option value="Cikampek Utara" {{ $desa == 'Cikampek Utara' ? 'selected' : '' }}>Cikampek Utara</option>
                        <option value="Cikampek Barat" {{ $desa == 'Cikampek Barat' ? 'selected' : '' }}>Cikampek Barat</option>
                        <option value="Wancimekar" {{ $desa == 'Wancimekar' ? 'selected' : '' }}>Wancimekar</option>
                        <option value="Pucung" {{ $desa == 'Pucung' ? 'selected' : '' }}>Pucung</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Container (1:1 KONHAKES) -->
    <div class="table-responsive" style="border: 1px solid #d2d6de; background: #fff; border-radius: 3px; overflow-x: auto; box-shadow: 0 1px 1px rgba(0,0,0,0.05);">
        <table class="table table-bordered table-hover mb-0" style="font-size: 11.5px; white-space: nowrap; border-collapse: collapse; width: 100%;">
            <thead style="background-color: #e4e7ea; color: #333; font-weight: 600; font-size: 11.5px; border-bottom: 2px solid #d2d6de;">
                <tr class="align-middle">
                    <th class="text-center" style="width: 40px; padding: 8px 6px;">No</th>
                    <th class="text-center" style="padding: 8px 10px;">Tgl &amp; Jam</th>
                    <th class="text-center" style="padding: 8px 10px;">RM#</th>
                    <th class="text-center" style="padding: 8px 10px;">RK#</th>
                    <th class="text-center" style="padding: 8px 10px;">Status Pasien</th>
                    <th style="padding: 8px 12px;">Nama Pasien</th>
                    <th class="text-center" style="padding: 8px 10px;">NIK</th>
                    <th class="text-center" style="padding: 8px 10px;">JKN-KIS#</th>
                    <th class="text-center" style="padding: 8px 10px;">Tgl.lahir</th>
                    <th class="text-center" style="padding: 8px 10px;">U/Jk</th>
                    <th style="padding: 8px 12px;">Alamat</th>
                    <th class="text-center" style="padding: 8px 10px;">No HP</th>
                    <th class="text-center" style="padding: 8px 8px;">Prolanis</th>
                    <th class="text-center" style="padding: 8px 8px;">PRB</th>
                    <th class="text-center" style="padding: 8px 10px;">Pembiaya</th>
                    <th class="text-center" style="padding: 8px 8px;">Alasan</th>
                    <th style="padding: 8px 10px;">Dokter/Bidan</th>
                    <th style="padding: 8px 12px;">Keluhan Utama</th>
                    <th style="padding: 8px 12px;">Diagnosa</th>
                    <th style="padding: 8px 12px;">Terapi/Obat</th>
                    <th class="text-center" style="padding: 8px 10px;">Status Pulang</th>
                    <th style="padding: 8px 10px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data_puskesmas as $index => $item)
                <tr class="align-middle" style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#fcfcfc' }};">
                    <td class="text-center" style="padding: 7px 6px; color: #555;">{{ $index + 1 }}</td>
                    <td class="text-center" style="padding: 7px 10px;">
                        <div>{{ substr($item['tgl_jam'], 0, 10) }}</div>
                        <div style="font-size: 10.5px; color: #777;">{{ substr($item['tgl_jam'], 11) }}</div>
                    </td>
                    <td class="text-center" style="padding: 7px 10px; font-weight: 500; color: #333;">{{ $item['no_rm'] }}</td>
                    <td class="text-center" style="padding: 7px 10px; color: #0073b7; font-weight: 500;">{{ $item['no_rk'] }}</td>
                    <td class="text-center" style="padding: 7px 10px;">
                        <span class="badge {{ $item['status_pasien'] == 'Pasien Baru' ? 'bg-success' : 'bg-secondary' }}" style="font-weight: 400; font-size: 10.5px; padding: 4px 6px;">
                            {{ $item['status_pasien'] }}
                        </span>
                    </td>
                    <td style="padding: 7px 12px; font-weight: 600; color: #333;">{{ $item['nama_pasien'] }}</td>
                    <td class="text-center" style="padding: 7px 10px; font-family: monospace; font-size: 11px;">{{ $item['nik'] }}</td>
                    <td class="text-center" style="padding: 7px 10px; font-family: monospace; font-size: 11px;">{{ $item['no_jkn'] }}</td>
                    <td class="text-center" style="padding: 7px 10px;">{{ $item['tgl_lahir'] }}</td>
                    <td class="text-center" style="padding: 7px 10px;">{{ $item['u_jk'] }}</td>
                    <td style="padding: 7px 12px; max-width: 250px; overflow: hidden; text-overflow: ellipsis;">{{ $item['alamat'] }}</td>
                    <td class="text-center" style="padding: 7px 10px;">{{ $item['no_hp'] }}</td>
                    <td class="text-center" style="padding: 7px 8px;">
                        @if($item['prolanis'] != '-')
                            <span class="badge bg-warning text-dark" style="font-size: 10px;">{{ $item['prolanis'] }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center" style="padding: 7px 8px;">
                        @if($item['prb'] == 'Ya')
                            <span class="badge bg-info text-white" style="font-size: 10px;">PRB</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center" style="padding: 7px 10px;">{{ $item['pembiaya'] }}</td>
                    <td class="text-center" style="padding: 7px 8px;">{{ $item['alasan'] }}</td>
                    <td style="padding: 7px 10px;">{{ $item['dokter'] }}</td>
                    <td style="padding: 7px 12px;">{{ $item['keluhan'] }}</td>
                    <td style="padding: 7px 12px;">{{ $item['diagnosa'] }}</td>
                    <td style="padding: 7px 12px;">{{ $item['terapi'] }}</td>
                    <td class="text-center" style="padding: 7px 10px;">{{ $item['status_pulang'] }}</td>
                    <td style="padding: 7px 10px;">{{ $item['keterangan'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="22" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-1"></i> Tidak ada data kunjungan pada periode dan filter yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Record Count Footer -->
    <div class="mt-2 text-muted" style="font-size: 11.5px;">
        Menampilkan <b>{{ $data_puskesmas->count() }}</b> data kunjungan pasien.
    </div>
</div>
@endsection
