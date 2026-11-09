@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* PROFASKES 1:1 PEMBUATANORDER CREATE */
    .po-create-page {
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
    .card-po-header {
        background-color: #fff;
        border: 1px solid #e7e7e7;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px;
        margin-bottom: 15px;
    }
    .card-detail-input {
        background-color: #fff;
        border: 1px solid #e7e7e7;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px;
        margin-bottom: 15px;
    }
    .card-detail-table {
        background-color: #fff;
        border: 1px solid #e7e7e7;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px;
        margin-bottom: 15px;
    }
    .section-subtitle {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 12px;
    }
    .btn-cyan-add {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #ffffff !important;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-cyan-add:hover {
        background-color: #00acd6;
    }
    .btn-prof-ok {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        font-size: 12.5px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }
    .btn-prof-ok:hover {
        background-color: #008d4c;
    }
    .btn-prof-tutup {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 12.5px;
        font-weight: 500;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-prof-tutup:hover {
        background-color: #d68407;
        color: #ffffff !important;
    }
    .table-order-items {
        width: 100%;
        border: 1px solid #ddd;
        border-collapse: collapse;
        font-size: 12.5px;
        margin-bottom: 10px;
    }
    .table-order-items thead th {
        background-color: #a6a6a6 !important;
        color: #ffffff !important;
        border: 1px solid #999 !important;
        padding: 7px 10px;
        font-weight: 600;
        vertical-align: middle;
    }
    .table-order-items tbody td {
        border: 1px solid #ddd !important;
        padding: 7px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-order-items tfoot td {
        background-color: #f5f5f5;
        border: 1px solid #ddd !important;
        padding: 7px 10px;
        font-weight: 600;
        color: #333;
    }
    .form-control-sm, .form-select-sm {
        font-size: 12.5px;
        border-radius: 2px;
    }
    .form-label-prof {
        font-size: 12.5px;
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
    }
</style>

<div class="po-create-page">
    <!-- Header Title & Breadcrumb (PROFASKES 1:1) -->
    <div class="profaskes-header">
        <h3 class="profaskes-title">Pembuatanorder</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a> <span style="color: #ccc; margin: 0 2px;">&gt;</span>
            <a href="/pembuatanorder/index">Pembuatanorders</a> <span style="color: #ccc; margin: 0 2px;">&gt;</span>
            <span style="color: #777;">Pembuatanorder</span>
        </div>
    </div>

    <form id="formPembuatanOrder" onsubmit="submitFormOrder(event)">
        @csrf
        
        <!-- CARD 1: Header Form (PROFASKES 1:1) -->
        <div class="card-po-header">
            <!-- Row 1: No PO, Tanggal, Vendor, Tgl Kirim, Pengiriman/Kurir -->
            <div class="row g-2 mb-3">
                <div class="col-md-2">
                    <label class="form-label-prof">No. PO</label>
                    <input type="text" name="nomor_po" id="nomor_po" class="form-control form-control-sm bg-light" value="{{ $noPo }}" readonly required>
                </div>
                <div class="col-md-2">
                    <label class="form-label-prof">Tanggal</label>
                    <input type="date" name="tanggal_po" id="tanggal_po" class="form-control form-control-sm" value="{{ $tanggalHariIni }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label-prof">Nama Vendor</label>
                    <div class="input-group input-group-sm">
                        <select name="master_vendor_id" id="master_vendor_id" class="form-select form-select-sm" required>
                            <option value="">Ketik Nama Vendor</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}">{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-cyan-add" onclick="bukaModalTambahVendor()" title="Tambah Vendor Baru">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label-prof">Tgl.Kirim</label>
                    <input type="date" name="tgl_kirim" id="tgl_kirim" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label-prof">Pengiriman/Kurir</label>
                    <input type="text" name="pengiriman_kurir" id="pengiriman_kurir" class="form-control form-control-sm" placeholder="Contoh: JNE / Kurir Vendor">
                </div>
            </div>

            <!-- Row 2: Alamat Kirim & Total PO -->
            <div class="row g-2">
                <div class="col-md-10">
                    <label class="form-label-prof">Alamat Kirim</label>
                    <input type="text" name="alamat_kirim" id="alamat_kirim" class="form-control form-control-sm" value="{{ $alamatKirim }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label-prof">Total PO</label>
                    <input type="text" id="displayTotalPO" class="form-control form-control-sm bg-light fw-bold text-end" value="0" readonly>
                </div>
            </div>
        </div>

        <!-- CARD 2: Input Detail Item (PROFASKES 1:1) -->
        <div class="card-detail-input">
            <div class="section-subtitle">Input Detail Item</div>
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label-prof">Nama Barang/Obat</label>
                    <select id="inputItemBarang" class="form-select form-select-sm" onchange="onPilihBarang()">
                        <option value="">Ketik Nama Barang</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}" 
                                    data-kode="{{ $b->kode_barang }}" 
                                    data-nama="{{ $b->nama_barang }}" 
                                    data-kemasan="{{ $b->kemasan ?? $b->satuan ?? 'Pcs' }}" 
                                    data-harga="{{ $b->harga_beli ?? 0 }}">
                                {{ $b->kode_barang }} - {{ $b->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-prof">Kemasan</label>
                    <select id="inputItemKemasan" class="form-select form-select-sm">
                        <option value="">--Pilih--</option>
                        <option value="Box">Box</option>
                        <option value="Strip">Strip</option>
                        <option value="Botol">Botol</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Kaplet">Kaplet</option>
                        <option value="Ampul">Ampul</option>
                        <option value="Vial">Vial</option>
                        <option value="Tube">Tube</option>
                        <option value="Sachet">Sachet</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-prof">Jumlah</label>
                    <input type="number" id="inputItemJumlah" class="form-control form-control-sm text-end" min="1" step="1" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label-prof">Harga</label>
                    <input type="number" id="inputItemHarga" class="form-control form-control-sm text-end" min="0" step="100" placeholder="0">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-prof-ok w-100" onclick="tambahItemKeTabel()">
                        <i class="fas fa-save"></i> OK, Simpan
                    </button>
                </div>
            </div>
        </div>

        <!-- CARD 3: Detail Item Transaksi (PROFASKES 1:1) -->
        <div class="card-detail-table">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="section-subtitle mb-0">Detail Item Transaksi</div>
                <a href="/pembuatanorder/index" class="btn btn-prof-tutup">
                    <i class="fas fa-times"></i> Tutup
                </a>
            </div>

            <div class="table-responsive">
                <table class="table-order-items" id="tableDetailPO">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">No</th>
                            <th style="width: 140px;">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th style="width: 110px;">Kemasan</th>
                            <th style="width: 90px; text-align: right;">Jumlah</th>
                            <th style="width: 130px; text-align: right;">Harga</th>
                            <th style="width: 140px; text-align: right;">Sub Total</th>
                            <th style="width: 50px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody id="tbodyOrderItems">
                        <!-- Dynamic Rows or Empty Row -->
                        <tr id="emptyRowPlaceholder">
                            <td colspan="8" class="text-center py-3 text-muted">Belum ada item obat/barang yang ditambahkan.</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td style="text-align: right;" id="footerTotalQty" class="fw-bold">0</td>
                            <td></td>
                            <td style="text-align: right;" id="footerTotalHarga" class="fw-bold">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Submit Action -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="/pembuatanorder/index" class="btn btn-secondary btn-sm px-3">Batal</a>
                <button type="submit" class="btn btn-success btn-sm px-4 fw-bold" id="btnSubmitOrder" style="background-color: #00a65a; border-color: #008d4c;">
                    <i class="fas fa-check-circle me-1"></i> Simpan Pembuatan Order
                </button>
            </div>
        </div>
    </form>
</div>

<!-- MODAL QUICK TAMBAH VENDOR -->
<div class="modal fade" id="modalQuickVendor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formQuickVendor" onsubmit="simpanQuickVendor(event)">
                @csrf
                <div class="modal-header py-2 px-3 text-white" style="background-color: #00c0ef !important;">
                    <h5 class="modal-title fs-6 fw-semibold text-white">
                        <i class="fas fa-truck me-1"></i> Tambah Vendor / Distributor Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1">Nama Vendor <span class="text-danger">*</span></label>
                        <input type="text" name="nama_vendor" id="quickNamaVendor" class="form-control form-control-sm" required placeholder="Contoh: PT ANUGERAH PHARMA">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1">Alamat</label>
                        <textarea name="alamat" id="quickAlamatVendor" class="form-control form-control-sm" rows="2" placeholder="Alamat kantor/gudang vendor..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1">No. Telepon / HP</label>
                        <input type="text" name="no_telepon" id="quickTeleponVendor" class="form-control form-control-sm" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info btn-sm text-white px-3" style="background-color: #00c0ef; border-color: #00acd6;" id="btnSimpanVendor">
                        <i class="fas fa-save me-1"></i> Simpan Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let itemsData = [];

function onPilihBarang() {
    const select = document.getElementById('inputItemBarang');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption && selectedOption.value) {
        const kemasan = selectedOption.getAttribute('data-kemasan');
        const harga = selectedOption.getAttribute('data-harga');

        if (kemasan) {
            $('#inputItemKemasan').val(kemasan);
            if (!$('#inputItemKemasan').val()) {
                // If not in standard dropdown, select Box or add option
                $('#inputItemKemasan').append(new Option(kemasan, kemasan, true, true));
            }
        }
        if (harga) {
            $('#inputItemHarga').val(parseFloat(harga) || 0);
        }
        $('#inputItemJumlah').val(1).focus();
    }
}

function tambahItemKeTabel() {
    const select = document.getElementById('inputItemBarang');
    const selectedOption = select.options[select.selectedIndex];

    if (!selectedOption || !selectedOption.value) {
        alert('Silakan pilih Nama Barang/Obat terlebih dahulu.');
        return;
    }

    const barangId = selectedOption.value;
    const kode = selectedOption.getAttribute('data-kode');
    const nama = selectedOption.getAttribute('data-nama');
    const kemasan = $('#inputItemKemasan').val() || selectedOption.getAttribute('data-kemasan') || '-';
    const jumlah = parseFloat($('#inputItemJumlah').val()) || 0;
    const harga = parseFloat($('#inputItemHarga').val()) || 0;

    if (jumlah <= 0) {
        alert('Jumlah harus lebih besar dari 0.');
        $('#inputItemJumlah').focus();
        return;
    }

    const subtotal = jumlah * harga;

    // Check if item already exists
    const existingIndex = itemsData.findIndex(item => item.master_barang_id == barangId);
    if (existingIndex >= 0) {
        itemsData[existingIndex].jumlah += jumlah;
        itemsData[existingIndex].harga = harga;
        itemsData[existingIndex].kemasan = kemasan;
        itemsData[existingIndex].subtotal = itemsData[existingIndex].jumlah * harga;
    } else {
        itemsData.push({
            master_barang_id: barangId,
            kode_barang: kode,
            nama_barang: nama,
            kemasan: kemasan,
            jumlah: jumlah,
            harga: harga,
            subtotal: subtotal
        });
    }

    // Reset inputs
    $('#inputItemBarang').val('').trigger('change');
    $('#inputItemKemasan').val('');
    $('#inputItemJumlah').val('');
    $('#inputItemHarga').val('');

    renderTableItems();
}

function hapusItem(index) {
    itemsData.splice(index, 1);
    renderTableItems();
}

function renderTableItems() {
    const tbody = $('#tbodyOrderItems');
    tbody.empty();

    if (itemsData.length === 0) {
        tbody.html('<tr id="emptyRowPlaceholder"><td colspan="8" class="text-center py-3 text-muted">Belum ada item obat/barang yang ditambahkan.</td></tr>');
        $('#footerTotalQty').text('0');
        $('#footerTotalHarga').text('0');
        $('#displayTotalPO').val('0');
        return;
    }

    let totalQty = 0;
    let grandTotal = 0;

    itemsData.forEach((item, idx) => {
        totalQty += item.jumlah;
        grandTotal += item.subtotal;

        tbody.append(`
            <tr>
                <td style="text-align: center;">${idx + 1}</td>
                <td>${item.kode_barang}</td>
                <td>${item.nama_barang}</td>
                <td>${item.kemasan}</td>
                <td style="text-align: right;">${item.jumlah.toLocaleString('id-ID')}</td>
                <td style="text-align: right;">${item.harga.toLocaleString('id-ID')}</td>
                <td style="text-align: right;">${item.subtotal.toLocaleString('id-ID')}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-link text-danger p-0" onclick="hapusItem(${idx})" title="Hapus">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `);
    });

    $('#footerTotalQty').text(totalQty.toLocaleString('id-ID'));
    $('#footerTotalHarga').text(grandTotal.toLocaleString('id-ID'));
    $('#displayTotalPO').val(grandTotal.toLocaleString('id-ID'));
}

function bukaModalTambahVendor() {
    $('#formQuickVendor')[0].reset();
    const modal = new bootstrap.Modal(document.getElementById('modalQuickVendor'));
    modal.show();
}

function simpanQuickVendor(e) {
    e.preventDefault();
    const btn = $('#btnSimpanVendor');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

    $.ajax({
        url: '/pembuatanorder/quick-vendor',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: $('#formQuickVendor').serialize(),
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Vendor');
            if (res.success && res.vendor) {
                // Add to vendor select
                const newOption = new Option(res.vendor.nama_vendor, res.vendor.id, true, true);
                $('#master_vendor_id').append(newOption).trigger('change');
                bootstrap.Modal.getInstance(document.getElementById('modalQuickVendor')).hide();
                alert('Vendor baru berhasil ditambahkan.');
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Vendor');
            alert('Gagal menambahkan vendor: ' + (xhr.responseJSON?.message || 'Error'));
        }
    });
}

function submitFormOrder(e) {
    e.preventDefault();

    if (itemsData.length === 0) {
        alert('Mohon tambahkan minimal 1 item barang/obat sebelum menyimpan PO.');
        return;
    }

    const vendorId = $('#master_vendor_id').val();
    if (!vendorId) {
        alert('Silakan pilih Nama Vendor terlebih dahulu.');
        $('#master_vendor_id').focus();
        return;
    }

    const btn = $('#btnSubmitOrder');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan PO...');

    const payload = {
        nomor_po: $('#nomor_po').val(),
        tanggal_po: $('#tanggal_po').val(),
        master_vendor_id: vendorId,
        tgl_kirim: $('#tgl_kirim').val(),
        pengiriman_kurir: $('#pengiriman_kurir').val(),
        alamat_kirim: $('#alamat_kirim').val(),
        items: itemsData
    };

    $.ajax({
        url: '/pembuatanorder/store',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        data: payload,
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Simpan Pembuatan Order');
            if (res.success) {
                alert(res.message || 'Transaksi Pembuatan Order berhasil disimpan.');
                window.location.href = res.redirect || '/pembuatanorder/index';
            } else {
                alert(res.message || 'Gagal menyimpan PO.');
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Simpan Pembuatan Order');
            let errMsg = 'Terjadi kesalahan sistem saat menyimpan PO.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errMsg = xhr.responseJSON.message;
            }
            alert('Gagal: ' + errMsg);
        }
    });
}
</script>
@endsection
