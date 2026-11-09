@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Data Order Pembelian (PO)</h1>
    <a href="/farmasi/order-pembelian/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus me-1"></i> Buat PO Baru
    </a>
</div>

<div id="alertBox" class="alert d-none mb-3 shadow-sm" role="alert"></div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="table-responsive mt-2">
        <table class="table table-bordered table-hover align-middle" id="tabelPo">
            <thead style="background-color: #3c8dbc; color: white;">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Nomor PO</th>
                    <th width="15%">Tgl PO</th>
                    <th width="20%">Vendor</th>
                    <th width="15%" class="text-end">Total Harga</th>
                    <th width="15%" class="text-center">Status</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr data-id="{{ $item->id }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold text-primary">{{ $item->nomor_po }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_po)->format('d/m/Y') }}</td>
                    <td class="fw-bold">{{ $item->masterVendor->nama_vendor ?? '-' }}</td>
                    <td class="text-end fw-semibold text-danger">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->status === 'Pending')
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>
                        @elseif($item->status === 'Diterima')
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Diterima</span>
                        @else
                            <span class="badge bg-secondary">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info text-white btnDetail" data-id="{{ $item->id }}" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($item->status === 'Pending')
                        <button class="btn btn-sm btn-danger btnHapus" data-id="{{ $item->id }}" data-nama="{{ $item->nomor_po }}" title="Batalkan PO">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-shopping-cart fa-3x mb-3 d-block text-secondary"></i> 
                        <h5>Belum ada data Order Pembelian.</h5>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background-color: #3c8dbc; color: white;">
                <h5 class="modal-title"><i class="fas fa-file-invoice me-2"></i>Detail Order Pembelian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td width="35%" class="text-muted">Nomor PO</td><td>: <strong id="det_nomor_po"></strong></td></tr>
                            <tr><td class="text-muted">Vendor</td><td>: <strong id="det_vendor"></strong></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td width="35%" class="text-muted">Tanggal PO</td><td>: <strong id="det_tanggal"></strong></td></tr>
                            <tr><td class="text-muted">Status</td><td>: <strong id="det_status"></strong></td></tr>
                        </table>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-2">Item Barang:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="det_items"></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">TOTAL</th>
                                <th class="text-end text-danger fs-6" id="det_total"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(document).on('click', '.btnDetail', function () {
        let id = $(this).data('id');
        $.get(`/farmasi/order-pembelian/${id}`, function (data) {
            $('#det_nomor_po').text(data.nomor_po);
            $('#det_vendor').text(data.master_vendor ? data.master_vendor.nama_vendor : '-');
            $('#det_tanggal').text(data.tanggal_po);
            $('#det_status').text(data.status);
            
            let itemsHtml = '';
            let total = 0;
            data.details.forEach(function(item, index) {
                let subtotal = parseFloat(item.subtotal);
                total += subtotal;
                itemsHtml += `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.master_barang ? item.master_barang.nama_barang : '-'}</td>
                    <td class="text-center">${item.jumlah}</td>
                    <td class="text-end">Rp ${parseFloat(item.harga_satuan).toLocaleString('id-ID')}</td>
                    <td class="text-end fw-bold">Rp ${subtotal.toLocaleString('id-ID')}</td>
                </tr>`;
            });
            $('#det_items').html(itemsHtml);
            $('#det_total').text('Rp ' + total.toLocaleString('id-ID'));
            
            $('#modalDetail').modal('show');
        });
    });

    $(document).on('click', '.btnHapus', function () {
        let id = $(this).data('id');
        let nama = $(this).data('nama');
        if(confirm(`Yakin ingin membatalkan PO ${nama}?`)) {
            $.ajax({
                url: `/farmasi/order-pembelian/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if(res.success) {
                        $('#tabelPo tbody tr[data-id="'+id+'"]').fadeOut().remove();
                    }
                }
            });
        }
    });
});
</script>
@endsection
