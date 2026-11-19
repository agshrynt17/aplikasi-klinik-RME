@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .pembayaran-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .konhakes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }

    /* Action Box */
    .card-action-box {
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        padding: 8px 12px;
        margin-bottom: 12px;
    }
    .btn-orange-cari {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-orange-cari:hover {
        background-color: #d68407;
    }
    .btn-cyan-cetak {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-cyan-cetak:hover {
        background-color: #00acd6;
    }
    .btn-green-excel {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-green-excel:hover {
        background-color: #008d4c;
    }
    .btn-reset-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-reset-light:hover {
        background-color: #f4f4f4;
    }

    /* Cyan Line */
    .cyan-divider {
        border-top: 2px solid #00c0ef;
        margin-top: 8px;
        margin-bottom: 8px;
    }

    .meta-info-line {
        font-size: 12px;
        color: #555;
        margin-bottom: 8px;
    }

    /* Table */
    .table-pembayaran-container {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #d2d6de;
        background: #fff;
    }
    .table-pembayaran {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .table-pembayaran thead th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 8px 10px;
        font-weight: 600;
        color: #337ab7;
        font-size: 12px;
        text-align: left;
        vertical-align: middle;
    }
    .table-pembayaran tbody td {
        border: 1px solid #e4e7ea;
        padding: 8px 10px;
        font-size: 11.5px;
        color: #333;
        vertical-align: top;
    }
    .table-pembayaran tbody tr:hover {
        background-color: #f7f9fa;
    }
    .badge-selesai {
        background-color: #00c0ef;
        color: #fff;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 2px;
        font-weight: normal;
        display: inline-block;
    }
    .link-reg {
        color: #337ab7 !important;
        font-weight: 600;
        text-decoration: none;
    }
    .link-reg:hover {
        text-decoration: underline;
    }
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
</style>

<div class="pembayaran-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Data Pembayaran Pasien</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/pembayaran/index">Data Pembayaran Pasien</a>
        </div>
    </div>

    {{-- Action Box --}}
    <div class="card-action-box">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <button type="button" class="btn-orange-cari" id="btnToggleFilter">
                <i class="fas fa-search"></i> Pencarian data
            </button>
            <a href="{{ route('pembayaran.pdf', ['tanggal' => $tanggal, 'q' => $q, 'poliklinik_id' => $poliklinikId, 'penjamin_id' => $penjaminId]) }}" target="_blank" class="btn-cyan-cetak">
                <i class="fas fa-file-pdf"></i> Cetak Pdf
            </a>
            <a href="{{ route('pembayaran.excel', ['tanggal' => $tanggal, 'q' => $q, 'poliklinik_id' => $poliklinikId, 'penjamin_id' => $penjaminId]) }}" target="_blank" class="btn-green-excel">
                <i class="fas fa-file-excel"></i> Cetak Excel
            </a>
            <a href="/pembayaran/index" class="btn-reset-light ms-auto" title="Reset">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterBox" class="mt-2 pt-2 border-top {{ $q || $poliklinikId || $penjaminId || $tanggal != '2026-10-14' ? '' : 'd-none' }}">
            <form method="GET" action="/pembayaran/index" class="d-flex align-items-center flex-wrap gap-2">
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm" style="max-width: 150px; height: 30px; font-size: 12px;"
                           value="{{ $tanggal }}">
                </div>
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Kata Kunci:</label>
                    <input type="text" name="q" class="form-control form-control-sm" style="max-width: 220px; height: 30px; font-size: 12px;"
                           placeholder="No Reg, Nama Pasien, RM..." value="{{ $q }}">
                </div>
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Poliklinik:</label>
                    <select name="poliklinik_id" class="form-select form-select-sm" style="height: 30px; font-size: 12px;">
                        <option value="">-- Semua Poliklinik --</option>
                        @foreach($polikliniks as $poli)
                            <option value="{{ $poli->id }}" {{ $poliklinikId == $poli->id ? 'selected' : '' }}>{{ $poli->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label mb-0 small text-muted" style="font-size: 11px;">Penjamin:</label>
                    <select name="penjamin_id" class="form-select form-select-sm" style="height: 30px; font-size: 12px;">
                        <option value="">-- Semua Penjamin --</option>
                        @foreach($penjamins as $penj)
                            <option value="{{ $penj->id }}" {{ $penjaminId == $penj->id ? 'selected' : '' }}>{{ $penj->nama_penjamin }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="align-self-end">
                    <button type="submit" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 12px; height: 30px;">Cari</button>
                    <a href="/pembayaran/index" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 12px; height: 30px;">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Cyan Divider Line --}}
    <div class="cyan-divider"></div>

    {{-- Meta Count Info --}}
    <div class="meta-info-line">
        Menampilkan {{ $data->firstItem() ?? 0 }}-{{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} item.
    </div>

    {{-- Table Data --}}
    <div class="table-pembayaran-container">
        <table class="table-pembayaran">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center; color: #333;">#</th>
                    <th style="width: 140px;">Registrasi</th>
                    <th style="width: 250px;">Pasien</th>
                    <th style="width: 130px;">Nama Poliklinik</th>
                    <th style="width: 180px;">Pembayaran</th>
                    <th style="width: 90px; text-align: right;">Tagihan</th>
                    <th style="width: 130px; text-align: right;">Tanggungan Penjamin</th>
                    <th style="width: 90px; text-align: right;">Non Penjamin</th>
                    <th style="width: 90px; text-align: right;">Jumlah Bayar</th>
                    <th style="width: 120px; text-align: center;">Tgl Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $tk)
                @php
                    $p = $tk->pendaftaran;
                    $tagihan = $tk->total_tagihan ?: 0;
                    $tanggungan = $tk->dijamin ?: $tagihan;
                    $nonPenj = $tk->dibayar_pasien ?: 0;
                    $jmlBayar = $tk->uang_diterima ?: $tagihan;
                    $tglReg = $p->tanggal_daftar ? \Carbon\Carbon::parse($p->tanggal_daftar)->format('d-m-Y') : '14-10-2026';
                    $tglBayar = $tk->waktu_bayar ? \Carbon\Carbon::parse($tk->waktu_bayar)->format('d-m-Y') : $tglReg;
                    $statusPenj = (stripos($p->penjamin->nama_penjamin ?? '', 'BPJS') !== false)
                        ? 'Ditanggung Penjamin Penuh'
                        : 'Pribadi / Non Penjamin';
                @endphp
                <tr id="row-pembayaran-{{ $tk->id }}">
                    <td class="text-center">{{ $data->firstItem() + $index }}</td>
                    <td>
                        <div><a href="#" class="link-reg">{{ $p->nomor_registrasi }}</a></div>
                        <div style="font-size: 11px; color: #666; margin-top: 1px;">Tgl: {{ $tglReg }}</div>
                        <div style="margin-top: 3px;"><span class="badge-selesai">Selesai</span></div>
                    </td>
                    <td>
                        <div style="color: #444;">Kode RM: {{ $p->pasien->no_rm ?? '-' }}</div>
                        <div style="font-weight: 600; color: #222;">Nama: {{ $p->pasien->nama_pasien ?? '-' }}</div>
                        <div style="color: #666;">Penjamin: {{ $p->penjamin->nama_penjamin ?? 'BPJS Kesehatan' }}</div>
                    </td>
                    <td>
                        {{ $p->poliklinik->nama_poli ?? ($p->ruang_pemeriksaan ?: 'Klinik Umum 1') }}
                    </td>
                    <td>
                        <div style="color: #333;">{{ $statusPenj }}</div>
                        <div style="color: #666;">Metode Bayar: {{ $tk->cara_bayar ?: 'Cash' }}</div>
                    </td>
                    <td class="text-right" style="font-weight: 500;">
                        {{ number_format($tagihan, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($tanggungan, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($nonPenj, 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="font-weight: 600;">
                        {{ number_format($jmlBayar, 0, ',', '.') }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center justify-content-between px-1">
                            <span>{{ $tglBayar }}</span>
                            <button type="button" class="btn btn-link btn-xs p-0 btn-delete-bayar" data-id="{{ $tk->id }}" title="Hapus / Batal Pembayaran">
                                <i class="fas fa-trash-alt" style="color: #00c0ef; font-size: 12px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">Tidak ada data pembayaran pasien ditemukan pada tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($data->hasPages())
    <div class="mt-3 d-flex justify-content-end">
        {{ $data->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#btnToggleFilter', function() {
            $('#filterBox').toggleClass('d-none');
        });

        // Handle delete / cancel payment
        $(document).on('click', '.btn-delete-bayar', function() {
            const id = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin membatalkan data pembayaran ini?')) {
                $.ajax({
                    url: '/pembayaran/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#row-pembayaran-' + id).fadeOut(300, function() { $(this).remove(); });
                        } else {
                            alert(data.message || 'Gagal membatalkan pembayaran');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat membatalkan pembayaran.');
                    }
                });
            }
        });
    });
</script>
@endsection

