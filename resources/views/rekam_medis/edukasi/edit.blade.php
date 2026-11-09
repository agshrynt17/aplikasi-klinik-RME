@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Edit Data Edukasi Terintegrasi</h1>
    <a href="/rekam-medis/edukasi" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card-custom">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-user-injured me-2"></i>Data Pasien</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th width="40%">No. Rawat</th>
                        <td>: {{ $pendaftaran->no_rawat }}</td>
                    </tr>
                    <tr>
                        <th>No. RM</th>
                        <td>: {{ $pendaftaran->pasien->no_rm }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <td>: {{ $pendaftaran->pasien->nama_pasien }}</td>
                    </tr>
                    <tr>
                        <th>Tgl. Lahir</th>
                        <td>: {{ $pendaftaran->pasien->tanggal_lahir }}</td>
                    </tr>
                    <tr>
                        <th>Poliklinik</th>
                        <td>: {{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card-custom">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Form Edukasi</h5>
            </div>
            <div class="card-body">
                <form action="/rekam-medis/edukasi/{{ $edukasi->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Waktu Edukasi</label>
                            <input type="datetime-local" name="waktu_edukasi" class="form-control" 
                                value="{{ old('waktu_edukasi', date('Y-m-d\TH:i', strtotime($edukasi->waktu_edukasi))) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pemberi Edukasi (Edukator)</label>
                            <select name="edukator_id" class="form-select">
                                <option value="">-- Pilih Edukator --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}" {{ old('edukator_id', $edukasi->edukator_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pegawai }} ({{ $p->jenis_pegawai }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Materi Edukasi yang Diberikan</label>
                        <textarea name="materi_edukasi" class="form-control" rows="4" required>{{ old('materi_edukasi', $edukasi->materi_edukasi) }}</textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Penerima Edukasi</label>
                            <select name="penerima_edukasi" class="form-select">
                                <option value="Pasien" {{ old('penerima_edukasi', $edukasi->penerima_edukasi) == 'Pasien' ? 'selected' : '' }}>Pasien</option>
                                <option value="Keluarga Pasien" {{ old('penerima_edukasi', $edukasi->penerima_edukasi) == 'Keluarga Pasien' ? 'selected' : '' }}>Keluarga Pasien</option>
                                <option value="Lainnya" {{ old('penerima_edukasi', $edukasi->penerima_edukasi) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Metode Edukasi</label>
                            <select name="metode_edukasi" class="form-select">
                                <option value="Lisan / Diskusi" {{ old('metode_edukasi', $edukasi->metode_edukasi) == 'Lisan / Diskusi' ? 'selected' : '' }}>Lisan / Diskusi</option>
                                <option value="Tertulis / Leaflet" {{ old('metode_edukasi', $edukasi->metode_edukasi) == 'Tertulis / Leaflet' ? 'selected' : '' }}>Tertulis / Leaflet</option>
                                <option value="Demonstrasi Praktik" {{ old('metode_edukasi', $edukasi->metode_edukasi) == 'Demonstrasi Praktik' ? 'selected' : '' }}>Demonstrasi Praktik</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Evaluasi Tingkat Pemahaman</label>
                        <select name="evaluasi_pemahaman" class="form-select">
                            <option value="">-- Pilih Evaluasi --</option>
                            <option value="Sudah Paham / Mengerti" {{ old('evaluasi_pemahaman', $edukasi->evaluasi_pemahaman) == 'Sudah Paham / Mengerti' ? 'selected' : '' }}>Sudah Paham / Mengerti</option>
                            <option value="Paham Sebagian / Perlu Re-edukasi" {{ old('evaluasi_pemahaman', $edukasi->evaluasi_pemahaman) == 'Paham Sebagian / Perlu Re-edukasi' ? 'selected' : '' }}>Paham Sebagian / Perlu Re-edukasi</option>
                            <option value="Belum Paham" {{ old('evaluasi_pemahaman', $edukasi->evaluasi_pemahaman) == 'Belum Paham' ? 'selected' : '' }}>Belum Paham</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $edukasi->keterangan) }}</textarea>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-save"></i> Perbarui Edukasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
