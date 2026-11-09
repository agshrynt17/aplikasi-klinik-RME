@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', isset($pelanggan) ? 'Edit Pelanggan' : 'Tambah Pelanggan')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES Screenshot 2) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            {{ isset($pelanggan) ? 'Edit Pelanggan' : 'Tambah Pelanggan' }}
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/pelanggan/index" style="color: #777; text-decoration: none;">Pelanggans</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">{{ isset($pelanggan) ? 'Edit Pelanggan' : 'Tambah Pelanggan' }}</span>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    <!-- Form Container (1:1 PROFASKES Screenshot 2) -->
    <form action="{{ isset($pelanggan) ? url('/pelanggan/' . $pelanggan->id) : url('/pelanggan/store') }}" method="POST">
        @csrf
        @if(isset($pelanggan))
            @method('PUT')
        @endif

        <!-- Kode Pelanggan -->
        <div class="mb-3">
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kode Pelanggan</label>
            <input type="text" name="kode_pelanggan" class="form-control" 
                value="{{ old('kode_pelanggan', $kode_pelanggan ?? ($pelanggan->kode_pelanggan ?? '')) }}" 
                readonly 
                style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de; background-color: #eeeeee; color: #555;">
        </div>

        <!-- Nama Pelanggan -->
        <div class="mb-3">
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" 
                value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan ?? '') }}" 
                required 
                placeholder="Nama Lengkap Pelanggan"
                style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
        </div>

        <!-- Tanggal Lahir -->
        <div class="mb-3">
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" 
                value="{{ old('tanggal_lahir', isset($pelanggan) && $pelanggan->tanggal_lahir ? date('Y-m-d', strtotime($pelanggan->tanggal_lahir)) : '') }}" 
                style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
        </div>

        <!-- Alamat -->
        <div class="mb-3">
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Alamat</label>
            <input type="text" name="alamat" class="form-control" 
                value="{{ old('alamat', $pelanggan->alamat ?? '') }}" 
                placeholder="Alamat Pelanggan"
                style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
        </div>

        <!-- 4 Kolom Wilayah: Kode Prop, Kode Kab, Kode Kec, Kode Desa -->
        <div class="row g-3 mb-3">
            <!-- Kode Prop -->
            <div class="col-md-3 col-sm-6">
                <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kode Prop</label>
                @php $curProp = old('kode_prop', $pelanggan->kode_prop ?? ''); @endphp
                <select name="kode_prop" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                    <option value="">--Pilih Propinsi--</option>
                    <option value="32" {{ $curProp == '32' || $curProp == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                    <option value="31" {{ $curProp == '31' || $curProp == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                    <option value="33" {{ $curProp == '33' || $curProp == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                    <option value="35" {{ $curProp == '35' || $curProp == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                    <option value="36" {{ $curProp == '36' || $curProp == 'Banten' ? 'selected' : '' }}>Banten</option>
                    <option value="34" {{ $curProp == '34' || $curProp == 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                </select>
            </div>

            <!-- Kode Kab -->
            <div class="col-md-3 col-sm-6">
                <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kode Kab</label>
                @php $curKab = old('kode_kab', $pelanggan->kode_kab ?? ''); @endphp
                <select name="kode_kab" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                    <option value="">--Pilih Kabupaten--</option>
                    <option value="3215" {{ $curKab == '3215' || $curKab == 'Karawang' ? 'selected' : '' }}>Kab. Karawang</option>
                    <option value="3216" {{ $curKab == '3216' || $curKab == 'Bekasi' ? 'selected' : '' }}>Kab. Bekasi</option>
                    <option value="3214" {{ $curKab == '3214' || $curKab == 'Purwakarta' ? 'selected' : '' }}>Kab. Purwakarta</option>
                    <option value="3213" {{ $curKab == '3213' || $curKab == 'Subang' ? 'selected' : '' }}>Kab. Subang</option>
                    <option value="3171" {{ $curKab == '3171' || $curKab == 'Jakarta Pusat' ? 'selected' : '' }}>Kota Jakarta Pusat</option>
                    <option value="3172" {{ $curKab == '3172' || $curKab == 'Jakarta Utara' ? 'selected' : '' }}>Kota Jakarta Utara</option>
                    <option value="3173" {{ $curKab == '3173' || $curKab == 'Jakarta Barat' ? 'selected' : '' }}>Kota Jakarta Barat</option>
                    <option value="3174" {{ $curKab == '3174' || $curKab == 'Jakarta Selatan' ? 'selected' : '' }}>Kota Jakarta Selatan</option>
                    <option value="3175" {{ $curKab == '3175' || $curKab == 'Jakarta Timur' ? 'selected' : '' }}>Kota Jakarta Timur</option>
                </select>
            </div>

            <!-- Kode Kec -->
            <div class="col-md-3 col-sm-6">
                <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kode Kec</label>
                @php $curKec = old('kode_kec', $pelanggan->kode_kec ?? ''); @endphp
                <select name="kode_kec" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                    <option value="">--Pilih Kecamatan--</option>
                    <option value="Kotabaru" {{ $curKec == 'Kotabaru' ? 'selected' : '' }}>Kotabaru</option>
                    <option value="Cikampek" {{ $curKec == 'Cikampek' ? 'selected' : '' }}>Cikampek</option>
                    <option value="Telagasari" {{ $curKec == 'Telagasari' ? 'selected' : '' }}>Telagasari</option>
                    <option value="Lemahabang" {{ $curKec == 'Lemahabang' ? 'selected' : '' }}>Lemahabang</option>
                    <option value="Klari" {{ $curKec == 'Klari' ? 'selected' : '' }}>Klari</option>
                    <option value="Karawang Barat" {{ $curKec == 'Karawang Barat' ? 'selected' : '' }}>Karawang Barat</option>
                    <option value="Karawang Timur" {{ $curKec == 'Karawang Timur' ? 'selected' : '' }}>Karawang Timur</option>
                </select>
            </div>

            <!-- Kode Desa -->
            <div class="col-md-3 col-sm-6">
                <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kode Desa</label>
                @php $curDesa = old('kode_desa', $pelanggan->kode_desa ?? ''); @endphp
                <select name="kode_desa" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                    <option value="">--Pilih Desa--</option>
                    <option value="Cikampek Utara" {{ $curDesa == 'Cikampek Utara' ? 'selected' : '' }}>Cikampek Utara</option>
                    <option value="Cikampek Barat" {{ $curDesa == 'Cikampek Barat' ? 'selected' : '' }}>Cikampek Barat</option>
                    <option value="Wancimekar" {{ $curDesa == 'Wancimekar' ? 'selected' : '' }}>Wancimekar</option>
                    <option value="Pucung" {{ $curDesa == 'Pucung' ? 'selected' : '' }}>Pucung</option>
                    <option value="Duren" {{ $curDesa == 'Duren' ? 'selected' : '' }}>Duren</option>
                    <option value="Pangulah Utara" {{ $curDesa == 'Pangulah Utara' ? 'selected' : '' }}>Pangulah Utara</option>
                    <option value="Pangulah Selatan" {{ $curDesa == 'Pangulah Selatan' ? 'selected' : '' }}>Pangulah Selatan</option>
                </select>
            </div>
        </div>

        <!-- Telp -->
        <div class="mb-3">
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Telp</label>
            <input type="text" name="telp" class="form-control" 
                value="{{ old('telp', $pelanggan->telp ?? ($pelanggan->no_telepon ?? '')) }}" 
                placeholder="Nomor Telepon / WhatsApp"
                style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Email</label>
            <input type="email" name="email" class="form-control" 
                value="{{ old('email', $pelanggan->email ?? '') }}" 
                placeholder="contoh@email.com"
                style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
        </div>

        <!-- Action Buttons (Save & Batal 1:1 PROFASKES Screenshot 2) -->
        <div class="d-flex align-items-center gap-1">
            <button type="submit" class="btn text-white" style="background-color: #00a65a; border-color: #008d4c; height: 34px; padding: 0 20px; font-size: 13px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center;">
                Save
            </button>
            <a href="/pelanggan/index" class="btn text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 34px; padding: 0 18px; font-size: 13px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
