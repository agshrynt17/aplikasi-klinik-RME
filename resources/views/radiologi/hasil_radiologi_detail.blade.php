@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
/* CSS Print Area */
@media print {
    body * {
        visibility: hidden;
    }
    #printableArea, #printableArea * {
        visibility: visible;
    }
    #printableArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 25px 30px;
        background: #fff;
        color: #000;
        display: block !important;
        font-family: 'Arial', sans-serif;
    }
    .no-print {
        display: none !important;
    }
}
.template-btn {
    font-size: 0.75rem;
    padding: 2px 8px;
}
.img-preview-box {
    max-height: 220px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    object-fit: contain;
    background: #000;
}
</style>

<!-- Header Area (Title and Action Buttons) -->
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Input Hasil Pemeriksaan Radiologi</h3>
        <p class="text-muted small mb-0">No. Rawat: <strong class="text-primary font-monospace">{{ $pendaftaran->no_rawat }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-info shadow-sm" id="btnCetakHasil" title="Cetak Lembar Hasil Radiologi">
            <i class="fas fa-print me-1"></i> Cetak Hasil
        </button>
        <a href="/radiologi/transaksi/{{ $pendaftaran->id }}" class="btn btn-sm btn-outline-primary" title="Kelola Order / Tambah Pemeriksaan">
            <i class="fas fa-x-ray me-1"></i> Order Radiologi
        </a>
        <a href="/radiologi/hasil" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div id="alertBox" class="alert d-none mb-3 no-print" role="alert"></div>

{{-- Info Pasien --}}
<div class="card border-0 shadow-sm rounded-3 mb-3 no-print" style="border-top: 3px solid #00c0ef !important;">
    <div class="card-body bg-light p-3">
        <div class="row align-items-center">
            <div class="col-md-3 border-end">
                <small class="text-muted d-block">Pasien</small>
                <div class="fw-bold fs-5 text-dark">{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</div>
                <div>
                    No. RM: <span class="badge bg-secondary font-monospace">{{ $pendaftaran->pasien->no_rm ?? '-' }}</span>
                    &nbsp;
                    <span class="badge bg-light text-dark border">
                        {{ ($pendaftaran->pasien->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : (($pendaftaran->pasien->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : ($pendaftaran->pasien->jenis_kelamin ?? '-')) }}
                    </span>
                </div>
            </div>
            <div class="col-md-2 border-end">
                <small class="text-muted d-block">Umur & Kategori</small>
                <div class="fw-bold text-dark">
                    {{ $umur !== null ? $umur . ' Thn' : '-' }}
                    <span class="badge bg-light text-primary border ms-1">{{ $kategoriUmur }}</span>
                </div>
                <small class="text-muted">{{ $pendaftaran->pasien && $pendaftaran->pasien->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->pasien->tanggal_lahir)->format('d/m/Y') : '' }}</small>
            </div>
            <div class="col-md-3 border-end">
                <small class="text-muted d-block">Poliklinik / Dokter Perujuk</small>
                <div class="fw-bold text-primary">{{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</div>
                <small class="text-muted">{{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</small>
            </div>
            <div class="col-md-2 border-end">
                <small class="text-muted d-block">Penjamin & Tanggal</small>
                <div class="fw-bold text-dark">
                    @if($pendaftaran->penjamin)
                        <span class="badge bg-info text-dark">{{ $pendaftaran->penjamin->nama_penjamin }}</span>
                    @else
                        <span class="badge bg-primary">UMUM</span>
                    @endif
                </div>
                <small class="text-muted">Tgl: {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d/m/Y') }}</small>
            </div>
            <div class="col-md-2 text-center">
                @php
                    $total   = $transaksis->count();
                    $selesai = $transaksis->filter(fn($t) => $t->status === 'Selesai')->count();
                    $pct     = $total > 0 ? (int)round(($selesai / $total) * 100) : 0;
                @endphp
                <small class="text-muted d-block mb-1">Status Hasil</small>
                @if($pct == 100 && $total > 0)
                    <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i> Terverifikasi</span>
                @elseif($selesai > 0)
                    <span class="badge bg-warning text-dark fs-6">{{ $selesai }}/{{ $total }} Selesai</span>
                @else
                    <span class="badge bg-secondary fs-6">Draf / Menunggu</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Daftar Pemeriksaan & Form Hasil --}}
<div class="row g-4 mb-4 no-print">
    <div class="col-12">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-x-ray me-2 text-primary"></i>Daftar Pemeriksaan & Hasil Ekspertise
                </h5>
                <span class="badge bg-light text-dark border">
                    Total: <strong>{{ count($transaksis) }}</strong> Tindakan
                </span>
            </div>

            <div class="accordion" id="accordionHasil">
                @forelse($transaksis as $index => $item)
                    @php 
                        $collapseId = 'collapse' . $item->id; 
                        $hasil = $item->hasilRadiologi;
                    @endphp
                    <div class="accordion-item mb-3 shadow-sm" style="border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                <div class="d-flex w-100 justify-content-between align-items-center me-3 flex-wrap gap-2">
                                    <div>
                                        <span class="badge bg-secondary font-monospace me-2">{{ $item->masterRadiologi->kode_rad ?? '-' }}</span>
                                        <strong class="text-dark fs-6">{{ $item->masterRadiologi->nama_pemeriksaan ?? '-' }}</strong>
                                        @if($item->radiografer)
                                            <span class="ms-2 small text-muted">&bull; Radiografer: <strong class="text-dark">{{ $item->radiografer->nama_pegawai }}</strong></span>
                                        @endif
                                    </div>
                                    <div class="status-badge-area">
                                        @if($item->status == 'Selesai')
                                            <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Selesai (Terverifikasi)</span>
                                        @elseif($item->status == 'Proses')
                                            <span class="badge bg-primary px-3 py-2"><i class="fas fa-sync fa-spin me-1"></i> Sedang Proses / Draf</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-clock me-1"></i> Menunggu Ekspertise</span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionHasil">
                            <div class="accordion-body bg-light p-4">
                                
                                @if($item->keterangan)
                                <div class="alert alert-secondary py-2 px-3 small mb-3 border-0">
                                    <i class="fas fa-notes-medical me-1 text-primary"></i> <strong>Indikasi Klinis / Catatan Dokter:</strong> {{ $item->keterangan }}
                                </div>
                                @endif

                                {{-- Form Input Ekspertise --}}
                                <form class="formHasil" id="formHasil_{{ $item->id }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="transaksi_radiologi_id" value="{{ $item->id }}">
                                    
                                    <div class="row g-4">
                                        {{-- Kolom Kiri: Ekspertise Teks --}}
                                        <div class="col-lg-7">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="form-label fw-bold small text-dark mb-0">
                                                    Bacaan / Ekspertise Dokter Radiolog <span class="text-danger">*</span>
                                                </label>
                                                @if($item->status != 'Selesai')
                                                <div class="d-flex gap-1">
                                                    <span class="small text-muted me-1">Template:</span>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn" onclick="applyTemplate({{ $item->id }}, 'thorax')">Thorax Normal</button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn" onclick="applyTemplate({{ $item->id }}, 'fraktur')">Fraktur</button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn" onclick="applyTemplate({{ $item->id }}, 'usg')">USG Normal</button>
                                                </div>
                                                @endif
                                            </div>

                                            <textarea class="form-control font-monospace text-ekspertise" id="ekspertise_{{ $item->id }}" name="hasil_ekspertise" rows="10" 
                                                placeholder="Tuliskan temuan radiologis, deskripsi anatomis, dan kesimpulan..."
                                                {{ $item->status == 'Selesai' ? 'readonly' : '' }}>{{ $hasil->hasil_ekspertise ?? '' }}</textarea>
                                        </div>
                                        
                                        {{-- Kolom Kanan: Upload Citra / File Gambar --}}
                                        <div class="col-lg-5">
                                            <label class="form-label fw-bold small text-dark mb-1">Citra Radiologi (Foto Rontgen / USG)</label>
                                            
                                            <div class="p-3 bg-white border rounded text-center mb-3">
                                                @if($hasil && $hasil->file_gambar)
                                                    <img src="{{ Storage::url($hasil->file_gambar) }}" alt="Citra Radiologi" class="img-fluid img-preview-box mb-2 d-block mx-auto" id="previewImg_{{ $item->id }}">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="{{ Storage::url($hasil->file_gambar) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-external-link-alt me-1"></i> Buka Ukuran Penuh
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="py-4 text-muted" id="placeholderImg_{{ $item->id }}">
                                                        <i class="fas fa-file-image fa-3x mb-2 text-secondary opacity-50"></i>
                                                        <p class="small mb-0">Belum ada file citra radiologi yang diunggah.</p>
                                                    </div>
                                                    <img src="" alt="Citra Radiologi" class="img-fluid img-preview-box mb-2 d-none mx-auto" id="previewImg_{{ $item->id }}">
                                                @endif
                                            </div>

                                            @if($item->status != 'Selesai')
                                                <div class="mb-3">
                                                    <label class="form-label small text-muted mb-1">Unggah Citra Baru (JPG / PNG / WEBP, Maks. 10MB):</label>
                                                    <input type="file" class="form-control form-control-sm input-file-gambar" name="file_gambar" data-target="{{ $item->id }}" accept="image/png, image/jpeg, image/jpg, image/webp">
                                                </div>

                                                <button type="submit" class="btn btn-primary text-white w-100 shadow-sm" id="btnSimpan_{{ $item->id }}">
                                                    <i class="fas fa-save me-1"></i> Simpan Draf Ekspertise
                                                </button>
                                            @endif

                                            @if($item->status == 'Selesai' && $hasil)
                                                <div class="alert alert-success p-3 text-center border-0 shadow-sm mt-3">
                                                    <i class="fas fa-stamp fa-2x text-success mb-1 d-block"></i>
                                                    <strong class="text-success">TERVERIFIKASI & TERSERTIFIKASI</strong>
                                                    <div class="small text-dark mt-1">
                                                        Dokter: <strong>{{ $hasil->dokterPemeriksa->nama_pegawai ?? '-' }}</strong><br>
                                                        Waktu: {{ \Carbon\Carbon::parse($hasil->waktu_verifikasi)->format('d/m/Y H:i') }} WIB
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                                {{-- Area Aksi Verifikasi / Pembatalan --}}
                                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="text-muted small">
                                        <i class="fas fa-info-circle me-1"></i> 
                                        @if($item->status == 'Selesai')
                                            Pemeriksaan ini telah disahkan. Anda dapat mencetak lembar hasil resmi atau membatalkan verifikasi jika diperlukan koreksi.
                                        @else
                                            Simpan draf terlebih dahulu sebelum melakukan verifikasi dokter radiolog.
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->status != 'Selesai')
                                            <select class="form-select form-select-sm" id="dokter_verif_{{ $item->id }}" style="width: 250px;">
                                                <option value="">-- Pilih Dokter Radiolog --</option>
                                                @foreach($dokterList as $dok)
                                                    <option value="{{ $dok->id }}" {{ ($hasil && $hasil->dokter_pemeriksa_id == $dok->id) ? 'selected' : '' }}>
                                                        {{ $dok->nama_pegawai }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-sm btn-success px-3 btn-verifikasi shadow-sm" data-id="{{ $item->id }}" data-hasil-id="{{ $hasil->id ?? '' }}">
                                                <i class="fas fa-check-double me-1"></i> Verifikasi Hasil
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-warning text-dark btn-batal-verif shadow-sm" data-hasil-id="{{ $hasil->id }}">
                                                <i class="fas fa-undo me-1"></i> Batalkan Verifikasi
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-hasil" data-hasil-id="{{ $hasil->id }}">
                                                <i class="fas fa-trash me-1"></i> Hapus Hasil
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                        Belum ada order pemeriksaan radiologi untuk kunjungan ini.
                        <div class="mt-2">
                            <a href="/radiologi/transaksi/{{ $pendaftaran->id }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Tambah Pemeriksaan Radiologi
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</div>

{{-- AREA CETAK LEMBAR HASIL RADIOLOGI --}}
<div id="printableArea" class="d-none">
    {{-- Kop Surat Klinik Telagasari 24 Jam --}}
    <div style="border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 15%; text-align: center; vertical-align: middle;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; background: #00a65a; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; font-weight: bold;">
                        +
                    </div>
                </td>
                <td style="width: 70%; text-align: center; vertical-align: middle;">
                    <h2 style="margin: 0; font-size: 20px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">KLINIK TELAGASARI 24 JAM</h2>
                    <p style="margin: 3px 0 0; font-size: 11px; color: #333;">
                        Jl. Raya Telagasari - Kosambi No. 45, Telagasari, Karawang, Jawa Barat<br>
                        Telp: (0267) 1234567 | WhatsApp: 0812-3456-7890 | Email: pelayanan@kliniktelagasari.com
                    </p>
                </td>
                <td style="width: 15%; text-align: right; vertical-align: middle;">
                    <div style="border: 1px solid #999; padding: 4px; font-size: 9px; text-align: center;">
                        <strong>FORM-RAD-01</strong><br>
                        RAHASIA MEDIS
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-bottom: 15px;">
        <h3 style="margin: 0; font-size: 15px; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
            LEMBAR HASIL PEMERIKSAAN RADIO-DIAGNOSTIK
        </h3>
    </div>

    {{-- Data Pasien --}}
    <table style="width: 100%; font-size: 11px; margin-bottom: 15px; border-collapse: collapse;">
        <tr>
            <td style="width: 18%; font-weight: bold; padding: 2px 0;">No. Rawat</td>
            <td style="width: 32%; padding: 2px 0;">: {{ $pendaftaran->no_rawat }}</td>
            <td style="width: 18%; font-weight: bold; padding: 2px 0;">Tgl. Pemeriksaan</td>
            <td style="width: 32%; padding: 2px 0;">: {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; padding: 2px 0;">No. Rekam Medis</td>
            <td style="padding: 2px 0;">: {{ $pendaftaran->pasien->no_rm ?? '-' }}</td>
            <td style="font-weight: bold; padding: 2px 0;">Poli / Unit Asal</td>
            <td style="padding: 2px 0;">: {{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; padding: 2px 0;">Nama Pasien</td>
            <td style="padding: 2px 0;">: <strong>{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</strong></td>
            <td style="font-weight: bold; padding: 2px 0;">Dokter Pengirim</td>
            <td style="padding: 2px 0;">: {{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; padding: 2px 0;">Jenis Kelamin / Usia</td>
            <td style="padding: 2px 0;">: {{ ($pendaftaran->pasien->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }} / {{ $umur ?? '-' }} Thn</td>
            <td style="font-weight: bold; padding: 2px 0;">Jenis Penjamin</td>
            <td style="padding: 2px 0;">: {{ $pendaftaran->penjamin->nama_penjamin ?? 'UMUM' }}</td>
        </tr>
    </table>

    <hr style="border: 0; border-top: 1px solid #ccc; margin: 10px 0;">

    {{-- Rincian Hasil Tiap Tindakan --}}
    @foreach($transaksis as $idx => $t)
    @php $h = $t->hasilRadiologi; @endphp
    <div style="margin-bottom: 20px; page-break-inside: avoid;">
        <div style="background: #f0f0f0; padding: 5px 8px; font-weight: bold; font-size: 12px; border-left: 4px solid #00a65a;">
            {{ $idx + 1 }}. {{ $t->masterRadiologi->nama_pemeriksaan ?? '-' }} ({{ $t->masterRadiologi->kode_rad ?? '' }})
            @if($t->radiografer)
                <span style="font-weight: normal; font-size: 10px; float: right;">Radiografer: {{ $t->radiografer->nama_pegawai }}</span>
            @endif
        </div>

        @if($t->keterangan)
        <div style="font-size: 10px; color: #555; padding: 3px 8px; font-style: italic;">
            Indikasi Klinis: {{ $t->keterangan }}
        </div>
        @endif

        <div style="padding: 8px; font-size: 11px; white-space: pre-wrap; line-height: 1.5; font-family: 'Courier New', monospace; background: #fafafa; border: 1px solid #eee; margin-top: 5px;">
            {!! nl2br(e($h->hasil_ekspertise ?? 'Hasil ekspertise belum diinput.')) !!}
        </div>

        @if($h && $h->file_gambar)
        <div style="margin-top: 8px; text-align: center;">
            <img src="{{ Storage::url($h->file_gambar) }}" alt="Citra" style="max-height: 180px; max-width: 100%; border: 1px solid #ddd; padding: 2px;">
        </div>
        @endif
    </div>
    @endforeach

    {{-- Tanda Tangan --}}
    <table style="width: 100%; margin-top: 30px; font-size: 11px; text-align: center; page-break-inside: avoid;">
        <tr>
            <td style="width: 45%;">
                Petugas Radiografer Pelaksana,
                <br><br><br><br>
                <strong><u>{{ $transaksis->first()->radiografer->nama_pegawai ?? '........................................' }}</u></strong><br>
                <span>A.Md.Rad</span>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                Telagasari, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Dokter Pemeriksa / Spesialis Radiologi,
                <br><br><br><br>
                <strong><u>{{ $transaksis->first()->hasilRadiologi->dokterPemeriksa->nama_pegawai ?? 'dr. Bambang Hermanto, Sp.Rad' }}</u></strong><br>
                <span>SIP: 503/SIP.DOK/2026/045</span>
            </td>
        </tr>
    </table>

    <div style="margin-top: 25px; border-top: 1px dashed #999; padding-top: 5px; font-size: 9px; color: #777; text-align: center;">
        * Dokumen ini merupakan rekam medis elektronik resmi Klinik Telagasari 24 Jam dan telah divalidasi oleh dokter pemeriksa yang berwenang.
    </div>
</div>

<script>
// Templates generator
function applyTemplate(id, type) {
    let textarea = $('#ekspertise_' + id);
    if (type === 'thorax') {
        textarea.val(
`COR:
- Membesar (-), CTR < 50%
- Bentuk dan letak normal

PULMO:
- Corakan bronkovaskular dalam batas normal
- Tidak tampak infiltrat, nodul, atau kavitas aktif
- Sinus kostofrenikus kanan dan kiri lancip dan tajam
- Hemidiafragma kanan dan kiri licin dan reguler
- Tulang-tulang dinding thorax dan jaringan lunak intak

KESIMPULAN:
Cor dan Pulmo dalam batas normal (tidak tampak kelainan radiologis aktif).`
        );
    } else if (type === 'fraktur') {
        textarea.val(
`KLINIS: Post trauma

TEMUAN RADIOLOGIS:
- Tampak diskontinuitas komplit korteks os clavicula 1/3 tengah dextra
- Displacement fragmen distal ke arah inferior-medial minimal
- Tidak tampak dislokasi artikulasio acromioclavicularis
- Soft tissue swelling di regio clavicula dextra (+)

KESIMPULAN:
Fraktur komplit os clavicula 1/3 tengah dextra dengan displacement minimal.`
        );
    } else if (type === 'usg') {
        textarea.val(
`USG ABDOMEN:
- Hepar: Ukuran normal, echoparenkim homogen, tepi tajam, vaskuler reguler, tidak tampak massa/SOL.
- Vesica Fellea: Ukuran normal, dinding tipis reguler, lumen anechoic, tidak tampak kalkulus/sludge.
- Lien & Pankreas: Ukuran dan densitas eko normal.
- Ginjal Kanan & Kiri: Ukuran normal, diferensiasi kortikomeduler tegas, tidak tampak hidronefrosis atau batu radioopak.
- Vesica Urinaria: Dinding reguler, tidak tampak massa atau kalkulus.

KESIMPULAN:
USG Abdomen dalam batas normal.`
        );
    }
}

$(document).ready(function() {
    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger').addClass('alert-' + type).html(msg).show();
        $('html, body').animate({ scrollTop: $('#alertBox').offset().top - 70 }, 300);
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }

    // Preview gambar saat dipilih
    $('.input-file-gambar').on('change', function() {
        let id = $(this).data('target');
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImg_' + id).attr('src', e.target.result).removeClass('d-none');
                $('#placeholderImg_' + id).addClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // 1. Simpan Draf (Ekspertise & Gambar)
    $('.formHasil').on('submit', function(e) {
        e.preventDefault();
        
        let form = $(this);
        let id = form.find('input[name="transaksi_radiologi_id"]').val();
        let btn = $('#btnSimpan_' + id);
        let oriHtml = btn.html();
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
        
        let formData = new FormData(this);

        $.ajax({
            url: '/radiologi/hasil/simpan',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                btn.prop('disabled', false).html(oriHtml);
                showAlert('success', '<i class="fas fa-check-circle me-1"></i> ' + res.message);
                
                // Update badge status
                let badgeArea = form.closest('.accordion-item').find('.status-badge-area');
                badgeArea.html('<span class="badge bg-primary px-3 py-2"><i class="fas fa-sync fa-spin me-1"></i> Sedang Proses / Draf</span>');
                
                // Update data-hasil-id pada tombol verifikasi
                form.closest('.accordion-collapse').find('.btn-verifikasi').attr('data-hasil-id', res.data.id);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(oriHtml);
                let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan draf ekspertise!';
                showAlert('danger', '<i class="fas fa-exclamation-triangle me-1"></i> ' + msg);
            }
        });
    });

    // 2. Verifikasi Hasil
    $(document).on('click', '.btn-verifikasi', function() {
        let transaksiId = $(this).data('id');
        let hasilId = $(this).attr('data-hasil-id');
        let dokterId = $('#dokter_verif_' + transaksiId).val();
        let teksEkspertise = $('#ekspertise_' + transaksiId).val();

        if (!teksEkspertise || teksEkspertise.trim() === '') {
            showAlert('warning', '<i class="fas fa-exclamation-circle me-1"></i> Harap tuliskan ekspertise terlebih dahulu sebelum memverifikasi!');
            $('#ekspertise_' + transaksiId).focus();
            return;
        }

        if (!dokterId) {
            showAlert('warning', '<i class="fas fa-exclamation-circle me-1"></i> Harap pilih Dokter Radiolog pemeriksa!');
            $('#dokter_verif_' + transaksiId).focus();
            return;
        }

        let btn = $(this);

        // Jika belum disimpan ke database
        if (!hasilId) {
            // Simpan otomatis dulu lalu verifikasi
            $('#formHasil_' + transaksiId).trigger('submit');
            setTimeout(() => {
                let newHasilId = btn.attr('data-hasil-id');
                if (newHasilId) {
                    kirimVerifikasi(newHasilId, dokterId, btn);
                } else {
                    showAlert('warning', 'Harap klik Simpan Draf terlebih dahulu sebelum verifikasi.');
                }
            }, 800);
        } else {
            kirimVerifikasi(hasilId, dokterId, btn);
        }
    });

    function kirimVerifikasi(hasilId, dokterId, btn) {
        if (confirm('Verifikasi dan sahkan hasil radiologi ini? Status akan ditandai Selesai.')) {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memverifikasi...');
            $.ajax({
                url: '/radiologi/hasil/' + hasilId + '/verifikasi',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    dokter_pemeriksa_id: dokterId
                },
                success: function(res) {
                    showAlert('success', '<i class="fas fa-check-double me-1"></i> ' + res.message);
                    setTimeout(() => location.reload(), 800);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fas fa-check-double me-1"></i> Verifikasi Hasil');
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal memverifikasi hasil.';
                    showAlert('danger', msg);
                }
            });
        }
    }

    // 3. Batalkan Verifikasi (Kembali ke draf tanpa hapus data)
    $(document).on('click', '.btn-batal-verif', function() {
        let hasilId = $(this).data('hasil-id');
        let btn = $(this);

        if (confirm('Batalkan verifikasi hasil ini? Hasil akan kembali menjadi draf sehingga dapat dikoreksi.')) {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');
            $.ajax({
                url: '/radiologi/hasil/' + hasilId + '/batal',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    showAlert('info', res.message);
                    setTimeout(() => location.reload(), 700);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fas fa-undo me-1"></i> Batalkan Verifikasi');
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal membatalkan verifikasi.';
                    showAlert('danger', msg);
                }
            });
        }
    });

    // 4. Hapus Hasil
    $(document).on('click', '.btn-hapus-hasil', function() {
        let hasilId = $(this).data('hasil-id');
        let btn = $(this);

        if (confirm('PERINGATAN: Menghapus hasil akan menghapus bacaan ekspertise dan file citra yang telah diunggah. Lanjutkan?')) {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menghapus...');
            $.ajax({
                url: '/radiologi/hasil/' + hasilId + '/hapus',
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    showAlert('warning', res.message);
                    setTimeout(() => location.reload(), 700);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Hapus Hasil');
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menghapus hasil.';
                    showAlert('danger', msg);
                }
            });
        }
    });

    // 5. Cetak Hasil
    $('#btnCetakHasil').on('click', function() {
        window.print();
    });
});
</script>
@endsection
