@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Input Pemeriksaan Lab</h3>
        <p class="text-muted small mb-0">No. Rawat: <strong class="text-primary font-monospace">{{ $pendaftaran->no_rawat }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="/laboratorium/hasil-lab/{{ $pendaftaran->id }}" class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-medical-alt me-1"></i> Input / Hasil Lab
        </a>
        <a href="/laboratorium/transaksi-lab" class="btn btn-sm btn-secondary">
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
                        {{ $pendaftaran->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : ($pendaftaran->pasien->jenis_kelamin == 'P' ? 'Perempuan' : $pendaftaran->pasien->jenis_kelamin) }}
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
            <form id="formLab">
                @csrf
                <input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                <input type="hidden" id="penjamin_id_pasien" value="{{ $pendaftaran->penjamin_id }}">

                {{-- Pilih Pemeriksaan --}}
                <div class="mb-3">
                    <label for="master_lab_id" class="form-label fw-semibold small text-dark">
                        Pemeriksaan Lab <span class="text-danger">*</span>
                    </label>
                    <select class="form-select form-select-sm" id="master_lab_id" name="master_lab_id" required>
                        <option value="">-- Pilih Pemeriksaan Lab --</option>
                        @php $currentKat = null; @endphp
                        @foreach($master_labs as $lab)
                            @if($lab->kategori !== $currentKat)
                                @php $currentKat = $lab->kategori; @endphp
                                <optgroup label="=== {{ $lab->kategori ?? 'Lainnya' }} ===">
                            @endif
                            <option value="{{ $lab->id }}" data-satuan="{{ $lab->satuan }}">
                                [{{ $lab->kode_lab }}] {{ $lab->nama_pemeriksaan }} {{ $lab->satuan ? "($lab->satuan)" : '' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="err_master_lab_id"></div>
                </div>

                {{-- Analis --}}
                <div class="mb-3">
                    <label for="analis_id" class="form-label fw-semibold small text-dark">Petugas / Analis Lab</label>
                    <select class="form-select form-select-sm" id="analis_id" name="analis_id">
                        <option value="">-- Pilih Petugas Analis --</option>
                        @foreach($master_analis as $an)
                            <option value="{{ $an->id }}">{{ $an->nama_pegawai }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="err_analis_id"></div>
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
                        <input type="number" class="form-control form-control-sm" id="qty" name="qty" value="1" min="1">
                        <div class="invalid-feedback" id="err_qty"></div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-semibold small text-dark">Keterangan / Catatan</label>
                    <input type="text" class="form-control form-control-sm" id="keterangan" name="keterangan" placeholder="Catatan spesimen/pemeriksaan...">
                </div>

                <button type="submit" class="btn btn-cyan text-white w-100" id="btnSimpanLab">
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
                    <i class="fas fa-clipboard-list me-2 text-success"></i>Daftar Pemeriksaan Lab
                </h5>
                <span class="badge bg-light text-dark border">
                    Total: <strong id="jumlahPemeriksaanBadge">{{ count($transaksis) }}</strong> Pemeriksaan
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="tabelLab" style="border-top: 2px solid #e9ecef; border-bottom: 1px solid #e9ecef;">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">#</th>
                            <th width="30%" class="text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">Pemeriksaan Lab</th>
                            <th width="18%" class="text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">Petugas Analis</th>
                            <th width="14%" class="text-dark text-end" style="background: transparent; border-bottom: 2px solid #dee2e6;">Tarif</th>
                            <th width="6%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Qty</th>
                            <th width="14%" class="text-dark text-end" style="background: transparent; border-bottom: 2px solid #dee2e6;">Subtotal</th>
                            <th width="10%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Status</th>
                            <th width="4%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalBiaya = 0; @endphp
                        @forelse($transaksis as $index => $item)
                        @php $totalBiaya += $item->subtotal; @endphp
                        <tr data-id="{{ $item->id }}">
                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->masterLab->nama_pemeriksaan ?? '-' }}</div>
                                <small class="text-muted">
                                    <span class="font-monospace">{{ $item->masterLab->kode_lab ?? '' }}</span>
                                    @if($item->keterangan)
                                        | {{ $item->keterangan }}
                                    @endif
                                </small>
                            </td>
                            <td>{{ $item->analis->nama_pegawai ?? '-' }}</td>
                            <td class="text-end">{{ number_format($item->tarif, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <select class="form-select form-select-sm status-select" data-id="{{ $item->id }}" style="min-width: 95px; font-size: 0.8rem;">
                                    <option value="Pending" {{ $item->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Proses"  {{ $item->status == 'Proses'  ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-link text-danger p-0 btnHapusLab" data-id="{{ $item->id }}" data-nama="{{ $item->masterLab->nama_pemeriksaan ?? '-' }}" title="Hapus">
                                    <i class="fas fa-trash fa-xs"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="rowKosong">
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-vial fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                Belum ada pemeriksaan laboratorium yang diinput untuk kunjungan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="5" class="text-end fs-6">TOTAL BIAYA:</th>
                            <th class="text-end fs-6 text-dark fw-bold" id="totalBiayaLabel">
                                Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if(count($transaksis) > 0)
            <div class="mt-3 text-end">
                <a href="/laboratorium/hasil-lab/{{ $pendaftaran->id }}" class="btn btn-sm btn-success px-3">
                    <i class="fas fa-check-circle me-1"></i> Lanjut Input Hasil Pemeriksaan Lab <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus Lab --}}
<div class="modal fade" id="modalHapusTransaksiLab" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Hapus Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Hapus pemeriksaan <strong id="namaHapusLab"></strong> dari daftar kunjungan ini?
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
        let count = $('#tabelLab tbody tr[data-id]').length;
        $('#tabelLab tbody tr[data-id]').each(function () {
            let sub = $(this).find('td:eq(5)').text().replace(/[^0-9]/g, '');
            total += parseInt(sub) || 0;
        });
        $('#totalBiayaLabel').text('Rp ' + fmt(total));
        $('#jumlahPemeriksaanBadge').text(count);
    }

    function renumber() {
        let no = 1;
        $('#tabelLab tbody tr[data-id]').each(function () {
            $(this).find('td:first').text(no++);
        });
        if ($('#tabelLab tbody tr[data-id]').length === 0) {
            $('#tabelLab tbody').html(
                `<tr id="rowKosong">
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-vial fa-2x mb-2 d-block text-secondary opacity-50"></i>
                        Belum ada pemeriksaan laboratorium yang diinput untuk kunjungan ini.
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

    // Auto-get Tarif saat lab dipilih
    $('#master_lab_id').on('change', function () {
        let lab_id   = $(this).val();
        let penjamin = $('#penjamin_id_pasien').val();
        if (lab_id) {
            $.get('/laboratorium/transaksi-lab/api/get-tarif', {
                master_lab_id: lab_id,
                penjamin_id: penjamin
            }, function (res) {
                $('#tarif').val(res.harga);
            });
        } else {
            $('#tarif').val(0);
        }
    });

    // Submit Form Tambah Pemeriksaan
    $('#formLab').on('submit', function (e) {
        e.preventDefault();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        let btn = $('#btnSimpanLab');
        let ori = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: '/laboratorium/transaksi-lab',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                btn.html(ori).prop('disabled', false);

                $('#alertBox').removeClass('d-none alert-danger')
                    .addClass('alert-success').html('<i class="fas fa-check-circle me-1"></i> ' + res.message).show();
                setTimeout(() => $('#alertBox').addClass('d-none'), 3000);

                // Reset form
                $('#master_lab_id').val('');
                $('#analis_id').val('');
                $('#qty').val(1);
                $('#keterangan').val('');
                $('#tarif').val(0);

                // Tambahkan ke tabel
                $('#rowKosong').remove();
                let d = res.data;
                let namaLab   = d.master_lab ? d.master_lab.nama_pemeriksaan : '-';
                let kode      = d.master_lab ? d.master_lab.kode_lab : '';
                let ket       = d.keterangan ? ' | ' + d.keterangan : '';
                let namaAnalis = d.analis ? d.analis.nama_pegawai : '-';

                let newRow = `<tr data-id="${d.id}">
                    <td class="text-center fw-bold">0</td>
                    <td>
                        <div class="fw-bold">${namaLab}</div>
                        <small class="text-muted"><span class="font-monospace">${kode}</span>${ket}</small>
                    </td>
                    <td>${namaAnalis}</td>
                    <td class="text-end">${fmt(d.tarif)}</td>
                    <td class="text-center">${d.qty}</td>
                    <td class="text-end fw-bold text-success">${fmt(d.subtotal)}</td>
                    <td class="text-center">${buildStatusSelect(d.id, d.status)}</td>
                    <td class="text-center">
                        <button class="btn btn-link text-danger p-0 btnHapusLab" data-id="${d.id}" data-nama="${namaLab}" title="Hapus">
                            <i class="fas fa-trash fa-xs"></i>
                        </button>
                    </td>
                </tr>`;

                $('#tabelLab tbody').prepend(newRow);
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
                    $('#alertBox').removeClass('d-none alert-success')
                        .addClass('alert-danger').text('Terjadi kesalahan server!').show();
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
            url: '/laboratorium/transaksi-lab/' + id + '/status',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: status
            },
            success: function (res) {
                el.closest('td').addClass('table-success');
                setTimeout(() => el.closest('td').removeClass('table-success'), 1200);
            },
            error: function () {
                alert('Gagal memperbarui status pemeriksaan.');
            }
        });
    });

    // Modal Hapus
    $(document).on('click', '.btnHapusLab', function () {
        hapusId  = $(this).data('id');
        hapusRow = $(this).closest('tr');
        $('#namaHapusLab').text($(this).data('nama'));
        $('#modalHapusTransaksiLab').modal('show');
    });

    $('#btnKonfirmasiHapus').on('click', function () {
        $.ajax({
            url: '/laboratorium/transaksi-lab/' + hapusId,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#modalHapusTransaksiLab').modal('hide');
                hapusRow.fadeOut(300, function () {
                    $(this).remove();
                    renumber();
                    hitungTotal();
                });
            }
        });
    });
});
</script>
@endsection
