@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Edit Data Imunisasi</h1>
    <a href="/rekam-medis/imunisasi" class="btn btn-secondary">
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
    <div class="col-md-3 mb-4">
        <div class="card-custom">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-child me-2"></i>Data Pasien</h5>
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
    </div>

    <div class="col-md-9">
        <div class="card-custom">
            <div class="card-header text-white" style="background-color: #00bcd4;">
                <h5 class="card-title mb-0"><i class="fas fa-syringe me-2"></i>Form Pemberian Imunisasi</h5>
            </div>
            <div class="card-body">
                <form action="/rekam-medis/imunisasi/{{ $imunisasi->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Imunisasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_imunisasi" class="form-control" value="{{ old('tanggal_imunisasi', $imunisasi->tanggal_imunisasi) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Imunisasi (Vaksin) <span class="text-danger">*</span></label>
                            <select name="jenis_imunisasi" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @php $jenis = ['Hepatitis B (HB0)', 'BCG', 'Polio 1', 'Polio 2', 'Polio 3', 'Polio 4', 'DPT-HB-Hib 1', 'DPT-HB-Hib 2', 'DPT-HB-Hib 3', 'Campak (MR)', 'Lainnya']; @endphp
                                @foreach($jenis as $j)
                                    <option value="{{ $j }}" {{ old('jenis_imunisasi', $imunisasi->jenis_imunisasi) == $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pemberi Vaksin</label>
                            <select name="pemberi_id" class="form-select">
                                <option value="">-- Pilih Petugas --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}" {{ old('pemberi_id', $imunisasi->pemberi_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pegawai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Batch Vaksin (No. Lot)</label>
                            <input type="text" name="batch_vaksin" class="form-control" value="{{ old('batch_vaksin', $imunisasi->batch_vaksin) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dosis</label>
                            <input type="text" name="dosis" class="form-control" value="{{ old('dosis', $imunisasi->dosis) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lokasi Suntikan / Pemberian</label>
                            <select name="lokasi_suntikan" class="form-select">
                                <option value="">-- Pilih Lokasi --</option>
                                @php $lokasi = ['Paha Kanan Luar (Intramuskular)', 'Paha Kiri Luar (Intramuskular)', 'Lengan Kanan Atas (Subkutan)', 'Lengan Kiri Atas (Intrakutan)', 'Mulut (Oral)']; @endphp
                                @foreach($lokasi as $l)
                                    <option value="{{ $l }}" {{ old('lokasi_suntikan', $imunisasi->lokasi_suntikan) == $l ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keluhan / Reaksi Pasca Imunisasi (KIPI)</label>
                        <textarea name="keluhan_pasca_imunisasi" class="form-control" rows="2">{{ old('keluhan_pasca_imunisasi', $imunisasi->keluhan_pasca_imunisasi) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan Tambahan / Anjuran Obat</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $imunisasi->keterangan) }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-info text-white fw-bold">
                            <i class="fas fa-save"></i> Perbarui Data Imunisasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
