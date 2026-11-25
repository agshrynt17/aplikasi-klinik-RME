@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'DASHBOARD - ' . ($namaKlinik ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="dashboard-container">
    @php
        $u = auth()->user();
        $userName = $u ? $u->name : 'Admin Klinik';
        $userRole = $u ? $u->role_name : 'Administrator';
    @endphp

    <!-- Header Title -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">
            DASHBOARD - {{ $namaKlinik ?? 'KLINIK TELAGASARI 24 JAM' }}
        </h5>
        <span class="badge bg-primary px-3 py-2 fw-semibold" style="font-size: 12px; border-radius: 4px;">
            <i class="fas fa-user-shield me-1"></i> Mode: {{ $userRole }}
        </span>
    </div>

    <!-- Welcome & Role Banner -->
    <div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #004aad 0%, #0077b6 100%); color: white; border-radius: 6px;">
        <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fas fa-user-md"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="font-size: 1.05rem;">
                        Selamat Datang, {{ $userName }}!
                    </h6>
                    <p class="mb-0" style="opacity: 0.95; font-size: 12px;">
                        Anda login dengan Hak Akses: <span class="badge bg-warning text-dark fw-bold px-2 py-1">{{ $userRole }}</span> &bull; Modul dan menu di layar ini telah dipersonalisasi sesuai wewenang tugas Anda.
                    </p>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex gap-2 align-items-center">
                <span class="badge bg-white text-dark px-3 py-2 fw-bold shadow-sm" style="font-size: 11px; border-radius: 20px;">
                    <i class="fas fa-check-circle text-success me-1"></i> Hak Akses Aktif
                </span>
            </div>
        </div>
    </div>

    <!-- Section 1: Ringkasan Pelayanan Hari Ini -->
    <div class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #444;">
        <span class="me-2" style="display: inline-block; width: 3px; height: 14px; background-color: #00a65a; border-radius: 2px;"></span>
        <i class="fas fa-chart-bar me-1 text-muted" style="font-size: 12px;"></i>
        <span>Ringkasan Pelayanan Hari Ini</span>
    </div>

    <div class="row g-2 mb-3">
        <!-- 1. Pasien Hari Ini -->
        @if(!$u || $u->isAdministrator() || $u->hasPermission(['master_pasien', 'pendaftaran_rajal', 'rekam_medis', 'kasir']))
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #00c0ef;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="kpi-val">{{ $pasienHariIni ?? 0 }}</div>
                <div class="kpi-title">Pasien Hari Ini</div>
                <div class="kpi-trend {{ ($pasienHariIni ?? 0) > 0 ? 'text-success' : 'text-muted' }}">
                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> {{ $pasienHariIniTrend ?? '0%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $pasienKemarin ?? 0 }}</div>
            </div>
        </div>
        @endif

        <!-- 2. Pasien Bulan Ini -->
        @if(!$u || $u->isAdministrator() || $u->hasPermission(['pendaftaran_rajal', 'rekam_medis', 'laporan_keuangan']))
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #00a65a;">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <div class="kpi-val">{{ $pasienBulanIni ?? 0 }}</div>
                <div class="kpi-title">Pasien Bulan Ini</div>
                <div class="kpi-trend {{ ($pasienBulanIni ?? 0) > 0 ? 'text-success' : 'text-muted' }}">
                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> {{ $pasienBulanIniTrend ?? '0%' }}
                </div>
                <div class="kpi-sub">Bulan Lalu : {{ $pasienBulanLalu ?? 0 }}</div>
            </div>
        </div>
        @endif

        <!-- 3. Resep Hari Ini -->
        @if(!$u || $u->isAdministrator() || $u->hasPermission(['farmasi_resep', 'master_obat', 'rekam_medis']))
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #5c6ac4;">
                    <i class="fas fa-briefcase-medical"></i>
                </div>
                <div class="kpi-val">{{ $resepHariIni ?? 0 }}</div>
                <div class="kpi-title">Resep Hari Ini</div>
                <div class="kpi-trend {{ ($resepHariIni ?? 0) > 0 ? 'text-success' : 'text-muted' }}">
                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> {{ $resepTrend ?? '0%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $resepKemarin ?? 0 }}</div>
            </div>
        </div>
        @endif

        <!-- 4. Lab Hari Ini -->
        @if(!$u || $u->isAdministrator() || $u->hasPermission(['laboratorium', 'rekam_medis']))
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #dd4b39;">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="kpi-val">{{ $labHariIni ?? 0 }}</div>
                <div class="kpi-title">Lab Hari Ini</div>
                <div class="kpi-trend {{ ($labHariIni ?? 0) > 0 ? 'text-success' : 'text-muted' }}">
                    <i class="fas fa-arrow-down" style="font-size: 9px;"></i> {{ $labTrend ?? '0%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $labKemarin ?? 0 }}</div>
            </div>
        </div>
        @endif

        <!-- 5. Radiologi -->
        @if(!$u || $u->isAdministrator() || $u->hasPermission(['radiologi', 'rekam_medis']))
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #f39c12;">
                    <i class="fas fa-th-large"></i>
                </div>
                <div class="kpi-val">{{ $radHariIni ?? 0 }}</div>
                <div class="kpi-title">Radiologi</div>
                <div class="kpi-trend text-muted">
                    - {{ $radTrend ?? '0%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $radKemarin ?? 0 }}</div>
            </div>
        </div>
        @endif

        <!-- 6. SATUSEHAT -->
        @if(!$u || $u->isAdministrator() || $u->hasPermission(['bridging_satusehat', 'bridging_bpjs']))
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #00b4d8;">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div class="kpi-val">{{ $satusehatHariIni ?? 0 }}</div>
                <div class="kpi-title">SATUSEHAT</div>
                <div class="kpi-trend {{ ($satusehatHariIni ?? 0) > 0 ? 'text-success' : 'text-muted' }}">
                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> {{ $satusehatTrend ?? '0%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $satusehatKemarin ?? 0 }}</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Section 2: Quick Action Tiles (Role-Filtered) -->
    <div class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #444;">
        <span class="me-2" style="display: inline-block; width: 3px; height: 14px; background-color: #004aad; border-radius: 2px;"></span>
        <i class="fas fa-th-large me-1 text-muted" style="font-size: 12px;"></i>
        <span>Menu Cepat & Pelayanan</span>
    </div>

    <div class="row g-2 mb-3">
        <!-- Front Office (E-KTP) -->
        @if(!$u || $u->hasPermission('pendaftaran_rajal'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-cyan">
                <div class="action-tile-body">
                    <div class="action-tile-title">Front Office (E-KTP)</div>
                    <div class="action-tile-subtitle">Pendaftaran Pasien Rajal</div>
                    <i class="fas fa-user-nurse action-tile-watermark"></i>
                </div>
                <a href="/pendaftaran-rajal" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Master Data Pasien -->
        @if(!$u || $u->hasPermission('master_pasien'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-blue">
                <div class="action-tile-body">
                    <div class="action-tile-title">Master Pasien</div>
                    <div class="action-tile-subtitle">Database & Riwayat Pasien</div>
                    <i class="fas fa-id-card action-tile-watermark"></i>
                </div>
                <a href="/master-pasien" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Antrian Cetak Tiket & Display -->
        @if(!$u || $u->hasPermission('antrian'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-amber">
                <div class="action-tile-body">
                    <div class="action-tile-title">Antrian & Tiket</div>
                    <div class="action-tile-subtitle">Cetak Tiket & TV Display</div>
                    <i class="fas fa-ticket-alt action-tile-watermark"></i>
                </div>
                <a href="/cetak-tiket" target="_blank" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Rekam Medis -->
        @if(!$u || $u->hasPermission(['rekam_medis', 'asesmen_rajal']))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-green">
                <div class="action-tile-body">
                    <div class="action-tile-title">Rekam Medis (RME)</div>
                    <div class="action-tile-subtitle">Asesmen Awal & Pemeriksaan</div>
                    <i class="fas fa-heartbeat action-tile-watermark"></i>
                </div>
                <a href="/rekam-medis/asesmen-awal-rajal" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Modul PPA -->
        @if(!$u || $u->hasPermission(['rekam_medis', 'asesmen_rajal']))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-teal">
                <div class="action-tile-body">
                    <div class="action-tile-title">Modul PPA</div>
                    <div class="action-tile-subtitle">Pelayanan Pasien Terintegrasi</div>
                    <i class="fas fa-stethoscope action-tile-watermark"></i>
                </div>
                <a href="/moduldokter/index" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Pelayanan Kebidanan (ANC, INC, PNC) -->
        @if(!$u || $u->hasPermission('kebidanan'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-pink">
                <div class="action-tile-body">
                    <div class="action-tile-title">Pelayanan Kebidanan</div>
                    <div class="action-tile-subtitle">ANC, INC, PNC & Partograf</div>
                    <i class="fas fa-baby action-tile-watermark"></i>
                </div>
                <a href="/anc/index" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Laboratorium -->
        @if(!$u || $u->hasPermission('laboratorium'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-red">
                <div class="action-tile-body">
                    <div class="action-tile-title">Laboratorium</div>
                    <div class="action-tile-subtitle">Transaksi & Entry Hasil Lab</div>
                    <i class="fas fa-flask action-tile-watermark"></i>
                </div>
                <a href="/laboratorium/transaksi-lab" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Radiologi -->
        @if(!$u || $u->hasPermission('radiologi'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-orange">
                <div class="action-tile-body">
                    <div class="action-tile-title">Radio-Diagnostik</div>
                    <div class="action-tile-subtitle">Rontgen & Hasil Radiologi</div>
                    <i class="fas fa-th-large action-tile-watermark"></i>
                </div>
                <a href="/radiologi/transaksi" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Farmasi Resep -->
        @if(!$u || $u->hasPermission('farmasi_resep'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-purple">
                <div class="action-tile-body">
                    <div class="action-tile-title">Farmasi Resep</div>
                    <div class="action-tile-subtitle">Verifikasi & Penyerahan Obat</div>
                    <i class="fas fa-prescription-bottle-alt action-tile-watermark"></i>
                </div>
                <a href="/transaksiresep/index" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Penjualan Apotek (POS) -->
        @if(!$u || $u->hasPermission('penjualan_apotek'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-emerald">
                <div class="action-tile-body">
                    <div class="action-tile-title">Penjualan Bebas Apotek</div>
                    <div class="action-tile-subtitle">Point of Sale (POS) Kasir Obat</div>
                    <i class="fas fa-shopping-cart action-tile-watermark"></i>
                </div>
                <a href="/transaksipenjualan/index" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Master & Stok Obat -->
        @if(!$u || $u->hasPermission('master_obat'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-indigo">
                <div class="action-tile-body">
                    <div class="action-tile-title">Stok & Master Obat</div>
                    <div class="action-tile-subtitle">Katalog Barang, Batch & Harga</div>
                    <i class="fas fa-boxes action-tile-watermark"></i>
                </div>
                <a href="/barang/index" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Kasir & Pembayaran -->
        @if(!$u || $u->hasPermission('kasir'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-green">
                <div class="action-tile-body">
                    <div class="action-tile-title">Kasir & Billing</div>
                    <div class="action-tile-subtitle">Pembayaran Tagihan & Kwitansi</div>
                    <i class="fas fa-cash-register action-tile-watermark"></i>
                </div>
                <a href="/transaksi-kasir" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Laporan Keuangan / Kasir -->
        @if(!$u || $u->hasPermission('laporan_keuangan'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-teal">
                <div class="action-tile-body">
                    <div class="action-tile-title">Laporan Keuangan</div>
                    <div class="action-tile-subtitle">Rekap Transaksi Kasir & Jasa</div>
                    <i class="fas fa-file-invoice-dollar action-tile-watermark"></i>
                </div>
                <a href="/backoffice/laporan-kasir" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Skrining Pasien -->
        @if(!$u || $u->hasPermission('skrining'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-green-ilp">
                <div class="action-tile-body">
                    <div class="action-tile-title">Skrining Pasien</div>
                    <div class="action-tile-subtitle">Modul Pelayanan ILP</div>
                    <i class="fas fa-diagnoses action-tile-watermark"></i>
                </div>
                <a href="/skrining/index" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Medical Checkup (MCU) -->
        @if(!$u || $u->hasPermission('rekam_medis'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-blue">
                <div class="action-tile-body">
                    <div class="action-tile-title">Medical Checkup</div>
                    <div class="action-tile-subtitle">Pemeriksaan Lengkap MCU</div>
                    <i class="fas fa-clipboard-check action-tile-watermark"></i>
                </div>
                <a href="/moduldokter/index-mcu" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Bridging PCare & i-Care -->
        @if(!$u || $u->hasPermission('bridging_bpjs'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-amber">
                <div class="action-tile-body">
                    <div class="action-tile-title">Bridging PCare</div>
                    <div class="action-tile-subtitle">BPJS PCare 4.0 & i-Care</div>
                    <i class="fas fa-rss action-tile-watermark"></i>
                </div>
                <a href="/bridging/pcare" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Bridging Satu Sehat -->
        @if(!$u || $u->hasPermission('bridging_satusehat'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-skyblue">
                <div class="action-tile-body">
                    <div class="action-tile-title">Bridging Satu Sehat</div>
                    <div class="action-tile-subtitle">Kemenkes Satu Sehat FHIR-R4</div>
                    <i class="fas fa-hospital action-tile-watermark"></i>
                </div>
                <a href="/satu-sehat/index" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Laporan PMKP -->
        @if(!$u || $u->hasPermission(['laporan_keuangan', 'profil_faskes', 'manajemen_user']))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-gray">
                <div class="action-tile-body">
                    <div class="action-tile-title" style="color: #495057;">PMKP</div>
                    <div class="action-tile-subtitle" style="color: #6c757d;">Mutu & Survey Kepuasan</div>
                    <i class="fas fa-book action-tile-watermark" style="color: #6c757d; opacity: 0.16;"></i>
                </div>
                <a href="/laporan-pmkp/survey-kepuasan" class="action-tile-footer action-tile-footer-gray">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Pengaturan Sistem (Admin) -->
        @if(!$u || $u->isAdministrator() || $u->hasPermission('manajemen_user'))
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="action-tile bg-tile-dark">
                <div class="action-tile-body">
                    <div class="action-tile-title">Setting & User</div>
                    <div class="action-tile-subtitle">Manajemen User & Hak Akses</div>
                    <i class="fas fa-users-cog action-tile-watermark"></i>
                </div>
                <a href="/user/admin/index" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Section 3: Status Integrasi & Informasi Layanan (2 Columns) -->
    <div class="row g-2 mb-3">
        <!-- Status Integrasi -->
        <div class="col-12 col-lg-6">
            <div class="card border rounded-2 shadow-none h-100 bg-white">
                <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center">
                    <i class="fas fa-random text-info me-2" style="font-size: 13px;"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 13px;">Status Integrasi</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                            <span class="fw-semibold text-dark" style="font-size: 13px;">SATUSEHAT</span>
                        </div>
                        <span class="badge" style="background-color: #00a65a; font-weight: 700; font-size: 11px; padding: 4px 12px; border-radius: 3px; letter-spacing: 0.5px;">ONLINE</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                            <span class="fw-semibold text-dark" style="font-size: 13px;">BPJS Antrean</span>
                        </div>
                        <span class="badge" style="background-color: #00a65a; font-weight: 700; font-size: 11px; padding: 4px 12px; border-radius: 3px; letter-spacing: 0.5px;">ONLINE</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                            <span class="fw-semibold text-dark" style="font-size: 13px;">BPJS PCare</span>
                        </div>
                        <span class="badge" style="background-color: #00a65a; font-weight: 700; font-size: 11px; padding: 4px 12px; border-radius: 3px; letter-spacing: 0.5px;">ONLINE</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Layanan -->
        <div class="col-12 col-lg-6">
            <div class="card border rounded-2 shadow-none h-100 bg-white">
                <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shield-alt text-muted me-2" style="font-size: 13px;"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 13px;">Informasi Layanan</h6>
                    </div>
                    <button class="btn btn-sm btn-success py-1 px-3" style="background-color: #00a65a; border-color: #00a65a; font-size: 11px; font-weight: 600; border-radius: 3px;">
                        Klaim Langganan
                    </button>
                </div>
                <div class="card-body p-3">
                    <div class="row align-items-center py-1">
                        <div class="col-4 text-muted" style="font-size: 12.5px;">Status</div>
                        <div class="col-8">
                            <span class="badge" style="background-color: #00a65a; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 3px;">AKTIF</span>
                        </div>
                    </div>
                    <div class="row align-items-center py-1">
                        <div class="col-4 text-muted" style="font-size: 12.5px;">Paket</div>
                        <div class="col-8 fw-bold text-dark" style="font-size: 12.5px;">GOLD BISNIS</div>
                    </div>
                    <div class="row align-items-center py-1">
                        <div class="col-4 text-muted" style="font-size: 12.5px;">Durasi</div>
                        <div class="col-8 fw-semibold text-dark" style="font-size: 12.5px;">12 Bulan</div>
                    </div>
                    <div class="row align-items-center py-1">
                        <div class="col-4 text-muted" style="font-size: 12.5px;">Berakhir</div>
                        <div class="col-8 fw-semibold text-dark" style="font-size: 12.5px;">22 Jun 2027</div>
                    </div>
                    <div class="row align-items-center py-1">
                        <div class="col-4 text-muted" style="font-size: 12.5px;">Sisa Hari</div>
                        <div class="col-8">
                            <span class="badge" style="background-color: #343a40; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 3px;">251 Hari</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Server Time & Version -->
    <div class="d-flex justify-content-end align-items-center pt-2 pb-3 text-muted" style="font-size: 11px;">
        <span>Waktu Server : <span id="server-live-clock">{{ date('d-m-Y H:i:s') }}</span> Zone : Asia/Jakarta Version 1.0</span>
    </div>
</div>

<style>
    /* Styling Container */
    .dashboard-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* KPI Cards */
    .kpi-card {
        border-color: #e2e8f0 !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.06) !important;
    }
    .kpi-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .kpi-val {
        font-size: 1.45rem;
        font-weight: 700;
        color: #212529;
        line-height: 1.15;
        margin-bottom: 2px;
    }
    .kpi-title {
        font-size: 11px;
        color: #6c757d;
        margin-bottom: 2px;
    }
    .kpi-trend {
        font-size: 10.5px;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .kpi-sub {
        font-size: 10px;
        color: #888888;
    }

    @media (max-width: 575.98px) {
        .kpi-card {
            padding: 8px 6px !important;
        }
        .kpi-icon-box {
            width: 32px;
            height: 32px;
            font-size: 15px;
            margin-bottom: 6px !important;
        }
        .kpi-val {
            font-size: 1.25rem;
        }
        .kpi-title {
            font-size: 10px;
        }
        .kpi-trend {
            font-size: 9.5px;
        }
        .kpi-sub {
            font-size: 9px;
        }
    }

    /* Action Tiles */
    .action-tile {
        position: relative;
        border-radius: 4px;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .action-tile:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    }
    .action-tile-body {
        padding: 12px 14px 8px 14px;
        position: relative;
        z-index: 1;
        min-height: 68px;
    }
    .action-tile-title {
        font-size: 13.5px;
        font-weight: 700;
        margin-bottom: 2px;
        line-height: 1.2;
    }
    .action-tile-subtitle {
        font-size: 11px;
        opacity: 0.85;
        font-weight: 400;
        display: block;
    }
    .action-tile-watermark {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3.8rem;
        opacity: 0.18;
        z-index: 0;
        pointer-events: none;
    }
    .action-tile-footer {
        display: block;
        background: rgba(0, 0, 0, 0.13);
        color: rgba(255, 255, 255, 0.9);
        text-align: center;
        padding: 4px 0;
        font-size: 11.5px;
        text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease;
        position: relative;
        z-index: 1;
    }
    .action-tile-footer:hover {
        background: rgba(0, 0, 0, 0.25);
        color: #ffffff;
    }

    /* Action Tile Palette */
    .bg-tile-cyan { background-color: #00a8e8; }
    .bg-tile-green { background-color: #00a65a; }
    .bg-tile-orange { background-color: #f39c12; }
    .bg-tile-red { background-color: #dd4b39; }
    .bg-tile-purple { background-color: #5c6ac4; }
    .bg-tile-teal { background-color: #20c997; }
    .bg-tile-gray { background-color: #e2e8f0; color: #333 !important; }
    .action-tile-footer-gray {
        background: #cbd5e1 !important;
        color: #475569 !important;
    }
    .action-tile-footer-gray:hover {
        background: #94a3b8 !important;
        color: #1e293b !important;
    }
    .bg-tile-blue { background-color: #0077b6; }
    .bg-tile-green-ilp { background-color: #00a86b; }
    .bg-tile-amber { background-color: #f59e0b; }
    .bg-tile-skyblue { background-color: #00b4d8; }
    .bg-tile-pink { background-color: #e83e8c; }
    .bg-tile-emerald { background-color: #059669; }
    .bg-tile-indigo { background-color: #4f46e5; }
    .bg-tile-dark { background-color: #334155; }
</style>

<script>
$(document).ready(function() {
    // Live Server Clock
    function updateServerClock() {
        const now = new Date();
        const d = String(now.getDate()).padStart(2, '0');
        const m = String(now.getMonth() + 1).padStart(2, '0');
        const y = now.getFullYear();
        const hh = String(now.getHours()).padStart(2, '0');
        const mm = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');
        $('#server-live-clock').text(`${d}-${m}-${y} ${hh}:${mm}:${ss}`);
    }
    setInterval(updateServerClock, 1000);
    updateServerClock();
});
</script>
@endsection
