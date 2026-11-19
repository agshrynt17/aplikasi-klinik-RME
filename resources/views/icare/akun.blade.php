@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .icare-user-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
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

    /* Info Bar */
    .meta-info-line {
        font-size: 12px;
        color: #555;
        margin-bottom: 6px;
    }

    /* Table */
    .table-icare-user {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 12px;
    }
    .table-icare-user th {
        background-color: #fff;
        border: 1px solid #d2d6de;
        padding: 8px 8px;
        font-weight: 700;
        color: #337ab7;
        font-size: 12px;
        text-align: left;
    }
    .table-icare-user td {
        border: 1px solid #e4e7ea;
        padding: 6px 8px;
        vertical-align: middle;
        color: #333;
        font-size: 12px;
    }
    .table-icare-user tbody tr:hover {
        background-color: #f5f5f5;
    }

    .btn-ubah-orange {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        text-decoration: none;
    }
    .btn-ubah-orange:hover {
        background-color: #d68407;
        color: #fff !important;
    }
    .text-italic-gray {
        color: #777;
        font-style: italic;
    }
</style>

<div class="icare-user-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Setting Akun User Bridging I-Care</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/icare-user/index">Setting Akun User Bridging I-Care</a>
        </div>
    </div>

    {{-- Alert sukses / error --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Info Count --}}
    <div class="meta-info-line">
        Menampilkan 1-{{ $total }} dari {{ $total }} item.
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-icare-user">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center; color: #333;">#</th>
                    <th style="width: 170px;">Kode NIK</th>
                    <th style="width: 250px;">Nama Karyawan</th>
                    <th style="width: 180px;">User Icare</th>
                    <th style="width: 160px;">Password Icare</th>
                    <th>Diubah Pada</th>
                    <th style="width: 80px; text-align: center;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $u)
                @php
                    $diubah = $u->diubah_pada ? \Carbon\Carbon::parse($u->diubah_pada)->format('d-m-Y H:i:s') : null;
                @endphp
                <tr id="row-user-{{ $u->id }}">
                    <td style="text-align: center; color: #555;">{{ $index + 1 }}</td>
                    <td>{{ $u->kode_nik ?: '-' }}</td>
                    <td style="font-weight: 500;">{{ $u->nama_karyawan }}</td>
                    <td>
                        @if($u->user_icare)
                            <span>{{ $u->user_icare }}</span>
                        @else
                            <span class="text-italic-gray">--Belum diatur--</span>
                        @endif
                    </td>
                    <td>
                        @if($u->password_icare)
                            <span>******</span>
                        @else
                            <span class="text-italic-gray">--Belum diatur--</span>
                        @endif
                    </td>
                    <td>
                        @if($diubah)
                            <span>{{ $diubah }}</span>
                        @else
                            <span class="text-italic-gray">--Belum diatur--</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn-ubah-orange btnTriggerUbah"
                                data-id="{{ $u->id }}"
                                data-nik="{{ $u->kode_nik }}"
                                data-nama="{{ $u->nama_karyawan }}"
                                data-user="{{ $u->user_icare }}">
                            <i class="fas fa-edit"></i> Ubah
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Ubah Akun i-Care --}}
<div class="modal fade" id="modalUbahIcare" tabindex="-1" aria-labelledby="modalUbahIcareLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #f39c12; color: #fff;">
                <h6 class="modal-title fw-bold" id="modalUbahIcareLabel" style="font-size: 14px;">
                    <i class="fas fa-user-edit me-1"></i> Ubah Akun User Bridging i-Care
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUbahIcare" method="POST" action="">
                @csrf
                <div class="modal-body p-3" style="font-size: 13px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Kode NIK</label>
                        <input type="text" id="inputNik" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Nama Karyawan / Dokter</label>
                        <input type="text" id="inputNama" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">User i-Care</label>
                        <input type="text" name="user_icare" id="inputUserIcare" class="form-control form-control-sm" placeholder="Masukkan Username i-Care BPJS">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Password i-Care</label>
                        <input type="password" name="password_icare" id="inputPassIcare" class="form-control form-control-sm" placeholder="Masukkan Password i-Care BPJS">
                        <small class="text-muted" style="font-size: 11px;">Kosongkan jika tidak ingin mengubah password.</small>
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
    const modalEl = document.getElementById('modalUbahIcare');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formUbahIcare');
    const inputNik = document.getElementById('inputNik');
    const inputNama = document.getElementById('inputNama');
    const inputUserIcare = document.getElementById('inputUserIcare');
    const inputPassIcare = document.getElementById('inputPassIcare');

    document.querySelectorAll('.btnTriggerUbah').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nik = this.getAttribute('data-nik');
            const nama = this.getAttribute('data-nama');
            const user = this.getAttribute('data-user');

            form.action = '/icare-user/' + id + '/update';
            inputNik.value = nik || '-';
            inputNama.value = nama || '';
            inputUserIcare.value = user || '';
            inputPassIcare.value = '';

            modal.show();
        });
    });
});
</script>
@endsection
