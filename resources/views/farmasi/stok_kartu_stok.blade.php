@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .profaskes-title-box {
        padding-bottom: 8px;
        margin-bottom: 12px;
        position: relative;
    }
    .profaskes-title-box h3 {
        font-size: 20px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .profaskes-orange-line {
        height: 2px;
        background-color: #f39c12;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .table-kartu-header th {
        background-color: #9aa0a6 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 12px;
        border: 1px solid #8c9298 !important;
        padding: 8px 10px !important;
        white-space: nowrap;
    }
    .table-kartu-data td {
        font-size: 12.5px;
        vertical-align: middle;
        padding: 7px 10px;
        border: 1px solid #e3e6e8;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center profaskes-title-box">
        <h3>Kartu Stok : <span class="text-primary">{{ $barang->nama_barang }}</span> ({{ $barang->kode_barang }})</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <a href="/stok/index">Data Stok</a> <span class="mx-1">&gt;</span> <span>Kartu Stok</span>
        </div>
    </div>
    <div class="profaskes-orange-line"></div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/stok/index" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Stok Barang
        </a>
        <div class="badge bg-light text-dark p-2 border fs-6">
            Total Stok Saat Ini: <strong class="{{ $barang->stok <= 0 ? 'text-danger' : 'text-success' }}">{{ $barang->stok }} {{ $barang->kemasan ?? $barang->satuan ?? 'Item' }}</strong>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 4px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 table-kartu-data">
                <thead class="table-kartu-header">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="14%">Tanggal</th>
                        <th width="16%">Gudang</th>
                        <th width="14%">Tipe Transaksi</th>
                        <th width="10%" class="text-center">Stok Awal</th>
                        <th width="10%" class="text-center">Perubahan</th>
                        <th width="10%" class="text-center">Stok Akhir</th>
                        <th width="22%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $index => $h)
                    <tr>
                        <td class="text-center text-muted">{{ $histories->firstItem() + $index }}</td>
                        <td>{{ $h->tanggal ? $h->tanggal->format('d/m/Y') : $h->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $h->masterGudang->nama_gudang ?? 'Gudang Farmasi' }}</td>
                        <td>
                            <span class="badge {{ $h->jenis_penyesuaian == 'Penambahan' ? 'bg-success' : 'bg-danger' }}">
                                {{ $h->tipe_penyesuaian ?? $h->jenis_penyesuaian }}
                            </span>
                        </td>
                        <td class="text-center">{{ $h->stok_sebelum }}</td>
                        <td class="text-center fw-bold {{ $h->jenis_penyesuaian == 'Penambahan' ? 'text-success' : 'text-danger' }}">
                            {{ $h->jenis_penyesuaian == 'Penambahan' ? '+' : '-' }}{{ $h->jumlah }}
                        </td>
                        <td class="text-center fw-bold">{{ $h->stok_sesudah }}</td>
                        <td class="text-muted">{{ $h->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            Belum ada riwayat transaksi kartu stok untuk barang ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($histories->hasPages())
        <div class="card-footer bg-white border-top py-2 px-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                Menampilkan {{ $histories->firstItem() ?? 0 }} - {{ $histories->lastItem() ?? 0 }} dari {{ $histories->total() }} transaksi
            </span>
            <div>
                {{ $histories->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
