@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Laporan Data Kesakitan Terbanyak')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Laporan Data Kesakitan Terbanyak</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Laporan Data Kesakitan Terbanyak</span>
        </div>
    </div>

    <!-- Cyan Divider Line (1:1 PROFASKES) -->
    <div style="height: 2px; background-color: #00c0ef; margin-top: 8px; margin-bottom: 15px;"></div>

    <!-- Filter Pencarian Card (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px;">Filter Pencarian</div>
        
        <form method="GET" action="/laporan-eksternal/laporan-diagnosa-terbanyak" id="filterForm">
            <!-- Row 1: Tanggal, Opsi Tampilan & Action Buttons (1:1 PROFASKES) -->
            <div class="d-flex align-items-end flex-wrap gap-2 mb-1">
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tgl Awal</label>
                    <input type="text" name="tgl_awal" class="form-control form-control-sm" value="{{ $tglAwalFormatted }}" style="width: 140px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tgl Akhir</label>
                    <input type="text" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tglAkhirFormatted }}" style="width: 140px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;" placeholder="dd-mm-yyyy">
                </div>
                <div>
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Opsi Tampilan Diagnosa</label>
                    <select name="opsi_tampilan" class="form-select form-select-sm" style="width: 260px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="10" {{ $opsiTampilan == '10' ? 'selected' : '' }}>Tampilkan 10 Diagnosa Terbanyak</option>
                        <option value="20" {{ $opsiTampilan == '20' ? 'selected' : '' }}>Tampilkan 20 Diagnosa Terbanyak</option>
                        <option value="30" {{ $opsiTampilan == '30' ? 'selected' : '' }}>Tampilkan 30 Diagnosa Terbanyak</option>
                        <option value="50" {{ $opsiTampilan == '50' ? 'selected' : '' }}>Tampilkan 50 Diagnosa Terbanyak</option>
                        <option value="all" {{ $opsiTampilan == 'all' ? 'selected' : '' }}>Tampilkan Semua</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div>
                    <a href="/laporan-eksternal/laporan-diagnosa-terbanyak" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/diagnosa-terbanyak/export-pdf?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&opsi_tampilan={{ $opsiTampilan }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;" target="_blank">
                        <i class="fas fa-file-pdf"></i> Cetak Laporan Pdf
                    </a>
                </div>
                <div>
                    <a href="/laporan-eksternal/diagnosa-terbanyak/export-excel?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&opsi_tampilan={{ $opsiTampilan }}" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; height: 32px; font-size: 12px; font-weight: 500; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px; text-decoration: none;">
                        <i class="fas fa-file-excel"></i> Cetak Laporan Excel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Total (1:1 PROFASKES) -->
    <div style="font-size: 12.5px; font-weight: 700; color: #222; margin-top: 20px; margin-bottom: 12px;">
        Total Kunjungan Pasien : {{ number_format($total_kunjungan) }} , Total Diagnosa Pasien : {{ number_format($total_diagnosa) }}
    </div>

    <!-- Table Container Card (1:1 PROFASKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px;">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" style="font-size: 11.5px; border-color: #d2d6de; width: 100%;">
                <thead style="background-color: #e4e7ea; color: #333;">
                    <tr>
                        <th class="text-center" width="45" style="padding: 7px 4px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">No</th>
                        <th style="padding: 7px 10px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Jenis Penyakit</th>
                        <th width="100" style="padding: 7px 10px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">ICD 10</th>
                        <th class="text-end" width="180" style="padding: 7px 15px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Jumlah Kasus Baru</th>
                        <th class="text-end" width="180" style="padding: 7px 15px; font-weight: 600; border-color: #d2d6de; vertical-align: middle;">Jumlah Kasus Lama</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_diagnosa as $item)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">{{ $item['no'] }}</td>
                        <td style="vertical-align: middle; color: #333;">{{ $item['jenis_penyakit'] }}</td>
                        <td style="vertical-align: middle; font-family: monospace, sans-serif; font-size: 11px;">{{ $item['icd_10'] }}</td>
                        <td class="text-end" style="vertical-align: middle; padding-right: 15px;">{{ number_format($item['kasus_baru']) }}</td>
                        <td class="text-end" style="vertical-align: middle; padding-right: 15px;">{{ number_format($item['kasus_lama']) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4" style="font-size: 12px;">
                            Tidak ada data diagnosa pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
