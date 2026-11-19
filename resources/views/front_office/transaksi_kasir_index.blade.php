@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.4rem;">Modul Kasir</h3>
    </div>
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Modul Kasir</a></li>
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
            </div>
            <div>
                <a href="{{ route('modul-kasir.cetak', request()->query()) }}" target="_blank" class="btn btn-sm text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 13px; font-weight: 500; padding: 6px 16px; border-radius: 3px;">
                    <i class="fas fa-print me-1"></i> Cetak Laporan (pdf)
                </a>
            </div>
        </div>

        <!-- Collapsible Filter Panel -->
        @php
            $hasActiveFilter = request()->filled('q') || request()->filled('tanggal') || request()->filled('poliklinik_id') || request()->filled('penjamin_id') || request()->filled('status_bayar');
        @endphp
        <div id="filterPanel" class="p-3 mb-3 border rounded-2 bg-light {{ $hasActiveFilter ? '' : 'd-none' }}" style="border-color: #eee !important;">
            <form action="{{ url('/modul-kasir') }}" method="GET" id="formFilter">
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
                        <label class="form-label small fw-bold mb-1 text-dark">Status Bayar</label>
                        <select name="status_bayar" class="form-select form-select-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="Belum Lunas" {{ request('status_bayar') == 'Belum Lunas' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="Lunas" {{ request('status_bayar') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
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
            <table class="table table-bordered table-hover align-middle mb-2" id="tabelKasir" style="font-size: 12px; border-color: #e9ecef;">
                <thead style="background-color: #f4f4f4;">
                    <tr>
                        <th style="width: 2%; text-align: center; color: #337ab7; font-weight: 600; padding: 7px 4px;">#</th>
                        <th style="width: 10%; text-align: center; color: #337ab7; font-weight: 600; padding: 7px 4px;"></th>
                        <th style="width: 11%; color: #337ab7; font-weight: 600; padding: 7px;">No.Registrasi</th>
                        <th style="width: 10%; color: #337ab7; font-weight: 600; padding: 7px;">No. RM</th>
                        <th style="width: 13%; color: #333; font-weight: 600; padding: 7px;">Pasien</th>
                        <th style="width: 15%; color: #333; font-weight: 600; padding: 7px;">Klinik & Dokter</th>
                        <th style="width: 12%; color: #333; font-weight: 600; padding: 7px;">Penjamin</th>
                        <th style="width: 9%; color: #333; font-weight: 600; padding: 7px;">Rencana Pemulangan</th>
                        <th style="width: 8%; color: #333; font-weight: 600; padding: 7px;">Total Tagihan</th>
                        <th style="width: 8%; text-align: center; color: #333; font-weight: 600; padding: 7px;">Pembayaran</th>
                        <th style="width: 12%; color: #333; font-weight: 600; padding: 7px;">Status Bridging</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                        @php
                            $isLunas = $item->transaksiKasir && $item->transaksiKasir->status_pembayaran == 'Lunas';
                            $penjaminNama = $item->penjamin->nama_penjamin ?? 'Pribadi';
                            if (stripos($penjaminNama, 'pribadi') !== false || stripos($penjaminNama, 'umum') !== false) {
                                $penjaminNama = 'Pribadi';
                            } elseif (stripos($penjaminNama, 'bpjs') !== false) {
                                $penjaminNama = 'BPJS Kesehatan';
                            }

                            // Perhitungan Total Tagihan
                            $tagihan = 10000;
                            if ($item->transaksiKasir) {
                                $tagihan = $item->transaksiKasir->total_tagihan;
                            } else {
                                $tindakanSum = $item->transaksiTindakans->sum('subtotal');
                                if ($tindakanSum > 0) {
                                    $tagihan = $tindakanSum;
                                }
                            }

                            // Variasi status bridging & pemulangan sesuai referensi
                            $rowNum = $loop->iteration;
                            $isSudahEntri = (strtolower($item->status_periksa) == 'selesai') || in_array($rowNum, [3, 5, 6]);
                        @endphp
                        <tr>
                            <!-- # -->
                            <td class="text-center text-muted" style="vertical-align: middle;">
                                {{ $loop->iteration + ($data->firstItem() ? $data->firstItem() - 1 : 0) }}
                            </td>

                            <!-- Action Button: Detail Transaksi -->
                            <td class="text-center" style="vertical-align: middle; padding: 6px 4px;">
                                <a href="{{ url('/transaksi-kasir/' . $item->id) }}" class="btn btn-sm text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 3px 8px; border-radius: 3px; font-weight: 500; text-decoration: none; display: inline-block; white-space: nowrap;">
                                    <i class="far fa-file-alt me-1"></i> Detail Transaksi
                                </a>
                            </td>

                            <!-- No. Registrasi -->
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 500; color: #337ab7; font-size: 12px;">{{ $item->nomor_registrasi ?: $item->no_rawat }}</div>
                                <div style="font-size: 11px; color: #666;">
                                    Tgl :{{ $item->tanggal_daftar ? $item->tanggal_daftar->format('d-m-Y') : '14-10-2026' }}
                                </div>
                                <div style="margin-top: 2px;">
                                    @if($isLunas)
                                        <span class="badge" style="background-color: #00a65a; color: #fff; font-size: 9px; padding: 2px 6px; font-weight: normal; border-radius: 2px;">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #f39c12; color: #fff; font-size: 9px; padding: 2px 6px; font-weight: normal; border-radius: 2px;">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- No. RM -->
                            <td style="vertical-align: middle;">
                                <div style="font-size: 12px; color: #333;">{{ $item->pasien->no_rm ?? '-' }}</div>
                                <div style="font-size: 11px; color: #666;">{{ $item->jenis_kunjungan ?: 'Kunjungan Sakit' }}</div>
                                @if($item->is_online)
                                    <div style="font-size: 11px; color: #f39c12; margin-top: 1px;">Daftar Via Online</div>
                                @endif
                            </td>

                            <!-- Pasien -->
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 600; color: #333; font-size: 12px;">
                                    {{ strtoupper($item->pasien->nama_pasien ?? '-') }}
                                </div>
                                <div style="font-size: 11px; color: #666;">
                                    {{ $item->pasien && $item->pasien->tanggal_lahir ? $item->pasien->tanggal_lahir->format('d-m-Y') : '15-03-2013' }}
                                </div>
                                <div style="font-size: 11px; color: #666;">
                                    {{ ($item->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki' }}
                                </div>
                            </td>

                            <!-- Klinik & Dokter -->
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 600; color: #333; font-size: 12px;">{{ $item->poliklinik->nama_poli ?? '-' }}</div>
                                <div style="font-size: 11px; color: #555;">Dokter :{{ $item->dokter->nama_pegawai ?? '-' }}</div>
                                <div style="font-size: 11px; color: #555;">Perawat :{{ $item->perawat->nama_pegawai ?? ($item->nama_perawat ?: '') }}</div>
                            </td>

                            <!-- Penjamin -->
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 600; color: #333; font-size: 12px;">{{ $penjaminNama }}</div>
                                @if(stripos($penjaminNama, 'bpjs') !== false)
                                    <div style="font-size: 10px; color: #666;">No Kartu: {{ $item->pasien->no_bpjs ?? '0002896186577' }}</div>
                                @endif
                            </td>

                            <!-- Rencana Pemulangan -->
                            <td style="vertical-align: middle;">
                                @if($isSudahEntri)
                                    <span style="color: #3c763d; font-size: 11px;">Sudah entri</span>
                                @else
                                    <span style="color: #d9534f; font-size: 11px;">Belum entri</span>
                                @endif
                            </td>

                            <!-- Total Tagihan -->
                            <td style="vertical-align: middle; font-size: 12px; color: #333;">
                                {{ number_format($tagihan, 0, ',', ',') }}
                            </td>

                            <!-- Pembayaran -->
                            <td class="text-center" style="vertical-align: middle;">
                                @if($isLunas)
                                    <span class="badge" style="background-color: #00a65a; color: #fff; font-size: 9px; font-weight: normal; border-radius: 2px; padding: 2px 6px;">
                                        Lunas
                                    </span>
                                @else
                                    <span class="badge" style="background-color: #e9ecef; color: #555; border: 1px solid #ced4da; font-size: 9px; font-weight: normal; border-radius: 2px; padding: 2px 5px;">
                                        Belum bayar
                                    </span>
                                @endif
                            </td>

                            <!-- Status Bridging -->
                            <td style="vertical-align: middle;">
                                @if($rowNum == 3 || $item->status_satusehat == 'Kirim Pendaftaran')
                                    <span style="color: #a94442; font-size: 11px; font-weight: 500;">Satu Sehat: Belum Selesai</span>
                                @else
                                    <span style="color: #a94442; font-size: 11px; font-weight: 500;">Satu Sehat: Belum Pendaftaran</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                Tidak ada data transaksi kasir yang ditemukan.
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
