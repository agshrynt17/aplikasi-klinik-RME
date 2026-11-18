@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .po-detail-page {
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
    .card-po-box {
        background-color: #fff;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px 20px;
        margin-top: 10px;
    }
    .cyan-divider {
        height: 3px;
        background-color: #00c0ef;
        margin-top: 15px;
        margin-bottom: 15px;
    }
    .detail-table th {
        background-color: #a6a6a6;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 600;
        padding: 7px 10px;
        border: 1px solid #999;
    }
    .detail-table td {
        padding: 6px 10px;
        font-size: 12.5px;
        border: 1px solid #ddd;
        vertical-align: middle;
    }
    .btn-action-prof {
        font-size: 12.5px;
        padding: 5px 14px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="po-detail-page">
    <div class="konhakes-header">
        <h1 class="konhakes-title">Detail Pemesanan Barang (PO)</h1>
        <div class="konhakes-breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> &gt; 
            <a href="{{ url('/pembuatanorder/index') }}">Transaksi Pemesanan Barang (PO)</a> &gt; 
            <span>Detail {{ $order->nomor_po }}</span>
        </div>
    </div>

    <div class="card-po-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-success fs-6 px-3 py-2">No. PO: {{ $order->nomor_po }}</span>
                <span class="badge bg-info text-dark ms-2">{{ $order->status ?? 'Draft' }}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url('/pembuatanorder/' . $order->id . '/cetak-pdf') }}" target="_blank" class="btn btn-primary btn-action-prof">
                    <i class="fa fa-print"></i> Cetak PO (PDF)
                </a>
                <a href="{{ url('/pembuatanorder/index') }}" class="btn btn-secondary btn-action-prof">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-3 py-2">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 140px; color: #666;">Tanggal PO</td>
                        <td style="width: 15px;">:</td>
                        <td><strong>{{ \Carbon\Carbon::parse($order->tanggal_po)->format('d-m-Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Vendor / Supplier</td>
                        <td>:</td>
                        <td><strong>{{ $order->masterVendor ? $order->masterVendor->nama_vendor : '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Telepon Vendor</td>
                        <td>:</td>
                        <td>{{ $order->masterVendor ? ($order->masterVendor->telepon ?? '-') : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Alamat Vendor</td>
                        <td>:</td>
                        <td>{{ $order->masterVendor ? ($order->masterVendor->alamat ?? '-') : '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 140px; color: #666;">Tgl. Estimasi Kirim</td>
                        <td style="width: 15px;">:</td>
                        <td>{{ $order->tgl_kirim ? \Carbon\Carbon::parse($order->tgl_kirim)->format('d-m-Y') : ($order->rencana_datang ? \Carbon\Carbon::parse($order->rencana_datang)->format('d-m-Y') : '-') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Pengiriman / Kurir</td>
                        <td>:</td>
                        <td>{{ $order->pengiriman_kurir ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Alamat Kirim</td>
                        <td>:</td>
                        <td>{{ $order->alamat_kirim ?? ($profil->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Keterangan</td>
                        <td>:</td>
                        <td>{{ $order->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="cyan-divider"></div>

        <h6 class="fw-bold mb-2" style="font-size: 13.5px; color: #444;">Daftar Item Pemesanan</h6>
        <div class="table-responsive">
            <table class="table table-bordered detail-table">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">No</th>
                        <th style="width: 130px;">Kode Barang</th>
                        <th>Nama Barang</th>
                        <th style="width: 130px; text-align: center;">Kemasan</th>
                        <th style="width: 100px; text-align: right;">Jumlah</th>
                        <th style="width: 140px; text-align: right;">Harga Satuan</th>
                        <th style="width: 150px; text-align: right;">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sumTotal = 0; @endphp
                    @forelse($order->details as $idx => $d)
                        @php $sumTotal += $d->subtotal; @endphp
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>{{ $d->masterBarang ? ($d->masterBarang->kode_barang ?? '-') : '-' }}</td>
                            <td>{{ $d->masterBarang ? $d->masterBarang->nama_barang : 'Item ID #' . $d->master_barang_id }}</td>
                            <td class="text-center">{{ $d->kemasan ?? ($d->masterBarang ? $d->masterBarang->kemasan : '-') }}</td>
                            <td class="text-end">{{ number_format($d->jumlah, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">Tidak ada detail item pemesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="6" class="text-end" style="font-size: 13px;">Total PO:</td>
                        <td class="text-end" style="font-size: 14px; color: #007bff;">
                            Rp {{ number_format($order->total_harga > 0 ? $order->total_harga : $sumTotal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
