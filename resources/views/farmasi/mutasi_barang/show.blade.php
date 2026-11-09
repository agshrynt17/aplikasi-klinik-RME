@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .mb-show-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .profaskes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
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
    .card-mb-box {
        background-color: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px 20px;
        margin-top: 10px;
    }
    .cyan-divider {
        height: 3px;
        background-color: #00c0ef;
        margin-top: 5px;
        margin-bottom: 15px;
    }
    .badge-belum-terima {
        background-color: #f39c12;
        color: #fff;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 2px;
    }
    .badge-sudah-terima {
        background-color: #00a65a;
        color: #fff;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 2px;
    }
</style>

<div class="mb-show-page">
    <div class="profaskes-header">
        <h1 class="profaskes-title">Detail Mutasi Barang Antar Gudang</h1>
        <div class="profaskes-breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> &gt; 
            <a href="{{ url('/mutasibarang/index') }}">Mutasi Barang Antar Gudang</a> &gt; 
            <span>Detail {{ $mutasi->nomor_mutasi }}</span>
        </div>
    </div>

    <div class="cyan-divider"></div>

    <div class="card-mb-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-primary fs-6 px-3 py-2">No. Mutasi: {{ $mutasi->nomor_mutasi }}</span>
                @if($mutasi->sudah_diterima === 'Sudah Terima' || $mutasi->status === 'Diterima')
                    <span class="badge-sudah-terima ms-2">Sudah Terima</span>
                @else
                    <span class="badge-belum-terima ms-2">Belum Terima</span>
                @endif
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url('/mutasibarang/' . $mutasi->id . '/cetak-pdf') }}" target="_blank" class="btn btn-primary btn-sm px-3">
                    <i class="fa fa-print me-1"></i> Cetak Surat Jalan
                </a>
                <a href="{{ url('/mutasibarang/index') }}" class="btn btn-secondary btn-sm px-3">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-3 py-2 bg-light border rounded mb-3" style="font-size: 12.5px;">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 140px; color: #666;">Tanggal Mutasi</td>
                        <td style="width: 15px;">:</td>
                        <td><strong>{{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d F Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Gudang Asal</td>
                        <td>:</td>
                        <td><strong>{{ $mutasi->gudangAsal ? $mutasi->gudangAsal->nama_gudang : 'GUDANG AWAL' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Gudang Tujuan</td>
                        <td>:</td>
                        <td><strong>{{ $mutasi->gudangTujuan ? $mutasi->gudangTujuan->nama_gudang : ($mutasi->unit_tujuan ?: 'Gudang Farmasi') }}</strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 140px; color: #666;">Petugas Pengirim</td>
                        <td style="width: 15px;">:</td>
                        <td>{{ $mutasi->petugas ?? 'dr. FAHMI RIZAL' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Penerima & Tgl Terima</td>
                        <td>:</td>
                        <td>{{ $mutasi->penerima ? ($mutasi->penerima . ' (' . \Carbon\Carbon::parse($mutasi->tanggal_terima)->format('d-m-Y') . ')') : 'Belum Diterima' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Keterangan</td>
                        <td>:</td>
                        <td>{{ $mutasi->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <h6 class="fw-bold mb-2" style="font-size: 13.5px; color: #444;">Daftar Item Obat / Alkes Dimutasi</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12px;">
                <thead style="background-color: #a6a6a6; color: white;">
                    <tr>
                        <th style="width: 35px; text-align: center;">No</th>
                        <th style="width: 130px;">Kode Barang</th>
                        <th>Nama Barang</th>
                        <th style="width: 100px; text-align: center;">Kemasan</th>
                        <th style="width: 160px;">Batch & Expire</th>
                        <th style="width: 100px; text-align: right;">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sumQty = 0; @endphp
                    @forelse($mutasi->details as $idx => $d)
                        @php $sumQty += $d->jumlah; @endphp
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>{{ $d->masterBarang ? ($d->masterBarang->kode_barang ?? '-') : '-' }}</td>
                            <td><strong>{{ $d->masterBarang ? $d->masterBarang->nama_barang : ('Item #' . $d->master_barang_id) }}</strong></td>
                            <td class="text-center">{{ $d->kemasan ?? ($d->masterBarang ? $d->masterBarang->kemasan : '-') }}</td>
                            <td>
                                @if($d->nomor_batch)<div>Batch: <strong>{{ $d->nomor_batch }}</strong></div>@endif
                                @if($d->tanggal_kadaluarsa)<div>Exp: {{ \Carbon\Carbon::parse($d->tanggal_kadaluarsa)->format('d-m-Y') }}</div>@endif
                                @if(!$d->nomor_batch && !$d->tanggal_kadaluarsa)-@endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($d->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3 text-muted">Tidak ada item yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="5" class="text-end">Total Item:</td>
                        <td class="text-end">{{ number_format($sumQty, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
