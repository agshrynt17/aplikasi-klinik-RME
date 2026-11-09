@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Input Transaksi Radiologi</h3>
        <p class="text-muted small mb-0">No. Rawat: <strong class="text-primary font-monospace">{{ $pendaftaran->no_rawat }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="/radiologi/hasil/{{ $pendaftaran->id }}" class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-medical-alt me-1"></i> Input / Hasil Radiologi
        </a>
        <a href="/radiologi/transaksi" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div id="alertBox" class="alert d-none mb-3" role="alert"></div>

{{-- Info Pasien --}}
<div class="card border-0 shadow-sm rounded-3 mb-3" style="border-top: 3px solid #00c0ef !important;">
    <div class="card-body bg-light p-3">
        <div class="row align-items-center">
            <div class="col-md-4 border-end">
                <small class="text-muted d-block">Pasien</small>
                <div class="fw-bold fs-5 text-dark">{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</div>
                <div>
                    No. RM: <span class="badge bg-secondary font-monospace">{{ $pendaftaran->pasien->no_rm ?? '-' }}</span>
                    &nbsp;
                    <span class="badge bg-light text-dark border">
                        {{ ($pendaftaran->pasien->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : (($pendaftaran->pasien->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : ($pendaftaran->pasien->jenis_kelamin ?? '-')) }}
                    </span>
                </div>
            </div>
            <div class="col-md-3 border-end">
                <small class="text-muted d-block">Poliklinik / Dokter Perujuk</small>
                <div class="fw-bold text-primary">{{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</div>
                <small class="text-muted">{{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</small>
            </div>
            <div class="col-md-3 border-end">
                <small class="text-muted d-block">Penjamin & Tanggal</small>
                <div class="fw-bold text-dark">
                    @if($pendaftaran->penjamin)
                        <span class="badge bg-info text-dark">{{ $pendaftaran->penjamin->nama_penjamin }}</span>
                    @else
                        <span class="badge bg-primary">UMUM</span>
                    @endif
                </div>
                <small class="text-muted">Tgl: {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d/m/Y') }}</small>
            </div>
            <div class="col-md-2 text-center">
                <small class="text-muted d-block mb-1">Status Rawat</small>
                <span class="badge bg-primary fs-6">{{ $pendaftaran->status_periksa }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Form Input Pemeriksaan (Kiri) --}}
    <div class="col-md-4">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 h-100">
            <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">
                <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Pemeriksaan
            </h5>
            <form id="formRadiologi">
                @csrf
                <input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                <input type="hidden" id="penjamin_id_pasien" value="{{ $pendaftaran->penjamin_id }}">

                {{-- Pilih Pemeriksaan --}}
                <div class="mb-3">
                    <label for="master_radiologi_id" class="form-label fw-semibold small text-dark">
                        Pemeriksaan Radiologi <span class="text-danger">*</span>
                    </label>
                    <select class="form-select form-select-sm" id="master_radiologi_id" name="master_radiologi_id" required>
                        <option value="">-- Pilih Pemeriksaan Radiologi --</option>
                        @php $currentKat = null; @endphp
                        @foreach($master_radiologis as $rad)
                            @if($rad->kategori !== $currentKat)
                                @php $currentKat = $rad->kategori; @endphp
                                <optgroup label="=== {{ $rad->kategori ?? 'Lain-lain' }} ===">
                            @endif
                            <option value="{{ $rad->id }}">
                                [{{ $rad->kode_rad }}] {{ $rad->nama_pemeriksaan }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="err_master_radiologi_id"></div>
                </div>

                {{-- Radiografer --}}
                <div class="mb-3">
                    <label for="radiografer_id" class="form-label fw-semibold small text-dark">Petugas Radiografer</label>
                    <select class="form-select form-select-sm" id="radiografer_id" name="radiografer_id">
                        <option value="">-- Pilih Radiografer --</option>
                        @foreach($radiografers as $rg)
                            <option value="{{ $rg->id }}">{{ $rg->nama_pegawai }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="err_radiografer_id"></div>
                </div>

                {{-- Tarif & Qty --}}
                <div class="row mb-3">
                    <div class="col-8">
                        <label for="tarif" class="form-label fw-semibold small text-dark">Tarif (Rp)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="tarif" name="tarif" value="0" min="0">
                        </div>
                        <div class="invalid-feedback" id="err_tarif"></div>
                    </div>
                    <div class="col-4">
                        <label for="qty" class="form-label fw-semibold small text-dark">Qty</label>
                        <input type="number" class="form-control form-control-sm" id="qty" name="qty" value="1" min="1" readonly>
                    </div>
                </div>

                {{-- Keterangan / Klinis --}}
                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-semibold small text-dark">Indikasi Klinis / Catatan</label>
                    <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="2" placeholder="Suspect fraktur, diagnosa pengantar, catatan khusus..."></textarea>
                </div>

                <button type="submit" class="btn btn-cyan text-white w-100 shadow-sm" id="btnSimpanRadiologi">
                    <i class="fas fa-plus me-1"></i> Tambahkan ke Daftar
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Pemeriksaan (Kanan) --}}
    <div class="col-md-8">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-x-ray me-2 text-primary"></i>Daftar Pemeriksaan Radiologi
                </h5>
                <span class="badge bg-light text-dark border">
                    Total: <strong id="jumlahPemeriksaanBadge">{{ count($transaksis) }}</strong> Tindakan
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="tabelRadiologi" style="border-top: 2px solid #e9ecef; border-bottom: 1px solid #e9ecef;">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">#</th>
                            <th width="32%" class="text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">Pemeriksaan Radiologi</th>
                            <th width="20%" class="text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">Radiografer</th>
                            <th width="16%" class="text-dark text-end" style="background: transparent; border-bottom: 2px solid #dee2e6;">Tarif (Rp)</th>
                            <th width="16%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Status</th>
                            <th width="6%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalBiaya = 0; @endphp
                        @forelse($transaksis as $index => $item)
                        @php $totalBiaya += $item->subtotal; @endphp
                        <tr data-id="{{ $item->id }}">
                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->masterRadiologi->nama_pemeriksaan ?? '-' }}</div>
                                <small class="text-muted">
                                    <span class="font-monospace text-primary fw-semibold">{{ $item->masterRadiologi->kode_rad ?? '' }}</span>
                                    @if($item->keterangan)
                                        | <i class="fas fa-notes-medical me-1"></i>{{ $item->keterangan }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                <span class="text-dark">{{ $item->radiografer->nama_pegawai ?? '-' }}</span>
                            </td>
                            <td class="text-end fw-bold text-success">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <select class="form-select form-select-sm status-select" data-id="{{ $item->id }}" style="min-width: 95px; font-size: 0.8rem;">
                                    <option value="Pending" {{ $item->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Proses"  {{ $item->status == 'Proses'  ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-link text-danger p-0 btnHapusRadiologi" data-id="{{ $item->id }}" data-nama="{{ $item->masterRadiologi->nama_pemeriksaan ?? '-' }}" data-status="{{ $item->status }}" title="Hapus">
                                    <i class="fas fa-trash fa-xs"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="rowKosong">
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-x-ray fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                Belum ada pemeriksaan radiologi yang diinput untuk kunjungan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="3" class="text-end fs-6">TOTAL BIAYA:</th>
                            <th class="text-end fs-6 text-dark fw-bold" id="totalBiayaLabel">
                                Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i> Ubah status secara langsung melalui dropdown untuk memulai proses atau menyelesaikan tindakan.
                </span>
                <a href="/radiologi/hasil/{{ $pendaftaran->id }}" class="btn btn-sm btn-success px-3 shadow-sm {{ count($transaksis) > 0 ? '' : 'd-none' }}" id="btnLanjutHasil">
                    <i class="fas fa-check-circle me-1"></i> Lanjut Input Hasil Radiologi <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus Radiologi --}}
<div class="modal fade" id="modalHapusTransaksiRadiologi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Hapus Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Hapus pemeriksaan <strong id="namaHapusRadiologi"></strong> dari daftar kunjungan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnKonfirmasiHapus">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let hapusId  = null;
    let hapusRow = null;

    function fmt(angka) {
        return parseInt(angka || 0).toLocaleString('id-ID');
    }

    function hitungTotal() {
        let total = 0;
        let count = $('#tabelRadiologi tbody tr[data-id]').length;
        $('#tabelRadiologi tbody tr[data-id]').each(function () {
            let sub = $(this).find('td:eq(3)').text().replace(/[^0-9]/g, '');
            total += parseInt(sub) || 0;
        });
        $('#totalBiayaLabel').text('Rp ' + fmt(total));
        $('#jumlahPemeriksaanBadge').text(count);

        if (count > 0) {
            $('#btnLanjutHasil').removeClass('d-none');
        } else {
            $('#btnLanjutHasil').addClass('d-none');
        }
    }

    function renumber() {
        let no = 1;
        $('#tabelRadiologi tbody tr[data-id]').each(function () {
            $(this).find('td:first').text(no++);
        });
        if ($('#tabelRadiologi tbody tr[data-id]').length === 0) {
            $('#tabelRadiologi tbody').html(
                `<tr id="rowKosong">
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-x-ray fa-2x mb-2 d-block text-secondary opacity-50"></i>
                        Belum ada pemeriksaan radiologi yang diinput untuk kunjungan ini.
                    </td>
                </tr>`
            );
        }
    }

    function buildStatusSelect(id, current) {
        const opts = ['Pending', 'Proses', 'Selesai'].map(s =>
            `<option value="${s}" ${s === current ? 'selected' : ''}>${s}</option>`
        ).join('');
        return `<select class="form-select form-select-sm status-select" data-id="${id}" style="min-width: 95px; font-size: 0.8rem;">${opts}</select>`;
    }

    // Auto-get Tarif saat master radiologi dipilih
    $('#master_radiologi_id').on('change', function () {
        let rad_id   = $(this).val();
        let penjamin = $('#penjamin_id_pasien').val();
        if (rad_id) {
            $.get('/radiologi/transaksi/api/get-tarif', {
                master_radiologi_id: rad_id,
                penjamin_id: penjamin
            }, function (res) {
                $('#tarif').val(res.harga);
            });
        } else {
            $('#tarif').val(0);
        }
    });

    // Submit Form Tambah Pemeriksaan
    $('#formRadiologi').on('submit', function (e) {
        e.preventDefault();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        let btn = $('#btnSimpanRadiologi');
        let ori = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: '/radiologi/transaksi',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                btn.html(ori).prop('disabled', false);

                $('#alertBox').removeClass('d-none alert-danger')
                    .addClass('alert-success').html('<i class="fas fa-check-circle me-1"></i> ' + res.message).show();
                setTimeout(() => $('#alertBox').addClass('d-none'), 3000);

                // Reset form
                $('#master_radiologi_id').val('');
                $('#radiografer_id').val('');
                $('#keterangan').val('');
                $('#tarif').val(0);

                // Tambahkan ke tabel
                $('#rowKosong').remove();
                let d = res.data;
                let namaRad   = d.master_radiologi ? d.master_radiologi.nama_pemeriksaan : '-';
                let kode      = d.master_radiologi ? d.master_radiologi.kode_rad : '';
                let ket       = d.keterangan ? ' | <i class="fas fa-notes-medical me-1"></i>' + d.keterangan : '';
                let namaRadgr = d.radiografer ? d.radiografer.nama_pegawai : '-';

                let newRow = `<tr data-id="${d.id}">
                    <td class="text-center fw-bold">0</td>
                    <td>
                        <div class="fw-bold text-dark">${namaRad}</div>
                        <small class="text-muted"><span class="font-monospace text-primary fw-semibold">${kode}</span>${ket}</small>
                    </td>
                    <td><span class="text-dark">${namaRadgr}</span></td>
                    <td class="text-end fw-bold text-success">${fmt(d.subtotal)}</td>
                    <td class="text-center">${buildStatusSelect(d.id, d.status)}</td>
                    <td class="text-center">
                        <button class="btn btn-link text-danger p-0 btnHapusRadiologi" data-id="${d.id}" data-nama="${namaRad}" data-status="${d.status}" title="Hapus">
                            <i class="fas fa-trash fa-xs"></i>
                        </button>
                    </td>
                </tr>`;

                $('#tabelRadiologi tbody').prepend(newRow);
                renumber();
                hitungTotal();
            },
            error: function (xhr) {
                btn.html(ori).prop('disabled', false);
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $('#' + field).addClass('is-invalid');
                        $('#err_' + field).text(messages[0]);
                    });
                } else {
                    let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan server!';
                    $('#alertBox').removeClass('d-none alert-success')
                        .addClass('alert-danger').text(msg).show();
                }
            }
        });
    });

    // Update Status Pemeriksaan (Pending / Proses / Selesai)
    $(document).on('change', '.status-select', function () {
        let id     = $(this).data('id');
        let status = $(this).val();
        let el     = $(this);

        $.ajax({
            url: '/radiologi/transaksi/' + id + '/status',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: status
            },
            success: function (res) {
                el.closest('tr').find('.btnHapusRadiologi').data('status', status);
                el.closest('td').addClass('table-success');
                setTimeout(() => el.closest('td').removeClass('table-success'), 1200);
            },
            error: function () {
                alert('Gagal memperbarui status pemeriksaan.');
            }
        });
    });

    // Modal Hapus
    $(document).on('click', '.btnHapusRadiologi', function () {
        let status = $(this).data('status');
        if (status === 'Selesai') {
            alert('Pemeriksaan dengan status Selesai tidak dapat dihapus!');
            return;
        }
        hapusId  = $(this).data('id');
        hapusRow = $(this).closest('tr');
        $('#namaHapusRadiologi').text($(this).data('nama'));
        $('#modalHapusTransaksiRadiologi').modal('show');
    });

    $('#btnKonfirmasiHapus').on('click', function () {
        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Hapus...');

        $.ajax({
            url: '/radiologi/transaksi/' + hapusId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Hapus');
                $('#modalHapusTransaksiRadiologi').modal('hide');
                hapusRow.fadeOut(300, function () {
                    $(this).remove();
                    renumber();
                    hitungTotal();
                });
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Hapus');
                let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menghapus data.';
                alert(msg);
            }
        });
    });
});
</script>
@endsection
