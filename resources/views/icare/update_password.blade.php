@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .icare-view-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
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
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }

    .btn-update-blue {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 16px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 12px;
    }
    .btn-update-blue:hover {
        background-color: #286090;
    }

    /* Definition Table */
    .table-detail-icare {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .table-detail-icare tr:nth-child(odd) {
        background-color: #fff;
    }
    .table-detail-icare tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .table-detail-icare td {
        padding: 8px 12px;
        border-top: 1px solid #f4f4f4;
        vertical-align: middle;
    }
    .table-detail-icare td.label-col {
        width: 280px;
        font-weight: 600;
        color: #333;
    }
    .table-detail-icare td.value-col {
        color: #333;
    }
</style>

<div class="icare-view-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Akun i-Care Dokter: {{ $user->nama_karyawan ?? 'dr. Fahmi Rizal' }}</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/icare-user/index">User Icares</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/icare-user/view">Akun i-Care Dokter: {{ $user->nama_karyawan ?? 'dr. Fahmi Rizal' }}</a>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small mb-3" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Update Button --}}
    <div>
        <button type="button" class="btn-update-blue" id="btnBukaModalUpdate">
            Update
        </button>
    </div>

    {{-- Table Attributes Detail --}}
    <div style="background:#fff; border: 1px solid #e4e7ea; border-radius: 2px;">
        <table class="table-detail-icare">
            <tbody>
                <tr>
                    <td class="label-col">Kode Nik</td>
                    <td class="value-col">{{ $user->kode_nik ?: '3215130508880001' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Nama Dokter</td>
                    <td class="value-col">{{ $user->nama_karyawan ?? 'dr. Fahmi Rizal' }}</td>
                </tr>
                <tr>
                    <td class="label-col">User i-Care</td>
                    <td class="value-col">{{ $user->user_icare ?: 'fahmirizaldr' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Password i-Care</td>
                    <td class="value-col">****</td>
                </tr>
                <tr>
                    <td class="label-col">Update At</td>
                    <td class="value-col">
                        {{ $user->diubah_pada ? \Carbon\Carbon::parse($user->diubah_pada)->format('Y-m-d H:i:s') : '2026-09-24 07:59:19' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-col">status</td>
                    <td class="value-col">Aktif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Update Akun / Password i-Care --}}
<div class="modal fade" id="modalUpdateIcare" tabindex="-1" aria-labelledby="modalUpdateIcareLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #337ab7; color: #fff;">
                <h6 class="modal-title fw-bold" id="modalUpdateIcareLabel" style="font-size: 14px;">
                    <i class="fas fa-lock me-1"></i> Update Akun & Password i-Care
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/icare-user/update-password">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id ?? 1 }}">
                <div class="modal-body p-3" style="font-size: 13px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Nama Dokter</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $user->nama_karyawan ?? 'dr. Fahmi Rizal' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Kode NIK</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $user->kode_nik ?: '3215130508880001' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">User i-Care</label>
                        <input type="text" name="user_icare" class="form-control form-control-sm"
                               value="{{ $user->user_icare ?: 'fahmirizaldr' }}" placeholder="User i-Care BPJS">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Password Baru i-Care</label>
                        <input type="password" name="password_icare" class="form-control form-control-sm"
                               placeholder="Masukkan password baru">
                    </div>
                </div>
                <div class="modal-footer py-2 px-3" style="background: #f9f9f9;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px;">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px; font-weight: 500;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnBukaModalUpdate');
    const modalEl = document.getElementById('modalUpdateIcare');
    if (btn && modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        btn.addEventListener('click', function () {
            modal.show();
        });
    }
});
</script>
@endsection
