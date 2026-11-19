@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 PENERIMAAN MUTASI BARANG ANTAR GUDANG */
    .mb-page {
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
    .card-mb-box {
        background-color: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 12px 15px;
    }
    .filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #444;
        margin-bottom: 3px;
        display: block;
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
    .filter-ctrl:focus {
        border-color: #3c8dbc;
        outline: none;
    }
    .btn-mb-search {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        height: 30px;
        padding: 0 10px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .btn-mb-search:hover {
        background-color: #d68407;
    }
    .btn-mb-reset {
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
    .btn-mb-reset:hover {
        background-color: #f4f4f4;
    }
    .btn-mb-create {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        height: 30px;
        padding: 0 14px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-mb-create:hover {
        background-color: #008d4c;
    }
    .table-konhakes {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        border: 1px solid #e1e1e1;
    }
    .table-konhakes thead th {
        background-color: #ffffff;
        color: #337ab7;
        font-weight: 600;
        padding: 8px 10px;
        border-bottom: 1px solid #e1e1e1;
        border-right: 1px solid #f0f0f0;
        white-space: nowrap;
    }
    .table-konhakes tbody td {
        padding: 7px 10px;
        border-bottom: 1px solid #f4f4f4;
        border-right: 1px solid #f9f9f9;
        vertical-align: middle;
        font-size: 12.5px;
    }
    .table-konhakes tbody tr:hover {
        background-color: #fcfcfc;
    }
    .badge-belum-terima {
        background-color: #f39c12;
        color: #fff;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 2px;
        display: inline-block;
    }
    .badge-sudah-terima {
        background-color: #00a65a;
        color: #fff;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 2px;
        display: inline-block;
    }
    .link-mutasi {
        color: #337ab7;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
    }
    .link-mutasi:hover {
        text-decoration: underline;
        color: #23527c;
    }
</style>

<div class="mb-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h1 class="konhakes-title">Penerimaan Mutasi Barang Antar Gudang</h1>
        <div class="konhakes-breadcrumb">
            <a href="{{ url('/dashboard') }}">Beranda</a> &gt; <a href="{{ url()->current() }}">Penerimaan Mutasi Barang Antar Gudang</a>
        </div>
    </div>

    {{-- Cyan divider line --}}
    <div class="cyan-divider"></div>

    {{-- Main Container Card --}}
    <div class="card-mb-box">
        {{-- Filter Bar 1:1 in one row --}}
        <form action="{{ url('/penerimaanmutasibarang/index') }}" method="GET" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="filter-label">No Mutasi</label>
                    <input type="text" name="no_mutasi" class="filter-ctrl" value="{{ request('no_mutasi') }}">
                </div>
                <div class="col-md-1" style="min-width: 120px;">
                    <label class="filter-label">Tgl Awal</label>
                    <input type="date" name="tgl_awal" class="filter-ctrl" value="{{ request('tgl_awal') }}">
                </div>
                <div class="col-md-1" style="min-width: 120px;">
                    <label class="filter-label">Tgl Akhir</label>
                    <input type="date" name="tgl_akhir" class="filter-ctrl" value="{{ request('tgl_akhir') }}">
                </div>
                <div class="col-md-2">
                    <label class="filter-label">Gudang Asal</label>
                    <select name="gudang_asal_id" class="filter-ctrl">
                        <option value="">--Pilih--</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ request('gudang_asal_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="filter-label">Gudang Tujuan</label>
                    <select name="gudang_tujuan_id" class="filter-ctrl">
                        <option value="">--Pilih--</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ request('gudang_tujuan_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1" style="min-width: 110px;">
                    <label class="filter-label">Sudah Diterima?</label>
                    <select name="sudah_diterima" class="filter-ctrl">
                        <option value="Belum Terima" {{ ($sudahDiterima ?? '') == 'Belum Terima' ? 'selected' : '' }}>Belum terima</option>
                        <option value="Sudah Terima" {{ ($sudahDiterima ?? '') == 'Sudah Terima' ? 'selected' : '' }}>Sudah terima</option>
                        <option value="Semua" {{ ($sudahDiterima ?? '') == 'Semua' ? 'selected' : '' }}>--Semua--</option>
                    </select>
                </div>
                <div class="col-md-auto d-flex gap-1 align-items-center">
                    <button type="submit" class="btn-mb-search" title="Cari Data">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="{{ url('/penerimaanmutasibarang/index') }}" class="btn-mb-reset" title="Segarkan Filter">
                        <i class="fa fa-sync-alt"></i>
                    </a>
                    <a href="{{ url('/penerimaanmutasibarang/create') }}" class="btn-mb-create ms-1">
                        <i class="fa fa-plus"></i> Create
                    </a>
                </div>
            </div>
        </form>

        {{-- Item Count Info --}}
        <div class="mb-2" style="font-size: 12px; color: #555;">
            Menampilkan <strong>{{ $data->firstItem() ?? 0 }}-{{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> item.
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-konhakes">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th style="width: 180px;">No Mutasi</th>
                        <th style="width: 130px;">Tgl Mutasi</th>
                        <th>Gudang Asal</th>
                        <th>Gudang Tujuan</th>
                        <th style="width: 130px; text-align: center;">Sudah Diterima?</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                        @php
                            $gudangAsalNama = $row->gudangAsal ? $row->gudangAsal->nama_gudang : 'GUDANG AWAL';
                            $gudangTujuanNama = $row->gudangTujuan ? $row->gudangTujuan->nama_gudang : ($row->unit_tujuan ?: 'Gudang Farmasi');
                            $isReceived = ($row->sudah_diterima === 'Sudah Terima' || $row->status === 'Diterima');
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $data->firstItem() + $index }}</td>
                            <td>
                                <a href="javascript:void(0)" onclick="bukaModalPenerimaan({{ $row->id }})" class="link-mutasi" title="Lihat & Konfirmasi Penerimaan">
                                    <i class="fa fa-arrow-circle-right text-info" style="font-size: 11px;"></i> {{ $row->nomor_mutasi }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_mutasi)->format('d.M.Y') }}</td>
                            <td>{{ $gudangAsalNama }}</td>
                            <td>{{ $gudangTujuanNama }}</td>
                            <td style="text-align: center;">
                                @if($isReceived)
                                    <span class="badge-sudah-terima" style="cursor: pointer;" onclick="bukaModalPenerimaan({{ $row->id }})" title="Sudah diterima. Klik untuk melihat detail.">Sudah terima</span>
                                @else
                                    <span class="badge-belum-terima" style="cursor: pointer;" onclick="bukaModalPenerimaan({{ $row->id }})" title="Belum diterima. Klik untuk konfirmasi penerimaan.">Belum terima</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if(!$isReceived)
                                    <button type="button" class="btn btn-xs btn-success py-0 px-2" style="font-size: 11px; height: 24px; line-height: 22px;" onclick="bukaModalPenerimaan({{ $row->id }})" title="Konfirmasi Terima Mutasi">
                                        <i class="fa fa-check me-1"></i> Terima
                                    </button>
                                @else
                                    <button type="button" class="btn btn-xs btn-outline-info py-0 px-2" style="font-size: 11px; height: 24px; line-height: 22px;" onclick="bukaModalPenerimaan({{ $row->id }})" title="Rincian Mutasi">
                                        <i class="fa fa-eye me-1"></i> Detail
                                    </button>
                                @endif
                                <a href="{{ url('/mutasibarang/' . $row->id . '/cetak-pdf') }}" target="_blank" class="btn btn-xs btn-outline-secondary py-0 px-2 ms-1" style="font-size: 11px; height: 24px; line-height: 22px;" title="Cetak Surat Jalan Mutasi">
                                    <i class="fa fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada data penerimaan mutasi yang ditemukan.
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

{{-- MODAL PENERIMAAN / DETAIL MUTASI --}}
<div class="modal fade" id="modalPenerimaanMutasi" tabindex="-1" aria-labelledby="modalPenerimaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 3px;">
            <div class="modal-header py-2 px-3 text-white" id="modalHeaderBox" style="background-color: #00a65a;">
                <h5 class="modal-title fs-6 fw-bold" id="modalPenerimaanLabel">
                    <i class="fa fa-clipboard-check me-1"></i> Konfirmasi Penerimaan Mutasi Barang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-3" style="font-size: 13px;">
                {{-- Info Summary --}}
                <div class="row g-2 mb-3 p-2 bg-light border rounded">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Nomor Mutasi:</small>
                        <strong class="text-primary fs-6" id="detNoMutasi">-</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal Mutasi:</small>
                        <strong id="detTanggal">-</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Status Penerimaan:</small>
                        <span id="detStatusBadge" class="badge-belum-terima">Belum Terima</span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <small class="text-muted d-block">Gudang Asal (Pengirim):</small>
                        <strong id="detGudangAsal">-</strong>
                    </div>
                    <div class="col-md-4 mt-2">
                        <small class="text-muted d-block">Gudang Tujuan (Penerima):</small>
                        <strong class="text-success" id="detGudangTujuan">-</strong>
                    </div>
                    <div class="col-md-4 mt-2">
                        <small class="text-muted d-block">Petugas Pengirim:</small>
                        <strong id="detPetugasPengirim">-</strong>
                    </div>
                </div>

                {{-- Table Items --}}
                <h6 class="fw-bold mb-2 text-dark" style="font-size: 13px;">
                    <i class="fa fa-boxes text-info me-1"></i> Rincian Barang yang Dimutasi:
                </h6>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead style="background-color: #f5f5f5;">
                            <tr>
                                <th style="width: 35px; text-align: center;">No</th>
                                <th style="width: 100px;">Kode</th>
                                <th>Nama Barang / Obat</th>
                                <th style="width: 100px; text-align: center;">Kemasan</th>
                                <th style="width: 170px;">Batch & Exp</th>
                                <th style="width: 90px; text-align: right;">Qty Kirim</th>
                            </tr>
                        </thead>
                        <tbody id="detTableItems">
                            <!-- Populated by JS -->
                        </tbody>
                        <tfoot style="background-color: #f9f9f9; font-weight: bold;">
                            <tr>
                                <td colspan="5" class="text-end">Total Item:</td>
                                <td class="text-end" id="detTotalQty">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Form Konfirmasi Terima (Only shown when Belum Terima) --}}
                <div id="sectionFormTerima" class="border p-3 rounded" style="background-color: #f8fff9; border-color: #c3e6cb !important;">
                    <div class="d-flex align-items-center mb-2 text-success fw-bold">
                        <i class="fa fa-check-circle me-2"></i> Masukkan Data Konfirmasi Penerimaan:
                    </div>
                    <form id="formKonfirmasiTerima">
                        @csrf
                        <input type="hidden" id="penerimaanMutasiId" value="">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold" style="font-size: 12px;">Tanggal Diterima <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" id="inputTanggalTerima" name="tanggal_terima" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1 fw-semibold" style="font-size: 12px;">Nama Petugas Penerima <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="inputPenerima" name="penerima" value="{{ Auth::user()->name ?? 'dr. FAHMI RIZAL' }}" placeholder="Contoh: dr. FAHMI RIZAL" required>
                            </div>
                        </div>
                        <div class="mt-2 text-muted" style="font-size: 11.5px;">
                            <i class="fa fa-info-circle text-info me-1"></i> Setelah konfirmasi disimpan, stok barang akan otomatis ditambahkan ke stok gudang tujuan dan tercatat di riwayat mutasi masuk.
                        </div>
                    </form>
                </div>

                {{-- Info Sudah Diterima (Shown when Sudah Terima) --}}
                <div id="sectionSudahDiterima" class="border p-3 rounded" style="background-color: #e8f4f8; border-color: #b8daff !important; display: none;">
                    <div class="text-success fw-bold mb-1">
                        <i class="fa fa-check-double me-1"></i> Mutasi Barang Sudah Berhasil Diterima
                    </div>
                    <div class="row g-2 text-dark" style="font-size: 12.5px;">
                        <div class="col-md-6">
                            Tanggal Diterima: <strong id="lblTanggalTerima">-</strong>
                        </div>
                        <div class="col-md-6">
                            Diterima Oleh: <strong id="lblPetugasPenerima">-</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-2 px-3">
                <a id="btnCetakSuratJalan" href="#" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-print me-1"></i> Cetak Dokumen
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-sm px-3 fw-bold" id="btnSubmitTerima" onclick="prosesSimpanPenerimaan()">
                    <i class="fa fa-check me-1"></i> Konfirmasi Terima Mutasi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function formatDateIndo(dStr) {
        if (!dStr) return '-';
        var d = new Date(dStr);
        if (isNaN(d.getTime())) return dStr;
        var day = String(d.getDate()).padStart(2, '0');
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return day + '.' + months[d.getMonth()] + '.' + d.getFullYear();
    }

    function bukaModalPenerimaan(id) {
        $.ajax({
            url: '/mutasibarang/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.data) {
                    var m = res.data;
                    $('#penerimaanMutasiId').val(m.id);
                    $('#detNoMutasi').text(m.nomor_mutasi);
                    $('#detTanggal').text(formatDateIndo(m.tanggal_mutasi));
                    $('#detGudangAsal').text(m.gudang_asal ? m.gudang_asal.nama_gudang : 'GUDANG AWAL');
                    $('#detGudangTujuan').text(m.gudang_tujuan ? m.gudang_tujuan.nama_gudang : (m.unit_tujuan || 'Gudang Farmasi'));
                    $('#detPetugasPengirim').text(m.petugas || 'dr. FAHMI RIZAL');

                    var isRec = (m.sudah_diterima === 'Sudah Terima' || m.status === 'Diterima');
                    if (isRec) {
                        $('#detStatusBadge').text('Sudah Terima').attr('class', 'badge-sudah-terima');
                        $('#sectionFormTerima').hide();
                        $('#sectionSudahDiterima').show();
                        $('#lblTanggalTerima').text(formatDateIndo(m.tanggal_terima));
                        $('#lblPetugasPenerima').text(m.penerima || '-');
                        $('#btnSubmitTerima').hide();
                        $('#modalHeaderBox').css('background-color', '#3c8dbc');
                        $('#modalPenerimaanLabel').html('<i class="fa fa-info-circle me-1"></i> Detail Penerimaan Mutasi Barang');
                    } else {
                        $('#detStatusBadge').text('Belum Terima').attr('class', 'badge-belum-terima');
                        $('#sectionFormTerima').show();
                        $('#sectionSudahDiterima').hide();
                        $('#inputTanggalTerima').val('{{ date("Y-m-d") }}');
                        $('#inputPenerima').val('{{ Auth::user()->name ?? "dr. FAHMI RIZAL" }}');
                        $('#btnSubmitTerima').show().prop('disabled', false).html('<i class="fa fa-check me-1"></i> Konfirmasi Terima Mutasi');
                        $('#modalHeaderBox').css('background-color', '#00a65a');
                        $('#modalPenerimaanLabel').html('<i class="fa fa-clipboard-check me-1"></i> Konfirmasi Penerimaan Mutasi Barang');
                    }

                    var rows = '';
                    var totalQty = 0;
                    if (m.details && m.details.length > 0) {
                        m.details.forEach(function(d, i) {
                            totalQty += parseFloat(d.jumlah) || 0;
                            var batchInfo = d.nomor_batch ? ('Batch: ' + d.nomor_batch) : '-';
                            if (d.tanggal_kadaluarsa) batchInfo += ' | Exp: ' + formatDateIndo(d.tanggal_kadaluarsa);

                            rows += '<tr>' +
                                '<td class="text-center">' + (i + 1) + '</td>' +
                                '<td>' + (d.master_barang ? (d.master_barang.kode_barang || '-') : '-') + '</td>' +
                                '<td><strong>' + (d.master_barang ? d.master_barang.nama_barang : ('Item #' + d.master_barang_id)) + '</strong></td>' +
                                '<td class="text-center">' + (d.kemasan || '-') + '</td>' +
                                '<td><small>' + batchInfo + '</small></td>' +
                                '<td class="text-end fw-bold text-success">' + Number(d.jumlah).toLocaleString('id-ID') + '</td>' +
                            '</tr>';
                        });
                    } else {
                        rows = '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada rincian item.</td></tr>';
                    }

                    $('#detTableItems').html(rows);
                    $('#detTotalQty').text(Number(totalQty).toLocaleString('id-ID'));
                    $('#btnCetakSuratJalan').attr('href', '/mutasibarang/' + m.id + '/cetak-pdf');

                    $('#modalPenerimaanMutasi').modal('show');
                }
            },
            error: function() {
                alert('Gagal mengambil rincian mutasi barang.');
            }
        });
    }

    function prosesSimpanPenerimaan() {
        var id = $('#penerimaanMutasiId').val();
        var tanggalTerima = $('#inputTanggalTerima').val();
        var penerima = $('#inputPenerima').val();

        if (!tanggalTerima) {
            alert('Silakan pilih tanggal diterima.');
            $('#inputTanggalTerima').focus();
            return;
        }

        if (!penerima || penerima.trim() === '') {
            alert('Silakan isi nama petugas penerima.');
            $('#inputPenerima').focus();
            return;
        }

        var btn = $('#btnSubmitTerima');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Memproses...');

        $.ajax({
            url: '/penerimaanmutasibarang/' + id + '/terima',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tanggal_terima: tanggalTerima,
                penerima: penerima
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    $('#modalPenerimaanMutasi').modal('hide');
                    window.location.reload();
                } else {
                    alert(res.message || 'Gagal mengonfirmasi penerimaan mutasi.');
                    btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Konfirmasi Terima Mutasi');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Konfirmasi Terima Mutasi');
                var msg = 'Terjadi kesalahan sistem saat memproses penerimaan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            }
        });
    }
</script>
@endsection
