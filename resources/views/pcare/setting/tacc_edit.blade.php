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
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }
    .form-group-konhakes {
        margin-bottom: 15px;
    }
    .form-group-konhakes label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .form-control-konhakes {
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 13px;
        color: #555;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 2px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        outline: none;
    }
    .form-control-konhakes:focus {
        border-color: #3c8dbc;
    }
    .btn-save-konhakes {
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
    .btn-save-konhakes:hover { background-color: #008d4c; }
</style>

<div class="pcare-page">
    <div class="konhakes-header">
        <h3 class="konhakes-title">Update TACC</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pcare-tacc/index">Bridging PCare - Setting TACC</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="{{ url()->current() }}" style="color:#777;">Update TACC</a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger py-2 px-3 small my-2">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/pcare-tacc/{{ $tacc->id }}/update" method="POST">
        @csrf
        <div class="form-group-konhakes">
            <label for="kode_tacc">Kode TACC</label>
            <input type="text" name="kode_tacc" id="kode_tacc" class="form-control-konhakes" value="{{ old('kode_tacc', $tacc->kode_tacc) }}" required autofocus>
        </div>

        <div class="form-group-konhakes">
            <label for="nama_tacc">Nama TACC</label>
            <input type="text" name="nama_tacc" id="nama_tacc" class="form-control-konhakes" value="{{ old('nama_tacc', $tacc->nama_tacc) }}" required>
        </div>

        <div class="form-group-konhakes">
            <label for="alasan_tacc">Alasan TACC</label>
            <textarea name="alasan_tacc" id="alasan_tacc" class="form-control-konhakes" style="height:80px;">{{ old('alasan_tacc', $tacc->alasan_tacc) }}</textarea>
        </div>

        <div style="margin-top: 15px;">
            <button type="submit" class="btn-save-konhakes">Update</button>
            <a href="/pcare-tacc/index" class="btn btn-default btn-sm ms-2" style="background:#e7e7e7; border:1px solid #ccc; font-size:13px; padding:6px 14px;">Batal</a>
        </div>
    </form>
</div>
@endsection

