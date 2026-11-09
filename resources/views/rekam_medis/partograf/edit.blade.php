@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Edit Partograf</h1>
    <div>
        <a href="/partograf/index" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
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
                        <th>J. Kelamin</th>
                        <td>: {{ $pendaftaran->pasien->jenis_kelamin }}</td>
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
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0"><i class="fas fa-file-medical me-2"></i>Form Partograf</h5>
            </div>
            <div class="card-body">
                <form action="/partograf/{{ $partograf->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Penolong Persalinan (Bidan/Dokter)</label>
                            <select name="petugas_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($petugas as $p)
                                    <option value="{{ $p->id }}" {{ old('petugas_id', $partograf->petugas_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pegawai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">G P A (Gravida Partus Abortus)</label>
                            <input type="text" name="gpa" class="form-control" value="{{ old('gpa', $partograf->gpa) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Usia Kehamilan</label>
                            <div class="input-group">
                                <input type="text" name="usia_kehamilan" class="form-control" value="{{ old('usia_kehamilan', $partograf->usia_kehamilan) }}">
                                <span class="input-group-text">Minggu</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Waktu Ketuban Pecah</label>
                            <input type="datetime-local" name="waktu_ketuban_pecah" class="form-control" 
                                value="{{ old('waktu_ketuban_pecah', $partograf->waktu_ketuban_pecah ? date('Y-m-d\TH:i', strtotime($partograf->waktu_ketuban_pecah)) : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu Mulas Sejak</label>
                            <input type="datetime-local" name="waktu_mulas" class="form-control" 
                                value="{{ old('waktu_mulas', $partograf->waktu_mulas ? date('Y-m-d\TH:i', strtotime($partograf->waktu_mulas)) : '') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Persalinan</label>
                        <textarea name="catatan_persalinan" class="form-control" rows="4">{{ old('catatan_persalinan', $partograf->catatan_persalinan) }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Perbarui Data Partograf
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
