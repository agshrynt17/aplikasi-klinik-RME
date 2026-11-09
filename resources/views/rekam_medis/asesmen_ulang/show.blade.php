@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/asesmen-ulang" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Catatan Perkembangan Pasien Terintegrasi (CPPT)
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
    <button class="btn btn-cyan text-white" id="btnTambahCPPT">
        <i class="fas fa-plus me-1"></i> Tambah Catatan (SOAP)
    </button>
</div>

<div class="card-custom p-4">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="tabelCPPT">
            <thead style="background-color: #00a65a; color: white;">
                <tr>
                    <th width="12%">Tgl & Jam</th>
                    <th width="20%">Profesi / Bagian</th>
                    <th width="58%">Hasil Asesmen Pasien (SOAP)</th>
                    <th width="10%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asesmens as $item)
                <tr data-id="{{ $item->id }}">
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_asesmen)->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong>{{ $item->pegawai->nama_pegawai ?? 'Petugas' }}</strong><br>
                        <small class="text-muted">{{ $item->pegawai->profesi ?? '-' }}</small>
                    </td>
                    <td>
                        <div class="mb-1"><strong>S:</strong> {{ $item->subjektif }}</div>
                        <div class="mb-1"><strong>O:</strong> {{ $item->objektif }}</div>
                        <div class="mb-1"><strong>A:</strong> {{ $item->asesmen }}</div>
                        <div class="mb-1"><strong>P:</strong> {{ $item->plan }}</div>
                        @if($item->instruksi_medis)
                        <div class="mt-2 text-primary"><strong>Instruksi:</strong> {{ $item->instruksi_medis }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btnEdit mb-1" data-id="{{ $item->id }}" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger btnHapus mb-1" data-id="{{ $item->id }}" title="Hapus"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="fas fa-notes-medical fa-2x mb-2 d-block"></i> Belum ada CPPT.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT CPPT --}}
<div class="modal fade" id="modalCPPT" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #00a65a; color: white;">
                <h5 class="modal-title" id="modalCPPTLabel">
                    <i class="fas fa-file-medical me-2"></i>Catatan Perkembangan Pasien (SOAP)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCPPT">
                @csrf
                <input type="hidden" id="cpptId" name="id">
                <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">
                <input type="hidden" name="pegawai_id" value="{{ auth()->user()->pegawai_id ?? 1 }}"> <!-- Simulasi pegawai_id login -->

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subjektif (S) <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="subjektif" name="subjektif" rows="2" placeholder="Keluhan pasien..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Objektif (O) <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="objektif" name="objektif" rows="2" placeholder="Hasil pemeriksaan fisik/penunjang..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Asesmen (A) <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="asesmen" name="asesmen" rows="2" placeholder="Diagnosis / Masalah..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plan (P) <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="plan" name="plan" rows="2" placeholder="Rencana tindakan/terapi..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Instruksi Medis (Opsional)</label>
                        <textarea class="form-control" id="instruksi_medis" name="instruksi_medis" rows="2" placeholder="Instruksi PPA..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-cyan text-white">
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
        setTimeout(() => $('#alertBox').addClass('d-none'), 3000);
    }

    $('#btnTambahCPPT').on('click', function () {
        $('#modalCPPTLabel').html('<i class="fas fa-file-medical me-2"></i>Tambah CPPT (SOAP)');
        $('#formCPPT')[0].reset();
        $('#cpptId').val('');
        $('#modalCPPT').modal('show');
    });

    $(document).on('click', '.btnEdit', function () {
        let id = $(this).data('id');
        $('#modalCPPTLabel').html('<i class="fas fa-edit me-2"></i>Edit CPPT (SOAP)');
        $.get('/rekam-medis/asesmen-ulang/edit/' + id, function (res) {
            $('#cpptId').val(res.id);
            $('#subjektif').val(res.subjektif);
            $('#objektif').val(res.objektif);
            $('#asesmen').val(res.asesmen);
            $('#plan').val(res.plan);
            $('#instruksi_medis').val(res.instruksi_medis);
            $('#modalCPPT').modal('show');
        });
    });

    $('#formCPPT').on('submit', function (e) {
        e.preventDefault();
        let id = $('#cpptId').val();
        let url = id ? '/rekam-medis/asesmen-ulang/' + id : '/rekam-medis/asesmen-ulang';
        let type = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: $(this).serialize(),
            success: function (res) {
                if(res.success) {
                    $('#modalCPPT').modal('hide');
                    showAlert('success', res.message);
                    // Reload the page to reflect new data simply
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
        if(confirm('Hapus data CPPT ini?')) {
            let id = $(this).data('id');
            $.ajax({
                url: '/rekam-medis/asesmen-ulang/' + id,
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
