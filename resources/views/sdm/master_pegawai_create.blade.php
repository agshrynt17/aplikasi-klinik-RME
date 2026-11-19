@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', (isset($item) ? 'Ubah Pegawai' : 'Tambah Pegawai') . ' - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="tambah-pegawai-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">
            {{ isset($item) ? 'Ubah Pegawai' : 'Tambah Pegawai' }}
        </h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/master-pegawai" class="text-decoration-none text-muted">Pegawai</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">{{ isset($item) ? 'Ubah Pegawai' : 'Tambah Pegawai' }}</a></li>
            </ol>
        </nav>
    </div>

    <!-- Blue Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #00c0ef;"></div>

    @if($errors->any())
    <div class="alert alert-danger py-2 px-3 mb-3 small">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Content Card -->
    <div class="card border rounded-2 shadow-none bg-white p-4">
        <form action="{{ isset($item) ? '/master-pegawai/' . $item->id : '/master-pegawai' }}" method="POST">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            <div class="row g-4">
                <!-- Column 1 (Left) -->
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">
                            Fungsional Medis <span class="text-danger small fw-normal">*wajib diisi!</span>
                        </label>
                        <div class="d-flex flex-column gap-1 mt-1 small">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fungsional_medis" id="fmDokter" value="DOKTER" 
                                       {{ old('fungsional_medis', $item->fungsional_medis ?? '') == 'DOKTER' ? 'checked' : '' }} required>
                                <label class="form-check-label text-dark" for="fmDokter">DOKTER</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fungsional_medis" id="fmNakes" value="TENAGA KESEHATAN" 
                                       {{ old('fungsional_medis', $item->fungsional_medis ?? '') == 'TENAGA KESEHATAN' ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="fmNakes">TENAGA KESEHATAN</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fungsional_medis" id="fmNonNakes" value="NON TENAGA KESEHATAN" 
                                       {{ old('fungsional_medis', $item->fungsional_medis ?? '') == 'NON TENAGA KESEHATAN' ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="fmNonNakes">NON TENAGA KESEHATAN</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">
                            No.NIK <span class="text-danger small fw-normal">*wajib diisi!</span>
                        </label>
                        <input type="text" name="nik" class="form-control form-control-sm @error('nik') is-invalid @enderror" 
                               value="{{ old('nik', $item->nik ?? '') }}" required 
                               style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        <div class="text-muted" style="font-size: 10.5px; margin-top: 3px;">
                            Jika NIK diubah akan berdampak pada semua transaksi yang terkait dengan data ini!
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">
                            Nama Lengkap + Gelar <span class="text-danger small fw-normal">*wajib diisi!</span>
                        </label>
                        <input type="text" name="nama_pegawai" class="form-control form-control-sm @error('nama_pegawai') is-invalid @enderror" 
                               value="{{ old('nama_pegawai', $item->nama_pegawai ?? '') }}" required 
                               style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Tempat Lahir <span class="text-danger small fw-normal">*wajib!</span>
                            </label>
                            <input type="text" name="tempat_lahir" class="form-control form-control-sm" 
                                   value="{{ old('tempat_lahir', $item->tempat_lahir ?? '') }}" required 
                                   style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Tgl.Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control form-control-sm" 
                                   value="{{ old('tanggal_lahir', isset($item->tanggal_lahir) ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('Y-m-d') : '') }}" 
                                   style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">
                                J/K <span class="text-danger small fw-normal">*wajib!</span>
                            </label>
                            <select name="jenis_kelamin" class="form-select form-select-sm" required 
                                    style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                                <option value="">--Pilih--</option>
                                <option value="L" {{ old('jenis_kelamin', $item->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('jenis_kelamin', $item->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Agama <span class="text-danger small fw-normal">*wajib!</span>
                            </label>
                            <select name="agama" class="form-select form-select-sm" required 
                                    style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                                <option value="">--Pilih Agama--</option>
                                <option value="Islam" {{ old('agama', $item->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama', $item->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama', $item->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama', $item->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama', $item->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama', $item->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">
                            No. Telp/HP <span class="text-danger small fw-normal">*wajib diisi!</span>
                        </label>
                        <input type="text" name="no_telepon" class="form-control form-control-sm" 
                               value="{{ old('no_telepon', $item->no_telepon ?? '') }}" required 
                               style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">
                            Alamat email aktif <span class="text-danger small fw-normal">*wajib diisi!</span>
                        </label>
                        <input type="email" name="email" class="form-control form-control-sm" 
                               value="{{ old('email', $item->email ?? '') }}" required 
                               style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                    </div>
                </div>

                <!-- Column 2 (Center) -->
                <div class="col-lg-4 col-md-6">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Tgl.Mulai Join</label>
                            <input type="date" name="tanggal_masuk" class="form-control form-control-sm" 
                                   value="{{ old('tanggal_masuk', isset($item->tanggal_masuk) ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('Y-m-d') : '') }}" 
                                   style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Status Nikah</label>
                            <select name="status_nikah" class="form-select form-select-sm" 
                                    style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                                <option value="">--Pilih--</option>
                                <option value="Belum Menikah" {{ old('status_nikah', $item->status_nikah ?? '') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="Menikah" {{ old('status_nikah', $item->status_nikah ?? '') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="Duda" {{ old('status_nikah', $item->status_nikah ?? '') == 'Duda' ? 'selected' : '' }}>Duda</option>
                                <option value="Janda" {{ old('status_nikah', $item->status_nikah ?? '') == 'Janda' ? 'selected' : '' }}>Janda</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">No KIS</label>
                        <input type="text" name="no_kis" class="form-control form-control-sm" 
                               value="{{ old('no_kis', $item->no_kis ?? '') }}" 
                               style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">No. SIP / SIPA</label>
                            <input type="text" name="no_sip" class="form-control form-control-sm" 
                                   value="{{ old('no_sip', $item->no_sip ?? '') }}" 
                                   style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Tgl. Expired</label>
                            <input type="date" name="tgl_expired_sip" class="form-control form-control-sm" 
                                   value="{{ old('tgl_expired_sip', isset($item->tgl_expired_sip) ? \Carbon\Carbon::parse($item->tgl_expired_sip)->format('Y-m-d') : '') }}" 
                                   style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Apoteker ?</label>
                            <div class="d-flex gap-3 mt-1 small">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_apoteker" id="aptYa" value="1" 
                                           {{ old('is_apoteker', $item->is_apoteker ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="aptYa">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_apoteker" id="aptTidak" value="0" 
                                           {{ !old('is_apoteker', $item->is_apoteker ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="aptTidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Status Pegawai</label>
                            <div class="d-flex gap-3 mt-1 small">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pegawai" id="spAktif" value="Aktif" 
                                           {{ old('status_pegawai', $item->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="spAktif">Aktif</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pegawai" id="spNonAktif" value="Non-Aktif" 
                                           {{ !old('status_pegawai', $item->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="spNonAktif">Non-Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">No SPK</label>
                            <input type="text" name="no_spk" class="form-control form-control-sm" 
                                   value="{{ old('no_spk', $item->no_spk ?? '') }}" 
                                   style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Expired SPK</label>
                            <input type="date" name="expired_spk" class="form-control form-control-sm" 
                                   value="{{ old('expired_spk', isset($item->expired_spk) ? \Carbon\Carbon::parse($item->expired_spk)->format('Y-m-d') : '') }}" 
                                   style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Status Pekerjaan</label>
                            <select name="status_pekerjaan" class="form-select form-select-sm" 
                                    style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                                <option value="">--Pilih--</option>
                                <option value="Tetap" {{ old('status_pekerjaan', $item->status_pekerjaan ?? '') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                <option value="Kontrak" {{ old('status_pekerjaan', $item->status_pekerjaan ?? '') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                <option value="Magang" {{ old('status_pekerjaan', $item->status_pekerjaan ?? '') == 'Magang' ? 'selected' : '' }}>Magang</option>
                                <option value="Honorer" {{ old('status_pekerjaan', $item->status_pekerjaan ?? '') == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Golongan</label>
                            <select name="golongan" class="form-select form-select-sm" 
                                    style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                                <option value="">--Pilih--</option>
                                <option value="Golongan I" {{ old('golongan', $item->golongan ?? '') == 'Golongan I' ? 'selected' : '' }}>Golongan I</option>
                                <option value="Golongan II" {{ old('golongan', $item->golongan ?? '') == 'Golongan II' ? 'selected' : '' }}>Golongan II</option>
                                <option value="Golongan III" {{ old('golongan', $item->golongan ?? '') == 'Golongan III' ? 'selected' : '' }}>Golongan III</option>
                                <option value="Golongan IV" {{ old('golongan', $item->golongan ?? '') == 'Golongan IV' ? 'selected' : '' }}>Golongan IV</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Unit</label>
                            <select name="unit" class="form-select form-select-sm" 
                                    style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                                <option value="">--Pilih Unit--</option>
                                <option value="Pelayanan" {{ old('unit', $item->unit ?? '') == 'Pelayanan' ? 'selected' : '' }}>Pelayanan</option>
                                <option value="Penunjang" {{ old('unit', $item->unit ?? '') == 'Penunjang' ? 'selected' : '' }}>Penunjang</option>
                                <option value="Umum" {{ old('unit', $item->unit ?? '') == 'Umum' ? 'selected' : '' }}>Umum</option>
                                <option value="Farmasi" {{ old('unit', $item->unit ?? '') == 'Farmasi' ? 'selected' : '' }}>Farmasi</option>
                                <option value="Laboratorium" {{ old('unit', $item->unit ?? '') == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark mb-1">Jabatan</label>
                            <select name="jabatan" class="form-select form-select-sm" 
                                    style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                                <option value="">--Pilih--</option>
                                <option value="Pelaksana" {{ old('jabatan', $item->jabatan ?? '') == 'Pelaksana' ? 'selected' : '' }}>Pelaksana</option>
                                <option value="Koordinator" {{ old('jabatan', $item->jabatan ?? '') == 'Koordinator' ? 'selected' : '' }}>Koordinator</option>
                                <option value="Penanggung Jawab Pelayanan" {{ old('jabatan', $item->jabatan ?? '') == 'Penanggung Jawab Pelayanan' ? 'selected' : '' }}>Penanggung Jawab Pelayanan</option>
                                <option value="Kepala Klinik" {{ old('jabatan', $item->jabatan ?? '') == 'Kepala Klinik' ? 'selected' : '' }}>Kepala Klinik</option>
                                <option value="Dokter Penanggung Jawab" {{ old('jabatan', $item->jabatan ?? '') == 'Dokter Penanggung Jawab' ? 'selected' : '' }}>Dokter Penanggung Jawab</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Column 3 (Right) -->
                <div class="col-lg-4 col-md-12">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">Alamat</label>
                        <textarea name="alamat" class="form-control form-control-sm" rows="3" 
                                  style="border: 1px solid #d2d6de; border-radius: 2px;">{{ old('alamat', $item->alamat ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">Propinsi</label>
                        <select name="propinsi" class="form-select form-select-sm" 
                                style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                            <option value="">--Pilih--</option>
                            <option value="Jawa Barat" {{ old('propinsi', $item->propinsi ?? 'Jawa Barat') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                            <option value="DKI Jakarta" {{ old('propinsi', $item->propinsi ?? '') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                            <option value="Banten" {{ old('propinsi', $item->propinsi ?? '') == 'Banten' ? 'selected' : '' }}>Banten</option>
                            <option value="Jawa Tengah" {{ old('propinsi', $item->propinsi ?? '') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                            <option value="Jawa Timur" {{ old('propinsi', $item->propinsi ?? '') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">Kabupaten</label>
                        <select name="kabupaten" class="form-select form-select-sm" 
                                style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                            <option value="">--Pilih--</option>
                            <option value="Karawang" {{ old('kabupaten', $item->kabupaten ?? 'Karawang') == 'Karawang' ? 'selected' : '' }}>Karawang</option>
                            <option value="Bekasi" {{ old('kabupaten', $item->kabupaten ?? '') == 'Bekasi' ? 'selected' : '' }}>Bekasi</option>
                            <option value="Purwakarta" {{ old('kabupaten', $item->kabupaten ?? '') == 'Purwakarta' ? 'selected' : '' }}>Purwakarta</option>
                            <option value="Subang" {{ old('kabupaten', $item->kabupaten ?? '') == 'Subang' ? 'selected' : '' }}>Subang</option>
                            <option value="Bandung" {{ old('kabupaten', $item->kabupaten ?? '') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">Kecamatan</label>
                        <select name="kecamatan" class="form-select form-select-sm" 
                                style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                            <option value="">--Pilih--</option>
                            <option value="Telagasari" {{ old('kecamatan', $item->kecamatan ?? 'Telagasari') == 'Telagasari' ? 'selected' : '' }}>Telagasari</option>
                            <option value="Klari" {{ old('kecamatan', $item->kecamatan ?? '') == 'Klari' ? 'selected' : '' }}>Klari</option>
                            <option value="Majalaya" {{ old('kecamatan', $item->kecamatan ?? '') == 'Majalaya' ? 'selected' : '' }}>Majalaya</option>
                            <option value="Lemahabang" {{ old('kecamatan', $item->kecamatan ?? '') == 'Lemahabang' ? 'selected' : '' }}>Lemahabang</option>
                            <option value="Karawang Barat" {{ old('kecamatan', $item->kecamatan ?? '') == 'Karawang Barat' ? 'selected' : '' }}>Karawang Barat</option>
                            <option value="Karawang Timur" {{ old('kecamatan', $item->kecamatan ?? '') == 'Karawang Timur' ? 'selected' : '' }}>Karawang Timur</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">Desa</label>
                        <select name="desa" class="form-select form-select-sm" 
                                style="border: 1px solid #d2d6de; border-radius: 2px; height: 34px;">
                            <option value="">--Pilih--</option>
                            <option value="Telagasari" {{ old('desa', $item->desa ?? 'Telagasari') == 'Telagasari' ? 'selected' : '' }}>Telagasari</option>
                            <option value="Talagamulya" {{ old('desa', $item->desa ?? '') == 'Talagamulya' ? 'selected' : '' }}>Talagamulya</option>
                            <option value="Cadas Kertajaya" {{ old('desa', $item->desa ?? '') == 'Cadas Kertajaya' ? 'selected' : '' }}>Cadas Kertajaya</option>
                            <option value="Pasirtalaga" {{ old('desa', $item->desa ?? '') == 'Pasirtalaga' ? 'selected' : '' }}>Pasirtalaga</option>
                            <option value="Kalibuaya" {{ old('desa', $item->desa ?? '') == 'Kalibuaya' ? 'selected' : '' }}>Kalibuaya</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-2 border-top">
                <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" 
                        style="background-color: #00a65a; border-color: #008d4c; height: 34px; min-width: 80px;">
                    Simpan
                </button>
                <a href="/master-pegawai" class="btn btn-sm text-white px-3 fw-semibold d-flex align-items-center justify-content-center" 
                   style="background-color: #f0ad4e; border-color: #eea236; height: 34px; min-width: 80px; text-decoration: none;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
