@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 TRANSAKSI RESEP */
    .resep-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .konhakes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #444;
        text-decoration: none;
    }
    .konhakes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .cyan-divider {
        height: 3px;
        background-color: #00c0ef;
        margin-top: 5px;
        margin-bottom: 12px;
    }
    .card-resep-box {
        background-color: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 12px 15px;
    }
    /* Notification banner */
    .alert-resep-baru {
        background-color: #f39c12;
        color: #ffffff;
        border-radius: 2px;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .badge-count-notif {
        background-color: #ffffff;
        color: #f39c12;
        padding: 2px 7px;
        border-radius: 10px;
        font-weight: bold;
        font-size: 11px;
        margin-left: 5px;
    }
    .btn-toggle-notif {
        color: #ffffff;
        text-decoration: none;
        cursor: pointer;
        font-size: 12px;
    }
    .btn-toggle-notif:hover {
        color: #ffe6ba;
        text-decoration: underline;
    }
    /* Buttons */
    .btn-resep-search {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        height: 30px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-resep-search:hover {
        background-color: #d68407;
    }
    .btn-resep-reset {
        background-color: #ffffff;
        border: 1px solid #ccc;
        color: #444 !important;
        height: 30px;
        padding: 0 10px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-resep-reset:hover {
        background-color: #f4f4f4;
    }
    .btn-resep-bhp {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        height: 30px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        text-decoration: none;
    }
    .btn-resep-bhp:hover {
        background-color: #008d4c;
    }
    .btn-resep-pdf {
        background-color: #00c0ef;
        border: 1px solid #00a7d0;
        color: #ffffff !important;
        height: 30px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        text-decoration: none;
    }
    .btn-resep-pdf:hover {
        background-color: #00a7d0;
    }
    .btn-resep-xls {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
        color: #ffffff !important;
        height: 30px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        text-decoration: none;
    }
    .btn-resep-xls:hover {
        background-color: #286090;
    }
    /* Table */
    .table-konhakes {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        border: 1px solid #e1e1e1;
    }
    .table-konhakes thead th {
        background-color: #ffffff;
        color: #337ab7;
        font-weight: 600;
        padding: 8px 8px;
        border-bottom: 1px solid #e1e1e1;
        border-right: 1px solid #f0f0f0;
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-konhakes tbody td {
        padding: 8px 8px;
        border-bottom: 1px solid #f0f0f0;
        border-right: 1px solid #f9f9f9;
        vertical-align: top;
        font-size: 12px;
        line-height: 1.4;
    }
    .table-konhakes tbody tr:hover {
        background-color: #fdfdfd;
    }
    .badge-rekam-medis {
        background-color: #f39c12;
        color: #ffffff;
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 2px;
        display: inline-block;
    }
    .badge-sudah-proses {
        background-color: #00a65a;
        color: #ffffff;
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 2px;
        display: inline-block;
    }
    .badge-sudah-serah {
        background-color: #008d4c;
        color: #ffffff;
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 2px;
        display: inline-block;
    }
    .filter-ctrl {
        height: 30px;
        font-size: 12px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 3px 6px;
        width: 100%;
        color: #333;
        background-color: #fff;
    }
</style>

<div class="resep-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h1 class="konhakes-title">Transaksi Resep</h1>
        <div class="konhakes-breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> &gt; <span>Transaksi Resep</span>
        </div>
    </div>

    {{-- Cyan divider line --}}
    <div class="cyan-divider"></div>

    {{-- Alert Banner: Resep Masuk Baru --}}
    <div class="alert-resep-baru">
        <div>
            <i class="fa fa-bell me-1"></i>
            <strong>Terdapat Resep Masuk Baru</strong>
            <span class="badge-count-notif">{{ $resepBaruCount }}</span>
        </div>
        <a href="javascript:void(0)" class="btn-toggle-notif" onclick="toggleFilterBox()">
            klik untuk lihat <i class="fa fa-chevron-down ms-1"></i>
        </a>
    </div>

    {{-- Main Container Card --}}
    <div class="card-resep-box">
        {{-- Toolbar Actions --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex gap-1 align-items-center">
                <button type="button" class="btn-resep-search" onclick="toggleFilterBox()">
                    <i class="fa fa-search"></i> Cari data
                </button>
                <a href="{{ url('/transaksiresep/index') }}" class="btn-resep-reset" title="Segarkan Data">
                    <i class="fa fa-sync-alt"></i>
                </a>
            </div>
            <div class="d-flex gap-1 align-items-center">
                <a href="{{ url('/transaksibhp/index') }}" class="btn-resep-bhp">
                    <i class="fa fa-list"></i> Lihat Transaksi BHP
                </a>
                <a href="{{ url('/transaksiresep/cetak-pdf') }}" target="_blank" class="btn-resep-pdf">
                    <i class="fa fa-file-pdf"></i> Cetak Laporan(PDF)
                </a>
                <a href="{{ url('/transaksiresep/cetak-xls') }}" class="btn-resep-xls">
                    <i class="fa fa-file-excel"></i> Cetak Laporan(XLS)
                </a>
            </div>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterSearchBox" class="border p-3 rounded mb-3 bg-light" style="display: {{ request()->anyFilled(['tgl_awal', 'tgl_akhir', 'keyword', 'status_proses', 'status_penyerahan']) ? 'block' : 'none' }};">
            <form action="{{ url('/transaksiresep/index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size: 11px; font-weight: 600;">Tgl Awal</label>
                        <input type="date" name="tgl_awal" class="filter-ctrl" value="{{ request('tgl_awal') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size: 11px; font-weight: 600;">Tgl Akhir</label>
                        <input type="date" name="tgl_akhir" class="filter-ctrl" value="{{ request('tgl_akhir') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1" style="font-size: 11px; font-weight: 600;">Pencarian (No Resep / Pasien / No RM / Reg)</label>
                        <input type="text" name="keyword" class="filter-ctrl" placeholder="Ketik kata kunci..." value="{{ request('keyword') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size: 11px; font-weight: 600;">Status Proses</label>
                        <select name="status_proses" class="filter-ctrl">
                            <option value="Semua">--Semua--</option>
                            <option value="Belum Diproses" {{ request('status_proses') == 'Belum Diproses' ? 'selected' : '' }}>Belum Diproses</option>
                            <option value="Sudah Diproses" {{ request('status_proses') == 'Sudah Diproses' ? 'selected' : '' }}>Sudah Diproses</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size: 11px; font-weight: 600;">Status Penyerahan</label>
                        <select name="status_penyerahan" class="filter-ctrl">
                            <option value="Semua">--Semua--</option>
                            <option value="Belum Diserahkan" {{ request('status_penyerahan') == 'Belum Diserahkan' ? 'selected' : '' }}>Belum Diserahkan</option>
                            <option value="Sudah Diserahkan" {{ request('status_penyerahan') == 'Sudah Diserahkan' ? 'selected' : '' }}>Sudah Diserahkan</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold" style="height: 30px; line-height: 18px; color: #fff;">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Cyan Divider --}}
        <div class="cyan-divider" style="margin-top: 0; margin-bottom: 8px;"></div>

        {{-- Item Count Info --}}
        <div class="mb-2" style="font-size: 12px; color: #555;">
            Menampilkan <strong>{{ $data->firstItem() ?? 0 }}-{{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> item.
        </div>

        {{-- Table Transaksi Resep 1:1 --}}
        <div class="table-responsive">
            <table class="table-konhakes">
                <thead>
                    <tr>
                        <th style="width: 30px; text-align: center;">#</th>
                        <th style="width: 100px;">Antrian</th>
                        <th style="width: 130px;">Proses Resep</th>
                        <th style="width: 140px;">Penyerahan Obat</th>
                        <th style="width: 130px;">Registrasi</th>
                        <th style="width: 160px;">Pasien</th>
                        <th style="width: 100px;">Informasi Tambahan</th>
                        <th style="width: 190px;">Poli, Dokter & Penjamin</th>
                        <th style="width: 160px;">Diagnosa</th>
                        <th style="width: 105px; text-align: center;">Conseling & PIO</th>
                        <th style="width: 160px;">Bridging Penyerahan Obat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                        @php
                            $pendaftaran = $row->pendaftaran;
                            $pasien = $row->pasien;
                            $dokterNama = $row->dokter ? $row->dokter->nama_pegawai : ($pendaftaran && $pendaftaran->dokter ? $pendaftaran->dokter->nama_pegawai : '-');
                            $perawatNama = $pendaftaran && $pendaftaran->perawat ? $pendaftaran->perawat->nama_pegawai : ($pendaftaran->nama_perawat ?? '-');
                            $poliNama = $pendaftaran && $pendaftaran->poliklinik ? $pendaftaran->poliklinik->nama_poli : 'Klinik Umum 1';
                            $penjaminNama = $pendaftaran && $pendaftaran->penjamin ? $pendaftaran->penjamin->nama_penjamin : 'BPJS Kesehatan';
                            $noAntrian = $row->antrian_nomor ?: ($pendaftaran->no_antrian ?? 'A001');
                            $isProcessed = ($row->status_proses === 'Sudah Diproses');
                            $isDelivered = ($row->status_penyerahan === 'Sudah Diserahkan');
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $data->firstItem() + $index }}</td>
                            
                            {{-- Col 2: Antrian --}}
                            <td>
                                <div style="font-size: 11px; color: #555;">Antrian No:</div>
                                <a href="javascript:void(0)" class="fw-bold" style="color: #337ab7; font-size: 13px;">{{ $noAntrian }}</a>
                                <div><span class="badge bg-secondary" style="font-size: 10px; font-weight: normal;">{{ $row->status_antrian ?: 'Menunggu' }}</span></div>
                            </td>

                            {{-- Col 3: Proses Resep --}}
                            <td>
                                @if(!$isProcessed)
                                    <span class="badge-rekam-medis"><i class="fa fa-clipboard-list me-1"></i> Rekam Medis</span>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-outline-info btn-xs py-0 px-2" style="font-size: 11px; height: 23px; line-height: 21px;" onclick="lihatRincianResep({{ $row->id }})">
                                            <i class="fa fa-edit me-1"></i> Lihat Data
                                        </button>
                                    </div>
                                @else
                                    <span class="badge-sudah-proses">Sudah diproses</span>
                                    <small class="d-block text-muted" style="font-size: 11px;">{{ $row->waktu_proses ? $row->waktu_proses->format('d/m/Y H:i') : '-' }}</small>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-outline-info btn-xs py-0 px-2" style="font-size: 11px; height: 23px; line-height: 21px;" onclick="lihatRincianResep({{ $row->id }})">
                                            <i class="fa fa-eye me-1"></i> Lihat Data
                                        </button>
                                    </div>
                                @endif
                            </td>

                            {{-- Col 4: Penyerahan Obat --}}
                            <td>
                                @if($isDelivered)
                                    <span class="badge-sudah-serah">Sudah Diserahkan</span>
                                    <small class="d-block text-muted" style="font-size: 11px;">{{ $row->waktu_penyerahan ? $row->waktu_penyerahan->format('d/m/Y H:i') : '-' }}</small>
                                    <small class="d-block text-muted" style="font-size: 10.5px;">Waktu tunggu:<br><strong>{{ $row->waktu_tunggu ?: '0 Jam 0 Mnt' }}</strong></small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Col 5: Registrasi --}}
                            <td>
                                <a href="javascript:void(0)" class="fw-bold" style="color: #337ab7;">
                                    {{ $pendaftaran ? $pendaftaran->nomor_registrasi : $row->nomor_resep }}
                                </a>
                                <small class="d-block text-muted" style="font-size: 11px;">
                                    Tgl: {{ $row->tanggal_resep ? \Carbon\Carbon::parse($row->tanggal_resep)->format('d.M.Y') : '-' }}
                                </small>
                                <div class="mt-1">
                                    @if($pendaftaran && $pendaftaran->status_pelayanan === 'Izin Pulang')
                                        <span class="badge bg-primary" style="font-size: 10px;">Izin Pulang</span>
                                    @elseif($pendaftaran && $pendaftaran->is_online)
                                        <span class="badge bg-warning text-dark" style="font-size: 10px;">Daftar Via Online</span>
                                    @else
                                        <span class="badge bg-info text-white" style="font-size: 10px;">Selesai</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Col 6: Pasien --}}
                            <td>
                                <div class="fw-bold text-dark">{{ $pasien ? $pasien->nama_pasien : '-' }}</div>
                                <small class="text-muted d-block">No RM: <strong>{{ $pasien ? $pasien->no_rm : '-' }}</strong></small>
                                <small class="text-muted d-block">L/P: {{ $pasien ? ($pasien->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-Laki') : '-' }}</small>
                                <small class="text-muted d-block">Lahir: {{ $pasien && $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d.M.Y') : '-' }}</small>
                            </td>

                            {{-- Col 7: Informasi Tambahan --}}
                            <td>
                                <small class="text-muted">{{ $row->informasi_tambahan ?: '-' }}</small>
                            </td>

                            {{-- Col 8: Poli, Dokter & Penjamin --}}
                            <td>
                                <div class="fw-bold text-dark">{{ $poliNama }}</div>
                                <small class="d-block text-muted">Dokter : <strong>{{ $dokterNama }}</strong></small>
                                <small class="d-block text-muted">Perawat: {{ $perawatNama }}</small>
                                <small class="d-block text-muted">Penjamin: <span class="text-success fw-bold">{{ $penjaminNama }}</span></small>
                            </td>

                            {{-- Col 9: Diagnosa --}}
                            <td>
                                <small class="text-dark fw-semibold" style="line-height: 1.3; display: block;">
                                    {{ $row->diagnosa ?: '-' }}
                                </small>
                            </td>

                            {{-- Col 10: Conseling & PIO --}}
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm py-0 px-2 mb-1 w-100" style="background-color: #337ab7; color: #fff; font-size: 11px; height: 23px; line-height: 21px;" onclick="modalConseling({{ $row->id }}, '{{ addslashes($row->conseling_catatan ?? '') }}')" title="Catatan Konseling">
                                    <i class="fa fa-user me-1"></i> Conseling
                                </button>
                                <button type="button" class="btn btn-sm py-0 px-2 w-100" style="background-color: #337ab7; color: #fff; font-size: 11px; height: 23px; line-height: 21px;" onclick="modalPio({{ $row->id }}, '{{ addslashes($row->pio_catatan ?? '') }}')" title="Entri Pelayanan Informasi Obat">
                                    <i class="fa fa-bullhorn me-1"></i> Entri PIO
                                </button>
                            </td>

                            {{-- Col 11: Bridging Penyerahan Obat --}}
                            <td>
                                <div style="font-size: 11px; line-height: 1.35;">
                                    <span class="{{ str_contains($row->status_pcare, 'Terkirim') ? 'text-success fw-bold' : 'text-danger' }}">
                                        Pcare: {{ $row->status_pcare ?: 'Obat Belum Kirim' }}
                                    </span><br>
                                    <span class="{{ str_contains($row->status_satusehat, 'Terkirim') ? 'text-success fw-bold' : 'text-danger' }}">
                                        Satu Sehat: {{ $row->status_satusehat ?: 'Belum Daftar' }}
                                    </span>
                                    @if(str_contains($row->status_satusehat, 'Terkirim') || $row->status_kaji_resep === 'Terkirim')
                                        <br><span class="text-success">Kaji Resep: Terkirim</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-5">
                                <i class="fa fa-prescription-bottle fa-2x mb-2 d-block text-secondary"></i>
                                Tidak ada data transaksi resep yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-end">
            {{ $data->links() }}
        </div>
    </div>
</div>

{{-- MODAL LIHAT DATA / PROSES RESEP --}}
<div class="modal fade" id="modalLihatDataResep" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 3px;">
            <div class="modal-header py-2 px-3 text-white" style="background-color: #337ab7;">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa fa-pills me-1"></i> Rincian Resep Obat Pasien
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-3" style="font-size: 12.5px;">
                {{-- Patient & Doctor Summary --}}
                <div class="row g-2 mb-3 p-2 bg-light border rounded">
                    <div class="col-md-3">
                        <small class="text-muted d-block">Nomor Resep:</small>
                        <strong class="text-primary fs-6" id="mdlNoResep">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Nama Pasien / RM:</small>
                        <strong id="mdlPasien">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Dokter Penulis:</small>
                        <strong id="mdlDokter">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Poli / Penjamin:</small>
                        <strong id="mdlPoliPenjamin">-</strong>
                    </div>
                    <div class="col-md-12 mt-2 pt-2 border-top">
                        <small class="text-muted d-block">Diagnosa Pasien:</small>
                        <strong class="text-dark" id="mdlDiagnosa">-</strong>
                    </div>
                </div>

                {{-- Table Items --}}
                <h6 class="fw-bold mb-2 text-dark" style="font-size: 13px;">
                    <i class="fa fa-list text-primary me-1"></i> Daftar Obat & Aturan Pakai:
                </h6>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead style="background-color: #f5f5f5;">
                            <tr>
                                <th style="width: 30px; text-align: center;">No</th>
                                <th>Nama Obat / Barang</th>
                                <th style="width: 90px; text-align: center;">Satuan</th>
                                <th>Signa / Aturan Pakai</th>
                                <th style="width: 70px; text-align: right;">Jumlah</th>
                                <th style="width: 90px; text-align: right;">Harga</th>
                                <th style="width: 100px; text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="mdlTableItems">
                            <!-- Populated by JS -->
                        </tbody>
                        <tfoot style="background-color: #f9f9f9; font-weight: bold;">
                            <tr>
                                <td colspan="6" class="text-end">Total Biaya Obat:</td>
                                <td class="text-end text-primary" id="mdlTotalBiaya">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Current Status Summary --}}
                <div class="border p-2 rounded mb-2" id="boxStatusResep" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Status Proses: <span id="lblStatusProses" class="badge bg-secondary">-</span></div>
                        <div>Status Penyerahan: <span id="lblStatusPenyerahan" class="badge bg-secondary">-</span></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-2 px-3">
                <input type="hidden" id="activeResepId" value="">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-sm px-3 fw-bold" id="btnProsesRacik" onclick="prosesRacikResep()">
                    <i class="fa fa-mortar-pestle me-1"></i> Proses & Racik Obat
                </button>
                <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" id="btnSerahkanObat" onclick="serahkanObatKePasien()">
                    <i class="fa fa-check-circle me-1"></i> Serahkan ke Pasien
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CONSELING --}}
<div class="modal fade" id="modalConselingDialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 3px;">
            <div class="modal-header py-2 px-3 text-white" style="background-color: #337ab7;">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa fa-user me-1"></i> Catatan Konseling Pasien
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" id="conselingResepId">
                <div class="mb-2">
                    <label class="form-label fw-bold" style="font-size: 12px;">Catatan Hasil Konseling Obat:</label>
                    <textarea class="form-control" id="txtCatatanConseling" rows="4" placeholder="Ketik catatan edukasi / kepatuhan minum obat pasien..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2 px-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm px-3" onclick="simpanDataConseling()">Simpan Konseling</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ENTRI PIO --}}
<div class="modal fade" id="modalPioDialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 3px;">
            <div class="modal-header py-2 px-3 text-white" style="background-color: #00a65a;">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa fa-bullhorn me-1"></i> Entri Pelayanan Informasi Obat (PIO)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" id="pioResepId">
                <div class="mb-2">
                    <label class="form-label fw-bold" style="font-size: 12px;">Informasi Obat yang Disampaikan:</label>
                    <textarea class="form-control" id="txtCatatanPio" rows="4" placeholder="Ketik rincian informasi obat (indikasi, dosis, cara penyimpanan, efek samping)..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2 px-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-sm px-3" onclick="simpanDataPio()">Simpan PIO</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFilterBox() {
        $('#filterSearchBox').slideToggle(200);
    }

    function lihatRincianResep(id) {
        $.ajax({
            url: '/transaksiresep/' + id + '/detail',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.data) {
                    var r = res.data;
                    $('#activeResepId').val(r.id);
                    $('#mdlNoResep').text(r.nomor_resep);
                    $('#mdlPasien').text((r.pasien ? r.pasien.nama_pasien : '-') + ' (' + (r.pasien ? r.pasien.no_rm : '-') + ')');
                    $('#mdlDokter').text(r.dokter ? r.dokter.nama_pegawai : '-');
                    
                    var poli = r.pendaftaran && r.pendaftaran.poliklinik ? r.pendaftaran.poliklinik.nama_poli : 'Klinik Umum 1';
                    var penjamin = r.pendaftaran && r.pendaftaran.penjamin ? r.pendaftaran.penjamin->nama_penjamin : 'BPJS Kesehatan';
                    $('#mdlPoliPenjamin').text(poli + ' / ' + penjamin);
                    $('#mdlDiagnosa').text(r.diagnosa || '-');

                    $('#lblStatusProses').text(r.status_proses).attr('class', 'badge ' + (r.status_proses === 'Sudah Diproses' ? 'bg-success' : 'bg-warning text-dark'));
                    $('#lblStatusPenyerahan').text(r.status_penyerahan).attr('class', 'badge ' + (r.status_penyerahan === 'Sudah Diserahkan' ? 'bg-success' : 'bg-secondary'));

                    if (r.status_proses === 'Sudah Diproses') {
                        $('#btnProsesRacik').hide();
                    } else {
                        $('#btnProsesRacik').show().prop('disabled', false).html('<i class="fa fa-mortar-pestle me-1"></i> Proses & Racik Obat');
                    }

                    if (r.status_penyerahan === 'Sudah Diserahkan') {
                        $('#btnSerahkanObat').hide();
                    } else {
                        $('#btnSerahkanObat').show().prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Serahkan ke Pasien');
                    }

                    var rows = '';
                    var total = 0;
                    if (r.details && r.details.length > 0) {
                        r.details.forEach(function(d, i) {
                            var subtotal = parseFloat(d.subtotal) || 0;
                            total += subtotal;
                            rows += '<tr>' +
                                '<td class="text-center">' + (i + 1) + '</td>' +
                                '<td><strong>' + (d.master_barang ? d.master_barang.nama_barang : ('Barang #' + d.master_barang_id)) + '</strong></td>' +
                                '<td class="text-center">' + (d.master_barang ? (d.master_barang.satuan || '-') : '-') + '</td>' +
                                '<td>' + (d.aturan_pakai || '-') + '</td>' +
                                '<td class="text-end fw-bold">' + d.jumlah + '</td>' +
                                '<td class="text-end">Rp ' + Number(d.harga_satuan).toLocaleString('id-ID') + '</td>' +
                                '<td class="text-end fw-bold text-success">Rp ' + Number(subtotal).toLocaleString('id-ID') + '</td>' +
                            '</tr>';
                        });
                    } else {
                        rows = '<tr><td colspan="7" class="text-center text-muted py-3">Tidak ada obat dalam resep ini.</td></tr>';
                    }

                    $('#mdlTableItems').html(rows);
                    $('#mdlTotalBiaya').text('Rp ' + Number(total).toLocaleString('id-ID'));
                    $('#modalLihatDataResep').modal('show');
                }
            },
            error: function() {
                alert('Gagal mengambil data rincian resep.');
            }
        });
    }

    function prosesRacikResep() {
        var id = $('#activeResepId').val();
        if (!confirm('Proses dan racik obat untuk resep ini? Stok obat akan dipotong otomatis.')) {
            return;
        }

        var btn = $('#btnProsesRacik');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Memproses...');

        $.ajax({
            url: '/transaksiresep/' + id + '/proses',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    $('#modalLihatDataResep').modal('hide');
                    window.location.reload();
                } else {
                    alert('Gagal: ' + res.message);
                    btn.prop('disabled', false).html('<i class="fa fa-mortar-pestle me-1"></i> Proses & Racik Obat');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-mortar-pestle me-1"></i> Proses & Racik Obat');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                alert(msg);
            }
        });
    }

    function serahkanObatKePasien() {
        var id = $('#activeResepId').val();
        if (!confirm('Konfirmasi bahwa obat telah diserahkan kepada pasien?')) {
            return;
        }

        var btn = $('#btnSerahkanObat');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Memproses...');

        $.ajax({
            url: '/transaksiresep/' + id + '/serahkan',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    $('#modalLihatDataResep').modal('hide');
                    window.location.reload();
                } else {
                    alert('Gagal: ' + res.message);
                    btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Serahkan ke Pasien');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Serahkan ke Pasien');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                alert(msg);
            }
        });
    }

    function modalConseling(id, catatan) {
        $('#conselingResepId').val(id);
        $('#txtCatatanConseling').val(catatan || '');
        $('#modalConselingDialog').modal('show');
    }

    function simpanDataConseling() {
        var id = $('#conselingResepId').val();
        var catatan = $('#txtCatatanConseling').val();

        if (!catatan || catatan.trim() === '') {
            alert('Silakan ketik catatan konseling.');
            return;
        }

        $.ajax({
            url: '/transaksiresep/' + id + '/conseling',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                catatan: catatan
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    $('#modalConselingDialog').modal('hide');
                    window.location.reload();
                }
            },
            error: function() {
                alert('Gagal menyimpan konseling.');
            }
        });
    }

    function modalPio(id, catatan) {
        $('#pioResepId').val(id);
        $('#txtCatatanPio').val(catatan || '');
        $('#modalPioDialog').modal('show');
    }

    function simpanDataPio() {
        var id = $('#pioResepId').val();
        var catatan = $('#txtCatatanPio').val();

        if (!catatan || catatan.trim() === '') {
            alert('Silakan ketik catatan PIO.');
            return;
        }

        $.ajax({
            url: '/transaksiresep/' + id + '/pio',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                catatan: catatan
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    $('#modalPioDialog').modal('hide');
                    window.location.reload();
                }
            },
            error: function() {
                alert('Gagal menyimpan PIO.');
            }
        });
    }
</script>
@endsection
