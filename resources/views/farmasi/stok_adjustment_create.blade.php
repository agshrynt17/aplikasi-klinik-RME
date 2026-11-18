@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .konhakes-title-box {
        padding-bottom: 8px;
        margin-bottom: 12px;
        position: relative;
    }
    .konhakes-title-box h3 {
        font-size: 20px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .konhakes-orange-line {
        height: 2px;
        background-color: #f39c12;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .btn-prof-simpan {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 18px;
        border-radius: 3px;
    }
    .btn-prof-simpan:hover {
        background-color: #008d4c;
        color: #fff;
    }
    .btn-prof-tutup {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 18px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-prof-tutup:hover {
        background-color: #d68407;
        color: #fff;
    }
    .card-header-cyan {
        background-color: #fff;
        border-top: 3px solid #00c0ef;
        padding: 10px 15px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center konhakes-title-box">
        <h3>Transaksi Penyesuaian Barang</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <a href="/stok/index">Stok Adjustments</a> <span class="mx-1">&gt;</span> <span>Transaksi Penyesuaian Barang</span>
        </div>
    </div>
    <div class="konhakes-orange-line"></div>

    <!-- Error Alerts -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small" role="alert">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="/stok-adjustment/store" method="POST" id="formAdjustment">
        @csrf

        <!-- Top Section Row -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 4px;">
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label mb-1" style="font-size: 13px; color: #333;">Gudang</label>
                        <select name="gudang_id" id="gudang_id" class="form-select form-select-sm" style="font-size: 13px;">
                            @foreach($gudangs as $g)
                                <option value="{{ $g->id }}" {{ ($selectedGudang->id ?? '') == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama_gudang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1" style="font-size: 13px; color: #333;">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" style="font-size: 13px;" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1" style="font-size: 13px; color: #333;">Tipe</label>
                        <select name="tipe" class="form-select form-select-sm" style="font-size: 13px;">
                            <option value="Penyesuaian Stok" {{ request('tipe') == 'Penyesuaian Stok' ? 'selected' : '' }}>Penyesuaian Stok</option>
                            <option value="Stok Opname" {{ request('tipe') == 'Stok Opname' ? 'selected' : '' }}>Stok Opname</option>
                            <option value="Koreksi Stok">Koreksi Stok</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Item Disesuaikan -->
        <div class="card border-0 shadow-sm" style="border-radius: 4px; overflow: hidden;">
            <div class="card-header-cyan">
                <span>+ Item Barang Yang Disesuaikan</span>
                <a href="/stok/index" class="btn btn-prof-tutup py-1 px-3" style="font-size: 12px;">
                    Tutup
                </a>
            </div>
            <div class="card-body p-3">
                <div class="row g-2 align-items-end mb-3">
                    <!-- Barang -->
                    <div class="col-md-4">
                        <label class="form-label mb-1" style="font-size: 12.5px;">Barang</label>
                        <select name="master_barang_id" id="master_barang_id" class="form-select form-select-sm" style="font-size: 12.5px;" required>
                            <option value="">Pilih barang ...</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}" data-satuan="{{ $b->kemasan ?? $b->satuan ?? '-' }}" data-stok="{{ $b->stok }}">
                                    {{ $b->kode_barang }} - {{ $b->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Satuan -->
                    <div class="col-md-1">
                        <label class="form-label mb-1" style="font-size: 12.5px;">Satuan</label>
                        <input type="text" id="satuan_display" class="form-control form-control-sm bg-light" readonly style="font-size: 12.5px;">
                    </div>

                    <!-- Jml. Sistem -->
                    <div class="col-md-1">
                        <label class="form-label mb-1" style="font-size: 12.5px;">Jml. Sistem</label>
                        <input type="number" id="jml_sistem" class="form-control form-control-sm bg-light text-center" readonly value="0" style="font-size: 12.5px;">
                    </div>

                    <!-- Jml. Fisik -->
                    <div class="col-md-1">
                        <label class="form-label mb-1 text-danger" style="font-size: 12.5px;">Jml. Fisik *wajib</label>
                        <input type="number" name="jml_fisik" id="jml_fisik" class="form-control form-control-sm text-center" value="0" style="font-size: 12.5px;" required>
                    </div>

                    <!-- Selisih -->
                    <div class="col-md-1">
                        <label class="form-label mb-1" style="font-size: 12.5px;">Selisih</label>
                        <input type="number" id="selisih" class="form-control form-control-sm bg-light text-center" readonly value="0" style="font-size: 12.5px;">
                    </div>

                    <!-- Keterangan -->
                    <div class="col-md-4">
                        <label class="form-label mb-1 text-danger" style="font-size: 12.5px;">Keterangan *wajib</label>
                        <select name="keterangan" id="keterangan" class="form-select form-select-sm" style="font-size: 12.5px;" required>
                            <option value="">--Pilih--</option>
                            <option value="Selisih Stok Opname">Selisih Stok Opname</option>
                            <option value="Barang Rusak">Barang Rusak</option>
                            <option value="Barang Hilang">Barang Hilang</option>
                            <option value="Kadaluarsa">Kadaluarsa</option>
                            <option value="Penyesuaian Fisik">Penyesuaian Fisik</option>
                            <option value="Koreksi Data">Koreksi Data</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 pt-2">
                    <button type="submit" class="btn btn-prof-simpan">
                        <i class="fas fa-check-circle me-1"></i> OK, Simpan!
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    function calculateSelisih() {
        let sistem = parseInt($('#jml_sistem').val()) || 0;
        let fisik = parseInt($('#jml_fisik').val()) || 0;
        let diff = fisik - sistem;
        $('#selisih').val(diff);
        if (diff < 0) {
            $('#selisih').addClass('text-danger fw-bold').removeClass('text-success');
        } else if (diff > 0) {
            $('#selisih').addClass('text-success fw-bold').removeClass('text-danger');
        } else {
            $('#selisih').removeClass('text-danger text-success fw-bold');
        }
    }

    $('#master_barang_id').on('change', function() {
        let selected = $(this).find('option:selected');
        let satuan = selected.data('satuan') || '-';
        let stok = selected.data('stok') || 0;

        $('#satuan_display').val(satuan);
        $('#jml_sistem').val(stok);
        calculateSelisih();
    });

    $('#jml_fisik').on('input keyup change', function() {
        calculateSelisih();
    });
});
</script>
@endsection
