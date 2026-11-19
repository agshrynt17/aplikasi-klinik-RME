@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .btn-orange { background-color: #f39c12; border-color: #e08e0b; color: #fff; }
    .btn-orange:hover { background-color: #d68910; border-color: #c77e0c; color: #fff; }
    .btn-green { background-color: #00a65a; border-color: #008d4c; color: #fff; }
    .btn-green:hover { background-color: #008d4c; border-color: #00733e; color: #fff; }
    .btn-cyan { background-color: #00c0ef; border-color: #00acd6; color: #fff; }
    .btn-cyan:hover { background-color: #00acd6; border-color: #0097bc; color: #fff; }

    .table-antrian { font-size: 12px; }
    .table-antrian thead th {
        background-color: #f9fafb;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        vertical-align: middle;
        white-space: nowrap;
        padding: 8px;
    }
    .table-antrian tbody td {
        vertical-align: middle;
        padding: 7px 8px;
    }

    .card-antrian-box {
        border-radius: 4px;
        border: 1px solid #e3e6f0;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .big-antrian-number {
        font-size: 3.5rem;
        font-weight: 800;
        color: #495057;
        line-height: 1;
        letter-spacing: 2px;
    }
    .antrian-sub {
        font-size: 13px;
        font-weight: 700;
        color: #0073b7;
        margin-top: 5px;
    }
    .antrian-next-label {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }
</style>

<!-- Header & Breadcrumbs Sesuai Screenshot 3 -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Checkin Antrian Pendaftaran</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Checkin Antrian Pendaftaran</a></li>
        </ol>
    </nav>
</div>

<!-- TOP SECTION: DAFTAR ANTRIAN MENUNGGU (Sesuai Screenshot 3) -->
<div class="card card-antrian-box p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
        <h6 class="fw-bold mb-0 text-secondary" style="font-size: 13px; letter-spacing: 0.5px;">
            DAFTAR ANTRIAN MENUNGGU
        </h6>
        <div>
            <a href="/pendaftaran-rajal" class="btn btn-orange btn-sm px-3 fw-semibold shadow-sm text-white">
                <i class="fas fa-times me-1"></i> Tutup
            </a>
        </div>
    </div>

    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
        <table class="table table-hover table-striped table-bordered table-antrian align-middle mb-0">
            <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th width="10%" class="text-center">Panggil</th>
                    <th width="10%">Sumber</th>
                    <th width="10%">No Antrian</th>
                    <th width="10%">Penjamin</th>
                    <th width="22%">Nama Pasien</th>
                    <th width="13%">No.KIS</th>
                    <th width="12%">NIK</th>
                    <th width="10%">Waktu Cetak</th>
                    <th width="10%">Status Kirim No.Antrian Ke BPJS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($antrians as $index => $ant)
                <tr id="row-antrian-{{ $ant->id }}" class="{{ isset($activeAntrian) && $activeAntrian->id == $ant->id ? 'table-warning' : '' }}">
                    <td class="text-center fw-semibold text-secondary">{{ $index + 1 }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-orange btn-sm py-0 px-2 fw-semibold text-white shadow-sm btnCallAntrian"
                            data-id="{{ $ant->id }}"
                            data-no="{{ $ant->no_antrian }}"
                            data-nama="{{ $ant->pasien->nama_pasien ?? 'Pasien' }}"
                            data-penjamin="{{ $ant->penjamin ? $ant->penjamin->nama_penjamin : 'NON BPJS' }}"
                            data-jam="{{ $ant->waktu_cetak ?? ($ant->jam_daftar ?? '-') }}"
                            data-nik="{{ $ant->pasien->nik ?? '-' }}"
                            data-pasienid="{{ $ant->pasien_id }}">
                            <i class="fas fa-bullhorn me-1"></i> Panggil
                        </button>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $ant->sumber_antrian ?: 'Booking' }}</span>
                    </td>
                    <td class="fw-bold text-primary">{{ $ant->no_antrian }}</td>
                    <td>
                        <span class="fw-semibold {{ str_contains(strtoupper($ant->penjamin->nama_penjamin ?? 'NON BPJS'), 'BPJS') ? 'text-success' : 'text-primary' }}">
                            {{ str_contains(strtoupper($ant->penjamin->nama_penjamin ?? 'NON BPJS'), 'BPJS') ? 'BPJS' : 'NON BPJS' }}
                        </span>
                    </td>
                    <td class="fw-bold text-dark text-uppercase">
                        {{ $ant->pasien ? $ant->pasien->nama_pasien : '-' }}
                    </td>
                    <td style="font-family: monospace;">{{ $ant->pasien->no_bpjs ?? '-' }}</td>
                    <td style="font-family: monospace;">{{ $ant->pasien->nik ?? '-' }}</td>
                    <td>{{ $ant->waktu_cetak ?? ($ant->jam_daftar ?? '-') }}</td>
                    <td>
                        @if($ant->status_antrol_bpjs === 'Terkirim' || $ant->status_antrol_bpjs === 'Hadir')
                            <span class="badge bg-success" style="background-color: #00a65a !important;">Terkirim</span>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">
                        Tidak ada antrian menunggu pada tanggal {{ $tanggal }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- BOTTOM SECTION: 2 COLUMNS (Sesuai Screenshot 3) -->
<div class="row">
    <!-- KOLOM KIRI: BIG CALL CARD DISPLAY (Sesuai Screenshot 3) -->
    <div class="col-lg-6 mb-3">
        <div class="card card-antrian-box p-4 h-100">
            <div class="text-center border-bottom pb-3 mb-3">
                <div class="big-antrian-number" id="displayBigNumber">
                    {{ $activeAntrian->no_antrian ?? 'BA001' }}
                </div>
                <div class="antrian-sub" id="displayBigPenjamin">
                    {{ str_contains(strtoupper($activeAntrian->penjamin->nama_penjamin ?? 'NON BPJS'), 'BPJS') ? 'BPJS' : 'NON BPJS' }}
                </div>
                <div class="antrian-next-label">
                    Antrian Selanjutnya
                </div>
            </div>

            <table class="table table-borderless table-sm small mb-0">
                <tr>
                    <td width="35%" class="text-muted fw-semibold">Status</td>
                    <td>
                        <span class="badge bg-cyan text-white fw-semibold" id="displayBigStatus">
                            Pasien Lama
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Nama</td>
                    <td class="fw-bold text-primary text-uppercase" id="displayBigNama">
                        {{ $activeAntrian->pasien->nama_pasien ?? 'NY HIKMATUS SARIAH YULIA WARDANI' }}
                    </td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Tanggal Sekarang</td>
                    <td id="displayBigTgl">{{ date('D, d-M-Y') }}</td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Jam Sekarang</td>
                    <td class="fw-bold" id="displayLiveClock">{{ date('H:i:s') }}</td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Jam Cetak Antrian</td>
                    <td id="displayBigJamCetak">{{ $activeAntrian->waktu_cetak ?? ($activeAntrian->jam_daftar ?? '14:21') }}</td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Lama Menunggu</td>
                    <td id="displayBigLamaMenunggu" class="text-muted">Sedang Menunggu</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- KOLOM KANAN: CALL ACTION CONTROLS (Sesuai Screenshot 3) -->
    <div class="col-lg-6 mb-3">
        <div class="card card-antrian-box p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <h5 class="fw-bold text-secondary mb-3 pb-2 border-bottom" id="actionPanelTitle">
                    {{ $activeAntrian->no_antrian ?? '-' }}
                </h5>

                <table class="table table-borderless table-sm small mb-3">
                    <tr>
                        <td width="35%" class="text-muted fw-semibold">Status</td>
                        <td id="actionStatusText" class="fw-bold text-success">
                            {{ $activeAntrian->status_antrian ?? 'Menunggu' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Nama</td>
                        <td id="actionNamaText" class="fw-bold text-dark text-uppercase">
                            {{ $activeAntrian->pasien->nama_pasien ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jam Cetak Antrian</td>
                        <td id="actionJamCetakText">
                            {{ $activeAntrian->waktu_cetak ?? ($activeAntrian->jam_daftar ?? '-') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jam Panggil Antrian</td>
                        <td id="actionJamPanggilText">
                            {{ $activeAntrian->waktu_panggil ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Lama Menunggu</td>
                        <td id="actionLamaText">-</td>
                    </tr>
                </table>
            </div>

            <!-- Tombol Aksi Panggil Ulang, Pasien Hadir, Lewatkan -->
            <div class="pt-3 border-top">
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-outline-secondary fw-semibold py-2" id="btnPanggilUlang">
                        <i class="fas fa-bullhorn me-1 text-warning"></i> Panggil Ulang
                    </button>
                    
                    <a href="/pendaftaran/create?antrian_id={{ $activeAntrian->id ?? '' }}&pasien_id={{ $activeAntrian->pasien_id ?? '' }}" 
                       class="btn btn-green fw-semibold py-2 shadow-sm" id="btnPasienHadir">
                        <i class="fas fa-check me-1"></i> Pasien Hadir (Buat Pendaftaran)
                    </a>

                    <button type="button" class="btn btn-outline-danger fw-semibold py-2" id="btnLewatkan">
                        <i class="fas fa-forward me-1"></i> Lewatkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentAntrianId = '{{ $activeAntrian->id ?? "" }}';
    let currentAntrianNo = '{{ $activeAntrian->no_antrian ?? "BA001" }}';
    let currentNama = '{{ $activeAntrian->pasien->nama_pasien ?? "" }}';

    // Live Clock
    setInterval(function() {
        let d = new Date();
        let h = String(d.getHours()).padStart(2, '0');
        let m = String(d.getMinutes()).padStart(2, '0');
        let s = String(d.getSeconds()).padStart(2, '0');
        $('#displayLiveClock').text(h + ':' + m + ':' + s);
    }, 1000);

    // Web Audio Chime Bell (Dua Nada Ding-Dong)
    function playChimeBell() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(587.33, ctx.currentTime);
            gain1.gain.setValueAtTime(0.25, ctx.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start();
            osc1.stop(ctx.currentTime + 0.35);

            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(440, ctx.currentTime + 0.25);
            gain2.gain.setValueAtTime(0.25, ctx.currentTime + 0.25);
            gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.7);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(ctx.currentTime + 0.25);
            osc2.stop(ctx.currentTime + 0.7);
        } catch (err) {}
    }

    // Text to Speech
    function speakAntrian(noAntrian) {
        playChimeBell();
        if ('speechSynthesis' in window) {
            let formatted = String(noAntrian).replace(/[-_]/g, ' ').trim();
            let spaced = formatted.split('').map(function(ch) { return (ch === '0' ? 'nol' : ch); }).join(' ');
            let text = "Nomor antrian " + spaced + ", silakan menuju loket pendaftaran!";
            
            setTimeout(() => {
                window.speechSynthesis.cancel();
                let utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.85;
                utterance.pitch = 1.0;
                window.speechSynthesis.speak(utterance);
            }, 500);
        }
    }

    // Klik tombol Panggil di tabel
    $('.btnCallAntrian').click(function() {
        let id = $(this).data('id');
        let no = $(this).data('no');
        let nama = $(this).data('nama');
        let penjamin = $(this).data('penjamin');
        let jam = $(this).data('jam');
        let pasienId = $(this).data('pasienid');

        currentAntrianId = id;
        currentAntrianNo = no;
        currentNama = nama;

        // Update Big Call Display
        $('#displayBigNumber').text(no);
        $('#displayBigPenjamin').text(penjamin);
        $('#displayBigNama').text(nama);
        $('#displayBigJamCetak').text(jam);

        // Update Action Controls Panel
        $('#actionPanelTitle').text(no);
        $('#actionStatusText').text('Dipanggil');
        $('#actionNamaText').text(nama);
        $('#actionJamCetakText').text(jam);
        
        let now = new Date();
        let jamPanggilStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0') + ':' + String(now.getSeconds()).padStart(2, '0');
        $('#actionJamPanggilText').text(jamPanggilStr);

        $('#btnPasienHadir').attr('href', '/pendaftaran/create?antrian_id=' + id + '&pasien_id=' + pasienId);

        // Highlight Row
        $('tr[id^="row-antrian-"]').removeClass('table-warning');
        $('#row-antrian-' + id).addClass('table-warning');

        // Play Sound / Speech
        speakAntrian(no);

        // AJAX update status
        $.ajax({
            url: '/pendaftaran/antrian/' + id + '/panggil',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            }
        });
    });

    // Panggil Ulang
    $('#btnPanggilUlang').click(function() {
        if(currentAntrianNo) {
            speakAntrian(currentAntrianNo);
        }
    });

    // Lewatkan
    $('#btnLewatkan').click(function() {
        if(confirm('Lewatkan antrian ' + currentAntrianNo + '?')) {
            $('#actionStatusText').text('Dilewati').removeClass('text-success').addClass('text-danger');
        }
    });
});
</script>
@endsection
