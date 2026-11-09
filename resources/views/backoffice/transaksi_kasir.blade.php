@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-desktop text-success me-2"></i> Transaksi Kasir (Billing)</h1>
</div>

<div class="alert alert-info py-2 shadow-sm mb-4">
    <i class="fas fa-bell me-2"></i> Berikut adalah daftar pasien yang telah selesai dilayani di poli/apotek dan <strong>menunggu pembayaran di Kasir</strong>.
</div>

<div class="row">
    <!-- Antrean Kiri -->
    <div class="col-lg-7 mb-4">
        <div class="card-custom p-0 shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white py-3 border-0">
                <h5 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Antrean Pembayaran</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($antrean as $item)
                    <div class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary">{{ $item->nama_pasien }} <small class="text-muted">({{ $item->no_rawat }})</small></h5>
                            <p class="mb-0 text-muted small"><i class="fas fa-stethoscope me-1"></i> {{ $item->poli }} | <i class="fas fa-user-md me-1"></i> {{ $item->dokter }}</p>
                        </div>
                        <div class="text-end">
                            <span class="d-block text-danger fw-bold fs-5 mb-1">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                            <button class="btn btn-sm btn-outline-success btn-pilih" data-pasien="{{ $item->nama_pasien }}" data-rawat="{{ $item->no_rawat }}" data-total="{{ $item->total }}">
                                <i class="fas fa-hand-pointer me-1"></i> Pilih Pasien
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="p-5 text-center text-muted">
                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                        <h5>Tidak ada antrean pembayaran kasir saat ini.</h5>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Kanan (Proses Bayar) -->
    <div class="col-lg-5 mb-4">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light border border-success h-100">
            <h4 class="fw-bold text-center border-bottom pb-3 mb-4"><i class="fas fa-calculator me-2"></i>Proses Pembayaran</h4>
            
            <form id="formBayar">
                <div class="mb-3">
                    <label class="text-muted fw-bold d-block mb-1">Nama Pasien</label>
                    <input type="text" class="form-control fw-bold bg-white" id="b_pasien" readonly placeholder="Pilih pasien di antrean...">
                </div>

                <div class="mb-4">
                    <label class="text-muted fw-bold d-block mb-1">TOTAL TAGIHAN</label>
                    <div class="fs-1 fw-bold text-danger text-end border rounded p-2 bg-white" id="b_total_label">Rp 0</div>
                    <input type="hidden" id="b_total_val" value="0">
                </div>

                <div class="mb-4">
                    <label class="text-muted fw-bold d-block mb-1">UANG TUNAI DIBAYAR <span class="text-danger">*</span></label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-success text-white fw-bold">Rp</span>
                        <input type="number" class="form-control fw-bold text-end fs-3" id="b_bayar" min="0" required placeholder="0" disabled>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-muted fw-bold d-block mb-1">KEMBALIAN</label>
                    <div class="fs-3 fw-bold text-success text-end border rounded p-2 bg-white" id="b_kembali_label">Rp 0</div>
                </div>

                <button type="button" class="btn btn-success btn-lg w-100 fw-bold shadow-sm py-3" id="btnProses" disabled>
                    <i class="fas fa-check-circle me-2"></i> SELESAIKAN PEMBAYARAN
                </button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function formatRp(angka) {
        return parseInt(angka || 0).toLocaleString('id-ID');
    }

    $('.btn-pilih').click(function() {
        let nama = $(this).data('pasien');
        let total = $(this).data('total');
        
        $('#b_pasien').val(nama);
        $('#b_total_val').val(total);
        $('#b_total_label').text('Rp ' + formatRp(total));
        
        $('#b_bayar').prop('disabled', false).val('').focus();
        $('#btnProses').prop('disabled', false);
        $('#b_kembali_label').text('Rp 0').removeClass('text-danger text-success').addClass('text-success');
    });

    $('#b_bayar').on('input', function() {
        let tagihan = parseInt($('#b_total_val').val()) || 0;
        let bayar = parseInt($(this).val()) || 0;
        let kembali = bayar - tagihan;

        if (kembali < 0) {
            $('#b_kembali_label').text('Uang Kurang!').removeClass('text-success').addClass('text-danger');
        } else {
            $('#b_kembali_label').text('Rp ' + formatRp(kembali)).removeClass('text-danger').addClass('text-success');
        }
    });

    $('#btnProses').click(function() {
        let tagihan = parseInt($('#b_total_val').val()) || 0;
        let bayar = parseInt($('#b_bayar').val()) || 0;
        
        if (bayar < tagihan) {
            alert('Pembayaran gagal! Uang tunai yang dibayar kurang dari total tagihan.');
            return;
        }

        let btn = $(this);
        let orig = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...').prop('disabled', true);

        // Simulasi proses
        setTimeout(() => {
            alert('Pembayaran berhasil diproses!\nKwitansi akan dicetak.');
            // Reload atau reset
            location.reload();
        }, 1000);
    });
});
</script>
@endsection
