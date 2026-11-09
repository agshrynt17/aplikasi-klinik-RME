@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Kelola Dokumen Pelayanan Pasien</h1>
    <a href="/dokumen-pasien/index" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-md-4 mb-4">
        <!-- Informasi Pasien -->
        <div class="card-custom mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-user-injured me-2"></i>Data Pasien</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th>No. Rawat</th><td>: {{ $pendaftaran->no_rawat }}</td></tr>
                    <tr><th>No. RM</th><td>: {{ $pendaftaran->pasien->no_rm }}</td></tr>
                    <tr><th>Nama Pasien</th><td>: {{ $pendaftaran->pasien->nama_pasien }}</td></tr>
                    <tr><th>Tgl. Lahir</th><td>: {{ $pendaftaran->pasien->tanggal_lahir }}</td></tr>
                    <tr><th>Poliklinik</th><td>: {{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <!-- Form Upload Baru -->
        <div class="card-custom">
            <div class="card-header text-white" style="background-color: #607d8b;">
                <h5 class="card-title mb-0"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Dokumen Baru</h5>
            </div>
            <div class="card-body">
                <form action="/rekam-medis/dokumen" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">

                    <div class="mb-3">
                        <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_dokumen" class="form-control" placeholder="Misal: Hasil Lab Darah Lengkap" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                        <select name="jenis_dokumen" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Hasil Laboratorium">Hasil Laboratorium</option>
                            <option value="Hasil Radiologi">Hasil Radiologi</option>
                            <option value="Surat Rujukan">Surat Rujukan</option>
                            <option value="Persetujuan Tindakan (Informed Consent)">Persetujuan Tindakan (Informed Consent)</option>
                            <option value="Rekam Medis Eksternal">Rekam Medis Eksternal</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih File <span class="text-danger">*</span> <br><small class="text-muted">(Format: PDF, JPG, PNG. Max: 5MB)</small></label>
                        <input type="file" name="file_dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn text-white fw-bold w-100" style="background-color: #607d8b;">
                            <i class="fas fa-upload"></i> Upload Berkas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card-custom h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title text-dark mb-0"><i class="fas fa-folder-open text-warning me-2"></i>Daftar Dokumen Tersimpan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="35%">Nama Dokumen & Jenis</th>
                                <th width="25%">Waktu Upload</th>
                                <th width="20%" class="text-center">Keterangan</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumens as $index => $doc)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $doc->nama_dokumen }}</span><br>
                                    <span class="badge bg-secondary">{{ $doc->jenis_dokumen }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y H:i') }}<br>
                                    <small class="text-muted">Oleh: {{ $doc->uploader->name ?? 'System' }}</small>
                                </td>
                                <td><small>{{ $doc->keterangan ?? '-' }}</small></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-info text-white" title="Lihat/Download Dokumen">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteDokumen({{ $doc->id }})" title="Hapus Dokumen">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-file-excel fa-3x mb-3 text-light"></i><br>
                                    Belum ada dokumen yang diunggah untuk kunjungan ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteDokumen(id) {
    if(confirm('Apakah Anda yakin ingin menghapus dokumen ini? File yang terhapus tidak dapat dikembalikan.')) {
        fetch('/rekam-medis/dokumen/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Gagal menghapus: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data.');
        });
    }
}
</script>
@endsection
