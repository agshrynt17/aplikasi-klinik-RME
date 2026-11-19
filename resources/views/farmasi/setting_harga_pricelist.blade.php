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
    .konhakes-cyan-line {
        height: 2px;
        background-color: #00c0ef;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .table-pricelist-header th {
        background-color: #9aa0a6 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 12px;
        border: 1px solid #8c9298 !important;
        padding: 8px 10px !important;
        white-space: nowrap;
    }
    .table-pricelist-data td {
        font-size: 12.5px;
        vertical-align: middle;
        padding: 7px 10px;
        border: 1px solid #e3e6e8;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center konhakes-title-box">
        <h3>Pricelist Barang / Obat : <span class="text-primary">{{ $penjamin->nama_penjamin }}</span></h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a> <span class="mx-1">&gt;</span> <a href="/hargaobat/index">Setting Harga</a> <span class="mx-1">&gt;</span> <a href="{{ url()->current() }}">Pricelist {{ $penjamin->nama_penjamin }}</a>
        </div>
    </div>
    <div class="konhakes-cyan-line"></div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/hargaobat/index" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Penjamin
        </a>
        <form action="/hargaobat/{{ $penjamin->id }}/pricelist" method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama atau kode obat..." value="{{ request('q') }}" style="width: 250px;">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fas fa-search"></i>
            </button>
            @if(request('q'))
                <a href="/hargaobat/{{ $penjamin->id }}/pricelist" class="btn btn-sm btn-outline-secondary" title="Reset">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 4px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 table-pricelist-data">
                <thead class="table-pricelist-header">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="14%">Kode Barang</th>
                        <th width="32%">Nama Barang / Obat</th>
                        <th width="14%">Jenis / Kemasan</th>
                        <th width="12%" class="text-end">HNA (Rp)</th>
                        <th width="14%" class="text-end">Harga Jual (Rp)</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $index => $b)
                    @php
                        $customPrice = $customPrices[$b->id] ?? $b->harga_jual;
                        if (!$customPrice || $customPrice == 0) {
                            $customPrice = $b->hna > 0 ? round($b->hna * 1.25) : 0;
                        }
                    @endphp
                    <tr id="row-barang-{{ $b->id }}">
                        <td class="text-center text-muted">{{ $barangs->firstItem() + $index }}</td>
                        <td><span class="text-primary fw-semibold">{{ $b->kode_barang }}</span></td>
                        <td class="fw-bold">{{ $b->nama_barang }}</td>
                        <td>{{ $b->jenis ?? 'Obat' }} / {{ $b->kemasan ?? '-' }}</td>
                        <td class="text-end text-muted">{{ number_format($b->hna, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <input type="number" min="0" class="form-control form-control-sm text-end input-harga-jual" id="price-{{ $b->id }}" value="{{ round($customPrice) }}" style="font-size: 13px; font-weight: bold;">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-success btnSavePrice py-1 px-2" data-id="{{ $b->id }}" title="Simpan Harga">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            Data obat tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($barangs->hasPages())
        <div class="card-footer bg-white border-top py-2 px-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                Menampilkan {{ $barangs->firstItem() ?? 0 }} - {{ $barangs->lastItem() ?? 0 }} dari {{ $barangs->total() }} item
            </span>
            <div>
                {{ $barangs->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btnSavePrice').on('click', function() {
        let btn = $(this);
        let barangId = btn.data('id');
        let hargaVal = $(`#price-${barangId}`).val();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: `/hargaobat/{{ $penjamin->id }}/pricelist`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                master_barang_id: barangId,
                harga_jual: hargaVal
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Tersimpan');
                btn.removeClass('btn-success').addClass('btn-secondary');
                setTimeout(() => {
                    btn.removeClass('btn-secondary').addClass('btn-success').html('<i class="fas fa-save me-1"></i> Simpan');
                }, 2000);
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                alert('Gagal menyimpan harga obat.');
            }
        });
    });
});
</script>
@endsection
