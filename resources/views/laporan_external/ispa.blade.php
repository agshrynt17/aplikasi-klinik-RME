@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Laporan Register Harian Penderita ISPA')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Laporan Register Harian Penderita ISPA</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Laporan Register Harian Penderita ISPA</span>
        </div>
    </div>

    <!-- Cyan Divider Line (1:1 PROFASKES) -->
    <div style="height: 2px; background-color: #00c0ef; margin-top: 8px; margin-bottom: 15px;"></div>

    <!-- Filter Pencarian Card (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px;">Filter Pencarian</div>
        
        <form method="GET" action="/laporan-eksternal/laporan-diagnosa-ispa" id="filterForm">
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
                    <a href="/laporan-eksternal/laporan-diagnosa-ispa" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
                <div>
                    <a href="/laporan-external/ispa/export-pdf?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;" target="_blank">
                        <i class="fas fa-file-pdf"></i> Cetak Laporan Pdf
                    </a>
                </div>
                <div>
                    <a href="/laporan-external/ispa/export-excel?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;">
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
                        <option value="Klari" {{ $kecamatan == 'Klari' ? 'selected' : '' }}>Klari</option>
                        <option value="Karawang Barat" {{ $kecamatan == 'Karawang Barat' ? 'selected' : '' }}>Karawang Barat</option>
                        <option value="Karawang Timur" {{ $kecamatan == 'Karawang Timur' ? 'selected' : '' }}>Karawang Timur</option>
                        <option value="Rengasdengklok" {{ $kecamatan == 'Rengasdengklok' ? 'selected' : '' }}>Rengasdengklok</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Desa/Kelurahan</label>
                    <select name="desa" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Desa--</option>
                        <option value="Telagasari" {{ $desa == 'Telagasari' ? 'selected' : '' }}>Telagasari</option>
                        <option value="Talagamulya" {{ $desa == 'Talagamulya' ? 'selected' : '' }}>Talagamulya</option>
                        <option value="Pasirkamuning" {{ $desa == 'Pasirkamuning' ? 'selected' : '' }}>Pasirkamuning</option>
                        <option value="Cariumulya" {{ $desa == 'Cariumulya' ? 'selected' : '' }}>Cariumulya</option>
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
                        <th rowspan="2" class="text-center" width="75" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Tanggal</th>
                        <th rowspan="2" class="text-center" width="105" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No Pendaftaran</th>
                        <th rowspan="2" class="text-center" width="130" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Nama</th>
                        <th rowspan="2" class="text-center" width="80" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No RM</th>
                        <th rowspan="2" class="text-center" width="115" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">NIK</th>
                        <th rowspan="2" class="text-center" width="120" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Alamat</th>
                        <th rowspan="2" class="text-center" width="75" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Tgl Lahir</th>
                        <th rowspan="2" class="text-center" width="45" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Umur</th>
                        <th rowspan="2" class="text-center" width="75" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Jenis Kelamin</th>
                        <th colspan="2" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Kunjungan</th>
                        <th rowspan="2" class="text-center" width="100" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Gejala Batuk/Sukar Bernapas</th>
                        <th rowspan="2" class="text-center" width="90" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Frekuensi Napas/TDKK</th>
                        <th colspan="3" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Klasifikasi</th>
                        <th colspan="2" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Tindak Lanjut</th>
                        <th colspan="2" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Antibiotik</th>
                        <th colspan="4" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Kondisi Saat Kunjungan Ulang</th>
                        <th colspan="2" class="text-center" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">ISPA &gt; 5 Tahun</th>
                    </tr>
                    <tr>
                        <!-- Kunjungan -->
                        <th class="text-center" width="45" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Baru</th>
                        <th class="text-center" width="45" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Ulang</th>
                        <!-- Klasifikasi -->
                        <th class="text-center" width="40" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">BBP</th>
                        <th class="text-center" width="35" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">P</th>
                        <th class="text-center" width="40" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">PB</th>
                        <!-- Tindak Lanjut -->
                        <th class="text-center" width="45" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Rajal</th>
                        <th class="text-center" width="45" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Rujuk</th>
                        <!-- Antibiotik -->
                        <th class="text-center" width="35" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Ya</th>
                        <th class="text-center" width="40" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Tidak</th>
                        <!-- Kondisi Saat Kunjungan Ulang -->
                        <th class="text-center" width="60" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Membaik</th>
                        <th class="text-center" width="45" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Tetap</th>
                        <th class="text-center" width="60" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Memburuk</th>
                        <th class="text-center" width="35" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Ket</th>
                        <!-- ISPA > 5 Tahun -->
                        <th class="text-center" width="80" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Bukan Pneumonia</th>
                        <th class="text-center" width="75" style="padding: 6px 4px; font-weight: 600; border-color: #d2d6de;">Pneumonia</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_ispa as $index => $item)
                    @php
                        $pasien = $item->pendaftaran->pasien ?? null;
                        $umur = $pasien && $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->age : '-';
                        $jk = in_array($pasien->jenis_kelamin ?? '', ['L', 'Laki-Laki', 'Laki-laki']) ? 'L' : 'P';
                        $isBaru = ($item->pendaftaran->jenis_kunjungan ?? '') == 'Baru' || ($pasien->is_pasien_baru ?? false);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_asesmen)->format('d-m-Y') }}</td>
                        <td>{{ $item->pendaftaran->nomor_registrasi ?? '-' }}</td>
                        <td>{{ strtoupper($pasien->nama_pasien ?? '-') }}</td>
                        <td>{{ $pasien->no_rm ?? '-' }}</td>
                        <td>{{ $pasien->nik ?? '-' }}</td>
                        <td>{{ $pasien->alamat ?? '-' }}</td>
                        <td class="text-center">{{ $pasien && $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">{{ $umur }}</td>
                        <td class="text-center">{{ $jk }}</td>
                        <!-- Kunjungan -->
                        <td class="text-center">{{ $isBaru ? '✓' : '-' }}</td>
                        <td class="text-center">{{ !$isBaru ? '✓' : '-' }}</td>
                        <!-- Gejala & Frekuensi -->
                        <td>{{ $item->keluhan_utama ?: 'Batuk' }}</td>
                        <td class="text-center">{{ $item->rr ? $item->rr . 'x/m' : '-' }}</td>
                        <!-- Klasifikasi -->
                        <td class="text-center">✓</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <!-- Tindak Lanjut -->
                        <td class="text-center">✓</td>
                        <td class="text-center">-</td>
                        <!-- Antibiotik -->
                        <td class="text-center">✓</td>
                        <td class="text-center">-</td>
                        <!-- Kondisi Ulang -->
                        <td class="text-center">✓</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <!-- ISPA > 5 Th -->
                        <td class="text-center">{{ is_numeric($umur) && $umur > 5 ? '✓' : '-' }}</td>
                        <td class="text-center">-</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="27" class="text-center text-muted py-4" style="font-size: 12px;">
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
