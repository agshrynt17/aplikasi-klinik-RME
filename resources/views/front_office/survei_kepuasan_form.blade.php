@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* Styling for Star Rating System */
    .rating-group {
        display: inline-flex;
        flex-direction: row-reverse;
        justify-content: center;
        align-items: center;
        margin-top: 10px;
    }
    .rating-group input {
        display: none;
    }
    .rating-group label {
        font-size: 2.5rem;
        color: #ddd;
        cursor: pointer;
        padding: 0 5px;
        transition: color 0.2s;
    }
    .rating-group input:checked ~ label,
    .rating-group label:hover,
    .rating-group label:hover ~ label {
        color: #ffc107; /* Bootstrap Warning Color for Gold Star */
    }
</style>

<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Formulir Survei Kepuasan Pasien</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/survei-kepuasan" class="text-decoration-none text-muted">Survei Kepuasan</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Formulir</a></li>
            </ol>
        </nav>
    </div>
    <a href="/survei-kepuasan" class="btn btn-outline-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div id="alertBox" class="alert d-none mb-3 border-0 shadow-sm" role="alert"></div>

<div class="row justify-content-center">
    <div class="col-md-8 mb-4">
        <div class="card-custom p-4">
            <div class="text-center mb-4">
                <i class="fas fa-smile-beam fa-4x text-cyan mb-3"></i>
                <h4 class="fw-bold">Bagaimana Pelayanan Kami Hari Ini?</h4>
                <p class="text-muted">Umpan balik dari Bpk/Ibu <strong>{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</strong> sangat berarti untuk peningkatan kualitas layanan kami.</p>
            </div>

            <form id="formSurvei">
                @csrf
                <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                
                <!-- Rating Pelayanan Petugas -->
                <div class="mb-4 text-center border-bottom pb-3">
                    <h5 class="fw-semibold">Kualitas Pelayanan Administrasi & Petugas</h5>
                    <div class="rating-group">
                        @for($i=5; $i>=1; $i--)
                            <input type="radio" id="pelayanan_{{$i}}" name="pelayanan" value="{{$i}}" {{ ($survei->pelayanan ?? 0) == $i ? 'checked' : '' }}>
                            <label for="pelayanan_{{$i}}"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                    <div class="invalid-feedback d-block fw-bold mt-1" id="err_pelayanan"></div>
                </div>

                <!-- Rating Fasilitas -->
                <div class="mb-4 text-center border-bottom pb-3">
                    <h5 class="fw-semibold">Kenyamanan Fasilitas Klinik</h5>
                    <div class="rating-group">
                        @for($i=5; $i>=1; $i--)
                            <input type="radio" id="fasilitas_{{$i}}" name="fasilitas" value="{{$i}}" {{ ($survei->fasilitas ?? 0) == $i ? 'checked' : '' }}>
                            <label for="fasilitas_{{$i}}"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                    <div class="invalid-feedback d-block fw-bold mt-1" id="err_fasilitas"></div>
                </div>

                <!-- Rating Dokter -->
                <div class="mb-4 text-center border-bottom pb-3">
                    <h5 class="fw-semibold">Penanganan oleh Dokter ({{ $pendaftaran->dokter->nama_pegawai ?? '-' }})</h5>
                    <div class="rating-group">
                        @for($i=5; $i>=1; $i--)
                            <input type="radio" id="dokter_{{$i}}" name="dokter" value="{{$i}}" {{ ($survei->dokter ?? 0) == $i ? 'checked' : '' }}>
                            <label for="dokter_{{$i}}"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                    <div class="invalid-feedback d-block fw-bold mt-1" id="err_dokter"></div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Kritik & Saran (Opsional)</label>
                    <textarea class="form-control" name="komentar" rows="4" placeholder="Tuliskan pengalaman Anda di sini...">{{ $survei->komentar ?? '' }}</textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-cyan btn-lg text-white px-5 rounded-pill shadow-sm" id="btnSimpan">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#formSurvei').on('submit', function (e) {
        e.preventDefault();
        $('.invalid-feedback').text('');
        
        let btn = $('#btnSimpan');
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Mengirim...').prop('disabled', true);

        $.ajax({
            url: '/survei-kepuasan', 
            type: 'POST', 
            data: $(this).serialize(),
            success: function (res) {
                btn.html(originalText).prop('disabled', false);
                $('#alertBox').removeClass('d-none alert-danger').addClass('alert-success').text(res.message).show();
                $('html, body').animate({scrollTop: 0}, 300);
            },
            error: function (xhr) {
                btn.html(originalText).prop('disabled', false);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $('#err_' + field).text(messages[0]);
                    });
                    $('#alertBox').removeClass('d-none alert-success').addClass('alert-danger').text('Terdapat penilaian yang belum diisi.').show();
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
