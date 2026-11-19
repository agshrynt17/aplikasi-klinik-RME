@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .bed-card {
        border-radius: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 2px solid transparent;
        background: #fff;
    }
    .bed-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
    .bed-available {
        border-color: #00a65a;
    }
    .bed-occupied {
        border-color: #dd4b39;
    }
    .bed-maintenance {
        border-color: #f39c12;
    }
    .badge-vip { background-color: #605ca8; color: #fff; }
    .badge-k1  { background-color: #0073b7; color: #fff; }
    .badge-k2  { background-color: #00c0ef; color: #fff; }
    .badge-k3  { background-color: #39cccc; color: #fff; }
    .badge-iso { background-color: #d81b60; color: #fff; }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-0"><i class="fas fa-bed text-primary me-2"></i>Bed Management &amp; Rawat Inap</h4>
            <small class="text-muted">Manajemen Tempat Tidur, Admisi Pasien, Pindah Kamar (Transfer), &amp; Discharge</small>
        </div>
        <div class="d-flex gap-2">
            <a href="/rawat-inap/pasien" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-user-injured me-1"></i> Daftar Pasien Ranap
            </a>
            <button class="btn btn-sm text-white" style="background-color: #00a65a;" data-bs-toggle="modal" data-bs-target="#modalAdmisi">
                <i class="fas fa-plus me-1"></i> Admisi Pasien Masuk
            </button>
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalTambahBed">
                <i class="fas fa-cog me-1"></i> Tambah Bed
            </button>
        </div>
    </div>

    <!-- Statistik Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-white" style="background: linear-gradient(135deg, #00c0ef, #0097bc);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-4 fw-bold mb-0">{{ $totalBed }}</div>
                        <div class="small text-white-50">Total Kapasitas Bed</div>
                    </div>
                    <i class="fas fa-hospital fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-white" style="background: linear-gradient(135deg, #dd4b39, #c23321);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-4 fw-bold mb-0">{{ $bedTerisi }}</div>
                        <div class="small text-white-50">Bed Terisi (Occupied)</div>
                    </div>
                    <i class="fas fa-procedures fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-white" style="background: linear-gradient(135deg, #00a65a, #008d4c);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-4 fw-bold mb-0">{{ $bedKosong }}</div>
                        <div class="small text-white-50">Bed Kosong (Tersedia)</div>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-white" style="background: linear-gradient(135deg, #f39c12, #c87f0a);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-4 fw-bold mb-0">{{ $bor }}%</div>
                        <div class="small text-white-50">BOR (Occupancy Rate)</div>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 bg-white">
        <form method="GET" action="/rawat-inap/bed" class="row g-2 align-items-center">
            <div class="col-md-4">
                <label class="small fw-semibold text-muted mb-1">Filter Kelas Kamar</label>
                <select name="kelas" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <option value="VIP" {{ request('kelas') == 'VIP' ? 'selected' : '' }}>VIP</option>
                    <option value="Kelas 1" {{ request('kelas') == 'Kelas 1' ? 'selected' : '' }}>Kelas 1</option>
                    <option value="Kelas 2" {{ request('kelas') == 'Kelas 2' ? 'selected' : '' }}>Kelas 2</option>
                    <option value="Kelas 3" {{ request('kelas') == 'Kelas 3' ? 'selected' : '' }}>Kelas 3</option>
                    <option value="Isolasi" {{ request('kelas') == 'Isolasi' ? 'selected' : '' }}>Isolasi</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="small fw-semibold text-muted mb-1">Filter Ketersediaan</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia (Kosong)</option>
                    <option value="Terisi" {{ request('status') == 'Terisi' ? 'selected' : '' }}>Terisi Pasien</option>
                </select>
            </div>
            <div class="col-md-4 text-end pt-3">
                <a href="/rawat-inap/bed" class="btn btn-outline-secondary btn-sm"><i class="fas fa-sync me-1"></i> Reset</a>
            </div>
        </form>
    </div>

    <!-- Grid Kartu Tempat Tidur (Bed Grid) -->
    <div class="row g-3">
        @forelse($beds as $b)
            @php
                $isTerisi = ($b->status === 'Terisi' && $b->pendaftaran);
                $kelasBadge = 'badge-k2';
                if($b->kelas === 'VIP') $kelasBadge = 'badge-vip';
                elseif($b->kelas === 'Kelas 1') $kelasBadge = 'badge-k1';
                elseif($b->kelas === 'Kelas 3') $kelasBadge = 'badge-k3';
                elseif($b->kelas === 'Isolasi') $kelasBadge = 'badge-iso';
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card bed-card p-3 shadow-sm h-100 {{ $isTerisi ? 'bed-occupied' : 'bed-available' }}">
                    <!-- Header Kartu -->
                    <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                        <div>
                            <div class="fw-bold text-dark fs-6">{{ $b->nama_kamar }}</div>
                            <span class="badge {{ $kelasBadge }} px-2 py-1" style="font-size: 10px;">{{ $b->kelas }}</span>
                        </div>
                        <span class="badge {{ $isTerisi ? 'bg-danger' : 'bg-success' }} px-2 py-1">
                            {{ $b->nomor_bed }}
                        </span>
                    </div>

                    <!-- Body Kartu -->
                    @if($isTerisi)
                        <div class="my-2">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="fas fa-user-injured"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate" title="{{ $b->pendaftaran->pasien->nama_pasien ?? '-' }}">
                                        {{ $b->pendaftaran->pasien->nama_pasien ?? 'Pasien Ranap' }}
                                    </div>
                                    <small class="text-muted">RM: {{ $b->pendaftaran->pasien->no_rm ?? '-' }}</small>
                                </div>
                            </div>
                            <div class="small text-muted bg-light p-2 rounded mb-2" style="font-size: 11px;">
                                <div><i class="fas fa-calendar-alt text-primary me-1"></i> Masuk: {{ $b->waktu_masuk ? $b->waktu_masuk->format('d/m/Y H:i') : '-' }}</div>
                                <div><i class="fas fa-user-md text-info me-1"></i> DPJP: {{ $b->pendaftaran->dokter->nama_pegawai ?? 'dr. Dokter Jaga' }}</div>
                            </div>
                        </div>
                        <!-- Aksi Bed Terisi -->
                        <div class="mt-auto pt-2 border-top d-flex gap-1">
                            <button type="button" class="btn btn-xs btn-outline-warning w-50 btnTransferBed" 
                                    data-id="{{ $b->id }}" 
                                    data-nama="{{ $b->nama_kamar }} - {{ $b->nomor_bed }}"
                                    title="Pindah Tempat Tidur">
                                <i class="fas fa-exchange-alt me-1"></i> Pindah
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger w-50 btnDischargeBed" 
                                    data-id="{{ $b->id }}" 
                                    data-nama="{{ $b->pendaftaran->pasien->nama_pasien ?? 'Pasien' }}"
                                    data-kamar="{{ $b->nama_kamar }} - {{ $b->nomor_bed }}"
                                    title="Pasien Pulang / Checkout">
                                <i class="fas fa-sign-out-alt me-1"></i> Pulang
                            </button>
                        </div>
                    @else
                        <!-- Bed Kosong -->
                        <div class="my-auto py-3 text-center">
                            <i class="fas fa-bed fa-2x text-success opacity-75 mb-2"></i>
                            <div class="fw-bold text-success" style="font-size: 13px;">KOSONG / TERSEDIA</div>
                            <div class="small text-muted">Rp {{ number_format($b->tarif_per_hari, 0, ',', '.') }} / hari</div>
                        </div>
                        <!-- Aksi Bed Kosong -->
                        <div class="mt-auto pt-2 border-top">
                            <button type="button" class="btn btn-xs btn-success w-100 py-1 btnAdmisiBed" 
                                    data-id="{{ $b->id }}" 
                                    data-nama="{{ $b->nama_kamar }} - {{ $b->nomor_bed }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Admisi Pasien
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-bed fa-3x mb-3 text-secondary"></i>
                <p>Belum ada data tempat tidur rawat inap.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Admisi Pasien Masuk Ranap -->
<div class="modal fade" id="modalAdmisi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white py-2 px-3">
                <h6 class="modal-title fw-bold"><i class="fas fa-sign-in-alt me-1"></i> Admisi Pasien Rawat Inap</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAdmisi">
                @csrf
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Pasien Registrasi <span class="text-danger">*</span></label>
                        <select name="pendaftaran_id" id="admisi_pendaftaran_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Pasien Terdaftar --</option>
                            @foreach($pasiensSiapAdmisi as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->nomor_registrasi }} - {{ $p->pasien->nama_pasien ?? 'Pasien' }} (RM: {{ $p->pasien->no_rm ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Kamar &amp; Tempat Tidur <span class="text-danger">*</span></label>
                        <select name="kamar_ranap_id" id="admisi_kamar_ranap_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Bed Kosong --</option>
                            @foreach($kamarKosongList as $k)
                                <option value="{{ $k->id }}">
                                    {{ $k->nama_kamar }} - {{ $k->nomor_bed }} ({{ $k->kelas }} - Rp {{ number_format($k->tarif_per_hari, 0, ',', '.') }}/hari)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Waktu Masuk <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="waktu_masuk" class="form-control form-control-sm" value="{{ date('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Dokter Penanggung Jawab (DPJP)</label>
                            <select name="dokter_id" class="form-select form-select-sm">
                                <option value="">-- Pilih Dokter --</option>
                                @foreach($dokters as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama_pegawai }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Catatan Diagnosa Masuk / Alasan Rawat</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Keluhan utama, indikasi rawat inap..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i> Simpan Admisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pindah Kamar (Transfer Bed) -->
<div class="modal fade" id="modalTransfer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark py-2 px-3">
                <h6 class="modal-title fw-bold"><i class="fas fa-exchange-alt me-1"></i> Pindah Kamar / Tempat Tidur</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTransfer">
                @csrf
                <input type="hidden" name="bed_asal_id" id="transfer_bed_asal_id">
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tempat Tidur Saat Ini</label>
                        <input type="text" id="transfer_bed_asal_nama" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pindah Ke Tempat Tidur Tujuan <span class="text-danger">*</span></label>
                        <select name="bed_tujuan_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Bed Kosong --</option>
                            @foreach($kamarKosongList as $k)
                                <option value="{{ $k->id }}">
                                    {{ $k->nama_kamar }} - {{ $k->nomor_bed }} ({{ $k->kelas }} - Rp {{ number_format($k->tarif_per_hari, 0, ',', '.') }}/hari)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Alasan Pindah Kamar</label>
                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Permintaan keluarga, upgrade kelas, kondisi medis...">
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm text-dark fw-bold"><i class="fas fa-check me-1"></i> Proses Pindah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pasien Pulang / Discharge -->
<div class="modal fade" id="modalDischarge" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title fw-bold"><i class="fas fa-sign-out-alt me-1"></i> Pasien Pulang / Discharge Rawat Inap</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDischarge">
                @csrf
                <input type="hidden" name="discharge_bed_id" id="discharge_bed_id">
                <div class="modal-body p-3">
                    <div class="p-2 bg-light border rounded mb-3 small">
                        <div>Nama Pasien: <strong id="discharge_pasien_nama">-</strong></div>
                        <div>Kamar Ranap: <strong id="discharge_kamar_nama">-</strong></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Pasien Pulang <span class="text-danger">*</span></label>
                        <select name="status_pulang" class="form-select form-select-sm" required>
                            <option value="Sembuh">Sembuh / Perbaikan</option>
                            <option value="Rujuk">Dirujuk ke RS Lebih Tinggi</option>
                            <option value="Atas Permintaan Sendiri">Pulang Atas Permintaan Sendiri (PAPS)</option>
                            <option value="Meninggal">Meninggal Dunia</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Catatan Pemulangan</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Kondisi keluar, obat pulang, instruksi kontrol..."></textarea>
                    </div>
                    <div class="small text-muted mt-2">
                        * Biaya sewa kamar akan otomatis terakumulasi dan siap ditagihkan di modul Kasir.
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-check me-1"></i> Konfirmasi Pulang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Master Bed -->
<div class="modal fade" id="modalTambahBed" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-secondary text-white py-2 px-3">
                <h6 class="modal-title fw-bold"><i class="fas fa-plus me-1"></i> Tambah Master Tempat Tidur</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTambahBed">
                @csrf
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kamar" class="form-control form-control-sm" placeholder="Contoh: Kamar Mawar 02" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor / Kode Bed <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_bed" class="form-control form-control-sm" placeholder="Contoh: Bed 1A" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-select form-select-sm" required>
                                <option value="VIP">VIP</option>
                                <option value="Kelas 1">Kelas 1</option>
                                <option value="Kelas 2" selected>Kelas 2</option>
                                <option value="Kelas 3">Kelas 3</option>
                                <option value="Isolasi">Isolasi</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tarif / Hari (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="tarif_per_hari" class="form-control form-control-sm" value="250000" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan Bed</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tombol Admisi Cepat dari Bed Card
    $(document).off('click', '.btnAdmisiBed').on('click', '.btnAdmisiBed', function() {
        const id = $(this).data('id');
        $('#admisi_kamar_ranap_id').val(id);
        const modal = new bootstrap.Modal(document.getElementById('modalAdmisi'));
        modal.show();
    });

    // Submit Admisi
    $('#formAdmisi').off('submit').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.post('/rawat-inap/admisi', data, function(res) {
            if(res.success) {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message || 'Gagal admisi.');
            }
        }).fail(function(xhr) {
            alert(xhr.responseJSON?.message || 'Terjadi kesalahan sistem.');
        });
    });

    // Pindah Bed
    $(document).off('click', '.btnTransferBed').on('click', '.btnTransferBed', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        $('#transfer_bed_asal_id').val(id);
        $('#transfer_bed_asal_nama').val(nama);
        const modal = new bootstrap.Modal(document.getElementById('modalTransfer'));
        modal.show();
    });

    $('#formTransfer').off('submit').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.post('/rawat-inap/transfer', data, function(res) {
            if(res.success) {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message || 'Gagal transfer.');
            }
        }).fail(function(xhr) {
            alert(xhr.responseJSON?.message || 'Terjadi kesalahan sistem.');
        });
    });

    // Discharge / Pulang
    $(document).off('click', '.btnDischargeBed').on('click', '.btnDischargeBed', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const kamar = $(this).data('kamar');
        $('#discharge_bed_id').val(id);
        $('#discharge_pasien_nama').text(nama);
        $('#discharge_kamar_nama').text(kamar);
        const modal = new bootstrap.Modal(document.getElementById('modalDischarge'));
        modal.show();
    });

    $('#formDischarge').off('submit').on('submit', function(e) {
        e.preventDefault();
        const id = $('#discharge_bed_id').val();
        const data = $(this).serialize();
        $.post('/rawat-inap/' + id + '/discharge', data, function(res) {
            if(res.success) {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message || 'Gagal discharge.');
            }
        }).fail(function(xhr) {
            alert(xhr.responseJSON?.message || 'Terjadi kesalahan sistem.');
        });
    });

    // Tambah Master Bed
    $('#formTambahBed').off('submit').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.post('/rawat-inap/bed/store', data, function(res) {
            if(res.success) {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message || 'Gagal menyimpan bed.');
            }
        }).fail(function(xhr) {
            alert(xhr.responseJSON?.message || 'Terjadi kesalahan sistem.');
        });
    });
});
</script>
@endsection

