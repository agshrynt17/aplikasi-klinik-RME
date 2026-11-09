<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Antrian</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'Open Sans', 'Roboto', 'Segoe UI', Tahoma, sans-serif;
            color: #333333;
            min-height: 100vh;
            padding: 12px 16px;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Top Bar Utilities (Subtle / Non-intrusive) */
        .tv-top-bar {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 8px;
        }
        .btn-tv-nav {
            padding: 2px 8px;
            font-size: 11px;
            color: #666;
            background: #fff;
            border: 1px solid #d2d6de;
            border-radius: 3px;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-tv-nav:hover {
            color: #337ab7;
            border-color: #337ab7;
        }

        /* Top Split Layout (Call Box on Left, Slider on Right) */
        .top-split-container {
            display: flex;
            gap: 12px;
            min-height: 380px;
            margin-bottom: 14px;
        }

        .call-display-box {
            flex: 1;
            background: #ffffff;
            border: 1px solid #dcdcdc;
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .box-header-title {
            text-align: center;
            font-size: 15px;
            font-weight: 800;
            color: #333333;
            text-transform: uppercase;
            padding: 9px 15px;
            border-bottom: 1px solid #ebebeb;
            background: #ffffff;
            letter-spacing: 0.5px;
        }

        .call-content-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
        }

        .pill-badge-cyan {
            background-color: #00c0ef;
            color: #ffffff;
            border-radius: 20px;
            padding: 3px 18px;
            font-size: 11.5px;
            font-weight: 700;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .big-ticket-number {
            font-size: 68px;
            font-weight: 900;
            color: #337ab7;
            margin: 12px 0 8px 0;
            line-height: 1;
            letter-spacing: 2px;
            transition: all 0.3s ease;
        }

        .pill-badge-orange {
            background-color: #f39c12;
            color: #ffffff;
            border-radius: 20px;
            padding: 3px 18px;
            font-size: 11.5px;
            font-weight: 700;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .big-loket-title {
            font-size: 38px;
            font-weight: 900;
            color: #337ab7;
            text-transform: uppercase;
            margin-top: 10px;
            line-height: 1.1;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        /* Slider Box */
        .slider-display-box {
            flex: 1;
            background: #ffffff;
            border: 1px solid #dcdcdc;
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        .slider-header-title {
            font-size: 11.5px;
            font-weight: 800;
            color: #333333;
            text-transform: uppercase;
            padding: 8px 15px;
            border-bottom: 1px solid #ebebeb;
            background: #ffffff;
            letter-spacing: 0.5px;
        }

        .carousel-container {
            flex: 1;
            position: relative;
            background: #ffffff;
            overflow: hidden;
        }

        .carousel-item {
            height: 330px;
            text-align: center;
        }

        .carousel-item img {
            max-height: 330px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            margin: 0 auto;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 35px;
            opacity: 0.7;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(50%);
        }

        /* Doctor Poster Flyer Styles (1:1 with Screenshot 2) */
        .doctor-poster-flyer {
            width: 330px;
            height: 310px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(135deg, #ffffff 60%, #e0f2fe 100%);
        }
        .doctor-poster-flyer::before {
            content: '';
            position: absolute;
            top: -35px;
            right: -35px;
            width: 170px;
            height: 170px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(167, 243, 208, 0.45) 0%, rgba(186, 230, 253, 0.25) 70%, transparent 100%);
            z-index: 1;
        }

        .poster-top-badges {
            position: absolute;
            top: 8px;
            left: 10px;
            z-index: 5;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .kemenkes-badge {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 12px;
            padding: 1px 6px;
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 8px;
        }
        .kemenkes-badge .stars {
            color: #eab308;
            font-size: 7px;
            letter-spacing: 0.5px;
        }
        .faskes-badge {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
        }

        .poster-body {
            display: flex;
            align-items: center;
            padding: 30px 14px 10px 14px;
            position: relative;
            z-index: 2;
            flex: 1;
        }
        .poster-doctor-figure {
            width: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .doctor-avatar-circle {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            background: #f1f5f9;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .doctor-icon-hijab {
            font-size: 55px;
            color: #334155;
        }
        .doctor-icon-male {
            font-size: 55px;
            color: #1e3a8a;
        }
        .stethoscope-pin {
            position: absolute;
            bottom: 2px;
            right: 2px;
            background: #0ea5e9;
            color: #ffffff;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            border: 2px solid #ffffff;
        }

        .poster-schedule-info {
            flex: 1;
            padding-left: 10px;
        }
        .poster-poli-title {
            font-size: 20px;
            font-weight: 800;
            color: #1e3a8a;
            margin: 0 0 2px 0;
            line-height: 1.1;
        }
        .poster-days-text {
            font-size: 11px;
            color: #475569;
            margin: 0 0 8px 0;
            font-weight: 600;
        }
        .poster-hours-badge {
            background-color: #38bdf8;
            color: #0c4a6e;
            border-radius: 6px;
            padding: 4px 8px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(14, 165, 233, 0.2);
        }
        .hours-label {
            font-size: 8.5px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
        }
        .hours-time {
            font-size: 12px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: 0.5px;
        }

        .poster-footer {
            display: flex;
            align-items: stretch;
            border-top: 1px solid #f1f5f9;
            background: #ffffff;
            position: relative;
            z-index: 2;
        }
        .poster-doc-name-pill {
            background-color: #14532d;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            white-space: nowrap;
            letter-spacing: 0.3px;
        }
        .poster-contact-block {
            padding: 4px 8px;
            font-size: 7.5px;
            color: #475569;
            line-height: 1.25;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .contact-label {
            font-weight: 700;
            color: #0f172a;
        }
        .contact-phone {
            font-weight: 800;
            color: #0369a1;
            font-size: 9px;
        }
        .contact-addr {
            color: #64748b;
        }

        /* Bottom Waiting Queues Section */
        .waiting-section-header {
            font-size: 12.5px;
            font-weight: 700;
            color: #444444;
            margin-bottom: 8px;
        }

        .waiting-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 2px;
            padding: 6px 4px 8px 4px;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            position: relative;
            transition: transform 0.15s ease;
        }
        .waiting-card:hover {
            transform: translateY(-1px);
        }

        .waiting-poli-name {
            font-size: 9.5px;
            font-weight: 700;
            color: #f39c12;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .waiting-ticket-code {
            font-size: 19px;
            font-weight: 700;
            color: #555555;
            line-height: 1;
            letter-spacing: 0.5px;
        }

        .waiting-card-border {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #f39c12;
        }
    </style>
</head>
<body>

    <!-- Subtle Navigation Bar (Top Right) -->
    <div class="tv-top-bar no-print">
        <a href="/dashboard-antrian" class="btn-tv-nav" title="Kembali ke Pilih Layanan">
            <i class="fas fa-arrow-left me-1"></i> Pilih Layanan
        </a>
        <button type="button" class="btn-tv-nav" id="btnToggleFullscreen" title="Layar Penuh TV">
            <i class="fas fa-expand me-1"></i> Fullscreen
        </button>
        <button type="button" class="btn-tv-nav" id="btnAudioToggle" title="Audio Suara Panggilan">
            <i class="fas fa-volume-up me-1"></i> Suara: ON
        </button>
    </div>

    <!-- Top Split Container (Called Queue Box + Media Slider) -->
    <div class="top-split-container">
        
        <!-- Left Box: Currently Called Ticket -->
        <div class="call-display-box">
            <div class="box-header-title">{{ $currentCalled->pelayanan ?? 'PELAYANAN' }}</div>
            <div class="call-content-body">
                <div class="pill-badge-cyan">Antrian No</div>
                <div class="big-ticket-number" id="currentCalledNumber">{{ $currentCalled->kode_nomor ?? 'AD061' }}</div>
                <div class="pill-badge-orange">Menuju</div>
                <div class="big-loket-title" id="currentCalledLoket">{{ $currentCalled->nama_loket ?? 'POLI DOKTER' }}</div>
            </div>
        </div>

        <!-- Right Box: Media Slider Carousel -->
        <div class="slider-display-box">
            <div class="slider-header-title">{{ strtoupper($namaFaskes ?? 'KLINIK TELAGASARI 24 JAM') }}</div>
            <div class="carousel-container">
                <div id="carouselSlides" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner h-100">
                        @if($slides->count() > 0)
                            @foreach($slides as $index => $slide)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                @if($slide->url_media == 'logo')
                                    <!-- Clinic Logo Slide -->
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 p-3 bg-white">
                                        <div style="position: relative; width: 110px; height: 110px; margin-bottom: 15px;">
                                            <i class="fas fa-leaf" style="color: #61b15a; font-size: 65px; position: absolute; bottom: 0; left: 8px; transform: rotate(-45deg);"></i>
                                            <i class="fas fa-leaf" style="color: #5c3c92; font-size: 65px; position: absolute; bottom: 12px; left: -10px; transform: rotate(-90deg);"></i>
                                            <i class="fas fa-leaf" style="color: #3b5998; font-size: 90px; position: absolute; top: -10px; right: 0; transform: rotate(15deg);"></i>
                                            <i class="fas fa-plus" style="color: #e60000; font-size: 45px; position: absolute; top: 18px; right: 18px; font-weight: 900;"></i>
                                        </div>
                                        <h3 class="fw-bold mb-1" style="color: #1e5647;">{{ $namaFaskes ?? 'KLINIK TELAGASARI 24 JAM' }}</h3>
                                        <p class="text-muted small mb-0">Layanan Prima 24 Jam Non-Stop & Rekam Medis Elektronik Terpadu</p>
                                    </div>
                                @elseif(str_starts_with($slide->url_media, 'doctor_'))
                                    <!-- Doctor Poster Slide (1:1 with Screenshot 2) -->
                                    @php
                                        $docName = $slide->text_slide ?? 'dr. Dokter Praktek';
                                        $poliName = 'Poli Umum';
                                        $hariPraktek = 'Selasa dan Jumat';
                                        $jamPraktek = '08.00-17.00';
                                        $isHijab = str_contains(strtolower($docName), 'shela') || str_contains(strtolower($docName), 'resi') || str_contains(strtolower($docName), 'apriani');

                                        if (str_contains(strtolower($docName), 'apriani')) {
                                            $poliName = 'Poli Dokter Gigi';
                                            $hariPraktek = 'Senin s/d Sabtu';
                                            $jamPraktek = '15.00-18.00';
                                        } elseif (str_contains(strtolower($docName), 'resi')) {
                                            $poliName = 'Poli Bidan / KIA';
                                            $hariPraktek = 'Senin s/d Minggu';
                                            $jamPraktek = '06.00-22.00';
                                        } elseif (str_contains(strtolower($docName), 'fahmi')) {
                                            $poliName = 'Poli Umum';
                                            $hariPraktek = 'Senin s/d Jumat';
                                            $jamPraktek = '06.00-22.00';
                                        }
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-center h-100 p-2 bg-white">
                                        <div class="doctor-poster-flyer">
                                            <!-- Top Badges -->
                                            <div class="poster-top-badges">
                                                <div class="kemenkes-badge" title="Terakreditasi Kemenkes">
                                                    <i class="fas fa-shield-alt text-success"></i>
                                                    <span class="stars">★★★★★</span>
                                                </div>
                                                <div class="faskes-badge">
                                                    <i class="fas fa-clinic-medical text-primary"></i>
                                                </div>
                                            </div>

                                            <!-- Main Content Area -->
                                            <div class="poster-body">
                                                <!-- Doctor Illustration / Avatar -->
                                                <div class="poster-doctor-figure">
                                                    <div class="doctor-avatar-circle">
                                                        @if($isHijab)
                                                            <i class="fas fa-user-nurse doctor-icon-hijab"></i>
                                                        @else
                                                            <i class="fas fa-user-md doctor-icon-male"></i>
                                                        @endif
                                                        <span class="stethoscope-pin"><i class="fas fa-stethoscope"></i></span>
                                                    </div>
                                                </div>

                                                <!-- Text Info -->
                                                <div class="poster-schedule-info">
                                                    <h2 class="poster-poli-title">{{ $poliName }}</h2>
                                                    <p class="poster-days-text">{{ $hariPraktek }}</p>
                                                    <div class="poster-hours-badge">
                                                        <div class="hours-label">Jam Praktek:</div>
                                                        <div class="hours-time">{{ $jamPraktek }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bottom Info Footer -->
                                            <div class="poster-footer">
                                                <div class="poster-doc-name-pill">
                                                    {{ $docName }}
                                                </div>
                                                <div class="poster-contact-block">
                                                    <div class="contact-label"><i class="fas fa-phone-alt me-1"></i> HUBUNGI :</div>
                                                    <div class="contact-phone">{{ $profil->no_telp ?? '(0264) 8303763' }}</div>
                                                    <div class="contact-addr"><i class="fas fa-map-marker-alt me-1"></i> Telagasari, Karawang</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(str_starts_with($slide->url_media, 'farmasi_') || str_starts_with($slide->url_media, 'igd_'))
                                    <!-- Unit Poster Slide -->
                                    <div class="d-flex align-items-center justify-content-center h-100 p-2 bg-white">
                                        <div class="card border-0 shadow-sm text-center p-3" style="max-width: 320px; border-radius: 8px; border-top: 4px solid #00c0ef !important;">
                                            <div style="width: 80px; height: 80px; border-radius: 50%; background: #e0f7fa; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px auto;">
                                                <i class="fas {{ str_starts_with($slide->url_media, 'farmasi_') ? 'fa-pills' : 'fa-ambulance' }}" style="font-size: 44px; color: #00acc1;"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">{{ $slide->text_slide }}</h5>
                                            <div class="text-muted small" style="font-size: 11px;">Pelayanan Cepat, Tepat & Profesional 24 Jam</div>
                                        </div>
                                    </div>
                                @elseif($slide->tipe == 'video')
                                    <!-- Video Slide -->
                                    <div class="h-100 w-100">
                                        <iframe class="w-100 h-100 border-0" 
                                                src="{{ str_starts_with($slide->url_media, 'http') ? (str_contains($slide->url_media, 'watch?v=') ? str_replace('watch?v=', 'embed/', $slide->url_media) : $slide->url_media) : 'https://www.youtube.com/embed/' . $slide->url_media }}?autoplay=1&mute=1&loop=1&controls=0" 
                                                allow="autoplay; encrypted-media"></iframe>
                                    </div>
                                @else
                                    <!-- Uploaded Custom Image Slide -->
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <img src="{{ $slide->url_media }}" alt="Slide" class="img-fluid">
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        @else
                            <div class="carousel-item active d-flex align-items-center justify-content-center">
                                <h4 class="text-muted">KLINIK TELAGASARI 24 JAM</h4>
                            </div>
                        @endif
                    </div>

                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselSlides" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselSlides" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Waiting Queues Grid Section (Antrian Menunggu) -->
    <div class="waiting-section-container">
        <div class="waiting-section-header">Antrian Menunggu</div>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-6 g-2" id="waitingCardsContainer">
            @forelse($waitingList as $wait)
            @php
                $namaLoketShow = $wait->masterAntrian->nama_antrian ?? 'POLI DOKTER';
            @endphp
            <div class="col waiting-ticket-item" data-code="{{ $wait->kode_nomor }}">
                <div class="waiting-card">
                    <div class="waiting-poli-name">{{ $namaLoketShow }}</div>
                    <div class="waiting-ticket-code">{{ $wait->kode_nomor }}</div>
                    <div class="waiting-card-border"></div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-3">Tidak ada antrian menunggu saat ini.</div>
            @endforelse
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let audioEnabled = true;
        let lastCalledNumber = "{{ $currentCalled->kode_nomor ?? '' }}";

        // Fullscreen Toggle
        const btnFullscreen = document.getElementById('btnToggleFullscreen');
        if (btnFullscreen) {
            btnFullscreen.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().catch(err => console.log(err));
                    this.innerHTML = '<i class="fas fa-compress me-1"></i> Exit';
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                        this.innerHTML = '<i class="fas fa-expand me-1"></i> Fullscreen';
                    }
                }
            });
        }

        // Audio Toggle
        const btnAudio = document.getElementById('btnAudioToggle');
        if (btnAudio) {
            btnAudio.addEventListener('click', function() {
                audioEnabled = !audioEnabled;
                this.innerHTML = audioEnabled ? '<i class="fas fa-volume-up me-1"></i> Suara: ON' : '<i class="fas fa-volume-mute me-1"></i> Suara: OFF';
            });
        }

        // Web Audio Chime Sound
        function playChimeBell() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                
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
                console.log(err);
            }
        }

        // Speech Synthesis for Queue Call in Indonesian
        function speakCallQueue(code, loket) {
            if (!audioEnabled || !('speechSynthesis' in window)) return;
            
            // Format code into spelled letters/digits (e.g. A D 0 6 1)
            const spelledCode = code.split('').join(' ');
            const textToSpeak = `Nomor antrian, ${spelledCode}, menuju, ${loket}`;

            setTimeout(() => {
                const utterance = new SpeechSynthesisUtterance(textToSpeak);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.pitch = 1.0;
                window.speechSynthesis.speak(utterance);
            }, 600);
        }

        // Real-time Polling for Called Ticket & Waiting Queues
        function pollQueueStatus() {
            const tanggal = "{{ $tanggal }}";
            const loketId = "{{ $loket->id ?? '' }}";
            let url = `/api/dashboard-antrian/status?tanggal_antrian=${tanggal}`;
            if (loketId) url += `&loket_id=${loketId}`;

            fetch(url)
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        // Check if called ticket changed
                        if (res.called && res.called.kode_nomor) {
                            const newNumber = res.called.kode_nomor;
                            const newLoket = res.called.nama_loket;

                            if (newNumber !== lastCalledNumber) {
                                lastCalledNumber = newNumber;
                                
                                const numEl = document.getElementById('currentCalledNumber');
                                const lokEl = document.getElementById('currentCalledLoket');
                                if (numEl) numEl.textContent = newNumber;
                                if (lokEl) lokEl.textContent = newLoket;

                                playChimeBell();
                                speakCallQueue(newNumber, newLoket);
                            }
                        }

                        // Update Waiting List Grid
                        if (res.waiting && res.waiting.length > 0) {
                            const container = document.getElementById('waitingCardsContainer');
                            if (container) {
                                container.innerHTML = res.waiting.map(w => `
                                    <div class="col waiting-ticket-item" data-code="${w.kode_nomor}">
                                        <div class="waiting-card">
                                            <div class="waiting-poli-name">${w.nama_loket}</div>
                                            <div class="waiting-ticket-code">${w.kode_nomor}</div>
                                            <div class="waiting-card-border"></div>
                                        </div>
                                    </div>
                                `).join('');
                            }
                        }
                    }
                })
                .catch(err => console.log("Poll error", err));
        }

        // Poll every 3 seconds
        setInterval(pollQueueStatus, 3000);
    </script>
</body>
</html>
