@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Pembayaran Kasir</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/transaksi-kasir" class="text-decoration-none text-muted">Transaksi Kasir</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
            </ol>
        </nav>
    </div>
    <a href="/transaksi-kasir" class="btn btn-outline-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div id="alertBox" class="alert d-none mb-3 border-0 shadow-sm" role="alert"></div>

<div class="row">
    <!-- Rincian Tagihan -->
    <div class="col-md-7 mb-4">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 h-100">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Rincian Tagihan</h6>
            
            <!-- Info Pasien Ringkas -->
            <div class="card border-0 bg-light mb-4 rounded-3" style="border-left: 4px solid #00c0ef !important;">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-sm-6 border-end">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase">Pasien</small>
                            <div class="fw-bold fs-5 text-dark">{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</div>
                            <div class="small">RM: <span class="badge bg-secondary">{{ $pendaftaran->pasien->no_rm ?? '-' }}</span> | No. Rawat: <span class="fw-semibold">{{ $pendaftaran->no_rawat }}</span></div>
                        </div>
                        <div class="col-sm-6 ps-sm-4 mt-3 mt-sm-0">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase">Penjamin / Tipe</small>
                            <div class="fw-bold text-primary fs-5">{{ $pendaftaran->penjamin->nama_penjamin ?? 'UMUM' }}</div>
                            <div class="small text-muted">{{ $pendaftaran->poliklinik->nama_poli ?? '-' }} - {{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold text-secondary mt-3"><i class="fas fa-stethoscope me-1"></i> A. Tindakan Medis / Layanan</h6>
            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover table-bordered border-light align-middle" style="border: 1px solid #dee2e6;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="text-dark py-2 ps-3">Nama Layanan</th>
                            <th class="text-center text-dark py-2">Qty</th>
                            <th class="text-end text-dark py-2 pe-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tindakans as $t)
                        <tr>
                            <td class="ps-3">{{ $t->tindakan->nama_tindakan ?? '-' }}</td>
                            <td class="text-center">{{ $t->qty }}</td>
                            <td class="text-end pe-3">Rp {{ number_format($t->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada tindakan medis.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background-color: #e9ecef;">
                        <tr>
                            <th colspan="2" class="text-end py-2 text-dark">Total Tindakan</th>
                            <th class="text-end py-2 pe-3 text-dark">Rp {{ number_format($total_tindakan, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <h6 class="fw-bold text-secondary mt-2"><i class="fas fa-pills me-1"></i> B. Resep Obat (Apotek)</h6>
            <div class="table-responsive mb-2">
                <table class="table table-sm table-hover table-bordered border-light align-middle" style="border: 1px solid #dee2e6;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="text-dark py-2 ps-3">Keterangan</th>
                            <th class="text-end text-dark py-2 pe-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="2" class="text-center text-muted py-3">Fitur Apotek belum tersedia saat ini.</td></tr>
                    </tbody>
                    <tfoot style="background-color: #e9ecef;">
                        <tr>
                            <th class="text-end py-2 text-dark">Total Obat</th>
                            <th class="text-end py-2 pe-3 text-dark">Rp {{ number_format($total_obat, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Pembayaran / Status -->
    <div class="col-md-5 mb-4">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 h-100" style="background-color: #f8f9fa;">
            @if($kasir && $kasir->status_pembayaran == 'Lunas')
                <!-- Tampilan Jika Sudah Lunas -->
                <div class="text-center mb-4 mt-2">
                    <div class="d-inline-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle mb-3" style="width: 100px; height: 100px;">
                        <i class="fas fa-check text-success fa-3x"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-1">LUNAS</h3>
                    <p class="text-muted mb-0 fs-5">Kwitansi: <strong>{{ $kasir->no_kwitansi }}</strong></p>
                    <div class="text-muted small mt-2">
                        <div>Waktu: {{ \Carbon\Carbon::parse($kasir->waktu_bayar)->format('d-m-Y H:i') }}</div>
                        <div>Kasir: {{ $kasir->kasir->name ?? 'Admin' }}</div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4 rounded-3">
                    <ul class="list-group list-group-flush rounded-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted">Total Tagihan</span>
                            <span class="fw-bold">Rp {{ number_format($kasir->total_tagihan + $kasir->diskon, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted">Diskon</span>
                            <span class="fw-bold text-danger">- Rp {{ number_format($kasir->diskon, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-light">
                            <span class="fw-bold text-dark fs-5">Grand Total</span>
                            <span class="fw-bold text-primary fs-5">Rp {{ number_format($kasir->total_tagihan, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted">Uang Diterima</span>
                            <span class="fw-bold">Rp {{ number_format($kasir->uang_diterima, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted">Kembalian</span>
                            <span class="fw-bold text-success">Rp {{ number_format($kasir->kembalian, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted">Metode</span>
                            <span class="badge bg-secondary px-3 py-2">{{ $kasir->akunPembayaran->nama_akun ?? 'CASH' }}</span>
                        </li>
                    </ul>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-success shadow-sm py-2"><i class="fas fa-print me-2"></i> Cetak Kwitansi</button>
                    <button class="btn btn-outline-danger py-2 mt-2" id="btnBatalBayar"><i class="fas fa-times me-2"></i> Batalkan Pembayaran</button>
                </div>

            @else
                <!-- Form Input Pembayaran Jika Belum Lunas -->
                <h5 class="fw-bold mb-4 border-bottom pb-3 text-center text-dark"><i class="fas fa-cash-register me-2 text-primary"></i>Proses Pembayaran</h5>
                
                <form id="formKasir">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                    <input type="hidden" id="raw_total" value="{{ $total_semua }}">

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label fw-bold small text-muted">Subtotal (Rp)</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control text-end fw-bold bg-white border-0 shadow-sm" value="Rp {{ number_format($total_semua, 0, ',', '.') }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label fw-bold small text-danger">Diskon (Rp)</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control text-end shadow-sm" id="diskon" name="diskon" value="0" min="0">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center mt-4">
                        <label class="col-sm-5 col-form-label fw-bold fs-5 text-dark">GRAND TOTAL</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control form-control-lg text-end fw-bold text-primary bg-white border-0 shadow" id="grand_total_display" value="Rp {{ number_format($total_semua, 0, ',', '.') }}" readonly>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color: #dee2e6;">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Metode / Akun Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select fw-semibold shadow-sm" id="akun_pembayaran_id" name="akun_pembayaran_id">
                            <option value="">-- Pilih Rekening / Tunai --</option>
                            @foreach($akun_pembayarans as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->nama_akun }} ({{ $akun->jenis_akun }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="err_akun_pembayaran_id"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Uang Diterima (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-lg text-end fw-bold shadow-sm" id="uang_diterima" name="uang_diterima" placeholder="0">
                        <div class="invalid-feedback" id="err_uang_diterima"></div>
                    </div>

                    <div class="mb-4 p-3 bg-white border rounded-3 shadow-sm d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success fs-6">KEMBALIAN</span>
                        <input type="text" class="form-control-plaintext text-end fw-bold text-success fs-4 w-50 p-0 m-0" id="kembalian_display" value="Rp 0" readonly>
                    </div>

                    <button type="submit" class="btn btn-success w-100 text-white btn-lg shadow mt-2" id="btnSimpanBayar">
                        <i class="fas fa-check-circle me-2"></i> Selesaikan Pembayaran
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    let rawTotal = parseInt($('#raw_total').val()) || 0;

    function calculate() {
        let diskon = parseInt($('#diskon').val()) || 0;
        let grandTotal = rawTotal - diskon;
        if(grandTotal < 0) grandTotal = 0;
        
        $('#grand_total_display').val('Rp ' + formatRupiah(grandTotal));

        let uangDiterima = parseInt($('#uang_diterima').val()) || 0;
        let kembalian = uangDiterima - grandTotal;
        if(kembalian < 0) kembalian = 0;

        $('#kembalian_display').val('Rp ' + formatRupiah(kembalian));
    }

    $('#diskon, #uang_diterima').on('keyup change', function() {
        calculate();
    });

    $('#formKasir').on('submit', function (e) {
        e.preventDefault();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        let btn = $('#btnSimpanBayar');
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

        $.ajax({
            url: '/transaksi-kasir', 
            type: 'POST', 
            data: $(this).serialize(),
            success: function (res) {
                btn.html(originalText).prop('disabled', false);
                $('#alertBox').removeClass('d-none alert-danger').addClass('alert-success').text(res.message).show();
                $('html, body').animate({scrollTop: 0}, 300);
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function (xhr) {
                btn.html(originalText).prop('disabled', false);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if(errors) {
                        $.each(errors, function (field, messages) {
                            $('#' + field).addClass('is-invalid');
                            $('#err_' + field).text(messages[0]);
                        });
                    } else if (xhr.responseJSON.message) {
                        $('#alertBox').removeClass('d-none alert-success').addClass('alert-danger').text(xhr.responseJSON.message).show();
                    }
                    $('html, body').animate({scrollTop: 0}, 300);
                } else {
                    $('#alertBox').removeClass('d-none alert-success').addClass('alert-danger').text('Terjadi kesalahan server!').show();
                }
            }
        });
    });

    @if($kasir)
    $('#btnBatalBayar').click(function() {
        if(confirm('Yakin ingin membatalkan pembayaran ini? Data kwitansi akan dihapus.')) {
            $.ajax({
                url: '/transaksi-kasir/{{ $kasir->id }}',
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    alert(res.message);
                    window.location.reload();
                }
            });
        }
    });
    @endif
});
</script>
@endsection
