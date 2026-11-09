@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Konfigurasi API BPJS Kesehatan (Antrol & VClaim)</h1>
</div>

<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle me-2"></i> Masukkan parameter koneksi API (Cons ID, Secret Key, User Key) yang didapatkan dari portal BPJS Trustmark.
</div>

<div id="alertBox" class="alert d-none mb-3" role="alert"></div>

<div class="row">
    <div class="col-md-8">
        <div class="card-custom p-4">
            <form id="formConfigBpjs">
                @csrf
                
                <div class="mb-4 text-center">
                    <img src="https://bpjs-kesehatan.go.id/bpjs/images/logo.png" alt="BPJS" style="max-height: 50px;" class="mb-3">
                    <h5 class="fw-bold">Parameter Autentikasi</h5>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-bold">Cons ID (Client ID) <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="client_id" id="client_id" value="{{ $config->client_id }}" placeholder="Cth: 12345">
                        <div class="invalid-feedback" id="err_client_id"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-bold">Secret Key <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="password" class="form-control" name="client_secret" id="client_secret" value="{{ $config->client_secret }}" placeholder="Secret Key">
                            <button class="btn btn-outline-secondary toggle-pwd" type="button"><i class="fas fa-eye"></i></button>
                        </div>
                        <div class="invalid-feedback d-block mt-1" id="err_client_secret"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-bold">User Key (Antrol) <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="password" class="form-control" name="user_key" id="user_key" value="{{ $config->user_key }}" placeholder="User Key Antrean Faskes">
                            <button class="btn btn-outline-secondary toggle-pwd" type="button"><i class="fas fa-eye"></i></button>
                        </div>
                        <div class="invalid-feedback d-block mt-1" id="err_user_key"></div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-bold">Base URL <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="base_url" id="base_url" value="{{ $config->base_url }}" placeholder="https://apijkn-dev.bpjs-kesehatan.go.id/antreanrs_dev/">
                        <div class="invalid-feedback" id="err_base_url"></div>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-sm-3 col-form-label fw-bold">Environment</label>
                    <div class="col-sm-9 pt-2">
                        <div class="form-check form-switch form-check-inline">
                            <input class="form-check-input" type="radio" name="is_production" id="env_dev" value="0" {{ !$config->is_production ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-secondary" for="env_dev">Development / Staging</label>
                        </div>
                        <div class="form-check form-switch form-check-inline">
                            <input class="form-check-input" type="radio" name="is_production" id="env_prod" value="1" {{ $config->is_production ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-danger" for="env_prod">Production (Live)</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-outline-success me-2" id="btnTest">
                        <i class="fas fa-plug me-1"></i> Test Koneksi
                    </button>
                    <button type="submit" class="btn btn-cyan text-white" id="btnSimpan">
                        <i class="fas fa-save me-1"></i> Simpan Konfigurasi
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom p-4 bg-light">
            <h5 class="fw-bold"><i class="fas fa-book me-2"></i>Panduan</h5>
            <p class="text-muted small">Pastikan Anda telah mendaftarkan IP Public server ini pada portal Trustmark BPJS Kesehatan.</p>
            <p class="text-muted small mb-1">Status Bridging:</p>
            <div id="statusKoneksi" class="alert alert-secondary p-2 text-center fw-bold">
                <i class="fas fa-question-circle"></i> Belum Diuji
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.toggle-pwd').click(function() {
        let input = $(this).prev('input');
        let icon = $(this).find('i');
        if(input.attr('type') == 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#formConfigBpjs').submit(function(e) {
        e.preventDefault();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        let btn = $('#btnSimpan');
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: '/config-bpjs',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                btn.html(originalText).prop('disabled', false);
                $('#alertBox').removeClass('d-none alert-danger').addClass('alert-success').text(res.message).show();
                $('html, body').animate({scrollTop: 0}, 300);
            },
            error: function(xhr) {
                btn.html(originalText).prop('disabled', false);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $('#' + field).addClass('is-invalid');
                        $('#err_' + field).text(messages[0]);
                    });
                } else {
                    $('#alertBox').removeClass('d-none alert-success').addClass('alert-danger').text('Terjadi kesalahan server!').show();
                }
            }
        });
    });

    $('#btnTest').click(function() {
        let btn = $(this);
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Testing...').prop('disabled', true);
        
        $('#statusKoneksi').removeClass('alert-success alert-danger alert-secondary').addClass('alert-warning').html('<i class="fas fa-spinner fa-spin"></i> Menghubungi Server BPJS...');

        $.ajax({
            url: '/api/bridging/test-connection',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', provider: 'bpjs_antrol' },
            success: function(res) {
                btn.html(originalText).prop('disabled', false);
                $('#statusKoneksi').removeClass('alert-warning').addClass('alert-success').html('<i class="fas fa-check-circle"></i> Connected (Valid)');
                alert(res.message);
            },
            error: function() {
                btn.html(originalText).prop('disabled', false);
                $('#statusKoneksi').removeClass('alert-warning').addClass('alert-danger').html('<i class="fas fa-times-circle"></i> Disconnected');
                alert('Gagal terhubung ke BPJS atau kredensial salah.');
            }
        });
    });
});
</script>
@endsection
