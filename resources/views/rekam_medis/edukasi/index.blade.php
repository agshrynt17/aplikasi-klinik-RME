@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Edukasi Terintegrasi Rawat Jalan')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Edukasi Terintegrasi Rawat Jalan</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Edukasi Terintegrasi Rawat Jalan</span>
        </div>
    </div>

    <!-- Orange Divider Line (1:1 KONHAKES) -->
    <div style="height: 2px; background-color: #f39c12; margin-top: 8px; margin-bottom: 15px;"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    <!-- Action Toolbar (1:1 KONHAKES) -->
    <div class="d-flex align-items-center gap-2 mb-3">
        <button type="button" class="btn text-white btn-sm" id="btnToggleCari" style="background-color: #f39c12; border-color: #e08e0b; font-size: 12px; font-weight: 500; height: 32px; padding: 0 14px; display: inline-flex; align-items: center; gap: 6px; border-radius: 3px;" onclick="toggleFilterForm()">
            <i class="fas fa-search"></i> Cari data
        </button>
        <a href="/edukasi/index" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset Filter">
            <i class="fas fa-sync-alt"></i>
        </a>
    </div>

    <!-- Collapsible Search Card -->
    <div id="filterFormContainer" style="display: {{ request()->hasAny(['tanggal', 'keyword', 'poliklinik_id', 'penjamin_id']) ? 'block' : 'none' }}; background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px;">Filter Pencarian Edukasi Terintegrasi</div>
        <form method="GET" action="/edukasi/index">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Tanggal Kunjungan</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ $tanggal ?: '2026-10-14' }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-3">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kata Kunci (No. Reg / RM / Nama)</label>
                    <input type="text" name="keyword" class="form-control form-control-sm" value="{{ $keyword }}" placeholder="Cari..." style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-3">
                    <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Klinik</label>
                    <select name="poliklinik_id" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">-- Semua Klinik --</option>
                        @foreach($listKlinik as $k)
                            <option value="{{ $k->id }}" {{ $poliklinikId == $k->id ? 'selected' : '' }}>{{ $k->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; font-size: 12px; padding: 0 14px; border-radius: 3px;">
                        <i class="fas fa-search me-1"></i> Terapkan
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" style="height: 32px; font-size: 12px; padding: 0 14px; border-radius: 3px;" onclick="toggleFilterForm()">
                        Tutup
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card with Cyan Top Border (1:1 KONHAKES) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 20px;">
        @if($pendaftarans->isNotEmpty())
        <div style="padding: 9px 15px; font-size: 12px; color: #444; font-weight: 600; border-bottom: 1px solid #eef2f5;">
            Menampilkan {{ $pendaftarans->firstItem() ?? 0 }}-{{ $pendaftarans->lastItem() ?? 0 }} dari {{ $pendaftarans->total() }} item
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 11.5px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; color: #333; font-weight: 600; font-size: 11.5px; border-bottom: 2px solid #d2d6de;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; padding: 8px 6px;">#</th>
                        <th class="text-center" style="width: 120px; padding: 8px 8px;">Status</th>
                        <th style="padding: 8px 12px; width: 160px;">No. Registrasi</th>
                        <th style="padding: 8px 12px; width: 220px;">No. Rekam Medis</th>
                        <th style="padding: 8px 12px; width: 150px;">Tgl Lahir &amp; Usia</th>
                        <th style="padding: 8px 12px; width: 240px;">Klinik &amp; Dokter</th>
                        <th style="padding: 8px 12px; width: 140px;">Penjamin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $index => $item)
                    @php
                        $hasEdukasi = isset($edukasis[$item->id]);
                        $gender = ($item->pasien && ($item->pasien->jenis_kelamin == 'L' || $item->pasien->jenis_kelamin == 'Laki-laki')) ? 'Laki-Laki' : 'Perempuan';
                        $statusPeriksa = $item->status_periksa ?: 'Dalam Proses';
                        
                        $usiaText = '-';
                        if ($item->pasien && $item->pasien->tanggal_lahir) {
                            $diffYears = \Carbon\Carbon::parse($item->pasien->tanggal_lahir)->diffInYears($item->tanggal_daftar ?? now());
                            $diffMonths = \Carbon\Carbon::parse($item->pasien->tanggal_lahir)->diffInMonths($item->tanggal_daftar ?? now()) % 12;
                            $usiaText = "{$diffYears} th {$diffMonths} bln";
                        }
                    @endphp
                    <tr class="align-middle" style="background-color: {{ $loop->iteration % 2 == 0 ? '#ffffff' : '#fcfcfc' }};">
                        <td class="text-center" style="padding: 8px 6px; color: #555;">{{ $pendaftarans->firstItem() + $index }}</td>
                        <td class="text-center" style="padding: 8px 8px;">
                            @if($hasEdukasi)
                                <a href="/edukasi/edit/{{ $edukasis[$item->id]->id }}" class="badge text-decoration-none" style="background-color: #00a65a; color: #fff; font-size: 10px; font-weight: normal; padding: 4px 6px; border-radius: 2px; display: inline-block;">
                                    Sudah Edukasi
                                </a>
                            @else
                                <a href="/edukasi/create/{{ $item->id }}" class="badge text-decoration-none" style="background-color: #f39c12; color: #fff; font-size: 10px; font-weight: normal; padding: 4px 6px; border-radius: 2px; display: inline-block;">
                                    Belum Edukasi
                                </a>
                            @endif
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>
                                <a href="/edukasi/create/{{ $item->id }}" style="color: #337ab7; text-decoration: none; font-weight: 500;">
                                    {{ $item->nomor_registrasi ?: $item->no_rawat }}
                                </a>
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                Tgl: {{ date('d.M.Y', strtotime($item->tanggal_daftar)) }}
                            </div>
                            <div style="margin-top: 3px;">
                                <span class="badge" style="background-color: {{ $statusPeriksa == 'Selesai' ? '#00c0ef' : '#f39c12' }}; color: #fff; font-size: 9.5px; font-weight: normal; padding: 2px 5px; border-radius: 2px;">
                                    {{ $statusPeriksa }}
                                </span>
                            </div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>
                                <a href="/master-pasien?search={{ $item->pasien->no_rm ?? '' }}" style="color: #337ab7; text-decoration: none; font-weight: 500;">
                                    {{ $item->pasien->no_rm ?? '-' }}
                                </a>
                            </div>
                            <div style="font-weight: 600; color: #333; font-size: 11.5px; margin-top: 2px;">
                                {{ strtoupper($item->pasien->nama_pasien ?? '-') }}
                            </div>
                            <div style="font-size: 11px; color: #555;">
                                {{ $gender }}
                            </div>
                            <div style="font-size: 11px; color: #555;">
                                {{ $item->jenis_kunjungan ?: 'Kunjungan Sakit' }}
                            </div>
                            @if($item->is_online)
                                <div style="color: #e67e22; font-size: 10.5px; margin-top: 2px;">
                                    Daftar Via Online
                                </div>
                            @endif
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="color: #333; font-size: 11.5px;">
                                {{ ($item->pasien && $item->pasien->tanggal_lahir) ? date('d.M.Y', strtotime($item->pasien->tanggal_lahir)) : '-' }}
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                {{ $usiaText }}
                            </div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="color: #333; font-weight: 500;">
                                {{ $item->poliklinik->nama_poli ?? '-' }}
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                Dokter :{{ $item->dokter->nama_pegawai ?? '-' }}
                            </div>
                            <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                Perawat :{{ $item->nama_perawat ?? '' }}
                            </div>
                        </td>
                        <td style="padding: 8px 12px; font-size: 11.5px; color: #333;">
                            {{ $item->penjamin->nama_penjamin ?? 'Pribadi' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 12px 15px; font-size: 11.5px; color: #555;">
                            Tidak ada data yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($pendaftarans->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $pendaftarans->firstItem() }} sampai {{ $pendaftarans->lastItem() }} dari {{ $pendaftarans->total() }} entri
            </div>
            <div>
                {{ $pendaftarans->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function toggleFilterForm() {
    var container = document.getElementById('filterFormContainer');
    if (container.style.display === 'none' || container.style.display === '') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}
</script>
@endsection
