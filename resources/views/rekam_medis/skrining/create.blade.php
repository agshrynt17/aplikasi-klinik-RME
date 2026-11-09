@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Form Pendaftaran Skrining')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    @php
        $patient = $pendaftaran ? $pendaftaran->pasien : $pasien;
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Form Pendaftaran Skrining
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/skrining/index" style="color: #777; text-decoration: none;">Pendaftaran Skrining</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Form Skrining</span>
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
        <!-- Panel Data Pasien -->
        <div class="col-md-3 mb-4">
            <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #337ab7; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05);">
                <div style="background-color: #f4f4f4; padding: 10px 15px; font-weight: 600; font-size: 13px; color: #333; border-bottom: 1px solid #eef2f5;">
                    <i class="fas fa-user me-1 text-primary"></i> Data Pasien
                </div>
                <div style="padding: 15px; font-size: 12px;">
                    @if($pendaftaran)
                        <div class="mb-2">
                            <span class="text-muted d-block" style="font-size: 11px;">No. Registrasi:</span>
                            <span class="fw-bold text-primary">{{ $pendaftaran->nomor_registrasi ?: $pendaftaran->no_rawat }}</span>
                        </div>
                    @endif
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 11px;">No. Rekam Medis:</span>
                        <span class="fw-bold text-primary">{{ $patient->no_rm ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 11px;">Nama Pasien:</span>
                        <span class="fw-bold">{{ strtoupper($patient->nama_pasien ?? '-') }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 11px;">Jenis Kelamin / Tgl Lahir:</span>
                        <span>{{ $patient->jenis_kelamin ?? '-' }} / {{ $patient->tanggal_lahir ? date('d/m/Y', strtotime($patient->tanggal_lahir)) : '-' }}</span>
                    </div>
                    @if($pendaftaran)
                        <div class="mb-2">
                            <span class="text-muted d-block" style="font-size: 11px;">Klinik:</span>
                            <span>{{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted d-block" style="font-size: 11px;">Dokter:</span>
                            <span>{{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Parameter Skrining -->
        <div class="col-md-9 mb-4">
            <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05);">
                <div class="d-flex justify-content-between align-items-center" style="background-color: #f4f4f4; padding: 10px 15px; border-bottom: 1px solid #eef2f5;">
                    <span style="font-weight: 600; font-size: 13px; color: #333;">
                        <i class="fas fa-diagnoses me-1 text-info"></i> Parameter Skrining Pasien
                    </span>
                    <a href="/skrining/index" class="btn btn-sm btn-light border" style="font-size: 11.5px; padding: 2px 10px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                
                <div style="padding: 20px;">
                    <form action="/skrining" method="POST">
                        @csrf
                        <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id ?? '' }}">
                        <input type="hidden" name="pasien_id" value="{{ $patient->id }}">

                        <div class="mb-3">
                            <label style="font-size: 12px; font-weight: 600; color: #333;">Nama Skrining</label>
                            <input type="text" name="nama_skrining" class="form-control form-control-sm" value="Skrining Visual / Umum" style="height: 32px; font-size: 12px;" required>
                        </div>

                        <!-- 1. SKRINING NYERI -->
                        <div style="background: #fafafa; border: 1px solid #eee; border-radius: 3px; padding: 12px 15px; margin-bottom: 15px;">
                            <h6 style="font-size: 12.5px; font-weight: 700; color: #f39c12; margin-bottom: 10px;">
                                1. Skrining Keluhan Nyeri
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label style="font-size: 11.5px; font-weight: 600; color: #444; display: block; margin-bottom: 5px;">Apakah pasien mengalami rasa nyeri?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="skrining_nyeri" id="nyeri_tidak" value="Tidak" checked>
                                        <label class="form-check-label" for="nyeri_tidak" style="font-size: 12px;">Tidak Ada Nyeri</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="skrining_nyeri" id="nyeri_ya" value="Ya">
                                        <label class="form-check-label" for="nyeri_ya" style="font-size: 12px;">Ya, Mengalami Nyeri</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size: 11.5px; font-weight: 600; color: #444; margin-bottom: 5px; display: block;">Skala Nyeri (0 - 10)</label>
                                    <input type="number" name="skor_nyeri" class="form-control form-control-sm" min="0" max="10" placeholder="0 = Tidak nyeri, 10 = Nyeri luar biasa" value="0" style="height: 32px; font-size: 12px;">
                                </div>
                            </div>
                        </div>

                        <!-- 2. SKRINING RESIKO JATUH -->
                        <div style="background: #fafafa; border: 1px solid #eee; border-radius: 3px; padding: 12px 15px; margin-bottom: 15px;">
                            <h6 style="font-size: 12.5px; font-weight: 700; color: #f39c12; margin-bottom: 10px;">
                                2. Skrining Resiko Jatuh (Gaya Berjalan / Cara Berdiri)
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label style="font-size: 11.5px; font-weight: 600; color: #444; display: block; margin-bottom: 5px;">Pasien sempoyongan atau butuh penopang?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="skrining_jatuh" id="jatuh_tidak" value="Tidak" checked>
                                        <label class="form-check-label" for="jatuh_tidak" style="font-size: 12px;">Tidak (Stabil)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="skrining_jatuh" id="jatuh_ya" value="Ya">
                                        <label class="form-check-label" for="jatuh_ya" style="font-size: 12px;">Ya (Butuh Bantuan)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size: 11.5px; font-weight: 600; color: #444; margin-bottom: 5px; display: block;">Tingkat Resiko Jatuh</label>
                                    <select name="resiko_jatuh" class="form-select form-select-sm" style="height: 32px; font-size: 12px;">
                                        <option value="Rendah">Resiko Rendah (Tidak beresiko)</option>
                                        <option value="Sedang">Resiko Sedang</option>
                                        <option value="Tinggi">Resiko Tinggi (Perlu gelang kuning & pendampingan)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 3. SKRINING GIZI & ALERGI -->
                        <div style="background: #fafafa; border: 1px solid #eee; border-radius: 3px; padding: 12px 15px; margin-bottom: 15px;">
                            <h6 style="font-size: 12.5px; font-weight: 700; color: #f39c12; margin-bottom: 10px;">
                                3. Skrining Gizi & Riwayat Alergi
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label style="font-size: 11.5px; font-weight: 600; color: #444; margin-bottom: 5px; display: block;">Status Gizi (Visual)</label>
                                    <select name="skrining_gizi" class="form-select form-select-sm" style="height: 32px; font-size: 12px;">
                                        <option value="Baik">Gizi Baik / Normal</option>
                                        <option value="Kurang">Tampak Kurus / Malnutrisi</option>
                                        <option value="Beresiko">Beresiko (Penurunan BB Drastis)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label style="font-size: 11.5px; font-weight: 600; color: #444; margin-bottom: 5px; display: block;">Riwayat Alergi</label>
                                    <select name="status_alergi" class="form-select form-select-sm" style="height: 32px; font-size: 12px;">
                                        <option value="Tidak">Tidak Ada</option>
                                        <option value="Ya">Ya, Ada Riwayat Alergi</option>
                                        <option value="Tidak Tahu">Tidak Tahu</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label style="font-size: 11.5px; font-weight: 600; color: #444; margin-bottom: 5px; display: block;">Detail Alergi (Jika Ada)</label>
                                    <input type="text" name="detail_alergi" class="form-control form-control-sm" placeholder="Contoh: Antibiotik, Makanan laut..." style="height: 32px; font-size: 12px;">
                                </div>
                            </div>
                        </div>

                        <!-- 4. KESIMPULAN SKRINING -->
                        <div class="mb-3">
                            <label style="font-size: 12px; font-weight: 600; color: #333;">Kesimpulan &amp; Catatan Skrining</label>
                            <textarea name="kesimpulan_skrining" class="form-control" rows="2" placeholder="Catatan hasil skrining perawat/petugas..." style="font-size: 12px;"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="/skrining/index" class="btn btn-sm btn-light border" style="font-size: 12px; padding: 6px 16px;">
                                Batal
                            </a>
                            <button type="submit" class="btn text-white btn-sm" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px; padding: 6px 20px; font-weight: 500;">
                                <i class="fas fa-save me-1"></i> Simpan Data Skrining
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
