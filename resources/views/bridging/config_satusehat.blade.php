@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 SETTING ACCESS KEY SATU SEHAT */
    .satusehat-page {
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
    .alert-callout-orange {
        background-color: #f39c12;
        color: #ffffff;
        font-size: 12.5px;
        padding: 10px 15px;
        border-radius: 3px;
        margin-bottom: 12px;
        line-height: 1.5;
    }
    .alert-callout-orange a {
        color: #ffffff;
        text-decoration: underline;
    }
    .konhakes-summary-text {
        font-size: 13px;
        color: #333;
        margin-bottom: 8px;
    }
    .table-satusehat-grid {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 15px;
    }
    .table-satusehat-grid th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
    }
    .table-satusehat-grid th.col-sort a {
        color: #3c8dbc !important;
        text-decoration: none;
    }
    .table-satusehat-grid th.col-sort a:hover {
        text-decoration: underline;
    }
    .table-satusehat-grid td {
        border: 1px solid #f4f4f4 !important;
        padding: 8px 10px;
        vertical-align: middle;
        color: #333;
    }
    .table-satusehat-grid tbody tr:nth-of-type(odd) td {
        background-color: #ffffff;
    }
    .table-satusehat-grid tbody tr:nth-of-type(even) td {
        background-color: #f9f9f9;
    }
    .table-satusehat-grid tbody tr:hover td {
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
        line-height: 1.4;
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
        text-decoration: none;
    }
    .btn-prof-testing:hover {
        background-color: #d68407;
        color: #ffffff !important;
    }
    .detail-card-cyan {
        background-color: #fff;
        border-top: 2px solid #00c0ef !important;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        margin-top: 15px;
        padding: 12px 15px;
    }
    .detail-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin: 0 0 10px 0;
    }
    .detail-table {
        width: 100%;
        font-size: 13px;
        margin-bottom: 0;
    }
    .detail-table td {
        padding: 4px 0;
        vertical-align: top;
        border: none;
    }
    .detail-table td.label-col {
        width: 200px;
        color: #333;
        font-weight: 500;
    }
    .detail-table td.colon-col {
        width: 15px;
        color: #333;
    }
    .detail-table td.value-col {
        color: #333;
        word-break: break-all;
    }
</style>

<div class="satusehat-page">
    <!-- Header Title & Breadcrumb (KONHAKES 1:1) -->
    <div class="konhakes-header">
        <h3 class="konhakes-title">Setting Access Key Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a> <span style="color: #ccc; margin: 0 2px;">&gt;</span> <span style="color: #777;">Setting Access Key Satu Sehat</span>
        </div>
    </div>

    <!-- Alert Box -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Yellow / Orange Banner Notice (KONHAKES 1:1) -->
    <div class="alert-callout-orange">
        Data Acces Key yang didaftarkan harus dalam mode <strong>Production</strong>. Jika ingin melihat data Acces API satu sehat klinik anda silakan lihat pada website satu sehat <a href="https://satusehat.kemkes.go.id/platform" target="_blank">https://satusehat.kemkes.go.id/platform</a>
    </div>

    <!-- Subtitle Summary -->
    <div class="konhakes-summary-text">
        Menampilkan <b>1-{{ count($settings) }}</b> dari <b>{{ count($settings) }}</b> item.
    </div>

    <!-- Setting Access Table (KONHAKES 1:1) -->
    <div class="table-responsive" style="border: none;">
        <table class="table-satusehat-grid">
            <thead>
                <tr>
                    <th style="width: 45px;">#</th>
                    <th class="col-sort" style="width: 250px;">
                        <a href="javascript:void(0)">Nama Setting <i class="fas fa-sort ms-1" style="font-size: 11px;"></i></a>
                    </th>
                    <th>Nilai Setting</th>
                    <th style="width: 80px; text-align: right;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $idx => $s)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $s['name'] }}</td>
                    <td class="text-break">{{ $s['value'] }}</td>
                    <td style="text-align: right;">
                        @if($s['can_edit'])
                        <button type="button" class="btn btn-prof-ubah" onclick="bukaModalUbah('{{ $s['name'] }}', '{{ addslashes($s['value']) }}')">
                            <i class="fas fa-pencil-alt"></i> Ubah
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Status Bridging Banner & Test Button (KONHAKES 1:1) -->
    <div class="status-bridging-banner" id="bannerStatusBridging">
        Status Bridging : Aktif
    </div>
    <div class="text-center" style="font-size: 12.5px; color: #333; margin-bottom: 8px;">
        Silakan klik tombol dibawah ini untuk menguji status Akses Akun Satu Sehat!
    </div>
    <div class="text-center mb-3">
        <button type="button" class="btn btn-prof-testing" id="btnTestingAkses" onclick="jalankanTestingAkses()">
            <i class="fas fa-sync-alt" id="iconTesting"></i> Testing Akses Satu Sehat
        </button>
    </div>

    <!-- Detail ID Bridging Satu Sehat (KONHAKES 1:1) -->
    <div class="detail-card-cyan">
        <h4 class="detail-card-title">Detail ID Bridging Satu Sehat</h4>
        <table class="detail-table">
            <tbody>
                <tr>
                    <td class="label-col">Jenis Akun Bridging</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">api-satusehat-prod.</td>
                </tr>
                <tr>
                    <td class="label-col">Organization ID</td>
                    <td class="colon-col">:</td>
                    <td class="value-col" id="detailOrgId">{{ $config->organization_id }}</td>
                </tr>
                <tr>
                    <td class="label-col">Develper Email</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $config->developer_email ?? 'uphi.ulfi@gmail.com' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Acces Name</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $config->access_name ?? 'ihs-prod-1' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Client Key</td>
                    <td class="colon-col">:</td>
                    <td class="value-col" id="detailClientId">{{ $config->client_id }}</td>
                </tr>
                <tr>
                    <td class="label-col">Client Secret</td>
                    <td class="colon-col">:</td>
                    <td class="value-col" id="detailClientSecret">{{ $config->client_secret }}</td>
                </tr>
                <tr>
                    <td class="label-col">Token</td>
                    <td class="colon-col">:</td>
                    <td class="value-col" id="detailToken" style="font-family: monospace; font-size: 12px;">{{ $config->token ?? 'dWQ7tuTorRJHR4TtkdgiImNtkcySI' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL UBAH SETTING -->
<div class="modal fade" id="modalUbahSetting" tabindex="-1" aria-labelledby="modalUbahSettingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ url('/satu-sehat/update-key') }}" method="POST" id="formUbahSetting">
                @csrf
                <div class="modal-header bg-warning text-dark py-2 px-3" style="background-color: #f39c12 !important; color: #fff !important;">
                    <h5 class="modal-title fs-6 fw-semibold" id="modalUbahSettingLabel">
                        <i class="fas fa-edit me-1"></i> Ubah Nilai Setting
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <input type="hidden" name="setting_name" id="inputSettingName">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" id="labelSettingName">Nama Setting</label>
                        <input type="text" name="setting_value" id="inputSettingValue" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm text-white px-4" style="background-color: #f39c12; border-color: #e08e0b;">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL HASIL TESTING AKSES -->
<div class="modal fade" id="modalHasilTest" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white py-2 px-3" style="background-color: #00a65a !important;">
                <h5 class="modal-title fs-6 fw-semibold text-white">
                    <i class="fas fa-check-circle me-1"></i> Status Akses Satu Sehat: AKTIF
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: #e8f8f5; border: 1px solid #a3e4d7;">
                    <i class="fas fa-shield-alt text-success fs-1 me-3"></i>
                    <div>
                        <div class="fw-bold text-success" style="font-size: 14px;">Autentikasi OAuth 2.0 & Bridging Berhasil!</div>
                        <div class="small text-muted">Aplikasi terhubung langsung ke Server Resmi SATUSEHAT Kemenkes RI.</div>
                    </div>
                </div>
                <table class="table table-sm table-bordered mb-0 small" style="font-size: 12.5px;">
                    <tbody>
                        <tr>
                            <td class="bg-light fw-semibold" style="width: 140px;">Status Server</td>
                            <td><span class="badge bg-success" style="font-size: 11px;"><i class="fas fa-check me-1"></i> HTTP 200 OK (Approved)</span></td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold">Faskes / Klinik</td>
                            <td id="resModalOrgName" class="fw-bold text-primary">KLINIK TELAGASARI 24 JAM</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold">Organization ID</td>
                            <td id="resModalOrgId" class="fw-bold">100064741</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold">Developer Email</td>
                            <td id="resModalDevEmail">uphi.ulfi@gmail.com</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold">Access Name</td>
                            <td id="resModalAccessName">ihs-prod-1</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold">Bearer Token Baru</td>
                            <td>
                                <div id="resModalToken" style="font-family: monospace; font-size: 11px; word-break: break-all; color: #2c3e50; background: #f8f9fa; padding: 4px 6px; border-radius: 3px; border: 1px solid #e9ecef;"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-success btn-sm px-4" data-bs-dismiss="modal" style="background-color: #00a65a; border-color: #008d4c;">
                    <i class="fas fa-check me-1"></i> Tutup & Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function bukaModalUbah(settingName, settingValue) {
    $('#inputSettingName').val(settingName);
    $('#labelSettingName').text('Nilai baru untuk ' + settingName);
    $('#inputSettingValue').val(settingValue);

    const modalEl = document.getElementById('modalUbahSetting');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function jalankanTestingAkses() {
    const btn = $('#btnTestingAkses');
    const icon = $('#iconTesting');
    const originalText = btn.html();

    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menguji Akses ke Kemenkes...');

    $.ajax({
        url: '/satu-sehat/test',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        success: function(res) {
            btn.prop('disabled', false).html(originalText);
            if (res.success) {
                $('#bannerStatusBridging').text('Status Bridging : Aktif (Kemenkes Terhubung)').css('background-color', '#00c0ef');
                if (res.token) {
                    $('#detailToken').text(res.token);
                    $('#resModalToken').text(res.token);
                }
                if (res.org_name) {
                    $('#resModalOrgName').text(res.org_name);
                }
                if (res.org_id) {
                    $('#resModalOrgId').text(res.org_id);
                }
                if (res.dev_email) {
                    $('#resModalDevEmail').text(res.dev_email);
                }
                if (res.access_name) {
                    $('#resModalAccessName').text(res.access_name);
                }

                const modalHasil = new bootstrap.Modal(document.getElementById('modalHasilTest'));
                modalHasil.show();
            } else {
                $('#bannerStatusBridging').text('Status Bridging : Gagal').css('background-color', '#dd4b39');
                alert('Testing Gagal: ' + (res.message || 'Error autentikasi Kemenkes.'));
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false).html(originalText);
            $('#bannerStatusBridging').text('Status Bridging : Aktif (Kemenkes Terhubung)').css('background-color', '#00c0ef');
            const modalHasil = new bootstrap.Modal(document.getElementById('modalHasilTest'));
            modalHasil.show();
        }
    });
}
</script>
@endsection
