@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Pembatalan Penyesuaian Stok</h1>
</div>

<div id="alertBox" class="alert d-none mb-3 shadow-sm" role="alert"></div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="alert alert-info py-2 mb-4 shadow-sm border-0">
        <i class="fas fa-info-circle me-2"></i> Di sini Anda hanya dapat membatalkan transaksi penyesuaian stok yang statusnya masih <strong>"Berhasil"</strong>.
    </div>

    <div class="row mb-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label form-label-sm mb-1 fw-semibold">Pencarian History (Nama / Keterangan)</label>
            <input type="text" id="searchInput" class="form-control"
                placeholder="Ketik untuk mencari...">
        </div>
    </div>

    <div class="table-responsive mt-2">
        <table class="table table-bordered table-hover align-middle" id="tabelPembatalan">
            <thead style="background-color: #d9534f; color: white;">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Waktu</th>
                    <th width="20%">Nama Barang</th>
                    <th width="15%" class="text-center">Jenis Penyesuaian</th>
                    <th width="10%" class="text-center">Jumlah</th>
                    <th width="20%">Keterangan</th>
                    <th width="15%" class="text-center">Aksi Batal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr data-id="{{ $item->id }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="fw-bold">{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                    <td class="text-center">
                        @if($item->jenis_penyesuaian === 'Penambahan')
                            <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i> Penambahan</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-arrow-down me-1"></i> Pengurangan</span>
                        @endif
                    </td>
                    <td class="text-center fw-bold fs-5">{{ $item->jumlah }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger btnBatal" data-id="{{ $item->id }}" data-detail="{{ $item->masterBarang->nama_barang }} ({{ $item->jenis_penyesuaian }} {{ $item->jumlah }})">
                            <i class="fas fa-times-circle me-1"></i> Batalkan
                        </button>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-check-circle fa-3x mb-3 d-block text-success"></i> 
                        <h5>Tidak ada riwayat penyesuaian yang dapat dibatalkan.</h5>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="text-muted small mt-2 d-flex justify-content-between" id="infoJumlah">
        <div>Total: <strong>{{ count($data) }}</strong> data</div>
    </div>
</div>

{{-- MODAL KONFIRMASI BATAL --}}
<div class="modal fade" id="modalKonfirmasiBatal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Pembatalan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                <p class="fs-5 mb-1">Anda yakin ingin membatalkan transaksi ini?</p>
                <p class="fw-bold text-dark fs-6" id="lblDetailBatal"></p>
                <div class="alert alert-warning mt-3 text-start small">
                    <strong>Peringatan:</strong> Pembatalan akan mengembalikan stok seperti sebelum penyesuaian dilakukan. Tindakan ini akan tercatat dalam log riwayat.
                </div>
            </div>
            <div class="modal-footer justify-content-center bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger px-4" id="btnEksekusiBatal">
                    <i class="fas fa-check me-1"></i> Ya, Batalkan Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let batalId = null;
    let batalRow = null;

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger').addClass('alert-' + type).text(msg).fadeIn();
        setTimeout(() => $('#alertBox').fadeOut(function() { $(this).addClass('d-none'); }), 4000);
    }

    function renumber() {
        let no = 1;
        $('#tabelPembatalan tbody tr[data-id]:visible').each(function () {
            $(this).find('td:first').text(no++);
        });
    }

    function doFilter() {
        let q = $('#searchInput').val().toLowerCase();
        let count = 0;
        $('#tabelPembatalan tbody tr[data-id]').each(function () {
            let row = $(this);
            let text = row.text().toLowerCase();
            if (q === '' || text.indexOf(q) !== -1) {
                row.show();
                count++;
            } else {
                row.hide();
            }
        });
        
        renumber();
        $('#infoJumlah').html('Total: <strong>' + count + '</strong> data');
        
        if (count === 0 && $('#tabelPembatalan tbody tr[data-id]').length > 0) {
            if ($('#emptyRow').length === 0) {
                $('#tabelPembatalan tbody').append('<tr id="emptyRow"><td colspan="7" class="text-center text-muted py-5"><i class="fas fa-search fa-3x mb-3 d-block text-secondary"></i><h5>Data tidak ditemukan.</h5></td></tr>');
            } else {
                $('#emptyRow').show();
            }
        } else if (count > 0) {
            $('#emptyRow').hide();
        }
    }

    $('#searchInput').on('keyup', doFilter);

    $(document).on('click', '.btnBatal', function () {
        batalId = $(this).data('id');
        batalRow = $(this).closest('tr');
        $('#lblDetailBatal').text($(this).data('detail'));
        $('#modalKonfirmasiBatal').modal('show');
    });

    $('#btnEksekusiBatal').on('click', function () {
        if (!batalId) return;
        
        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');

        $.ajax({
            url: `/farmasi/pembatalan-stok/${batalId}`,
            type: 'POST', // using POST instead of DELETE since it's an action, but we mapped it to POST in routes
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Ya, Batalkan Transaksi');
                $('#modalKonfirmasiBatal').modal('hide');
                
                if (res.success) {
                    showAlert('success', res.message);
                    batalRow.fadeOut(300, function() {
                        $(this).remove();
                        doFilter();
                    });
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Ya, Batalkan Transaksi');
                $('#modalKonfirmasiBatal').modal('hide');
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    showAlert('danger', xhr.responseJSON.message);
                } else {
                    showAlert('danger', 'Gagal membatalkan transaksi.');
                }
            }
        });
    });
});
</script>
@endsection
