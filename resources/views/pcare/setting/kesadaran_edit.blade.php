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
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #444;
        text-decoration: none;
    }
    .profaskes-breadcrumb a:hover { text-decoration: underline; }
    .form-group-profaskes {
        margin-bottom: 15px;
    }
    .form-group-profaskes label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .form-control-profaskes {
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
    .form-control-profaskes:focus {
        border-color: #3c8dbc;
    }
    .btn-save-profaskes {
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
    .btn-save-profaskes:hover { background-color: #008d4c; }
</style>

<div class="pcare-page">
    <div class="profaskes-header">
        <h3 class="profaskes-title">Update Kesadaran</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <a href="/pcare-kesadaran/index">Bridging PCare - Setting Data Kesadaran</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <span style="color:#777;">Update Kesadaran</span>
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

    <form action="/pcare-kesadaran/{{ $kesadaran->id }}/update" method="POST">
        @csrf
        <div class="form-group-profaskes">
            <label for="kd_sadar">Kd Sadar</label>
            <input type="text" name="kd_sadar" id="kd_sadar" class="form-control-profaskes" value="{{ old('kd_sadar', $kesadaran->kd_sadar) }}" required autofocus>
        </div>

        <div class="form-group-profaskes">
            <label for="nm_sadar">Nm Sadar</label>
            <input type="text" name="nm_sadar" id="nm_sadar" class="form-control-profaskes" value="{{ old('nm_sadar', $kesadaran->nm_sadar) }}" required>
        </div>

        <div class="form-group-profaskes">
            <label for="kd_sadar_reff">Kd Sadar Reff</label>
            <input type="text" name="kd_sadar_reff" id="kd_sadar_reff" class="form-control-profaskes" value="{{ old('kd_sadar_reff', $kesadaran->kd_sadar_reff) }}">
        </div>

        <div style="margin-top: 15px;">
            <button type="submit" class="btn-save-profaskes">Update</button>
            <a href="/pcare-kesadaran/index" class="btn btn-default btn-sm ms-2" style="background:#e7e7e7; border:1px solid #ccc; font-size:13px; padding:6px 14px;">Batal</a>
        </div>
    </form>
</div>
@endsection

