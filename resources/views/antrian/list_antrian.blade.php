@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="fw-normal mb-0 text-dark" style="font-size: 24px; color: #333;">List Antrian Pelayanan</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">List Antrian Pelayanan</a></li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3 py-2 px-3 small" role="alert" style="border-radius: 3px;">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div id="alertBox" class="alert d-none mb-3 border-0 shadow-sm py-2 px-3 small" role="alert" style="border-radius: 3px;"></div>

<!-- Main Card Box -->
<div class="bg-white shadow-sm rounded-1 p-3 mb-4" style="border-top: 3px solid #00c0ef; border-radius: 3px;">
    <!-- Filter Bar Form -->
    <form method="GET" action="/list-antrian" class="mb-3">
        <div class="d-flex flex-wrap align-items-end gap-2">
            <div>
                <label class="form-label mb-1 fw-semibold small text-dark">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="{{ $tanggal }}" style="font-size: 13px; height: 34px; width: 140px;">
            </div>

            <div>
                <label class="form-label mb-1 fw-semibold small text-dark">Antrian</label>
                <select class="form-select" name="antrian_id" style="font-size: 13px; height: 34px; width: 150px;">
                    <option value="">--Semua--</option>
                    @foreach($lokets as $lok)
                        <option value="{{ $lok->id }}" {{ $antrian_id == $lok->id ? 'selected' : '' }}>
                            {{ $lok->nama_antrian }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label mb-1 fw-semibold small text-dark">Dokter</label>
                <select class="form-select" name="dokter_id" style="font-size: 13px; height: 34px; width: 160px;">
                    <option value="">--Semua--</option>
                    @foreach($dokters as $dok)
                        <option value="{{ $dok->id }}" {{ $dokter_id == $dok->id ? 'selected' : '' }}>
                            {{ ($dok->kode_dpjp_bpjs ? '(' . $dok->kode_dpjp_bpjs . ') ' : '') . $dok->nama_pegawai }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label mb-1 fw-semibold small text-dark">No Antrian</label>
                <input type="text" class="form-control" name="no_antrian" value="{{ $no_antrian }}" placeholder="" style="font-size: 13px; height: 34px; width: 110px;">
            </div>

            <div>
                <label class="form-label mb-1 fw-semibold small text-dark">No BPJS</label>
                <input type="text" class="form-control" name="no_bpjs" value="{{ $no_bpjs }}" placeholder="" style="font-size: 13px; height: 34px; width: 120px;">
            </div>

            <div>
                <label class="form-label mb-1 fw-semibold small text-dark">Ket.</label>
                <select class="form-select" name="ket" style="font-size: 13px; height: 34px; width: 125px;">
                    <option value="">--Semua--</option>
                    <option value="Menunggu" {{ $ket == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Checkin" {{ $ket == 'Checkin' ? 'selected' : '' }}>Checkin</option>
                    <option value="Selesai" {{ $ket == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Batal" {{ $ket == 'Batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>

            <div class="d-flex gap-1">
                <button type="submit" class="btn text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 34px; padding: 5px 12px; border-radius: 3px;" title="Cari Data">
                    <i class="fas fa-search"></i>
                </button>
                <a href="/list-antrian" class="btn btn-light border" style="height: 34px; padding: 5px 12px; border-radius: 3px;" title="Reset Filter">
                    <i class="fas fa-sync-alt text-secondary"></i>
                </a>
            </div>

            <div class="ms-auto">
                <button type="button" class="btn text-white fw-normal" id="btnBookingAntrian" data-bs-toggle="modal" data-bs-target="#modalBookingAntrian" onclick="window.openModalBooking && window.openModalBooking()" style="background-color: #00c0ef; border-color: #00acd6; font-size: 13px; padding: 6px 14px; border-radius: 3px;">
                    <i class="fas fa-plus me-1"></i> Booking Antrian
                </button>
            </div>
        </div>
    </form>

    <!-- Items counter -->
    <div class="mb-2" style="font-size: 13px; color: #555;">
        Menampilkan 1-{{ $data->count() }} dari {{ $data->total() }} item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tableListAntrian" style="border-top: 1px solid #f4f4f4; border-bottom: 1px solid #f4f4f4;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd; background-color: #fcfcfc;">
                    <th class="text-center" style="width: 40px; color: #337ab7; font-weight: 600; font-size: 13px;">#</th>
                    <th style="width: 135px; color: #337ab7; font-weight: 600; font-size: 13px;">Status Antrian</th>
                    <th style="width: 140px; color: #337ab7; font-weight: 600; font-size: 13px;">No & Tanggal</th>
                    <th style="color: #337ab7; font-weight: 600; font-size: 13px;">Pasien</th>
                    <th style="color: #337ab7; font-weight: 600; font-size: 13px;">Poli , Dokter & Jadwal</th>
                    <th style="width: 160px; color: #337ab7; font-weight: 600; font-size: 13px;">Waktu Tunggu</th>
                    <th style="width: 160px; color: #337ab7; font-weight: 600; font-size: 13px;">Sumber Data</th>
                    <th style="width: 150px; color: #337ab7; font-weight: 600; font-size: 13px;">Status Kirim Antrian BPJS</th>
                    <th style="width: 140px; text-align: center; color: #337ab7; font-weight: 600; font-size: 13px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                @php
                    $namaPasien = $item->pasien ? $item->pasien->nama_pasien : ($item->nama_pasien_non_rm ?? 'Pasien Umum');
                    $isMenunggu = ($item->status_poli == 'Menunggu' && $item->status_daftar == 'Menunggu');
                    $waktuCetak = $item->waktu_cetak ? date('d-m-Y H:i', strtotime($item->waktu_cetak)) : date('d-m-Y H:i', strtotime($item->created_at));
                @endphp
                <tr data-id="{{ $item->id }}" style="border-bottom: 1px solid #f4f4f4;">
                    <!-- # -->
                    <td class="text-center" style="font-size: 13px; color: #333;">
                        {{ $data->firstItem() + $index }}
                    </td>

                    <!-- Status Antrian -->
                    <td style="font-size: 12px; line-height: 1.6;">
                        <div>
                            Bagian Daftar: 
                            @if($item->status_daftar == 'Checkin')
                                <span class="badge" style="background-color: #00acd6; font-size: 10px; font-weight: normal; padding: 2px 5px;">Checkin</span>
                            @else
                                <span class="badge bg-secondary" style="font-size: 10px; font-weight: normal; padding: 2px 5px;">Menunggu</span>
                            @endif
                        </div>
                        <div>
                            Bagian Poli: 
                            @if($item->status_poli == 'Selesai')
                                <span class="badge" style="background-color: #00a65a; font-size: 10px; font-weight: normal; padding: 2px 5px;">Selesai</span>
                            @elseif($item->status_poli == 'Dipanggil')
                                <span class="badge bg-primary" style="font-size: 10px; font-weight: normal; padding: 2px 5px;">Dipanggil</span>
                            @elseif($item->status_poli == 'Batal')
                                <span class="badge bg-danger" style="font-size: 10px; font-weight: normal; padding: 2px 5px;">Batal</span>
                            @else
                                <span class="badge bg-secondary" style="font-size: 10px; font-weight: normal; padding: 2px 5px;">Menunggu</span>
                            @endif
                        </div>
                    </td>

                    <!-- No & Tanggal -->
                    <td style="font-size: 12px; line-height: 1.4;">
                        <span class="fw-bold fs-6 text-dark d-block">{{ $item->kode_nomor }}</span>
                        <span class="text-muted small">Tgl: {{ date('d-m-Y', strtotime($item->tanggal)) }}</span><br>
                        <span class="text-muted" style="font-size: 11px;">Cetak: {{ $waktuCetak }}</span>
                    </td>

                    <!-- Pasien -->
                    <td style="font-size: 12px; line-height: 1.4;">
                        <span class="text-secondary d-block">{{ $item->no_kartu_bpjs ? 'BPJS Kesehatan' : 'Pasien Umum' }}</span>
                        <span class="text-dark d-block" style="font-size: 11.5px;">{{ $item->no_kartu_bpjs ?: ($item->pasien->no_rm ?? '-') }}</span>
                        <span class="fw-semibold text-primary d-block">a.n {{ strtoupper($namaPasien) }}</span>
                    </td>

                    <!-- Poli , Dokter & Jadwal -->
                    <td style="font-size: 12px; line-height: 1.4;">
                        <span class="text-dark d-block">{{ $item->masterAntrian->poliklinik->nama_poli ?? ($item->masterAntrian->nama_antrian ?? 'Klinik Umum 1') }}</span>
                        <span class="text-primary d-block fw-normal">
                            {{ ($item->dokter?->kode_dpjp_bpjs ? '(' . $item->dokter->kode_dpjp_bpjs . ') ' : '') . ($item->dokter?->nama_pegawai ?? 'dr. VIERTA AJI NUR YASIN') }}
                        </span>
                        <span class="text-muted" style="font-size: 11px;">Jam: {{ $item->jam_praktek ?? '06:00 - 22:00' }}</span>
                    </td>

                    <!-- Waktu Tunggu -->
                    <td style="font-size: 11.5px; line-height: 1.4; color: #555;">
                        @if($item->waktu_tunggu)
                            <div>Waktu Tunggu: {{ $item->waktu_tunggu }}</div>
                            <div>Waktu Pelayanan: {{ $item->waktu_pelayanan ?? '-' }}</div>
                        @else
                            <span>-</span>
                        @endif
                    </td>

                    <!-- Sumber Data -->
                    <td style="font-size: 11.5px; line-height: 1.4;">
                        <span class="text-dark d-block">{{ $item->sumber_data ?? 'Cetak Onsite' }}</span>
                        @if($item->no_kartu_bpjs)
                            <span class="text-muted d-block" style="font-size: 11px;">KIS: {{ $item->no_kartu_bpjs }}</span>
                        @endif
                        @if($item->nik)
                            <span class="text-muted d-block" style="font-size: 11px;">NIK: {{ $item->nik }}</span>
                        @endif
                    </td>

                    <!-- Status Kirim Antrian BPJS -->
                    <td style="font-size: 11px; line-height: 1.5;">
                        <div>
                            Cetak Tiket: 
                            <span class="badge" style="background-color: #00a65a; font-size: 9.5px; font-weight: normal; padding: 2px 4px;">Terkirim</span>
                        </div>
                        @if($item->status_kirim_hadir == 'Terkirim')
                        <div class="mt-1">
                            Kehadiran : 
                            <span class="badge" style="background-color: #00a65a; font-size: 9.5px; font-weight: normal; padding: 2px 4px;">Terkirim</span>
                        </div>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td style="text-align: center; white-space: nowrap;">
                        <button type="button" class="btn btn-xs text-white btnPanggilAntrian mb-1 w-100" 
                                data-id="{{ $item->id }}" 
                                data-nomor="{{ $item->kode_nomor }}" 
                                data-loket="{{ $item->masterAntrian->poliklinik->nama_poli ?? ($item->masterAntrian->nama_antrian ?? 'POLI DOKTER') }}"
                                style="background-color: #00a65a; border-color: #008d4c; font-size: 11px; padding: 3px 6px; border-radius: 2px;"
                                title="Panggil Antrian">
                            <i class="fas fa-volume-up me-1"></i> {{ $item->status_poli == 'Dipanggil' ? 'Panggil Ulang' : 'Panggil' }}
                        </button>
                        <button type="button" class="btn btn-xs text-white btnCetakUlang mb-1 w-100" data-id="{{ $item->id }}" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 2px 6px; border-radius: 2px;">
                            <i class="fas fa-print me-1"></i> Cetak Ulang
                        </button>
                        @if($isMenunggu)
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-xs text-white btnUbahAntrian" data-id="{{ $item->id }}" style="background-color: #f39c12; border-color: #e08e0b; font-size: 10px; padding: 1px 6px; border-radius: 2px;">
                                <i class="fas fa-edit me-1"></i> Ubah
                            </button>
                            <button type="button" class="btn btn-xs text-white btnBatalAntrian" data-id="{{ $item->id }}" style="background-color: #dd4b39; border-color: #d73925; font-size: 10px; padding: 1px 6px; border-radius: 2px;">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-5">
                        <i class="fas fa-users-slash fa-3x mb-3 text-secondary d-block"></i>
                        Tidak ada antrian pada tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
        {{ $data->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Booking Antrian (Screenshot 2) -->
<!-- ======================================================== -->
<div class="modal fade" id="modalBookingAntrian" tabindex="-1" aria-labelledby="modalBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content" style="border-radius: 3px; border: none; border-top: 3px solid #00c0ef; box-shadow: 0 4px 20px rgba(0,0,0,0.18);">
            <div class="modal-header py-2 px-3 bg-white" style="border-bottom: 1px solid #eee;">
                <h5 class="modal-title fs-6 fw-normal text-dark" id="modalBookingLabel">
                    Booking Antrian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBookingAntrian">
                @csrf
                <div class="modal-body p-4">
                    <!-- Row 1: Tanggal Booking & Antrian -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Tanggal Booking</label>
                            <input type="date" class="form-control" id="booking_tanggal" name="tanggal" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" style="font-size: 13px; border-radius: 3px; height: 35px;" required>
                            <div class="text-muted small mt-1" style="font-size: 11px;">Maks. 14 hari</div>
                        </div>
                        <div class="col-12 col-md-7">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Antrian</label>
                            <select class="form-select form-control" id="booking_antrian" name="master_antrian_id" required style="font-size: 13px; border-radius: 3px; height: 35px;">
                                <option value="">--Pilih</option>
                                @foreach($lokets as $lok)
                                    <option value="{{ $lok->id }}">{{ $lok->nama_antrian }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Dokter yang tersedia & Jam Praktek -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-7">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Pilih dokter yang tersedia sesuai jadwal</label>
                            <select class="form-select form-control" id="booking_dokter" name="dokter_id" style="font-size: 13px; border-radius: 3px; height: 35px;">
                                <option value="">--Pilih Dokter--</option>
                                @foreach($dokters as $dok)
                                    <option value="{{ $dok->id }}">
                                        {{ ($dok->kode_dpjp_bpjs ? '(' . $dok->kode_dpjp_bpjs . ') ' : '') . $dok->nama_pegawai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Jam Praktek</label>
                            <select class="form-select form-control" id="booking_jam" name="jam_praktek" style="font-size: 13px; border-radius: 3px; height: 35px;">
                                <option value="06:00 - 22:00">06:00 - 22:00</option>
                                <option value="06:00 - 16:00">06:00 - 16:00</option>
                                <option value="16:05 - 22:00">16:05 - 22:00</option>
                                <option value="08:00 - 14:00">08:00 - 14:00</option>
                                <option value="15:00 - 18:00">15:00 - 18:00</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: Pilih Pasien -->
                    <div class="mb-3">
                        <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Pilih Pasien</label>
                        <select class="form-select form-control" id="booking_pasien" name="pasien_id" style="font-size: 13px; border-radius: 3px; height: 35px;">
                            <option value="">Pasien ...</option>
                            @foreach($pasiens as $pas)
                                <option value="{{ $pas->id }}">
                                    [{{ $pas->no_rm }}] {{ $pas->nama_pasien }} - {{ $pas->no_bpjs ?: $pas->nik }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-muted small mt-1" style="font-size: 11px;">Ketik Kode RM / Nama Pasien Min.3 Karakter</div>
                    </div>

                    <!-- Row 4: Atas Nama -->
                    <div class="mb-3">
                        <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Atas Nama (Isi jika data pasien belum terdaftar)</label>
                        <input type="text" class="form-control" id="booking_nama_non_rm" name="nama_pasien_non_rm" placeholder="Ketik nama pasien yang ingin berobat" style="font-size: 13px; border-radius: 3px; height: 35px;">
                    </div>
                </div>

                <div class="modal-footer py-2 px-3 bg-white" style="border-top: 1px solid #eee;">
                    <button type="submit" class="btn text-white fw-normal" style="background-color: #00a65a; border-color: #008d4c; font-size: 13px; padding: 6px 16px; border-radius: 3px;">
                        OK. Simpan
                    </button>
                    <button type="button" class="btn text-white fw-normal" data-bs-dismiss="modal" style="background-color: #f39c12; border-color: #e08e0b; font-size: 13px; padding: 6px 16px; border-radius: 3px;">
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Cetak Ulang Tiket -->
<!-- ======================================================== -->
<div class="modal fade" id="modalCetakTiket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content" style="border-radius: 3px;">
            <div class="modal-header py-2 px-3 bg-light border-bottom">
                <h6 class="modal-title fs-6 text-dark"><i class="fas fa-ticket-alt me-1 text-info"></i> Tiket Antrian</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 text-center" id="printAreaTiket">
                <div class="fw-bold mb-1" style="font-size: 14px;">KLINIK TELAGASARI 24 JAM</div>
                <div class="small text-muted mb-2" style="font-size: 11px;">Jl. Raya Telagasari - Karawang</div>
                <hr class="my-2">
                <div class="small text-secondary" id="cetakPoliNama">POLI DOKTER</div>
                <div class="display-4 fw-bold text-dark my-2" id="cetakNomorAntrian">A094</div>
                <div class="small text-dark fw-semibold" id="cetakPasienNama">NN DINI PUTRI AGUSTIN</div>
                <div class="small text-muted mt-2" style="font-size: 11px;" id="cetakWaktu">14-10-2026 15:02</div>
                <div class="alert alert-light border small text-muted mt-3 mb-0 py-1" style="font-size: 11px;">
                    Silakan menunggu nomor antrian Anda dipanggil. Terima kasih.
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function showModalSafe(modalId) {
        let el = document.getElementById(modalId);
        if (!el) return;
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            let inst = bootstrap.Modal.getOrCreateInstance(el);
            inst.show();
        } else if (typeof $ !== 'undefined' && typeof $.fn.modal === 'function') {
            $('#' + modalId).modal('show');
        } else {
            el.classList.add('show');
            el.style.display = 'block';
        }
    }

    function hideModalSafe(modalId) {
        let el = document.getElementById(modalId);
        if (!el) return;
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            let inst = bootstrap.Modal.getInstance(el);
            if (inst) inst.hide();
            else { el.classList.remove('show'); el.style.display = 'none'; }
        } else if (typeof $ !== 'undefined' && typeof $.fn.modal === 'function') {
            $('#' + modalId).modal('hide');
        } else {
            el.classList.remove('show');
            el.style.display = 'none';
        }
    }

    window.openModalBooking = function() {
        if ($('#formBookingAntrian').length) {
            $('#formBookingAntrian')[0].reset();
            $('#booking_tanggal').val('{{ date("Y-m-d") }}');
        }
        showModalSafe('modalBookingAntrian');
    };

    $(document).ready(function() {
        $('#btnBookingAntrian').off('click').on('click', function(e) {
            e.preventDefault();
            window.openModalBooking();
        });

        // Dynamic jadwal ketika antrian atau tanggal dipilih
        $('#booking_antrian, #booking_tanggal').change(function() {
            let antrianId = $('#booking_antrian').val();
            let tgl = $('#booking_tanggal').val();
            if (!antrianId) return;

            $.get('/api/jadwal-dokter', { master_antrian_id: antrianId, tanggal: tgl }, function(res) {
                if (res && res.length) {
                    let optDokter = '<option value="">--Pilih Dokter--</option>';
                    let optJam = '';
                    res.forEach(function(j) {
                        let dpjp = j.dokter && j.dokter.kode_dpjp_bpjs ? '(' + j.dokter.kode_dpjp_bpjs + ') ' : '';
                        let dokName = j.dokter ? j.dokter.nama_pegawai : 'Dokter';
                        optDokter += `<option value="${j.dokter_id}">${dpjp}${dokName}</option>`;
                        let jamText = (j.jam_mulai ? j.jam_mulai.substring(0,5) : '06:00') + ' - ' + (j.jam_selesai ? j.jam_selesai.substring(0,5) : '22:00');
                        optJam += `<option value="${jamText}">${jamText}</option>`;
                    });
                    $('#booking_dokter').html(optDokter);
                    if (optJam) $('#booking_jam').html(optJam);
                }
            });
        });

        // Submit Booking Form
        $('#formBookingAntrian').off('submit').on('submit', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                url: '/antrian/booking',
                type: 'POST',
                data: data,
                success: function(res) {
                    hideModalSafe('modalBookingAntrian');
                    showAlert('success', res.message || 'Booking antrian berhasil disimpan.');
                    setTimeout(() => window.location.reload(), 700);
                },
                error: function(xhr) {
                    alert('Gagal menyimpan booking antrian. Pastikan tanggal dan antrian terisi.');
                }
            });
        });

        // Fungsi Bunyi Chime Bell Dua Nada (Ding-Dong)
        function playChimeBell() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                gain1.gain.setValueAtTime(0.25, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start();
                osc1.stop(ctx.currentTime + 0.35);

                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(440, ctx.currentTime + 0.25); // A4
                gain2.gain.setValueAtTime(0.25, ctx.currentTime + 0.25);
                gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.7);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(ctx.currentTime + 0.25);
                osc2.stop(ctx.currentTime + 0.7);
            } catch (err) {}
        }

        // Panggil Antrian (Voice Announcement TTS + Chime Bell)
        $(document).off('click', '.btnPanggilAntrian').on('click', '.btnPanggilAntrian', function(e) {
            e.preventDefault();
            let btn = $(this);
            let id = btn.data('id');
            let nomor = String(btn.data('nomor'));
            let loket = btn.data('loket') || 'Poli Dokter';

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memanggil...');

            $.post('/antrian/' + id + '/update-status', {
                _token: '{{ csrf_token() }}',
                action: 'panggil'
            }, function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-volume-up me-1"></i> Panggil Ulang');
                showAlert('success', 'Antrian ' + nomor + ' dipanggil.');

                // Bunyikan Bell Ding Dong & Suara TTS
                playChimeBell();
                if ('speechSynthesis' in window) {
                    let formatted = nomor.replace(/[-_]/g, ' ').trim();
                    let spelled = formatted.split('').map(function(ch) { return (ch === '0' ? 'nol' : ch); }).join(' ');
                    let text = "Nomor antrian, " + spelled + ", silakan menuju ke, " + loket;

                    setTimeout(() => {
                        window.speechSynthesis.cancel();
                        let utt = new SpeechSynthesisUtterance(text);
                        utt.lang = 'id-ID';
                        utt.rate = 0.85;
                        utt.pitch = 1.0;
                        window.speechSynthesis.speak(utt);
                    }, 500);
                }
            }).fail(function() {
                btn.prop('disabled', false).html('<i class="fas fa-volume-up me-1"></i> Panggil');
                alert('Gagal memanggil antrian.');
            });
        });

        // Cetak Ulang
        $(document).off('click', '.btnCetakUlang').on('click', '.btnCetakUlang', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            $.get('/antrian/' + id + '/cetak-ulang', function(res) {
                if (res && res.data) {
                    let d = res.data;
                    $('#cetakNomorAntrian').text(d.kode_nomor);
                    $('#cetakPoliNama').text(d.master_antrian ? d.master_antrian.nama_antrian : 'POLI DOKTER');
                    let pasName = d.pasien ? d.pasien.nama_pasien : (d.nama_pasien_non_rm || 'Umum');
                    $('#cetakPasienNama').text(pasName);
                    $('#cetakWaktu').text(d.waktu_cetak || d.created_at);
                    showModalSafe('modalCetakTiket');
                }
            });
        });

        // Batal Antrian
        $(document).off('click', '.btnBatalAntrian').on('click', '.btnBatalAntrian', function(e) {
            e.preventDefault();
            if(confirm('Yakin ingin membatalkan antrian ini?')) {
                let id = $(this).data('id');
                $.post('/antrian/' + id + '/batal', { _token: '{{ csrf_token() }}' }, function(res) {
                    showAlert('success', res.message || 'Antrian berhasil dibatalkan.');
                    setTimeout(() => window.location.reload(), 700);
                });
            }
        });

        // Ubah Antrian
        $(document).off('click', '.btnUbahAntrian').on('click', '.btnUbahAntrian', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            window.openModalBooking();
        });

        function showAlert(type, msg) {
            $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
                .addClass('alert-' + type)
                .html('<i class="fas fa-check-circle me-2"></i> ' + msg)
                .show();
            setTimeout(() => $('#alertBox').addClass('d-none'), 3500);
        }
    });
})();
</script>
@endsection
