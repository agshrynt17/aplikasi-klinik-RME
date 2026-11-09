@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0"><i class="fas fa-calendar-check text-primary me-2"></i> Cek Riwayat Kunjungan i-Care</h1>
</div>

<div class="alert alert-info py-2 mb-4 shadow-sm">
    <i class="fas fa-info-circle me-2"></i> Cek riwayat kunjungan pasien yang telah tercatat di sistem i-Care BPJS Kesehatan.
</div>

<div class="card-custom shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-check me-2"></i> Cek Riwayat Kunjungan</h5>
    </div>
    <div class="card-body p-4 bg-light">
        <p class="text-muted">Halaman ini digunakan untuk mengecek riwayat kunjungan pasien melalui API i-Care BPJS.</p>
    </div>
</div>
@endsection

