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
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }
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
    .btn-create-tkp {
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
    .btn-create-tkp:hover { background-color: #008d4c; }
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
        <h3 class="konhakes-title">Pcare Tkps</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-tkp/index">Pcare Tkps</a>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tombol Create --}}
    <div>
        <a href="/pcare-tkp/create" class="btn-create-tkp">
            Create Tkp
        </a>
    </div>

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>1-{{ $total }}</b> dari <b>{{ $total }}</b> item.
    </div>

    {{-- Tabel dengan filter baris kedua --}}
    <div class="table-responsive" style="border: none;">
        <form method="GET" action="/pcare-tkp/index" id="formFilterTkp">
            <table class="table-pcare-grid">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th class="col-sort" style="width:250px;">
                            <a href="javascript:void(0)">Kd Tkp</a>
                        </th>
                        <th class="col-sort">
                            <a href="javascript:void(0)">Nm Tkp</a>
                        </th>
                        <th class="col-sort" style="width:240px;">
                            <a href="javascript:void(0)">Diubah Pada</a>
                        </th>
                        <th style="width:100px; text-align:center;"></th>
                    </tr>
                    <tr style="background:#fff;">
                        <td></td>
                        <td>
                            <input type="text" name="kd_tkp" value="{{ request('kd_tkp') }}" class="filter-input" onchange="document.getElementById('formFilterTkp').submit()">
                        </td>
                        <td>
                            <input type="text" name="nm_tkp" value="{{ request('nm_tkp') }}" class="filter-input" onchange="document.getElementById('formFilterTkp').submit()">
                        </td>
                        <td>
                            <input type="text" class="filter-input" placeholder="" disabled style="background:#fcfcfc;">
                        </td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tkps as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->kd_tkp }}</td>
                        <td>{{ $item->nm_tkp }}</td>
                        <td style="font-size:12px; color:#555;">
                            {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i:s') : '-' }}
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="/pcare-tkp/{{ $item->id }}" class="action-icon" title="Lihat">
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="/pcare-tkp/{{ $item->id }}/edit" class="action-icon" title="Ubah">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="/pcare-tkp/{{ $item->id }}/delete" class="action-icon trash" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data TKP ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-1"></i> Data TKP tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>
</div>
@endsection
