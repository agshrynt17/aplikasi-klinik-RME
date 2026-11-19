@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 CREATE MUTASI BARANG */
    .mb-create-page {
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
        margin-bottom: 15px;
    }
    .card-mb-box {
        background-color: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px 20px;
        margin-bottom: 15px;
    }
    .form-group-mb {
        margin-bottom: 10px;
    }
    .form-group-mb label {
        font-size: 12px;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
        display: block;
    }
    .form-ctrl-mb {
        height: 32px;
        font-size: 12.5px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 4px 8px;
        width: 100%;
        color: #333;
        background-color: #fff;
    }
    .form-ctrl-mb:focus {
        border-color: #3c8dbc;
        outline: none;
    }
    .form-ctrl-mb[readonly] {
        background-color: #eeeeee !important;
        cursor: not-allowed;
    }
    .btn-mb-tutup {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        height: 32px;
        padding: 0 16px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-mb-tutup:hover {
        background-color: #d68407;
    }
    .btn-mb-tambah {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        height: 32px;
        padding: 0 16px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        font-weight: 500;
    }
    .btn-mb-tambah:hover {
        background-color: #008d4c;
    }
    .prof-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #444;
        margin-bottom: 12px;
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
        padding: 7px 10px;
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
</style>

<div class="mb-create-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h1 class="konhakes-title">Create Mutasi Barang</h1>
        <div class="konhakes-breadcrumb">
            <a href="{{ url('/dashboard') }}">Beranda</a> &gt; 
            <a href="{{ $backUrl ?? url('/mutasibarang/index') }}">{{ $backTitle ?? 'Mutasibarangs' }}</a> &gt; 
            <a href="{{ url()->current() }}">Create Mutasi Barang</a>
        </div>
    </div>

    {{-- Cyan divider --}}
    <div class="cyan-divider"></div>

    <form id="formCreateMutasi" onsubmit="submitFormMutasi(event)">
        @csrf
        <input type="hidden" name="from" id="from" value="{{ $from ?? request('from', '') }}">

        {{-- Row 1: Header Form Information --}}
        <div class="card-mb-box">
            <div class="row g-2 align-items-end">
                <div class="col-md-2 form-group-mb">
                    <label>No Mutasi</label>
                    <input type="text" class="form-ctrl-mb fw-bold" id="nomor_mutasi" name="nomor_mutasi" value="{{ $nomorMutasi }}" readonly required>
                </div>
                <div class="col-md-2 form-group-mb">
                    <label>Tgl Mutasi</label>
                    <input type="date" class="form-ctrl-mb" id="tanggal_mutasi" name="tanggal_mutasi" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-2 form-group-mb">
                    <label>Gudang Asal</label>
                    <select class="form-ctrl-mb" id="gudang_asal_id" name="gudang_asal_id" required onchange="onGudangAsalChange()">
                        <option value="">--Pilih--</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 form-group-mb">
                    <label>Gudang Tujuan</label>
                    <select class="form-ctrl-mb" id="gudang_tujuan_id" name="gudang_tujuan_id" required>
                        <option value="">--Pilih--</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ $loop->iteration == 2 ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 form-group-mb">
                    <label>Petugas</label>
                    <input type="text" class="form-ctrl-mb" id="petugas" name="petugas" value="{{ $petugas }}" readonly>
                </div>
                <div class="col-md-2 form-group-mb text-end">
                    <a href="{{ $backUrl ?? url('/mutasibarang/index') }}" class="btn-mb-tutup">
                        <i class="fa fa-times"></i> Tutup
                    </a>
                </div>
            </div>

            {{-- Row 2: Input Item Mutasi --}}
            <div class="row g-2 align-items-start mt-3 pt-3 border-top">
                <div class="col-md-3 form-group-mb">
                    <label>Kode Barang</label>
                    <select class="form-ctrl-mb" id="in_barang_id" onchange="onSelectBarang(this)">
                        <option value="">--Pilih--</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}" 
                                    data-kode="{{ $b->kode_barang }}" 
                                    data-nama="{{ $b->nama_barang }}" 
                                    data-kemasan="{{ $b->kemasan ?? 'Box' }}" 
                                    data-stok="{{ $b->stok }}">
                                {{ $b->nama_barang }} ({{ $b->kode_barang }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-danger d-block" style="font-size: 11px; margin-top: 2px;">
                        Barang harus berbeda disetiap baris input
                    </small>
                </div>
                <div class="col-md-2 form-group-mb">
                    <label>Kemasan</label>
                    <select class="form-ctrl-mb" id="in_kemasan">
                        <option value="">--Pilih--</option>
                        <option value="Box">Box</option>
                        <option value="Botol">Botol</option>
                        <option value="Strip">Strip</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Ampul">Ampul</option>
                        <option value="Vial">Vial</option>
                        <option value="Pcs">Pcs</option>
                    </select>
                </div>
                <div class="col-md-1 form-group-mb">
                    <label>Jml.Keluar</label>
                    <input type="number" step="any" class="form-ctrl-mb text-end" id="in_jumlah" value="1" min="1">
                </div>
                <div class="col-md-2 form-group-mb">
                    <label>Pilih No. Batch</label>
                    <select class="form-ctrl-mb" id="in_batch_select" onchange="onSelectBatch(this)">
                        <option value="">--Cari--</option>
                    </select>
                    <small class="text-muted d-block" style="font-size: 11px; margin-top: 2px;">
                        Batch yang muncul sesuai kode barang
                    </small>
                </div>
                <div class="col-md-2 form-group-mb">
                    <label>Tgl Expire</label>
                    <input type="text" class="form-ctrl-mb" id="in_tgl_exp" readonly placeholder="-">
                </div>
                <div class="col-md-2 form-group-mb pt-3">
                    <button type="button" class="btn-mb-tambah mt-1" onclick="tambahBarisItem()">
                        <i class="fa fa-save"></i> OK, Tambahkan!
                    </button>
                </div>
            </div>
        </div>

        {{-- Section Detail Input --}}
        <div class="card-mb-box">
            <div class="prof-card-title">Detail Input</div>
            <div class="table-responsive">
                <table class="table-detail-input" id="tableDetailInput">
                    <thead>
                        <tr>
                            <th style="width: 35px; text-align: center;">No</th>
                            <th style="width: 140px;">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th style="width: 120px; text-align: center;">Kemasan</th>
                            <th style="width: 150px;">Batch</th>
                            <th style="width: 110px; text-align: right;">Jumlah Keluar</th>
                            <th style="width: 45px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bodyItemsMutasi">
                        <tr id="emptyRowMutasi">
                            <td colspan="7" class="text-center py-3 text-muted" style="font-size: 12px;">
                                Belum ada item yang ditambahkan. Silakan pilih barang di atas lalu klik "OK, Tambahkan!".
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f9f9f9; font-weight: bold;">
                            <td colspan="5" class="text-end" style="border-right: none;">Total</td>
                            <td class="text-end" id="sumTotalQty" style="border-left: none;">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <a href="{{ url('/mutasibarang/index') }}" class="btn btn-secondary btn-sm px-3">
                    <i class="fa fa-arrow-left me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-success px-4" id="btnSubmitMutasi" style="font-size: 13px; font-weight: 600;">
                    <i class="fa fa-check-circle me-1"></i> Simpan Mutasi Barang
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    var itemsList = [];

    function onGudangAsalChange() {
        // Refresh batches if barang already selected
        var bId = $('#in_barang_id').val();
        if (bId) {
            loadBatches(bId);
        }
    }

    function onSelectBarang(sel) {
        var opt = $(sel).find(':selected');
        if (!opt.val()) {
            $('#in_kemasan').val('');
            $('#in_batch_select').html('<option value="">--Cari--</option>');
            $('#in_tgl_exp').val('');
            return;
        }

        var kemasan = opt.data('kemasan') || 'Box';
        $('#in_kemasan').val(kemasan);

        loadBatches(opt.val());
    }

    function loadBatches(barangId) {
        var gudangId = $('#gudang_asal_id').val();
        $.ajax({
            url: '/mutasibarang/get-batches/' + barangId + '?gudang_id=' + (gudangId || ''),
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                var opts = '<option value="">--Cari--</option>';
                if (res.success && res.batches && res.batches.length > 0) {
                    res.batches.forEach(function(b) {
                        var expStr = b.tanggal_kadaluarsa ? b.tanggal_kadaluarsa.split('T')[0] : '';
                        opts += '<option value="' + b.nomor_batch + '" data-exp="' + expStr + '">' + b.nomor_batch + ' (Stok: ' + b.stok + ')</option>';
                    });
                }
                $('#in_batch_select').html(opts);
                $('#in_tgl_exp').val('');
            }
        });
    }

    function onSelectBatch(sel) {
        var opt = $(sel).find(':selected');
        var exp = opt.data('exp') || '';
        $('#in_tgl_exp').val(exp);
    }

    function tambahBarisItem() {
        var barangId = $('#in_barang_id').val();
        if (!barangId) {
            alert('Silakan pilih Kode Barang / Obat terlebih dahulu.');
            $('#in_barang_id').focus();
            return;
        }

        // Cek duplikasi barang
        var existing = itemsList.find(function(it) {
            return String(it.master_barang_id) === String(barangId);
        });
        if (existing) {
            alert('Barang ini sudah ada di daftar input. Sesuai aturan KONHAKES, barang harus berbeda di setiap baris input.');
            return;
        }

        var jumlah = parseFloat($('#in_jumlah').val()) || 0;
        if (jumlah <= 0) {
            alert('Jumlah keluar harus lebih dari 0.');
            $('#in_jumlah').focus();
            return;
        }

        var opt = $('#in_barang_id option:selected');
        var brgNama = opt.data('nama');
        var brgKode = opt.data('kode');
        var kemasan = $('#in_kemasan').val() || opt.data('kemasan') || 'Box';
        var batchNo = $('#in_batch_select').val() || '-';
        var tglExp = $('#in_tgl_exp').val() || '';

        itemsList.push({
            master_barang_id: barangId,
            kode_barang: brgKode,
            nama_barang: brgNama,
            kemasan: kemasan,
            nomor_batch: batchNo,
            tanggal_kadaluarsa: tglExp,
            jumlah: jumlah
        });

        renderTableItems();
        resetFormItem();
    }

    function resetFormItem() {
        $('#in_barang_id').val('');
        $('#in_kemasan').val('');
        $('#in_jumlah').val(1);
        $('#in_batch_select').html('<option value="">--Cari--</option>');
        $('#in_tgl_exp').val('');
    }

    function hapusItem(idx) {
        itemsList.splice(idx, 1);
        renderTableItems();
    }

    function renderTableItems() {
        var tbody = $('#bodyItemsMutasi');
        if (itemsList.length === 0) {
            tbody.html('<tr id="emptyRowMutasi"><td colspan="7" class="text-center py-3 text-muted" style="font-size: 12px;">Belum ada item yang ditambahkan. Silakan pilih barang di atas lalu klik "OK, Tambahkan!".</td></tr>');
            $('#sumTotalQty').text('0');
            return;
        }

        var rows = '';
        var totalQty = 0;
        itemsList.forEach(function(item, idx) {
            totalQty += parseFloat(item.jumlah) || 0;
            var batchDisplay = item.nomor_batch || '-';
            if (item.tanggal_kadaluarsa) {
                batchDisplay += ' (' + item.tanggal_kadaluarsa + ')';
            }

            rows += '<tr>' +
                '<td style="text-align: center;">' + (idx + 1) + '</td>' +
                '<td>' + item.kode_barang + '</td>' +
                '<td><strong>' + item.nama_barang + '</strong></td>' +
                '<td style="text-align: center;">' + item.kemasan + '</td>' +
                '<td>' + batchDisplay + '</td>' +
                '<td style="text-align: right; font-weight: 600;">' + Number(item.jumlah).toLocaleString('id-ID') + '</td>' +
                '<td style="text-align: center;">' +
                    '<button type="button" class="btn btn-outline-danger btn-sm py-0 px-1" onclick="hapusItem(' + idx + ')" title="Hapus baris">' +
                        '<i class="fa fa-trash"></i>' +
                    '</button>' +
                '</td>' +
            '</tr>';
        });

        tbody.html(rows);
        $('#sumTotalQty').text(Number(totalQty).toLocaleString('id-ID'));
    }

    function submitFormMutasi(e) {
        e.preventDefault();

        if (itemsList.length === 0) {
            alert('Tabel Detail Input masih kosong! Minimal harus ada 1 item barang yang dimutasi.');
            return;
        }

        var asal = $('#gudang_asal_id').val();
        var tujuan = $('#gudang_tujuan_id').val();
        if (!asal || !tujuan) {
            alert('Silakan pilih Gudang Asal dan Gudang Tujuan.');
            return;
        }
        if (asal === tujuan) {
            alert('Gudang Tujuan harus berbeda dengan Gudang Asal.');
            $('#gudang_tujuan_id').focus();
            return;
        }

        var btn = $('#btnSubmitMutasi');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');

        var formData = {
            _token: '{{ csrf_token() }}',
            from: $('#from').val(),
            nomor_mutasi: $('#nomor_mutasi').val(),
            tanggal_mutasi: $('#tanggal_mutasi').val(),
            gudang_asal_id: asal,
            gudang_tujuan_id: tujuan,
            petugas: $('#petugas').val(),
            items: itemsList
        };

        $.ajax({
            url: '/mutasibarang/store',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    window.location.href = res.redirect || '{{ $backUrl ?? url("/mutasibarang/index") }}';
                } else {
                    alert('Gagal: ' + res.message);
                    btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Simpan Mutasi Barang');
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
                btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Simpan Mutasi Barang');
            }
        });
    }
</script>
@endsection
