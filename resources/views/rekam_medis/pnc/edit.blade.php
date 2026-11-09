@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">Edit Data Pelayanan Kebidanan (PNC)</h1>
    <a href="/rekam-medis/pnc" class="btn btn-secondary">
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
                <h5 class="card-title mb-0"><i class="fas fa-female me-2"></i>Data Pasien (Ibu)</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th>No. Rawat</th><td>: {{ $pendaftaran->no_rawat }}</td></tr>
                    <tr><th>No. RM</th><td>: {{ $pendaftaran->pasien->no_rm }}</td></tr>
                    <tr><th>Nama Ibu</th><td>: {{ $pendaftaran->pasien->nama_pasien }}</td></tr>
                    <tr><th>Tgl. Lahir</th><td>: {{ $pendaftaran->pasien->tanggal_lahir }}</td></tr>
                    <tr><th>Poliklinik</th><td>: {{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card-custom">
            <div class="card-header text-white" style="background-color: #e91e63;">
                <h5 class="card-title mb-0"><i class="fas fa-file-medical-alt me-2"></i>Asuhan Kebidanan Masa Nifas (PNC)</h5>
            </div>
            <div class="card-body">
                <form action="/rekam-medis/pnc/{{ $pnc->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">S - SUBJEKTIF (ANAMNESA)</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Persalinan</label>
                            <input type="date" name="tanggal_persalinan" class="form-control" value="{{ old('tanggal_persalinan', $pnc->tanggal_persalinan) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hari Nifas Ke-</label>
                            <input type="text" name="hari_ke" class="form-control" value="{{ old('hari_ke', $pnc->hari_ke) }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Keluhan Utama</label>
                        <textarea name="keluhan_utama" class="form-control" rows="2">{{ old('keluhan_utama', $pnc->keluhan_utama) }}</textarea>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">O - OBJEKTIF (PEMERIKSAAN)</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Tekanan Darah (mmHg)</label>
                            <input type="text" name="tekanan_darah" class="form-control" value="{{ old('tekanan_darah', $pnc->tekanan_darah) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nadi (x/mnt)</label>
                            <input type="number" name="nadi" class="form-control" value="{{ old('nadi', $pnc->nadi) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Respirasi (x/mnt)</label>
                            <input type="number" name="respirasi" class="form-control" value="{{ old('respirasi', $pnc->respirasi) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Suhu (°C)</label>
                            <input type="number" step="0.1" name="suhu" class="form-control" value="{{ old('suhu', $pnc->suhu) }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Payudara / Laktasi</label>
                            <input type="text" name="payudara_laktasi" class="form-control" value="{{ old('payudara_laktasi', $pnc->payudara_laktasi) }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">TFU (Tinggi Fundus Uteri)</label>
                            <input type="text" name="tfu" class="form-control" value="{{ old('tfu', $pnc->tfu) }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Kontraksi Uterus</label>
                            <select name="kontraksi_uterus" class="form-select">
                                <option value="">-- Pilih Kontraksi --</option>
                                <option value="Keras / Baik" {{ old('kontraksi_uterus', $pnc->kontraksi_uterus) == 'Keras / Baik' ? 'selected' : '' }}>Keras / Baik</option>
                                <option value="Lembek" {{ old('kontraksi_uterus', $pnc->kontraksi_uterus) == 'Lembek' ? 'selected' : '' }}>Lembek</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Lochea / Pendarahan</label>
                            <select name="lochea" class="form-select">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Rubra (Merah)" {{ old('lochea', $pnc->lochea) == 'Rubra (Merah)' ? 'selected' : '' }}>Rubra (Merah)</option>
                                <option value="Sanguinolenta (Merah Coklat)" {{ old('lochea', $pnc->lochea) == 'Sanguinolenta (Merah Coklat)' ? 'selected' : '' }}>Sanguinolenta (Merah Coklat)</option>
                                <option value="Serosa (Kekuningan)" {{ old('lochea', $pnc->lochea) == 'Serosa (Kekuningan)' ? 'selected' : '' }}>Serosa (Kekuningan)</option>
                                <option value="Alba (Putih)" {{ old('lochea', $pnc->lochea) == 'Alba (Putih)' ? 'selected' : '' }}>Alba (Putih)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Perineum (Luka Jahitan)</label>
                            <input type="text" name="perineum" class="form-control" value="{{ old('perineum', $pnc->perineum) }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Kandung Kemih</label>
                            <input type="text" name="kandung_kemih" class="form-control" value="{{ old('kandung_kemih', $pnc->kandung_kemih) }}">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">A & P - ASSESSMENT & PLAN</h6>
                    <div class="mb-3">
                        <label class="form-label">Masalah / Diagnosa Kebidanan</label>
                        <textarea name="masalah_diagnosa" class="form-control" rows="2">{{ old('masalah_diagnosa', $pnc->masalah_diagnosa) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tatalaksana / Tindakan</label>
                        <textarea name="tatalaksana" class="form-control" rows="2">{{ old('tatalaksana', $pnc->tatalaksana) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Edukasi / KIE</label>
                        <textarea name="edukasi" class="form-control" rows="2">{{ old('edukasi', $pnc->edukasi) }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-info text-white fw-bold">
                            <i class="fas fa-save"></i> Perbarui Data PNC
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
