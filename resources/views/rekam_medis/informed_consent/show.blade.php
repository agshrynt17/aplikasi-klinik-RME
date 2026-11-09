@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/informed-consent" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Kelola Informed Consent
    </h1>
</div>

<!-- Informasi Pasien -->
<div class="card card-custom mb-4">
    <div class="card-header bg-light fw-bold"><i class="fas fa-user-injured me-2"></i>Informasi Pendaftaran</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>No. Rawat:</strong> {{ $pendaftaran->no_rawat }}<br>
                <strong>No. RM:</strong> {{ $pendaftaran->pasien->no_rm }}<br>
                <strong>Nama:</strong> {{ $pendaftaran->pasien->nama_pasien }}
            </div>
            <div class="col-md-4">
                <strong>Tgl Lahir:</strong> {{ $pendaftaran->pasien->tanggal_lahir ? $pendaftaran->pasien->tanggal_lahir->format('d/m/Y') : '-' }}<br>
                <strong>Jenis Kelamin:</strong> {{ $pendaftaran->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}<br>
                <strong>Poliklinik:</strong> {{ $pendaftaran->poliklinik->nama_poli ?? '-' }}
            </div>
            <div class="col-md-4">
                <strong>Dokter:</strong> {{ $pendaftaran->dokter->nama_pegawai ?? '-' }}<br>
                <strong>Tanggal Kunjungan:</strong> {{ $pendaftaran->tanggal_daftar->format('d/m/Y') }} {{ $pendaftaran->jam_daftar }}
            </div>
        </div>
    </div>
</div>

<div id="alertBox" class="alert d-none mb-3" role="alert"></div>

<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" id="btnTambahConsent">
        <i class="fas fa-plus me-1"></i> Buat Persetujuan Tindakan
    </button>
</div>

<div class="card-custom p-4">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead style="background-color: #00a65a; color: white;">
                <tr>
                    <th width="15%">Tgl. Persetujuan</th>
                    <th width="20%">Jenis Tindakan</th>
                    <th width="15%">Pihak Menyatakan</th>
                    <th width="15%">Edukator / Dokter</th>
                    <th width="15%" class="text-center">Status</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consents as $item)
                <tr data-id="{{ $item->id }}">
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_persetujuan)->format('d/m/Y H:i') }}</td>
                    <td class="fw-bold">{{ $item->jenis_tindakan }}</td>
                    <td>
                        {{ $item->pihak_menyatakan }}<br>
                        @if($item->pihak_menyatakan != 'Pasien')
                        <small class="text-muted">{{ $item->nama_wali }} ({{ $item->hubungan_wali }})</small>
                        @endif
                    </td>
                    <td>{{ $item->dokter->nama_pegawai ?? '-' }}</td>
                    <td class="text-center">
                        @if($item->status_persetujuan == 'Setuju')
                            <span class="badge bg-success">Setuju</span>
                        @else
                            <span class="badge bg-danger">Menolak</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-info btnDetail mb-1" data-id="{{ $item->id }}" title="Detail"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-primary btnEdit mb-1" data-id="{{ $item->id }}" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger btnHapus mb-1" data-id="{{ $item->id }}" title="Hapus"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-file-signature fa-2x mb-2 d-block"></i> Belum ada dokumen informed consent.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT --}}
<div class="modal fade" id="modalConsent" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #00a65a; color: white;">
                <h5 class="modal-title" id="modalConsentLabel">
                    <i class="fas fa-file-signature me-2"></i>Form Informed Consent
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formConsent">
                @csrf
                <input type="hidden" id="consentId" name="id">
                <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Jenis Tindakan Kedokteran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jenis_tindakan" name="jenis_tindakan" placeholder="Misal: Operasi Appendiktomi, Pemasangan NGT, dll" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dokter / Edukator</label>
                            <select class="form-select" id="dokter_id" name="dokter_id">
                                <option value="">Pilih Dokter...</option>
                                @foreach($dokters as $dk)
                                    <option value="{{ $dk->id }}" {{ $pendaftaran->dokter_id == $dk->id ? 'selected' : '' }}>{{ $dk->nama_pegawai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status Persetujuan <span class="text-danger">*</span></label>
                            <select class="form-select" id="status_persetujuan" name="status_persetujuan" required>
                                <option value="Setuju">MENGIZINKAN (Setuju)</option>
                                <option value="Tolak">MENOLAK (Tidak Setuju)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12 mb-2 mt-2">
                            <h6 class="fw-bold border-bottom pb-2">Identitas Pemberi Pernyataan</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Pihak Menyatakan <span class="text-danger">*</span></label>
                            <select class="form-select" id="pihak_menyatakan" name="pihak_menyatakan" required>
                                <option value="Pasien">Pasien Sendiri</option>
                                <option value="Keluarga">Keluarga (Wali)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 div-wali" style="display: none;">
                            <label class="form-label fw-semibold">Nama Keluarga/Wali</label>
                            <input type="text" class="form-control" id="nama_wali" name="nama_wali">
                        </div>
                        <div class="col-md-4 mb-3 div-wali" style="display: none;">
                            <label class="form-label fw-semibold">Hubungan dengan Pasien</label>
                            <select class="form-select" id="hubungan_wali" name="hubungan_wali">
                                <option value="">Pilih...</option>
                                <option value="Suami">Suami</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Orang Tua">Orang Tua</option>
                                <option value="Saudara Kandung">Saudara Kandung</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-2 mt-2">
                            <h6 class="fw-bold border-bottom pb-2">Saksi & Keterangan Tambahan</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Saksi Keluarga (Opsional)</label>
                            <input type="text" class="form-control" id="saksi_keluarga" name="saksi_keluarga" placeholder="Nama saksi dari pihak keluarga">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Saksi Rumah Sakit (Opsional)</label>
                            <input type="text" class="form-control" id="saksi_rs" name="saksi_rs" placeholder="Nama perawat/petugas saksi">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Keterangan / Catatan Khusus</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan bila ada..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger').addClass('alert-' + type).text(msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }

    // Toggle form wali
    $('#pihak_menyatakan').on('change', function() {
        if($(this).val() == 'Keluarga') {
            $('.div-wali').show();
            $('#nama_wali, #hubungan_wali').prop('required', true);
        } else {
            $('.div-wali').hide();
            $('#nama_wali, #hubungan_wali').prop('required', false).val('');
        }
    });

    $('#btnTambahConsent').on('click', function () {
        $('#modalConsentLabel').html('<i class="fas fa-file-signature me-2"></i>Buat Persetujuan Tindakan (Baru)');
        $('#formConsent')[0].reset();
        $('#consentId').val('');
        $('#pihak_menyatakan').trigger('change');
        $('#modalConsent').modal('show');
    });

    $(document).on('click', '.btnEdit', function () {
        let id = $(this).data('id');
        $('#modalConsentLabel').html('<i class="fas fa-edit me-2"></i>Edit Informed Consent');
        $.get('/rekam-medis/informed-consent/edit/' + id, function (res) {
            $('#consentId').val(res.id);
            $('#jenis_tindakan').val(res.jenis_tindakan);
            $('#dokter_id').val(res.dokter_id);
            $('#status_persetujuan').val(res.status_persetujuan);
            $('#pihak_menyatakan').val(res.pihak_menyatakan).trigger('change');
            $('#nama_wali').val(res.nama_wali);
            $('#hubungan_wali').val(res.hubungan_wali);
            $('#saksi_keluarga').val(res.saksi_keluarga);
            $('#saksi_rs').val(res.saksi_rs);
            $('#keterangan').val(res.keterangan);
            $('#modalConsent').modal('show');
        });
    });

    $('#formConsent').on('submit', function (e) {
        e.preventDefault();
        let id = $('#consentId').val();
        let url = id ? '/rekam-medis/informed-consent/' + id : '/rekam-medis/informed-consent';
        let type = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: $(this).serialize(),
            success: function (res) {
                if(res.success) {
                    $('#modalConsent').modal('hide');
                    showAlert('success', res.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('danger', res.message);
                }
            },
            error: function (xhr) {
                showAlert('danger', 'Terjadi kesalahan server!');
            }
        });
    });

    $(document).on('click', '.btnHapus', function () {
        if(confirm('Hapus dokumen Informed Consent ini?')) {
            let id = $(this).data('id');
            $.ajax({
                url: '/rekam-medis/informed-consent/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if(res.success) {
                        showAlert('warning', res.message);
                        $('tr[data-id="'+id+'"]').fadeOut(300, function() { $(this).remove(); });
                    }
                }
            });
        }
    });
});
</script>
@endsection
