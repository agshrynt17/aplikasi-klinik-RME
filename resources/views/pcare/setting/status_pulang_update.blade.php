@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page {
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
    .table-pcare-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 6px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-pcare-grid tbody tr:nth-of-type(odd) td  { background-color: #ffffff; }
    .table-pcare-grid tbody tr:nth-of-type(even) td { background-color: #f9f9f9; }
    .table-pcare-grid tbody tr:hover td             { background-color: #f5f5f5; }
    .select-status-app {
        width: 100%;
        height: 32px;
        font-size: 13px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 4px 8px;
        outline: none;
        background-color: #fff;
    }
    .select-status-app:focus {
        border-color: #3c8dbc;
    }
    .btn-simpan-green {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        font-size: 13px;
        padding: 6px 16px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-block;
        font-weight: 400;
    }
    .btn-simpan-green:hover { background-color: #008d4c; }
    .btn-batal-orange {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 13px;
        padding: 6px 16px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-block;
        font-weight: 400;
        text-decoration: none;
        margin-left: 5px;
    }
    .btn-batal-orange:hover { background-color: #d68407; }
</style>

<div class="pcare-page">
    {{-- Header --}}
    <div class="profaskes-header">
        <h3 class="profaskes-title">Update Status Pulang</h3>
    </div>

    <form method="POST" action="/pcare-status-pulang/update">
        @csrf
        <div class="table-responsive" style="border: none;">
            <table class="table-pcare-grid">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:250px;">Kode (Pcare)</th>
                        <th>Status Pulang (Pcare)</th>
                        <th style="width:350px;">Pilih Status (Aplikasi)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statusPulang as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->kd_status_pulang }}</td>
                        <td>{{ $item->nm_status_pulang }}</td>
                        <td>
                            <select name="mapping[{{ $item->id }}]" class="select-status-app">
                                <option value="">-- Pilih Status --</option>
                                @foreach($opsiStatusAplikasi as $opsi)
                                    <option value="{{ $opsi }}" {{ strcasecmp($item->status_pulang_aplikasi ?? '', $opsi) === 0 ? 'selected' : '' }}>
                                        {{ $opsi }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 15px;">
            <button type="submit" class="btn-simpan-green">Simpan Perubahan</button>
            <a href="/pcare-status-pulang/index" class="btn-batal-orange">Batal</a>
        </div>
    </form>
</div>
@endsection

