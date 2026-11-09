@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* PROFASKES 1:1 FORM PENERIMAAN BARANG & OBAT */
    .bm-create-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .profaskes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .profaskes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #444;
        text-decoration: none;
    }
    .profaskes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .card-prof-box {
        background-color: #fff;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px 20px;
        margin-bottom: 15px;
    }
    .prof-card-title {
        font-size: 15px;
        font-weight: 600;
        color: #444;
        margin-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 8px;
    }
    .form-group-prof {
        margin-bottom: 12px;
    }
    .form-group-prof label {
        font-size: 12px;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
        display: block;
    }
    .form-group-prof label .wajib {
        color: #dd4b39;
        font-size: 11px;
    }
    .form-control-prof {
        height: 32px;
        font-size: 12.5px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 4px 8px;
        width: 100%;
        color: #333;
        background-color: #fff;
    }
    .form-control-prof:focus {
        border-color: #3c8dbc;
        outline: none;
        box-shadow: none;
    }
    .form-control-prof[readonly] {
        background-color: #eeeeee !important;
        cursor: not-allowed;
    }
    .btn-quick-vendor {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff;
        height: 32px;
        padding: 0 10px;
        border-radius: 2px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-quick-vendor:hover {
        background-color: #0097bc;
        color: #fff;
    }
    .btn-prof-tambah {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        font-size: 12.5px;
        padding: 6px 15px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
    }
    .btn-prof-tambah:hover {
        background-color: #008d4c;
    }
    .btn-prof-tutup {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 12.5px;
        padding: 6px 15px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
    }
    .btn-prof-tutup:hover {
        background-color: #d68407;
    }
    .table-detail-input {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        border: 1px solid #dcdcdc;
    }
    .table-detail-input thead th {
        background-color: #a6a6a6;
        color: #ffffff;
        font-weight: 600;
        padding: 8px 10px;
        border: 1px solid #999;
        white-space: nowrap;
    }
    .table-detail-input tbody td {
        padding: 6px 10px;
        border: 1px solid #e5e5e5;
        vertical-align: middle;
    }
    .table-detail-input tfoot td {
        padding: 6px 10px;
        border: 1px solid #dcdcdc;
    }
    .cyan-divider {
        height: 3px;
        background-color: #00c0ef;
        margin-top: 15px;
        margin-bottom: 15px;
    }
</style>

<div class="bm-create-page">
    {{-- Header & Breadcrumbs --}}
    <div class="profaskes-header">
        <h1 class="profaskes-title">Form Penerimaan Barang & Obat</h1>
        <div class="profaskes-breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> &gt; 
            <a href="{{ url('/barangmasuk/index') }}">Barangmasukheaders</a> &gt; 
            <span>Tambah Barang Masuk</span>
        </div>
    </div>

    <form id="formPenerimaanBarang" onsubmit="submitFormPenerimaan(event)">
        @csrf

        {{-- Top Bar Card: Header Information --}}
        <div class="card-prof-box">
            <div class="row g-3">
                <div class="col-md-2 form-group-prof">
                    <label>Kode Penerimaan</label>
                    <input type="text" class="form-control-prof fw-bold" id="nomor_penerimaan" name="nomor_penerimaan" value="{{ $nomorPenerimaan }}" readonly required>
                </div>
                <div class="col-md-2 form-group-prof">
                    <label>Tanggal Terima</label>
                    <input type="date" class="form-control-prof" id="tanggal_terima" name="tanggal_terima" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3 form-group-prof">
                    <label>Nama Vendor <span class="wajib">*wajib!</span></label>
                    <div class="d-flex gap-1">
                        <select class="form-control-prof flex-fill" id="master_vendor_id" name="master_vendor_id" required>
                            <option value="">Ketik Nama Vendor</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}">{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn-quick-vendor" data-bs-toggle="modal" data-bs-target="#modalQuickVendor" title="Tambah Vendor Cepat">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3 form-group-prof">
                    <label>No Faktur Penerimaan <span class="wajib">*wajib!</span></label>
                    <input type="text" class="form-control-prof" id="nomor_faktur" name="nomor_faktur" placeholder="Masukkan nomor faktur..." required>
                </div>
                <div class="col-md-2 form-group-prof">
                    <label>Kode PO</label>
                    <select class="form-control-prof" id="order_pembelian_id" name="order_pembelian_id" onchange="onSelectPO(this)">
                        <option value="">-- Tanpa PO --</option>
                        @foreach($pos as $po)
                            <option value="{{ $po->id }}" data-nomor="{{ $po->nomor_po }}" data-vendor-id="{{ $po->master_vendor_id }}" {{ (isset($selectedPoId) && $selectedPoId == $po->id) ? 'selected' : '' }}>
                                {{ $po->nomor_po }} ({{ $po->masterVendor ? $po->masterVendor->nama_vendor : '-' }})
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" id="kode_po" name="kode_po" value="">
                </div>
            </div>
        </div>

        {{-- Card 1: Detail Barang (Item Input Form) --}}
        <div class="card-prof-box" id="boxDetailBarang">
            <div class="prof-card-title">Detail Barang</div>

            {{-- Row 1: Gudang, Lokasi/Rak, Item Barang, Kemasan, Harga, Jumlah, Disc.Item, Sub Total --}}
            <div class="row g-2 align-items-start">
                <div class="col-md-2 form-group-prof">
                    <label>Gudang <span class="wajib">*wajib!</span></label>
                    <select class="form-control-prof" id="in_gudang_id">
                        <option value="">--Pilih--</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 form-group-prof">
                    <label>Lokasi/Rak</label>
                    <select class="form-control-prof" id="in_lokasi_rak">
                        <option value="">--Pilih--</option>
                        <option value="Rak A">Rak A</option>
                        <option value="Rak B">Rak B</option>
                        <option value="Rak C">Rak C</option>
                        <option value="Rak D">Rak D</option>
                        <option value="Lemari Dingin">Lemari Dingin</option>
                        <option value="Etalase Depan">Etalase Depan</option>
                    </select>
                </div>
                <div class="col-md-3 form-group-prof">
                    <label>Item Barang / Obat <span class="wajib">*wajib!</span></label>
                    <select class="form-control-prof" id="in_barang_id" onchange="onSelectBarang(this)">
                        <option value="">--Pilih--</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}" 
                                    data-kode="{{ $b->kode_barang }}" 
                                    data-nama="{{ $b->nama_barang }}" 
                                    data-kemasan="{{ $b->kemasan ?? 'Box' }}" 
                                    data-harga="{{ $b->harga_beli ?: ($b->hna ?: 0) }}">
                                {{ $b->nama_barang }} ({{ $b->kode_barang }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-danger d-block" style="font-size: 10.5px; margin-top: 2px;">
                        Barang harus berbeda disetiap baris input
                    </small>
                </div>
                <div class="col-md-1 form-group-prof">
                    <label>Kemasan</label>
                    <input type="text" class="form-control-prof" id="in_kemasan" placeholder="Kemasan">
                </div>
                <div class="col-md-2 form-group-prof">
                    <label>Harga <span class="wajib">*wajib!</span></label>
                    <input type="number" step="any" class="form-control-prof text-end" id="in_harga" value="0" oninput="hitungSubtotalBaris()">
                </div>
                <div class="col-md-1 form-group-prof">
                    <label>Jumlah <span class="wajib">*wajib!</span></label>
                    <input type="number" step="any" class="form-control-prof text-end" id="in_jumlah" value="1" oninput="hitungSubtotalBaris()">
                </div>
                <div class="col-md-1 form-group-prof">
                    <label>Disc.Item</label>
                    <div class="d-flex">
                        <input type="number" step="any" class="form-control-prof text-end" id="in_disc_val" value="0" style="width: 60%;" oninput="hitungSubtotalBaris()">
                        <select class="form-control-prof" id="in_disc_type" style="width: 40%; padding: 4px 2px;" onchange="hitungSubtotalBaris()">
                            <option value="%">%</option>
                            <option value="Rp">Rp</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1 form-group-prof">
                    <label>Sub Total</label>
                    <input type="text" class="form-control-prof text-end fw-bold" id="in_subtotal_preview" value="0" readonly>
                </div>
            </div>

            {{-- Row 2: No Batch, Tgl Expired, No BPOM --}}
            <div class="row g-2 align-items-start mt-1">
                <div class="col-md-2 form-group-prof">
                    <label>No.Batch Produksi</label>
                    <input type="text" class="form-control-prof" id="in_no_batch" placeholder="No. Batch...">
                </div>
                <div class="col-md-2 form-group-prof">
                    <label>Tgl Expired Obat</label>
                    <input type="date" class="form-control-prof" id="in_tgl_exp">
                </div>
                <div class="col-md-2 form-group-prof">
                    <label>No. BPOM</label>
                    <input type="text" class="form-control-prof" id="in_no_bpom" placeholder="Nomor BPOM...">
                    <small class="text-muted d-block" style="font-size: 10.5px; margin-top: 2px;">Isi jika ada</small>
                </div>

                {{-- Row 3 (Right Side Summary Fields in Row): Diskon Total, PPN, Grand Total --}}
                <div class="col-md-2 form-group-prof ms-auto">
                    <label>Diskon/total</label>
                    <div class="d-flex">
                        <input type="number" step="any" class="form-control-prof text-end" id="diskon_total_persen" name="diskon_total_persen" value="0" style="width: 65%;" oninput="hitungKalkulasiTotal()">
                        <span class="input-group-text p-1 text-center" style="font-size: 12px; width: 35%; justify-content: center; background: #eee;">%</span>
                    </div>
                </div>
                <div class="col-md-1 form-group-prof">
                    <label>PPN(%)</label>
                    <input type="number" step="any" class="form-control-prof text-end" id="ppn_persen" name="ppn_persen" value="0" oninput="hitungKalkulasiTotal()">
                </div>
                <div class="col-md-2 form-group-prof">
                    <label>Grand Total Penerimaan</label>
                    <input type="text" class="form-control-prof text-end fw-bold text-primary" id="preview_grand_total" value="0" readonly>
                </div>
            </div>

            {{-- Buttons Row: OK Tambahkan & Tutup --}}
            <div class="d-flex gap-2 mt-3 pt-2 border-top">
                <button type="button" class="btn btn-prof-tambah" onclick="tambahBarisItem()">
                    <i class="fa fa-save"></i> OK, Tambahkan!
                </button>
                <button type="button" class="btn btn-prof-tutup" onclick="resetFormItem()">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>

        {{-- Card 2: Detail Input (Table of Items Added) --}}
        <div class="card-prof-box">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="prof-card-title mb-0 border-0 p-0">Detail Input</div>
                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 11px;" onclick="kosongkanSemuaItem()">
                    <i class="fa fa-trash"></i> Bersihkan Tabel
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-detail-input" id="tableDetailInput">
                    <thead>
                        <tr>
                            <th style="width: 35px; text-align: center;">No</th>
                            <th style="width: 140px;">Gudang</th>
                            <th style="width: 100px;">Lokasi/Rak</th>
                            <th style="width: 110px;">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th style="width: 180px;">No Batch & Tgl.Expired</th>
                            <th style="width: 70px; text-align: right;">Jumlah</th>
                            <th style="width: 110px; text-align: right;">HNA</th>
                            <th style="width: 100px; text-align: right;">Diskon(Rp)</th>
                            <th style="width: 120px; text-align: right;">Sub Total (Rp)</th>
                            <th style="width: 45px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bodyItemsPenerimaan">
                        <tr id="emptyRowPenerimaan">
                            <td colspan="11" class="text-center py-3 text-muted" style="font-size: 12px;">
                                Belum ada item barang yang ditambahkan. Silakan gunakan form di atas lalu klik "OK, Tambahkan!".
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #fcfcfc; font-weight: bold;">
                            <td colspan="9" class="text-end" style="border-right: none;">Total</td>
                            <td class="text-end" id="sumFooterTotal" style="border-left: none;">0,00</td>
                            <td></td>
                        </tr>
                        <tr style="background-color: #fcfcfc; font-weight: bold;">
                            <td colspan="9" class="text-end" style="border-right: none;" id="labelDiskonTotal">Diskon Total 0,00%</td>
                            <td class="text-end text-danger" id="sumFooterDiskon" style="border-left: none;">0,00</td>
                            <td></td>
                        </tr>
                        <tr style="background-color: #fcfcfc; font-weight: bold;">
                            <td colspan="9" class="text-end" style="border-right: none;" id="labelPpnTotal">PPN 0,00%</td>
                            <td class="text-end text-success" id="sumFooterPpn" style="border-left: none;">0,00</td>
                            <td></td>
                        </tr>
                        <tr style="background-color: #f3f3f3; font-weight: bold; font-size: 13px;">
                            <td colspan="9" class="text-end" style="border-right: none;">Grand Total Penerimaan</td>
                            <td class="text-end text-primary" id="sumFooterGrandTotal" style="border-left: none;">0,00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Card 3: Pembayaran & Selesaikan Transaksi --}}
        <div class="card-prof-box">
            <div class="prof-card-title">Pembayaran & Selesai Transaksi</div>
            <div class="row g-3 align-items-center">
                <div class="col-md-2 form-group-prof">
                    <label>Jenis Pembayaran <span class="wajib">*wajib!</span></label>
                    <select class="form-control-prof fw-bold" id="jenis_pembayaran" name="jenis_pembayaran" onchange="onChangeJenisBayar(this)">
                        <option value="Kredit">Kredit (Hutang)</option>
                        <option value="Tunai">Tunai (Lunas)</option>
                    </select>
                </div>
                <div class="col-md-2 form-group-prof" id="boxJatuhTempo">
                    <label>Jatuh Tempo</label>
                    <input type="date" class="form-control-prof" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                </div>
                <div class="col-md-2 form-group-prof">
                    <label>Total Bayar / DP (Rp)</label>
                    <input type="number" step="any" class="form-control-prof text-end" id="total_bayar" name="total_bayar" value="0" oninput="hitungSisaBayar()">
                </div>
                <div class="col-md-2 form-group-prof">
                    <label>Sisa Bayar / Hutang (Rp)</label>
                    <input type="text" class="form-control-prof text-end fw-bold text-danger" id="preview_sisa_bayar" value="0" readonly>
                    <input type="hidden" id="sisa_bayar" name="sisa_bayar" value="0">
                </div>
                <div class="col-md-4 form-group-prof">
                    <label>Keterangan (Opsional)</label>
                    <input type="text" class="form-control-prof" id="keterangan" name="keterangan" placeholder="Catatan penerimaan barang...">
                </div>
            </div>

            <div class="cyan-divider"></div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ url('/barangmasuk/index') }}" class="btn btn-secondary btn-sm px-3">
                    <i class="fa fa-arrow-left me-1"></i> Batal & Kembali
                </a>
                <button type="submit" class="btn btn-success px-4" id="btnSubmitPenerimaan" style="font-size: 13.5px; font-weight: 600;">
                    <i class="fa fa-check-circle me-1"></i> Simpan Seluruh Penerimaan Barang
                </button>
            </div>
        </div>

        {{-- Hidden fields for calculated totals --}}
        <input type="hidden" id="subtotal_sum" name="subtotal" value="0">
        <input type="hidden" id="diskon_total_nominal" name="diskon_total_nominal" value="0">
        <input type="hidden" id="ppn_nominal" name="ppn_nominal" value="0">
        <input type="hidden" id="grand_total" name="grand_total" value="0">
    </form>
</div>

{{-- MODAL QUICK ADD VENDOR --}}
<div class="modal fade" id="modalQuickVendor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background-color: #00c0ef; color: white;">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-truck me-1"></i> Tambah Master Vendor Cepat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formQuickVendor">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 12px;">Nama Vendor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="qv_nama" required placeholder="Contoh: PT. Kimia Farma Trading">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 12px;">No. Telepon</label>
                        <input type="text" class="form-control form-control-sm" id="qv_telepon" placeholder="021-...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 12px;">Alamat</label>
                        <textarea class="form-control form-control-sm" id="qv_alamat" rows="2" placeholder="Alamat vendor..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-info btn-sm text-white" onclick="simpanQuickVendor()"><i class="fa fa-save me-1"></i> Simpan Vendor</button>
            </div>
        </div>
    </div>
</div>

<script>
    var itemsList = [];

    function formatNumber(num) {
        return Number(num || 0).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function onSelectBarang(sel) {
        var opt = $(sel).find(':selected');
        if (!opt.val()) {
            $('#in_kemasan').val('');
            $('#in_harga').val(0);
            hitungSubtotalBaris();
            return;
        }

        var kemasan = opt.data('kemasan') || 'Box';
        var harga = opt.data('harga') || 0;

        $('#in_kemasan').val(kemasan);
        $('#in_harga').val(harga);
        hitungSubtotalBaris();
    }

    function hitungSubtotalBaris() {
        var harga = parseFloat($('#in_harga').val()) || 0;
        var jumlah = parseFloat($('#in_jumlah').val()) || 0;
        var discVal = parseFloat($('#in_disc_val').val()) || 0;
        var discType = $('#in_disc_type').val();

        var baseTotal = harga * jumlah;
        var discNominal = 0;
        if (discType === '%') {
            discNominal = (baseTotal * discVal) / 100;
        } else {
            discNominal = discVal;
        }

        var sub = Math.max(0, baseTotal - discNominal);
        $('#in_subtotal_preview').val(formatNumber(sub));
    }

    function tambahBarisItem() {
        var barangId = $('#in_barang_id').val();
        if (!barangId) {
            alert('Silakan pilih Item Barang/Obat terlebih dahulu.');
            $('#in_barang_id').focus();
            return;
        }

        // Cek duplikasi barang
        var existing = itemsList.find(function(it) {
            return String(it.master_barang_id) === String(barangId);
        });
        if (existing) {
            alert('Barang ini sudah ada di daftar input. Sesuai aturan PROFASKES, barang harus berbeda di setiap baris input. Silakan edit atau sesuaikan jumlah baris yang ada.');
            return;
        }

        var jumlah = parseFloat($('#in_jumlah').val()) || 0;
        if (jumlah <= 0) {
            alert('Jumlah barang harus lebih dari 0.');
            $('#in_jumlah').focus();
            return;
        }

        var harga = parseFloat($('#in_harga').val()) || 0;
        var gudangId = $('#in_gudang_id').val();
        var gudangNama = $('#in_gudang_id option:selected').text();
        if (!gudangId) {
            alert('Silakan pilih Gudang tujuan penyimpanan.');
            $('#in_gudang_id').focus();
            return;
        }

        var opt = $('#in_barang_id option:selected');
        var brgNama = opt.data('nama');
        var brgKode = opt.data('kode');
        var kemasan = $('#in_kemasan').val() || opt.data('kemasan') || 'Box';
        var lokasiRak = $('#in_lokasi_rak').val() || '';

        var discVal = parseFloat($('#in_disc_val').val()) || 0;
        var discType = $('#in_disc_type').val();
        var baseTotal = harga * jumlah;
        var discNominal = (discType === '%') ? ((baseTotal * discVal) / 100) : discVal;
        var subtotal = Math.max(0, baseTotal - discNominal);

        var noBatch = $('#in_no_batch').val() || '';
        var tglExp = $('#in_tgl_exp').val() || '';
        var noBpom = $('#in_no_bpom').val() || '';

        itemsList.push({
            master_gudang_id: gudangId,
            gudang_nama: gudangNama,
            lokasi_rak: lokasiRak,
            master_barang_id: barangId,
            kode_barang: brgKode,
            nama_barang: brgNama,
            kemasan: kemasan,
            harga: harga,
            jumlah: jumlah,
            disc_persen: discType === '%' ? discVal : 0,
            disc_nominal: discNominal,
            subtotal: subtotal,
            no_batch: noBatch,
            tgl_exp: tglExp,
            no_bpom: noBpom
        });

        renderTableItems();
        resetFormItem();
    }

    function resetFormItem() {
        $('#in_barang_id').val('');
        $('#in_kemasan').val('');
        $('#in_harga').val(0);
        $('#in_jumlah').val(1);
        $('#in_disc_val').val(0);
        $('#in_disc_type').val('%');
        $('#in_subtotal_preview').val('0,00');
        $('#in_no_batch').val('');
        $('#in_tgl_exp').val('');
        $('#in_no_bpom').val('');
    }

    function hapusItem(idx) {
        itemsList.splice(idx, 1);
        renderTableItems();
    }

    function kosongkanSemuaItem() {
        if (itemsList.length === 0) return;
        if (confirm('Apakah Anda yakin ingin mengosongkan seluruh tabel item?')) {
            itemsList = [];
            renderTableItems();
        }
    }

    function renderTableItems() {
        var tbody = $('#bodyItemsPenerimaan');
        if (itemsList.length === 0) {
            tbody.html('<tr id="emptyRowPenerimaan"><td colspan="11" class="text-center py-3 text-muted" style="font-size: 12px;">Belum ada item barang yang ditambahkan. Silakan gunakan form di atas lalu klik "OK, Tambahkan!".</td></tr>');
            hitungKalkulasiTotal();
            return;
        }

        var rows = '';
        itemsList.forEach(function(item, idx) {
            var batchExp = '';
            if (item.no_batch) batchExp += '<strong>B:</strong> ' + item.no_batch;
            if (item.tgl_exp) batchExp += (batchExp ? ' | ' : '') + '<strong>Exp:</strong> ' + item.tgl_exp;
            if (!batchExp) batchExp = '-';

            rows += '<tr>' +
                '<td style="text-align: center;">' + (idx + 1) + '</td>' +
                '<td>' + item.gudang_nama + '</td>' +
                '<td>' + (item.lokasi_rak || '-') + '</td>' +
                '<td>' + (item.kode_barang || '-') + '</td>' +
                '<td><strong>' + item.nama_barang + '</strong><br><small class="text-muted">' + item.kemasan + '</small></td>' +
                '<td><small>' + batchExp + '</small></td>' +
                '<td style="text-align: right;">' + Number(item.jumlah).toLocaleString('id-ID') + '</td>' +
                '<td style="text-align: right;">' + formatNumber(item.harga) + '</td>' +
                '<td style="text-align: right;">' + formatNumber(item.disc_nominal) + '</td>' +
                '<td style="text-align: right; font-weight: 600;">' + formatNumber(item.subtotal) + '</td>' +
                '<td style="text-align: center;">' +
                    '<button type="button" class="btn btn-outline-danger btn-sm py-0 px-1" onclick="hapusItem(' + idx + ')" title="Hapus baris">' +
                        '<i class="fa fa-trash"></i>' +
                    '</button>' +
                '</td>' +
            '</tr>';
        });

        tbody.html(rows);
        hitungKalkulasiTotal();
    }

    function hitungKalkulasiTotal() {
        var totalSub = 0;
        itemsList.forEach(function(it) {
            totalSub += it.subtotal;
        });

        var discPersen = parseFloat($('#diskon_total_persen').val()) || 0;
        var discNominal = (totalSub * discPersen) / 100;
        var afterDisc = Math.max(0, totalSub - discNominal);

        var ppnPersen = parseFloat($('#ppn_persen').val()) || 0;
        var ppnNominal = (afterDisc * ppnPersen) / 100;

        var grandTotal = afterDisc + ppnNominal;

        // Update Footers
        $('#sumFooterTotal').text(formatNumber(totalSub));
        $('#labelDiskonTotal').text('Diskon Total ' + formatNumber(discPersen) + '%');
        $('#sumFooterDiskon').text(formatNumber(discNominal));
        $('#labelPpnTotal').text('PPN ' + formatNumber(ppnPersen) + '%');
        $('#sumFooterPpn').text(formatNumber(ppnNominal));
        $('#sumFooterGrandTotal').text(formatNumber(grandTotal));

        // Update Card 1 Preview
        $('#preview_grand_total').val(formatNumber(grandTotal));

        // Update Hidden form inputs
        $('#subtotal_sum').val(totalSub);
        $('#diskon_total_nominal').val(discNominal);
        $('#ppn_nominal').val(ppnNominal);
        $('#grand_total').val(grandTotal);

        // Update payment defaults
        var jenisBayar = $('#jenis_pembayaran').val();
        if (jenisBayar === 'Tunai') {
            $('#total_bayar').val(grandTotal);
        }
        hitungSisaBayar();
    }

    function onChangeJenisBayar(sel) {
        var val = $(sel).val();
        var gTotal = parseFloat($('#grand_total').val()) || 0;
        if (val === 'Tunai') {
            $('#boxJatuhTempo').hide();
            $('#total_bayar').val(gTotal);
        } else {
            $('#boxJatuhTempo').show();
            if (parseFloat($('#total_bayar').val()) === gTotal) {
                $('#total_bayar').val(0);
            }
        }
        hitungSisaBayar();
    }

    function hitungSisaBayar() {
        var grandTotal = parseFloat($('#grand_total').val()) || 0;
        var bayar = parseFloat($('#total_bayar').val()) || 0;
        var sisa = Math.max(0, grandTotal - bayar);

        $('#preview_sisa_bayar').val(formatNumber(sisa));
        $('#sisa_bayar').val(sisa);
    }

    function onSelectPO(sel) {
        var poId = $(sel).val();
        var opt = $(sel).find(':selected');
        var noPo = opt.data('nomor') || '';
        $('#kode_po').val(noPo);

        if (!poId) return;

        var vendorId = opt.data('vendor-id');
        if (vendorId) {
            $('#master_vendor_id').val(vendorId);
        }

        // Fetch items from PO via AJAX
        $.ajax({
            url: '/barangmasuk/get-po/' + poId,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.po && res.po.details) {
                    if (confirm('PO ' + res.po.nomor_po + ' memiliki ' + res.po.details.length + ' item obat/barang. Apakah Anda ingin memuat item-item tersebut ke tabel penerimaan?')) {
                        itemsList = [];
                        var defGudang = $('#in_gudang_id').val();
                        var defGudangNama = $('#in_gudang_id option:selected').text();

                        res.po.details.forEach(function(d) {
                            var b = d.master_barang;
                            if (b) {
                                var sub = parseFloat(d.subtotal) || (parseFloat(d.jumlah) * parseFloat(d.harga_satuan));
                                itemsList.push({
                                    master_gudang_id: defGudang,
                                    gudang_nama: defGudangNama,
                                    lokasi_rak: 'Rak A',
                                    master_barang_id: b.id,
                                    kode_barang: b.kode_barang,
                                    nama_barang: b.nama_barang,
                                    kemasan: d.kemasan || b.kemasan || 'Box',
                                    harga: parseFloat(d.harga_satuan) || 0,
                                    jumlah: parseFloat(d.jumlah) || 1,
                                    disc_persen: 0,
                                    disc_nominal: 0,
                                    subtotal: sub,
                                    no_batch: '',
                                    tgl_exp: '',
                                    no_bpom: ''
                                });
                            }
                        });
                        renderTableItems();
                    }
                }
            }
        });
    }

    function simpanQuickVendor() {
        var nama = $('#qv_nama').val().trim();
        if (!nama) {
            alert('Nama vendor wajib diisi.');
            $('#qv_nama').focus();
            return;
        }

        $.ajax({
            url: '/barangmasuk/quick-vendor',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nama_vendor: nama,
                telepon: $('#qv_telepon').val(),
                alamat: $('#qv_alamat').val()
            },
            dataType: 'json',
            success: function(res) {
                if (res.success && res.vendor) {
                    var newOpt = $('<option>', {
                        value: res.vendor.id,
                        text: res.vendor.nama_vendor,
                        selected: true
                    });
                    $('#master_vendor_id').append(newOpt).val(res.vendor.id);
                    $('#modalQuickVendor').modal('hide');
                    $('#formQuickVendor')[0].reset();
                    alert('Vendor ' + res.vendor.nama_vendor + ' berhasil ditambahkan!');
                }
            },
            error: function(xhr) {
                var err = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan vendor.';
                alert('Gagal: ' + err);
            }
        });
    }

    function submitFormPenerimaan(e) {
        e.preventDefault();

        if (itemsList.length === 0) {
            alert('Tabel Detail Input masih kosong! Minimal harus ada 1 item barang yang diterima.');
            return;
        }

        var vendorId = $('#master_vendor_id').val();
        if (!vendorId) {
            alert('Silakan pilih Nama Vendor.');
            $('#master_vendor_id').focus();
            return;
        }

        var noFaktur = $('#nomor_faktur').val().trim();
        if (!noFaktur) {
            alert('Nomor Faktur Penerimaan wajib diisi.');
            $('#nomor_faktur').focus();
            return;
        }

        var btn = $('#btnSubmitPenerimaan');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan Penerimaan...');

        var formData = {
            _token: '{{ csrf_token() }}',
            nomor_penerimaan: $('#nomor_penerimaan').val(),
            tanggal_terima: $('#tanggal_terima').val(),
            master_vendor_id: vendorId,
            nomor_faktur: noFaktur,
            order_pembelian_id: $('#order_pembelian_id').val() || null,
            kode_po: $('#kode_po').val() || null,
            jenis_pembayaran: $('#jenis_pembayaran').val(),
            tanggal_jatuh_tempo: $('#tanggal_jatuh_tempo').val() || null,
            total_bayar: $('#total_bayar').val(),
            sisa_bayar: $('#sisa_bayar').val(),
            keterangan: $('#keterangan').val(),
            diskon_total_persen: $('#diskon_total_persen').val(),
            diskon_total_nominal: $('#diskon_total_nominal').val(),
            ppn_persen: $('#ppn_persen').val(),
            ppn_nominal: $('#ppn_nominal').val(),
            subtotal: $('#subtotal_sum').val(),
            grand_total: $('#grand_total').val(),
            items: itemsList
        };

        $.ajax({
            url: '/barangmasuk/store',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    window.location.href = res.redirect || '/barangmasuk/index';
                } else {
                    alert('Gagal: ' + res.message);
                    btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Simpan Seluruh Penerimaan Barang');
                }
            },
            error: function(xhr) {
                var msg = 'Terjadi kesalahan sistem.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        var firstErr = Object.values(xhr.responseJSON.errors)[0];
                        msg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                    } else if (xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                }
                alert('Gagal: ' + msg);
                btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Simpan Seluruh Penerimaan Barang');
            }
        });
    }

    $(document).ready(function() {
        // Trigger default calculations
        hitungSubtotalBaris();
        hitungKalkulasiTotal();

        // Check if PO was pre-selected
        if ($('#order_pembelian_id').val()) {
            onSelectPO($('#order_pembelian_id'));
        }
    });
</script>
@endsection
