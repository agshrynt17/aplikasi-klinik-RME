@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-users text-primary me-2"></i> Data Pasien Satu Sehat</h1>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3 bg-light mb-4">
    <form action="/bridging/satusehat/pasien" method="GET" class="d-flex w-50">
        <input type="text" name="q" class="form-control me-2" placeholder="Cari NIK, No RM, atau Nama Pasien..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">No RM</th>
                    <th width="20%">NIK</th>
                    <th width="25%">Nama Pasien</th>
                    <th width="20%">IHS ID (Satu Sehat)</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $index => $item)
                <tr>
                    <td class="text-center">{{ $pasiens->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $item->no_rm }}</td>
                    <td>{{ $item->nik ?: '-' }}</td>
                    <td class="fw-bold">{{ $item->nama_pasien }}</td>
                    <td class="fw-bold text-success" id="ihs_text_{{ $item->nik }}">
                        {{ $item->kode_ihs_satusehat ?: 'Belum Ada' }}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-info text-white btnSync" data-nik="{{ $item->nik }}">
                            <i class="fas fa-sync-alt me-1"></i> Cari IHS ID
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Data pasien tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-end mt-3">
        {{ $pasiens->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btnSync').click(function() {
        let btn = $(this);
        let nik = btn.data('nik');

        if (!nik || nik == '') {
            alert('Gagal: Pasien ini tidak memiliki NIK yang valid.');
            return;
        }

        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: '/bridging/satusehat/pasien/sync',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', nik: nik },
            success: function(res) {
                btn.html(originalText).prop('disabled', false);
                if (res.success) {
                    $('#ihs_text_' + nik).text(res.ihs);
                    alert('IHS ID Ditemukan: ' + res.ihs);
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                btn.html(originalText).prop('disabled', false);
                alert('Gagal menyinkronkan data pasien. Periksa koneksi ke server Kemenkes.');
            }
        });
    });
});
</script>
@endsection
