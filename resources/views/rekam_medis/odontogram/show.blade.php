@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .tooth-btn {
        width: 45px;
        height: 45px;
        margin: 2px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 2px solid #ddd;
        background-color: #fff;
        cursor: pointer;
        transition: all 0.2s;
    }
    .tooth-btn:hover {
        background-color: #f0f0f0;
        border-color: #999;
    }
    /* Warna berdasarkan status umum, bisa disesuaikan */
    .tooth-Caries { background-color: #ffebee; border-color: #f44336; color: #d32f2f; }
    .tooth-Missing { background-color: #e0e0e0; border-color: #9e9e9e; color: #616161; }
    .tooth-Filled { background-color: #e8f5e9; border-color: #4caf50; color: #388e3c; }
    .tooth-Normal { background-color: #fff; border-color: #ddd; color: #333; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/odon/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Pemeriksaan Odontogram
    </h1>
</div>

<div class="row">
    <!-- Kolom Kiri: Peta Odontogram Sederhana (Grid Angka) -->
    <div class="col-md-8">
        <div class="card card-custom mb-4">
            <div class="card-header bg-light fw-bold text-center">Peta Gigi (FDI System)</div>
            <div class="card-body text-center">
                
                <h6 class="text-muted mb-2">Gigi Permanen (Atas)</h6>
                <div class="d-flex justify-content-center mb-2 flex-wrap">
                    <!-- Kuadran 1 -->
                    <div class="me-3 border-end pe-3">
                        @foreach(['18','17','16','15','14','13','12','11'] as $g)
                            @php $cond = isset($records[$g]) ? $records[$g]->kondisi : 'Normal'; @endphp
                            <div class="tooth-btn tooth-{{ $cond }} btn-gigi" data-gigi="{{ $g }}" data-kondisi="{{ $cond }}" data-ket="{{ isset($records[$g]) ? $records[$g]->keterangan : '' }}">
                                {{ $g }}
                            </div>
                        @endforeach
                    </div>
                    <!-- Kuadran 2 -->
                    <div>
                        @foreach(['21','22','23','24','25','26','27','28'] as $g)
                            @php $cond = isset($records[$g]) ? $records[$g]->kondisi : 'Normal'; @endphp
                            <div class="tooth-btn tooth-{{ $cond }} btn-gigi" data-gigi="{{ $g }}" data-kondisi="{{ $cond }}" data-ket="{{ isset($records[$g]) ? $records[$g]->keterangan : '' }}">
                                {{ $g }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <h6 class="text-muted mb-2">Gigi Permanen (Bawah)</h6>
                <div class="d-flex justify-content-center mb-2 flex-wrap">
                    <!-- Kuadran 4 -->
                    <div class="me-3 border-end pe-3">
                        @foreach(['48','47','46','45','44','43','42','41'] as $g)
                            @php $cond = isset($records[$g]) ? $records[$g]->kondisi : 'Normal'; @endphp
                            <div class="tooth-btn tooth-{{ $cond }} btn-gigi" data-gigi="{{ $g }}" data-kondisi="{{ $cond }}" data-ket="{{ isset($records[$g]) ? $records[$g]->keterangan : '' }}">
                                {{ $g }}
                            </div>
                        @endforeach
                    </div>
                    <!-- Kuadran 3 -->
                    <div>
                        @foreach(['31','32','33','34','35','36','37','38'] as $g)
                            @php $cond = isset($records[$g]) ? $records[$g]->kondisi : 'Normal'; @endphp
                            <div class="tooth-btn tooth-{{ $cond }} btn-gigi" data-gigi="{{ $g }}" data-kondisi="{{ $cond }}" data-ket="{{ isset($records[$g]) ? $records[$g]->keterangan : '' }}">
                                {{ $g }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="my-4 text-muted">
                
                <h6 class="text-muted mb-2">Gigi Susu (Desidui)</h6>
                <div class="d-flex justify-content-center mb-2 flex-wrap">
                    <div class="me-3 border-end pe-3">
                        @foreach(['55','54','53','52','51'] as $g)
                            @php $cond = isset($records[$g]) ? $records[$g]->kondisi : 'Normal'; @endphp
                            <div class="tooth-btn tooth-{{ $cond }} btn-gigi" data-gigi="{{ $g }}" data-kondisi="{{ $cond }}" data-ket="{{ isset($records[$g]) ? $records[$g]->keterangan : '' }}">
                                {{ $g }}
                            </div>
                        @endforeach
                    </div>
                    <div>
                        @foreach(['61','62','63','64','65'] as $g)
                            @php $cond = isset($records[$g]) ? $records[$g]->kondisi : 'Normal'; @endphp
                            <div class="tooth-btn tooth-{{ $cond }} btn-gigi" data-gigi="{{ $g }}" data-kondisi="{{ $cond }}" data-ket="{{ isset($records[$g]) ? $records[$g]->keterangan : '' }}">
                                {{ $g }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="d-flex justify-content-center mb-2 flex-wrap">
                    <div class="me-3 border-end pe-3">
                        @foreach(['85','84','83','82','81'] as $g)
                            @php $cond = isset($records[$g]) ? $records[$g]->kondisi : 'Normal'; @endphp
                            <div class="tooth-btn tooth-{{ $cond }} btn-gigi" data-gigi="{{ $g }}" data-kondisi="{{ $cond }}" data-ket="{{ isset($records[$g]) ? $records[$g]->keterangan : '' }}">
                                {{ $g }}
                            </div>
                        @endforeach
                    </div>
                    <div>
                        @foreach(['71','72','73','74','75'] as $g)
                            @php $cond = isset($records[$g]) ? $records[$g]->kondisi : 'Normal'; @endphp
                            <div class="tooth-btn tooth-{{ $cond }} btn-gigi" data-gigi="{{ $g }}" data-kondisi="{{ $cond }}" data-ket="{{ isset($records[$g]) ? $records[$g]->keterangan : '' }}">
                                {{ $g }}
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Form Update Kondisi Gigi -->
    <div class="col-md-4">
        <!-- Info Pasien Mini -->
        <div class="card card-custom mb-3">
            <div class="card-header bg-primary text-white fw-bold">Info Pasien</div>
            <div class="card-body p-3">
                <strong>{{ $pendaftaran->pasien->nama_pasien }}</strong><br>
                RM: {{ $pendaftaran->pasien->no_rm }}<br>
                Tgl: {{ $pendaftaran->tanggal_daftar->format('d/m/Y') }}
            </div>
        </div>

        <div id="alertBox" class="alert d-none" role="alert"></div>

        <div class="card card-custom">
            <div class="card-header bg-info text-white fw-bold">
                Update Status Gigi <span id="lblGigi" class="badge bg-warning text-dark fs-6 ms-2">-</span>
            </div>
            <div class="card-body">
                <p class="text-muted small">Klik salah satu gigi di peta untuk memperbarui kondisinya.</p>
                <form id="formOdontogram">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">
                    <input type="hidden" name="dokter_id" value="{{ auth()->user()->pegawai_id ?? $pendaftaran->dokter_id }}">
                    <input type="hidden" id="nomor_gigi" name="nomor_gigi" required>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kondisi</label>
                        <select class="form-select" id="kondisi" name="kondisi" disabled required>
                            <option value="Normal">Normal</option>
                            <option value="Caries">Caries (Karies)</option>
                            <option value="Missing">Missing (Hilang/Dicabut)</option>
                            <option value="Filled">Filled (Ditambal)</option>
                            <option value="Sisa Akar">Sisa Akar</option>
                            <option value="Gigi Tiruan">Gigi Tiruan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan Khusus</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" disabled placeholder="Misal: Karies media, tambalan komposit..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="btnSimpan" disabled>Simpan Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let currentBtn = null;

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger').addClass('alert-' + type).text(msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 3000);
    }

    $('.btn-gigi').on('click', function() {
        $('.btn-gigi').css('box-shadow', 'none');
        $(this).css('box-shadow', '0 0 0 3px #00a65a');
        
        currentBtn = $(this);
        let gigi = $(this).data('gigi');
        let kondisi = $(this).attr('data-kondisi') || 'Normal';
        let ket = $(this).attr('data-ket') || '';

        $('#lblGigi').text(gigi);
        $('#nomor_gigi').val(gigi);
        $('#kondisi').val(kondisi).prop('disabled', false);
        $('#keterangan').val(ket).prop('disabled', false);
        $('#btnSimpan').prop('disabled', false);
    });

    $('#formOdontogram').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/rekam-medis/odontogram',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    showAlert('success', res.message);
                    
                    // Update tampilan tombol secara langsung
                    let newCond = $('#kondisi').val();
                    let newKet = $('#keterangan').val();
                    
                    if(currentBtn) {
                        currentBtn.removeClass('tooth-Normal tooth-Caries tooth-Missing tooth-Filled tooth-Sisa tooth-Gigi')
                                  .addClass('tooth-' + newCond.split(' ')[0]); // Trick class name for styling
                        currentBtn.attr('data-kondisi', newCond);
                        currentBtn.attr('data-ket', newKet);
                    }
                }
            },
            error: function(xhr) {
                showAlert('danger', 'Gagal memperbarui status gigi.');
            }
        });
    });
});
</script>
@endsection
