@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="fw-normal mb-0 text-dark" style="font-size: 24px; color: #333;">Setting Jadwal Antrian</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Setting Jadwal Antrian</li>
        </ol>
    </nav>
</div>

<!-- PERHATIAN!! Banner Notice -->
<div class="mb-3" style="font-size: 13px; line-height: 1.6;">
    <strong style="color: #a04000; font-size: 14px;">PERHATIAN!!</strong><br>
    <span style="color: #666; font-weight: 600;">Antrian yang muncul sesuai dengan antrian yang disetting di master antrian, jika antrian tidak muncul silakan cek pada master antrian.</span><br>
    <span style="color: #666; font-weight: 600;">Untuk Antrian yang terbridging Antrian Mobile JKN V2 mohon samakan jadwal dengan sistem pada HFIS BPJS</span>
    <button type="button" class="btn text-white btn-sm py-0 px-2 ms-1" id="btnLihatHfis" data-bs-toggle="modal" data-bs-target="#modalHfis" onclick="window.openModalHfis && window.openModalHfis()" style="background-color: #f39c12; border-color: #e08e0b; font-size: 11px; border-radius: 3px; vertical-align: middle;">
        <i class="fas fa-edit me-1"></i> Lihat Jadwal Pada HFIS
    </button>
</div>

<div id="alertBox" class="alert d-none mb-3 border-0 shadow-sm" role="alert" style="border-radius: 3px;"></div>

<!-- Main Card Box -->
<div class="bg-white shadow-sm rounded-1 p-3 mb-4" style="border-top: 3px solid #00c0ef; border-radius: 3px;">
    <!-- Antrian Title & Action Buttons -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-0 fw-semibold d-inline-block" style="color: #337ab7; font-size: 18px;">
                Antrian : <span id="labelAntrian">{{ $selectedAntrian->nama_antrian ?? 'POLI DOKTER' }}</span> | 
                Poli : <span id="labelPoli">{{ $selectedAntrian->poliklinik->nama_poli ?? 'Klinik Umum 1' }}</span>
            </h4>
            @if(isset($master_antrians) && $master_antrians->count() > 1)
            <div class="dropdown d-inline-block ms-2">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-0 px-2" type="button" data-bs-toggle="dropdown" style="font-size: 11px; border-radius: 3px;">
                    Ganti Antrian
                </button>
                <ul class="dropdown-menu shadow-sm" style="font-size: 13px;">
                    @foreach($master_antrians as $ma)
                    <li>
                        <a class="dropdown-item {{ ($selectedAntrian->id ?? 0) == $ma->id ? 'active fw-bold' : '' }}" href="/antrian-jadwal?antrian_id={{ $ma->id }}">
                            {{ $ma->kode_antrian }} - {{ $ma->nama_antrian }} ({{ $ma->poliklinik->nama_poli ?? 'Umum' }})
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn text-white fw-normal" id="btnTambahJadwal" data-bs-toggle="modal" data-bs-target="#modalJadwalPraktek" onclick="window.openModalTambahJadwal && window.openModalTambahJadwal()" style="background-color: #337ab7; border-color: #2e6da4; font-size: 13px; padding: 5px 14px; border-radius: 3px;">
                <i class="fas fa-plus me-1"></i> Tambah Jadwal Praktek
            </button>
            <button type="button" class="btn text-white fw-normal" id="btnTarikHfis" data-bs-toggle="modal" data-bs-target="#modalTarikHfis" onclick="window.openModalTarikHfis && window.openModalTarikHfis()" style="background-color: #00acd6; border-color: #0097bc; font-size: 13px; padding: 5px 14px; border-radius: 3px;">
                <i class="fas fa-cloud-download-alt me-1"></i> Tarik Jadwal Dari HFIS
            </button>
            <button type="button" class="btn text-white fw-normal" id="btnAturSound" data-bs-toggle="modal" data-bs-target="#modalSoundAntrian" onclick="window.openModalSoundAntrian && window.openModalSoundAntrian()" style="background-color: #f39c12; border-color: #e08e0b; font-size: 13px; padding: 5px 14px; border-radius: 3px;">
                <i class="fas fa-volume-up me-1"></i> Atur Sound Antrian
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tableJadwal" style="border-top: 1px solid #f4f4f4; border-bottom: 1px solid #f4f4f4;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd; background-color: #fcfcfc;">
                    <th style="width: 130px; color: #337ab7; font-weight: 600; font-size: 13px;">Status Antrian</th>
                    <th style="width: 90px; color: #337ab7; font-weight: 600; font-size: 13px;">Hari</th>
                    <th style="width: 140px; color: #337ab7; font-weight: 600; font-size: 13px;">Jam Antrian</th>
                    <th style="color: #337ab7; font-weight: 600; font-size: 13px;">Dokter/ Tenkes</th>
                    <th style="width: 160px; color: #337ab7; font-weight: 600; font-size: 13px;">
                        Kode Prefix Antrian <span class="badge" style="background-color: #00a65a; font-size: 9px; vertical-align: top; padding: 2px 4px;">New!</span>
                    </th>
                    <th style="width: 130px; color: #337ab7; font-weight: 600; font-size: 13px;">Ruangan</th>
                    <th style="width: 110px; color: #337ab7; font-weight: 600; font-size: 13px;">Kuota Antrian</th>
                    <th style="width: 70px; color: #337ab7; font-weight: 600; font-size: 13px;">Terisi</th>
                    <th style="width: 70px; color: #337ab7; font-weight: 600; font-size: 13px;">Sound</th>
                    <th style="width: 110px; text-align: center; color: #337ab7; font-weight: 600; font-size: 13px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                @php
                    $kodePrefixShow = $item->kode_prefix ?: ($selectedAntrian->kode_antrian ?? 'A');
                @endphp
                <tr data-id="{{ $item->id }}" style="border-bottom: 1px solid #f4f4f4;">
                    <!-- Status Antrian -->
                    <td style="font-size: 12px; white-space: nowrap;">
                        @if($item->is_active)
                            <span class="badge" style="background-color: #00a65a; font-size: 10px; font-weight: normal; padding: 3px 6px;">Aktif</span>
                            <button type="button" class="btn btn-xs text-white btnToggleStatus ms-1" data-id="{{ $item->id }}" data-status="0" style="background-color: #dd4b39; border-color: #d73925; font-size: 11px; padding: 1px 6px; border-radius: 2px;">
                                <i class="fas fa-times me-1"></i> Matikan
                            </button>
                        @else
                            <span class="badge" style="background-color: #999; font-size: 10px; font-weight: normal; padding: 3px 6px;">Non-Aktif</span>
                            <button type="button" class="btn btn-xs text-white btnToggleStatus ms-1" data-id="{{ $item->id }}" data-status="1" style="background-color: #f39c12; border-color: #e08e0b; font-size: 11px; padding: 1px 6px; border-radius: 2px;">
                                <i class="fas fa-check-square me-1"></i> Nyalakan
                            </button>
                        @endif
                    </td>

                    <!-- Hari -->
                    <td style="font-size: 13px; color: #333;">
                        {{ $item->hari }}
                    </td>

                    <!-- Jam Antrian -->
                    <td style="font-size: 13px; color: #333; white-space: nowrap;">
                        {{ date('H:i', strtotime($item->jam_mulai)) }} s.d {{ date('H:i', strtotime($item->jam_selesai)) }}
                    </td>

                    <!-- Dokter/Tenkes -->
                    <td style="font-size: 13px; color: #333;">
                        {{ ($item->dokter?->kode_dpjp_bpjs ? '(' . $item->dokter->kode_dpjp_bpjs . ') ' : '') . ($item->dokter?->nama_pegawai ?? '-') }}
                    </td>

                    <!-- Kode Prefix Antrian -->
                    <td style="font-size: 13px; color: #333;">
                        <strong>{{ $kodePrefixShow }}</strong> 
                        <small class="text-muted fst-italic" style="font-size: 11px;">cth Nomor: {{ $kodePrefixShow . '001' }}</small>
                    </td>

                    <!-- Ruangan -->
                    <td style="font-size: 13px; color: #333;">
                        {{ $item->ruangan ?? 'Ruang Rawat 1' }}
                    </td>

                    <!-- Kuota Antrian -->
                    <td style="font-size: 13px; color: #333;">
                        <span class="spanKuota" data-id="{{ $item->id }}">{{ $item->kuota }}</span>
                        <a href="javascript:void(0)" class="btnEditKuota text-info ms-1" data-id="{{ $item->id }}" data-kuota="{{ $item->kuota }}" title="Ubah Kuota">
                            <i class="fas fa-pencil-alt" style="font-size: 10px; color: #00c0ef;"></i>
                        </a>
                    </td>

                    <!-- Terisi -->
                    <td style="font-size: 13px; color: #333;">
                        {{ $item->terisi ?? 0 }}
                    </td>

                    <!-- Sound -->
                    <td style="font-size: 13px;">
                        <i class="fas fa-volume-up text-secondary" style="font-size: 14px;"></i>
                    </td>

                    <!-- Aksi -->
                    <td style="font-size: 12px; white-space: nowrap; text-align: center;">
                        <button type="button" class="btn text-white btn-xs btnEditJadwal" data-id="{{ $item->id }}" style="background-color: #f39c12; border-color: #e08e0b; font-size: 12px; padding: 2px 8px; border-radius: 3px;">
                            <i class="fas fa-edit me-1"></i> Ubah
                        </button>
                        <button type="button" class="btn text-white btn-xs btnHapusJadwal ms-1" data-id="{{ $item->id }}" style="background-color: #dd4b39; border-color: #d73925; font-size: 12px; padding: 2px 8px; border-radius: 3px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">Belum ada jadwal praktek untuk antrian ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 1: Tambah / Edit Jadwal Praktek (Screenshot 2) -->
<!-- ======================================================== -->
<div class="modal fade" id="modalJadwalPraktek" tabindex="-1" aria-labelledby="modalJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 3px; border: none; border-top: 3px solid #00c0ef; box-shadow: 0 4px 20px rgba(0,0,0,0.18);">
            <div class="modal-header py-2 px-3 bg-white" style="border-bottom: 1px solid #eee;">
                <h5 class="modal-title fs-6 fw-normal text-dark" id="modalJadwalLabel">
                    Jadwal Praktek<span id="titleNamaAntrian">{{ $selectedAntrian->nama_antrian ?? 'POLI DOKTER' }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formJadwalPraktek">
                @csrf
                <input type="hidden" id="jadwalId" name="id">
                <input type="hidden" id="master_antrian_id" name="master_antrian_id" value="{{ $selectedAntrian->id ?? '' }}">

                <div class="modal-body p-4">
                    <!-- NOTE -->
                    <div class="mb-3 small" style="color: #e74c3c; line-height: 1.5; font-size: 12.5px;">
                        <strong style="color: #e74c3c;">NOTE:</strong><br>
                        Pastikan hari, jam mulai dan dokter tidak ada yang sama dengan jadwal pada hari lainnya.<br>
                        Untuk Antrian yang terbridging Mobile JKN mohon samakan jadwal dengan sistem pada HFIS BPJS
                    </div>

                    <!-- Row 1: Hari, Mulai, Selesai -->
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Hari</label>
                            <select class="form-select form-control" id="hari" name="hari" required style="font-size: 13px; border-radius: 3px; height: 35px;">
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Mulai</label>
                            <div class="d-flex align-items-center gap-1">
                                <select class="form-select form-control" id="mulai_jam" name="mulai_jam" style="font-size: 13px; border-radius: 3px; height: 35px;">
                                    @for($i=0; $i<=23; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}" {{ $i == 7 ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                                <span class="text-muted">-</span>
                                <select class="form-select form-control" id="mulai_menit" name="mulai_menit" style="font-size: 13px; border-radius: 3px; height: 35px;">
                                    @foreach(['00','05','10','15','20','25','30','35','40','45','50','55'] as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Selesai</label>
                            <div class="d-flex align-items-center gap-1">
                                <select class="form-select form-control" id="selesai_jam" name="selesai_jam" style="font-size: 13px; border-radius: 3px; height: 35px;">
                                    @for($i=0; $i<=23; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}" {{ $i == 22 ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                                <span class="text-muted">-</span>
                                <select class="form-select form-control" id="selesai_menit" name="selesai_menit" style="font-size: 13px; border-radius: 3px; height: 35px;">
                                    @foreach(['00','05','10','15','20','25','30','35','40','45','50','55'] as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Nama Antrian & Kode Prefix -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Nama Antrian</label>
                            <input type="text" class="form-control bg-light" id="display_nama_antrian" value="{{ $selectedAntrian->nama_antrian ?? 'POLI DOKTER' }}" readonly style="font-size: 13px; border-radius: 3px; height: 35px;">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Kode Prefix</label>
                            <input type="text" class="form-control bg-light" id="display_kode_prefix" value="{{ $selectedAntrian->kode_antrian ?? 'A' }}" readonly style="font-size: 13px; border-radius: 3px; height: 35px;">
                        </div>
                    </div>

                    <!-- Row 3: Dokter & Kode Prefix (Kode Dokter) -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">
                                Dokter <span style="color:red;font-style:italic; font-size:12px; font-weight:normal;">Wajib!</span>
                            </label>
                            <select class="form-select form-control" id="dokter_id" name="dokter_id" required style="font-size: 13px; border-radius: 3px; height: 35px;">
                                <option value="">--Dokter--</option>
                                @foreach($dokters as $dok)
                                    <option value="{{ $dok->id }}" data-kode="{{ $dok->kode_dpjp_bpjs }}">
                                        {{ ($dok->kode_dpjp_bpjs ? '(' . $dok->kode_dpjp_bpjs . ') ' : '') . $dok->nama_pegawai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Kode Prefix</label>
                            <select class="form-select form-control" id="kode_dokter" name="kode_dokter" style="font-size: 13px; border-radius: 3px; height: 35px;">
                                <option value="">--Kode Dokter--</option>
                                <option value="A">A</option>
                                <option value="AA">AA</option>
                                <option value="AD">AD</option>
                                <option value="AF">AF</option>
                                <option value="AG">AG</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                            <div style="font-size: 11px; color: red; font-style: italic;" class="mt-1">
                                Wajib untuk jadwal lebih dari 1x sehari
                            </div>
                        </div>
                    </div>

                    <!-- Row 4: Contoh No Antrian -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Contoh No Antrian</label>
                            <input type="text" class="form-control bg-light" id="contoh_no_antrian" value="A001" readonly style="font-size: 13px; border-radius: 3px; height: 35px;">
                        </div>
                    </div>

                    <!-- Row 5: Ruang Pelayanan & Maks. Kuota Antrian -->
                    <div class="row g-3 mb-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">
                                Ruang Pelayanan <span style="color:red;font-style:italic; font-size:12px; font-weight:normal;">Wajib!</span>
                            </label>
                            <select class="form-select form-control" id="ruangan" name="ruangan" required style="font-size: 13px; border-radius: 3px; height: 35px;">
                                <option value="">Pilih Ruang</option>
                                <option value="Ruang Rawat 1" selected>Ruang Rawat 1</option>
                                <option value="Ruang Rawat 2">Ruang Rawat 2</option>
                                <option value="Ruang Poli Umum">Ruang Poli Umum</option>
                                <option value="Ruang Poli Gigi">Ruang Poli Gigi</option>
                                <option value="Ruang KIA/KB">Ruang KIA/KB</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-1" style="font-size: 13px; font-weight: 600; color: #333;">Maks. Kuota Antrian</label>
                            <input type="number" class="form-control" id="kuota" name="kuota" value="200" min="0" required style="font-size: 13px; border-radius: 3px; height: 35px;">
                        </div>
                    </div>
                </div>

                <div class="modal-footer py-2 px-3 bg-white" style="border-top: 1px solid #eee;">
                    <button type="submit" class="btn text-white fw-normal" style="background-color: #00a65a; border-color: #008d4c; font-size: 13px; padding: 6px 16px; border-radius: 3px;">
                        <i class="fas fa-check-circle me-1"></i> OK!Simpan
                    </button>
                    <button type="button" class="btn text-white fw-normal" data-bs-dismiss="modal" style="background-color: #f39c12; border-color: #e08e0b; font-size: 13px; padding: 6px 16px; border-radius: 3px;">
                        <i class="fas fa-sign-out-alt me-1"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 2: Tarik Jadwal Dari HFIS BPJS -->
<!-- ======================================================== -->
<div class="modal fade" id="modalTarikHfis" tabindex="-1" aria-labelledby="modalTarikHfisLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 3px; border: none; border-top: 3px solid #00acd6; box-shadow: 0 4px 20px rgba(0,0,0,0.18);">
            <div class="modal-header py-2 px-3 bg-white" style="border-bottom: 1px solid #eee;">
                <h5 class="modal-title fs-6 fw-semibold text-dark" id="modalTarikHfisLabel">
                    <i class="fas fa-cloud-download-alt text-info me-2"></i> Tarik Jadwal Dari HFIS BPJS
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info py-2 px-3 mb-3 small" style="border-radius: 3px;">
                    <i class="fas fa-info-circle me-1"></i> 
                    Menarik jadwal praktek dokter yang sudah disetujui di portal <strong>HFIS BPJS Kesehatan</strong> untuk disinkronkan ke dalam antrian klinik ini.
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Pilih Poliklinik BPJS:</label>
                        <select class="form-select" id="hfis_select_poli" style="font-size: 13px;">
                            <option value="001">001 - Poli Umum ({{ $selectedAntrian->poliklinik->nama_poli ?? 'Klinik Umum 1' }})</option>
                            <option value="002">002 - Poli Gigi</option>
                            <option value="003">003 - Poli KIA / Kebidanan</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="button" class="btn text-white w-100" id="btnProsesTarikHfis" style="background-color: #00acd6; border-color: #0097bc; font-size: 13px; height: 38px;">
                            <i class="fas fa-sync-alt me-1"></i> Tarik Data dari Server BPJS
                        </button>
                    </div>
                </div>

                <div class="border rounded p-3 bg-light">
                    <div class="fw-bold small text-dark mb-2"><i class="fas fa-check-circle text-success me-1"></i> Jadwal Tersedia di Server HFIS:</div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered bg-white mb-0" style="font-size: 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Praktek</th>
                                    <th>Dokter HFIS</th>
                                    <th>Kode DPJP</th>
                                    <th>Kapasitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Minggu</td>
                                    <td>06:00 - 22:00</td>
                                    <td>dr. VIERTA AJI NUR YASIN</td>
                                    <td><span class="badge bg-secondary">627356</span></td>
                                    <td>140</td>
                                </tr>
                                <tr>
                                    <td>Senin</td>
                                    <td>06:00 - 22:00</td>
                                    <td>dr. Harry Putra Pratama</td>
                                    <td><span class="badge bg-secondary">538658</span></td>
                                    <td>140</td>
                                </tr>
                                <tr>
                                    <td>Senin</td>
                                    <td>06:00 - 16:00</td>
                                    <td>dr. Fahmi Rizal</td>
                                    <td><span class="badge bg-secondary">48187</span></td>
                                    <td>70</td>
                                </tr>
                                <tr>
                                    <td>Selasa</td>
                                    <td>06:00 - 22:00</td>
                                    <td>dr. Sheila Asyiah Granitya</td>
                                    <td><span class="badge bg-secondary">610316</span></td>
                                    <td>92</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-white" style="border-top: 1px solid #eee;">
                <button type="button" class="btn text-white fw-normal" id="btnSimpanSinkronHfis" style="background-color: #00a65a; border-color: #008d4c; font-size: 13px; padding: 6px 16px; border-radius: 3px;">
                    <i class="fas fa-save me-1"></i> Simpan ke Jadwal Antrian
                </button>
                <button type="button" class="btn text-white fw-normal" data-bs-dismiss="modal" style="background-color: #f39c12; border-color: #e08e0b; font-size: 13px; padding: 6px 16px; border-radius: 3px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 3: Atur Sound Antrian -->
<!-- ======================================================== -->
<div class="modal fade" id="modalSoundAntrian" tabindex="-1" aria-labelledby="modalSoundAntrianLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 3px; border: none; border-top: 3px solid #f39c12; box-shadow: 0 4px 20px rgba(0,0,0,0.18);">
            <div class="modal-header py-2 px-3 bg-white" style="border-bottom: 1px solid #eee;">
                <h5 class="modal-title fs-6 fw-semibold text-dark" id="modalSoundAntrianLabel">
                    <i class="fas fa-volume-up text-warning me-2"></i> Atur Sound Antrian - {{ $selectedAntrian->nama_antrian ?? 'POLI DOKTER' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSoundAntrian">
                @csrf
                <input type="hidden" name="antrian_id" value="{{ $selectedAntrian->id ?? '' }}">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Pilihan Jenis Suara:</label>
                        <select class="form-select form-control" id="sound_type" name="sound_type" style="font-size: 13px; border-radius: 3px;">
                            <option value="female">Default Suara Panggilan Wanita (Bahasa Indonesia)</option>
                            <option value="male">Suara Panggilan Pria (Bahasa Indonesia)</option>
                            <option value="bell_dingdong">Bell Ding-Dong & Panggilan Standar</option>
                            <option value="chime">Soft Chime Notification</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Contoh Kalimat Panggilan:</label>
                        <div class="p-2 bg-light border rounded small text-secondary" id="previewSoundText">
                            "Nomor antrian A-001, silakan menuju {{ $selectedAntrian->nama_antrian ?? 'POLI DOKTER' }}"
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnTestSoundJadwal" style="font-size: 12px; border-radius: 3px;">
                            <i class="fas fa-play me-1"></i> Putar Contoh Suara
                        </button>
                        <span id="soundPlayingIndicator" class="small text-success d-none"><i class="fas fa-spinner fa-spin me-1"></i> Memutar suara...</span>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-white" style="border-top: 1px solid #eee;">
                    <button type="submit" class="btn text-white fw-normal" style="background-color: #00a65a; border-color: #008d4c; font-size: 13px; padding: 6px 16px; border-radius: 3px;">
                        <i class="fas fa-save me-1"></i> Simpan Sound
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
<!-- MODAL 4: Quick Edit Kuota -->
<!-- ======================================================== -->
<div class="modal fade" id="modalEditKuota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content" style="border-radius: 3px; border-top: 3px solid #337ab7;">
            <div class="modal-header py-2 px-3" style="background-color: #337ab7; color: white;">
                <h6 class="modal-title fs-6">Ubah Kuota Antrian</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditKuota">
                @csrf
                <input type="hidden" id="editKuotaId">
                <div class="modal-body p-3">
                    <label class="form-label small fw-bold">Kuota Antrian:</label>
                    <input type="number" class="form-control" id="inputKuotaBaru" min="0" required style="font-size: 13px;">
                </div>
                <div class="modal-footer py-2 px-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #00a65a;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 5: Info HFIS BPJS -->
<!-- ======================================================== -->
<div class="modal fade" id="modalHfis" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content" style="border-radius: 3px; border-top: 3px solid #00c0ef;">
            <div class="modal-header py-2 px-3">
                <h5 class="modal-title fs-6 text-dark"><i class="fas fa-network-wired text-info me-2"></i> Integrasi HFIS BPJS Kesehatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 small text-secondary">
                <p>Status Bridging: <span class="badge bg-success">Terkoneksi (V2 Antrol Mobile JKN)</span></p>
                <div class="alert alert-info py-2 px-3 mb-2" style="font-size: 12px;">
                    Jadwal praktek dokter pada HFIS BPJS disinkronkan secara otomatis sesuai kode DPJP BPJS masing-masing dokter.
                </div>
                <ul class="mb-0 ps-3">
                    <li>(627356) dr. VIERTA AJI NUR YASIN - Aktif</li>
                    <li>(538658) dr. Harry Putra Pratama - Aktif</li>
                    <li>(48187) dr. Fahmi Rizal - Aktif</li>
                    <li>(610316) dr. Sheila Asyiah Granitya - Aktif</li>
                    <li>(48000) dr. Syaiful Bachtiar - Aktif</li>
                    <li>(425176) dr. Galang Bela Nusa - Aktif</li>
                </ul>
            </div>
            <div class="modal-footer py-2 px-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    let basePrefix = "{{ $selectedAntrian->kode_antrian ?? 'A' }}";
    let antrianNama = "{{ $selectedAntrian->nama_antrian ?? 'POLI DOKTER' }}";

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

    function updateContohNomor() {
        let kd = $('#kode_dokter').val();
        let prefix = kd ? kd : basePrefix;
        $('#contoh_no_antrian').val(prefix + '001');
    }

    // Expose functions globally for immediate button clicks
    window.openModalTambahJadwal = function() {
        if ($('#formJadwalPraktek').length) {
            $('#formJadwalPraktek')[0].reset();
        }
        $('#jadwalId').val('');
        $('#modalJadwalLabel').text('Jadwal Praktek' + antrianNama);
        $('#ruangan').val('Ruang Rawat 1');
        $('#kuota').val('200');
        $('#mulai_jam').val('07');
        $('#mulai_menit').val('00');
        $('#selesai_jam').val('22');
        $('#selesai_menit').val('00');
        $('#kode_dokter').val('');
        updateContohNomor();
        showModalSafe('modalJadwalPraktek');
    };

    window.openModalTarikHfis = function() {
        showModalSafe('modalTarikHfis');
    };

    window.openModalSoundAntrian = function() {
        showModalSafe('modalSoundAntrian');
    };

    window.openModalHfis = function() {
        showModalSafe('modalHfis');
    };

    // jQuery bindings
    $(document).ready(function() {
        $('#kode_dokter').change(updateContohNomor);

        $('#btnTambahJadwal').off('click').on('click', function(e) {
            e.preventDefault();
            window.openModalTambahJadwal();
        });

        $('#btnTarikHfis').off('click').on('click', function(e) {
            e.preventDefault();
            window.openModalTarikHfis();
        });

        $('#btnAturSound').off('click').on('click', function(e) {
            e.preventDefault();
            window.openModalSoundAntrian();
        });

        $('#btnLihatHfis').off('click').on('click', function(e) {
            e.preventDefault();
            window.openModalHfis();
        });

        // Submit Form Jadwal Praktek (Tambah / Edit)
        $('#formJadwalPraktek').off('submit').on('submit', function(e) {
            e.preventDefault();
            let id = $('#jadwalId').val();
            let url = id ? '/antrian-jadwal/' + id : '/antrian-jadwal';
            let data = $(this).serialize();
            if(id) {
                data += '&_method=PUT';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(res) {
                    hideModalSafe('modalJadwalPraktek');
                    showAlert('success', res.message || 'Jadwal praktek berhasil disimpan.');
                    setTimeout(() => window.location.reload(), 700);
                },
                error: function(xhr) {
                    alert('Gagal menyimpan jadwal praktek. Pastikan semua field wajib terisi.');
                }
            });
        });

        // Edit Jadwal Klik
        $(document).off('click', '.btnEditJadwal').on('click', '.btnEditJadwal', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $.get('/antrian-jadwal/' + id + '/edit', function(res) {
                $('#jadwalId').val(res.id);
                $('#hari').val(res.hari);

                if(res.jam_mulai) {
                    let parts = res.jam_mulai.split(':');
                    $('#mulai_jam').val(parts[0]);
                    $('#mulai_menit').val(parts[1] || '00');
                }
                if(res.jam_selesai) {
                    let parts = res.jam_selesai.split(':');
                    $('#selesai_jam').val(parts[0]);
                    $('#selesai_menit').val(parts[1] || '00');
                }

                $('#dokter_id').val(res.dokter_id);
                $('#kode_dokter').val(res.kode_prefix || '');
                $('#ruangan').val(res.ruangan || 'Ruang Rawat 1');
                $('#kuota').val(res.kuota);

                updateContohNomor();
                $('#modalJadwalLabel').text('Edit Jadwal Praktek - ' + antrianNama);
                showModalSafe('modalJadwalPraktek');
            });
        });

        // Toggle Status
        $(document).off('click', '.btnToggleStatus').on('click', '.btnToggleStatus', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let status = $(this).data('status');
            let btn = $(this);

            btn.prop('disabled', true);
            $.ajax({
                url: '/antrian-jadwal/' + id + '/status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(res) {
                    showAlert('success', res.message);
                    window.location.reload();
                },
                error: function() {
                    alert('Gagal mengubah status jadwal antrian.');
                    btn.prop('disabled', false);
                }
            });
        });

        // Quick Edit Kuota
        $(document).off('click', '.btnEditKuota').on('click', '.btnEditKuota', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let kuota = $(this).data('kuota');
            $('#editKuotaId').val(id);
            $('#inputKuotaBaru').val(kuota);
            showModalSafe('modalEditKuota');
        });

        $('#formEditKuota').off('submit').on('submit', function(e) {
            e.preventDefault();
            let id = $('#editKuotaId').val();
            let k = $('#inputKuotaBaru').val();

            $.ajax({
                url: '/antrian-jadwal/' + id + '/kuota',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kuota: k
                },
                success: function(res) {
                    hideModalSafe('modalEditKuota');
                    $('.spanKuota[data-id="' + id + '"]').text(res.kuota);
                    $('.btnEditKuota[data-id="' + id + '"]').data('kuota', res.kuota);
                    showAlert('success', res.message);
                },
                error: function() {
                    alert('Gagal mengupdate kuota antrian.');
                }
            });
        });

        // Hapus Jadwal
        $(document).off('click', '.btnHapusJadwal').on('click', '.btnHapusJadwal', function(e) {
            e.preventDefault();
            if(confirm('Yakin ingin menghapus jadwal praktek ini?')) {
                let id = $(this).data('id');
                let tr = $(this).closest('tr');

                $.ajax({
                    url: '/antrian-jadwal/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(res) {
                        tr.fadeOut(300, function() { $(this).remove(); });
                        showAlert('success', res.message);
                    },
                    error: function() {
                        alert('Gagal menghapus jadwal.');
                    }
                });
            }
        });

        // Tarik Data HFIS (Button di dalam modal)
        $('#btnProsesTarikHfis, #btnSimpanSinkronHfis').off('click').on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let orig = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Sinkronisasi...').prop('disabled', true);

            $.ajax({
                url: '/antrian-jadwal/tarik-hfis',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    btn.html(orig).prop('disabled', false);
                    hideModalSafe('modalTarikHfis');
                    showAlert('success', res.message || 'Jadwal praktek berhasil disinkronkan dengan HFIS BPJS!');
                    setTimeout(() => window.location.reload(), 700);
                },
                error: function() {
                    btn.html(orig).prop('disabled', false);
                    alert('Gagal sinkronisasi data dengan HFIS BPJS.');
                }
            });
        });

        // Test Suara di Modal Sound
        $('#btnTestSoundJadwal').off('click').on('click', function(e) {
            e.preventDefault();
            let text = 'Nomor antrian ' + basePrefix + ' kosong kosong satu, silakan menuju ' + antrianNama;
            let voiceType = $('#sound_type').val();

            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                let utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.pitch = (voiceType === 'male') ? 0.8 : 1.1;

                $('#soundPlayingIndicator').removeClass('d-none');
                utterance.onend = function() {
                    $('#soundPlayingIndicator').addClass('d-none');
                };
                utterance.onerror = function() {
                    $('#soundPlayingIndicator').addClass('d-none');
                };

                window.speechSynthesis.speak(utterance);
            } else {
                alert('Browser tidak mendukung Speech Synthesis.');
            }
        });

        // Submit Form Sound Antrian
        $('#formSoundAntrian').off('submit').on('submit', function(e) {
            e.preventDefault();
            let soundType = $('#sound_type').val();

            $.ajax({
                url: '/antrian-jadwal/sound',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    antrian_id: '{{ $selectedAntrian->id ?? "" }}',
                    sound_type: soundType
                },
                success: function(res) {
                    hideModalSafe('modalSoundAntrian');
                    showAlert('success', res.message || 'Pengaturan sound berhasil disimpan.');
                },
                error: function() {
                    alert('Gagal menyimpan sound antrian.');
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
})();
</script>
@endsection
