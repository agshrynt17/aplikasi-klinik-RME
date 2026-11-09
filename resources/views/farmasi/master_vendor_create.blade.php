@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', isset($vendor) ? 'Edit Vendor' : 'Tambah Vendor')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Area (Title and Breadcrumbs 1:1 PROFASKES Screenshot 2) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            {{ isset($vendor) ? 'Edit Vendor' : 'Tambah Vendor' }}
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/vendor/index" style="color: #777; text-decoration: none;">Vendor</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">{{ isset($vendor) ? 'Edit Vendor' : 'Tambah Vendor' }}</span>
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

    <!-- Form Container (1:1 PROFASKES 4-Column Layout Screenshot 2) -->
    <form action="{{ isset($vendor) ? url('/vendor/' . $vendor->id) : url('/vendor/store') }}" method="POST">
        @csrf
        @if(isset($vendor))
            @method('PUT')
        @endif

        <div class="row g-4 mb-3">
            <!-- Kolom 1 -->
            <div class="col-md-3 col-sm-6">
                <!-- Kode Vendor -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kode Vendor</label>
                    <input type="text" name="kode_vendor" class="form-control" 
                        value="{{ old('kode_vendor', $kode_vendor ?? ($vendor->kode_vendor ?? '')) }}" 
                        readonly 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de; background-color: #eeeeee; color: #555;">
                </div>

                <!-- Nama Vendor *wajib diisi! -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">
                        Nama Vendor <span style="color: #dd4b39; font-style: italic; font-weight: normal; font-size: 11.5px;">*wajib diisi!</span>
                    </label>
                    <input type="text" name="nama_vendor" class="form-control" 
                        value="{{ old('nama_vendor', $vendor->nama_vendor ?? '') }}" 
                        required 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Join Date -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Join Date</label>
                    <input type="date" name="join_date" class="form-control" 
                        value="{{ old('join_date', isset($vendor) && $vendor->join_date ? date('Y-m-d', strtotime($vendor->join_date)) : '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
            </div>

            <!-- Kolom 2 -->
            <div class="col-md-3 col-sm-6">
                <!-- Alamat -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Alamat</label>
                    <input type="text" name="alamat" class="form-control" 
                        value="{{ old('alamat', $vendor->alamat ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Telp -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Telp</label>
                    <input type="text" name="telp" class="form-control" 
                        value="{{ old('telp', $vendor->telp ?? ($vendor->no_telepon ?? '')) }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Fax -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Fax</label>
                    <input type="text" name="fax" class="form-control" 
                        value="{{ old('fax', $vendor->fax ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
            </div>

            <!-- Kolom 3 -->
            <div class="col-md-3 col-sm-6">
                <!-- Email -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Email</label>
                    <input type="email" name="email" class="form-control" 
                        value="{{ old('email', $vendor->email ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Nomor SPK -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Nomor SPK</label>
                    <input type="text" name="nomor_spk" class="form-control" 
                        value="{{ old('nomor_spk', $vendor->nomor_spk ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Expired SPK -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Expired SPK</label>
                    <input type="date" name="expired_spk" class="form-control" 
                        value="{{ old('expired_spk', isset($vendor) && $vendor->expired_spk ? date('Y-m-d', strtotime($vendor->expired_spk)) : '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
            </div>

            <!-- Kolom 4 -->
            <div class="col-md-3 col-sm-6">
                <!-- Nama PIC -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Nama PIC</label>
                    <input type="text" name="nama_pic" class="form-control" 
                        value="{{ old('nama_pic', $vendor->nama_pic ?? ($vendor->kontak_person ?? '')) }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Telp PIC -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Telp PIC</label>
                    <input type="text" name="telp_pic" class="form-control" 
                        value="{{ old('telp_pic', $vendor->telp_pic ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Email PIC -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Email PIC</label>
                    <input type="email" name="email_pic" class="form-control" 
                        value="{{ old('email_pic', $vendor->email_pic ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
            </div>
        </div>

        <!-- Action Buttons (Simpan & Batal 1:1 PROFASKES Screenshot 2) -->
        <div class="d-flex align-items-center gap-1">
            <button type="submit" class="btn text-white" style="background-color: #00a65a; border-color: #008d4c; height: 34px; padding: 0 16px; font-size: 12.5px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center;">
                <i class="fas fa-save me-1"></i> Simpan
            </button>
            <a href="/vendor/index" class="btn text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 34px; padding: 0 16px; font-size: 12.5px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
