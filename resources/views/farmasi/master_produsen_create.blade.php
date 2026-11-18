@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', isset($produsen) ? 'Edit Produsen' : 'Tambah Produsen')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Area (Title and Breadcrumbs 1:1 KONHAKES Screenshot 3) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            {{ isset($produsen) ? 'Edit Produsen' : 'Tambah Produsen' }}
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/produsen/index" style="color: #777; text-decoration: none;">Produsen</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">{{ isset($produsen) ? 'Edit Produsen' : 'Tambah Produsen' }}</span>
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

    <!-- Form Container (1:1 KONHAKES 2-Column Layout Screenshot 3) -->
    <form action="{{ isset($produsen) ? url('/produsen/' . $produsen->id) : url('/produsen/store') }}" method="POST">
        @csrf
        @if(isset($produsen))
            @method('PUT')
        @endif

        <div class="row g-4 mb-3">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <!-- Kode Produsen -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kode Produsen</label>
                    <input type="text" name="kode_produsen" class="form-control" 
                        value="{{ old('kode_produsen', $kode_produsen ?? ($produsen->kode_produsen ?? '')) }}" 
                        readonly 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de; background-color: #eeeeee; color: #555;">
                </div>

                <!-- Nama Produsen *wajib diisi! -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">
                        Nama Produsen <span style="color: #dd4b39; font-style: italic; font-weight: normal; font-size: 11.5px;">*wajib diisi!</span>
                    </label>
                    <input type="text" name="nama_produsen" class="form-control" 
                        value="{{ old('nama_produsen', $produsen->nama_produsen ?? '') }}" 
                        required 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Alamat</label>
                    <input type="text" name="alamat" class="form-control" 
                        value="{{ old('alamat', $produsen->alamat ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <!-- Telp -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Telp</label>
                    <input type="text" name="telp" class="form-control" 
                        value="{{ old('telp', $produsen->telp ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Fax -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Fax</label>
                    <input type="text" name="fax" class="form-control" 
                        value="{{ old('fax', $produsen->fax ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Email</label>
                    <input type="text" name="email" class="form-control" 
                        value="{{ old('email', $produsen->email ?? '') }}" 
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
            </div>
        </div>

        <!-- Action Buttons (Simpan & Batal 1:1 KONHAKES Screenshot 3) -->
        <div class="d-flex align-items-center gap-1">
            <button type="submit" class="btn text-white" style="background-color: #00a65a; border-color: #008d4c; height: 34px; padding: 0 16px; font-size: 12.5px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center;">
                <i class="fas fa-save me-1"></i> Simpan
            </button>
            <a href="/produsen/index" class="btn text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 34px; padding: 0 16px; font-size: 12.5px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
