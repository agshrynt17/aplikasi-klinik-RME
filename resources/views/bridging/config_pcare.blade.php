@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-rss text-primary me-2"></i> Konfigurasi Bridging PCare & i-Care</h1>
</div>

<div class="alert alert-info py-2 mb-4 shadow-sm">
    <i class="fas fa-info-circle me-2"></i> Masukkan konfigurasi <strong>Cons ID</strong>, <strong>Secret Key</strong>, dan <strong>User Key</strong> yang Anda peroleh dari tim IT BPJS Kesehatan Pusat/Cabang.
</div>

<div class="row">
    <!-- PCare Form -->
    <div class="col-md-6 mb-4">
        <div class="card-custom shadow-sm border-0 rounded-3 h-100">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-dot-circle me-2"></i>Bridging BPJS PCare</h5>
            </div>
            <div class="card-body p-4 bg-light">
                <form id="formPcare">
                    @csrf
                    <input type="hidden" name="provider" value="pcare">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cons ID (Client ID) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="client_id" value="{{ $configPcare->client_id }}" placeholder="Cons ID RS/Klinik" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Secret Key <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="client_secret" value="{{ $configPcare->client_secret }}" placeholder="Secret Key" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">User Key (Opsional PCare)</label>
                        <input type="text" class="form-control" name="user_key" value="{{ $configPcare->user_key }}" placeholder="User Key">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Base URL PCare API <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="base_url" value="{{ $configPcare->base_url }}" required>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_production" id="is_prod_pcare" {{ $configPcare->is_production ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_prod_pcare">Mode Production (Live)</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-info fw-bold btn-test" data-provider="pcare">
                            <i class="fas fa-wifi me-1"></i> Ping / Test
                        </button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- i-Care Form -->
    <div class="col-md-6 mb-4">
        <div class="card-custom shadow-sm border-0 rounded-3 h-100">
            <div class="card-header text-white py-3" style="background-color: #0dcaf0;">
                <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Bridging BPJS i-Care</h5>
            </div>
            <div class="card-body p-4 bg-light">
                <form id="formIcare">
                    @csrf
                    <input type="hidden" name="provider" value="icare">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cons ID (Client ID) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="client_id" value="{{ $configIcare->client_id }}" placeholder="Cons ID RS/Klinik" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Secret Key <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="client_secret" value="{{ $configIcare->client_secret }}" placeholder="Secret Key" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">User Key (Wajib i-Care) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="user_key" value="{{ $configIcare->user_key }}" placeholder="User Key" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Base URL i-Care API <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="base_url" value="{{ $configIcare->base_url }}" required>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_production" id="is_prod_icare" {{ $configIcare->is_production ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_prod_icare">Mode Production (Live)</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-info fw-bold btn-test" data-provider="icare">
                            <i class="fas fa-wifi me-1"></i> Ping / Test
                        </button>
                        <button type="submit" class="btn btn-info text-white fw-bold px-4">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    
    // Simpan Config PCare
    $('#formPcare').on('submit', function (e) {
        e.preventDefault();
        let btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: '/bridging/pcare',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) alert(res.message);
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                alert('Terjadi kesalahan penyimpanan PCare. Cek semua isian URL dsb.');
            }
        });
    });

    // Simpan Config i-Care
    $('#formIcare').on('submit', function (e) {
        e.preventDefault();
        let btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: '/bridging/pcare', // we use the same store endpoint that discriminates via provider=icare
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) alert(res.message);
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                alert('Terjadi kesalahan penyimpanan i-Care. Cek isian wajib.');
            }
        });
    });

    // Test Koneksi
    $('.btn-test').on('click', function () {
        let provider = $(this).data('provider');
        let btn = $(this);
        let origHtml = btn.html();
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Testing...');

        $.ajax({
            url: '/bridging/test',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                provider: provider
            },
            success: function (res) {
                if (res.success) {
                    alert('SUCCESS: \n' + res.message);
                }
                btn.prop('disabled', false).html(origHtml);
            },
            error: function (xhr) {
                btn.prop('disabled', false).html(origHtml);
                alert('ERROR: Koneksi ke server BPJS Gagal. Cek URL atau Jaringan Anda.');
            }
        });
    });

});
</script>
@endsection
