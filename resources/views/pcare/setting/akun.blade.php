@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pcare-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
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
        color: #444;
        text-decoration: none;
    }
    .konhakes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .konhakes-summary-text {
        font-size: 13px;
        color: #333;
        margin-bottom: 8px;
    }
    .table-pcare-grid {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 15px;
    }
    .table-pcare-grid th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
    }
    .table-pcare-grid th.col-sort a {
        color: #3c8dbc !important;
        text-decoration: none;
    }
    .table-pcare-grid th.col-sort a:hover {
        text-decoration: underline;
    }
    .table-pcare-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-pcare-grid tbody tr:nth-of-type(odd) td {
        background-color: #ffffff;
    }
    .table-pcare-grid tbody tr:nth-of-type(even) td {
        background-color: #f9f9f9;
    }
    .table-pcare-grid tbody tr:hover td {
        background-color: #f5f5f5;
    }
    .btn-prof-ubah {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 11.5px;
        padding: 2px 8px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        text-decoration: none;
        cursor: pointer;
        line-height: 1.6;
    }
    .btn-prof-ubah:hover {
        background-color: #d68407;
        color: #ffffff !important;
    }
    .status-bridging-banner {
        background-color: #00c0ef;
        color: #ffffff;
        text-align: center;
        font-weight: 600;
        font-size: 13.5px;
        padding: 6px 12px;
        border-radius: 2px;
        margin-bottom: 8px;
    }
    .btn-prof-testing {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 16px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .btn-prof-testing:hover {
        background-color: #d68407;
        color: #ffffff !important;
    }
</style>

<div class="pcare-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Setting Bridging Pcare</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a>
            <span style="color: #ccc; margin: 0 2px;">&gt;</span>
            <span style="color: #777;">Setting Bridging Pcare</span>
        </div>
    </div>

    {{-- Alert sukses/error --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Summary --}}
    <div class="konhakes-summary-text">
        Menampilkan <b>1-{{ $total }}</b> dari <b>{{ $total }}</b> item
    </div>

    {{-- Tabel Setting --}}
    <div class="table-responsive" style="border: none;">
        <table class="table-pcare-grid">
            <thead>
                <tr>
                    <th style="width: 45px;">#</th>
                    <th class="col-sort" style="width: 220px;">
                        <a href="javascript:void(0)">Nama Setting <i class="fas fa-sort ms-1" style="font-size: 11px;"></i></a>
                    </th>
                    <th>
                        <a href="javascript:void(0)" style="color:#3c8dbc; text-decoration:none;">Nilai Setting</a>
                    </th>
                    <th>
                        <a href="javascript:void(0)" style="color:#3c8dbc; text-decoration:none;">Deskripsi</a>
                    </th>
                    <th style="width: 80px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $i => $s)
                <tr id="row-{{ $s->nama_setting }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $s->nama_setting }}</td>
                    <td id="val-{{ $s->nama_setting }}">{{ $s->display_value }}</td>
                    <td>{{ $s->deskripsi ?? '' }}</td>
                    <td style="text-align: right;">
                        @if($s->can_edit)
                        <button type="button" class="btn-prof-ubah"
                                onclick="bukaModalUbah('{{ $s->nama_setting }}', '{{ addslashes($s->nilai_setting) }}', {{ $s->is_secret ? 'true' : 'false' }})">
                            <i class="fas fa-pencil-alt"></i> Ubah
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Status Bridging Banner --}}
    <div class="status-bridging-banner" id="bannerStatusBridging">
        Status Bridging : Aktif
    </div>
    <div class="text-center" style="font-size: 12.5px; color: #333; margin-bottom: 8px;">
        Silakan klik tombol dibawah ini untuk menguji status Akses Akun Bridging Pcare!
    </div>
    <div class="text-center mb-3">
        <button type="button" class="btn-prof-testing" id="btnTestingPcare" onclick="testingAksesPcare()">
            <i class="fas fa-sync-alt" id="iconTestingPcare"></i> Testing Akses Bridging Pcare
        </button>
    </div>
</div>

{{-- MODAL UBAH SETTING --}}
<div class="modal fade" id="modalUbahSetting" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 px-3" style="background-color: #f39c12;">
                <h5 class="modal-title fs-6 fw-semibold text-white">
                    <i class="fas fa-edit me-1"></i> Ubah Nilai Setting
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <label class="form-label small fw-semibold" id="labelSettingName">Nilai baru</label>
                    <div class="input-group">
                        <input type="text" id="inputSettingValue" class="form-control" required>
                        <button class="btn btn-outline-secondary" type="button" id="btnTogglePass" style="display:none;" onclick="togglePass()">
                            <i class="fas fa-eye" id="iconEye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm text-white px-4" id="btnSimpanSetting"
                        style="background-color: #f39c12; border-color: #e08e0b;"
                        onclick="simpanSetting()">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HASIL TEST --}}
<div class="modal fade" id="modalHasilTest" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white py-2 px-3" id="modalTestHeader" style="background-color: #00a65a;">
                <h5 class="modal-title fs-6 fw-semibold text-white" id="modalTestTitle">
                    <i class="fas fa-check-circle me-1"></i> Status Akses PCare: AKTIF
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex align-items-center mb-3 p-2 rounded" id="modalTestBadge"
                     style="background-color: #e8f8f5; border: 1px solid #a3e4d7;">
                    <i class="fas fa-shield-alt text-success fs-1 me-3" id="modalTestIcon"></i>
                    <div>
                        <div class="fw-bold text-success" style="font-size: 14px;" id="modalTestHead">Koneksi Bridging PCare Berhasil!</div>
                        <div class="small text-muted" id="modalTestSub">Aplikasi terhubung ke server BPJS Kesehatan.</div>
                    </div>
                </div>
                <pre id="modalTestBody" class="mb-0 small p-2 rounded" style="white-space:pre-wrap; background:#f8f9fa; border:1px solid #e9ecef; font-size:12px;"></pre>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-sm px-4 text-white" data-bs-dismiss="modal"
                        style="background-color: #00a65a; border-color: #008d4c;">
                    <i class="fas fa-check me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';
let currentSettingName = '';

function bukaModalUbah(nama, nilai, isSecret) {
    currentSettingName = nama;
    document.getElementById('labelSettingName').textContent = 'Nilai baru untuk: ' + nama;
    const inp = document.getElementById('inputSettingValue');
    inp.value = nilai;
    inp.type  = isSecret ? 'password' : 'text';

    const btnToggle = document.getElementById('btnTogglePass');
    btnToggle.style.display = isSecret ? '' : 'none';
    document.getElementById('iconEye').className = 'fas fa-eye';

    new bootstrap.Modal(document.getElementById('modalUbahSetting')).show();
}

function togglePass() {
    const inp  = document.getElementById('inputSettingValue');
    const icon = document.getElementById('iconEye');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function simpanSetting() {
    const nilai = document.getElementById('inputSettingValue').value.trim();
    if (!nilai) { alert('Nilai setting tidak boleh kosong.'); return; }

    const btn = document.getElementById('btnSimpanSetting');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan…';

    $.ajax({
        url: '/pcare/setting/akun/update',
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        data: { nama_setting: currentSettingName, nilai_setting: nilai },
        success: function(res) {
            if (res.success) {
                document.getElementById('val-' + currentSettingName).textContent = res.display || nilai;
                bootstrap.Modal.getInstance(document.getElementById('modalUbahSetting')).hide();
                toastr.success(res.message || 'Setting berhasil disimpan.');
            } else {
                toastr.error(res.message || 'Gagal menyimpan.');
            }
        },
        error: function() { toastr.error('Terjadi kesalahan jaringan.'); },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Perubahan';
        }
    });
}

function testingAksesPcare() {
    const btn  = $('#btnTestingPcare');
    const orig = btn.html();
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menguji Akses PCare...');

    $.ajax({
        url: '/pcare/setting/akun/test',
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        data: {},
        success: function(res) {
            btn.prop('disabled', false).html(orig);
            if (res.success) {
                $('#bannerStatusBridging').text('Status Bridging : Aktif').css('background-color', '#00c0ef');
                $('#modalTestHeader').css('background-color', '#00a65a');
                $('#modalTestTitle').html('<i class="fas fa-check-circle me-1"></i> Status Akses PCare: AKTIF');
                $('#modalTestBadge').css({'background-color':'#e8f8f5','border-color':'#a3e4d7'});
                $('#modalTestIcon').removeClass().addClass('fas fa-shield-alt text-success fs-1 me-3');
                $('#modalTestHead').removeClass().addClass('fw-bold text-success').css('font-size','14px').text('Koneksi Bridging PCare Berhasil!');
                $('#modalTestSub').text('Aplikasi terhubung ke server BPJS Kesehatan.');
            } else {
                $('#bannerStatusBridging').text('Status Bridging : Tidak Aktif').css('background-color', '#dd4b39');
                $('#modalTestHeader').css('background-color', '#dd4b39');
                $('#modalTestTitle').html('<i class="fas fa-times-circle me-1"></i> Status Akses PCare: GAGAL');
                $('#modalTestBadge').css({'background-color':'#fdecea','border-color':'#f5c6cb'});
                $('#modalTestIcon').removeClass().addClass('fas fa-exclamation-triangle text-danger fs-1 me-3');
                $('#modalTestHead').removeClass().addClass('fw-bold text-danger').css('font-size','14px').text('Koneksi PCare Gagal!');
                $('#modalTestSub').text('Periksa konfigurasi username, password, dan user_key.');
            }
            $('#modalTestBody').text(res.message || '');
            new bootstrap.Modal(document.getElementById('modalHasilTest')).show();
        },
        error: function() {
            btn.prop('disabled', false).html(orig);
            $('#bannerStatusBridging').text('Status Bridging : Aktif').css('background-color', '#00c0ef');
            new bootstrap.Modal(document.getElementById('modalHasilTest')).show();
        }
    });
}
</script>
@endsection
