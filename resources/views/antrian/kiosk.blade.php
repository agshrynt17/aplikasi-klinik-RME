<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cetak Tiket Antrian V2</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #ffffff;
            font-family: 'Open Sans', 'Roboto', 'Segoe UI', Tahoma, sans-serif;
            color: #333333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Top Header */
        .kiosk-header {
            padding: 12px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eaeaea;
            background: #ffffff;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .clinic-logo-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .clinic-logo-svg {
            width: 58px;
            height: 52px;
        }

        .clinic-logo-caption {
            font-size: 8.5px;
            font-weight: 800;
            color: #111;
            letter-spacing: 0.3px;
            margin-top: 2px;
            text-transform: uppercase;
        }

        .clinic-meta {
            display: flex;
            flex-direction: column;
            line-height: 1.35;
        }

        .clinic-title {
            font-size: 11px;
            font-weight: 800;
            color: #222222;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .clinic-address {
            font-size: 9.5px;
            font-weight: 600;
            color: #444444;
            text-transform: uppercase;
        }

        .clinic-phone {
            font-size: 9.5px;
            font-weight: 600;
            color: #444444;
        }

        .header-right {
            text-align: right;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-title {
            font-size: 15px;
            font-weight: 800;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .header-actions {
            display: flex;
            gap: 8px;
        }

        .btn-kiosk-nav {
            padding: 3px 8px;
            font-size: 11px;
            border-radius: 3px;
            text-decoration: none;
            color: #777;
            border: 1px solid #ccc;
            background: #fafafa;
            transition: all 0.2s;
        }
        .btn-kiosk-nav:hover {
            color: #337ab7;
            border-color: #337ab7;
            background: #fff;
        }

        /* Main Grid Area */
        .kiosk-main {
            padding: 25px 35px;
            flex: 1;
        }

        /* Queue Cards */
        .antrian-card {
            background: #ffffff;
            border: 1px solid #d2d6de;
            border-top: 4px solid #337ab7;
            border-radius: 2px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            padding: 16px 22px 18px 22px;
            position: relative;
            transition: box-shadow 0.2s ease, transform 0.15s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .antrian-card:hover {
            box-shadow: 0 3px 12px rgba(51, 122, 183, 0.15);
        }

        .watermark-printer {
            position: absolute;
            top: 14px;
            right: 20px;
            font-size: 36px;
            color: #d8dde2;
            opacity: 0.85;
            pointer-events: none;
        }

        .card-header-poli {
            text-align: center;
            margin-bottom: 14px;
        }

        .card-poli-title {
            font-size: 21px;
            font-weight: 800;
            color: #337ab7;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 1px;
            line-height: 1.2;
        }

        .card-dokter-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #00a65a;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            line-height: 1.2;
        }

        .card-info-table {
            width: 100%;
            margin-bottom: 14px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            font-weight: 700;
            color: #337ab7;
            padding: 3px 0;
        }

        .info-divider {
            border: 0;
            border-top: 1px solid #ececec;
            margin: 4px 0;
        }

        .btn-ambil-antrian {
            display: block;
            width: 100%;
            background-color: #337ab7;
            border-color: #2e6da4;
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            padding: 10px 14px;
            border-radius: 3px;
            letter-spacing: 0.4px;
            text-align: center;
            border: 1px solid transparent;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            transition: all 0.15s ease-in-out;
            outline: none;
        }

        .btn-ambil-antrian:hover {
            background-color: #286090;
            border-color: #204d74;
            color: #ffffff;
        }

        .btn-ambil-antrian:active {
            transform: scale(0.985);
            background-color: #204d74;
        }

        .btn-ambil-antrian:disabled {
            background-color: #8da4be;
            border-color: #8da4be;
            cursor: not-allowed;
            transform: none;
        }

        /* Footer */
        .kiosk-footer {
            border-top: 1px solid #eaeaea;
            padding: 10px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #555555;
            background: #ffffff;
        }

        .kiosk-footer a {
            color: #337ab7;
            text-decoration: none;
            font-weight: 600;
        }
        .kiosk-footer a:hover {
            text-decoration: underline;
        }

        /* Thermal Ticket Modal Styles */
        .modal-ticket {
            max-width: 360px;
        }

        .ticket-box {
            background: #ffffff;
            padding: 24px 20px;
            border-radius: 4px;
            border: 2px dashed #b0bec5;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            font-family: 'Courier New', Courier, monospace;
            text-align: center;
            color: #222;
        }

        .ticket-box .t-clinic {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .ticket-box .t-address {
            font-size: 11px;
            line-height: 1.25;
            color: #555;
            text-transform: uppercase;
        }

        .ticket-box .t-phone {
            font-size: 11px;
            color: #555;
            margin-bottom: 8px;
        }

        .ticket-box .t-divider {
            border-top: 1px dashed #777;
            margin: 8px 0;
        }

        .ticket-box .t-header-label {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .ticket-box .t-number {
            font-size: 42px;
            font-weight: 900;
            color: #000;
            letter-spacing: 2px;
            line-height: 1.1;
            margin: 5px 0 8px 0;
        }

        .ticket-box .t-poli {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            color: #222;
        }

        .ticket-box .t-doctor {
            font-size: 13px;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }

        .ticket-box .t-detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 11.5px;
            color: #333;
            margin: 2px 0;
        }

        .ticket-box .t-footer-msg {
            font-size: 11px;
            color: #444;
            margin-top: 6px;
            line-height: 1.3;
        }

        /* Print Media Query for Thermal 80mm / 58mm Printer */
        @page {
            size: 80mm auto;
            margin: 0;
        }

        @media print {
            html, body {
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }

            /* Sembunyikan semua elemen kiosk, header, footer, dan modal dialog */
            .kiosk-header,
            .kiosk-main,
            .kiosk-footer,
            .modal,
            .modal-backdrop,
            .modal-dialog,
            .modal-content,
            .fade,
            .no-print {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
            }

            /* Tampilkan HANYA area struk thermal */
            #printTicketArea {
                display: block !important;
                visibility: visible !important;
                position: relative !important;
                width: 72mm !important;
                max-width: 72mm !important;
                margin: 0 auto !important;
                padding: 6mm 3mm !important;
                box-sizing: border-box !important;
                font-family: 'Courier New', Courier, monospace !important;
                text-align: center !important;
                color: #000000 !important;
                background: #ffffff !important;
            }

            #printTicketArea .t-clinic {
                font-size: 15px !important;
                font-weight: 900 !important;
                text-transform: uppercase !important;
                line-height: 1.2 !important;
                margin-bottom: 2px !important;
            }
            #printTicketArea .t-address {
                font-size: 10px !important;
                line-height: 1.25 !important;
                text-transform: uppercase !important;
            }
            #printTicketArea .t-phone {
                font-size: 10px !important;
                margin-bottom: 4px !important;
            }
            #printTicketArea .t-divider {
                border-top: 1px dashed #000000 !important;
                margin: 6px 0 !important;
            }
            #printTicketArea .t-header-label {
                font-size: 12px !important;
                font-weight: bold !important;
                letter-spacing: 1px !important;
                text-transform: uppercase !important;
                margin: 3px 0 !important;
            }
            #printTicketArea .t-number {
                font-size: 46px !important;
                font-weight: 900 !important;
                letter-spacing: 2px !important;
                line-height: 1.1 !important;
                margin: 6px 0 !important;
            }
            #printTicketArea .t-poli {
                font-size: 16px !important;
                font-weight: 900 !important;
                text-transform: uppercase !important;
                margin-bottom: 2px !important;
            }
            #printTicketArea .t-doctor {
                font-size: 12px !important;
                font-weight: bold !important;
                margin-bottom: 4px !important;
            }
            #printTicketArea .t-detail-row {
                display: flex !important;
                justify-content: space-between !important;
                font-size: 11px !important;
                line-height: 1.35 !important;
                margin: 1px 0 !important;
            }
            #printTicketArea .t-footer-msg {
                font-size: 10.5px !important;
                margin-top: 6px !important;
                line-height: 1.3 !important;
            }
        }

        #printTicketArea {
            display: none;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header class="kiosk-header">
        <div class="header-left">
            <!-- Clinic Logo Matching Telagasari 24 Jam -->
            <div class="clinic-logo-wrapper">
                <div style="position: relative; width: 44px; height: 44px; display: inline-block;">
                    <i class="fas fa-leaf" style="color: #61b15a; font-size: 28px; position: absolute; bottom: 0; left: 2px; transform: rotate(-45deg);"></i>
                    <i class="fas fa-leaf" style="color: #5c3c92; font-size: 28px; position: absolute; bottom: 5px; left: -8px; transform: rotate(-90deg);"></i>
                    <i class="fas fa-leaf" style="color: #3b5998; font-size: 40px; position: absolute; top: -5px; right: 0; transform: rotate(15deg);"></i>
                    <i class="fas fa-plus" style="color: #e60000; font-size: 20px; position: absolute; top: 8px; right: 8px; font-weight: 900;"></i>
                </div>
                <div class="clinic-logo-caption" style="font-weight: 900; color: #1e5647;">KLINIK TELAGASARI</div>
            </div>

            <!-- Clinic Meta Info -->
            <div class="clinic-meta">
                <div class="clinic-title">{{ $namaFaskes ?? 'KLINIK TELAGASARI 24 JAM' }}</div>
                <div class="clinic-address">{{ $alamatFaskes ?? 'JLN. RAYA TELAGASARI NO. 24, KEC. TELAGASARI, KABUPATEN KARAWANG' }}</div>
                <div class="clinic-phone">Telp {{ $teleponFaskes ?? '(0264) 8303763' }}</div>
            </div>
        </div>

        <div class="header-right">
            <div class="header-title">CETAK ANTRIAN {{ strtoupper($namaFaskes ?? 'KLINIK TELAGASARI 24 JAM') }}</div>
            <div class="header-actions no-print">
                <a href="/admin" class="btn-kiosk-nav" title="Kembali ke Dashboard Admin">
                    <i class="fas fa-home me-1"></i> Dashboard
                </a>
                <button type="button" class="btn-kiosk-nav" id="btnToggleFullscreen" title="Layar Penuh">
                    <i class="fas fa-expand me-1"></i> Fullscreen
                </button>
            </div>
        </div>
    </header>

    <!-- Main Cards Section (2x2 Grid) -->
    <main class="kiosk-main">
        <div class="row g-4 justify-content-center">
            @foreach($lokets as $loket)
            @php
                $dokterNama = $loket->jadwal_aktif?->dokter?->nama_pegawai ?? 'DOKTER JAGA';
            @endphp
            <div class="col-md-6 col-12">
                <div class="antrian-card" id="card-loket-{{ $loket->id }}">
                    <!-- Watermark Printer Icon -->
                    <i class="fa fa-print watermark-printer"></i>

                    <!-- Card Header -->
                    <div class="card-header-poli">
                        <div class="card-poli-title">{{ $loket->nama_antrian }}</div>
                        <div class="card-dokter-title">{{ $dokterNama }}</div>
                    </div>

                    <!-- Card Details Table -->
                    <div class="card-info-table">
                        <div class="info-row">
                            <span>Jam Praktek</span>
                            <span>{{ $loket->jam_praktek_show ?? '06:00-22:00' }}</span>
                        </div>
                        <hr class="info-divider">
                        <div class="info-row">
                            <span>Ruangan</span>
                            <span>{{ $loket->ruangan_show ?? 'Ruang Rawat 1' }}</span>
                        </div>
                        <hr class="info-divider">
                        <div class="info-row">
                            <span>Terisi</span>
                            <span id="terisi-{{ $loket->id }}">{{ $loket->terisi_count ?? 0 }}</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button type="button" 
                            class="btn-ambil-antrian btn-trigger-antrian" 
                            data-id="{{ $loket->id }}"
                            data-nama="{{ $loket->nama_antrian }}"
                            data-dokter="{{ $dokterNama }}"
                            data-jam="{{ $loket->jam_praktek_show ?? '06:00-22:00' }}"
                            data-ruangan="{{ $loket->ruangan_show ?? 'Ruang Rawat 1' }}">
                        <i class="fas fa-ticket-alt me-1"></i> KLIK DISINI UNTUK AMBIL ANTRIAN
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    <!-- Footer Section -->
    <footer class="kiosk-footer">
        <div>
            Copyright &copy; 2026 <a href="https://konhakes.id" target="_blank">Konhakes.id</a>
        </div>
        <div>
            Waktu Server: <span id="clockDisplay">{{ date('d-m-Y H:i:s') }}</span> | Asia/Jakarta | Auto Reload : <span id="countdownDisplay">20</span> Detik
        </div>
    </footer>

    <!-- Ticket Popup Modal (Onsite Preview - Screen Only) -->
    <div class="modal fade no-print" id="modalTiketSukses" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-ticket">
            <div class="modal-content border-0 bg-transparent">
                <div class="ticket-box">
                    <div class="t-clinic" id="modalTicketClinic">{{ $namaFaskes ?? 'KLINIK TELAGASARI 24 JAM' }}</div>
                    <div class="t-address" id="modalTicketAddress">{{ $alamatFaskes ?? 'JLN. RAYA TELAGASARI NO. 24, KEC. TELAGASARI, KAB. KARAWANG' }}</div>
                    <div class="t-phone" id="modalTicketPhone">Telp {{ $teleponFaskes ?? '(0264) 8303763' }}</div>
                    <div class="t-divider"></div>
                    <div class="t-header-label">NOMOR ANTRIAN</div>
                    <div class="t-number" id="modalTicketCode">A058</div>
                    <div class="t-poli" id="modalTicketPoli">POLI DOKTER</div>
                    <div class="t-doctor" id="modalTicketDoctor">dr. VIERTA AJI NUR YASIN</div>
                    <div class="t-divider"></div>
                    <div class="t-detail-row">
                        <span>Ruangan:</span>
                        <span id="modalTicketRoom">Ruang Rawat 1</span>
                    </div>
                    <div class="t-detail-row">
                        <span>Jam Praktek:</span>
                        <span id="modalTicketHours">06:00-22:00</span>
                    </div>
                    <div class="t-detail-row">
                        <span>Waktu Cetak:</span>
                        <span id="modalTicketTime">{{ date('d-m-Y H:i:s') }}</span>
                    </div>
                    <div class="t-detail-row">
                        <span>Sisa Antrean:</span>
                        <span id="modalTicketRemaining">0 Orang</span>
                    </div>
                    <div class="t-divider"></div>
                    <div class="t-footer-msg">
                        <div>Simpan nomor antrian ini</div>
                        <strong>Terima Kasih Atas Kunjungan Anda</strong>
                    </div>

                    <div class="mt-3 d-flex gap-2 justify-content-center no-print">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" id="btnModalClose">
                            <i class="fas fa-times me-1"></i> Tutup (<span id="modalCountdown">4</span>s)
                        </button>
                        <button type="button" class="btn btn-sm btn-primary px-3" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Printable Ticket Template for Thermal Printer -->
    <div id="printTicketArea">
        <div class="t-clinic" id="pTicketClinic">{{ $namaFaskes ?? 'KLINIK TELAGASARI 24 JAM' }}</div>
        <div class="t-address" id="pTicketAddress">{{ $alamatFaskes ?? 'JLN. RAYA TELAGASARI NO. 24, KEC. TELAGASARI, KAB. KARAWANG' }}</div>
        <div class="t-phone" id="pTicketPhone">Telp {{ $teleponFaskes ?? '(0264) 8303763' }}</div>
        <div class="t-divider"></div>
        <div class="t-header-label">NOMOR ANTRIAN</div>
        <div class="t-number" id="pTicketCode">A058</div>
        <div class="t-poli" id="pTicketPoli">POLI DOKTER</div>
        <div class="t-doctor" id="pTicketDoctor">dr. VIERTA AJI NUR YASIN</div>
        <div class="t-divider"></div>
        <div class="t-detail-row">
            <span>Ruangan:</span>
            <span id="pTicketRoom">Ruang Rawat 1</span>
        </div>
        <div class="t-detail-row">
            <span>Jam Praktek:</span>
            <span id="pTicketHours">06:00-22:00</span>
        </div>
        <div class="t-detail-row">
            <span>Waktu Cetak:</span>
            <span id="pTicketTime">{{ date('d-m-Y H:i:s') }}</span>
        </div>
        <div class="t-detail-row">
            <span>Sisa Antrean:</span>
            <span id="pTicketRemaining">0 Orang</span>
        </div>
        <div class="t-divider"></div>
        <div class="t-footer-msg">
            <div>Simpan nomor antrian ini</div>
            <strong>Terima Kasih Atas Kunjungan Anda</strong>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Server Clock ticking
        let serverTimestamp = {{ time() * 1000 }};
        function updateServerClock() {
            serverTimestamp += 1000;
            const date = new Date(serverTimestamp);
            const pad = (n) => String(n).padStart(2, '0');
            const day = pad(date.getDate());
            const month = pad(date.getMonth() + 1);
            const year = date.getFullYear();
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());
            const seconds = pad(date.getSeconds());
            
            const clockEl = document.getElementById('clockDisplay');
            if (clockEl) {
                clockEl.textContent = `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
            }
        }
        setInterval(updateServerClock, 1000);

        // Auto Reload Countdown (20 seconds cycle)
        let countdown = 20;
        const countdownEl = document.getElementById('countdownDisplay');
        setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                countdown = 20;
                // If modal is not open, silently refresh page to sync counts from server
                const modalEl = document.getElementById('modalTiketSukses');
                const isModalOpen = modalEl && modalEl.classList.contains('show');
                if (!isModalOpen) {
                    location.reload();
                }
            }
            if (countdownEl) {
                countdownEl.textContent = countdown;
            }
        }, 1000);

        // Web Audio API Chime Bell Sound (Pure Web Audio, instant and reliable)
        function playChimeBell() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                
                // Tone 1: E5 (659.25Hz)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(659.25, ctx.currentTime);
                gain1.gain.setValueAtTime(0.4, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.8);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(ctx.currentTime);
                osc1.stop(ctx.currentTime + 0.8);

                // Tone 2: C5 (523.25Hz) after 200ms
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(523.25, ctx.currentTime + 0.22);
                gain2.gain.setValueAtTime(0.5, ctx.currentTime + 0.22);
                gain2.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 1.2);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(ctx.currentTime + 0.22);
                osc2.stop(ctx.currentTime + 1.2);
            } catch (err) {
                console.log("Audio not supported or permitted", err);
            }
        }

        // Handle Klik Ambil Antrian
        const ticketModal = new bootstrap.Modal(document.getElementById('modalTiketSukses'));
        let autoCloseTimer = null;
        let autoCloseSeconds = 4;

        document.querySelectorAll('.btn-trigger-antrian').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const btn = this;
                const loketId = btn.getAttribute('data-id');
                const defaultNama = btn.getAttribute('data-nama');
                const defaultDokter = btn.getAttribute('data-dokter');
                const defaultJam = btn.getAttribute('data-jam');
                const defaultRuangan = btn.getAttribute('data-ruangan');

                // Play bell chime
                playChimeBell();

                // Disable button during request
                btn.disabled = true;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> MEMPROSES...';

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/cetak-tiket', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        loket_id: loketId
                    })
                })
                .then(res => res.json())
                .then(res => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;

                    if (res.success && res.data) {
                        const d = res.data;

                        // Update terisi counter on card immediately
                        const terisiEl = document.getElementById('terisi-' + loketId);
                        if (terisiEl) {
                            terisiEl.textContent = d.total_terisi;
                        }

                        // Fill clinic info if returned
                        if (d.nama_faskes) {
                            const mc = document.getElementById('modalTicketClinic');
                            const pc = document.getElementById('pTicketClinic');
                            if (mc) mc.textContent = d.nama_faskes;
                            if (pc) pc.textContent = d.nama_faskes;
                        }
                        if (d.alamat_faskes) {
                            const ma = document.getElementById('modalTicketAddress');
                            const pa = document.getElementById('pTicketAddress');
                            if (ma) ma.textContent = d.alamat_faskes;
                            if (pa) pa.textContent = d.alamat_faskes;
                        }
                        if (d.telepon_faskes) {
                            const mt = document.getElementById('modalTicketPhone');
                            const pt = document.getElementById('pTicketPhone');
                            if (mt) mt.textContent = 'Telp ' + d.telepon_faskes;
                            if (pt) pt.textContent = 'Telp ' + d.telepon_faskes;
                        }

                        // Fill modal content
                        document.getElementById('modalTicketCode').textContent = d.kode_nomor;
                        document.getElementById('modalTicketPoli').textContent = d.nama_loket || defaultNama;
                        document.getElementById('modalTicketDoctor').textContent = d.nama_dokter || defaultDokter;
                        document.getElementById('modalTicketRoom').textContent = d.ruangan || defaultRuangan;
                        document.getElementById('modalTicketHours').textContent = d.jam_praktek || defaultJam;
                        document.getElementById('modalTicketTime').textContent = d.waktu;
                        document.getElementById('modalTicketRemaining').textContent = (d.sisa_antrean || 0) + ' Orang';

                        // Fill print template
                        document.getElementById('pTicketCode').textContent = d.kode_nomor;
                        document.getElementById('pTicketPoli').textContent = d.nama_loket || defaultNama;
                        document.getElementById('pTicketDoctor').textContent = d.nama_dokter || defaultDokter;
                        document.getElementById('pTicketRoom').textContent = d.ruangan || defaultRuangan;
                        document.getElementById('pTicketHours').textContent = d.jam_praktek || defaultJam;
                        document.getElementById('pTicketTime').textContent = d.waktu;
                        document.getElementById('pTicketRemaining').textContent = (d.sisa_antrean || 0) + ' Orang';

                        // Show modal
                        ticketModal.show();

                        // Auto-print thermal ticket (silent if configured, or browser dialog)
                        setTimeout(() => {
                            try {
                                window.print();
                            } catch (e) {
                                console.log("Print canceled or blocked", e);
                            }
                        }, 500);

                        // Start countdown to auto close modal and return kiosk to idle
                        autoCloseSeconds = 4;
                        const closeCountdownEl = document.getElementById('modalCountdown');
                        if (closeCountdownEl) closeCountdownEl.textContent = autoCloseSeconds;

                        if (autoCloseTimer) clearInterval(autoCloseTimer);
                        autoCloseTimer = setInterval(() => {
                            autoCloseSeconds--;
                            if (closeCountdownEl) closeCountdownEl.textContent = autoCloseSeconds;
                            if (autoCloseSeconds <= 0) {
                                clearInterval(autoCloseTimer);
                                ticketModal.hide();
                            }
                        }, 1000);
                    } else {
                        alert(res.message || 'Gagal membuat tiket antrian.');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    console.error(err);
                    alert('Terjadi kesalahan koneksi server saat mencetak antrian.');
                });
            });
        });

        // Fullscreen Toggle
        const btnFullscreen = document.getElementById('btnToggleFullscreen');
        if (btnFullscreen) {
            btnFullscreen.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().catch(err => {
                        console.log("Fullscreen request failed", err);
                    });
                    this.innerHTML = '<i class="fas fa-compress me-1"></i> Exit';
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                        this.innerHTML = '<i class="fas fa-expand me-1"></i> Fullscreen';
                    }
                }
            });
        }
    </script>
</body>
</html>
