@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 TRANSAKSI PENERIMAAN BARANG */
    .bm-page {
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
    .card-bm-box {
        background-color: #fff;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 12px 15px;
        margin-top: 10px;
    }
    .btn-prof-cari {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 12.5px;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        line-height: 1.4;
    }
    .btn-prof-cari:hover {
        background-color: #d68407;
        color: #ffffff !important;
    }
    .btn-prof-transaksi-baru {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        font-size: 12.5px;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        line-height: 1.4;
    }
    .btn-prof-transaksi-baru:hover {
        background-color: #008d4c;
        color: #ffffff !important;
    }
    .btn-prof-cetak-pdf {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
        color: #ffffff !important;
        font-size: 12.5px;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        line-height: 1.4;
    }
    .btn-prof-cetak-pdf:hover {
        background-color: #286090;
        color: #ffffff !important;
    }
    .btn-prof-cetak-xls {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #ffffff !important;
        font-size: 12.5px;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        line-height: 1.4;
    }
    .btn-prof-cetak-xls:hover {
        background-color: #00a7d0;
        color: #ffffff !important;
    }
    .cyan-divider {
        height: 3px;
        background-color: #00c0ef;
        margin-top: 10px;
        margin-bottom: 12px;
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
    .filter-card {
        background-color: #fdfdfd;
        border: 1px solid #e3e3e3;
        border-radius: 3px;
        padding: 12px 15px;
        margin-bottom: 12px;
    }
    .filter-card label {
        font-size: 12px;
        font-weight: 600;
        color: #555;
        margin-bottom: 3px;
    }
</style>

<div class="bm-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h1 class="konhakes-title">Transaksi Penerimaan Barang</h1>
        <div class="konhakes-breadcrumb">
            <a href="{{ url('/dashboard') }}">Beranda</a> &gt; <a href="{{ url()->current() }}">Transaksi Penerimaan Barang</a>
        </div>
    </div>

    {{-- Main Box Container --}}
    <div class="card-bm-box">
        {{-- Action Bar --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-prof-cari" id="btnToggleCari" onclick="toggleSearchFilter()">
                    <i class="fa fa-search"></i> Cari data
                </button>
                <a href="{{ url('/barangmasuk/create') }}" class="btn btn-prof-transaksi-baru">
                    <i class="fa fa-plus"></i> Transaksi Baru
                </a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('/barangmasuk/cetak-pdf' . (request()->getQueryString() ? '?' . request()->getQueryString() : '')) }}" target="_blank" class="btn btn-prof-cetak-pdf">
                    <i class="fa fa-file-pdf"></i> Cetak Laporan (pdf)
                </a>
                <a href="{{ url('/barangmasuk/export-excel' . (request()->getQueryString() ? '?' . request()->getQueryString() : '')) }}" class="btn btn-prof-cetak-xls">
                    <i class="fa fa-file-excel"></i> Cetak Laporan (xls)
                </a>
            </div>
        </div>

        {{-- Collapsible Search Filter --}}
        <div id="filterSearchSection" class="filter-card" style="{{ (request('nomor_penerimaan') || request('nomor_faktur') || request('master_vendor_id') || request('status_pembayaran') || request('tanggal_mulai')) ? '' : 'display: none;' }}">
            <form action="{{ url('/barangmasuk/index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label>Kode Penerimaan</label>
                    <input type="text" name="nomor_penerimaan" class="form-control form-control-sm" placeholder="BP..." value="{{ request('nomor_penerimaan') }}">
                </div>
                <div class="col-md-2">
                    <label>No. Faktur</label>
                    <input type="text" name="nomor_faktur" class="form-control form-control-sm" placeholder="No Faktur..." value="{{ request('nomor_faktur') }}">
                </div>
                <div class="col-md-2">
                    <label>Vendor</label>
                    <select name="master_vendor_id" class="form-select form-select-sm">
                        <option value="">-- Semua Vendor --</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ request('master_vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Tgl. Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-2">
                    <label>Tgl. Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fa fa-filter"></i> Filter</button>
                    <a href="{{ url('/barangmasuk/index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>

        {{-- Cyan line divider --}}
        <div class="cyan-divider"></div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2 px-3 small" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close py-2 px-3" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small" role="alert">
                <i class="fa fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close py-2 px-3" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-konhakes">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th style="width: 140px;">Kode Penerimaan <span style="font-size: 10px; color: #999;">⇅</span></th>
                        <th style="width: 105px;">Tanggal Terima</th>
                        <th>Vendor</th>
                        <th style="width: 150px;">No Faktur Penerimaan</th>
                        <th style="width: 110px; text-align: right;">Grand Total</th>
                        <th style="width: 110px; text-align: center;">Jenis Pembayaran</th>
                        <th style="width: 100px; text-align: center;">Jatuh Tempo</th>
                        <th style="width: 100px; text-align: center;">Pembayaran</th>
                        <th style="width: 100px; text-align: right;">Total Bayar</th>
                        <th style="width: 100px; text-align: right;">Sisa Bayar</th>
                        <th style="width: 90px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                        @php
                            $grandTotalVal = $row->grand_total > 0 ? $row->grand_total : $row->total_tagihan;
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $data->firstItem() + $index }}</td>
                            <td class="fw-bold" style="color: #007bff;">
                                <a href="javascript:void(0)" onclick="lihatDetailPenerimaan({{ $row->id }})" style="text-decoration: none; color: inherit;">
                                    {{ $row->nomor_penerimaan }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_terima)->format('d-m-Y') }}</td>
                            <td>{{ $row->masterVendor ? $row->masterVendor->nama_vendor : '-' }}</td>
                            <td>
                                <div>{{ $row->nomor_faktur }}</div>
                                @if($row->kode_po || $row->orderPembelian)
                                    <small class="text-muted">PO: {{ $row->kode_po ?? ($row->orderPembelian->nomor_po ?? '') }}</small>
                                @endif
                            </td>
                            <td style="text-align: right; font-weight: 600;">
                                Rp {{ number_format($grandTotalVal, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                <span class="badge {{ $row->jenis_pembayaran === 'Tunai' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $row->jenis_pembayaran }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                {{ $row->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($row->tanggal_jatuh_tempo)->format('d-m-Y') : '-' }}
                            </td>
                            <td style="text-align: center;">
                                @if($row->status_pembayaran === 'Lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-danger">Belum Lunas</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                Rp {{ number_format($row->total_bayar, 0, ',', '.') }}
                            </td>
                            <td style="text-align: right; font-weight: 600; color: {{ $row->sisa_bayar > 0 ? '#d9534f' : '#28a745' }};">
                                Rp {{ number_format($row->sisa_bayar, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-info btn-sm py-0 px-2" onclick="lihatDetailPenerimaan({{ $row->id }})" title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <a href="{{ url('/barangmasuk/' . $row->id . '/cetak-pdf') }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" title="Cetak BKB/Faktur">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" onclick="hapusPenerimaan({{ $row->id }}, '{{ $row->nomor_penerimaan }}')" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" style="text-align: left; padding: 12px 15px; color: #555;">
                                Tidak ada data yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($data->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Menampilkan {{ $data->firstItem() }} s/d {{ $data->lastItem() }} dari {{ $data->total() }} data
                </div>
                <div>
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL DETAIL PENERIMAAN BARANG --}}
<div class="modal fade" id="modalDetailPenerimaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background-color: #f39c12; color: #ffffff; padding: 10px 15px;">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa fa-file-invoice me-1"></i> Detail Transaksi Penerimaan Barang (<span id="detModalKode"></span>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2 mb-3 bg-light p-2 rounded border">
                    <div class="col-md-3">
                        <small class="text-muted d-block">Tanggal Terima</small>
                        <strong id="detModalTanggal">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Vendor / PBF</small>
                        <strong id="detModalVendor">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">No. Faktur</small>
                        <strong id="detModalFaktur">-</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Kode PO</small>
                        <strong id="detModalPo">-</strong>
                    </div>
                    <div class="col-md-3 mt-2">
                        <small class="text-muted d-block">Jenis Pembayaran</small>
                        <span id="detModalJenisBayar" class="badge bg-secondary">-</span>
                    </div>
                    <div class="col-md-3 mt-2">
                        <small class="text-muted d-block">Status Pembayaran</small>
                        <span id="detModalStatusBayar" class="badge bg-secondary">-</span>
                    </div>
                    <div class="col-md-3 mt-2">
                        <small class="text-muted d-block">Jatuh Tempo</small>
                        <strong id="detModalJatuhTempo">-</strong>
                    </div>
                    <div class="col-md-3 mt-2">
                        <small class="text-muted d-block">Grand Total</small>
                        <strong id="detModalGrandTotal" class="text-primary">-</strong>
                    </div>
                </div>

                <h6 class="fw-bold mb-2" style="font-size: 13px;">Daftar Item Barang & Obat Diterima:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead style="background-color: #a6a6a6; color: white;">
                            <tr>
                                <th style="width: 35px; text-align: center;">No</th>
                                <th>Gudang / Lokasi</th>
                                <th>Kode & Nama Barang</th>
                                <th style="text-align: center;">Kemasan</th>
                                <th>Batch / Exp / BPOM</th>
                                <th style="text-align: right;">Qty</th>
                                <th style="text-align: right;">HNA (Rp)</th>
                                <th style="text-align: right;">Disc</th>
                                <th style="text-align: right;">Sub Total (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="detModalTableBody">
                            <!-- Populated by JS -->
                        </tbody>
                        <tfoot style="background-color: #f9f9f9; font-weight: bold;">
                            <tr>
                                <td colspan="8" class="text-end">Total Nilai Barang:</td>
                                <td class="text-end" id="detModalSubtotal">Rp 0</td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-end">Diskon Total:</td>
                                <td class="text-end text-danger" id="detModalDiskon">Rp 0</td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-end">PPN:</td>
                                <td class="text-end text-success" id="detModalPpn">Rp 0</td>
                            </tr>
                            <tr style="font-size: 13px;">
                                <td colspan="8" class="text-end">Grand Total Penerimaan:</td>
                                <td class="text-end text-primary" id="detModalGrandTotalFoot">Rp 0</td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-end">Total Bayar (DP/Lunas):</td>
                                <td class="text-end" id="detModalTotalBayar">Rp 0</td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-end">Sisa Hutang:</td>
                                <td class="text-end text-danger" id="detModalSisaBayar">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-1 px-3">
                <a id="detModalPrintBtn" href="#" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fa fa-print me-1"></i> Cetak Faktur (BKB)
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSearchFilter() {
        $('#filterSearchSection').slideToggle(200);
    }

    function formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    }

    function formatDate(dStr) {
        if (!dStr) return '-';
        var d = new Date(dStr);
        if (isNaN(d.getTime())) return dStr;
        var day = String(d.getDate()).padStart(2, '0');
        var mon = String(d.getMonth() + 1).padStart(2, '0');
        var yr = d.getFullYear();
        return day + '-' + mon + '-' + yr;
    }

    function lihatDetailPenerimaan(id) {
        $.ajax({
            url: '/barangmasuk/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.data) {
                    var m = res.data;
                    $('#detModalKode').text(m.nomor_penerimaan);
                    $('#detModalTanggal').text(formatDate(m.tanggal_terima));
                    $('#detModalVendor').text(m.master_vendor ? m.master_vendor.nama_vendor : '-');
                    $('#detModalFaktur').text(m.nomor_faktur || '-');
                    $('#detModalPo').text(m.kode_po || (m.order_pembelian ? m.order_pembelian.nomor_po : '-'));
                    $('#detModalJenisBayar').text(m.jenis_pembayaran || 'Tunai');
                    $('#detModalStatusBayar').text(m.status_pembayaran || 'Lunas').attr('class', 'badge ' + (m.status_pembayaran === 'Lunas' ? 'bg-success' : 'bg-danger'));
                    $('#detModalJatuhTempo').text(formatDate(m.tanggal_jatuh_tempo));
                    
                    var gTotal = m.grand_total > 0 ? m.grand_total : m.total_tagihan;
                    $('#detModalGrandTotal').text(formatRupiah(gTotal));
                    $('#detModalSubtotal').text(formatRupiah(m.subtotal || gTotal));
                    $('#detModalDiskon').text(formatRupiah(m.diskon_total_nominal || 0));
                    $('#detModalPpn').text(formatRupiah(m.ppn_nominal || 0));
                    $('#detModalGrandTotalFoot').text(formatRupiah(gTotal));
                    $('#detModalTotalBayar').text(formatRupiah(m.total_bayar || 0));
                    $('#detModalSisaBayar').text(formatRupiah(m.sisa_bayar || 0));

                    var rows = '';
                    if (m.details && m.details.length > 0) {
                        m.details.forEach(function(d, i) {
                            var gudangNama = d.master_gudang ? d.master_gudang.nama_gudang : '-';
                            if (d.lokasi_rak) gudangNama += ' (' + d.lokasi_rak + ')';

                            var batchInfo = '';
                            if (d.nomor_batch) batchInfo += 'Batch: ' + d.nomor_batch;
                            if (d.tanggal_kadaluarsa) batchInfo += ' | Exp: ' + formatDate(d.tanggal_kadaluarsa);
                            if (d.nomor_bpom) batchInfo += ' | BPOM: ' + d.nomor_bpom;
                            if (!batchInfo) batchInfo = '-';

                            var brgNama = d.master_barang ? d.master_barang.nama_barang : ('Item #' + d.master_barang_id);
                            var brgKode = (d.master_barang && d.master_barang.kode_barang) ? ('[' + d.master_barang.kode_barang + '] ') : '';

                            rows += '<tr>' +
                                '<td class="text-center">' + (i + 1) + '</td>' +
                                '<td>' + gudangNama + '</td>' +
                                '<td><strong>' + brgKode + brgNama + '</strong></td>' +
                                '<td class="text-center">' + (d.kemasan || '-') + '</td>' +
                                '<td><small>' + batchInfo + '</small></td>' +
                                '<td class="text-end">' + Number(d.jumlah_terima).toLocaleString('id-ID') + '</td>' +
                                '<td class="text-end">' + formatRupiah(d.harga_satuan) + '</td>' +
                                '<td class="text-end">' + (d.diskon_nominal > 0 ? formatRupiah(d.diskon_nominal) : (d.diskon_persen > 0 ? (d.diskon_persen + '%') : '0')) + '</td>' +
                                '<td class="text-end fw-bold">' + formatRupiah(d.subtotal) + '</td>' +
                            '</tr>';
                        });
                    } else {
                        rows = '<tr><td colspan="9" class="text-center text-muted">Tidak ada rincian item.</td></tr>';
                    }
                    $('#detModalTableBody').html(rows);

                    $('#detModalPrintBtn').attr('href', '/barangmasuk/' + m.id + '/cetak-pdf');
                    $('#modalDetailPenerimaan').modal('show');
                }
            },
            error: function() {
                alert('Gagal mengambil data detail penerimaan barang.');
            }
        });
    }

    function hapusPenerimaan(id, kode) {
        if (!confirm('Apakah Anda yakin ingin menghapus Transaksi Penerimaan Barang (' + kode + ')? Stok barang akan dikurangi kembali.')) {
            return;
        }

        $.ajax({
            url: '/barangmasuk/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Gagal: ' + res.message);
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                alert('Gagal: ' + msg);
            }
        });
    }
</script>
@endsection
