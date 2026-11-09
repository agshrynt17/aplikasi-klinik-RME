@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/farmasi/resep" class="btn btn-sm btn-outline-secondary me-2"><i class="fas fa-arrow-left"></i></a>
        Buat Resep Obat Pasien Baru
    </h1>
</div>

<div id="alertBox" class="alert d-none mb-3 shadow-sm" role="alert"></div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light">
    <form id="formResep">
        @csrf
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Nomor Resep <span class="text-danger">*</span></label>
                <input type="text" class="form-control fw-bold text-primary" id="nomor_resep" name="nomor_resep" value="{{ $nomorResep }}" readonly required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Tanggal Resep <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_resep" name="tanggal_resep" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Pasien <span class="text-danger">*</span></label>
                <select class="form-select fw-bold" id="pasien_id" name="pasien_id" required>
                    <option value="">-- Pilih Pasien --</option>
                    @foreach($pasiens as $pasien)
                        <option value="{{ $pasien->id }}">{{ $pasien->no_rm }} - {{ $pasien->nama_pasien }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Dokter Pemeriksa</label>
                <select class="form-select" id="pegawai_id" name="pegawai_id">
                    <option value="">-- Pilih Dokter --</option>
                    @foreach($dokters as $dokter)
                        <option value="{{ $dokter->id }}">{{ $dokter->nama_pegawai }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="fas fa-pills me-2"></i>Daftar Resep Obat</h5>
        
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle" id="tableItems">
                <thead class="table-primary text-dark">
                    <tr>
                        <th width="35%">Pilih Obat / Barang</th>
                        <th width="15%" class="text-center">Sisa Stok</th>
                        <th width="10%">Qty <span class="text-danger">*</span></th>
                        <th width="30%">Aturan Pakai (Signa) <span class="text-danger">*</span></th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- JS will populate rows -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddItem">
                                <i class="fas fa-plus me-1"></i> Tambah Obat
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Keterangan / Diagnosa Tambahan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Catatan resep..."></textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-5 fs-5 shadow-sm">
                <i class="fas fa-save me-2"></i> Simpan Resep
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function () {
    let barangs = @json($barangs);
    let itemIndex = 0;

    function addRow() {
        let options = '<option value="">-- Cari Obat --</option>';
        barangs.forEach(function(b) {
            options += `<option value="${b.id}" data-stok="${b.stok}">${b.nama_barang} (Stok: ${b.stok} ${b.satuan})</option>`;
        });

        let row = `
        <tr class="item-row">
            <td>
                <select class="form-select select-barang" name="items[${itemIndex}][master_barang_id]" required>
                    ${options}
                </select>
            </td>
            <td class="text-center">
                <span class="badge bg-secondary stok-tersedia">0</span>
            </td>
            <td>
                <input type="number" class="form-control input-qty text-center fw-bold text-danger" name="items[${itemIndex}][jumlah]" min="1" value="1" required>
                <div class="invalid-feedback err-qty" style="display:none; font-size:12px;">Stok Kurang!</div>
            </td>
            <td>
                <input type="text" class="form-control" name="items[${itemIndex}][aturan_pakai]" placeholder="3 x 1 Sesudah Makan" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger btnRemove"><i class="fas fa-times"></i></button>
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
        } else {
            alert("Resep minimal memiliki 1 obat.");
        }
    });

    $(document).on('change', '.select-barang', function() {
        let stok = $(this).find(':selected').data('stok') || 0;
        let row = $(this).closest('tr');
        row.find('.stok-tersedia').text(stok);
        row.find('.input-qty').attr('max', stok).val(1);
        checkQty(row);
    });

    $(document).on('input', '.input-qty', function() {
        checkQty($(this).closest('tr'));
    });

    function checkQty(row) {
        let maxStok = parseInt(row.find('.select-barang :selected').data('stok')) || 0;
        let val = parseInt(row.find('.input-qty').val()) || 0;
        
        if (val > maxStok) {
            row.find('.input-qty').addClass('is-invalid');
            row.find('.err-qty').show();
            return false;
        } else {
            row.find('.input-qty').removeClass('is-invalid');
            row.find('.err-qty').hide();
            return true;
        }
    }

    $('#formResep').on('submit', function (e) {
        e.preventDefault();
        
        let valid = true;
        $('#tableItems tbody tr').each(function() {
            if (!checkQty($(this))) valid = false;
        });

        if (!valid) {
            alert("Jumlah resep obat melebihi stok yang ada di Apotek/Gudang. Periksa kembali yang berwarna merah.");
            return;
        }
        
        let btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...');

        $.ajax({
            url: '/farmasi/resep',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    alert(res.message);
                    window.location.href = '/farmasi/resep';
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i> Simpan Resep');
                if (xhr.status === 422 && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Terjadi kesalahan sistem, cek kelengkapan data obat.');
                }
            }
        });
    });
});
</script>
@endsection
