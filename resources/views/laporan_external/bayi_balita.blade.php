@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Laporan Kunjungan Bayi & Balita')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Laporan Kunjungan Bayi &amp; Balita</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Laporan Kunjungan Bayi &amp; Balita</span>
        </div>
    </div>

    <!-- Cyan Divider Line (1:1 KONHAKES) -->
    <div style="height: 2px; background-color: #00c0ef; margin-top: 8px; margin-bottom: 15px;"></div>

    <!-- Filter Pencarian Card (1:1 KONHAKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px;">Filter Pencarian</div>
        
        <form method="GET" action="/laporan-eksternal/laporan-kunjungan-bayi" id="filterForm">
            <!-- Row 1: Tanggal & Action Buttons -->
            <div class="d-flex align-items-end flex-wrap gap-2 mb-3">
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tgl Awal</label>
                    <input type="text" name="tgl_awal" class="form-control form-control-sm" value="{{ $tglAwalFormatted }}" style="width: 140px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tgl Akhir</label>
                    <input type="text" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tglAkhirFormatted }}" style="width: 140px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div>
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div>
                    <a href="/laporan-eksternal/laporan-kunjungan-bayi" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/kunjungan-bayi/export-pdf?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&provinsi={{ urlencode($provinsi) }}&kabupaten={{ urlencode($kabupaten) }}&kecamatan={{ urlencode($kecamatan) }}&desa={{ urlencode($desa) }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;" target="_blank">
                        <i class="fas fa-print"></i> Cetak Laporan (Pdf)
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/kunjungan-bayi/export-excel?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&provinsi={{ urlencode($provinsi) }}&kabupaten={{ urlencode($kabupaten) }}&kecamatan={{ urlencode($kecamatan) }}&desa={{ urlencode($desa) }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;">
                        <i class="fas fa-file-excel"></i> Cetak Laporan (Xls)
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
                        <option value="Telagasari" {{ $desa == 'Telagasari' ? 'selected' : '' }}>Telagasari</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Container Card (1:1 KONHAKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px; margin-top: 20px;">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" style="font-size: 11px; border-color: #d2d6de; width: 100%;">
                <thead style="background-color: #e4e7ea; color: #333;">
                    <tr>
                        <th class="text-center" width="35" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No</th>
                        <th class="text-center" width="75" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Tanggal</th>
                        <th width="135" style="padding: 6px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">NIK</th>
                        <th width="165" style="padding: 6px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Nama Anak</th>
                        <th width="125" style="padding: 6px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Nama Ibu</th>
                        <th class="text-center" width="55" style="padding: 6px 2px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Usia</th>
                        <th width="240" style="padding: 6px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Alamat</th>
                        <th width="75" style="padding: 6px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Telp</th>
                        <th width="180" style="padding: 6px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Diagnosa</th>
                        <th width="85" style="padding: 6px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_kunjungan as $index => $item)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">{{ $index + 1 }}</td>
                        <td class="text-center" style="vertical-align: middle; white-space: nowrap;">{{ $item['tanggal'] }}</td>
                        <td style="vertical-align: middle; font-family: monospace, sans-serif; font-size: 11px;">{{ $item['nik'] }}</td>
                        <td style="vertical-align: middle; font-weight: 500;">{{ strtoupper($item['nama_anak']) }}</td>
                        <td style="vertical-align: middle;">{{ $item['nama_ibu'] }}</td>
                        <td class="text-center" style="vertical-align: middle; width: 55px; white-space: normal; line-height: 1.2;">{{ $item['usia'] }}</td>
                        <td style="vertical-align: middle; font-size: 10px; line-height: 1.35;">{{ $item['alamat'] }}</td>
                        <td style="vertical-align: middle;">{{ $item['telp'] ?: '' }}</td>
                        <td style="vertical-align: middle; font-size: 10.5px; line-height: 1.35;">{!! nl2br(e($item['diagnosa'])) !!}</td>
                        <td style="vertical-align: middle;">{{ $item['keterangan'] ?: '' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4" style="font-size: 12px;">
                            Tidak ada data yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

