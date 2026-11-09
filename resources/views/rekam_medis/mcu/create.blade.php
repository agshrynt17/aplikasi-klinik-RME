@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Form Pemeriksaan MCU')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Input Pemeriksaan Medical Checkup (MCU)
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/moduldokter/index-mcu" style="color: #777; text-decoration: none;">Modul PPA Medical Checkup</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Input MCU</span>
        </div>
    </div>

    <div style="height: 1px; background: #eee; margin-bottom: 20px; margin-top: 10px;"></div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Data Pasien -->
        <div class="col-md-3 mb-4">
            <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #337ab7; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05);">
                <div style="background-color: #f4f4f4; padding: 10px 15px; font-weight: 600; font-size: 13px; color: #333; border-bottom: 1px solid #eef2f5;">
                    <i class="fas fa-user-tie me-1 text-primary"></i> Data Peserta MCU
                </div>
                <div style="padding: 15px; font-size: 12px;">
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 11px;">No. Registrasi:</span>
                        <span class="fw-bold text-primary">{{ $pendaftaran->nomor_registrasi ?: $pendaftaran->no_rawat }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 11px;">No. Rekam Medis:</span>
                        <span class="fw-bold text-primary">{{ $pendaftaran->pasien->no_rm ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 11px;">Nama Pasien:</span>
                        <span class="fw-bold">{{ strtoupper($pendaftaran->pasien->nama_pasien ?? '-') }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 11px;">Jenis Kelamin / Tgl Lahir:</span>
                        <span>{{ $pendaftaran->pasien->jenis_kelamin ?? '-' }} / {{ $pendaftaran->pasien->tanggal_lahir ? date('d/m/Y', strtotime($pendaftaran->pasien->tanggal_lahir)) : '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 11px;">Poli / Ruangan:</span>
                        <span>{{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form MCU -->
        <div class="col-md-9 mb-4">
            <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05);">
                <div class="d-flex justify-content-between align-items-center" style="background-color: #f4f4f4; padding: 10px 15px; border-bottom: 1px solid #eef2f5;">
                    <span style="font-weight: 600; font-size: 13px; color: #333;">
                        <i class="fas fa-stethoscope me-1 text-info"></i> Formulir Pemeriksaan Fisik &amp; Khusus MCU
                    </span>
                    <a href="/moduldokter/index-mcu" class="btn btn-sm btn-light border" style="font-size: 11.5px; padding: 2px 10px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                
                <div style="padding: 20px;">
                    <form action="/rekam-medis/mcu" method="POST">
                        @csrf
                        <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                        <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">

                        <!-- ANTROPOMETRI & TTV -->
                        <div style="background: #fafafa; border: 1px solid #eee; border-radius: 3px; padding: 12px 15px; margin-bottom: 15px;">
                            <h6 style="font-size: 12.5px; font-weight: 700; color: #337ab7; margin-bottom: 10px;">
                                A. Antropometri &amp; Tanda-Tanda Vital
                            </h6>
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Tinggi Badan (cm)</label>
                                    <input type="number" step="0.1" name="tinggi_badan" class="form-control form-control-sm" value="{{ old('tinggi_badan') }}" style="height: 32px; font-size: 12px;">
                                </div>
                                <div class="col-md-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Berat Badan (kg)</label>
                                    <input type="number" step="0.1" name="berat_badan" class="form-control form-control-sm" value="{{ old('berat_badan') }}" style="height: 32px; font-size: 12px;">
                                </div>
                                <div class="col-md-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Tensi (mmHg)</label>
                                    <input type="text" name="tekanan_darah" class="form-control form-control-sm" placeholder="120/80" value="{{ old('tekanan_darah') }}" style="height: 32px; font-size: 12px;">
                                </div>
                                <div class="col-md-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Nadi (x/mnt)</label>
                                    <input type="number" name="nadi" class="form-control form-control-sm" value="{{ old('nadi') }}" style="height: 32px; font-size: 12px;">
                                </div>
                                <div class="col-md-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Suhu (°C)</label>
                                    <input type="number" step="0.1" name="suhu" class="form-control form-control-sm" value="{{ old('suhu') }}" style="height: 32px; font-size: 12px;">
                                </div>
                                <div class="col-md-2">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Nafas (x/mnt)</label>
                                    <input type="number" name="respirasi" class="form-control form-control-sm" value="{{ old('respirasi') }}" style="height: 32px; font-size: 12px;">
                                </div>
                            </div>
                        </div>

                        <!-- PEMERIKSAAN KHUSUS -->
                        <div style="background: #fafafa; border: 1px solid #eee; border-radius: 3px; padding: 12px 15px; margin-bottom: 15px;">
                            <h6 style="font-size: 12.5px; font-weight: 700; color: #337ab7; margin-bottom: 10px;">
                                B. Pemeriksaan Khusus
                            </h6>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Tes Buta Warna</label>
                                    <select name="buta_warna" class="form-select form-select-sm" style="height: 32px; font-size: 12px;">
                                        <option value="Normal">Normal</option>
                                        <option value="Buta Warna Parsial">Buta Warna Parsial</option>
                                        <option value="Buta Warna Total">Buta Warna Total</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Golongan Darah</label>
                                    <select name="golongan_darah" class="form-select form-select-sm" style="height: 32px; font-size: 12px;">
                                        <option value="">-- Pilih --</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="AB">AB</option>
                                        <option value="O">O</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Visus Mata Kanan (OD)</label>
                                    <input type="text" name="visus_mata_kanan" class="form-control form-control-sm" placeholder="Misal: 6/6" value="{{ old('visus_mata_kanan') }}" style="height: 32px; font-size: 12px;">
                                </div>
                                <div class="col-md-3">
                                    <label style="font-size: 11px; font-weight: 600; color: #444; margin-bottom: 2px; display: block;">Visus Mata Kiri (OS)</label>
                                    <input type="text" name="visus_mata_kiri" class="form-control form-control-sm" placeholder="Misal: 6/6" value="{{ old('visus_mata_kiri') }}" style="height: 32px; font-size: 12px;">
                                </div>
                            </div>
                        </div>

                        <!-- PEMERIKSAAN FISIK UMUM & KESIMPULAN -->
                        <div class="mb-3">
                            <label style="font-size: 12px; font-weight: 600; color: #333;">Uraian Pemeriksaan Fisik (Kepala s/d Ekstremitas)</label>
                            <textarea name="pemeriksaan_fisik" class="form-control" rows="3" placeholder="Jelaskan temuan fisik normal/abnormal..." style="font-size: 12px;">{{ old('pemeriksaan_fisik') }}</textarea>
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label style="font-size: 12px; font-weight: 600; color: #333;">Kesimpulan / Hasil Kelayakan MCU</label>
                                <select name="kesimpulan" class="form-select form-select-sm" style="height: 32px; font-size: 12px;" required>
                                    <option value="">-- Pilih Kesimpulan MCU --</option>
                                    <option value="Sehat (Fit for Job)">Sehat (Fit for Job)</option>
                                    <option value="Sehat dengan Catatan (Fit with Restriction)">Sehat dengan Catatan (Fit with Restriction)</option>
                                    <option value="Tidak Sehat (Unfit for Job)">Tidak Sehat (Unfit for Job)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label style="font-size: 12px; font-weight: 600; color: #333;">Dokter Pemeriksa</label>
                                <select name="dokter_id" class="form-select form-select-sm" style="height: 32px; font-size: 12px;">
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokters as $d)
                                        <option value="{{ $d->id }}" {{ old('dokter_id', $pendaftaran->dokter_id) == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama_pegawai }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="/moduldokter/index-mcu" class="btn btn-sm btn-light border" style="font-size: 12px; padding: 6px 16px;">
                                Batal
                            </a>
                            <button type="submit" class="btn text-white btn-sm" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px; padding: 6px 20px; font-weight: 500;">
                                <i class="fas fa-save me-1"></i> Simpan Data MCU
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
