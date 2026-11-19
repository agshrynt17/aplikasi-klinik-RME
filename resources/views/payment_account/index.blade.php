@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="fw-normal mb-0" style="color: #333; font-size: 24px;">Akun Channel Pembayaran</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 12px; background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Akun Channel Pembayaran</a></li>
            </ol>
        </nav>
    </div>
    
    <!-- Orange accent line below header -->
    <div style="height: 3px; background-color: #f39c12; margin-bottom: 18px; width: 100%;"></div>

    <!-- Alert Box -->
    <div id="alertBox" class="alert d-none mb-3" role="alert"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Card 1: Pencarian Data -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 3px; background-color: #ffffff;">
        <div class="card-body p-3">
            <div class="fw-bold mb-3" style="font-size: 13px; color: #333;">Pencarian Data</div>
            <form method="GET" action="/payment-account/index" id="filterForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label text-dark fw-bold mb-1" style="font-size: 12px;">Nama Channel / Bank</label>
                        <input type="text" name="nama_akun" class="form-control form-control-sm" value="{{ request('nama_akun') }}" style="font-size: 12px;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-dark fw-bold mb-1" style="font-size: 12px;">Nama Pemilik</label>
                        <input type="text" name="atas_nama" class="form-control form-control-sm" value="{{ request('atas_nama') }}" style="font-size: 12px;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-dark fw-bold mb-1" style="font-size: 12px;">No Akun</label>
                        <input type="text" name="no_rekening" class="form-control form-control-sm" value="{{ request('no_rekening') }}" style="font-size: 12px;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-dark fw-bold mb-1" style="font-size: 12px;">Status</label>
                        <select name="is_active" class="form-select form-select-sm" style="font-size: 12px;">
                            <option value="">--Pilih--</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn-sm text-white px-3 fw-bold shadow-sm" style="background-color: #337ab7; border-color: #2e6da4; font-size: 12px;">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                            <a href="/payment-account/index" class="btn btn-sm text-white px-3 fw-bold shadow-sm" style="background-color: #f39c12; border-color: #e08e0b; font-size: 12px;">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                        <div>
                            <a href="/payment-account/create" class="btn btn-sm text-white px-3 fw-bold shadow-sm" style="background-color: #337ab7; border-color: #2e6da4; font-size: 12px;">
                                <i class="fas fa-plus me-1"></i> Tambah
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card 2: Table Result with Cyan Top Border -->
    <div class="card shadow-sm border-0 mb-4" style="border-top: 3px solid #00c0ef !important; border-radius: 3px; background-color: #ffffff;">
        <div class="card-body p-3">
            <!-- Counter Text -->
            <div class="mb-2 text-dark" style="font-size: 13px;">
                Menampilkan <strong>1-{{ $data->count() }}</strong> dari <strong>{{ $data->count() }}</strong> item.
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0" id="tabelPaymentAccount" style="font-size: 12px; border-color: #e9ecef;">
                    <thead style="background-color: #fdfdfd; border-bottom: 2px solid #dee2e6;">
                        <tr style="color: #333;">
                            <th style="width: 45px;" class="text-center">#</th>
                            <th style="min-width: 180px; color: #337ab7;">Nama Channel / Bank</th>
                            <th style="min-width: 250px;">Nama Pemilik</th>
                            <th style="min-width: 180px;">No Akun</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 100px;" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        <tr data-id="{{ $item->id }}">
                            <td class="text-center fw-normal" style="color: #555;">{{ $index + 1 }}</td>
                            <td class="fw-normal" style="color: #333;">{{ $item->nama_akun }}</td>
                            <td style="color: #333;">{{ $item->atas_nama ?? '-' }}</td>
                            <td style="color: #333;">{{ $item->no_rekening ?? '-' }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge py-1 px-2" style="background-color: #00a65a; font-size: 11px; font-weight: normal; border-radius: 2px;">Aktif</span>
                                @else
                                    <span class="badge py-1 px-2" style="background-color: #dd4b39; font-size: 11px; font-weight: normal; border-radius: 2px;">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <!-- Eye Icon (View) -->
                                    <button type="button" class="btn btn-link p-0 text-primary btnDetail" data-id="{{ $item->id }}" title="Lihat Detail" style="font-size: 14px; text-decoration: none;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- Pencil Icon (Edit) -->
                                    <a href="/payment-account/edit/{{ $item->id }}" class="text-primary" title="Ubah" style="font-size: 14px; text-decoration: none;">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Trash Icon (Delete) -->
                                    <button type="button" class="btn btn-link p-0 text-danger btnHapus" data-id="{{ $item->id }}" data-nama="{{ $item->nama_akun }} - {{ $item->no_rekening }}" title="Hapus" style="font-size: 14px; text-decoration: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data Akun Channel Pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Akun -->
<div class="modal fade" id="modalDetailAkun" tabindex="-1" aria-labelledby="modalDetailAkunLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #00c0ef; border-radius: 3px 3px 0 0;">
                <h5 class="modal-title fs-6 fw-bold" id="modalDetailAkunLabel">
                    <i class="fas fa-info-circle me-2"></i> Detail Akun Channel Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 13px;">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-bold text-muted" style="width: 160px;">Nama Channel / Bank</td>
                            <td style="width: 10px;">:</td>
                            <td class="fw-bold text-dark" id="detailNamaAkun">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Nama Pemilik</td>
                            <td>:</td>
                            <td id="detailAtasNama">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">No Akun</td>
                            <td>:</td>
                            <td id="detailNoRekening">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Status</td>
                            <td>:</td>
                            <td id="detailStatus">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Jenis Akun</td>
                            <td>:</td>
                            <td id="detailJenis">-</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Kode Akun</td>
                            <td>:</td>
                            <td id="detailKode">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-light py-2">
                <a href="#" id="btnEditFromDetail" class="btn btn-sm btn-primary px-3">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapusAkun" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> Hapus Akun
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3" style="font-size: 13px;">
                Apakah Anda yakin ingin menghapus akun channel pembayaran <br><strong id="namaHapusAkun" class="text-danger"></strong>?
            </div>
            <div class="modal-footer bg-light py-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-danger px-3" id="btnKonfirmasiHapus">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let currentHapusId = null;

    // View detail modal
    $(document).on('click', '.btnDetail', function () {
        let id = $(this).data('id');
        $.get('/payment-account/' + id, function (res) {
            $('#detailNamaAkun').text(res.nama_akun || '-');
            $('#detailAtasNama').text(res.atas_nama || '-');
            $('#detailNoRekening').text(res.no_rekening || '-');
            $('#detailJenis').text(res.jenis_akun || '-');
            $('#detailKode').text(res.kode_akun || '-');
            
            if (res.is_active) {
                $('#detailStatus').html('<span class="badge" style="background-color: #00a65a;">Aktif</span>');
            } else {
                $('#detailStatus').html('<span class="badge" style="background-color: #dd4b39;">Tidak Aktif</span>');
            }

            $('#btnEditFromDetail').attr('href', '/payment-account/edit/' + res.id);
            $('#modalDetailAkun').modal('show');
        });
    });

    // Click Delete
    $(document).on('click', '.btnHapus', function () {
        currentHapusId = $(this).data('id');
        $('#namaHapusAkun').text($(this).data('nama'));
        $('#modalHapusAkun').modal('show');
    });

    // Confirm Delete
    $('#btnKonfirmasiHapus').on('click', function () {
        if (!currentHapusId) return;

        $.ajax({
            url: '/payment-account/delete/' + currentHapusId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#modalHapusAkun').modal('hide');
                showAlert('success', res.message || 'Akun berhasil dihapus.');
                $(`tr[data-id="${currentHapusId}"]`).fadeOut(300, function () {
                    $(this).remove();
                });
            },
            error: function () {
                alert('Gagal menghapus data akun.');
            }
        });
    });

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
            .addClass('alert-' + type).html('<i class="fas fa-check-circle me-1"></i> ' + msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }
});
</script>
@endsection
