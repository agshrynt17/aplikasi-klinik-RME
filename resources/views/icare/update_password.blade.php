@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-key text-primary me-2"></i> Update Password i-Care</h1>
</div>

<div class="alert alert-info py-2 mb-4 shadow-sm">
    <i class="fas fa-info-circle me-2"></i> Gunakan halaman ini untuk memperbarui password akun i-Care BPJS Kesehatan.
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card-custom shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-lock me-2"></i> Update Password i-Care</h5>
            </div>
            <div class="card-body p-4 bg-light">
                @if(session('success'))
                    <div class="alert alert-success py-2">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="/icare/update-password">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_lama') is-invalid @enderror"
                               name="password_lama" placeholder="Masukkan password lama">
                        @error('password_lama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_baru') is-invalid @enderror"
                               name="password_baru" placeholder="Masukkan password baru (min. 6 karakter)">
                        @error('password_baru')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"
                               name="password_baru_confirmation" placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

