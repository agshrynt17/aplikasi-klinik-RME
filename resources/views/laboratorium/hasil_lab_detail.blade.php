@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
/* Styling khusus hasil lab */
.row-pemeriksaan td { vertical-align: middle; }
.input-nilai-hasil { text-align: center; font-weight: 700; font-size: 0.95rem; }
.flag-normal { color: #198754; font-weight: 600; }
.flag-tinggi { color: #dc3545; font-weight: 600; }
.flag-rendah { color: #0d6efd; font-weight: 600; }
.flag-abnormal { color: #d63384; font-weight: 600; }

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
        padding: 20px;
        background: #fff;
        color: #000;
        display: block !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>

<!-- Header Area (Title and Action Buttons) -->
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Input Hasil Pemeriksaan Lab</h3>
        <p class="text-muted small mb-0">No. Rawat: <strong class="text-primary font-monospace">{{ $pendaftaran->no_rawat }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-info" id="btnCetakHasil" title="Cetak Lembar Hasil Lab">
            <i class="fas fa-print me-1"></i> Cetak Hasil
        </button>
        <a href="/laboratorium/transaksi-lab/{{ $pendaftaran->id }}" class="btn btn-sm btn-outline-primary" title="Kelola Order / Tambah Tes Lab">
            <i class="fas fa-flask me-1"></i> Order Lab
        </a>
        <a href="/laboratorium/hasil-lab" class="btn btn-sm btn-secondary">
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
                        {{ $pendaftaran->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : ($pendaftaran->pasien->jenis_kelamin == 'P' ? 'Perempuan' : ($pendaftaran->pasien->jenis_kelamin ?? '-')) }}
                    </span>
                </div>
            </div>
            <div class="col-md-2 border-end">
                <small class="text-muted d-block">Umur & Kategori</small>
                <div class="fw-bold text-dark">
                    {{ $umur !== null ? $umur . ' Thn' : '-' }}
                    <span class="badge bg-light text-primary border ms-1">{{ $kategoriUmur }}</span>
                </div>
                <small class="text-muted">{{ $pendaftaran->pasien->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->pasien->tanggal_lahir)->format('d/m/Y') : '' }}</small>
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
                    $pct     = $total > 0 ? round($selesai / $total * 100) : 0;
                    $barClass = $pct == 100 ? 'bg-success' : ($pct > 0 ? 'bg-warning' : 'bg-secondary');
                @endphp
                <small class="text-muted d-block mb-1">Status Progress</small>
                <div class="fw-bold fs-6 {{ $pct == 100 ? 'text-success' : 'text-primary' }}" id="progressCounter">
                    {{ $selesai }}/{{ $total }} Selesai ({{ $pct }}%)
                </div>
                <div class="progress mt-1" style="height: 8px;">
                    <div class="progress-bar {{ $barClass }}" id="progressBar" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Daftar Pemeriksaan & Hasil --}}
<div class="card-custom p-4 shadow-sm border-0 rounded-3 no-print">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="fas fa-vial me-2 text-primary"></i>Daftar Pemeriksaan & Hasil Lab
        </h5>
        <div class="d-flex gap-2">
            @php
                $unverifiedCount = $transaksis->filter(fn($t) => $t->hasilLab && !$t->hasilLab->verified_at)->count();
            @endphp
            <button class="btn btn-sm btn-outline-success {{ $unverifiedCount > 0 ? '' : 'd-none' }}" id="btnVerifikasiSemua">
                <i class="fas fa-check-double me-1"></i> Verifikasi Semua
            </button>
            @if($total > 0)
            <button class="btn btn-sm btn-cyan text-white shadow-sm" id="btnSimpanSemua">
                <i class="fas fa-save me-1"></i> Simpan Semua Hasil
            </button>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabelHasil" style="border-top: 2px solid #e9ecef; border-bottom: 1px solid #e9ecef;">
            <thead>
                <tr>
                    <th width="3%" class="text-center text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">#</th>
                    <th width="23%" class="text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">Pemeriksaan Lab</th>
                    <th width="14%" class="text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">Nilai Rujukan</th>
                    <th width="13%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Nilai Hasil</th>
                    <th width="7%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Satuan</th>
                    <th width="10%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Flag</th>
                    <th width="14%" class="text-dark" style="background: transparent; border-bottom: 2px solid #dee2e6;">Catatan</th>
                    <th width="10%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Verifikasi</th>
                    <th width="6%" class="text-dark text-center" style="background: transparent; border-bottom: 2px solid #dee2e6;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $index => $item)
                @php 
                    $hasil = $item->hasilLab; 
                    $rowBgClass = '';
                    if ($hasil) {
                        if ($hasil->flag == 'Normal') $rowBgClass = 'table-light';
                        elseif ($hasil->flag == 'Tinggi') $rowBgClass = 'table-danger bg-opacity-10';
                        elseif ($hasil->flag == 'Rendah') $rowBgClass = 'table-primary bg-opacity-10';
                        elseif ($hasil->flag == 'Abnormal') $rowBgClass = 'table-warning bg-opacity-10';
                    }
                @endphp
                <tr data-id="{{ $item->id }}" data-hasil-id="{{ $hasil ? $hasil->id : '' }}"
                    class="row-pemeriksaan {{ $rowBgClass }}">
                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                    <td>
                        <div class="fw-bold text-dark">{{ $item->masterLab->nama_pemeriksaan ?? '-' }}</div>
                        <small class="text-muted">
                            <span class="badge bg-secondary font-monospace">{{ $item->masterLab->kode_lab ?? '' }}</span>
                            &nbsp;Analis: {{ $item->analis->nama_pegawai ?? '-' }}
                        </small>
                    </td>
                    <td class="text-muted small">
                        <span class="fw-semibold normal-text-label">{{ $item->normal_text }}</span>
                    </td>
                    <td>
                        <input type="text"
                            class="form-control form-control-sm input-nilai-hasil"
                            data-id="{{ $item->id }}"
                            data-normal-text="{{ $item->normal_text }}"
                            data-nilai-min="{{ $item->nilai_normal_ref->nilai_min ?? '' }}"
                            data-nilai-max="{{ $item->nilai_normal_ref->nilai_max ?? '' }}"
                            data-nilai-teks="{{ $item->nilai_normal_ref->nilai_teks ?? '' }}"
                            value="{{ $hasil ? $hasil->nilai_hasil : '' }}"
                            placeholder="Isi hasil...">
                    </td>
                    <td class="text-center text-muted small fw-semibold">
                        {{ $item->masterLab->satuan ?? '-' }}
                    </td>
                    <td class="text-center">
                        <select class="form-select form-select-sm select-flag" data-id="{{ $item->id }}">
                            @foreach(['Normal','Tinggi','Rendah','Abnormal'] as $f)
                                <option value="{{ $f }}" {{ ($hasil && $hasil->flag == $f) ? 'selected' : ($f == 'Normal' && !$hasil ? 'selected' : '') }}>
                                    {{ $f }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm input-catatan"
                            data-id="{{ $item->id }}"
                            value="{{ $hasil ? $hasil->catatan : '' }}"
                            placeholder="Catatan...">
                    </td>
                    <td class="text-center cell-verifikasi small">
                        @if($hasil && $hasil->verified_at)
                            <span class="badge bg-success d-block mb-1" style="font-size: 11px;">
                                <i class="fas fa-check-circle me-1"></i>Terverifikasi
                            </span>
                            <small class="text-muted d-block" style="font-size: 10px; line-height: 1.2;">
                                {{ $hasil->verifikator->nama_pegawai ?? '-' }}<br>
                                {{ $hasil->verified_at->format('d/m/Y H:i') }}
                            </small>
                        @elseif($hasil)
                            <button class="btn btn-sm btn-outline-success btn-verifikasi"
                                data-hasil-id="{{ $hasil->id }}" style="font-size: 11px; padding: 2px 8px;">
                                <i class="fas fa-check me-1"></i>Verifikasi
                            </button>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-center cell-aksi text-nowrap">
                        <button class="btn btn-sm btn-cyan text-white btn-simpan-hasil"
                            data-id="{{ $item->id }}"
                            data-normal-text="{{ $item->normal_text }}"
                            title="Simpan Hasil">
                            <i class="fas fa-save"></i>
                        </button>
                        @if($hasil)
                        <button class="btn btn-sm btn-outline-danger btn-hapus-hasil ms-1"
                            data-hasil-id="{{ $hasil->id }}" title="Hapus Hasil">
                            <i class="fas fa-trash fa-xs"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-flask fa-2x mb-2 d-block"></i>
                        Belum ada order pemeriksaan laboratorium untuk kunjungan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Keterangan Legenda Flag --}}
    <div class="d-flex align-items-center gap-2 mt-3 p-2 bg-light rounded border flex-wrap">
        <small class="text-dark fw-bold me-2"><i class="fas fa-info-circle me-1 text-primary"></i>Keterangan Flag:</small>
        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Normal</span>
        <span class="badge bg-danger"><i class="fas fa-arrow-up me-1"></i>Tinggi</span>
        <span class="badge bg-primary"><i class="fas fa-arrow-down me-1"></i>Rendah</span>
        <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>Abnormal</span>
    </div>
</div>

{{-- ==================== MODAL VERIFIKASI ==================== --}}
<div class="modal fade" id="modalVerifikasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #00a65a;">
                <h6 class="modal-title fw-bold"><i class="fas fa-user-check me-2"></i>Verifikasi Hasil Lab</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <p class="small text-muted mb-2">Pilih staf / dokter analis yang memverifikasi keabsahan hasil pemeriksaan ini:</p>
                <div class="mb-2">
                    <label class="form-label fw-bold small text-dark">Petugas Verifikator <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm" id="verifikatorId">
                        <option value="">-- Pilih Petugas Verifikator --</option>
                        @foreach($master_pegawai as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer p-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-cyan text-white btn-sm" id="btnKonfirmasiVerifikasi">
                    <i class="fas fa-check me-1"></i> Verifikasi
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL KONFIRMASI HAPUS ==================== --}}
<div class="modal fade" id="modalHapusHasil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Hapus Hasil</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <p class="small text-dark mb-0">Apakah Anda yakin ingin menghapus hasil pemeriksaan ini? Status pemeriksaan akan dikembalikan ke <strong>Proses</strong>.</p>
            </div>
            <div class="modal-footer p-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnKonfirmasiHapusHasil">
                    <i class="fas fa-trash me-1"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== PRINTABLE AREA (A4 CETAK HASIL LAB) ==================== --}}
<div id="printableArea" style="display: none; font-family: Arial, sans-serif;">
    <div style="text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px;">
        <h3 style="margin: 0; font-weight: bold; text-transform: uppercase;">KLINIK TELAGASARI 24 JAM</h3>
        <p style="margin: 2px 0; font-size: 13px;">Jl. Raya Telagasari No. 123, Karawang - Jawa Barat</p>
        <p style="margin: 0; font-size: 12px; color: #555;">Telp: (0267) 123456 | Email: lab@kliniktelagasari.com</p>
    </div>

    <div style="text-align: center; margin-bottom: 15px;">
        <h4 style="margin: 0; font-weight: bold; text-decoration: underline;">HASIL PEMERIKSAAN LABORATORIUM</h4>
    </div>

    <table style="width: 100%; font-size: 13px; margin-bottom: 15px;" cellpadding="3">
        <tr>
            <td width="18%"><strong>No. Rawat</strong></td>
            <td width="2%">:</td>
            <td width="30%">{{ $pendaftaran->no_rawat }}</td>
            <td width="18%"><strong>Tgl. Registrasi</strong></td>
            <td width="2%">:</td>
            <td width="30%">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>No. RM</strong></td>
            <td>:</td>
            <td>{{ $pendaftaran->pasien->no_rm ?? '-' }}</td>
            <td><strong>Dokter Pengirim</strong></td>
            <td>:</td>
            <td>{{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Pasien</strong></td>
            <td>:</td>
            <td><strong>{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</strong></td>
            <td><strong>Unit / Poli</strong></td>
            <td>:</td>
            <td>{{ $pendaftaran->poliklinik->nama_poli ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Jenis Kelamin / Umur</strong></td>
            <td>:</td>
            <td>{{ $pendaftaran->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} / {{ $umur !== null ? $umur . ' Thn' : '-' }}</td>
            <td><strong>Penjamin</strong></td>
            <td>:</td>
            <td>{{ $pendaftaran->penjamin->nama_penjamin ?? 'UMUM' }}</td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 25px;" border="1" cellpadding="6">
        <thead style="background: #f0f0f0;">
            <tr>
                <th width="5%" style="text-align: center;">No</th>
                <th width="30%">Pemeriksaan</th>
                <th width="15%" style="text-align: center;">Hasil</th>
                <th width="12%" style="text-align: center;">Satuan</th>
                <th width="20%">Nilai Rujukan</th>
                <th width="8%" style="text-align: center;">Flag</th>
                <th width="10%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $idx => $t)
            @php $h = $t->hasilLab; @endphp
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td><strong>{{ $t->masterLab->nama_pemeriksaan ?? '-' }}</strong></td>
                <td style="text-align: center; font-weight: bold;">{{ $h ? $h->nilai_hasil : '-' }}</td>
                <td style="text-align: center;">{{ $t->masterLab->satuan ?? '-' }}</td>
                <td>{{ $t->normal_text }}</td>
                <td style="text-align: center; font-weight: bold; color: {{ ($h && $h->flag != 'Normal') ? 'red' : 'inherit' }};">
                    {{ $h ? $h->flag : '-' }}
                </td>
                <td>{{ $h ? $h->catatan : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%; font-size: 13px; margin-top: 30px;">
        <tr>
            <td width="50%" style="text-align: center;">
                Analis Laboratorium,<br><br><br><br>
                <strong><u>( {{ $transaksis->first()->analis->nama_pegawai ?? 'Petugas Laboratorium' }} )</u></strong>
            </td>
            <td width="50%" style="text-align: center;">
                Karawang, {{ date('d/m/Y') }}<br>
                Penanggung Jawab Lab / Verifikator,<br><br><br><br>
                <strong><u>( {{ $transaksis->first()->hasilLab->verifikator->nama_pegawai ?? 'dr. Penanggung Jawab' }} )</u></strong>
            </td>
        </tr>
    </table>
</div>

<script>
$(document).ready(function () {
    const pendaftaranId = {{ $pendaftaran->id }};

    /* ---------------------------------------------------------- */
    /*  Auto-detect Flag saat nilai diisi                         */
    /* ---------------------------------------------------------- */
    $(document).on('input', '.input-nilai-hasil', function () {
        let row       = $(this).closest('tr');
        let rawVal    = $(this).val().trim();
        let nilaiMin  = parseFloat($(this).data('nilai-min'));
        let nilaiMax  = parseFloat($(this).data('nilai-max'));
        let nilaiTeks = $(this).data('nilai-teks');
        let selectFlag = row.find('.select-flag');

        if (rawVal === '') {
            row.removeClass('table-light table-danger table-primary table-warning bg-opacity-10');
            return;
        }

        // Jika nilai numerik
        let numVal = parseFloat(rawVal.replace(',', '.'));
        if (!isNaN(numVal)) {
            let flag = 'Normal';
            if (!isNaN(nilaiMin) && !isNaN(nilaiMax)) {
                if (numVal < nilaiMin)      flag = 'Rendah';
                else if (numVal > nilaiMax) flag = 'Tinggi';
            } else if (!isNaN(nilaiMin) && numVal < nilaiMin) {
                flag = 'Rendah';
            } else if (!isNaN(nilaiMax) && numVal > nilaiMax) {
                flag = 'Tinggi';
            }
            selectFlag.val(flag);
            applyRowFlagColor(row, flag);
        } else if (nilaiTeks) {
            // Evaluasi teks rujukan (e.g., Negatif / Non Reaktif)
            let flag = rawVal.toLowerCase() === String(nilaiTeks).toLowerCase() ? 'Normal' : 'Abnormal';
            selectFlag.val(flag);
            applyRowFlagColor(row, flag);
        }
    });

    $(document).on('change', '.select-flag', function () {
        let row = $(this).closest('tr');
        applyRowFlagColor(row, $(this).val());
    });

    function applyRowFlagColor(row, flag) {
        row.removeClass('table-light table-danger table-primary table-warning bg-opacity-10');
        if (flag === 'Normal')   row.addClass('table-light');
        if (flag === 'Tinggi')   row.addClass('table-danger bg-opacity-10');
        if (flag === 'Rendah')   row.addClass('table-primary bg-opacity-10');
        if (flag === 'Abnormal') row.addClass('table-warning bg-opacity-10');
    }

    /* ---------------------------------------------------------- */
    /*  Simpan Hasil (Per Baris)                                   */
    /* ---------------------------------------------------------- */
    $(document).on('click', '.btn-simpan-hasil', function () {
        let btn         = $(this);
        let row         = btn.closest('tr');
        let transaksiId = btn.data('id');
        let normalText  = btn.data('normal-text');
        let nilaiHasil  = row.find('.input-nilai-hasil').val();
        let flag        = row.find('.select-flag').val();
        let catatan     = row.find('.input-catatan').val();

        let oriHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: '/laboratorium/hasil-lab/simpan',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                transaksi_lab_id: transaksiId,
                nilai_hasil: nilaiHasil,
                flag: flag,
                catatan: catatan,
                nilai_normal_text: normalText,
            },
            success: function (res) {
                btn.html(oriHtml).prop('disabled', false);
                showAlert('success', res.message);

                applyRowFlagColor(row, res.flag);
                row.data('hasil-id', res.data.id).attr('data-hasil-id', res.data.id);

                // Tambahkan tombol hapus jika belum ada
                let cellAksi = row.find('.cell-aksi');
                if (cellAksi.find('.btn-hapus-hasil').length === 0) {
                    cellAksi.append(`
                        <button class="btn btn-sm btn-outline-danger btn-hapus-hasil ms-1"
                            data-hasil-id="${res.data.id}" title="Hapus Hasil">
                            <i class="fas fa-trash fa-xs"></i>
                        </button>
                    `);
                } else {
                    cellAksi.find('.btn-hapus-hasil').data('hasil-id', res.data.id).attr('data-hasil-id', res.data.id);
                }

                // Tambahkan tombol verifikasi jika belum terverifikasi
                let cellVer = row.find('.cell-verifikasi');
                if (cellVer.find('.badge.bg-success').length === 0) {
                    cellVer.html(`
                        <button class="btn btn-sm btn-outline-success btn-verifikasi"
                            data-hasil-id="${res.data.id}" style="font-size: 11px; padding: 2px 8px;">
                            <i class="fas fa-check me-1"></i>Verifikasi
                        </button>
                    `);
                }

                $('#btnVerifikasiSemua').removeClass('d-none');
                updateProgressUI();
            },
            error: function (xhr) {
                btn.html(oriHtml).prop('disabled', false);
                let err = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyimpan hasil.';
                showAlert('danger', err);
            }
        });
    });

    /* ---------------------------------------------------------- */
    /*  Simpan Semua Hasil Sekaligus                               */
    /* ---------------------------------------------------------- */
    $('#btnSimpanSemua').on('click', function () {
        let rows = $('.row-pemeriksaan');
        let promises = [];
        let btn = $(this);
        let oriHtml = btn.html();

        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...').prop('disabled', true);

        rows.each(function () {
            let row         = $(this);
            let transaksiId = row.data('id');
            let normalText  = row.find('.btn-simpan-hasil').data('normal-text');
            let nilaiHasil  = row.find('.input-nilai-hasil').val();
            let flag        = row.find('.select-flag').val();
            let catatan     = row.find('.input-catatan').val();

            if (nilaiHasil !== '') {
                let req = $.ajax({
                    url: '/laboratorium/hasil-lab/simpan',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        transaksi_lab_id: transaksiId,
                        nilai_hasil: nilaiHasil,
                        flag: flag,
                        catatan: catatan,
                        nilai_normal_text: normalText,
                    },
                    success: function (res) {
                        applyRowFlagColor(row, res.flag);
                        row.data('hasil-id', res.data.id).attr('data-hasil-id', res.data.id);
                        
                        let cellAksi = row.find('.cell-aksi');
                        if (cellAksi.find('.btn-hapus-hasil').length === 0) {
                            cellAksi.append(`
                                <button class="btn btn-sm btn-outline-danger btn-hapus-hasil ms-1"
                                    data-hasil-id="${res.data.id}" title="Hapus Hasil">
                                    <i class="fas fa-trash fa-xs"></i>
                                </button>
                            `);
                        }
                        
                        let cellVer = row.find('.cell-verifikasi');
                        if (cellVer.find('.badge.bg-success').length === 0) {
                            cellVer.html(`
                                <button class="btn btn-sm btn-outline-success btn-verifikasi"
                                    data-hasil-id="${res.data.id}" style="font-size: 11px; padding: 2px 8px;">
                                    <i class="fas fa-check me-1"></i>Verifikasi
                                </button>
                            `);
                        }
                    }
                });
                promises.push(req);
            }
        });

        if (promises.length === 0) {
            btn.html(oriHtml).prop('disabled', false);
            showAlert('warning', 'Belum ada nilai hasil yang diisi untuk disimpan.');
            return;
        }

        $.when.apply($, promises).always(function () {
            btn.html(oriHtml).prop('disabled', false);
            showAlert('success', 'Semua hasil pemeriksaan berhasil disimpan.');
            $('#btnVerifikasiSemua').removeClass('d-none');
            updateProgressUI();
        });
    });

    /* ---------------------------------------------------------- */
    /*  Hapus Hasil (dengan Modal Konfirmasi)                     */
    /* ---------------------------------------------------------- */
    let hapusHasilId = null;
    let hapusRowTarget = null;

    $(document).on('click', '.btn-hapus-hasil', function () {
        hapusHasilId = $(this).data('hasil-id');
        hapusRowTarget = $(this).closest('tr');
        $('#modalHapusHasil').modal('show');
    });

    $('#btnKonfirmasiHapusHasil').on('click', function () {
        if (!hapusHasilId) return;

        let btn = $(this);
        let oriHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Menghapus...').prop('disabled', true);

        $.ajax({
            url: '/laboratorium/hasil-lab/' + hapusHasilId + '/hapus',
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                btn.html(oriHtml).prop('disabled', false);
                $('#modalHapusHasil').modal('hide');
                showAlert('success', res.message);

                hapusRowTarget.removeClass('table-danger table-primary table-warning bg-opacity-10').addClass('table-light');
                hapusRowTarget.find('.input-nilai-hasil').val('');
                hapusRowTarget.find('.input-catatan').val('');
                hapusRowTarget.find('.select-flag').val('Normal');
                hapusRowTarget.find('.btn-hapus-hasil').remove();
                hapusRowTarget.find('.cell-verifikasi').html('<span class="text-muted small">—</span>');
                hapusRowTarget.data('hasil-id', '').attr('data-hasil-id', '');

                updateProgressUI();
            },
            error: function () {
                btn.html(oriHtml).prop('disabled', false);
                showAlert('danger', 'Gagal menghapus hasil pemeriksaan.');
            }
        });
    });

    /* ---------------------------------------------------------- */
    /*  Verifikasi Hasil (Tunggal & Semua)                        */
    /* ---------------------------------------------------------- */
    let verifikasiTargetId = null;
    let verifikasiTargetRow = null;

    $(document).on('click', '.btn-verifikasi', function () {
        verifikasiTargetId = $(this).data('hasil-id');
        verifikasiTargetRow = $(this).closest('tr');
        $('#verifikatorId').val('');
        $('#modalVerifikasi').modal('show');
    });

    $('#btnVerifikasiSemua').on('click', function () {
        verifikasiTargetId = 'semua';
        verifikasiTargetRow = null;
        $('#verifikatorId').val('');
        $('#modalVerifikasi').modal('show');
    });

    $('#btnKonfirmasiVerifikasi').on('click', function () {
        let verifikatorId = $('#verifikatorId').val();
        if (!verifikatorId) {
            alert('Silakan pilih petugas verifikator terlebih dahulu!');
            return;
        }

        let btn = $(this);
        let oriHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...').prop('disabled', true);

        $.ajax({
            url: '/laboratorium/hasil-lab/' + verifikasiTargetId + '/verifikasi',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                diverifikasi_oleh: verifikatorId,
                pendaftaran_id: pendaftaranId
            },
            success: function (res) {
                btn.html(oriHtml).prop('disabled', false);
                $('#modalVerifikasi').modal('hide');
                showAlert('success', res.message);

                if (res.is_all) {
                    $('.cell-verifikasi').each(function () {
                        let row = $(this).closest('tr');
                        if (row.data('hasil-id')) {
                            $(this).html(`
                                <span class="badge bg-success d-block mb-1" style="font-size: 11px;">
                                    <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                </span>
                                <small class="text-muted d-block" style="font-size: 10px; line-height: 1.2;">
                                    ${res.verifikator}<br>${res.verified_at}
                                </small>
                            `);
                        }
                    });
                    $('#btnVerifikasiSemua').addClass('d-none');
                } else if (verifikasiTargetRow) {
                    verifikasiTargetRow.find('.cell-verifikasi').html(`
                        <span class="badge bg-success d-block mb-1" style="font-size: 11px;">
                            <i class="fas fa-check-circle me-1"></i>Terverifikasi
                        </span>
                        <small class="text-muted d-block" style="font-size: 10px; line-height: 1.2;">
                            ${res.verifikator}<br>${res.verified_at}
                        </small>
                    `);
                    // Periksa apakah masih ada yang belum diverifikasi
                    if ($('.btn-verifikasi').length === 0) {
                        $('#btnVerifikasiSemua').addClass('d-none');
                    }
                }
            },
            error: function () {
                btn.html(oriHtml).prop('disabled', false);
                showAlert('danger', 'Gagal memproses verifikasi hasil.');
            }
        });
    });

    /* ---------------------------------------------------------- */
    /*  Cetak Hasil Lembar Lab                                     */
    /* ---------------------------------------------------------- */
    $('#btnCetakHasil').on('click', function () {
        window.print();
    });

    /* ---------------------------------------------------------- */
    /*  Helper: Update Progress UI                                */
    /* ---------------------------------------------------------- */
    function updateProgressUI() {
        let total = $('.row-pemeriksaan').length;
        let selesai = 0;
        $('.row-pemeriksaan').each(function () {
            if ($(this).find('.input-nilai-hasil').val().trim() !== '') {
                selesai++;
            }
        });
        let pct = total > 0 ? Math.round((selesai / total) * 100) : 0;
        let barClass = pct === 100 ? 'bg-success' : (pct > 0 ? 'bg-warning' : 'bg-secondary');

        $('#progressBar').css('width', pct + '%').attr('class', 'progress-bar ' + barClass);
        $('#progressCounter').text(`${selesai}/${total} Selesai (${pct}%)`)
            .attr('class', 'fw-bold fs-6 ' + (pct === 100 ? 'text-success' : 'text-primary'));
    }

    /* ---------------------------------------------------------- */
    /*  Helper: Alert Notification                                 */
    /* ---------------------------------------------------------- */
    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger alert-info')
            .addClass('alert-' + type).html('<i class="fas fa-info-circle me-2"></i>' + msg).show();
        $('html, body').animate({ scrollTop: $('#alertBox').offset().top - 70 }, 200);
        setTimeout(function () { $('#alertBox').addClass('d-none'); }, 4000);
    }
});
</script>
@endsection
