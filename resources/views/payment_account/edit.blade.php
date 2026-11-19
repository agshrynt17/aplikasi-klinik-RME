@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-normal mb-0" style="color: #333; font-size: 24px;">Update Payment Account</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 12px; background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/payment-account/index" class="text-decoration-none text-muted">Payment Accounts</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Update Payment Account</a></li>
            </ol>
        </nav>
    </div>

    <!-- Alert / Errors -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Card with Cyan Top Border -->
    <div class="card shadow-sm border-0 mb-4" style="border-top: 3px solid #00c0ef !important; border-radius: 3px; background-color: #ffffff;">
        <div class="card-body p-4">
            <form action="/payment-account/update/{{ $item->id }}" method="POST" id="formEditPaymentAccount">
                @csrf

                <!-- Nama Channel / Bank -->
                <div class="mb-3">
                    <label for="nama_akun" class="form-label text-dark fw-bold" style="font-size: 13px;">Nama Channel / Bank</label>
                    <input type="text" name="nama_akun" id="nama_akun" class="form-control form-control-sm" style="font-size: 13px;" value="{{ old('nama_akun', $item->nama_akun) }}" required>
                </div>

                <!-- Nama Pemilik -->
                <div class="mb-3">
                    <label for="atas_nama" class="form-label text-dark fw-bold" style="font-size: 13px;">Nama Pemilik</label>
                    <input type="text" name="atas_nama" id="atas_nama" class="form-control form-control-sm" style="font-size: 13px;" value="{{ old('atas_nama', $item->atas_nama) }}">
                </div>

                <!-- No Akun -->
                <div class="mb-3">
                    <label for="no_rekening" class="form-label text-dark fw-bold" style="font-size: 13px;">No Akun</label>
                    <input type="text" name="no_rekening" id="no_rekening" class="form-control form-control-sm" style="font-size: 13px;" value="{{ old('no_rekening', $item->no_rekening) }}">
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="is_active" class="form-label text-dark fw-bold" style="font-size: 13px;">Status</label>
                    <select name="is_active" id="is_active" class="form-select form-select-sm" style="font-size: 13px;">
                        <option value="1" {{ old('is_active', $item->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $item->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Action Buttons: Save, Tutup -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn text-white fw-bold px-3 py-1" style="background-color: #00a65a; border-color: #00a65a; border-radius: 3px; font-size: 13px;">
                        Save
                    </button>
                    <a href="/payment-account/index" class="btn text-white fw-bold px-3 py-1" style="background-color: #f39c12; border-color: #f39c12; border-radius: 3px; font-size: 13px;">
                        Tutup
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
