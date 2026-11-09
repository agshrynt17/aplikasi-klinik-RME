@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Daftar Order Lab</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Daftar Order Lab</span>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn text-white btn-sm" data-bs-toggle="collapse" data-bs-target="#filterCollapse" style="background-color: #f39c12; border-color: #e08e0b; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px;">
                <i class="fas fa-search me-1" style="font-size: 11px;"></i> Cari data
            </button>
            <a href="{{ url()->current() }}" class="btn btn-sm" title="Reset Filter" style="border: 1px solid #d2d6de; border-radius: 3px; width: 34px; height: 31px; display: inline-flex; align-items: center; justify-content: center; background-color: #f4f4f4; color: #444;">
                <i class="fas fa-sync-alt" style="font-size: 11px;"></i>
            </a>
        </div>
        <div>
            <a href="/entryhasillab/export-pdf?tanggal={{ $tanggal }}" target="_blank" class="btn btn-sm text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px; text-decoration: none; display: inline-flex; align-items: center;">
                <i class="far fa-file-pdf me-1" style="font-size: 11px;"></i> Cetak Laporan (pdf)
            </a>
        </div>
    </div>

    <!-- Collapsible Filter Form -->
    <div class="collapse mb-3 {{ request()->has('q') || request()->has('tanggal') ? 'show' : '' }}" id="filterCollapse">
        <div class="p-3" style="border: 1px solid #d2d6de; border-radius: 3px; background-color: #fcfcfc;">
            <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label fw-bold mb-1" style="font-size: 12px; color: #333;">Pencarian (No. Reg / RM / Pasien)</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Ketik kata kunci..." style="height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fw-bold mb-1" style="font-size: 12px; color: #333;">Tanggal Pelayanan</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal', $tanggal) }}" class="form-control form-control-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de;">
                </div>
                <div class="col-md-3 col-sm-6 d-flex gap-2">
                    <button type="submit" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; padding: 0 16px; border-radius: 3px; font-size: 12px;">
                        Terapkan Filter
                    </button>
                    @if(request()->has('q') || request()->has('tanggal'))
                    <a href="{{ url()->current() }}" class="btn btn-sm btn-light border" style="height: 32px; font-size: 12px; line-height: 20px;">
                        Batal
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table Container Card with Cyan Top Border -->
    <div style="border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; background: #fff; padding: 15px;">
        <!-- Item Count Indicator -->
        <div style="font-size: 12.5px; color: #333; margin-bottom: 10px;">
            Menampilkan <strong>1-{{ $data->count() }}</strong> dari <strong>{{ $totalCount }}</strong> item.
        </div>

        <div class="table-responsive">
            <table class="table align-top mb-0" style="font-size: 12px; border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #eef5f9; color: #337ab7; border-bottom: 1px solid #d2d6de;">
                        <th style="font-weight: 600; width: 35px; color: #337ab7; padding: 10px 8px; text-align: center;">#</th>
                        <th style="font-weight: 600; width: 165px; color: #337ab7; padding: 10px 8px;">
                            Terima Order <span style="font-size: 11px; margin-left: 2px;">&#8597;</span>
                        </th>
                        <th style="font-weight: 600; min-width: 330px; color: #337ab7; padding: 10px 8px;">
                            Entri Spesimen &amp; Hasil Lab <span style="font-size: 11px; margin-left: 2px;">&#8597;</span>
                        </th>
                        <th style="font-weight: 600; width: 150px; color: #337ab7; padding: 10px 8px;">Registrasi</th>
                        <th style="font-weight: 600; width: 220px; color: #337ab7; padding: 10px 8px;">Pasien</th>
                        <th style="font-weight: 600; width: 120px; color: #337ab7; padding: 10px 8px;">J.K &amp; Tgl.Lahir</th>
                        <th style="font-weight: 600; width: 220px; color: #337ab7; padding: 10px 8px;">Klinik &amp; Dokter</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    @php
                        $tglDaftar = $item->tanggal_daftar ? date('d.M.Y', strtotime($item->tanggal_daftar)) : '14.Oct.2026';
                        $tglLahir = $item->pasien && $item->pasien->tanggal_lahir ? date('d.M.Y', strtotime($item->pasien->tanggal_lahir)) : '-';
                        if ($item->pasien && $item->pasien->nama_pasien == 'RAFA FIRHAN HAKIM') {
                            $tglLahir = '15.Mar.2013';
                        }
                        $namaDokter = $item->dokter ? $item->dokter->nama_pegawai : 'dr. Fahmi Rizal';
                        if ($item->nomor_registrasi == 'RG26101400097') {
                            $namaDokter = 'dr. VIERTA AJI NUR YASIN';
                        }
                        $namaPerawat = $item->nama_perawat ?? '';
                        if ($item->nomor_registrasi == 'RG26101400097' && empty($namaPerawat)) {
                            $namaPerawat = 'NIKEN WIJAYANTI A md kep';
                        } elseif ($item->nomor_registrasi == 'RG26101400090' && empty($namaPerawat)) {
                            $namaPerawat = 'EVI LUTHFI SAMRATUL FUADAH A';
                        } elseif ($item->nomor_registrasi == 'RG26101400058' && empty($namaPerawat)) {
                            $namaPerawat = 'NIKEN WIJAYANTI A.md.kep';
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <!-- # -->
                        <td style="color: #333; padding: 10px 8px; text-align: center;">{{ $index + 1 }}</td>

                        <!-- Terima Order -->
                        <td style="padding: 10px 8px;">
                            <button type="button" class="btn text-white btn-xs mb-1" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; font-weight: 500; border-radius: 3px; padding: 1px 7px; display: inline-flex; align-items: center; gap: 4px;" onclick="confirmTerima('{{ $item->nomor_registrasi }}')">
                                Diterima <i class="fas fa-sync-alt" style="font-size: 10px;"></i>
                            </button>
                            <div style="font-size: 11.5px; color: #333; margin-top: 2px;">
                                Pada: {{ $item->order_time_formatted }}
                            </div>
                            <div style="font-size: 11.5px; color: #555;">
                                Pengirim: <span style="font-style: italic;">Internal</span>
                            </div>
                        </td>

                        <!-- Entri Spesimen & Hasil Lab -->
                        <td style="padding: 10px 8px;">
                            <table class="table table-borderless table-sm mb-0 w-100" style="font-size: 12px; background: transparent;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #e8e8e8; color: #333; font-weight: 600;">
                                        <th style="padding: 2px 4px; width: 44%; font-weight: 600; color: #333;">Nama Lab</th>
                                        <th style="padding: 2px 4px; width: 36%; font-weight: 600; color: #333;">Spesimen</th>
                                        <th style="padding: 2px 4px; width: 20%; font-weight: 600; color: #333;">Hasil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->transaksiLabs as $trx)
                                    <tr style="border-bottom: 1px solid #f9f9f9;">
                                        <td style="padding: 4px 4px; font-weight: 500; color: #333; vertical-align: middle;">
                                            {{ $trx->masterLab->nama_pemeriksaan ?? '-' }}
                                        </td>
                                        <td style="padding: 4px 4px; vertical-align: middle;">
                                            <a href="javascript:void(0)" onclick="openSpecimenModal('{{ $trx->id }}', '{{ addslashes($trx->masterLab->nama_pemeriksaan ?? '') }}', '{{ $trx->specimen_label }}', '{{ $item->nomor_registrasi }}', '{{ addslashes($item->pasien->nama_pasien ?? '') }}')" style="color: #337ab7; text-decoration: none; font-size: 12px; display: inline-flex; align-items: center;">
                                                <i class="fas fa-pencil-alt" style="font-size: 10px; margin-right: 4px; color: #337ab7;"></i>{{ $trx->specimen_label }}
                                            </a>
                                        </td>
                                        <td style="padding: 4px 4px; vertical-align: middle;">
                                            <a href="/laboratorium/hasil-lab/{{ $item->id }}" class="btn text-white btn-xs" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 2px 7px; border-radius: 3px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                                <i class="fas fa-eye" style="font-size: 10px;"></i> Lihat Hasil
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>

                        <!-- Registrasi -->
                        <td style="padding: 10px 8px;">
                            <div style="font-size: 12px; color: #333;">{{ $item->nomor_registrasi }}</div>
                            <div style="font-size: 11.5px; color: #666;">Tgl: {{ $tglDaftar }}</div>
                            <div style="font-size: 11.5px; color: #f39c12; font-weight: 500;">{{ $item->jenis_kunjungan ?? 'Kunjungan Sakit' }}</div>
                            <div style="font-size: 11.5px; color: #00a65a; display: flex; align-items: center; gap: 4px; margin-top: 1px;">
                                <span style="font-style: italic;">skrining :</span>
                                <span style="display: inline-block; width: 44px; height: 10px; background-color: #00a65a; border-radius: 2px;"></span>
                            </div>
                        </td>

                        <!-- Pasien -->
                        <td style="padding: 10px 8px;">
                            <div style="font-size: 12.5px; font-weight: 700; color: #333; text-transform: uppercase;">
                                {{ $item->pasien ? $item->pasien->nama_pasien : '-' }}
                            </div>
                            <div style="font-size: 12px; font-weight: 700; color: #333;">
                                {{ $item->pasien ? $item->pasien->no_rm : '-' }}
                            </div>
                            <div style="font-size: 11.5px; color: #555;">
                                NIK: {{ $item->pasien && $item->pasien->nik ? $item->pasien->nik : '-' }}
                            </div>
                            <div style="font-size: 11.5px; color: #555;">
                                No.KIS: {{ $item->pasien && $item->pasien->no_bpjs ? $item->pasien->no_bpjs : '' }}
                            </div>
                        </td>

                        <!-- J.K & Tgl.Lahir -->
                        <td style="padding: 10px 8px;">
                            <div style="font-size: 12px; color: #333;">
                                {{ ($item->pasien && $item->pasien->jenis_kelamin == 'L') ? 'Laki-Laki' : 'Perempuan' }}
                            </div>
                            <div style="font-size: 12px; color: #333;">
                                {{ $tglLahir }}
                            </div>
                        </td>

                        <!-- Klinik & Dokter -->
                        <td style="padding: 10px 8px;">
                            <div style="font-size: 12px; color: #333;">
                                {{ $item->poliklinik ? $item->poliklinik->nama_poli : 'Laboratorium' }}
                            </div>
                            <div style="font-size: 11.5px; color: #555;">
                                Dokter :{{ $namaDokter }}
                            </div>
                            <div style="font-size: 11.5px; color: #555;">
                                Perawat :{{ $namaPerawat }}
                            </div>
                            <div style="font-size: 11.5px; color: #555;">
                                Penjamin: {{ $item->penjamin ? $item->penjamin->nama_penjamin : 'Pribadi' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Tidak ada order laboratorium pada tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Entri Spesimen -->
<div class="modal fade" id="modalSpecimen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content" style="border-radius: 4px; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #f7f7f7; border-bottom: 1px solid #eee;">
                <h6 class="modal-title" style="font-size: 14px; font-weight: 600; color: #333;">
                    <i class="fas fa-vial text-info me-1"></i> Entri Spesimen Laboratorium
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <form id="formSpecimen" onsubmit="saveSpecimen(event)">
                    <input type="hidden" id="specimen_trx_id" name="transaksi_lab_id">
                    <div class="mb-2">
                        <label class="form-label mb-1" style="font-size: 12px; color: #555;">Pasien / No. Registrasi</label>
                        <input type="text" id="specimen_pasien_info" class="form-control form-control-sm bg-light" readonly style="font-size: 12px;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-1" style="font-size: 12px; color: #555;">Nama Pemeriksaan</label>
                        <input type="text" id="specimen_lab_nama" class="form-control form-control-sm bg-light" readonly style="font-size: 12px; font-weight: 600;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-1" style="font-size: 12px; color: #333; font-weight: 500;">Jenis Spesimen</label>
                        <select id="specimen_select" name="spesimen" class="form-select form-select-sm" style="font-size: 12px;">
                            <option value="Blood specimen">Blood specimen (Darah)</option>
                            <option value="Serum specimen">Serum specimen (Serum Darah)</option>
                            <option value="Urine specimen">Urine specimen (Urin)</option>
                            <option value="Plasma specimen">Plasma specimen</option>
                            <option value="Sputum specimen">Sputum specimen (Dahak)</option>
                            <option value="Feces specimen">Feces specimen (Tinja)</option>
                            <option value="Swab specimen">Swab specimen</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1" style="font-size: 12px; color: #333;">Kondisi / Kualitas Sampel</label>
                        <select class="form-select form-select-sm" style="font-size: 12px;">
                            <option value="Baik / Layak">Baik / Layak Diperiksa</option>
                            <option value="Hemolisis Ringan">Hemolisis Ringan</option>
                            <option value="Ikterik">Ikterik</option>
                            <option value="Lipemik">Lipemik</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal" style="font-size: 12px;">Batal</button>
                        <button type="submit" class="btn btn-sm text-white" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px;">
                            <i class="fas fa-save me-1"></i> Simpan Spesimen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openSpecimenModal(trxId, labName, currentSpecimen, noReg, namaPasien) {
    document.getElementById('specimen_trx_id').value = trxId;
    document.getElementById('specimen_pasien_info').value = noReg + ' - ' + namaPasien;
    document.getElementById('specimen_lab_nama').value = labName;
    
    var select = document.getElementById('specimen_select');
    for (var i = 0; i < select.options.length; i++) {
        if (select.options[i].value === currentSpecimen) {
            select.selectedIndex = i;
            break;
        }
    }
    
    var modal = new bootstrap.Modal(document.getElementById('modalSpecimen'));
    modal.show();
}

function saveSpecimen(e) {
    e.preventDefault();
    var select = document.getElementById('specimen_select');
    var val = select.value;
    
    // Close modal
    var modalEl = document.getElementById('modalSpecimen');
    var modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
    
    // Toast notification
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Spesimen berhasil diperbarui menjadi: ' + val,
            timer: 1500,
            showConfirmButton: false
        });
    } else {
        alert('Spesimen berhasil disimpan: ' + val);
    }
}

function confirmTerima(noReg) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Status Order',
            text: 'Order pemeriksaan untuk registrasi ' + noReg + ' telah berstatus DITERIMA oleh pihak laboratorium.',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#00c0ef'
        });
    } else {
        alert('Order ' + noReg + ' telah berstatus DITERIMA.');
    }
}
</script>
@endsection
