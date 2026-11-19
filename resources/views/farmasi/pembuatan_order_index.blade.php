@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 TRANSAKSI PEMESANAN BARANG (PO) */
    .po-page {
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
    .card-po-box {
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
    .btn-prof-refresh {
        background-color: #ffffff;
        border: 1px solid #ccc;
        color: #333 !important;
        font-size: 12.5px;
        padding: 4px 10px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        line-height: 1.4;
    }
    .btn-prof-refresh:hover {
        background-color: #e6e6e6;
        border-color: #adadad;
    }
    .btn-prof-buat-po {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        font-size: 12.5px;
        padding: 4px 14px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        line-height: 1.4;
        font-weight: 500;
    }
    .btn-prof-buat-po:hover {
        background-color: #008d4c;
        color: #ffffff !important;
    }
    .btn-prof-cetak {
        background-color: #3c8dbc;
        border: 1px solid #367fa9;
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
    .btn-prof-cetak:hover {
        background-color: #367fa9;
        color: #ffffff !important;
    }
    .cyan-table-separator {
        border-top: 2px solid #00c0ef !important;
        margin-top: 12px;
        margin-bottom: 0px;
    }
    .table-po-grid {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 12.5px;
        margin-bottom: 15px;
    }
    .table-po-grid th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
        color: #337ab7;
    }
    .table-po-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-po-grid tbody tr:nth-of-type(odd) td {
        background-color: #ffffff;
    }
    .table-po-grid tbody tr:nth-of-type(even) td {
        background-color: #f9f9f9;
    }
    .table-po-grid tbody tr:hover td {
        background-color: #f5f5f5;
    }
</style>

<div class="po-page">
    <!-- Header Title & Breadcrumb (KONHAKES 1:1) -->
    <div class="konhakes-header">
        <h3 class="konhakes-title">Transaksi Pemesanan Barang (PO)</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a> <span style="color: #ccc; margin: 0 2px;">&gt;</span> <a href="{{ url()->current() }}" style="color: #777;">Transaksi Pemesanan Barang (PO)</a>
        </div>
    </div>

    <!-- Alert Flash Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Main Card with Orange Top Border (KONHAKES 1:1) -->
    <div class="card-po-box">
        <!-- Toolbar (KONHAKES 1:1) -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-prof-cari" data-bs-toggle="collapse" data-bs-target="#boxFilterPO" aria-expanded="false">
                    <i class="fas fa-search"></i> Cari data
                </button>
                <a href="/pembuatanorder/index" class="btn btn-prof-refresh" title="Segarkan Data">
                    <i class="fas fa-sync-alt"></i>
                </a>
                <a href="/pembuatanorder/create" class="btn btn-prof-buat-po">
                    <i class="fas fa-plus"></i> Buat PO
                </a>
            </div>
            <div>
                <a href="/pembuatanorder/cetak-pdf{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" target="_blank" class="btn btn-prof-cetak">
                    <i class="fas fa-file-pdf"></i> Cetak Laporan(pdf)
                </a>
            </div>
        </div>

        <!-- Filter Box (Collapsible) -->
        <div class="collapse mb-3 {{ request()->has('q') || request()->has('vendor_id') || request()->has('tgl_mulai') ? 'show' : '' }}" id="boxFilterPO">
            <div class="p-3 border rounded bg-light">
                <form action="/pembuatanorder/index" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">Cari No. PO / Kurir</label>
                        <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}" placeholder="Ketik nomor PO...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">Vendor / Distributor</label>
                        <select name="vendor_id" class="form-select form-select-sm">
                            <option value="">-- Semua Vendor --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" class="form-control form-control-sm" value="{{ request('tgl_mulai') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" class="form-control form-control-sm" value="{{ request('tgl_selesai') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill" style="background-color: #3c8dbc; border-color: #367fa9;">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="/pembuatanorder/index" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cyan Border Separator (KONHAKES 1:1) -->
        <div class="cyan-table-separator"></div>

        <!-- Table Grid (KONHAKES 1:1) -->
        <div class="table-responsive">
            <table class="table-po-grid">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">#</th>
                        <th style="width: 170px;">No. PO</th>
                        <th style="width: 130px;">Tanggal</th>
                        <th>Vendor</th>
                        <th style="width: 140px;">Tgl.Kirim</th>
                        <th style="width: 220px;">Pengiriman/Kurir</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                        <td class="fw-semibold text-primary">
                            <a href="javascript:void(0)" onclick="lihatDetailPO({{ $order->id }})" class="text-decoration-none">
                                {{ $order->nomor_po }}
                            </a>
                        </td>
                        <td>{{ $order->tanggal_po ? \Carbon\Carbon::parse($order->tanggal_po)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $order->masterVendor->nama_vendor ?? '-' }}</td>
                        <td>{{ $order->tgl_kirim ? \Carbon\Carbon::parse($order->tgl_kirim)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $order->pengiriman_kurir ?? '-' }}</td>
                        <td style="text-align: center;">
                            <div class="btn-group btn-group-sm">
                                <a href="javascript:void(0)" onclick="lihatDetailPO({{ $order->id }})" class="btn btn-outline-info btn-sm py-0 px-2" title="Detail PO">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/pembuatanorder/{{ $order->id }}/cetak-pdf" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" title="Cetak Surat PO">
                                    <i class="fas fa-print"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" onclick="hapusPO({{ $order->id }}, '{{ $order->nomor_po }}')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-3 text-muted" style="padding-left: 15px;">
                            Tidak ada data yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="small text-muted">
                Menampilkan {{ $orders->firstItem() }} sampai {{ $orders->lastItem() }} dari {{ $orders->total() }} data
            </div>
            <div>
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- MODAL DETAIL PO -->
<div class="modal fade" id="modalDetailPO" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white py-2 px-3" style="background-color: #3c8dbc !important;">
                <h5 class="modal-title fs-6 fw-semibold text-white">
                    <i class="fas fa-file-invoice me-1"></i> Detail Pemesanan Barang (PO) - <span id="detailNoPo">-</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2 mb-3 small">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold" style="width: 120px;">No. PO</td>
                                <td style="width: 10px;">:</td>
                                <td id="modalPoNo">-</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal</td>
                                <td>:</td>
                                <td id="modalPoTgl">-</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Vendor</td>
                                <td>:</td>
                                <td id="modalPoVendor" class="fw-bold text-primary">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold" style="width: 120px;">Tgl. Kirim</td>
                                <td style="width: 10px;">:</td>
                                <td id="modalPoTglKirim">-</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Kurir/Pengiriman</td>
                                <td>:</td>
                                <td id="modalPoKurir">-</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Alamat Kirim</td>
                                <td>:</td>
                                <td id="modalPoAlamat" class="small">-</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 35px; text-align: center;">No</th>
                                <th style="width: 110px;">Kode Barang</th>
                                <th>Nama Barang</th>
                                <th style="width: 90px;">Kemasan</th>
                                <th style="width: 75px; text-align: right;">Jumlah</th>
                                <th style="width: 110px; text-align: right;">Harga</th>
                                <th style="width: 125px; text-align: right;">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody id="modalPoTableBody">
                            <tr><td colspan="7" class="text-center py-2">Memuat item...</td></tr>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">Total:</td>
                                <td style="text-align: right;" id="modalPoTotalQty">0</td>
                                <td></td>
                                <td style="text-align: right;" id="modalPoTotalHarga">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <a href="#" id="modalBtnPrint" target="_blank" class="btn btn-primary btn-sm px-3" style="background-color: #3c8dbc; border-color: #367fa9;">
                    <i class="fas fa-print me-1"></i> Cetak Dokumen PO
                </a>
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function lihatDetailPO(id) {
    const modalEl = document.getElementById('modalDetailPO');
    const modal = new bootstrap.Modal(modalEl);
    
    $('#modalPoTableBody').html('<tr><td colspan="7" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div> Memuat detail...</td></tr>');
    modal.show();

    $.ajax({
        url: '/pembuatanorder/' + id,
        type: 'GET',
        success: function(res) {
            if (res.success && res.order) {
                const o = res.order;
                $('#detailNoPo').text(o.nomor_po);
                $('#modalPoNo').text(o.nomor_po);
                $('#modalPoTgl').text(o.tanggal_po);
                $('#modalPoVendor').text(o.master_vendor ? o.master_vendor.nama_vendor : '-');
                $('#modalPoTglKirim').text(o.tgl_kirim || '-');
                $('#modalPoKurir').text(o.pengiriman_kurir || '-');
                $('#modalPoAlamat').text(o.alamat_kirim || '-');
                $('#modalBtnPrint').attr('href', '/pembuatanorder/' + o.id + '/cetak-pdf');

                let rowsHtml = '';
                let totalQty = 0;
                let grandTotal = 0;

                if (o.details && o.details.length > 0) {
                    o.details.forEach((item, idx) => {
                        const kode = item.master_barang ? item.master_barang.kode_barang : '-';
                        const nama = item.master_barang ? item.master_barang.nama_barang : '-';
                        const kemasan = item.kemasan || (item.master_barang ? item.master_barang.kemasan : '-');
                        const qty = parseFloat(item.jumlah) || 0;
                        const harga = parseFloat(item.harga_satuan) || 0;
                        const subtotal = parseFloat(item.subtotal) || (qty * harga);

                        totalQty += qty;
                        grandTotal += subtotal;

                        rowsHtml += `
                            <tr>
                                <td style="text-align: center;">${idx + 1}</td>
                                <td>${kode}</td>
                                <td>${nama}</td>
                                <td>${kemasan || '-'}</td>
                                <td style="text-align: right;">${qty.toLocaleString('id-ID')}</td>
                                <td style="text-align: right;">Rp ${harga.toLocaleString('id-ID')}</td>
                                <td style="text-align: right;">Rp ${subtotal.toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });
                } else {
                    rowsHtml = '<tr><td colspan="7" class="text-center text-muted py-2">Tidak ada detail item</td></tr>';
                }

                $('#modalPoTableBody').html(rowsHtml);
                $('#modalPoTotalQty').text(totalQty.toLocaleString('id-ID'));
                $('#modalPoTotalHarga').text('Rp ' + grandTotal.toLocaleString('id-ID'));
            }
        },
        error: function() {
            $('#modalPoTableBody').html('<tr><td colspan="7" class="text-center text-danger py-2">Gagal memuat detail data PO.</td></tr>');
        }
    });
}

function hapusPO(id, nomorPo) {
    if (confirm('Apakah Anda yakin ingin menghapus Pemesanan Barang (PO) ' + nomorPo + ' ini?')) {
        $.ajax({
            url: '/pembuatanorder/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    window.location.reload();
                } else {
                    alert('Gagal menghapus: ' + (res.message || 'Error'));
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus data.');
            }
        });
    }
}
</script>
@endsection
