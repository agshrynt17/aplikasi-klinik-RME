@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/rekam-medis/ppa" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        CPPT: {{ $pendaftaran->pasien->nama_pasien }}
    </h1>
    <a href="/rekam-medis/ppa/create/{{ $pendaftaran->id }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah CPPT
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card card-custom mb-4 border-secondary">
    <div class="card-body py-2">
        <div class="row text-sm">
            <div class="col-md-3"><strong>No RM:</strong> {{ $pendaftaran->pasien->no_rm }}</div>
            <div class="col-md-3"><strong>No Rawat:</strong> {{ $pendaftaran->no_rawat }}</div>
            <div class="col-md-3"><strong>Poliklinik:</strong> {{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</div>
            <div class="col-md-3"><strong>Dokter DPJP:</strong> {{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</div>
        </div>
    </div>
</div>

<div class="card-custom p-4">
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead style="background-color: #605ca8; color: white;">
                <tr>
                    <th width="15%" class="text-center">Tanggal & Jam</th>
                    <th width="20%">Profesi / PPA</th>
                    <th width="50%">SOAP & Instruksi</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ppas as $ppa)
                <tr>
                    <td class="text-center align-top">
                        <strong>{{ date('d-m-Y', strtotime($ppa->waktu)) }}</strong><br>
                        <span class="text-muted">{{ date('H:i', strtotime($ppa->waktu)) }}</span>
                    </td>
                    <td class="align-top">
                        <span class="badge bg-info mb-1">{{ $ppa->profesi ?? 'PPA' }}</span><br>
                        <strong>{{ $ppa->pegawai->nama_pegawai ?? 'Unknown' }}</strong>
                    </td>
                    <td class="align-top" style="font-size: 0.9rem;">
                        <div class="mb-2"><strong>S:</strong> <br>{!! nl2br(e($ppa->subjective)) !!}</div>
                        <div class="mb-2"><strong>O:</strong> <br>{!! nl2br(e($ppa->objective)) !!}</div>
                        <div class="mb-2"><strong>A:</strong> <br>{!! nl2br(e($ppa->assessment)) !!}</div>
                        <div class="mb-2"><strong>P:</strong> <br>{!! nl2br(e($ppa->plan)) !!}</div>
                        
                        @if($ppa->instruksi)
                        <hr class="my-2">
                        <div class="text-danger"><strong>Instruksi PPA:</strong> <br>{!! nl2br(e($ppa->instruksi)) !!}</div>
                        @endif
                    </td>
                    <td class="text-center align-top">
                        <a href="/rekam-medis/ppa/edit/{{ $ppa->id }}" class="btn btn-sm btn-outline-primary mb-1 w-100">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="hapusPpa({{ $ppa->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="fas fa-notes-medical fa-2x mb-2 d-block"></i> Belum ada catatan perkembangan pasien (CPPT).
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function hapusPpa(id) {
    if(confirm('Apakah Anda yakin ingin menghapus catatan CPPT ini?')) {
        $.ajax({
            url: '/rekam-medis/ppa/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                location.reload();
            }
        });
    }
}
</script>
@endsection
