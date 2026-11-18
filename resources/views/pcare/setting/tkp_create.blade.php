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
        color: #444;
        text-decoration: none;
    }
    .konhakes-breadcrumb a:hover { text-decoration: underline; }
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
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }
    .form-control-konhakes:focus {
        border-color: #3c8dbc;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(60,141,188,.6);
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
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Create Tkp</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <a href="/pcare-tkp/index">Pcare Tkps</a>
            <span style="color:#777; margin:0 5px;">&nbsp;</span>
            <span style="color:#777;">Create Tkp</span>
        </div>
    </div>

    {{-- Alert Error --}}
    @if ($errors->any())
    <div class="alert alert-danger py-2 px-3 small my-2">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/pcare-tkp/store" method="POST">
        @csrf
        <div class="form-group-konhakes">
            <label for="kd_tkp">Kd Tkp</label>
            <input type="text" name="kd_tkp" id="kd_tkp" class="form-control-konhakes" value="{{ old('kd_tkp') }}" required autofocus>
        </div>

        <div class="form-group-konhakes">
            <label for="nm_tkp">Nm Tkp</label>
            <input type="text" name="nm_tkp" id="nm_tkp" class="form-control-konhakes" value="{{ old('nm_tkp') }}" required>
        </div>

        <div style="margin-top: 15px;">
            <button type="submit" class="btn-save-konhakes">Save</button>
        </div>
    </form>
</div>
@endsection

