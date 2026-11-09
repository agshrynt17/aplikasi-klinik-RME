@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Edit Data INC (Intra Natal Care)</h1>
    <a href="/inc/index" class="btn btn-secondary">
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
                <h5 class="card-title mb-0"><i class="fas fa-file-medical me-2"></i>Form Intra Natal Care</h5>
            </div>
            <div class="card-body">
                <form action="/inc/{{ $inc->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Penolong Persalinan (Bidan/Dokter)</label>
                        <select name="petugas_id" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(\App\Models\Pegawai::where('jenis_pegawai', 'Dokter')->orWhere('jenis_pegawai', 'Bidan')->get() as $p)
                                <option value="{{ $p->id }}" {{ old('petugas_id', $inc->petugas_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pegawai }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <h6 class="mt-4 border-bottom pb-2 text-success">Catatan Kala (I - IV)</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kala I</label>
                            <textarea name="catatan_kala_1" class="form-control" rows="3">{{ old('catatan_kala_1', $inc->catatan_kala_1) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kala II</label>
                            <textarea name="catatan_kala_2" class="form-control" rows="3">{{ old('catatan_kala_2', $inc->catatan_kala_2) }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kala III</label>
                            <textarea name="catatan_kala_3" class="form-control" rows="3">{{ old('catatan_kala_3', $inc->catatan_kala_3) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kala IV</label>
                            <textarea name="catatan_kala_4" class="form-control" rows="3">{{ old('catatan_kala_4', $inc->catatan_kala_4) }}</textarea>
                        </div>
                    </div>

                    <h6 class="mt-4 border-bottom pb-2 text-success">Data Kelahiran Bayi</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Waktu Kelahiran</label>
                            <input type="datetime-local" name="waktu_lahir" class="form-control" 
                                value="{{ old('waktu_lahir', $inc->waktu_lahir ? date('Y-m-d\TH:i', strtotime($inc->waktu_lahir)) : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Persalinan</label>
                            <select name="jenis_persalinan" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="Normal" {{ $inc->jenis_persalinan == 'Normal' ? 'selected' : '' }}>Normal</option>
                                <option value="Sectio Caesarea (SC)" {{ $inc->jenis_persalinan == 'Sectio Caesarea (SC)' ? 'selected' : '' }}>Sectio Caesarea (SC)</option>
                                <option value="Vakum" {{ $inc->jenis_persalinan == 'Vakum' ? 'selected' : '' }}>Vakum Ekstraksi</option>
                                <option value="Forceps" {{ $inc->jenis_persalinan == 'Forceps' ? 'selected' : '' }}>Forceps</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin_bayi" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" {{ $inc->jenis_kelamin_bayi == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ $inc->jenis_kelamin_bayi == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Berat Badan (Gram)</label>
                            <input type="number" step="0.01" name="berat_badan_bayi" class="form-control" value="{{ old('berat_badan_bayi', $inc->berat_badan_bayi) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Panjang Badan (Cm)</label>
                            <input type="number" step="0.01" name="panjang_badan_bayi" class="form-control" value="{{ old('panjang_badan_bayi', $inc->panjang_badan_bayi) }}">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">APGAR Score</label>
                            <input type="text" name="apgar_score" class="form-control" value="{{ old('apgar_score', $inc->apgar_score) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Keadaan Bayi Lahir</label>
                            <input type="text" name="keadaan_bayi" class="form-control" value="{{ old('keadaan_bayi', $inc->keadaan_bayi) }}">
                        </div>
                    </div>

                    <h6 class="mt-4 border-bottom pb-2 text-danger">Komplikasi / Tindakan Lain</h6>
                    <div class="mb-3">
                        <label class="form-label">Penyulit / Komplikasi</label>
                        <textarea name="komplikasi" class="form-control" rows="2">{{ old('komplikasi', $inc->komplikasi) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tindakan Resusitasi</label>
                        <textarea name="tindakan_resusitasi" class="form-control" rows="2">{{ old('tindakan_resusitasi', $inc->tindakan_resusitasi) }}</textarea>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Perbarui Data INC
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
