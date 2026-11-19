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
        <h3 class="konhakes-title">Update Master Tindakan PCare</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>&nbsp;»&nbsp;
            <a href="/pcare-tindakan/index">Bridging PCare - Setting Master Tindakan</a>&nbsp;»&nbsp;
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

    <form action="/pcare-tindakan/{{ $tindakan->id }}/update" method="POST">
        @csrf
        <div class="form-group-konhakes">
            <label>TKP</label>
            <select name="kd_tkp" class="form-control-konhakes">
                @foreach($tkps as $tkp)
                    <option value="{{ $tkp->kd_tkp }}" {{ old('kd_tkp', $tindakan->kd_tkp) == $tkp->kd_tkp ? 'selected' : '' }}>
                        {{ $tkp->nm_tkp }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group-konhakes">
            <label>Kd Tindakan</label>
            <input type="text" name="kd_tindakan" class="form-control-konhakes" value="{{ old('kd_tindakan', $tindakan->kd_tindakan) }}" required>
        </div>
        <div class="form-group-konhakes">
            <label>Nm Tindakan</label>
            <input type="text" name="nm_tindakan" class="form-control-konhakes" value="{{ old('nm_tindakan', $tindakan->nm_tindakan) }}" required>
        </div>
        <div class="form-group-konhakes" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label>Max Tarif</label>
                <input type="number" name="max_tarif" class="form-control-konhakes" value="{{ old('max_tarif', $tindakan->max_tarif) }}" min="0">
            </div>
            <div style="flex:1;">
                <label>With Value</label>
                <input type="number" name="with_value" class="form-control-konhakes" value="{{ old('with_value', $tindakan->with_value) }}" min="0">
            </div>
        </div>
        <div class="form-group-konhakes">
            <label>Kd Tindakan Reff (Kode Lokal Klinik)</label>
            <input type="text" name="kd_tindakan_reff" class="form-control-konhakes"
                   value="{{ old('kd_tindakan_reff', $tindakan->kd_tindakan_reff == '--Belum diatur--' ? '' : $tindakan->kd_tindakan_reff) }}"
                   placeholder="--Belum diatur--">
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn-save">Simpan</button>
            <a href="/pcare-tindakan/index" class="btn btn-default btn-sm ms-2"
               style="background:#e7e7e7; border:1px solid #ccc; font-size:13px; padding:6px 16px;">Batal</a>
        </div>
    </form>
</div>
@endsection

