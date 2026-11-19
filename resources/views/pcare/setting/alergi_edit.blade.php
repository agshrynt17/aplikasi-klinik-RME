@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #333; }
    .konhakes-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .konhakes-title { font-size: 20px; font-weight: 400; color: #333; margin: 0; }
    .konhakes-breadcrumb { font-size: 12px; color: #777; }
    .konhakes-breadcrumb a { color: #444; text-decoration: none; }
    .form-group-konhakes { margin-bottom: 15px; }
    .form-group-konhakes label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px; }
    .form-control-konhakes {
        width: 100%; height: 34px; padding: 6px 12px; font-size: 13px; color: #555;
        background-color: #fff; border: 1px solid #ccc; border-radius: 2px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075); outline: none;
    }
    .form-control-konhakes:focus { border-color: #3c8dbc; }
    .btn-save { background-color: #00a65a; border: 1px solid #008d4c; color: #fff; font-size: 13px; padding: 6px 16px; border-radius: 3px; cursor: pointer; }
    .btn-save:hover { background-color: #008d4c; }
</style>

<div class="pcare-page">
    <div class="konhakes-header">
        <h3 class="konhakes-title">Update Data Alergi PCare</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>&nbsp;»&nbsp;
            <a href="/pcare-alergi/index">Bridging PCare - Setting Data Alergi</a>&nbsp;»&nbsp;
            <a href="{{ url()->current() }}">Update</a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger py-2 px-3 small my-2">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    <form action="/pcare-alergi/{{ $alergi->id }}/update" method="POST">
        @csrf
        <div class="form-group-konhakes">
            <label>Jenis</label>
            <input type="text" class="form-control-konhakes" value="{{ $alergi->nm_jenis }}" disabled style="background:#eee;">
        </div>
        <div class="form-group-konhakes">
            <label>Kd Alergi</label>
            <input type="text" class="form-control-konhakes" value="{{ $alergi->kd_alergi }}" disabled style="background:#eee;">
        </div>
        <div class="form-group-konhakes">
            <label>Nm Alergi</label>
            <input type="text" class="form-control-konhakes" value="{{ $alergi->nm_alergi }}" disabled style="background:#eee;">
        </div>
        <div class="form-group-konhakes">
            <label>Kd Alergi Reff</label>
            <input type="text" name="kd_alergi_reff" class="form-control-konhakes"
                   value="{{ old('kd_alergi_reff', $alergi->kd_alergi_reff !== '(belum diset)' ? $alergi->kd_alergi_reff : '') }}"
                   placeholder="--Masukkan Reff--">
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn-save">Simpan</button>
            <a href="/pcare-alergi/index" class="btn btn-default btn-sm ms-2"
               style="background:#e7e7e7; border:1px solid #ccc; font-size:13px; padding:6px 16px;">Batal</a>
        </div>
    </form>
</div>
@endsection

