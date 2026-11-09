@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-stethoscope text-primary me-2"></i> Data Kunjungan (Encounter) Satu Sehat</h1>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light mb-4">
    <form action="/bridging/satusehat/kunjungan" method="GET" class="d-flex w-50">
        <label class="col-form-label fw-bold me-3">Pilih Tanggal:</label>
        <input type="date" name="tanggal" class="form-control me-2" value="{{ request('tanggal') ?: date('Y-m-d') }}">
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
    </form>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" style="font-size: 0.9rem;">
            <thead class="table-primary">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="12%">No Rawat</th>
                    <th width="18%">Pasien (No RM)</th>
                    <th width="15%">Poli / Unit</th>
                    <th width="15%">Dokter</th>
                    <th width="20%">Encounter ID (Satu Sehat)</th>
                    <th width="15%" class="text-center">Aksi (Kirim)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjungans as $index => $item)
                <tr>
                    <td class="text-center">{{ $kunjungans->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $item->no_rawat }}</td>
                    <td>{{ $item->nama_pasien }} <br><small class="text-muted">{{ $item->no_rm }}</small></td>
                    <td>{{ $item->nama_unit }}</td>
                    <td>{{ $item->nama_dokter }}</td>
                    <td class="fw-bold text-success" id="enc_text_{{ $item->id }}">
                        {{ $item->kode_ihs_satusehat ?: 'Belum Dikirim' }}
                    </td>
                    <td class="text-center">
                        @if($item->kode_ihs_satusehat)
                        <button class="btn btn-sm btn-secondary disabled" title="Sudah Dikirim">
                            <i class="fas fa-check"></i> Terkirim
                        </button>
                        @else
                        <button type="button" class="btn btn-sm btn-primary btnKirim" data-id="{{ $item->id }}">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Encounter
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Data pendaftaran/kunjungan tidak ditemukan pada tanggal tersebut.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-end mt-3">
        {{ $kunjungans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btnKirim').click(function() {
        let btn = $(this);
        let id = btn.data('id');

        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: '/bridging/satusehat/kunjungan/kirim',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', id: id },
            success: function(res) {
                if (res.success) {
                    $('#enc_text_' + id).text(res.encounter_id);
                    btn.replaceWith(`<button class="btn btn-sm btn-secondary disabled"><i class="fas fa-check"></i> Terkirim</button>`);
                    alert(res.message);
                } else {
                    btn.html(originalText).prop('disabled', false);
                    alert(res.message);
                }
            },
            error: function() {
                btn.html(originalText).prop('disabled', false);
                alert('Gagal mengirim data Encounter ke server Kemenkes. Periksa pengaturan IHS Nakes/Ruangan.');
            }
        });
    });
});
</script>
@endsection
