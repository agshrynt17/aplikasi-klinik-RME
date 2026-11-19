@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.4rem;">Survei</h3>
    </div>
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Survei</a></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Card with Top Orange Border -->
<div class="card shadow-sm border-0" style="border-top: 3px solid #f39c12 !important; border-radius: 3px; background: #fff;">
    <div class="card-body p-3">

        <!-- Top Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2 align-items-center">
                <button type="button" class="btn btn-sm text-white" id="btnToggleFilter" style="background-color: #f39c12; border-color: #e08e0b; font-size: 13px; font-weight: 500; padding: 6px 14px; border-radius: 3px;">
                    <i class="fas fa-search me-1"></i> Cari data
                </button>
                <a href="{{ url('/survei') }}" class="btn btn-sm btn-light border" style="background-color: #fff; border-color: #d2d6de; color: #444; padding: 6px 12px; border-radius: 3px;" title="Reset Filter">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
            <div>
                <!-- Right side is intentionally empty to match KONHAKES screenshot -->
            </div>
        </div>

        <!-- Collapsible Filter Panel -->
        @php
            $hasActiveFilter = request()->filled('q') || request()->filled('tanggal') || request()->filled('poliklinik_id') || request()->filled('penjamin_id') || request()->filled('status_survei');
        @endphp
        <div id="filterPanel" class="p-3 mb-3 border rounded-2 bg-light {{ $hasActiveFilter ? '' : 'd-none' }}" style="border-color: #eee !important;">
            <form action="{{ url('/survei') }}" method="GET" id="formFilter">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 text-dark">Pencarian</label>
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="No. Reg, No. RM, atau Nama Pasien..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1 text-dark">Filter Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1 text-dark">Poliklinik</label>
                        <select name="poliklinik_id" class="form-select form-select-sm">
                            <option value="">-- Semua Poli --</option>
                            @foreach($polikliniks as $poli)
                                <option value="{{ $poli->id }}" {{ request('poliklinik_id') == $poli->id ? 'selected' : '' }}>{{ $poli->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1 text-dark">Penjamin</label>
                        <select name="penjamin_id" class="form-select form-select-sm">
                            <option value="">-- Semua Penjamin --</option>
                            @foreach($penjamins as $penj)
                                <option value="{{ $penj->id }}" {{ request('penjamin_id') == $penj->id ? 'selected' : '' }}>{{ $penj->nama_penjamin }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1 text-dark">Status Survei</label>
                        <select name="status_survei" class="form-select form-select-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="belum" {{ request('status_survei') == 'belum' ? 'selected' : '' }}>Belum Isi Survei</option>
                            <option value="sudah" {{ request('status_survei') == 'sudah' ? 'selected' : '' }}>Sudah Isi Survei</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="Terapkan Filter">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Counter Text -->
        <div class="mb-2 text-dark" style="font-size: 13px;">
            Menampilkan <strong>{{ $data->firstItem() ?? ($data->count() > 0 ? 1 : 0) }}-{{ $data->lastItem() ?? $data->count() }}</strong> dari <strong>{{ $total }}</strong> item
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-2" id="tabelSurvei" style="font-size: 13px; border-color: #e9ecef;">
                <thead style="background-color: #f4f4f4;">
                    <tr>
                        <th style="width: 3%; text-align: center; color: #337ab7; font-weight: 600; padding: 8px;">#</th>
                        <th style="width: 13%; text-align: center; color: #337ab7; font-weight: 600; padding: 8px;">Status</th>
                        <th style="width: 14%; color: #337ab7; font-weight: 600; padding: 8px;">No. Registrasi</th>
                        <th style="width: 14%; color: #337ab7; font-weight: 600; padding: 8px;">No. Rekam Medis</th>
                        <th style="width: 18%; color: #337ab7; font-weight: 600; padding: 8px;">Nama & J.K</th>
                        <th style="width: 10%; color: #337ab7; font-weight: 600; padding: 8px;">Tgl Lahir</th>
                        <th style="width: 11%; color: #337ab7; font-weight: 600; padding: 8px;">Penjamin</th>
                        <th style="width: 17%; color: #337ab7; font-weight: 600; padding: 8px;">Klinik & Dokter</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                        @php
                            $hasSurvei = $item->surveiKepuasan;
                            $penjaminNama = $item->penjamin->nama_penjamin ?? 'Pribadi';
                            if (stripos($penjaminNama, 'pribadi') !== false || stripos($penjaminNama, 'umum') !== false) {
                                $penjaminNama = 'Pribadi';
                            } elseif (stripos($penjaminNama, 'bpjs') !== false) {
                                $penjaminNama = 'BPJS Kesehatan';
                            }
                        @endphp
                        <tr>
                            <!-- # -->
                            <td class="text-center text-muted" style="vertical-align: middle;">
                                {{ $loop->iteration + ($data->firstItem() ? $data->firstItem() - 1 : 0) }}
                            </td>

                            <!-- Status & Button Isi Survei -->
                            <td class="text-center" style="vertical-align: middle; padding: 8px 6px;">
                                @if($hasSurvei)
                                    <span class="badge" style="background-color: #00a65a; color: #fff; font-size: 10px; font-weight: normal; border-radius: 2px; padding: 3px 6px; display: inline-block; margin-bottom: 5px;">
                                        Sudah Isi Survei
                                    </span>
                                    <br>
                                    <a href="{{ url('/survei/' . $item->id) }}" class="btn btn-sm text-white" style="background-color: #00a65a; border-color: #008d4c; font-size: 11px; padding: 2px 8px; border-radius: 2px; text-decoration: none; display: inline-block; font-weight: 500;">
                                        <i class="fas fa-check fa-xs"></i> Lihat Hasil
                                    </a>
                                @else
                                    <span class="badge" style="background-color: #f39c12; color: #fff; font-size: 10px; font-weight: normal; border-radius: 2px; padding: 3px 6px; display: inline-block; margin-bottom: 5px;">
                                        Belum Isi Survei
                                    </span>
                                    <br>
                                    <a href="{{ url('/survei/' . $item->id) }}" class="btn btn-sm text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 2px 8px; border-radius: 2px; text-decoration: none; display: inline-block; font-weight: 500;">
                                        + Isi Sekarang
                                    </a>
                                @endif
                            </td>

                            <!-- No. Registrasi -->
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 500; color: #333; font-size: 13px;">{{ $item->nomor_registrasi ?: $item->no_rawat }}</div>
                                <div style="font-size: 11px; color: #666;">
                                    Tgl: {{ $item->tanggal_daftar ? $item->tanggal_daftar->format('d.M.Y') : ($item->created_at ? $item->created_at->format('d.M.Y') : '14.Oct.2026') }}
                                </div>
                            </td>

                            <!-- No. Rekam Medis -->
                            <td style="vertical-align: middle;">
                                <div style="font-size: 13px; color: #333;">{{ $item->pasien->no_rm ?? '-' }}</div>
                                <div style="font-size: 11px; color: #666;">{{ $item->jenis_kunjungan ?: 'Kunjungan Sakit' }}</div>
                                @if($item->is_online)
                                    <div style="font-size: 11px; color: #f39c12; margin-top: 1px;">Daftar Via Online</div>
                                @endif
                            </td>

                            <!-- Nama & J.K -->
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 600; color: #333; font-size: 13px;">
                                    {{ strtoupper($item->pasien->nama_pasien ?? '-') }}
                                </div>
                                <div style="font-size: 11px; color: #555;">
                                    {{ ($item->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki' }}
                                </div>
                                <div style="font-size: 11px; color: #777; margin-top: 2px;">
                                    skrining : <span style="display: inline-block; width: 26px; height: 9px; background-color: #00a65a; border-radius: 2px; vertical-align: middle; margin-left: 2px;"></span>
                                </div>
                            </td>

                            <!-- Tgl Lahir -->
                            <td style="vertical-align: middle; font-size: 12px; color: #333;">
                                {{ $item->pasien && $item->pasien->tanggal_lahir ? $item->pasien->tanggal_lahir->format('d M Y') : '-' }}
                            </td>

                            <!-- Penjamin -->
                            <td style="vertical-align: middle; font-size: 12px; color: #333;">
                                {{ $penjaminNama }}
                            </td>

                            <!-- Klinik & Dokter -->
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 500; color: #333; font-size: 12px;">{{ $item->poliklinik->nama_poli ?? '-' }}</div>
                                <div style="font-size: 11px; color: #555;">Dokter :{{ $item->dokter->nama_pegawai ?? '-' }}</div>
                                <div style="font-size: 11px; color: #555;">Perawat :{{ $item->perawat->nama_pegawai ?? ($item->nama_perawat ?: '') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada data kunjungan / survei kepuasan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($data->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Halaman {{ $data->currentPage() }} dari {{ $data->lastPage() }}
                </div>
                <div>
                    {{ $data->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

    </div>
</div>

<script>
$(document).ready(function () {
    // Toggle filter panel
    $('#btnToggleFilter').click(function () {
        $('#filterPanel').toggleClass('d-none');
    });
});
</script>
@endsection
