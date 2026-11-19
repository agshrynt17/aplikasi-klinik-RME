@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .survey-kepuasan-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .konhakes-title {
        font-size: 19px;
        font-weight: 500;
        color: #333;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }

    /* Filter Card */
    .card-filter-waktu {
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #00c0ef !important;
        border-radius: 2px;
        padding: 12px 16px;
        margin-bottom: 15px;
    }
    .filter-section-title {
        font-size: 11.5px;
        color: #555;
        margin-bottom: 6px;
        font-weight: 600;
    }
    .filter-label {
        font-size: 11.5px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
        display: block;
    }
    .input-date-custom {
        height: 32px;
        font-size: 12px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        padding: 4px 10px;
        width: 140px;
        color: #555;
        background-color: #fff;
    }
    .input-date-custom:focus {
        border-color: #3c8dbc;
        outline: 0;
    }

    .btn-search-blue {
        background-color: #3c8dbc;
        border: 1px solid #367fa9;
        color: #fff !important;
        font-size: 12px;
        height: 32px;
        padding: 0 14px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-search-blue:hover {
        background-color: #367fa9;
    }
    .btn-reset-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        font-size: 12px;
        height: 32px;
        padding: 0 12px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    .btn-reset-light:hover {
        background-color: #f4f4f4;
    }

    .btn-orange-pdf {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        height: 32px;
        padding: 0 14px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-orange-pdf:hover {
        background-color: #d68407;
    }

    /* Table Survey */
    .table-survey {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 11px;
    }
    .table-survey th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 6px 8px;
        font-weight: 700;
        color: #333;
        font-size: 11px;
        text-align: center;
    }
    .table-survey td {
        border: 1px solid #d2d6de;
        padding: 5px 8px;
        vertical-align: middle;
        color: #333;
        font-size: 11px;
    }
    .table-survey tbody tr:hover {
        background-color: #f5f5f5;
    }

    .table-unsur-ref {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }
    .table-unsur-ref th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 4px 8px;
        font-weight: 700;
        color: #333;
        text-align: left;
    }
    .table-unsur-ref td {
        border: 1px solid #d2d6de;
        padding: 3px 8px;
        color: #333;
    }

    .banner-ikm {
        background-color: #e0e0e0;
        border: 1px solid #d2d6de;
        padding: 8px 15px;
        text-align: center;
        font-weight: 700;
        font-size: 12.5px;
        color: #333;
        margin: 15px 0 12px 0;
    }
</style>

<div class="survey-kepuasan-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">PENGOLAHAN DATA SURVEI KEPUASAN MASYARAKAT</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/laporan-pmkp/laporan-survey-kepuasan">PENGOLAHAN DATA SURVEI KEPUASAN MASYARAKAT</a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card-filter-waktu">
        <div class="filter-section-title">Filter Pencarian</div>
        <form method="GET" action="/laporan-pmkp/laporan-survey-kepuasan" class="d-flex align-items-flex-end flex-wrap gap-2">
            <div>
                <label class="filter-label">Tgl Awal</label>
                <input type="text" name="tgl_awal" class="input-date-custom" value="{{ $tglAwalInput }}">
            </div>
            <div>
                <label class="filter-label">Tgl Akhir</label>
                <input type="text" name="tgl_akhir" class="input-date-custom" value="{{ $tglAkhirInput }}">
            </div>
            <div class="d-flex align-items-center gap-1 pt-3">
                <button type="submit" class="btn-search-blue" title="Cari">
                    <i class="fas fa-search"></i>
                </button>
                <a href="/laporan-pmkp/laporan-survey-kepuasan" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
                <a href="/laporan-pmkp/cetak-survey-kepuasan-pdf?tgl_awal={{ $tglAwalInput }}&tgl_akhir={{ $tglAkhirInput }}" target="_blank" class="btn-orange-pdf">
                    <i class="fas fa-print"></i> Cetak Laporan Pdf
                </a>
            </div>
        </form>
    </div>

    {{-- Main Survey Table --}}
    <div class="table-responsive">
        <table class="table-survey">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th style="width: 140px;">Kode RM</th>
                    <th style="width: 140px;">Ruang</th>
                    <th style="width: 60px;">U1</th>
                    <th style="width: 60px;">U2</th>
                    <th style="width: 60px;">U3</th>
                    <th style="width: 60px;">U4</th>
                    <th style="width: 60px;">U5</th>
                    <th style="width: 60px;">U6</th>
                    <th style="width: 60px;">U7</th>
                    <th style="width: 60px;">U8</th>
                    <th style="width: 60px;">U9</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surveys as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $item->no_rm ?? '-' }}</td>
                    <td>{{ $item->ruang ?? 'Poli Umum' }}</td>
                    <td style="text-align: center;">{{ $item->u1 ?? 0 }}</td>
                    <td style="text-align: center;">{{ $item->u2 ?? 0 }}</td>
                    <td style="text-align: center;">{{ $item->u3 ?? 0 }}</td>
                    <td style="text-align: center;">{{ $item->u4 ?? 0 }}</td>
                    <td style="text-align: center;">{{ $item->u5 ?? 0 }}</td>
                    <td style="text-align: center;">{{ $item->u6 ?? 0 }}</td>
                    <td style="text-align: center;">{{ $item->u7 ?? 0 }}</td>
                    <td style="text-align: center;">{{ $item->u8 ?? 0 }}</td>
                    <td style="text-align: center;">{{ $item->u9 ?? 0 }}</td>
                </tr>
                @endforeach

                {{-- Summary Rows --}}
                <tr>
                    <td colspan="3" style="text-align: left; padding-left: 10px;">Nilai Unsur</td>
                    @for($i = 1; $i <= 9; $i++)
                        <td style="text-align: center;">{{ $nilaiUnsur[$i] ?? 0 }}</td>
                    @endfor
                </tr>
                <tr>
                    <td colspan="3" style="text-align: left; padding-left: 10px;">NRR Unsur</td>
                    @for($i = 1; $i <= 9; $i++)
                        <td style="text-align: center;">{{ number_format($nrrUnsur[$i] ?? 0, 3) }}</td>
                    @endfor
                </tr>
                <tr>
                    <td colspan="3" style="text-align: left; padding-left: 10px;">NRR Tertimbang Unsur</td>
                    @for($i = 1; $i <= 9; $i++)
                        <td style="text-align: center;">
                            {{ number_format($nrrTertimbang[$i] ?? 0, 3) }}
                            @if($i == 9)
                                <div style="font-size: 10.5px; color: #555; margin-top: 2px;">{{ number_format($totalNrrTertimbang ?? 0, 3) }} *)</div>
                            @endif
                        </td>
                    @endfor
                </tr>
                <tr>
                    <td colspan="3" style="text-align: left; padding-left: 10px;">IKM</td>
                    <td colspan="9" style="text-align: left; padding-left: 15px;">
                        {{ number_format($ikm ?? 0, 3) }} **)
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Keterangan Section --}}
    <div class="mt-3">
        <div class="fw-bold mb-2" style="font-size: 11.5px; color: #333;">Keterangan:</div>
        <div class="row">
            {{-- Left Column: Explanations --}}
            <div class="col-md-6 col-sm-12">
                <div style="font-size: 11px; line-height: 1.6; color: #333;">
                    <div><strong>U1 s/d U9</strong> : Unsur Unsur Pelayanan</div>
                    <div><strong>NRR</strong> : Nilai Rata-Rata</div>
                    <div><strong>IKM</strong> : Indeks Kepuasan Masyarakat</div>
                    <div><strong>*)</strong> : Jumlah NRR Per Unsur</div>
                    <div><strong>**)</strong> : Jumlah NRR Tertimbang x 25</div>
                    <div class="mt-1"><strong>NRR Per Unsur</strong> : Jumlah nilai per unsur dibagi jumlah kuesioner terisi</div>
                    <div><strong>NRR Tertimbang per Unsur</strong> : NRR Per Unsur x 0.11</div>
                </div>
            </div>

            {{-- Right Column: UNSUR PELAYANAN Table --}}
            <div class="col-md-6 col-sm-12">
                <table class="table-unsur-ref">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">No</th>
                            <th>UNSUR PELAYANAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="text-align: center;">U1</td><td>: Persyaratan</td></tr>
                        <tr><td style="text-align: center;">U2</td><td>: Sistem, Mekanisme, dan Prosedur</td></tr>
                        <tr><td style="text-align: center;">U3</td><td>: Waktu Penyelesaian</td></tr>
                        <tr><td style="text-align: center;">U4</td><td>: Biaya/Tarif</td></tr>
                        <tr><td style="text-align: center;">U5</td><td>: Produk Spesifikasi Jenis Pelayanan</td></tr>
                        <tr><td style="text-align: center;">U6</td><td>: Kompetensi Pelaksana</td></tr>
                        <tr><td style="text-align: center;">U7</td><td>: Perilaku Pelaksana</td></tr>
                        <tr><td style="text-align: center;">U8</td><td>: Penanganan Pengaduan, Saran dan Masukan</td></tr>
                        <tr><td style="text-align: center;">U9</td><td>: Sarana dan prasarana</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Full Width IKM Banner --}}
    <div class="banner-ikm">
        IKM {{ $namaKlinik ?? 'KLINIK TELAGASARI 24 JAM' }} : {{ number_format($ikm ?? 0, 2) }}
    </div>

    {{-- Bottom Mutu Pelayanan Legend --}}
    <div class="mt-2" style="font-size: 11.5px; color: #333;">
        <div class="fw-bold mb-1">Mutu Pelayanan :</div>
        <table style="width: 280px; font-size: 11.5px; line-height: 1.5;">
            <tr>
                <td style="width: 140px;">A ( Sangat Baik )</td>
                <td>: 88.31 - 100.00</td>
            </tr>
            <tr>
                <td>B ( Baik )</td>
                <td>: 76.61 - 88.30</td>
            </tr>
            <tr>
                <td>C ( Kurang Baik )</td>
                <td>: 65.00 - 76.60</td>
            </tr>
            <tr>
                <td>D ( Tidak Baik )</td>
                <td>: 25.00 - 64.99</td>
            </tr>
        </table>
    </div>
</div>
@endsection

