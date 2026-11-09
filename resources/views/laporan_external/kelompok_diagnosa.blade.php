@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'LAPORAN KUNJUNGAN BERDASARKAN KELOMPOK DIAGNOSA')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0; text-transform: uppercase;">LAPORAN KUNJUNGAN BERDASARKAN KELOMPOK DIAGNOSA</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999; text-transform: uppercase;">LAPORAN KUNJUNGAN BERDASARKAN KELOMPOK DIAGNOSA</span>
        </div>
    </div>

    <!-- Cyan Divider Line (1:1 PROFASKES) -->
    <div style="height: 2px; background-color: #00c0ef; margin-top: 8px; margin-bottom: 15px;"></div>

    <!-- Filter Pencarian Card (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px;">Filter Pencarian</div>
        
        <form method="GET" action="/laporan-eksternal/laporan-kelompok-diagnosa" id="filterForm">
            <!-- Row 1: Tanggal, Kelompok Diagnosa & Action Buttons (1:1 PROFASKES) -->
            <div class="d-flex align-items-end flex-wrap gap-2 mb-3">
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tgl Awal</label>
                    <input type="text" name="tgl_awal" class="form-control form-control-sm" value="{{ $tglAwalFormatted }}" style="width: 130px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tgl Akhir</label>
                    <input type="text" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tglAkhirFormatted }}" style="width: 130px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kelompok Diagnosa</label>
                    <select name="kelompok_diagnosa" class="form-select form-select-sm" style="width: 260px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">Pilih...</option>
                        @foreach($listKelompok as $kel)
                            <option value="{{ $kel }}" {{ $kelompokDiagnosa == $kel ? 'selected' : '' }}>{{ $kel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div>
                    <a href="/laporan-eksternal/laporan-kelompok-diagnosa" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/kelompok-diagnosa/export-pdf?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&kelompok_diagnosa={{ urlencode($kelompokDiagnosa) }}&provinsi={{ urlencode($provinsi) }}&kabupaten={{ urlencode($kabupaten) }}&kecamatan={{ urlencode($kecamatan) }}&desa={{ urlencode($desa) }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;" target="_blank">
                        <i class="fas fa-file-pdf"></i> Cetak Laporan Pdf
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/kelompok-diagnosa/export-excel?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&kelompok_diagnosa={{ urlencode($kelompokDiagnosa) }}&provinsi={{ urlencode($provinsi) }}&kabupaten={{ urlencode($kabupaten) }}&kecamatan={{ urlencode($kecamatan) }}&desa={{ urlencode($desa) }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;">
                        <i class="fas fa-file-excel"></i> Cetak Laporan Excel
                    </a>
                </div>
            </div>

            <!-- Row 2: Wilayah Dropdowns (1:1 PROFASKES) -->
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Provinsi</label>
                    <select name="provinsi" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Propinsi--</option>
                        @foreach($listProvinsi as $p)
                            <option value="{{ $p }}" {{ $provinsi == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kabupaten/Kota</label>
                    <select name="kabupaten" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Kabupaten--</option>
                        @foreach($listKabupaten as $k)
                            <option value="{{ $k }}" {{ $kabupaten == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kecamatan</label>
                    <select name="kecamatan" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Kecamatan--</option>
                        @foreach($listKecamatan as $kc)
                            <option value="{{ $kc }}" {{ $kecamatan == $kc ? 'selected' : '' }}>{{ $kc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Desa/Kelurahan</label>
                    <select name="desa" class="form-select form-select-sm" style="font-size: 12px; height: 32px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih Desa--</option>
                        @foreach($listDesa as $d)
                            <option value="{{ $d }}" {{ $desa == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Container Card (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px;">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" style="font-size: 11.5px; border-color: #d2d6de; width: 100%;">
                <thead style="background-color: #e4e7ea; color: #333;">
                    <tr>
                        <th class="text-center" width="40" style="padding: 7px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No</th>
                        <th class="text-center" width="105" style="padding: 7px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Tanggal Berobat</th>
                        <th width="120" style="padding: 7px 8px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No Pendaftaran</th>
                        <th width="150" style="padding: 7px 8px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Nama</th>
                        <th width="90" style="padding: 7px 8px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No RM</th>
                        <th width="130" style="padding: 7px 8px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">NIK</th>
                        <th class="text-center" width="65" style="padding: 7px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Umur</th>
                        <th class="text-center" width="85" style="padding: 7px 6px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Jenis Kelamin</th>
                        <th style="padding: 7px 8px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Alamat</th>
                        <th width="95" style="padding: 7px 8px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No HP</th>
                        <th width="95" style="padding: 7px 8px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Penjamin</th>
                        <th width="160" style="padding: 7px 8px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Diagnosa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_kunjungan as $item)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">{{ $item['no'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $item['tanggal_berobat'] }}</td>
                        <td style="vertical-align: middle; font-weight: 500;">{{ $item['no_pendaftaran'] }}</td>
                        <td style="vertical-align: middle; font-weight: 600; color: #333;">{{ $item['nama'] }}</td>
                        <td style="vertical-align: middle;">{{ $item['no_rm'] }}</td>
                        <td style="vertical-align: middle; font-family: monospace, sans-serif; font-size: 11px;">{{ $item['nik'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $item['umur'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $item['jenis_kelamin'] }}</td>
                        <td style="vertical-align: middle; font-size: 10.5px; color: #555;">{{ $item['alamat'] }}</td>
                        <td style="vertical-align: middle;">{{ $item['no_hp'] }}</td>
                        <td style="vertical-align: middle;">{{ $item['penjamin'] }}</td>
                        <td style="vertical-align: middle; color: #222;">{{ $item['diagnosa'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4" style="font-size: 12px;">
                            @if(!$kelompokDiagnosa)
                                Silakan pilih kelompok diagnosa dan klik tombol Cari untuk menampilkan data kunjungan.
                            @else
                                Tidak ada data kunjungan untuk kelompok diagnosa "{{ $kelompokDiagnosa }}" pada periode yang dipilih.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
