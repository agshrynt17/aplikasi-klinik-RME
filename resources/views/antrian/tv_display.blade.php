<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - Klinik RME</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Roboto+Mono:wght@700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b132b; /* Dark Blue / Premium Dark */
            color: #ffffff;
            overflow: hidden; /* Hide scrollbars for TV */
            margin: 0;
            padding: 0;
        }

        /* --- Header --- */
        .header {
            background: linear-gradient(90deg, #1c2541 0%, #3a506b 100%);
            padding: 15px 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title {
            font-size: 2rem;
            font-weight: 800;
            color: #5bc0be; /* Teal accent */
            letter-spacing: 2px;
            margin: 0;
        }
        .header-time {
            font-size: 1.5rem;
            font-family: 'Roboto Mono', monospace;
            font-weight: 700;
            color: #ffffff;
        }

        /* --- Main Layout --- */
        .main-container {
            display: flex;
            height: calc(100vh - 80px); /* Minus header */
        }

        /* Left Side: Media / Slides */
        .media-panel {
            width: 65%;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .media-container {
            flex-grow: 1;
            background-color: #000;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            position: relative;
        }
        
        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }

        .youtube-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Right Side: Queue Info */
        .queue-panel {
            width: 35%;
            padding: 20px 20px 20px 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Current Called */
        .current-queue {
            background: linear-gradient(135deg, #ff9f1c 0%, #ffbf69 100%);
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(255, 159, 28, 0.3);
            animation: pulse-glow 2s infinite alternate;
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 5px 20px rgba(255,159,28,0.2); }
            100% { box-shadow: 0 15px 40px rgba(255,159,28,0.6); }
        }

        .current-queue-title {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .current-queue-number {
            font-size: 6rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.2);
            margin: 10px 0;
        }
        .current-queue-loket {
            font-size: 2rem;
            font-weight: 800;
            color: #1c2541;
            background: rgba(255,255,255,0.8);
            padding: 5px 15px;
            border-radius: 50px;
            display: inline-block;
        }

        /* History List */
        .history-list {
            background-color: #1c2541;
            border-radius: 15px;
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .history-title {
            color: #5bc0be;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 2px solid #3a506b;
            padding-bottom: 10px;
        }
        .history-item {
            background-color: #3a506b;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .history-item:last-child {
            margin-bottom: 0;
        }
        .h-number {
            font-size: 2rem;
            font-weight: 800;
            color: #ffffff;
        }
        .h-loket {
            font-size: 1.2rem;
            color: #ffbf69;
            font-weight: 600;
        }

        /* Running Text Footer */
        .running-text {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #ff9f1c;
            color: #1c2541;
            padding: 10px 0;
            font-size: 1.5rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .running-text marquee {
            padding: 0 20px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1 class="header-title">{{ $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}</h1>
        <div class="header-time" id="clock">00:00:00</div>
    </div>

    <!-- Main Layout -->
    <div class="main-container">
        
        <!-- Left Media Panel -->
        <div class="media-panel">
            <div class="media-container" style="background: #111a2e; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                @if($slides->count() > 0)
                    <!-- Bootstrap Carousel Media Slides -->
                    <div id="carouselMedia" class="carousel slide carousel-fade h-100 w-100" data-bs-ride="carousel" data-bs-interval="6000">
                        <div class="carousel-inner h-100">
                            @foreach($slides as $index => $sl)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }} h-100" style="position: relative;">
                                @if($sl->url_media == 'logo')
                                    <!-- Clinic Logo Slide -->
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-center bg-white text-dark">
                                        <div style="position: relative; width: 140px; height: 140px; margin-bottom: 25px;">
                                            <i class="fas fa-leaf" style="color: #61b15a; font-size: 85px; position: absolute; bottom: 0; left: 10px; transform: rotate(-45deg);"></i>
                                            <i class="fas fa-leaf" style="color: #5c3c92; font-size: 85px; position: absolute; bottom: 15px; left: -15px; transform: rotate(-90deg);"></i>
                                            <i class="fas fa-leaf" style="color: #3b5998; font-size: 120px; position: absolute; top: -15px; right: 0; transform: rotate(15deg);"></i>
                                            <i class="fas fa-plus" style="color: #e60000; font-size: 60px; position: absolute; top: 25px; right: 25px; font-weight: 900;"></i>
                                        </div>
                                        <h1 class="fw-bold mb-2" style="font-size: 2.8rem; color: #1e5647;">{{ $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}</h1>
                                        <h4 class="text-muted fw-semibold" style="letter-spacing: 1px;">LAYANAN MEDIS NON-STOP & REKAM MEDIS ELEKTRONIK TERPADU</h4>
                                        <div class="badge bg-success px-4 py-2 mt-3 fs-5">Siap Melayani Pasien Umum & BPJS Kesehatan</div>
                                    </div>
                                @elseif(str_starts_with($sl->url_media, 'doctor_'))
                                    <!-- Doctor Profile Slide -->
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-center" style="background: linear-gradient(135deg, #1c2541 0%, #0b132b 100%);">
                                        <span class="badge bg-success px-4 py-2 mb-3 fs-5"><i class="fas fa-stethoscope me-2"></i>JADWAL DOKTER PRAKTEK</span>
                                        <div style="width: 160px; height: 160px; border-radius: 50%; background: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.4); margin-bottom: 20px; border: 5px solid #5bc0be;">
                                            <i class="fas fa-user-md" style="font-size: 90px; color: #1c2541;"></i>
                                        </div>
                                        <h2 class="fw-bold text-white mb-2" style="font-size: 2.6rem;">{{ $sl->text_slide ?? 'Dokter Spesialis / Umum' }}</h2>
                                        <h4 style="color: #5bc0be; font-weight: 600;">Dokter Penanggung Jawab Pelayanan</h4>
                                        <p class="text-white-50 mt-2 fs-5">Klinik Telagasari 24 Jam - Melayani Dengan Senyum & Keahlian</p>
                                    </div>
                                @elseif(str_starts_with($sl->url_media, 'farmasi_') || str_starts_with($sl->url_media, 'igd_'))
                                    <!-- Clinic Unit Feature Slide -->
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-center" style="background: linear-gradient(135deg, #0f4c81 0%, #1c2541 100%);">
                                        <div style="width: 140px; height: 140px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                            <i class="fas {{ str_starts_with($sl->url_media, 'farmasi_') ? 'fa-pills' : 'fa-ambulance' }}" style="font-size: 70px; color: #5bc0be;"></i>
                                        </div>
                                        <h2 class="fw-bold text-white mb-2" style="font-size: 2.6rem;">{{ $sl->text_slide }}</h2>
                                        <h4 class="text-white-50">Pelayanan Profesional, Cepat, dan Tepat 24 Jam Non-Stop</h4>
                                    </div>
                                @elseif($sl->tipe == 'video')
                                    <!-- Video Slide -->
                                    <div class="h-100 w-100">
                                        <iframe class="w-100 h-100 border-0" 
                                                src="{{ str_starts_with($sl->url_media, 'http') ? (str_contains($sl->url_media, 'watch?v=') ? str_replace('watch?v=', 'embed/', $sl->url_media) : $sl->url_media) : 'https://www.youtube.com/embed/' . $sl->url_media }}?autoplay=1&mute=1&loop=1&controls=0" 
                                                allow="autoplay; encrypted-media"></iframe>
                                    </div>
                                @else
                                    <!-- Uploaded Custom Image Slide -->
                                    <div class="h-100 w-100 d-flex align-items-center justify-content-center bg-black">
                                        <img src="{{ $sl->url_media }}" alt="Slide" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                        @if($sl->text_slide)
                                        <div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-3 mb-3">
                                            <h3 class="fw-bold text-white m-0">{{ $sl->text_slide }}</h3>
                                        </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div style="color:white; display:flex; align-items:center; justify-content:center; height:100%;">
                        <h2>Media belum diatur</h2>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Queue Panel -->
        <div class="queue-panel">
            
            <!-- Highlight Current Called -->
            <div class="current-queue">
                <div class="current-queue-title">Nomor Antrian</div>
                <div class="current-queue-number" id="display_nomor">---</div>
                <div class="current-queue-loket" id="display_loket">STANDBY</div>
            </div>

            <!-- History -->
            <div class="history-list">
                <div class="history-title">PANGGILAN TERAKHIR</div>
                <div id="history_container">
                    <!-- Loaded via AJAX -->
                    <div class="text-center text-muted py-4">Menunggu antrian...</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Running Text -->
    <div class="running-text">
        <marquee behavior="scroll" direction="left">
            SELAMAT DATANG DI {{ strtoupper($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM') }}. PASTIKAN ANDA MEMBAWA KARTU IDENTITAS DAN KARTU ASURANSI. HARAP MENUNGGU PANGGILAN ANTRIAN DENGAN TERTIB. TERIMA KASIH ATAS KUNJUNGAN ANDA.
        </marquee>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- 1. Jam Digital ---
        function updateClock() {
            let now = new Date();
            let hh = String(now.getHours()).padStart(2, '0');
            let mm = String(now.getMinutes()).padStart(2, '0');
            let ss = String(now.getSeconds()).padStart(2, '0');
            $('#clock').text(hh + ":" + mm + ":" + ss);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // --- 2. Polling AJAX & Text to Speech ---
        let lastCalledId = null;
        let lastCallCount = null;

        function fetchAntrian() {
            $.ajax({
                url: '/api/tv-antrian',
                type: 'GET',
                success: function(res) {
                    if(res.success) {
                        
                        // Update Current Queue
                        if(res.current) {
                            $('#display_nomor').text(res.current.kode_nomor);
                            $('#display_loket').text(res.current.master_antrian.nama_antrian);

                            // Trigger Suara jika ID berganti atau jumlah panggilan berubah (dipanggil ulang)
                            if(lastCalledId !== res.current.id || lastCallCount !== res.current.panggilan_ke) {
                                playSound(res.current.kode_nomor, res.current.master_antrian.nama_antrian);
                                lastCalledId = res.current.id;
                                lastCallCount = res.current.panggilan_ke;
                            }
                        } else {
                            $('#display_nomor').text('---');
                            $('#display_loket').text('STANDBY');
                        }

                        // Update History
                        if(res.history && res.history.length > 0) {
                            let html = '';
                            $.each(res.history, function(index, item) {
                                // Skip jika itu yang sedang tampil besar di atas
                                if(res.current && item.id == res.current.id) return; 
                                
                                html += `
                                <div class="history-item">
                                    <div class="h-number">${item.kode_nomor}</div>
                                    <div class="h-loket">${item.master_antrian.nama_antrian}</div>
                                </div>`;
                            });
                            $('#history_container').html(html);
                        }
                    }
                }
            });
        }

        // Web Speech API untuk panggilan (Text-to-Speech)
        function playSound(nomor, loket) {
            // Hilangkan dash pada nomor agar dibaca per huruf/angka dengan jelas
            // Misal: A-001 -> A, 0, 0, 1
            let nomorClear = nomor.replace('-', ' ');
            // Pisahkan tiap karakter dengan spasi agar dieja
            let ejaanNomor = nomorClear.split('').join(' ');

            let text = "Nomor antrian, " + ejaanNomor + ". Silakan menuju ke, " + loket + ".";
            
            // Konfigurasi Suara (TTS)
            let speech = new SpeechSynthesisUtterance();
            speech.text = text;
            speech.lang = 'id-ID'; // Bahasa Indonesia
            speech.rate = 0.8; // Kecepatan bicara (sedikit dilambatkan)
            speech.pitch = 1;

            window.speechSynthesis.speak(speech);
        }

        // Jalankan polling setiap 3 detik
        setInterval(fetchAntrian, 3000);
        fetchAntrian();
    </script>
</body>
</html>
