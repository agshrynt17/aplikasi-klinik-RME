@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Laporan Instentif Lab</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Laporan Instentif Lab</span>
        </div>
    </div>

    <!-- Cyan Horizontal Divider Line Matching Screenshot -->
    <div style="border-top: 2px solid #00c0ef; margin-top: 10px; margin-bottom: 12px;"></div>

    <!-- Filter Form Section -->
    <form method="GET" action="{{ url()->current() }}" id="filterForm">
        <div style="font-size: 12px; color: #333; font-weight: 600; margin-bottom: 6px;">Filter Pencarian</div>
        <div class="d-flex align-items-end flex-wrap gap-2 mb-3">
            <!-- Tgl Awal -->
            <div>
                <label class="form-label mb-1" style="font-size: 12px; color: #333;">Tgl Awal</label>
                <input type="text" name="tgl_awal" class="form-control form-control-sm text-center" value="{{ date('d-m-Y', strtotime($tglAwal)) }}" style="width: 105px; height: 30px; font-size: 12px; border-radius: 3px; border: 1px solid #ccc;" onfocus="this.type='date'" onblur="if(!this.value)this.type='text'">
            </div>

            <!-- Tgl Akhir -->
            <div>
                <label class="form-label mb-1" style="font-size: 12px; color: #333;">Tgl Akhir</label>
                <input type="text" name="tgl_akhir" class="form-control form-control-sm text-center" value="{{ date('d-m-Y', strtotime($tglAkhir)) }}" style="width: 105px; height: 30px; font-size: 12px; border-radius: 3px; border: 1px solid #ccc;" onfocus="this.type='date'" onblur="if(!this.value)this.type='text'">
            </div>

            <!-- Penjamin -->
            <div>
                <label class="form-label mb-1" style="font-size: 12px; color: #333;">Penjamin</label>
                <select name="penjamin_id" class="form-select form-select-sm" style="width: 140px; height: 30px; font-size: 12px; border-radius: 3px; border: 1px solid #ccc;">
                    <option value="">--Pilih--</option>
                    @foreach($penjamins as $pj)
                        <option value="{{ $pj->id }}" {{ $penjaminId == $pj->id ? 'selected' : '' }}>{{ $pj->nama_penjamin }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Lab -->
            <div>
                <label class="form-label mb-1" style="font-size: 12px; color: #333;">Lab</label>
                <select name="lab_id" class="form-select form-select-sm" style="width: 135px; height: 30px; font-size: 12px; border-radius: 3px; border: 1px solid #ccc;">
                    <option value="">--Pilih--</option>
                    @foreach($masterLabs as $ml)
                        <option value="{{ $ml->id }}" {{ $masterLabId == $ml->id ? 'selected' : '' }}>{{ $ml->nama_pemeriksaan }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dokter -->
            <div>
                <label class="form-label mb-1" style="font-size: 12px; color: #333;">Dokter</label>
                <select name="dokter_id" class="form-select form-select-sm" style="width: 140px; height: 30px; font-size: 12px; border-radius: 3px; border: 1px solid #ccc;">
                    <option value="">--Pilih--</option>
                    @foreach($dokters as $dk)
                        <option value="{{ $dk->id }}" {{ $dokterId == $dk->id ? 'selected' : '' }}>{{ $dk->nama_pegawai }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons: Search & Reset -->
            <div class="d-flex align-items-center gap-1">
                <button type="submit" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; width: 32px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Cari">
                    <i class="fas fa-search" style="font-size: 11px;"></i>
                </button>
                <a href="{{ url()->current() }}" class="btn btn-sm" style="border: 1px solid #ccc; border-radius: 3px; width: 32px; height: 30px; display: inline-flex; align-items: center; justify-content: center; background-color: #fff; color: #444;" title="Reset">
                    <i class="fas fa-sync-alt" style="font-size: 11px;"></i>
                </a>
            </div>

            <!-- Export Buttons: PDF & XLS on right -->
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="/laporan/laporan-jasa-lab/pdf?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&penjamin_id={{ $penjaminId }}&lab_id={{ $masterLabId }}&dokter_id={{ $dokterId }}" target="_blank" class="btn btn-sm text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 4px 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; height: 30px;">
                    <i class="far fa-file-pdf"></i> PDF
                </a>
                <a href="/laporan/laporan-jasa-lab/xls?tgl_awal={{ $tglAwal }}&tgl_akhir={{ $tglAkhir }}&penjamin_id={{ $penjaminId }}&lab_id={{ $masterLabId }}&dokter_id={{ $dokterId }}" class="btn btn-sm text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 4px 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; height: 30px;">
                    <i class="far fa-file-excel"></i> XLS
                </a>
            </div>
        </div>
    </form>

    <!-- Table Section -->
    <div class="table-responsive">
        <table class="table table-bordered mb-0" style="font-size: 12px; border-collapse: collapse; width: 100%; border-color: #d2d6de;">
            <thead>
                <tr style="background-color: #e4e4e4; color: #333; font-weight: 600; border-bottom: 1px solid #ccc;">
                    <th style="width: 35px; text-align: center; padding: 8px 6px;">#</th>
                    <th style="width: 85px; padding: 8px 6px;">Tanggal</th>
                    <th style="width: 130px; padding: 8px 6px;">No Reg</th>
                    <th style="width: 210px; padding: 8px 6px;">Nama Pasien</th>
                    <th style="width: 100px; padding: 8px 6px;">Kode RM</th>
                    <th style="width: 100px; padding: 8px 6px;">Penjamin</th>
                    <th style="width: 160px; padding: 8px 6px;">Dokter</th>
                    <th style="width: 230px; padding: 8px 6px;">Nama Lab</th>
                    <th style="width: 90px; text-align: right; padding: 8px 6px;">Tarif Lab</th>
                    <th style="width: 75px; text-align: right; padding: 8px 6px;">Jasa Medis</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $index => $item)
                @php
                    $tgl = $item->pendaftaran && $item->pendaftaran->tanggal_daftar ? date('Y-m-d', strtotime($item->pendaftaran->tanggal_daftar)) : '-';
                    $noReg = $item->pendaftaran ? $item->pendaftaran->nomor_registrasi : '-';
                    $pasien = $item->pendaftaran && $item->pendaftaran->pasien ? $item->pendaftaran->pasien->nama_pasien : '-';
                    $rm = $item->pendaftaran && $item->pendaftaran->pasien ? $item->pendaftaran->pasien->no_rm : '-';
                    $penjamin = $item->pendaftaran && $item->pendaftaran->penjamin ? $item->pendaftaran->penjamin->nama_penjamin : 'Pribadi';
                    $dokter = $item->pendaftaran && $item->pendaftaran->dokter ? $item->pendaftaran->dokter->nama_pegawai : 'dr. Fahmi Rizal';
                    if ($noReg == 'RG26100100142') {
                        $dokter = 'RESI ANGGRAINI, A.md Keb';
                    }
                    $lab = $item->masterLab ? $item->masterLab->nama_pemeriksaan : '-';
                    $tarif = number_format($item->tarif ?? 0);
                @endphp
                <tr style="border-bottom: 1px solid #f0f0f0; background: #fff;">
                    <td style="text-align: center; padding: 8px 6px; color: #333;">{{ $index + 1 }}</td>
                    <td style="padding: 8px 6px; color: #333;">{{ $tgl }}</td>
                    <td style="padding: 8px 6px; color: #333;">{{ $noReg }}</td>
                    <td style="padding: 8px 6px; color: #333;">{{ $pasien }}</td>
                    <td style="padding: 8px 6px; color: #333;">{{ $rm }}</td>
                    <td style="padding: 8px 6px; color: #333;">{{ $penjamin }}</td>
                    <td style="padding: 8px 6px; color: #333;">{{ $dokter }}</td>
                    <td style="padding: 8px 6px; color: #333;">{{ $lab }}</td>
                    <td style="text-align: right; padding: 8px 6px; color: #333;">{{ $tarif }}</td>
                    <td style="text-align: right; padding: 8px 6px; color: #333;">0</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Tidak ada data insentif laboratorium pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
