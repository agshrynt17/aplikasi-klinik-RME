@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'DASHBOARD - ' . ($namaKlinik ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="dashboard-container">
    <!-- Header Title -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">
            DASHBOARD - {{ $namaKlinik ?? 'KLINIK TELAGASARI 24 JAM' }}
        </h5>
    </div>

    <!-- Section 1: Ringkasan Pelayanan Hari Ini -->
    <div class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #444;">
        <span class="me-2" style="display: inline-block; width: 3px; height: 14px; background-color: #00a65a; border-radius: 2px;"></span>
        <i class="fas fa-chart-bar me-1 text-muted" style="font-size: 12px;"></i>
        <span>Ringkasan Pelayanan Hari Ini</span>
    </div>

    <div class="row g-2 mb-3">
        <!-- 1. Pasien Hari Ini -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #00c0ef;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="kpi-val">{{ $pasienHariIni ?? 112 }}</div>
                <div class="kpi-title">Pasien Hari Ini</div>
                <div class="kpi-trend text-danger">
                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> {{ $pasienHariIniTrend ?? '+41%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $pasienKemarin ?? 101 }}</div>
            </div>
        </div>

        <!-- 2. Pasien Bulan Ini -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #00a65a;">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <div class="kpi-val">{{ $pasienBulanIni ?? '2,324' }}</div>
                <div class="kpi-title">Pasien Bulan Ini</div>
                <div class="kpi-trend text-danger">
                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> {{ $pasienBulanIniTrend ?? '+63%' }}
                </div>
                <div class="kpi-sub">Bulan Lalu : {{ $pasienBulanLalu ?? '8,268' }}</div>
            </div>
        </div>

        <!-- 3. Resep Hari Ini -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #5c6ac4;">
                    <i class="fas fa-briefcase-medical"></i>
                </div>
                <div class="kpi-val">{{ $resepHariIni ?? 88 }}</div>
                <div class="kpi-title">Resep Hari Ini</div>
                <div class="kpi-trend text-danger">
                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> {{ $resepTrend ?? '+43%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $resepKemarin ?? 155 }}</div>
            </div>
        </div>

        <!-- 4. Lab Hari Ini -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #dd4b39;">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="kpi-val">{{ $labHariIni ?? 7 }}</div>
                <div class="kpi-title">Lab Hari Ini</div>
                <div class="kpi-trend text-danger">
                    <i class="fas fa-arrow-down" style="font-size: 9px;"></i> {{ $labTrend ?? '-37%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $labKemarin ?? 13 }}</div>
            </div>
        </div>

        <!-- 5. Radiologi -->
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

        <!-- 6. SATUSEHAT -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card text-center bg-white p-2 h-100 rounded-2 border">
                <div class="kpi-icon-box mx-auto mb-2 text-white" style="background-color: #00b4d8;">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div class="kpi-val">{{ $satusehatHariIni ?? 61 }}</div>
                <div class="kpi-title">SATUSEHAT</div>
                <div class="kpi-trend text-danger">
                    <i class="fas fa-arrow-up" style="font-size: 9px;"></i> {{ $satusehatTrend ?? '+38%' }}
                </div>
                <div class="kpi-sub">Kemarin : {{ $satusehatKemarin ?? 100 }}</div>
            </div>
        </div>
    </div>

    <!-- Section 2: Colorful Action Tiles -->
    <!-- Row 1 (4 modules) -->
    <div class="row g-2 mb-2">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-cyan">
                <div class="action-tile-body">
                    <div class="action-tile-title">Front Office (E-KTP)</div>
                    <div class="action-tile-subtitle">Transaksi</div>
                    <i class="fas fa-user-nurse action-tile-watermark"></i>
                </div>
                <a href="/pendaftaran-rajal" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-green">
                <div class="action-tile-body">
                    <div class="action-tile-title">Rekam Medis</div>
                    <div class="action-tile-subtitle">Transaksi</div>
                    <i class="fas fa-heartbeat action-tile-watermark"></i>
                </div>
                <a href="/rekam-medis/asesmen-awal-rajal" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-orange">
                <div class="action-tile-body">
                    <div class="action-tile-title">Radiologi</div>
                    <div class="action-tile-subtitle">Transaksi</div>
                    <i class="fas fa-th-large action-tile-watermark"></i>
                </div>
                <a href="/radiologi/transaksi" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-red">
                <div class="action-tile-body">
                    <div class="action-tile-title">Laboratorium</div>
                    <div class="action-tile-subtitle">Transaksi</div>
                    <i class="fas fa-plus action-tile-watermark"></i>
                </div>
                <a href="/laboratorium/transaksi-lab" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Row 2 (3 modules: Farmasi, Modul PPA, PMKP) -->
    <div class="row g-2 mb-2">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-purple">
                <div class="action-tile-body">
                    <div class="action-tile-title">Farmasi</div>
                    <div class="action-tile-subtitle">Transaksi</div>
                    <i class="fas fa-prescription-bottle-alt action-tile-watermark"></i>
                </div>
                <a href="/farmasi/resep" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-5">
            <div class="action-tile bg-tile-teal">
                <div class="action-tile-body">
                    <div class="action-tile-title">Modul PPA</div>
                    <div class="action-tile-subtitle">Transaksi</div>
                    <i class="fas fa-stethoscope action-tile-watermark"></i>
                </div>
                <a href="/rekam-medis/ppa" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-xl-4">
            <div class="action-tile bg-tile-gray">
                <div class="action-tile-body">
                    <div class="action-tile-title" style="color: #495057;">PMKP</div>
                    <div class="action-tile-subtitle" style="color: #6c757d;">Laporan</div>
                    <i class="fas fa-book action-tile-watermark" style="color: #6c757d; opacity: 0.16;"></i>
                </div>
                <a href="/laporan-pmkp/survey-kepuasan" class="action-tile-footer action-tile-footer-gray">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Row 3 (4 modules: MCU, Skrining, Bridging PCare, Bridging Satu Sehat) -->
    <div class="row g-2 mb-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-blue">
                <div class="action-tile-body">
                    <div class="action-tile-title">Medical Checkup</div>
                    <div class="action-tile-subtitle">Pemeriksaan Lengkap</div>
                    <i class="fas fa-clipboard-check action-tile-watermark"></i>
                </div>
                <a href="/rekam-medis/mcu" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-green-ilp">
                <div class="action-tile-body">
                    <div class="action-tile-title">Skrining Pasien</div>
                    <div class="action-tile-subtitle">Modul Pelayanan ILP</div>
                    <i class="fas fa-briefcase action-tile-watermark"></i>
                </div>
                <a href="/rekam-medis/skrining" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-amber">
                <div class="action-tile-body">
                    <div class="action-tile-title">Bridging PCare</div>
                    <div class="action-tile-subtitle">PCare 4.0</div>
                    <i class="fas fa-briefcase action-tile-watermark"></i>
                </div>
                <a href="/bridging/pcare" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="action-tile bg-tile-skyblue">
                <div class="action-tile-body">
                    <div class="action-tile-title">Bridging Satu Sehat</div>
                    <div class="action-tile-subtitle">Satu Sehat FHIR-R4</div>
                    <i class="fas fa-briefcase action-tile-watermark"></i>
                </div>
                <a href="/bridging/satusehat" class="action-tile-footer">
                    Open <i class="far fa-arrow-alt-circle-right ms-1"></i>
                </a>
            </div>
        </div>
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
        <span>Waktu Server : <span id="server-live-clock">14-10-2026 14:55:35</span> Zone : Asia/Jakarta Version 2.0</span>
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
        padding: 3px 0;
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
