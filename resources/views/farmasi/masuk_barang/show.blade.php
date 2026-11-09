@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .bm-detail-page {
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
    .card-bm-box {
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
        font-size: 12px;
        font-weight: 600;
        padding: 7px 10px;
        border: 1px solid #999;
    }
    .detail-table td {
        padding: 6px 10px;
        font-size: 12px;
        border: 1px solid #ddd;
        vertical-align: middle;
    }
</style>

<div class="bm-detail-page">
    <div class="profaskes-header">
        <h1 class="profaskes-title">Detail Penerimaan Barang</h1>
        <div class="profaskes-breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> &gt; 
            <a href="{{ url('/barangmasuk/index') }}">Transaksi Penerimaan Barang</a> &gt; 
            <span>Detail {{ $mb->nomor_penerimaan }}</span>
        </div>
    </div>

    <div class="card-bm-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-success fs-6 px-3 py-2">Kode: {{ $mb->nomor_penerimaan }}</span>
                <span class="badge {{ $mb->status_pembayaran === 'Lunas' ? 'bg-success' : 'bg-danger' }} ms-2">
                    {{ $mb->status_pembayaran }}
                </span>
                <span class="badge bg-secondary ms-1">{{ $mb->jenis_pembayaran }}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url('/barangmasuk/' . $mb->id . '/cetak-pdf') }}" target="_blank" class="btn btn-primary btn-sm px-3">
                    <i class="fa fa-print me-1"></i> Cetak BKB / Faktur
                </a>
                <a href="{{ url('/barangmasuk/index') }}" class="btn btn-secondary btn-sm px-3">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-3 py-2 bg-light border rounded mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 150px; color: #666;">Tanggal Terima</td>
                        <td style="width: 15px;">:</td>
                        <td><strong>{{ \Carbon\Carbon::parse($mb->tanggal_terima)->format('d-m-Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Vendor / PBF</td>
                        <td>:</td>
                        <td><strong>{{ $mb->masterVendor ? $mb->masterVendor->nama_vendor : '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">No. Faktur Vendor</td>
                        <td>:</td>
                        <td><strong>{{ $mb->nomor_faktur }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Referensi PO</td>
                        <td>:</td>
                        <td>{{ $mb->kode_po ?? ($mb->orderPembelian ? $mb->orderPembelian->nomor_po : '-') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 150px; color: #666;">Jatuh Tempo</td>
                        <td style="width: 15px;">:</td>
                        <td>{{ $mb->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($mb->tanggal_jatuh_tempo)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Total Bayar (DP/Lunas)</td>
                        <td>:</td>
                        <td>Rp {{ number_format($mb->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Sisa Hutang</td>
                        <td>:</td>
                        <td><strong class="{{ $mb->sisa_bayar > 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($mb->sisa_bayar, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Keterangan</td>
                        <td>:</td>
                        <td>{{ $mb->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="cyan-divider"></div>

        <h6 class="fw-bold mb-2" style="font-size: 13.5px; color: #444;">Daftar Item Barang Masuk</h6>
        <div class="table-responsive">
            <table class="table detail-table">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">No</th>
                        <th>Gudang / Lokasi</th>
                        <th>Kode & Nama Barang</th>
                        <th style="text-align: center;">Kemasan</th>
                        <th>Batch / Exp / BPOM</th>
                        <th style="text-align: right;">Jumlah</th>
                        <th style="text-align: right;">HNA (Rp)</th>
                        <th style="text-align: right;">Diskon (Rp)</th>
                        <th style="text-align: right;">Sub Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sumItems = 0; @endphp
                    @forelse($mb->details as $idx => $d)
                        @php $sumItems += $d->subtotal; @endphp
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                {{ $d->masterGudang ? $d->masterGudang->nama_gudang : 'Gudang Utama' }}
                                @if($d->lokasi_rak) ({{ $d->lokasi_rak }}) @endif
                            </td>
                            <td>
                                <strong>{{ $d->masterBarang ? $d->masterBarang->nama_barang : 'Item #' . $d->master_barang_id }}</strong>
                                @if($d->masterBarang && $d->masterBarang->kode_barang)
                                    <small class="text-muted d-block">Kode: {{ $d->masterBarang->kode_barang }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $d->kemasan ?? ($d->masterBarang ? $d->masterBarang->kemasan : '-') }}</td>
                            <td>
                                @if($d->nomor_batch)<div>Batch: <strong>{{ $d->nomor_batch }}</strong></div>@endif
                                @if($d->tanggal_kadaluarsa)<div>Exp: {{ \Carbon\Carbon::parse($d->tanggal_kadaluarsa)->format('d-m-Y') }}</div>@endif
                                @if($d->nomor_bpom)<div>BPOM: {{ $d->nomor_bpom }}</div>@endif
                                @if(!$d->nomor_batch && !$d->tanggal_kadaluarsa && !$d->nomor_bpom)-@endif
                            </td>
                            <td class="text-end">{{ number_format($d->jumlah_terima, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($d->diskon_nominal, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-3 text-muted">Tidak ada rincian item.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    @php
                        $gTotal = $mb->grand_total > 0 ? $mb->grand_total : $mb->total_tagihan;
                    @endphp
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="8" class="text-end">Total Nilai Barang:</td>
                        <td class="text-end">Rp {{ number_format($mb->subtotal > 0 ? $mb->subtotal : $sumItems, 0, ',', '.') }}</td>
                    </tr>
                    @if($mb->diskon_total_nominal > 0)
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="8" class="text-end text-danger">Diskon Total ({{ number_format($mb->diskon_total_persen, 1) }}%):</td>
                        <td class="text-end text-danger">- Rp {{ number_format($mb->diskon_total_nominal, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($mb->ppn_nominal > 0)
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="8" class="text-end text-success">PPN ({{ number_format($mb->ppn_persen, 1) }}%):</td>
                        <td class="text-end text-success">+ Rp {{ number_format($mb->ppn_nominal, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr style="background-color: #f0f4f8; font-weight: bold; font-size: 13.5px;">
                        <td colspan="8" class="text-end">GRAND TOTAL PENERIMAAN:</td>
                        <td class="text-end text-primary">Rp {{ number_format($gTotal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
