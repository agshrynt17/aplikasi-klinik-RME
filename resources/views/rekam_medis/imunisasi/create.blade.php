@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Input Data Imunisasi</h1>
    <a href="/rekam-medis/imunisasi" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

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
                <form action="/rekam-medis/imunisasi" method="POST">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Imunisasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_imunisasi" class="form-control" value="{{ old('tanggal_imunisasi', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Imunisasi (Vaksin) <span class="text-danger">*</span></label>
                            <select name="jenis_imunisasi" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Hepatitis B (HB0)" {{ old('jenis_imunisasi') == 'Hepatitis B (HB0)' ? 'selected' : '' }}>Hepatitis B (HB0)</option>
                                <option value="BCG" {{ old('jenis_imunisasi') == 'BCG' ? 'selected' : '' }}>BCG</option>
                                <option value="Polio 1" {{ old('jenis_imunisasi') == 'Polio 1' ? 'selected' : '' }}>Polio 1</option>
                                <option value="Polio 2" {{ old('jenis_imunisasi') == 'Polio 2' ? 'selected' : '' }}>Polio 2</option>
                                <option value="Polio 3" {{ old('jenis_imunisasi') == 'Polio 3' ? 'selected' : '' }}>Polio 3</option>
                                <option value="Polio 4" {{ old('jenis_imunisasi') == 'Polio 4' ? 'selected' : '' }}>Polio 4</option>
                                <option value="DPT-HB-Hib 1" {{ old('jenis_imunisasi') == 'DPT-HB-Hib 1' ? 'selected' : '' }}>DPT-HB-Hib 1</option>
                                <option value="DPT-HB-Hib 2" {{ old('jenis_imunisasi') == 'DPT-HB-Hib 2' ? 'selected' : '' }}>DPT-HB-Hib 2</option>
                                <option value="DPT-HB-Hib 3" {{ old('jenis_imunisasi') == 'DPT-HB-Hib 3' ? 'selected' : '' }}>DPT-HB-Hib 3</option>
                                <option value="Campak (MR)" {{ old('jenis_imunisasi') == 'Campak (MR)' ? 'selected' : '' }}>Campak (MR)</option>
                                <option value="Lainnya" {{ old('jenis_imunisasi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pemberi Vaksin</label>
                            <select name="pemberi_id" class="form-select">
                                <option value="">-- Pilih Petugas --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}" {{ old('pemberi_id', $pendaftaran->dokter_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pegawai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Batch Vaksin (No. Lot)</label>
                            <input type="text" name="batch_vaksin" class="form-control" placeholder="No. Batch Vaksin" value="{{ old('batch_vaksin') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dosis</label>
                            <input type="text" name="dosis" class="form-control" placeholder="Misal: 0.5 ml / 2 Tetes" value="{{ old('dosis') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lokasi Suntikan / Pemberian</label>
                            <select name="lokasi_suntikan" class="form-select">
                                <option value="">-- Pilih Lokasi --</option>
                                <option value="Paha Kanan Luar (Intramuskular)" {{ old('lokasi_suntikan') == 'Paha Kanan Luar (Intramuskular)' ? 'selected' : '' }}>Paha Kanan Luar (IM)</option>
                                <option value="Paha Kiri Luar (Intramuskular)" {{ old('lokasi_suntikan') == 'Paha Kiri Luar (Intramuskular)' ? 'selected' : '' }}>Paha Kiri Luar (IM)</option>
                                <option value="Lengan Kanan Atas (Subkutan)" {{ old('lokasi_suntikan') == 'Lengan Kanan Atas (Subkutan)' ? 'selected' : '' }}>Lengan Kanan Atas (SC)</option>
                                <option value="Lengan Kiri Atas (Intrakutan)" {{ old('lokasi_suntikan') == 'Lengan Kiri Atas (Intrakutan)' ? 'selected' : '' }}>Lengan Kiri Atas (IC)</option>
                                <option value="Mulut (Oral)" {{ old('lokasi_suntikan') == 'Mulut (Oral)' ? 'selected' : '' }}>Mulut (Oral)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keluhan / Reaksi Pasca Imunisasi (KIPI)</label>
                        <textarea name="keluhan_pasca_imunisasi" class="form-control" rows="2" placeholder="Biarkan kosong jika tidak ada keluhan...">{{ old('keluhan_pasca_imunisasi') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan Tambahan / Anjuran Obat</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Misal: Berikan Paracetamol drop jika anak demam...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn fw-bold text-white" style="background-color: #00bcd4; border: none;">
                            <i class="fas fa-save"></i> Simpan Data Imunisasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
