@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* PROFASKES 1:1 TAMBAH TRANSAKSI PENJUALAN APOTEK */
    .tpa-create-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .profaskes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    .profaskes-title {
        font-size: 19px;
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
    .card-profaskes {
        background-color: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px 20px;
        margin-bottom: 15px;
    }
    .card-profaskes-orange-top {
        border-top: 3px solid #f39c12;
    }
    .form-label-prof {
        font-size: 12px;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
        display: block;
    }
    .form-ctrl-prof {
        height: 32px;
        font-size: 12.5px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 4px 8px;
        width: 100%;
        background-color: #fff;
        color: #333;
    }
    .form-ctrl-prof:focus {
        border-color: #3c8dbc;
        outline: none;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 5px rgba(60,141,188,.5);
    }
    .form-ctrl-prof[readonly] {
        background-color: #eee;
        cursor: not-allowed;
    }
    .btn-prof-blue-plus {
        background-color: #00c0ef;
        color: #fff !important;
        border: none;
        border-radius: 2px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .btn-prof-blue-plus:hover {
        background-color: #00a7d0;
    }
    .btn-prof-orange {
        background-color: #f39c12;
        color: #ffffff !important;
        border: none;
        border-radius: 2px;
        padding: 5px 14px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-prof-orange:hover {
        background-color: #e08e0b;
    }
    .btn-prof-green-ok {
        background-color: #00a65a;
        color: #ffffff !important;
        border: none;
        border-radius: 2px;
        padding: 0 16px;
        height: 32px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-prof-green-ok:hover {
        background-color: #008d4c;
    }
    .btn-prof-cyan-sm {
        background-color: #00c0ef;
        color: #ffffff !important;
        border: none;
        border-radius: 2px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-prof-cyan-sm:hover {
        background-color: #00a7d0;
    }
    .link-blue-sub {
        font-size: 11px;
        color: #337ab7;
        text-decoration: none;
        cursor: pointer;
    }
    .link-blue-sub:hover {
        text-decoration: underline;
    }
    .helper-text-sub {
        font-size: 11px;
        color: #777;
        margin-top: 2px;
        display: block;
    }

    /* Table Detail Items */
    .table-profaskes-detail {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin-bottom: 20px;
    }
    .table-profaskes-detail th {
        background-color: #f4f4f4;
        color: #333;
        font-weight: 600;
        border: 1px solid #e3e3e3;
        padding: 8px 10px;
        vertical-align: middle;
        text-align: left;
    }
    .table-profaskes-detail td {
        border: 1px solid #e3e3e3;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }

    /* Summary Bar & Payment */
    .summary-pos-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        border-top: 1px solid #eee;
        padding-top: 15px;
        align-items: center;
    }
    @media (max-width: 992px) {
        .summary-pos-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 576px) {
        .summary-pos-container {
            grid-template-columns: 1fr;
        }
    }
    .summary-pos-box {
        text-align: left;
    }
    .summary-pos-label {
        font-size: 12px;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .summary-pos-value {
        font-size: 26px;
        font-weight: 700;
        color: #333;
        line-height: 1.1;
    }
    .summary-pos-note {
        font-size: 11px;
        color: #777;
        margin-top: 3px;
    }
    .btn-bayar-sekarang {
        background-color: #f39c12;
        color: #fff !important;
        border: none;
        border-radius: 3px;
        padding: 10px 15px;
        font-size: 15px;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    .btn-bayar-sekarang:hover {
        background-color: #e08e0b;
    }
</style>

<div class="tpa-create-page">
    {{-- Header --}}
    <div class="profaskes-header">
        <h1 class="profaskes-title">Tambah Transaksi Penjualan</h1>
        <div class="profaskes-breadcrumb">
            <a href="/dashboard">Beranda</a> &gt; <a href="{{ route('transaksipenjualan.index') }}">Transaksipenjualans</a> &gt; <span>Tambah Transaksi Penjualan</span>
        </div>
    </div>

    <form id="formPenjualanApotek">
        @csrf
        {{-- CARD 1: FORM HEADER TRANSAKSI --}}
        <div class="card-profaskes">
            <div class="row g-3">
                {{-- Column 1 --}}
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label-prof">Kode Transaksi</label>
                        <input type="text" class="form-ctrl-prof fw-bold" id="kode_transaksi" name="kode_transaksi" value="{{ $kodeTransaksi }}" readonly>
                    </div>
                    <div>
                        <label class="form-label-prof">Tanggal</label>
                        <input type="date" class="form-ctrl-prof" id="tanggal_penjualan" name="tanggal_penjualan" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                {{-- Column 2 --}}
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label-prof">Pelanggan</label>
                        <div class="d-flex gap-1">
                            <input type="text" class="form-ctrl-prof" id="pelanggan_nama" name="pelanggan_nama" placeholder="Ketik Nama Pasien / Tanggal Lahir" list="listPasien">
                            <datalist id="listPasien">
                                <!-- Populated dynamically or static -->
                            </datalist>
                            <input type="hidden" id="pasien_id" name="pasien_id" value="">
                            <input type="hidden" id="pelanggan_telp" name="pelanggan_telp" value="">
                            <button type="button" class="btn-prof-blue-plus" title="Tambah Pelanggan / Pasien Baru" data-bs-toggle="modal" data-bs-target="#modalQuickPasien">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="form-label-prof">No Resep Rujukan</label>
                        <input type="text" class="form-ctrl-prof" id="no_resep_rujukan" name="no_resep_rujukan" placeholder="">
                    </div>
                </div>

                {{-- Column 3 --}}
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label-prof">Dokter Rujukan</label>
                        <select class="form-ctrl-prof" id="dokter_id" name="dokter_id">
                            <option value="">Nama Dokter Rujukan</option>
                            @foreach($dokters as $dok)
                                <option value="{{ $dok->id }}">{{ $dok->nama_pegawai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label-prof">Instansi Perujuk</label>
                        <input type="text" class="form-ctrl-prof" id="instansi_perujuk" name="instansi_perujuk" placeholder="">
                    </div>
                </div>

                {{-- Column 4 --}}
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label-prof">Asal Gudang Barang</label>
                        <select class="form-ctrl-prof" id="master_gudang_id" name="master_gudang_id">
                            @foreach($gudangs as $g)
                                <option value="{{ $g->id }}" {{ (stripos($g->nama_gudang, 'awal') !== false || stripos($g->nama_gudang, 'farmasi') !== false || $loop->first) ? 'selected' : '' }}>
                                    {{ strtoupper($g->nama_gudang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: INPUT DETAIL BARANG --}}
        <div class="card-profaskes">
            <h6 class="fw-bold mb-3" style="font-size: 13px; color: #444;">Silakan Input Detail Barang</h6>
            
            {{-- Row 1: Kelas Terapi, Nama Barang, Harga, Kemasan, Qty, Dosis, Cara Minum, Tambah --}}
            <div class="row g-2 align-items-end mb-2">
                <div class="col-md-2">
                    <label class="form-label-prof">Kelas Terapi</label>
                    <select class="form-ctrl-prof" id="input_kelas_terapi">
                        <option value="">Kelas Terapi</option>
                        @foreach($kelasTerapi as $kt)
                            <option value="{{ $kt }}">{{ $kt }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label-prof">Nama Barang/Obat</label>
                    <select class="form-ctrl-prof" id="input_master_barang_id">
                        <option value="">Ketik Nama Barang</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}" data-kelas="{{ $b->kelas_terapi }}" data-kategori="{{ $b->kategori }}">
                                {{ $b->nama_barang }} ({{ $b->kemasan ?: ($b->satuan ?: 'TAB') }})
                            </option>
                        @endforeach
                    </select>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <a href="javascript:void(0)" class="link-blue-sub" id="btnLihatHarga">
                            <i class="fas fa-file-alt"></i> Lihat Daftar Harga Obat
                        </a>
                        <button type="button" class="btn-prof-cyan-sm" id="btnBukaRacik">
                            <i class="fas fa-plus"></i> Buat Obat Racik
                        </button>
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label-prof">Harga</label>
                    <input type="text" class="form-ctrl-prof text-end" id="input_harga" value="0" readonly>
                </div>

                <div class="col-md-1">
                    <label class="form-label-prof">Kemasan</label>
                    <input type="text" class="form-ctrl-prof" id="input_kemasan" readonly>
                </div>

                <div class="col-md-1">
                    <label class="form-label-prof">Jumlah</label>
                    <input type="number" class="form-ctrl-prof text-center" id="input_jumlah" min="1" value="1">
                    <span class="helper-text-sub" id="label_stok">Stok : 0</span>
                </div>

                <div class="col-md-1">
                    <label class="form-label-prof">Dosis</label>
                    <input type="text" class="form-ctrl-prof" id="input_dosis" placeholder="Ketik Dosis">
                </div>

                <div class="col-md-2">
                    <label class="form-label-prof">Cara Minum</label>
                    <input type="text" class="form-ctrl-prof" id="input_cara_minum" placeholder="Ketik Cara Minum" list="listCaraMinum">
                    <datalist id="listCaraMinum">
                        <option value="3 x 1 Hari Sesudah Makan">
                        <option value="2 x 1 Hari Sesudah Makan">
                        <option value="1 x 1 Hari Sesudah Makan">
                        <option value="3 x 1 Hari Sebelum Makan">
                        <option value="1 x 1 Hari Malam Hari">
                        <option value="Bila Perlu / Sakit">
                        <option value="Oleskan Tipis pada Bagian Sakit">
                        <option value="Teteskan 2 tetes 3x sehari">
                    </datalist>
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn-prof-green-ok w-100" id="btnTambahItem">
                        <i class="fas fa-check-circle me-1"></i> OK|Tambah
                    </button>
                </div>
            </div>

            {{-- Row 2: No Batch & Tgl Expired --}}
            <div class="row g-2 align-items-start mt-1">
                <div class="col-md-2 offset-md-2">
                    <label class="form-label-prof">No.Batch</label>
                    <select class="form-ctrl-prof" id="input_nomor_batch">
                        <option value="">--Cari/Ketik No.Batch--</option>
                    </select>
                    <span class="helper-text-sub">Ketik No Batch jika belum ada</span>
                </div>
                <div class="col-md-2">
                    <label class="form-label-prof">Tgl.Expired</label>
                    <input type="date" class="form-ctrl-prof" id="input_tanggal_expired" readonly>
                </div>
            </div>
        </div>

        {{-- CARD 3: DETAIL ITEM TRANSAKSI & POS PANEL --}}
        <div class="card-profaskes card-profaskes-orange-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold m-0" style="font-size: 13px; color: #444;">Detail Item Transaksi</h6>
                <a href="{{ route('transaksipenjualan.index') }}" class="btn-prof-orange">
                    <i class="fas fa-times"></i> Tutup
                </a>
            </div>

            <div class="table-responsive">
                <table class="table-profaskes-detail" id="tableItems">
                    <thead>
                        <tr>
                            <th style="width: 35px; text-align: center;">No</th>
                            <th style="width: 100px;">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th style="width: 130px;">Batch & Tgl.Exp</th>
                            <th style="width: 80px;">Kemasan</th>
                            <th style="width: 60px; text-align: center;">Jumlah</th>
                            <th style="width: 130px;">Cara Minum</th>
                            <th style="width: 90px;">Dosis</th>
                            <th style="width: 90px; text-align: right;">Harga</th>
                            <th style="width: 100px; text-align: right;">Sub Total</th>
                            <th style="width: 50px; text-align: center;">Delete</th>
                        </tr>
                    </thead>
                    <tbody id="tableItemsBody">
                        <tr id="emptyRow">
                            <td colspan="11" class="text-center text-muted py-4">
                                Belum ada item transaksi. Silakan input obat pada form di atas.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- 5-COLUMN SUMMARY POS PANEL --}}
            <div class="summary-pos-container">
                {{-- Box 1: TOTAL BELANJA --}}
                <div class="summary-pos-box">
                    <div class="summary-pos-label">TOTAL BELANJA</div>
                    <div class="summary-pos-value" id="dispTotalBelanja">0</div>
                </div>

                {{-- Box 2: DISKON --}}
                <div class="summary-pos-box">
                    <div class="summary-pos-label">
                        DISKON 
                        <a href="javascript:void(0)" class="link-blue-sub ms-1" id="btnEditDiskon" style="text-transform: none; font-size: 11px;">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </a>
                    </div>
                    <div class="summary-pos-value" id="dispDiskon">0</div>
                    <input type="hidden" id="diskon_nominal" name="diskon" value="0">
                </div>

                {{-- Box 3: GRAND TOTAL --}}
                <div class="summary-pos-box">
                    <div class="summary-pos-label">GRAND TOTAL</div>
                    <div class="summary-pos-value text-primary" id="dispGrandTotal">0</div>
                    <div class="summary-pos-note" id="dispPembulatan">Pembulatan ()</div>
                    <input type="hidden" id="pembulatan" name="pembulatan" value="0">
                </div>

                {{-- Box 4: PEMBAYARAN BUTTON --}}
                <div class="summary-pos-box">
                    <div class="summary-pos-label">PEMBAYARAN</div>
                    <button type="button" class="btn-bayar-sekarang" id="btnBayarSekarang">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>
                </div>

                {{-- Box 5: KEMBALI --}}
                <div class="summary-pos-box">
                    <div class="summary-pos-label">KEMBALI</div>
                    <div class="summary-pos-value text-success" id="dispKembali">0</div>
                </div>
            </div>
        </div>

        {{-- Hidden Payment Fields --}}
        <input type="hidden" id="cara_bayar" name="cara_bayar" value="Tunai">
        <input type="hidden" id="status_bayar" name="status_bayar" value="Lunas">
        <input type="hidden" id="dibayar" name="dibayar" value="0">
        <input type="hidden" id="keterangan" name="keterangan" value="">
    </form>
</div>

{{-- MODAL BAYAR SEKARANG (POS CHECKOUT) --}}
<div class="modal fade" id="modalBayar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 4px;">
            <div class="modal-header py-2" style="background-color: #f39c12; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 14px;"><i class="fas fa-cash-register me-2"></i>Pembayaran Transaksi Kasir Apotek</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <div class="bg-light p-3 rounded mb-3 text-center border">
                    <span class="text-muted text-uppercase fw-semibold" style="font-size: 11px;">Total Tagihan / Grand Total</span>
                    <h2 class="fw-bold text-primary mb-0 mt-1" id="modalTagihanDisplay">Rp 0</h2>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold mb-1">Metode / Cara Bayar</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="modal_cara_bayar" id="cb_tunai" value="Tunai" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="cb_tunai">Tunai</label>

                        <input type="radio" class="btn-check" name="modal_cara_bayar" id="cb_qris" value="QRIS">
                        <label class="btn btn-outline-secondary btn-sm" for="cb_qris">QRIS</label>

                        <input type="radio" class="btn-check" name="modal_cara_bayar" id="cb_transfer" value="Transfer">
                        <label class="btn btn-outline-secondary btn-sm" for="cb_transfer">Transfer</label>

                        <input type="radio" class="btn-check" name="modal_cara_bayar" id="cb_debit" value="Debit">
                        <label class="btn btn-outline-secondary btn-sm" for="cb_debit">Debit</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold mb-1">Jumlah Uang Diterima / Dibayar (Rp)</label>
                    <input type="number" class="form-control form-control-lg fw-bold text-end text-success" id="modal_dibayar" placeholder="0" min="0">
                    <div class="d-flex gap-1 mt-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-primary py-1 btnQuickCash" data-val="pas">Uang Pas</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 btnQuickCash" data-val="10000">10.000</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 btnQuickCash" data-val="20000">20.000</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 btnQuickCash" data-val="50000">50.000</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 btnQuickCash" data-val="100000">100.000</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 btnQuickCash" data-val="200000">200.000</button>
                    </div>
                </div>

                <div class="bg-light p-2 rounded mb-3 border d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Kembalian:</span>
                    <span class="fs-5 fw-bold" id="modalKembalianDisplay">Rp 0</span>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold mb-1" style="font-size: 12px;">Catatan / Keterangan Transaksi</label>
                    <input type="text" class="form-control form-control-sm" id="modal_keterangan" placeholder="Opsional...">
                </div>
            </div>
            <div class="modal-footer py-2 bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-prof-green-ok" id="btnSubmitSimpanNota">
                        <i class="fas fa-print me-1"></i> Simpan &amp; Cetak Nota
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" id="btnSubmitSimpanSaja">
                        <i class="fas fa-save me-1"></i> Simpan Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT DISKON --}}
<div class="modal fade" id="modalEditDiskon" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="border-radius: 4px;">
            <div class="modal-header py-2" style="background-color: #337ab7; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-tag me-1"></i>Atur Diskon Transaksi</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <label class="form-label fw-bold mb-1" style="font-size: 12px;">Diskon (Nominal Rp)</label>
                    <input type="number" class="form-control" id="input_modal_diskon" value="0" min="0">
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" id="btnTerapkanDiskon">Terapkan</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL QUICK ADD PASIEN/PELANGGAN --}}
<div class="modal fade" id="modalQuickPasien" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 4px;">
            <div class="modal-header py-2" style="background-color: #00c0ef; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-user-plus me-1"></i>Tambah Pelanggan / Pasien Baru</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12px;">
                <div class="mb-2">
                    <label class="form-label fw-bold mb-1">Nama Lengkap Pasien <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="qp_nama" placeholder="Ketik nama pasien..." required>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label fw-bold mb-1">Tanggal Lahir</label>
                        <input type="date" class="form-control form-control-sm" id="qp_tgl_lahir">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold mb-1">Jenis Kelamin</label>
                        <select class="form-select form-select-sm" id="qp_jk">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold mb-1">No. WhatsApp / HP</label>
                    <input type="text" class="form-control form-control-sm" id="qp_nohp" placeholder="08xxxxxxxxxx">
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold mb-1">Alamat</label>
                    <textarea class="form-control form-control-sm" id="qp_alamat" rows="2" placeholder="Alamat domisili..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-prof-green-ok" id="btnSaveQuickPasien">Simpan Pelanggan</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DAFTAR HARGA OBAT --}}
<div class="modal fade" id="modalDaftarHarga" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 4px;">
            <div class="modal-header py-2" style="background-color: #337ab7; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-file-invoice-dollar me-2"></i>Daftar Harga Obat &amp; Stok</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12px;">
                <div class="mb-3">
                    <input type="text" class="form-control form-control-sm" id="search_harga_obat" placeholder="🔍 Cari nama obat, kode, atau kategori...">
                </div>
                <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-bordered table-hover table-sm align-middle" style="font-size: 11.5px;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Obat / Barang</th>
                                <th>Kategori</th>
                                <th>Kemasan</th>
                                <th style="text-align: right;">Harga Jual</th>
                                <th style="text-align: center;">Total Stok</th>
                                <th style="text-align: center; width: 60px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDaftarHarga">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL BUAT OBAT RACIK --}}
<div class="modal fade" id="modalObatRacik" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 4px;">
            <div class="modal-header py-2" style="background-color: #00c0ef; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-mortar-pestle me-2"></i>Input Obat Racikan</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12px;">
                <div class="mb-2">
                    <label class="form-label fw-bold mb-1">Nama Racikan</label>
                    <input type="text" class="form-control form-control-sm" id="racik_nama" placeholder="Contoh: Racikan Batuk Pilek">
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label fw-bold mb-1">Kemasan</label>
                        <select class="form-select form-select-sm" id="racik_kemasan">
                            <option value="BUNGKUS / PUYER">BUNGKUS / PUYER</option>
                            <option value="KAPSUL">KAPSUL</option>
                            <option value="BOTOL SIRUP">BOTOL SIRUP</option>
                            <option value="POT SALEP">POT SALEP</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold mb-1">Jumlah Racik</label>
                        <input type="number" class="form-control form-control-sm text-center" id="racik_jumlah" value="10" min="1">
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label fw-bold mb-1">Harga Satuan (Rp)</label>
                        <input type="number" class="form-control form-control-sm text-end" id="racik_harga" value="1000" min="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold mb-1">Dosis</label>
                        <input type="text" class="form-control form-control-sm" id="racik_dosis" placeholder="1 bungkus">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold mb-1">Cara Minum / Aturan Pakai</label>
                    <input type="text" class="form-control form-control-sm" id="racik_cara_minum" placeholder="3 x 1 Bungkus Sesudah Makan">
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-prof-green-ok" id="btnTambahRacikanKeTable">Tambahkan ke Transaksi</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let items = [];
    let currentSelectedMedicine = null;
    let diskonNominal = 0;
    let pembulatanNominal = 0;

    // Filter Nama Barang by Kelas Terapi
    $('#input_kelas_terapi').on('change', function () {
        let selectedKt = $(this).val();
        $('#input_master_barang_id option').each(function () {
            let itemKt = $(this).data('kelas') || $(this).data('kategori');
            if (!selectedKt || $(this).val() === '' || itemKt === selectedKt) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        $('#input_master_barang_id').val('');
        resetItemForm();
    });

    // When Asal Gudang changes, refresh selected medicine stock & batches
    $('#master_gudang_id').on('change', function () {
        let barangId = $('#input_master_barang_id').val();
        if (barangId) {
            fetchMedicineDetails(barangId);
        }
    });

    // When Medicine is selected
    $('#input_master_barang_id').on('change', function () {
        let barangId = $(this).val();
        if (!barangId) {
            resetItemForm();
            return;
        }
        fetchMedicineDetails(barangId);
    });

    function resetItemForm() {
        currentSelectedMedicine = null;
        $('#input_harga').val('0');
        $('#input_kemasan').val('');
        $('#label_stok').text('Stok : 0');
        $('#input_nomor_batch').html('<option value="">--Cari/Ketik No.Batch--</option>');
        $('#input_tanggal_expired').val('');
        $('#input_jumlah').val('1');
        $('#input_dosis').val('');
        $('#input_cara_minum').val('');
    }

    function fetchMedicineDetails(barangId) {
        let gudangId = $('#master_gudang_id').val();
        $.get('{{ route("transaksipenjualan.search_obat") }}', { barang_id: barangId, gudang_id: gudangId }, function (res) {
            if (res.success) {
                currentSelectedMedicine = res;
                $('#input_harga').val(res.harga_jual.toLocaleString('id-ID'));
                $('#input_kemasan').val(res.satuan_terkecil);
                $('#label_stok').text('Stok : ' + res.stok);

                // Populate batches
                let batchOptions = '<option value="">--Cari/Ketik No.Batch--</option>';
                if (res.batches && res.batches.length > 0) {
                    res.batches.forEach(function (b) {
                        batchOptions += `<option value="${b.nomor_batch}" data-exp="${b.tanggal_kadaluarsa || ''}" data-stok="${b.stok}">
                            ${b.nomor_batch} (Stok: ${b.stok} | Exp: ${b.tanggal_kadaluarsa || '-'})
                        </option>`;
                    });
                }
                $('#input_nomor_batch').html(batchOptions);

                // If batches exist, pick first
                if (res.batches && res.batches.length > 0) {
                    $('#input_nomor_batch').val(res.batches[0].nomor_batch);
                    $('#input_tanggal_expired').val(res.batches[0].tanggal_kadaluarsa || '');
                } else {
                    $('#input_tanggal_expired').val('');
                }
            }
        });
    }

    // When Batch selected
    $('#input_nomor_batch').on('change', function () {
        let exp = $(this).find(':selected').data('exp');
        if (exp) {
            $('#input_tanggal_expired').val(exp);
        }
    });

    // Add Item to Table
    $('#btnTambahItem').on('click', function () {
        if (!currentSelectedMedicine) {
            alert('Silakan pilih obat / barang terlebih dahulu.');
            return;
        }

        let qty = parseFloat($('#input_jumlah').val()) || 0;
        if (qty <= 0) {
            alert('Jumlah obat harus lebih dari 0.');
            return;
        }

        let batch = $('#input_nomor_batch').val() || '-';
        let exp = $('#input_tanggal_expired').val() || '-';
        let dosis = $('#input_dosis').val() || '';
        let caraMinum = $('#input_cara_minum').val() || '';
        let harga = currentSelectedMedicine.harga_jual || 0;
        let subtotal = harga * qty;

        items.push({
            master_barang_id: currentSelectedMedicine.id,
            kode_barang: currentSelectedMedicine.kode_barang || '-',
            nama_barang: currentSelectedMedicine.nama_barang,
            nomor_batch: batch !== '-' ? batch : null,
            tanggal_kadaluarsa: exp !== '-' ? exp : null,
            kemasan: currentSelectedMedicine.satuan_terkecil || 'TABLET',
            jumlah: qty,
            cara_minum: caraMinum,
            dosis: dosis,
            harga_satuan: harga,
            subtotal: subtotal,
            diskon: 0
        });

        renderItemsTable();
        resetItemForm();
        $('#input_master_barang_id').val('');
    });

    // Render Table
    function renderItemsTable() {
        let tbody = $('#tableItemsBody');
        tbody.empty();

        if (items.length === 0) {
            tbody.html(`<tr id="emptyRow">
                <td colspan="11" class="text-center text-muted py-4">
                    Belum ada item transaksi. Silakan input obat pada form di atas.
                </td>
            </tr>`);
            updateSummaryTotals();
            return;
        }

        items.forEach(function (item, idx) {
            let batchExp = item.nomor_batch ? `${item.nomor_batch} (${item.tanggal_kadaluarsa || '-'})` : '-';
            let row = `<tr>
                <td style="text-align: center;">${idx + 1}</td>
                <td><span class="badge bg-light text-dark border">${item.kode_barang}</span></td>
                <td class="fw-semibold">${item.nama_barang}</td>
                <td style="font-size: 11px;" class="text-muted">${batchExp}</td>
                <td>${item.kemasan}</td>
                <td style="text-align: center; font-weight: bold;">${item.jumlah}</td>
                <td style="font-size: 11px;">${item.cara_minum || '-'}</td>
                <td style="font-size: 11px;">${item.dosis || '-'}</td>
                <td style="text-align: right;">Rp ${item.harga_satuan.toLocaleString('id-ID')}</td>
                <td style="text-align: right; font-weight: bold; color: #337ab7;">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-sm btn-outline-danger p-0 px-2 btnRemoveItem" data-index="${idx}" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
            tbody.append(row);
        });

        updateSummaryTotals();
    }

    // Delete item
    $(document).on('click', '.btnRemoveItem', function () {
        let idx = $(this).data('index');
        items.splice(idx, 1);
        renderItemsTable();
    });

    // Update Summary POS Totals
    function updateSummaryTotals() {
        let totalBelanja = 0;
        items.forEach(function (it) {
            totalBelanja += it.subtotal;
        });

        let grandTotal = totalBelanja - diskonNominal + pembulatanNominal;
        if (grandTotal < 0) grandTotal = 0;

        $('#dispTotalBelanja').text(totalBelanja.toLocaleString('id-ID'));
        $('#dispDiskon').text(diskonNominal.toLocaleString('id-ID'));
        $('#diskon_nominal').val(diskonNominal);
        $('#dispGrandTotal').text(grandTotal.toLocaleString('id-ID'));
        $('#pembulatan').val(pembulatanNominal);

        // Update Kembalian
        let dibayar = parseFloat($('#dibayar').val()) || 0;
        let kembali = dibayar - grandTotal;
        if (kembali >= 0) {
            $('#dispKembali').text(kembali.toLocaleString('id-ID')).removeClass('text-danger').addClass('text-success');
        } else {
            $('#dispKembali').text('0').removeClass('text-success').addClass('text-danger');
        }
    }

    // Edit Diskon Modal
    $('#btnEditDiskon').on('click', function () {
        $('#input_modal_diskon').val(diskonNominal);
        $('#modalEditDiskon').modal('show');
    });

    $('#btnTerapkanDiskon').on('click', function () {
        diskonNominal = parseFloat($('#input_modal_diskon').val()) || 0;
        updateSummaryTotals();
        $('#modalEditDiskon').modal('hide');
    });

    // Open Payment Modal
    $('#btnBayarSekarang').on('click', function () {
        if (items.length === 0) {
            alert('Belum ada item obat yang ditambahkan ke keranjang transaksi!');
            return;
        }

        let totalBelanja = 0;
        items.forEach(it => totalBelanja += it.subtotal);
        let grandTotal = Math.max(0, totalBelanja - diskonNominal + pembulatanNominal);

        $('#modalTagihanDisplay').text('Rp ' + grandTotal.toLocaleString('id-ID'));
        $('#modal_dibayar').val(grandTotal);
        updateModalKembalian(grandTotal, grandTotal);

        $('#modalBayar').modal('show');
    });

    // Quick Cash Buttons
    $('.btnQuickCash').on('click', function () {
        let val = $(this).data('val');
        let totalBelanja = 0;
        items.forEach(it => totalBelanja += it.subtotal);
        let grandTotal = Math.max(0, totalBelanja - diskonNominal + pembulatanNominal);

        if (val === 'pas') {
            $('#modal_dibayar').val(grandTotal);
        } else {
            $('#modal_dibayar').val(val);
        }
        let dibayar = parseFloat($('#modal_dibayar').val()) || 0;
        updateModalKembalian(grandTotal, dibayar);
    });

    $('#modal_dibayar').on('input', function () {
        let totalBelanja = 0;
        items.forEach(it => totalBelanja += it.subtotal);
        let grandTotal = Math.max(0, totalBelanja - diskonNominal + pembulatanNominal);
        let dibayar = parseFloat($(this).val()) || 0;
        updateModalKembalian(grandTotal, dibayar);
    });

    function updateModalKembalian(grandTotal, dibayar) {
        let kembali = dibayar - grandTotal;
        if (kembali >= 0) {
            $('#modalKembalianDisplay').text('Rp ' + kembali.toLocaleString('id-ID')).removeClass('text-danger').addClass('text-success');
        } else {
            $('#modalKembalianDisplay').text('Kurang Rp ' + Math.abs(kembali).toLocaleString('id-ID')).removeClass('text-success').addClass('text-danger');
        }
    }

    // Submit Action (Simpan & Cetak or Simpan Saja)
    $('#btnSubmitSimpanNota').on('click', function () {
        executeCheckout(true);
    });

    $('#btnSubmitSimpanSaja').on('click', function () {
        executeCheckout(false);
    });

    function executeCheckout(printReceipt) {
        let totalBelanja = 0;
        items.forEach(it => totalBelanja += it.subtotal);
        let grandTotal = Math.max(0, totalBelanja - diskonNominal + pembulatanNominal);
        let dibayar = parseFloat($('#modal_dibayar').val()) || 0;
        let caraBayar = $('input[name="modal_cara_bayar"]:checked').val() || 'Tunai';
        let keterangan = $('#modal_keterangan').val() || '';

        let payload = {
            _token: '{{ csrf_token() }}',
            kode_transaksi: $('#kode_transaksi').val(),
            tanggal_penjualan: $('#tanggal_penjualan').val(),
            pelanggan_nama: $('#pelanggan_nama').val() || 'UMUM',
            pelanggan_telp: $('#pelanggan_telp').val(),
            pasien_id: $('#pasien_id').val() || null,
            dokter_id: $('#dokter_id').val() || null,
            master_gudang_id: $('#master_gudang_id').val() || null,
            no_resep_rujukan: $('#no_resep_rujukan').val(),
            instansi_perujuk: $('#instansi_perujuk').val(),
            cara_bayar: caraBayar,
            status_bayar: dibayar >= grandTotal ? 'Lunas' : 'Sebagian',
            diskon: diskonNominal,
            pembulatan: pembulatanNominal,
            dibayar: dibayar,
            keterangan: keterangan,
            items: items
        };

        let btnPrint = $('#btnSubmitSimpanNota');
        let btnSave = $('#btnSubmitSimpanSaja');
        btnPrint.prop('disabled', true);
        btnSave.prop('disabled', true);

        $.ajax({
            url: '{{ route("transaksipenjualan.store") }}',
            type: 'POST',
            data: payload,
            success: function (res) {
                if (res.success) {
                    $('#modalBayar').modal('hide');
                    if (printReceipt) {
                        let printWin = window.open(`/transaksipenjualan/${res.penjualan_id}/cetak-nota`, '_blank');
                        setTimeout(function () {
                            window.location.href = res.redirect;
                        }, 500);
                    } else {
                        window.location.href = res.redirect;
                    }
                }
            },
            error: function (xhr) {
                btnPrint.prop('disabled', false);
                btnSave.prop('disabled', false);
                let err = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                alert('Gagal: ' + err);
            }
        });
    }

    // Modal Quick Add Pasien
    $('#btnSaveQuickPasien').on('click', function () {
        let nama = $('#qp_nama').val();
        if (!nama) {
            alert('Nama pasien wajib diisi.');
            return;
        }
        let data = {
            _token: '{{ csrf_token() }}',
            nama_pasien: nama,
            tanggal_lahir: $('#qp_tgl_lahir').val(),
            jenis_kelamin: $('#qp_jk').val(),
            no_hp: $('#qp_nohp').val(),
            alamat: $('#qp_alamat').val()
        };

        $.post('{{ route("transaksipenjualan.quick_pasien") }}', data, function (res) {
            if (res.success) {
                let p = res.pasien;
                $('#pelanggan_nama').val(p.nama_pasien + ' (' + p.no_rm + ')');
                $('#pasien_id').val(p.id);
                $('#pelanggan_telp').val(p.no_hp || '');
                $('#modalQuickPasien').modal('hide');
                $('#qp_nama').val('');
                $('#qp_nohp').val('');
                $('#qp_alamat').val('');
                alert('Pasien berhasil ditambahkan.');
            }
        }).fail(function (xhr) {
            let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal menyimpan pasien';
            alert(msg);
        });
    });

    // Modal Daftar Harga
    $('#btnLihatHarga').on('click', function () {
        loadDaftarHarga('');
        $('#modalDaftarHarga').modal('show');
    });

    $('#search_harga_obat').on('input', function () {
        loadDaftarHarga($(this).val());
    });

    function loadDaftarHarga(kw) {
        $.get('{{ route("transaksipenjualan.daftar_harga") }}', { q: kw }, function (data) {
            let html = '';
            if (data && data.length > 0) {
                data.forEach(function (d) {
                    html += `<tr>
                        <td><span class="badge bg-light text-dark border">${d.kode_barang}</span></td>
                        <td class="fw-semibold">${d.nama_barang}</td>
                        <td class="text-muted">${d.kategori_barang || '-'}</td>
                        <td>${d.satuan_terkecil || 'TAB'}</td>
                        <td style="text-align: right; font-weight: bold; color: #00a65a;">Rp ${parseFloat(d.harga_jual || 0).toLocaleString('id-ID')}</td>
                        <td style="text-align: center;">${d.stok}</td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btnPilihObatModal" data-id="${d.id}">Pilih</button>
                        </td>
                    </tr>`;
                });
            } else {
                html = `<tr><td colspan="7" class="text-center text-muted py-3">Tidak ada obat ditemukan</td></tr>`;
            }
            $('#tbodyDaftarHarga').html(html);
        });
    }

    $(document).on('click', '.btnPilihObatModal', function () {
        let id = $(this).data('id');
        $('#input_master_barang_id').val(id).trigger('change');
        $('#modalDaftarHarga').modal('hide');
    });

    // Modal Racik
    $('#btnBukaRacik').on('click', function () {
        $('#modalObatRacik').modal('show');
    });

    $('#btnTambahRacikanKeTable').on('click', function () {
        let nama = $('#racik_nama').val() || 'Obat Racik';
        let kemasan = $('#racik_kemasan').val();
        let qty = parseFloat($('#racik_jumlah').val()) || 1;
        let harga = parseFloat($('#racik_harga').val()) || 0;
        let dosis = $('#racik_dosis').val() || '';
        let aturan = $('#racik_cara_minum').val() || '';

        // Pick first available barang as master placeholder or find racikan
        let placeholderBarangId = $('#input_master_barang_id option:eq(1)').val();
        if (!placeholderBarangId) {
            alert('Data master obat belum tersedia di sistem.');
            return;
        }

        items.push({
            master_barang_id: placeholderBarangId,
            kode_barang: 'RACIK',
            nama_barang: nama,
            nomor_batch: '-',
            tanggal_kadaluarsa: null,
            kemasan: kemasan,
            jumlah: qty,
            cara_minum: aturan,
            dosis: dosis,
            harga_satuan: harga,
            subtotal: harga * qty,
            diskon: 0
        });

        renderItemsTable();
        $('#modalObatRacik').modal('hide');
        $('#racik_nama').val('');
    });
});
</script>
@endsection
