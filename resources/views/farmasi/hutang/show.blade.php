@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .ph-show-page {
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
    .card-ph-box {
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
</style>

<div class="ph-show-page">
    <div class="konhakes-header">
        <h1 class="konhakes-title">Detail & Riwayat Pembayaran Tagihan</h1>
        <div class="konhakes-breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> &gt; 
            <a href="{{ url('/pembayaran-hutang/index') }}">Data Pembayaran Hutang</a> &gt; 
            <span>Detail {{ $hutang->no_tagihan_display }}</span>
        </div>
    </div>

    <div class="card-ph-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-primary fs-6 px-3 py-2">No. Tagihan: {{ $hutang->no_tagihan_display }}</span>
                <span class="badge {{ $hutang->status === 'Lunas' ? 'bg-success' : 'bg-danger' }} ms-2">
                    {{ $hutang->status }}
                </span>
            </div>
            <div class="d-flex gap-2">
                @if($hutang->pembayarans->count() > 0)
                    <a href="{{ url('/pembayaran-hutang/' . $hutang->id . '/cetak-kuitansi') }}" target="_blank" class="btn btn-primary btn-sm px-3">
                        <i class="fa fa-print me-1"></i> Cetak Kuitansi Terakhir
                    </a>
                @endif
                <a href="{{ url('/pembayaran-hutang/index') }}" class="btn btn-secondary btn-sm px-3">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-3 py-2 bg-light border rounded mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 150px; color: #666;">Vendor / Supplier</td>
                        <td style="width: 15px;">:</td>
                        <td><strong>{{ $hutang->masterVendor ? $hutang->masterVendor->nama_vendor : '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Ref. Masuk Barang</td>
                        <td>:</td>
                        <td>{{ $hutang->masukBarang ? $hutang->masukBarang->nomor_penerimaan : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">No. Faktur Vendor</td>
                        <td>:</td>
                        <td><strong>{{ $hutang->masukBarang ? $hutang->masukBarang->nomor_faktur : '-' }}</strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 150px; color: #666;">Jatuh Tempo</td>
                        <td style="width: 15px;">:</td>
                        <td><strong>{{ $hutang->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->format('d-m-Y') : '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Total Tagihan</td>
                        <td>:</td>
                        <td><strong>Rp {{ number_format($hutang->jumlah_hutang, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Total Dibayar</td>
                        <td>:</td>
                        <td class="text-success"><strong>Rp {{ number_format($hutang->jumlah_dibayar, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Sisa Hutang</td>
                        <td>:</td>
                        <td class="text-danger fs-6"><strong>Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="cyan-divider"></div>

        <h6 class="fw-bold mb-2" style="font-size: 13.5px; color: #444;">Riwayat Pembayaran & Cicilan</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12.5px;">
                <thead style="background-color: #a6a6a6; color: white;">
                    <tr>
                        <th style="width: 35px; text-align: center;">No</th>
                        <th>No. Bukti / Kuitansi</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode Pembayaran</th>
                        <th style="text-align: right;">Nominal Bayar (Rp)</th>
                        <th>Keterangan</th>
                        <th style="width: 80px; text-align: center;">Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hutang->pembayarans as $idx => $p)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td><strong>{{ $p->nomor_bukti }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d-m-Y') }}</td>
                            <td>{{ $p->metode_pembayaran }}</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</td>
                            <td>{{ $p->keterangan ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ url('/pembayaran-hutang/' . $hutang->id . '/cetak-kuitansi?pembayaran_id=' . $p->id) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" title="Cetak Kuitansi">
                                    <i class="fa fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">Belum ada riwayat pembayaran yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
