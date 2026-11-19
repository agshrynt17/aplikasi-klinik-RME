@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Daftar Order Radiologi')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Daftar Order Radiologi</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Daftar Order Radiologi</span>
        </div>
    </div>

    <!-- Orange Horizontal Line -->
    <div style="height: 2px; background-color: #f39c12; margin-top: 8px; margin-bottom: 15px;"></div>

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
            <a href="/entryhasilrad/export-xls?tanggal={{ $tanggal }}" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; font-size: 12px; font-weight: 500; border-radius: 3px; padding: 5px 14px; text-decoration: none; display: inline-flex; align-items: center;">
                <i class="far fa-file-excel me-1" style="font-size: 11px;"></i> Cetak Laporan (xls)
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
        <div class="table-responsive">
            <table class="table align-top mb-0" style="font-size: 12px; border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #eef5f9; color: #333; border-bottom: 1px solid #d2d6de;">
                        <th style="font-weight: 600; width: 35px; color: #333; padding: 10px 8px; text-align: center; border-right: 1px solid #e0e0e0;">#</th>
                        <th style="font-weight: 600; width: 155px; color: #337ab7; padding: 10px 8px; border-right: 1px solid #e0e0e0;">
                            Terima Order <span style="font-size: 11px; margin-left: 2px;">&#8597;</span>
                        </th>
                        <th style="font-weight: 600; min-width: 250px; color: #333; padding: 10px 8px; border-right: 1px solid #e0e0e0;">Hasil Pemeriksaan Radiologi</th>
                        <th style="font-weight: 600; width: 140px; color: #337ab7; padding: 10px 8px; border-right: 1px solid #e0e0e0;">Registrasi</th>
                        <th style="font-weight: 600; width: 180px; color: #333; padding: 10px 8px; border-right: 1px solid #e0e0e0;">Pasien</th>
                        <th style="font-weight: 600; width: 130px; color: #333; padding: 10px 8px; border-right: 1px solid #e0e0e0;">J.K &amp; Tgl.Lahir</th>
                        <th style="font-weight: 600; width: 180px; color: #333; padding: 10px 8px; border-right: 1px solid #e0e0e0;">Klinik &amp; Dokter</th>
                        <th style="font-weight: 600; width: 160px; color: #333; padding: 10px 8px;">Pengiriman Data ke Satu Sehat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    @php
                        $tglDaftar = $item->tanggal_daftar ? date('d.M.Y', strtotime($item->tanggal_daftar)) : '14.Oct.2026';
                        $tglLahir = $item->pasien && $item->pasien->tanggal_lahir ? date('d.M.Y', strtotime($item->pasien->tanggal_lahir)) : '-';
                        $namaDokter = $item->dokter ? $item->dokter->nama_pegawai : 'dr. Fahmi Rizal';
                        $isAccepted = $item->transaksiRadiologis->whereIn('status', ['Proses', 'Selesai'])->count() > 0;
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="color: #333; padding: 10px 8px; text-align: center; vertical-align: top; border-right: 1px solid #f0f0f0;">{{ $index + 1 }}</td>
                        <td style="padding: 10px 8px; vertical-align: top; border-right: 1px solid #f0f0f0;">
                            @if($isAccepted)
                                <span class="badge" style="background-color: #00a65a; color: white; font-size: 11px; padding: 4px 8px; font-weight: normal; border-radius: 3px;">
                                    <i class="fas fa-check me-1"></i> Order Diterima
                                </span>
                            @else
                                <button type="button" class="btn btn-xs text-white btn-terima-order" data-id="{{ $item->id }}" style="background-color: #00a65a; border-color: #008d4c; font-size: 11px; padding: 3px 8px; border-radius: 3px;">
                                    <i class="fas fa-check me-1"></i> Terima Order
                                </button>
                            @endif
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top; border-right: 1px solid #f0f0f0;">
                            @foreach($item->transaksiRadiologis as $tx)
                                <div class="mb-1 d-flex align-items-center flex-wrap gap-1">
                                    <strong style="color: #333;">{{ $tx->masterRadiologi->nama_pemeriksaan ?? '-' }}</strong>
                                    @if($tx->hasilRadiologi && $tx->hasilRadiologi->status_verifikasi == 'Terverifikasi')
                                        <span class="badge bg-success" style="font-size: 10px; padding: 2px 6px; border-radius: 3px;">Selesai</span>
                                    @elseif($tx->hasilRadiologi)
                                        <span class="badge bg-info text-white" style="font-size: 10px; padding: 2px 6px; border-radius: 3px;">Draft</span>
                                    @else
                                        <span class="badge bg-warning text-dark" style="font-size: 10px; padding: 2px 6px; border-radius: 3px;">Belum Diperiksa</span>
                                    @endif
                                </div>
                            @endforeach
                            <a href="/entryhasilrad/{{ $item->id }}" class="btn btn-xs text-white mt-1" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 3px 8px; border-radius: 3px; text-decoration: none; display: inline-flex; align-items: center;">
                                <i class="fas fa-edit me-1"></i> Entri Hasil Radiologi
                            </a>
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top; border-right: 1px solid #f0f0f0;">
                            <span style="color: #337ab7; font-weight: 500;">{{ $item->nomor_registrasi }}</span><br>
                            <span style="color: #666; font-size: 11px;">Tgl : {{ $tglDaftar }}</span><br>
                            <span class="badge" style="background-color: #00c0ef; color: white; font-size: 10px; padding: 2px 6px; border-radius: 3px; font-weight: 500;">
                                {{ $item->penjamin->nama_penjamin ?? 'UMUM' }}
                            </span>
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top; border-right: 1px solid #f0f0f0;">
                            <span style="color: #333; font-weight: 600; text-transform: uppercase;">{{ $item->pasien->nama_pasien ?? '-' }}</span><br>
                            <span style="color: #337ab7; font-size: 11.5px; font-weight: 500;">No. RM: {{ $item->pasien->no_rm ?? '-' }}</span>
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top; border-right: 1px solid #f0f0f0;">
                            <span style="color: #333;">{{ ($item->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki' }}</span><br>
                            <span style="color: #666; font-size: 11px;">{{ $tglLahir }}</span>
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top; border-right: 1px solid #f0f0f0;">
                            <span style="color: #333;">{{ $item->poliklinik ? $item->poliklinik->nama_poli : 'Poli Umum' }}</span><br>
                            <span style="color: #666; font-size: 11px;">{{ $namaDokter }}</span>
                        </td>
                        <td style="padding: 10px 8px; vertical-align: top;">
                            <span class="badge" style="background-color: #f39c12; color: white; font-size: 11px; padding: 4px 7px; border-radius: 3px; font-weight: normal;">
                                <i class="fas fa-clock me-1"></i> Belum Kirim
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td colspan="8" style="padding: 12px 15px; color: #333; font-size: 13px; text-align: left; background-color: #fff;">
                            Tidak ada data yang ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Terima Order AJAX
    $(document).on('click', '.btn-terima-order', function () {
        const id = $(this).data('id');
        const $targetBtn = $(this);

        $targetBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: `/entryhasilrad/${id}/terima`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function (data) {
                if (data.success) {
                    $targetBtn.replaceWith('<span class="badge" style="background-color: #00a65a; color: white; font-size: 11px; padding: 4px 8px; font-weight: normal; border-radius: 3px;"><i class="fas fa-check me-1"></i> Order Diterima</span>');
                } else {
                    alert('Gagal menerima order: ' + (data.message || 'Terjadi kesalahan'));
                    $targetBtn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Terima Order');
                }
            },
            error: function () {
                alert('Terjadi kesalahan jaringan.');
                $targetBtn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Terima Order');
            }
        });
    });
});
</script>
@endsection
