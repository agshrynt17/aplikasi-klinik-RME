@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Laporan Data Kesakitan Berdasar Jenis & Usia')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Laporan Data Kesakitan Berdasar Jenis &amp; Usia</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Laporan Data Kesakitan Berdasar Jenis &amp; Usia</span>
        </div>
    </div>

    <!-- Cyan Divider Line (1:1 KONHAKES) -->
    <div style="height: 2px; background-color: #00c0ef; margin-top: 8px; margin-bottom: 15px;"></div>

    <!-- Filter Pencarian Card (1:1 KONHAKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px;">Filter Pencarian</div>
        
        <form method="GET" action="/laporan-eksternal/laporan-jenis-penyakit" id="filterForm">
            <div class="d-flex align-items-end flex-wrap gap-2">
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tahun</label>
                    <input type="text" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}" style="width: 110px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm" style="width: 140px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        @foreach($listBulan as $k => $v)
                            <option value="{{ $k }}" {{ $bulan == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Klinik</label>
                    <select name="klinik" class="form-select form-select-sm" style="width: 160px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Semua--</option>
                        @foreach($listKlinik as $id => $nama)
                            <option value="{{ $id }}" {{ $klinikId == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Penjamin</label>
                    <select name="penjamin" class="form-select form-select-sm" style="width: 160px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Semua--</option>
                        @foreach($listPenjamin as $id => $nama)
                            <option value="{{ $id }}" {{ $penjaminId == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div>
                    <a href="/laporan-eksternal/laporan-jenis-penyakit" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/jenis-penyakit/export-pdf?tahun={{ $tahun }}&bulan={{ $bulan }}&klinik={{ $klinikId }}&penjamin={{ $penjaminId }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;" target="_blank">
                        <i class="fas fa-file-pdf"></i> Cetak Laporan Pdf
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/jenis-penyakit/export-excel?tahun={{ $tahun }}&bulan={{ $bulan }}&klinik={{ $klinikId }}&penjamin={{ $penjaminId }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;">
                        <i class="fas fa-file-excel"></i> Cetak Laporan Excel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Container Card (1:1 KONHAKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px;">
        <div class="table-responsive" style="max-height: 650px; overflow-y: auto;">
            <table class="table table-bordered align-middle mb-0" style="font-size: 11px; border-color: #d2d6de; width: 100%; white-space: nowrap;">
                <thead style="background-color: #e4e7ea; color: #333; position: sticky; top: 0; z-index: 10;">
                    <!-- Row 1: Main Headers -->
                    <tr>
                        <th rowspan="3" class="text-center" width="35" style="border-color: #d2d6de; vertical-align: middle;">No</th>
                        <th rowspan="3" style="border-color: #d2d6de; vertical-align: middle; min-width: 170px;">Jenis Penyakit</th>
                        <th colspan="22" class="text-center" style="border-color: #d2d6de; vertical-align: middle; padding: 5px;">Jumlah Kasus Baru (Umur dan Jenis Kelamin)</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; vertical-align: middle; padding: 5px;">Jumlah Kasus Lama</th>
                        <th rowspan="3" class="text-center" width="70" style="border-color: #d2d6de; vertical-align: middle;">Jumlah Total</th>
                    </tr>
                    <!-- Row 2: Age Categories -->
                    <tr>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">0-7 Hr</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">8-28 Hr</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">1-11 Bln</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">1-4 Th</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">5-9 Th</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">10-14 Th</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">15-19 Th</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">20-44 Th</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">45-59 Th</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">&gt;60 Th</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px;">Jumlah</th>
                        <th colspan="2" class="text-center" style="border-color: #d2d6de; padding: 4px; font-size: 10.5px; border-top: none;"></th>
                    </tr>
                    <!-- Row 3: Gender L/P Subheaders -->
                    <tr>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">L</th>
                        <th class="text-center" width="22" style="border-color: #d2d6de; padding: 3px; font-size: 10px;">P</th>
                        <th class="text-center" width="25" style="border-color: #d2d6de; padding: 3px; font-size: 10px; font-weight: bold;">L</th>
                        <th class="text-center" width="25" style="border-color: #d2d6de; padding: 3px; font-size: 10px; font-weight: bold;">P</th>
                        <th class="text-center" width="28" style="border-color: #d2d6de; padding: 3px; font-size: 10px; font-weight: bold;">L</th>
                        <th class="text-center" width="28" style="border-color: #d2d6de; padding: 3px; font-size: 10px; font-weight: bold;">P</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_penyakit as $row)
                    <tr>
                        <td class="text-center" style="vertical-align: middle; color: #555;">{{ $row['no'] }}</td>
                        <td style="vertical-align: middle; color: #333; font-weight: 500;">{{ $row['jenis_penyakit'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_0_7_hr_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_0_7_hr_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_8_28_hr_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_8_28_hr_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_1_11_bln_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_1_11_bln_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_1_4_th_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_1_4_th_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_5_9_th_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_5_9_th_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_10_14_th_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_10_14_th_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_15_19_th_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_15_19_th_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_20_44_th_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_20_44_th_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_45_59_th_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_45_59_th_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_gt_60_th_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $row['baru_gt_60_th_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle; font-weight: 600; background-color: #fafafa;">{{ $row['jumlah_baru_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle; font-weight: 600; background-color: #fafafa;">{{ $row['jumlah_baru_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle; font-weight: 500;">{{ $row['lama_l'] }}</td>
                        <td class="text-center" style="vertical-align: middle; font-weight: 500;">{{ $row['lama_p'] }}</td>
                        <td class="text-center" style="vertical-align: middle; font-weight: 700; color: #222; background-color: #f7f9fa;">{{ $row['jumlah_total'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
