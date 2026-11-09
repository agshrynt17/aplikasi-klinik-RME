@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/praanestesi/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Edit Pengkajian Pra Anestesi
    </h1>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/rekam-medis/pra-anestesi/{{ $praanestesi->id }}" method="POST">
    @csrf
    @method('PUT')
    
    <input type="hidden" name="dokter_id" value="{{ $praanestesi->dokter_id }}">

    <!-- Informasi Pasien -->
    <div class="card card-custom mb-3">
        <div class="card-header bg-light fw-bold"><i class="fas fa-user-injured me-2 text-primary"></i>Informasi Pasien</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>No. RM / Nama:</strong><br>
                    {{ $pendaftaran->pasien->no_rm }} - {{ $pendaftaran->pasien->nama_pasien }}
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Waktu Pengkajian</label>
                    <input type="datetime-local" class="form-control" name="waktu_pengkajian" value="{{ \Carbon\Carbon::parse($praanestesi->waktu_pengkajian)->format('Y-m-d\TH:i') }}" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pasien -->
    <div class="card card-custom mb-3 border-info">
        <div class="card-header bg-info text-white fw-bold"><i class="fas fa-history me-2"></i>Anamnesis & Riwayat</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Riwayat Alergi (Obat/Makanan)</label>
                    <textarea class="form-control" name="riwayat_alergi" rows="2">{{ $praanestesi->riwayat_alergi }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Riwayat Penyakit Sistemik (Asma, Jantung, HT, DM)</label>
                    <textarea class="form-control" name="riwayat_penyakit" rows="2">{{ $praanestesi->riwayat_penyakit }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Riwayat Operasi Sebelumnya</label>
                    <textarea class="form-control" name="riwayat_operasi" rows="2">{{ $praanestesi->riwayat_operasi }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Riwayat Anestesi Sebelumnya (Komplikasi dll)</label>
                    <textarea class="form-control" name="riwayat_anestesi" rows="2">{{ $praanestesi->riwayat_anestesi }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Fisik & Penunjang -->
    <div class="card card-custom mb-3 border-success">
        <div class="card-header bg-success text-white fw-bold"><i class="fas fa-heartbeat me-2"></i>Pemeriksaan Fisik & Penunjang</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">Tensi (mmHg)</label>
                    <input type="text" name="tensi" class="form-control" value="{{ $praanestesi->tensi }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">Nadi (x/mnt)</label>
                    <input type="text" name="nadi" class="form-control" value="{{ $praanestesi->nadi }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">Suhu (°C)</label>
                    <input type="text" name="suhu" class="form-control" value="{{ $praanestesi->suhu }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">RR (x/mnt)</label>
                    <input type="text" name="rr" class="form-control" value="{{ $praanestesi->rr }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">TB (cm)</label>
                    <input type="text" name="tb" class="form-control" value="{{ $praanestesi->tb }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">BB (kg)</label>
                    <input type="text" name="bb" class="form-control" value="{{ $praanestesi->bb }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Penyulit Jalan Napas (Mallampati, dll)</label>
                    <input type="text" name="jalan_napas" class="form-control" value="{{ $praanestesi->jalan_napas }}">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Pemeriksaan Fisik Lain (Jantung, Paru)</label>
                    <textarea class="form-control" name="pemeriksaan_fisik" rows="2">{{ $praanestesi->pemeriksaan_fisik }}</textarea>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">Pemeriksaan Penunjang (Lab/EKG/Ro)</label>
                    <textarea class="form-control" name="pemeriksaan_penunjang" rows="2">{{ $praanestesi->pemeriksaan_penunjang }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Kesimpulan Resiko ASA -->
    <div class="card card-custom mb-3 border-danger">
        <div class="card-header bg-danger text-white fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Klasifikasi ASA & Rencana Anestesi</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">ASA Score <span class="text-danger">*</span></label>
                    <select class="form-select border-danger" name="asa_score" required>
                        <option value="ASA 1" {{ $praanestesi->asa_score == 'ASA 1' ? 'selected' : '' }}>ASA 1 (Normal sehat)</option>
                        <option value="ASA 2" {{ $praanestesi->asa_score == 'ASA 2' ? 'selected' : '' }}>ASA 2 (Penyakit sistemik ringan)</option>
                        <option value="ASA 3" {{ $praanestesi->asa_score == 'ASA 3' ? 'selected' : '' }}>ASA 3 (Penyakit sistemik berat)</option>
                        <option value="ASA 4" {{ $praanestesi->asa_score == 'ASA 4' ? 'selected' : '' }}>ASA 4 (Penyakit sistemik mengancam nyawa)</option>
                        <option value="ASA 5" {{ $praanestesi->asa_score == 'ASA 5' ? 'selected' : '' }}>ASA 5 (Harapan hidup < 24 jam)</option>
                        <option value="ASA 1 E" {{ $praanestesi->asa_score == 'ASA 1 E' ? 'selected' : '' }}>ASA 1 E (Darurat)</option>
                        <option value="ASA 2 E" {{ $praanestesi->asa_score == 'ASA 2 E' ? 'selected' : '' }}>ASA 2 E (Darurat)</option>
                        <option value="ASA 3 E" {{ $praanestesi->asa_score == 'ASA 3 E' ? 'selected' : '' }}>ASA 3 E (Darurat)</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Rencana Teknik Anestesi <span class="text-danger">*</span></label>
                    <select class="form-select border-danger" name="rencana_anestesi" required>
                        <option value="General Anesthesia (GA)" {{ $praanestesi->rencana_anestesi == 'General Anesthesia (GA)' ? 'selected' : '' }}>General Anesthesia (GA)</option>
                        <option value="Spinal Anesthesia (SAB)" {{ $praanestesi->rencana_anestesi == 'Spinal Anesthesia (SAB)' ? 'selected' : '' }}>Spinal Anesthesia (SAB)</option>
                        <option value="Epidural" {{ $praanestesi->rencana_anestesi == 'Epidural' ? 'selected' : '' }}>Epidural</option>
                        <option value="Lokal Anestesi" {{ $praanestesi->rencana_anestesi == 'Lokal Anestesi' ? 'selected' : '' }}>Lokal Anestesi</option>
                        <option value="TIVA" {{ $praanestesi->rencana_anestesi == 'TIVA' ? 'selected' : '' }}>TIVA</option>
                        <option value="Lainnya" {{ $praanestesi->rencana_anestesi == 'Lainnya' ? 'selected' : '' }}>Lainnya...</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Catatan / Pre-medikasi Khusus</label>
                    <textarea class="form-control" name="keterangan" rows="2">{{ $praanestesi->keterangan }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="button" class="btn btn-outline-danger me-2" onclick="hapusPraAnestesi()">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
        <a href="/praanestesi/index" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-danger"><i class="fas fa-save me-1"></i> Perbarui Pra Anestesi</button>
    </div>
</form>

<script>
function hapusPraAnestesi() {
    if(confirm('Apakah Anda yakin ingin menghapus data pengkajian pra anestesi ini?')) {
        $.ajax({
            url: '/rekam-medis/pra-anestesi/{{ $praanestesi->id }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                window.location.href = '/praanestesi/index';
            }
        });
    }
}
</script>
@endsection
