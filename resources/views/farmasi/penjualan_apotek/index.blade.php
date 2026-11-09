@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* PROFASKES 1:1 TRANSAKSI PENJUALAN APOTEK */
    .tpa-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .profaskes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    .profaskes-title {
        font-size: 19px;
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
    .profaskes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .orange-divider {
        height: 3px;
        background-color: #f39c12;
        margin-top: 5px;
        margin-bottom: 12px;
    }
    .card-tpa-box {
        background-color: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 12px 15px;
    }
    .tpa-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .btn-prof-orange {
        background-color: #f39c12;
        color: #ffffff !important;
        border: none;
        border-radius: 2px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-prof-orange:hover {
        background-color: #e08e0b;
        color: #fff;
    }
    .btn-prof-green {
        background-color: #00a65a;
        color: #ffffff !important;
        border: none;
        border-radius: 2px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-prof-green:hover {
        background-color: #008d4c;
        color: #fff;
    }
    .btn-prof-cyan {
        background-color: #00c0ef;
        color: #ffffff !important;
        border: none;
        border-radius: 2px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-prof-cyan:hover {
        background-color: #00a7d0;
        color: #fff;
    }
    .cyan-table-top {
        height: 3px;
        background-color: #00c0ef;
        margin-bottom: 0px;
    }
    .table-profaskes {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .table-profaskes th {
        background-color: #ffffff;
        color: #337ab7;
        font-weight: 600;
        border: 1px solid #f4f4f4;
        border-top: none;
        padding: 8px 10px;
        vertical-align: middle;
        text-align: left;
    }
    .table-profaskes td {
        border: 1px solid #f4f4f4;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-profaskes tbody tr:hover {
        background-color: #f9f9f9;
    }
    .badge-lunas {
        background-color: #00a65a;
        color: #fff;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-belum-lunas {
        background-color: #dd4b39;
        color: #fff;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-sebagian {
        background-color: #f39c12;
        color: #fff;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
    }
</style>

<div class="tpa-page">
    {{-- Header --}}
    <div class="profaskes-header">
        <h1 class="profaskes-title">Transaksi Penjualan Apotek</h1>
        <div class="profaskes-breadcrumb">
            <a href="/dashboard"><i class="fa fa-dashboard"></i> Beranda</a> &gt; <span>Transaksi Penjualan Apotek</span>
        </div>
    </div>
    <div class="orange-divider"></div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert" style="font-size: 12.5px;">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert" style="font-size: 12.5px;">
            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Main Box --}}
    <div class="card-tpa-box">
        {{-- Toolbar --}}
        <div class="tpa-toolbar">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn-prof-orange" data-bs-toggle="modal" data-bs-target="#modalCariData">
                    <i class="fas fa-search"></i> Cari data
                </button>
                <a href="{{ route('transaksipenjualan.create') }}" class="btn-prof-green">
                    <i class="fas fa-plus"></i> Transaksi Baru
                </a>
                @if(request('keyword') || request('tgl_awal') || request('tgl_akhir') || request('cara_bayar') || request('status_bayar'))
                    <a href="{{ route('transaksipenjualan.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size: 11.5px; border-radius: 2px;">
                        <i class="fas fa-times"></i> Reset Filter
                    </a>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('transaksipenjualan.hitung_ulang') }}" class="btn-prof-cyan">
                    <i class="fas fa-calculator"></i> Hitung Ulang
                </a>
                <a href="{{ route('transaksipenjualan.cetak_laporan', request()->query()) }}" target="_blank" class="btn-prof-green">
                    <i class="fas fa-print"></i> Cetak Laporan <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="cyan-table-top"></div>
        <div class="table-responsive">
            <table class="table-profaskes">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th style="width: 100px;">Status Bayar</th>
                        <th style="width: 140px;">Kode Transaksi</th>
                        <th style="width: 110px;">Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th style="width: 110px;">Telp</th>
                        <th style="width: 90px;">Cara Bayar</th>
                        <th style="width: 120px; text-align: right;">Total Transaksi</th>
                        <th style="width: 120px; text-align: right;">Jumlah Bayar</th>
                        <th style="width: 110px; text-align: right;">Kembali</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                        <tr>
                            <td style="text-align: center;">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light p-0 px-1 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px;">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu shadow-sm" style="font-size: 12px;">
                                        <li>
                                            <a class="dropdown-item py-1 text-primary btnDetail" href="javascript:void(0)" data-id="{{ $item->id }}">
                                                <i class="fas fa-eye me-1"></i> Lihat Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1 text-success" href="{{ route('transaksipenjualan.cetak_nota', $item->id) }}" target="_blank">
                                                <i class="fas fa-print me-1"></i> Cetak Nota Kasir
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('transaksipenjualan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus transaksi {{ $item->kode_transaksi }}? Stok barang akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item py-1 text-danger">
                                                    <i class="fas fa-trash me-1"></i> Batalkan / Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                @if(strtolower($item->status_bayar) === 'lunas')
                                    <span class="badge-lunas">Lunas</span>
                                @elseif(strtolower($item->status_bayar) === 'sebagian')
                                    <span class="badge-sebagian">Sebagian</span>
                                @else
                                    <span class="badge-belum-lunas">Belum Lunas</span>
                                @endif
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="text-primary fw-semibold btnDetail" data-id="{{ $item->id }}" style="text-decoration: none;">
                                    {{ $item->kode_transaksi ?: $item->nomor_faktur }}
                                </a>
                            </td>
                            <td>{{ $item->tanggal_penjualan ? \Carbon\Carbon::parse($item->tanggal_penjualan)->format('d m Y') : '-' }}</td>
                            <td>
                                <strong>{{ $item->pelanggan_nama ?: ($item->nama_pembeli ?: 'UMUM') }}</strong>
                                @if($item->pasien)
                                    <span class="text-muted" style="font-size: 11px;">(RM: {{ $item->pasien->no_rm }})</span>
                                @endif
                            </td>
                            <td>{{ $item->pelanggan_telp ?: '-' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->cara_bayar ?: 'Tunai' }}</span></td>
                            <td style="text-align: right; font-weight: 600;">
                                Rp {{ number_format($item->grand_total ?: $item->total_harga, 0, ',', '.') }}
                            </td>
                            <td style="text-align: right; color: #00a65a; font-weight: 600;">
                                Rp {{ number_format($item->dibayar, 0, ',', '.') }}
                            </td>
                            <td style="text-align: right; color: #555;">
                                Rp {{ number_format($item->kembalian, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="padding: 15px 10px; color: #777;">
                                Tidak ada data yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
            <div class="mt-3 d-flex justify-content-end">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL CARI DATA --}}
<div class="modal fade" id="modalCariData" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 4px;">
            <div class="modal-header py-2" style="background-color: #f39c12; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-search me-2"></i>Filter & Cari Transaksi Penjualan</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('transaksipenjualan.index') }}" method="GET">
                <div class="modal-body p-3" style="font-size: 12px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1">Kata Kunci (Kode / Pelanggan / Telp)</label>
                        <input type="text" name="keyword" class="form-control form-control-sm" value="{{ request('keyword') }}" placeholder="Ketik nomor faktur atau nama pelanggan...">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold mb-1">Tanggal Awal</label>
                            <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ request('tgl_awal') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold mb-1">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ request('tgl_akhir') }}">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-bold mb-1">Cara Bayar</label>
                            <select name="cara_bayar" class="form-select form-select-sm">
                                <option value="">-- Semua Cara Bayar --</option>
                                <option value="Tunai" {{ request('cara_bayar') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="Transfer" {{ request('cara_bayar') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="QRIS" {{ request('cara_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                <option value="Debit" {{ request('cara_bayar') == 'Debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold mb-1">Status Bayar</label>
                            <select name="status_bayar" class="form-select form-select-sm">
                                <option value="">-- Semua Status --</option>
                                <option value="Lunas" {{ request('status_bayar') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="Belum Lunas" {{ request('status_bayar') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="Sebagian" {{ request('status_bayar') == 'Sebagian' ? 'selected' : '' }}>Sebagian</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <a href="{{ route('transaksipenjualan.index') }}" class="btn btn-sm btn-secondary" style="font-size: 12px;">Reset</a>
                    <button type="submit" class="btn btn-sm btn-prof-orange" style="font-size: 12px;"><i class="fas fa-search me-1"></i> Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL TRANSAKSI --}}
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 4px;">
            <div class="modal-header py-2" style="background-color: #00c0ef; color: white;">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-receipt me-2"></i>Detail Transaksi Penjualan Apotek</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12px;">
                <div class="row mb-3 bg-light p-2 rounded border">
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td class="text-muted py-0" style="width: 100px;">Kode Transaksi</td><td class="py-0">: <strong id="det_kode" class="text-primary"></strong></td></tr>
                            <tr><td class="text-muted py-0">Tanggal</td><td class="py-0">: <span id="det_tanggal"></span></td></tr>
                            <tr><td class="text-muted py-0">Gudang Asal</td><td class="py-0">: <span id="det_gudang">-</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td class="text-muted py-0" style="width: 100px;">Pelanggan</td><td class="py-0">: <strong id="det_pelanggan"></strong></td></tr>
                            <tr><td class="text-muted py-0">Telepon</td><td class="py-0">: <span id="det_telp">-</span></td></tr>
                            <tr><td class="text-muted py-0">Dokter Rujukan</td><td class="py-0">: <span id="det_dokter">-</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td class="text-muted py-0" style="width: 100px;">Cara Bayar</td><td class="py-0">: <span id="det_cara_bayar" class="badge bg-light text-dark border"></span></td></tr>
                            <tr><td class="text-muted py-0">Status Bayar</td><td class="py-0">: <span id="det_status_bayar"></span></td></tr>
                            <tr><td class="text-muted py-0">No Resep</td><td class="py-0">: <span id="det_resep">-</span></td></tr>
                        </table>
                    </div>
                </div>

                <h6 class="fw-bold mb-2 text-secondary" style="font-size: 12px;"><i class="fas fa-list me-1"></i> Rincian Barang / Obat Terjual:</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm align-middle" style="font-size: 11.5px;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30px; text-align: center;">No</th>
                                <th>Nama Barang / Obat</th>
                                <th style="width: 110px;">Batch & Exp</th>
                                <th style="width: 70px;">Kemasan</th>
                                <th style="width: 50px; text-align: center;">Qty</th>
                                <th style="width: 110px;">Dosis & Aturan</th>
                                <th style="width: 90px; text-align: right;">Harga</th>
                                <th style="width: 100px; text-align: right;">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody id="det_items_body">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                            <tr>
                                <td class="text-muted py-1">Total Belanja</td>
                                <td class="text-end fw-bold py-1" id="det_total_belanja">Rp 0</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Diskon</td>
                                <td class="text-end text-danger py-1" id="det_diskon">Rp 0</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Pembulatan</td>
                                <td class="text-end py-1" id="det_pembulatan">Rp 0</td>
                            </tr>
                            <tr class="border-top">
                                <td class="fw-bold py-1 fs-6">Grand Total</td>
                                <td class="text-end fw-bold py-1 fs-6 text-primary" id="det_grand_total">Rp 0</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Pembayaran (Dibayar)</td>
                                <td class="text-end fw-bold text-success py-1" id="det_dibayar">Rp 0</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Kembali</td>
                                <td class="text-end fw-bold py-1" id="det_kembali">Rp 0</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="det_btn_print" target="_blank" class="btn btn-sm btn-prof-green">
                    <i class="fas fa-print me-1"></i> Cetak Nota Kasir
                </a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(document).on('click', '.btnDetail', function () {
        let id = $(this).data('id');
        $.get(`/transaksipenjualan/${id}`, function (data) {
            $('#det_kode').text(data.kode_transaksi || data.nomor_faktur || '-');
            $('#det_tanggal').text(data.tanggal_penjualan ? new Date(data.tanggal_penjualan).toLocaleDateString('id-ID') : '-');
            $('#det_gudang').text(data.gudang ? data.gudang.nama_gudang : '-');
            $('#det_pelanggan').text(data.pelanggan_nama || data.nama_pembeli || 'UMUM');
            $('#det_telp').text(data.pelanggan_telp || '-');
            $('#det_dokter').text(data.dokter ? data.dokter.nama_pegawai : (data.dokter_rujukan || '-'));
            $('#det_cara_bayar').text(data.cara_bayar || 'Tunai');
            $('#det_resep').text(data.no_resep_rujukan || '-');

            let statusBadge = '';
            if ((data.status_bayar || '').toLowerCase() === 'lunas') {
                statusBadge = '<span class="badge-lunas">Lunas</span>';
            } else if ((data.status_bayar || '').toLowerCase() === 'sebagian') {
                statusBadge = '<span class="badge-sebagian">Sebagian</span>';
            } else {
                statusBadge = '<span class="badge-belum-lunas">Belum Lunas</span>';
            }
            $('#det_status_bayar').html(statusBadge);

            let rows = '';
            if (data.details && data.details.length > 0) {
                data.details.forEach(function(item, idx) {
                    let nama = item.master_barang ? item.master_barang.nama_barang : '-';
                    let batch = item.nomor_batch || '-';
                    let exp = item.tanggal_kadaluarsa ? item.tanggal_kadaluarsa : '-';
                    let batchExp = batch !== '-' ? `${batch} (${exp})` : '-';
                    let aturan = (item.cara_minum || '') + (item.dosis ? ' / ' + item.dosis : '');

                    rows += `<tr>
                        <td class="text-center">${idx + 1}</td>
                        <td class="fw-semibold">${nama}</td>
                        <td class="text-muted" style="font-size: 11px;">${batchExp}</td>
                        <td>${item.kemasan || '-'}</td>
                        <td class="text-center">${item.jumlah}</td>
                        <td style="font-size: 11px;">${aturan || '-'}</td>
                        <td class="text-end">Rp ${parseFloat(item.harga_satuan || 0).toLocaleString('id-ID')}</td>
                        <td class="text-end fw-semibold">Rp ${parseFloat(item.subtotal || 0).toLocaleString('id-ID')}</td>
                    </tr>`;
                });
            } else {
                rows = `<tr><td colspan="8" class="text-center text-muted py-3">Tidak ada detail item</td></tr>`;
            }
            $('#det_items_body').html(rows);

            let total = parseFloat(data.total_harga || 0);
            let diskon = parseFloat(data.diskon || 0);
            let pembulatan = parseFloat(data.pembulatan || 0);
            let grandTotal = parseFloat(data.grand_total || total);
            let dibayar = parseFloat(data.dibayar || 0);
            let kembali = parseFloat(data.kembalian || 0);

            $('#det_total_belanja').text('Rp ' + total.toLocaleString('id-ID'));
            $('#det_diskon').text('Rp ' + diskon.toLocaleString('id-ID'));
            $('#det_pembulatan').text('Rp ' + pembulatan.toLocaleString('id-ID'));
            $('#det_grand_total').text('Rp ' + grandTotal.toLocaleString('id-ID'));
            $('#det_dibayar').text('Rp ' + dibayar.toLocaleString('id-ID'));
            $('#det_kembali').text('Rp ' + kembali.toLocaleString('id-ID'));

            $('#det_btn_print').attr('href', `/transaksipenjualan/${data.id}/cetak-nota`);
            $('#modalDetail').modal('show');
        });
    });
});
</script>
@endsection
