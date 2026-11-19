@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Formulir Persetujuan Umum</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/general-consent" class="text-decoration-none text-muted">General Consent</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Formulir</a></li>
            </ol>
        </nav>
    </div>
    <a href="/general-consent" class="btn btn-outline-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div id="alertBox" class="alert d-none mb-3 border-0 shadow-sm" role="alert"></div>

<div class="row">
    <!-- Info Pasien -->
    <div class="col-md-4 mb-4">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 h-100" style="border-top: 4px solid #00c0ef !important;">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="fas fa-user-injured me-2 text-primary"></i>Data Pasien</h6>
            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle">
                    <tr>
                        <td width="35%" class="text-muted fw-bold small">No. RM</td>
                        <td class="fw-bold text-dark">: {{ $pendaftaran->pasien->no_rm ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold small">No. Rawat</td>
                        <td class="fw-semibold text-dark">: {{ $pendaftaran->no_rawat }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold small">Nama Pasien</td>
                        <td class="fw-bold text-dark fs-6">: {{ $pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold small">Tgl Lahir</td>
                        <td class="text-dark">: {{ $pendaftaran->pasien->tanggal_lahir ? $pendaftaran->pasien->tanggal_lahir->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold small">Alamat</td>
                        <td class="text-dark">: {{ $pendaftaran->pasien->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold small">No. HP</td>
                        <td class="text-dark">: {{ $pendaftaran->pasien->no_hp ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Form General Consent -->
    <div class="col-md-8">
        <div class="card-custom p-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-file-contract me-2"></i>Pihak Penanggung Jawab</h5>
            <form id="formConsent">
                @csrf
                <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Penanggung Jawab <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_pj" name="nama_pj" value="{{ $consent->nama_pj ?? '' }}">
                        <div class="invalid-feedback" id="err_nama_pj"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Hubungan dengan Pasien <span class="text-danger">*</span></label>
                        <select class="form-select" id="hubungan_pj" name="hubungan_pj">
                            <option value="">- Pilih Hubungan -</option>
                            <option value="Diri Sendiri" {{ ($consent->hubungan_pj ?? '') == 'Diri Sendiri' ? 'selected' : '' }}>Diri Sendiri</option>
                            <option value="Suami" {{ ($consent->hubungan_pj ?? '') == 'Suami' ? 'selected' : '' }}>Suami</option>
                            <option value="Istri" {{ ($consent->hubungan_pj ?? '') == 'Istri' ? 'selected' : '' }}>Istri</option>
                            <option value="Anak" {{ ($consent->hubungan_pj ?? '') == 'Anak' ? 'selected' : '' }}>Anak</option>
                            <option value="Ayah" {{ ($consent->hubungan_pj ?? '') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                            <option value="Ibu" {{ ($consent->hubungan_pj ?? '') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                            <option value="Saudara" {{ ($consent->hubungan_pj ?? '') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                            <option value="Lainnya" {{ ($consent->hubungan_pj ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        <div class="invalid-feedback" id="err_hubungan_pj"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">No. HP / Telepon</label>
                        <input type="text" class="form-control" id="no_hp_pj" name="no_hp_pj" value="{{ $consent->no_hp_pj ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control" id="alamat_pj" name="alamat_pj" rows="2">{{ $consent->alamat_pj ?? '' }}</textarea>
                    </div>
                </div>

                <div class="alert alert-warning mt-3">
                    <strong>Pernyataan:</strong> Dengan ini menyatakan bahwa saya menyetujui untuk dilakukan tindakan medis umum, perawatan, pengobatan, serta menyetujui ketentuan administrasi dan tata tertib klinik.
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 text-center">
                        <p class="fw-semibold mb-2">Tanda Tangan Penanggung Jawab</p>
                        <div class="border rounded bg-light mb-2" style="height: 150px; position: relative;">
                            <!-- Placeholder untuk signature pad. Saat ini pakai textarea sederhana. -->
                            @if(isset($consent->ttd_pj) && !empty($consent->ttd_pj))
                                <img src="{{ $consent->ttd_pj }}" alt="TTD PJ" style="max-height: 140px; max-width: 100%;">
                                <input type="hidden" name="ttd_pj" value="{{ $consent->ttd_pj }}">
                            @else
                                <canvas id="pad_pj" width="300" height="150" class="border"></canvas>
                                <input type="hidden" id="ttd_pj" name="ttd_pj">
                                <br>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="clear_pj">Ulangi</button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <p class="fw-semibold mb-2">Tanda Tangan Petugas</p>
                        <div class="border rounded bg-light mb-2" style="height: 150px; position: relative;">
                            @if(isset($consent->ttd_petugas) && !empty($consent->ttd_petugas))
                                <img src="{{ $consent->ttd_petugas }}" alt="TTD Petugas" style="max-height: 140px; max-width: 100%;">
                                <input type="hidden" name="ttd_petugas" value="{{ $consent->ttd_petugas }}">
                            @else
                                <canvas id="pad_petugas" width="300" height="150" class="border"></canvas>
                                <input type="hidden" id="ttd_petugas" name="ttd_petugas">
                                <br>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="clear_petugas">Ulangi</button>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <div class="text-end">
                    <button type="submit" class="btn btn-cyan text-white px-5" id="btnSimpan">
                        <i class="fas fa-save me-2"></i> Simpan General Consent
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Signature Pad JS via CDN -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
<script>
$(document).ready(function () {
    let signaturePadPj = null;
    let signaturePadPetugas = null;

    // Inisialisasi Canvas jika ada
    let canvasPj = document.getElementById('pad_pj');
    if(canvasPj) {
        signaturePadPj = new SignaturePad(canvasPj);
        $('#clear_pj').click(function() { signaturePadPj.clear(); });
    }

    let canvasPetugas = document.getElementById('pad_petugas');
    if(canvasPetugas) {
        signaturePadPetugas = new SignaturePad(canvasPetugas);
        $('#clear_petugas').click(function() { signaturePadPetugas.clear(); });
    }

    // Copy data pasien (Diri Sendiri)
    $('#hubungan_pj').change(function() {
        if($(this).val() == 'Diri Sendiri') {
            $('#nama_pj').val("{{ $pendaftaran->pasien->nama_pasien }}");
            $('#no_hp_pj').val("{{ $pendaftaran->pasien->no_hp }}");
            $('#alamat_pj').val("{{ $pendaftaran->pasien->alamat }}");
        } else {
            if(!'{{ $consent->id ?? "" }}') {
                $('#nama_pj').val("");
                $('#no_hp_pj').val("");
                $('#alamat_pj').val("");
            }
        }
    });

    $('#formConsent').on('submit', function (e) {
        e.preventDefault();
        $('.is-invalid').removeClass('is-invalid');
        
        // Simpan Base64 TTD jika canvas ada & tidak kosong
        if(signaturePadPj && !signaturePadPj.isEmpty()) {
            $('#ttd_pj').val(signaturePadPj.toDataURL('image/png'));
        }
        if(signaturePadPetugas && !signaturePadPetugas.isEmpty()) {
            $('#ttd_petugas').val(signaturePadPetugas.toDataURL('image/png'));
        }
        
        let btn = $('#btnSimpan');
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: '/general-consent', 
            type: 'POST', 
            data: $(this).serialize(),
            success: function (res) {
                btn.html(originalText).prop('disabled', false);
                $('#alertBox').removeClass('d-none alert-danger').addClass('alert-success').text(res.message).show();
                $('html, body').animate({scrollTop: 0}, 300);
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            },
            error: function (xhr) {
                btn.html(originalText).prop('disabled', false);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $('#' + field).addClass('is-invalid');
                        $('#err_' + field).text(messages[0]);
                    });
                    $('#alertBox').removeClass('d-none alert-success').addClass('alert-danger').text('Periksa kembali inputan Anda.').show();
                    $('html, body').animate({scrollTop: 0}, 300);
                } else {
                    $('#alertBox').removeClass('d-none alert-success').addClass('alert-danger').text('Terjadi kesalahan server!').show();
                }
            }
        });
    });

});
</script>
@endsection
