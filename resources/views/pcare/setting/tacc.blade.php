@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
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
    .konhakes-breadcrumb a:hover { text-decoration: underline; }
    .konhakes-summary-text {
        font-size: 13px;
        color: #333;
        margin-bottom: 8px;
    }
    .table-pcare-grid {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 15px;
    }
    .table-pcare-grid th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
    }
    .table-pcare-grid th.col-sort a {
        color: #3c8dbc !important;
        text-decoration: none;
    }
    .table-pcare-grid th.col-sort a:hover { text-decoration: underline; }
    .table-pcare-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-pcare-grid tbody tr:nth-of-type(odd) td  { background-color: #ffffff; }
    .table-pcare-grid tbody tr:nth-of-type(even) td { background-color: #f9f9f9; }
    .table-pcare-grid tbody tr:hover td             { background-color: #f5f5f5; }
    .btn-add-tacc {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        font-size: 12px;
        padding: 4px 14px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        cursor: pointer;
        line-height: 1.5;
        margin-bottom: 10px;
    }
    .btn-add-tacc:hover { background-color: #008d4c; }
    .filter-input {
        width: 100%;
        border: 1px solid #d2d6de;
        padding: 3px 6px;
        font-size: 12px;
        border-radius: 2px;
        outline: none;
    }
    .filter-input:focus {
        border-color: #3c8dbc;
    }
    .action-icon {
        color: #3c8dbc;
        margin: 0 4px;
        font-size: 13px;
        text-decoration: none;
    }
    .action-icon:hover { color: #204d74; }
    .action-icon.trash { color: #3c8dbc; }
    .action-icon.trash:hover { color: #dd4b39; }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging PCare - Setting TACC</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <span style="color:#777;">Bridging PCare - Setting TACC</span>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tombol Add --}}
    <div>
        <a href="/pcare-tacc/create" class="btn-add-tacc">
            Add
        </a>
    </div>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>1-{{ $total }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel dengan filter baris kedua --}}
    <div class="table-responsive" style="border: none;">
        <form method="GET" action="/pcare-tacc/index" id="formFilterTacc">
            <table class="table-pcare-grid">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th class="col-sort" style="width:140px;">
                            <a href="javascript:void(0)">Kode TACC</a>
                        </th>
                        <th class="col-sort" style="width:180px;">
                            <a href="javascript:void(0)">Nama TACC</a>
                        </th>
                        <th class="col-sort">
                            <a href="javascript:void(0)">Alasan TACC</a>
                        </th>
                        <th class="col-sort" style="width:180px;">
                            <a href="javascript:void(0)">Diubah Pada</a>
                        </th>
                        <th style="width:100px; text-align:center;"></th>
                    </tr>
                    <tr style="background:#fff;">
                        <td></td>
                        <td>
                            <input type="text" name="kode_tacc" value="{{ request('kode_tacc') }}" class="filter-input" onchange="document.getElementById('formFilterTacc').submit()">
                        </td>
                        <td>
                            <input type="text" name="nama_tacc" value="{{ request('nama_tacc') }}" class="filter-input" onchange="document.getElementById('formFilterTacc').submit()">
                        </td>
                        <td>
                            <input type="text" name="alasan_tacc" value="{{ request('alasan_tacc') }}" class="filter-input" onchange="document.getElementById('formFilterTacc').submit()">
                        </td>
                        <td>
                            <input type="text" class="filter-input" disabled style="background:#fcfcfc;">
                        </td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($taccs as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->kode_tacc }}</td>
                        <td>{{ $item->nama_tacc }}</td>
                        <td style="font-size:12px; color:#444; word-break:break-all;">
                            {{ $item->alasan_tacc }}
                        </td>
                        <td style="font-size:12px; color:#555;">
                            {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="/pcare-tacc/{{ $item->id }}" class="action-icon" title="Lihat">
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="/pcare-tacc/{{ $item->id }}/edit" class="action-icon" title="Ubah">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="/pcare-tacc/{{ $item->id }}/delete" class="action-icon trash" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data TACC ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-1"></i> Data TACC tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>
</div>
@endsection
