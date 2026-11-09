@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-normal mb-0 text-dark" style="font-size: 24px; color: #333;">Layanan Antrian</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Layanan Antrian</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="background-color: #d4edda; color: #155724; border-radius: 3px;">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div id="alertBox" class="alert d-none mb-3 border-0 shadow-sm" role="alert" style="border-radius: 3px;"></div>

<!-- Main Card Box -->
<div class="bg-white shadow-sm rounded-1 p-3 mb-4" style="border-top: 3px solid #00c0ef; border-radius: 3px;">
    <!-- Top Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="/master-antrian/create" class="btn text-white fw-normal" style="background-color: #00a65a; border-color: #008d4c; font-size: 14px; padding: 6px 14px; border-radius: 3px;">
                <i class="fas fa-plus me-1"></i> Tambah Layanan Antrian
            </a>
        </div>
        <div>
            <a href="/jadwal-antrian" class="btn text-white fw-normal" style="background-color: #337ab7; border-color: #2e6da4; font-size: 14px; padding: 6px 14px; border-radius: 3px;">
                <i class="far fa-calendar-alt me-1"></i> Atur Jadwal Antrian
            </a>
        </div>
    </div>

    <!-- Items counter -->
    <div class="mb-2" style="font-size: 13px; color: #555;">
        Menampilkan 1-{{ $data->count() }} dari {{ $data->count() }} item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tableLayananAntrian" style="border-top: 1px solid #f4f4f4; border-bottom: 1px solid #f4f4f4;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd; background-color: #fcfcfc;">
                    <th class="text-center" style="width: 50px; color: #337ab7; font-weight: 600; font-size: 13px;">#</th>
                    <th style="width: 15%; color: #337ab7; font-weight: 600; font-size: 13px;">Kode Antrian</th>
                    <th style="width: 35%; color: #337ab7; font-weight: 600; font-size: 13px;">Nama Antrian</th>
                    <th style="width: 25%; color: #337ab7; font-weight: 600; font-size: 13px;">Poli Klinik</th>
                    <th style="width: 25%; color: #337ab7; font-weight: 600; font-size: 13px;">Sound Layanan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr data-id="{{ $item->id }}" style="border-bottom: 1px solid #f4f4f4;">
                    <td class="text-center" style="font-size: 13px; color: #333;">{{ $index + 1 }}</td>
                    <td style="font-size: 13px;">
                        <a href="/master-antrian/{{ $item->id }}/edit" class="text-decoration-none fw-normal" style="color: #337ab7;" title="Ubah Kode / Layanan">
                            {{ $item->kode_antrian }}
                            <i class="fas fa-pencil-alt ms-1" style="color: #00c0ef; font-size: 11px;"></i>
                        </a>
                    </td>
                    <td style="font-size: 13px; color: #333;">
                        {{ $item->nama_antrian }}
                    </td>
                    <td style="font-size: 13px; color: #333;">
                        {{ $item->poliklinik ? $item->poliklinik->nama_poli : '' }}
                    </td>
                    <td style="font-size: 13px;">
                        @if(!$item->poliklinik_id)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-volume-up text-secondary me-2" style="font-size: 15px;"></i>
                                <button type="button" class="btn text-white btnUbahSound" 
                                    data-id="{{ $item->id }}" 
                                    data-kode="{{ $item->kode_antrian }}" 
                                    data-nama="{{ $item->nama_antrian }}" 
                                    data-sound="{{ $item->sound_layanan ?? 'default' }}"
                                    style="background-color: #f39c12; border-color: #e08e0b; padding: 2px 8px; font-size: 12px; border-radius: 3px;">
                                    <i class="fas fa-edit me-1" style="font-size: 11px;"></i> Ubah
                                </button>
                            </div>
                        @else
                            <span style="color: #333;">Atur sound pada menu jadwal</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada layanan antrian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ubah Sound Layanan -->
<div class="modal fade" id="modalSound" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #00a65a; color: white; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                <h5 class="modal-title fs-6 fw-normal" id="modalSoundLabel">
                    <i class="fas fa-volume-up me-2"></i> Sound Layanan - <span id="soundTitleNama"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSound">
                @csrf
                <input type="hidden" id="soundAntrianId" name="id">
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Pilihan Suara Panggilan</label>
                        <select class="form-select form-control" id="sound_layanan" name="sound_layanan" style="font-size: 13px; border-radius: 3px;">
                            <option value="default">Default Suara Panggilan Wanita (Indonesia)</option>
                            <option value="male_id">Suara Panggilan Pria (Indonesia)</option>
                            <option value="bell_dingdong">Bell Ding-Dong & Panggilan Standar</option>
                            <option value="chime_soft">Soft Chime Notification</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Simulasi Kalimat Panggilan</label>
                        <div class="p-2 bg-light border rounded small text-secondary" id="previewKalimat">
                            "Nomor antrian 0-001, silakan menuju PENDAFTARAN"
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnTestSound" style="font-size: 12px; border-radius: 3px;">
                            <i class="fas fa-play me-1"></i> Putar Contoh Suara
                        </button>
                        <span id="playingIndicator" class="small text-success d-none"><i class="fas fa-spinner fa-spin me-1"></i> Sedang berbunyi...</span>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 3px; font-size: 13px;">Tutup</button>
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #00a65a; border-color: #008d4c; border-radius: 3px; font-size: 13px;">
                        <i class="fas fa-save me-1"></i> Simpan Sound
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentItem = {};

    $('.btnUbahSound').click(function() {
        let id = $(this).data('id');
        let kode = $(this).data('kode');
        let nama = $(this).data('nama');
        let sound = $(this).data('sound') || 'default';

        currentItem = { id: id, kode: kode, nama: nama, sound: sound };

        $('#soundAntrianId').val(id);
        $('#soundTitleNama').text(kode + ' (' + nama + ')');
        $('#sound_layanan').val(sound);
        $('#previewKalimat').text('"Nomor antrian ' + kode + '-001, silakan menuju ' + nama + '"');

        $('#modalSound').modal('show');
    });

    $('#sound_layanan').change(function() {
        let jenis = $(this).val();
        let kalimat = '"Nomor antrian ' + currentItem.kode + '-001, silakan menuju ' + currentItem.nama + '"';
        if(jenis === 'bell_dingdong') {
            kalimat = '[Ding-Dong] ' + kalimat;
        } else if(jenis === 'chime_soft') {
            kalimat = '[Soft Chime] ' + kalimat;
        }
        $('#previewKalimat').text(kalimat);
    });

    $('#btnTestSound').click(function() {
        let text = 'Nomor antrian ' + currentItem.kode + ' kosong kosong satu, silakan menuju ' + currentItem.nama;
        let voiceType = $('#sound_layanan').val();

        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            let utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID';
            utterance.rate = 0.9;
            utterance.pitch = (voiceType === 'male_id') ? 0.8 : 1.1;

            $('#playingIndicator').removeClass('d-none');
            utterance.onend = function() {
                $('#playingIndicator').addClass('d-none');
            };
            utterance.onerror = function() {
                $('#playingIndicator').addClass('d-none');
            };

            window.speechSynthesis.speak(utterance);
        } else {
            alert('Browser Anda tidak mendukung preview suara Speech Synthesis.');
        }
    });

    $('#formSound').submit(function(e) {
        e.preventDefault();
        let id = $('#soundAntrianId').val();
        let soundVal = $('#sound_layanan').val();

        $.ajax({
            url: '/master-antrian/' + id + '/sound',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                sound_layanan: soundVal
            },
            success: function(res) {
                $('#modalSound').modal('hide');
                showAlert('success', res.message || 'Sound layanan berhasil diubah.');
                // update button data
                $('.btnUbahSound[data-id="' + id + '"]').data('sound', soundVal);
            },
            error: function(xhr) {
                alert('Gagal menyimpan sound layanan.');
            }
        });
    });

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
            .addClass('alert-' + type)
            .html('<i class="fas fa-check-circle me-2"></i> ' + msg)
            .show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 3500);
    }
});
</script>
@endsection
