@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-normal mb-0" style="color: #333; font-size: 24px;">Create User</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 12px; background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/user/admin/index" class="text-decoration-none text-muted">Users</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Create User</a></li>
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
            <form action="/user/admin/store" method="POST" id="formCreateUser">
                @csrf

                <!-- Row 1: Karyawan, Full Name, Email -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="pegawai_id" class="form-label text-dark fw-bold" style="font-size: 13px;">Karyawan</label>
                        <select name="pegawai_id" id="pegawai_id" class="form-select form-select-sm" style="font-size: 13px;">
                            <option value="">--Pilih Karyawan--</option>
                            @foreach($pegawais as $pegawai)
                                <option value="{{ $pegawai->id }}" 
                                        data-name="{{ $pegawai->nama_pegawai }}"
                                        data-nik="{{ $pegawai->nik }}"
                                        data-email="{{ $pegawai->email }}"
                                        {{ old('pegawai_id') == $pegawai->id ? 'selected' : '' }}>
                                    {{ $pegawai->nama_pegawai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label text-dark fw-bold" style="font-size: 13px;">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm" style="font-size: 13px;" value="{{ old('name') }}" placeholder="Nama Lengkap Karyawan" required>
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label text-dark fw-bold" style="font-size: 13px;">Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm" style="font-size: 13px;" value="{{ old('email') }}" required>
                    </div>
                </div>

                <!-- Row 2: Username, Password -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="username" class="form-label text-dark fw-bold" style="font-size: 13px;">Username</label>
                        <input type="text" name="username" id="username" class="form-control form-control-sm" style="font-size: 13px;" value="{{ old('username') }}" required>
                    </div>
                    <div class="col-md-8">
                        <label for="password" class="form-label text-dark fw-bold" style="font-size: 13px;">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-control-sm" style="font-size: 13px;" required>
                    </div>
                </div>

                <!-- Row 3: Status & Hak Akses (Role) -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="is_active" class="form-label text-dark fw-bold" style="font-size: 13px;">Status</label>
                        <select name="is_active" id="is_active" class="form-select form-select-sm" style="font-size: 13px;">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="role_id" class="form-label text-dark fw-bold" style="font-size: 13px;">Hak Akses (Role)</label>
                        <select name="role_id" id="role_id" class="form-select form-select-sm" style="font-size: 13px;">
                            <option value="">-- Pilih Hak Akses --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Buttons: Create, Tutup -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn text-white fw-bold px-3 py-1" style="background-color: #00a65a; border-color: #00a65a; border-radius: 3px; font-size: 13px;">
                        Create
                    </button>
                    <a href="/user/admin/index" class="btn text-white fw-bold px-3 py-1" style="background-color: #f39c12; border-color: #f39c12; border-radius: 3px; font-size: 13px;">
                        Tutup
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    function applySelectedPegawai() {
        let selected = $('#pegawai_id').find(':selected');
        let name = selected.data('name') || '';
        let email = selected.data('email') || '';
        
        if (name && !$('#name').val()) {
            $('#name').val(name);
        }

        if (name) {
            let cleanUsername = name.replace(/^(dr\.|drg\.|Apt\.|Ners\.|Prof\.)\s*/i, '')
                                    .replace(/[,.].*$/, '')
                                    .trim()
                                    .toLowerCase()
                                    .replace(/\s+/g, '');
            if (!$('#username').val() || $('#username').val() === 'admin') {
                $('#username').val(cleanUsername);
            }

            if (email) {
                $('#email').val(email);
            } else if (!$('#email').val()) {
                $('#email').val(cleanUsername + '@gmail.com');
            }
        }
    }

    // When employee selection changes
    $('#pegawai_id').on('change', function () {
        let selected = $(this).find(':selected');
        let name = selected.data('name') || '';
        let email = selected.data('email') || '';
        
        if (name) {
            $('#name').val(name);
            let cleanUsername = name.replace(/^(dr\.|drg\.|Apt\.|Ners\.|Prof\.)\s*/i, '')
                                    .replace(/[,.].*$/, '')
                                    .trim()
                                    .toLowerCase()
                                    .replace(/\s+/g, '');
            if (!$('#username').val() || $('#username').val() === 'admin') {
                $('#username').val(cleanUsername);
            }

            if (email) {
                $('#email').val(email);
            } else if (!$('#email').val()) {
                $('#email').val(cleanUsername + '@gmail.com');
            }
        }
    });

    // Auto trigger on page load if an employee is already selected (e.g. after validation error redirect)
    if ($('#pegawai_id').val()) {
        applySelectedPegawai();
    }
});
</script>
@endsection
