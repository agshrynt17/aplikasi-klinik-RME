@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-bed text-success me-2"></i> Setting Ruang Rawat (Satu Sehat)</h1>
</div>

@if(session('success'))
<div class="alert alert-success shadow-sm">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
</div>
@endif

<div class="card-custom p-4 shadow-sm border-0 rounded-3">
    <div class="alert alert-info py-2 small">
        <strong>Info:</strong> Petakan Ruangan Rawat Inap (Bed) dengan <strong>Location ID</strong> yang telah didaftarkan ke DTO Satu Sehat (Kemkes).
    </div>

    <form action="/bridging/satusehat/ruangan" method="POST">
        @csrf
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success text-dark">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="35%">Nama Ruangan (Bed)</th>
                        <th width="20%">Keterangan</th>
                        <th width="40%">Location ID (Kode IHS)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangans as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $item->nama_ruangan }}</td>
                        <td class="text-muted">{{ $item->keterangan }}</td>
                        <td>
                            <input type="text" class="form-control" name="kode_ihs[{{ $item->id }}]" value="{{ $item->kode_ihs_satusehat }}" placeholder="Location ID...">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada data ruangan rawat inap aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-success fw-bold px-5">
                <i class="fas fa-save me-2"></i> Simpan Location ID
            </button>
        </div>
    </form>
</div>
@endsection
