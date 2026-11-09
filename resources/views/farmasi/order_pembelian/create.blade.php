@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/farmasi/order-pembelian" class="btn btn-sm btn-outline-secondary me-2"><i class="fas fa-arrow-left"></i></a>
        Buat Order Pembelian (PO)
    </h1>
</div>

<div id="alertBox" class="alert d-none mb-3 shadow-sm" role="alert"></div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light">
    <form id="formPo">
        @csrf
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Nomor PO <span class="text-danger">*</span></label>
                <input type="text" class="form-control fw-bold text-primary" id="nomor_po" name="nomor_po" value="{{ $nomorPo }}" readonly required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Tanggal PO <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_po" name="tanggal_po" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Rencana Datang</label>
                <input type="date" class="form-control" id="rencana_datang" name="rencana_datang">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Vendor <span class="text-danger">*</span></label>
                <select class="form-select" id="master_vendor_id" name="master_vendor_id" required>
                    <option value="">-- Pilih Vendor --</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->nama_vendor }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h5 class="fw-bold mb-3 border-bottom pb-2">Item Barang</h5>
        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle" id="tableItems">
                <thead class="table-secondary">
                    <tr>
                        <th width="35%">Pilih Barang</th>
                        <th width="15%">Harga Satuan (Rp)</th>
                        <th width="15%">Qty</th>
                        <th width="25%">Subtotal (Rp)</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- JS will populate rows -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <button type="button" class="btn btn-sm btn-outline-success" id="btnAddItem">
                                <i class="fas fa-plus me-1"></i> Tambah Baris Barang
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end fs-5">TOTAL KESELURUHAN</th>
                        <th class="text-end text-danger fs-4 fw-bold" id="grandTotal">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Keterangan Tambahan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Catatan untuk vendor..."></textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-5 fs-5 shadow-sm">
                <i class="fas fa-save me-2"></i> Simpan PO
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function () {
    let barangs = @json($barangs);
    let itemIndex = 0;

    function formatRupiah(angka) {
        return parseInt(angka).toLocaleString('id-ID');
    }

    function calculateTotal() {
        let grandTotal = 0;
        $('#tableItems tbody tr').each(function() {
            let qty = parseFloat($(this).find('.input-qty').val()) || 0;
            let harga = parseFloat($(this).find('.input-harga').val()) || 0;
            let subtotal = qty * harga;
            $(this).find('.subtotal-text').text(formatRupiah(subtotal));
            grandTotal += subtotal;
        });
        $('#grandTotal').text('Rp ' + formatRupiah(grandTotal));
    }

    function addRow() {
        let options = '<option value="">-- Pilih --</option>';
        barangs.forEach(function(b) {
            options += `<option value="${b.id}" data-harga="${b.harga_beli}">${b.kode_barang} - ${b.nama_barang}</option>`;
        });

        let row = `
        <tr class="item-row">
            <td>
                <select class="form-select select-barang" name="items[${itemIndex}][master_barang_id]" required>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" class="form-control input-harga text-end" name="items[${itemIndex}][harga_satuan]" min="0" value="0" required>
            </td>
            <td>
                <input type="number" class="form-control input-qty text-center" name="items[${itemIndex}][jumlah]" min="1" value="1" required>
            </td>
            <td class="text-end fw-bold text-primary fs-5 subtotal-text">0</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger btnRemove"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
        $('#tableItems tbody').append(row);
        itemIndex++;
    }

    // Add first row
    addRow();

    $('#btnAddItem').click(function() {
        addRow();
    });

    $(document).on('click', '.btnRemove', function() {
        if($('#tableItems tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotal();
        } else {
            alert("Minimal harus ada 1 barang.");
        }
    });

    $(document).on('change', '.select-barang', function() {
        let harga = $(this).find(':selected').data('harga') || 0;
        $(this).closest('tr').find('.input-harga').val(harga);
        calculateTotal();
    });

    $(document).on('input', '.input-qty, .input-harga', function() {
        calculateTotal();
    });

    $('#formPo').on('submit', function (e) {
        e.preventDefault();
        
        let btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...');

        $.ajax({
            url: '/farmasi/order-pembelian',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    alert(res.message);
                    window.location.href = '/farmasi/order-pembelian';
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i> Simpan PO');
                alert('Terjadi kesalahan, pastikan semua data terisi dengan benar.');
            }
        });
    });
});
</script>
@endsection
