@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'REGISTER LAYANAN REHIDRASI ORAL AKTIF')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">REGISTER LAYANAN REHIDRASI ORAL AKTIF</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">REGISTER LAYANAN REHIDRASI ORAL AKTIF</span>
        </div>
    </div>

    <!-- Cyan Divider Line (1:1 PROFASKES) -->
    <div style="height: 2px; background-color: #00c0ef; margin-top: 8px; margin-bottom: 15px;"></div>

    <!-- Filter Pencarian Card (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px;">Filter Pencarian</div>
        
        <form method="GET" action="/laporan-eksternal/laporan-diagnosa-gea" id="filterForm">
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
                    <a href="/laporan-eksternal/laporan-diagnosa-gea" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/gea/export-pdf?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;" target="_blank">
                        <i class="fas fa-file-pdf"></i> Cetak Laporan Pdf
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/gea/export-excel?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;">
                        <i class="fas fa-file-excel"></i> Cetak Laporan Excel
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
                        <option value="Telagasari" {{ $kecamatan == 'Telagasari' ? 'selected' : '' }}>Telagasari</option>
                        <option value="Cikampek" {{ $kecamatan == 'Cikampek' ? 'selected' : '' }}>Cikampek</option>
                        <option value="Kotabaru" {{ $kecamatan == 'Kotabaru' ? 'selected' : '' }}>Kotabaru</option>
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
                        <option value="Cikampek Barat" {{ $desa == 'Cikampek Barat' ? 'selected' : '' }}>Cikampek Barat</option>
                        <option value="Cikampek Kota" {{ $desa == 'Cikampek Kota' ? 'selected' : '' }}>Cikampek Kota</option>
                        <option value="Wancimekar" {{ $desa == 'Wancimekar' ? 'selected' : '' }}>Wancimekar</option>
                        <option value="Karyamukti" {{ $desa == 'Karyamukti' ? 'selected' : '' }}>Karyamukti</option>
                        <option value="Telagasari" {{ $desa == 'Telagasari' ? 'selected' : '' }}>Telagasari</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Container Card (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px; margin-top: 20px;">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" style="font-size: 11px; border-color: #d2d6de; width: 100%;">
                <thead style="background-color: #e4e7ea; color: #333;">
                    <tr>
                        <th rowspan="2" class="text-center" width="30" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No</th>
                        <th rowspan="2" class="text-center" width="75" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Tanggal Berobat</th>
                        <th rowspan="2" class="text-center" width="105" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No Pendaftaran</th>
                        <th rowspan="2" class="text-center" width="140" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Nama</th>
                        <th rowspan="2" class="text-center" width="80" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No RM</th>
                        <th rowspan="2" class="text-center" width="115" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">NIK</th>
                        <th rowspan="2" class="text-center" width="180" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Alamat</th>
                        <th rowspan="2" class="text-center" width="75" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Tgl Lahir</th>
                        <th rowspan="2" class="text-center" width="65" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Umur</th>
                        <th rowspan="2" class="text-center" width="75" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Jenis Kelamin</th>
                        <th rowspan="2" class="text-center" width="220" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Resep</th>
                        <th colspan="3" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Derajat Dehidrasi</th>
                        <th colspan="3" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Jumlah Pemakaian</th>
                        <th colspan="4" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Rencana Terapi</th>
                    </tr>
                    <tr>
                        <!-- Derajat Dehidrasi -->
                        <th class="text-center" width="50" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Ringan</th>
                        <th class="text-center" width="50" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Sedang</th>
                        <th class="text-center" width="50" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Berat</th>
                        <!-- Jumlah Pemakaian -->
                        <th class="text-center" width="50" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Oralit</th>
                        <th class="text-center" width="50" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Zinc</th>
                        <th class="text-center" width="60" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Konseling</th>
                        <!-- Rencana Terapi -->
                        <th class="text-center" width="35" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">A</th>
                        <th class="text-center" width="35" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">B</th>
                        <th class="text-center" width="35" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">C</th>
                        <th class="text-center" width="40" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_gea as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $item['tanggal_berobat'] }}</td>
                        <td>{{ $item['no_pendaftaran'] }}</td>
                        <td>{{ strtoupper($item['nama']) }}</td>
                        <td>{{ $item['no_rm'] }}</td>
                        <td>{{ $item['nik'] }}</td>
                        <td style="font-size: 10px; line-height: 1.3;">{{ $item['alamat'] }}</td>
                        <td class="text-center">{{ $item['tanggal_lahir'] }}</td>
                        <td class="text-center">{{ $item['umur'] }}</td>
                        <td class="text-center">{{ $item['jenis_kelamin'] }}</td>
                        <td style="font-size: 10.5px; line-height: 1.3;">{{ $item['resep'] }}</td>
                        <td class="text-center">{{ $item['derajat_ringan'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['derajat_sedang'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['derajat_berat'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['oralit'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['zinc'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['konseling'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['rencana_a'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['rencana_b'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['rencana_c'] ?: '-' }}</td>
                        <td class="text-center">{{ $item['ket'] ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="21" class="text-center text-muted py-4" style="font-size: 12px;">
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
