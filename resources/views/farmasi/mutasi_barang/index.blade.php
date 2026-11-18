@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 MUTASI BARANG ANTAR GUDANG */
    .mb-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
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
        color: #444;
        text-decoration: none;
    }
    .konhakes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .cyan-divider {
        height: 3px;
        background-color: #00c0ef;
        margin-top: 5px;
        margin-bottom: 12px;
    }
    .card-mb-box {
        background-color: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 12px 15px;
    }
    .filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #444;
        margin-bottom: 3px;
        display: block;
    }
    .filter-ctrl {
        height: 30px;
        font-size: 12px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 3px 6px;
        width: 100%;
        color: #333;
        background-color: #fff;
    }
    .filter-ctrl:focus {
        border-color: #3c8dbc;
        outline: none;
    }
    .btn-mb-search {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        height: 30px;
        padding: 0 10px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-mb-search:hover {
        background-color: #d68407;
    }
    .btn-mb-reset {
        background-color: #ffffff;
        border: 1px solid #ccc;
        color: #444 !important;
        height: 30px;
        padding: 0 10px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-mb-reset:hover {
        background-color: #f4f4f4;
    }
    .btn-mb-create {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #ffffff !important;
        height: 30px;
        padding: 0 14px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-mb-create:hover {
        background-color: #008d4c;
    }
    .table-konhakes {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        border: 1px solid #e1e1e1;
    }
    .table-konhakes thead th {
        background-color: #ffffff;
        color: #337ab7;
        font-weight: 600;
        padding: 8px 10px;
        border-bottom: 1px solid #e1e1e1;
        border-right: 1px solid #f0f0f0;
        white-space: nowrap;
    }
    .table-konhakes tbody td {
        padding: 7px 10px;
        border-bottom: 1px solid #f4f4f4;
        border-right: 1px solid #f9f9f9;
        vertical-align: middle;
        font-size: 12.5px;
    }
    .table-konhakes tbody tr:hover {
        background-color: #fcfcfc;
    }
    .badge-belum-terima {
        background-color: #f39c12;
        color: #fff;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 2px;
    }
    .badge-sudah-terima {
        background-color: #00a65a;
        color: #fff;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 2px;
    }
</style>

<div class="mb-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h1 class="konhakes-title">Mutasi Barang Antar Gudang</h1>
        <div class="konhakes-breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> &gt; <span>Mutasi Barang Antar Gudang</span>
        </div>
    </div>

    {{-- Cyan divider --}}
    <div class="cyan-divider"></div>

    {{-- Main Container Card --}}
    <div class="card-mb-box">
        {{-- Filter Bar 1:1 in one row --}}
        <form action="{{ url('/mutasibarang/index') }}" method="GET" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="filter-label">No Mutasi</label>
                    <input type="text" name="no_mutasi" class="filter-ctrl" value="{{ request('no_mutasi') }}">
                </div>
                <div class="col-md-1" style="min-width: 120px;">
                    <label class="filter-label">Tgl Awal</label>
                    <input type="date" name="tgl_awal" class="filter-ctrl" value="{{ request('tgl_awal') }}">
                </div>
                <div class="col-md-1" style="min-width: 120px;">
                    <label class="filter-label">Tgl Akhir</label>
                    <input type="date" name="tgl_akhir" class="filter-ctrl" value="{{ request('tgl_akhir') }}">
                </div>
                <div class="col-md-2">
                    <label class="filter-label">Gudang Asal</label>
                    <select name="gudang_asal_id" class="filter-ctrl">
                        <option value="">--Pilih--</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ request('gudang_asal_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="filter-label">Gudang Tujuan</label>
                    <select name="gudang_tujuan_id" class="filter-ctrl">
                        <option value="">--Pilih--</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}" {{ request('gudang_tujuan_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1" style="min-width: 110px;">
                    <label class="filter-label">Sudah Diterima?</label>
                    <select name="sudah_diterima" class="filter-ctrl">
                        <option value="Semua">--Semua--</option>
                        <option value="Belum Terima" {{ request('sudah_diterima') == 'Belum Terima' ? 'selected' : '' }}>Belum Terima</option>
                        <option value="Sudah Terima" {{ request('sudah_diterima') == 'Sudah Terima' ? 'selected' : '' }}>Sudah Terima</option>
                    </select>
                </div>
                <div class="col-md-auto d-flex gap-1 align-items-center">
                    <button type="submit" class="btn-mb-search" title="Cari Data">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="{{ url('/mutasibarang/index') }}" class="btn-mb-reset" title="Segarkan Filter">
                        <i class="fa fa-sync-alt"></i>
                    </a>
                    <a href="{{ url('/mutasibarang/create') }}" class="btn-mb-create ms-1">
                        <i class="fa fa-plus"></i> Create
                    </a>
                </div>
            </div>
        </form>

        {{-- Item Count Info --}}
        <div class="mb-2" style="font-size: 12px; color: #555;">
            Menampilkan <strong>{{ $data->firstItem() ?? 0 }}-{{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> item.
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-konhakes">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th style="width: 160px;">No Mutasi</th>
                        <th style="width: 120px;">Tgl Mutasi</th>
                        <th>Gudang Asal</th>
                        <th>Gudang Tujuan</th>
                        <th style="width: 120px; text-align: center;">Sudah Diterima?</th>
                        <th style="width: 90px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                        @php
                            $gudangAsalNama = $row->gudangAsal ? $row->gudangAsal->nama_gudang : 'GUDANG AWAL';
                            $gudangTujuanNama = $row->gudangTujuan ? $row->gudangTujuan->nama_gudang : ($row->unit_tujuan ?: 'Gudang Farmasi');
                            $isReceived = ($row->sudah_diterima === 'Sudah Terima' || $row->status === 'Diterima');
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $data->firstItem() + $index }}</td>
                            <td class="fw-bold">
                                <a href="javascript:void(0)" onclick="lihatDetailMutasi({{ $row->id }})" style="text-decoration: none; color: #007bff; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa fa-arrow-circle-right text-info" style="font-size: 11px;"></i> {{ $row->nomor_mutasi }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_mutasi)->format('d.M.Y') }}</td>
                            <td>{{ $gudangAsalNama }}</td>
                            <td>{{ $gudangTujuanNama }}</td>
                            <td style="text-align: center;">
                                @if($isReceived)
                                    <span class="badge-sudah-terima">Sudah Terima</span>
                                @else
                                    <span class="badge-belum-terima">Belum Terima</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-info btn-sm py-0 px-2" onclick="lihatDetailMutasi({{ $row->id }})" title="Lihat Rincian Item">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <a href="{{ url('/mutasibarang/' . $row->id . '/cetak-pdf') }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" title="Cetak Surat Jalan Mutasi">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    @if(!$isReceived)
                                        <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" onclick="hapusMutasi({{ $row->id }}, '{{ $row->nomor_mutasi }}')" title="Batalkan Mutasi">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: left; padding: 12px 15px; color: #555;">
                                Tidak ada data yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($data->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Halaman {{ $data->currentPage() }} dari {{ $data->lastPage() }}
                </div>
                <div>
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL DETAIL MUTASI BARANG --}}
<div class="modal fade" id="modalDetailMutasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background-color: #00c0ef; color: white; padding: 10px 15px;">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa fa-dolly-flatbed me-1"></i> Rincian Mutasi Barang (<span id="detModalNoMutasi"></span>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2 mb-3 bg-light p-2 rounded border" style="font-size: 12px;">
                    <div class="col-md-3">
                        <span class="text-muted d-block">Tanggal Mutasi:</span>
                        <strong id="detModalTanggal">-</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block">Gudang Asal:</span>
                        <strong id="detModalGudangAsal">-</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block">Gudang Tujuan:</span>
                        <strong id="detModalGudangTujuan">-</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block">Status Penerimaan:</span>
                        <span id="detModalStatus" class="badge-belum-terima">-</span>
                    </div>
                    <div class="col-md-6 mt-2">
                        <span class="text-muted d-block">Petugas Pengirim:</span>
                        <strong id="detModalPetugas">-</strong>
                    </div>
                    <div class="col-md-6 mt-2">
                        <span class="text-muted d-block">Penerima & Tgl Terima:</span>
                        <strong id="detModalPenerima">-</strong>
                    </div>
                </div>

                <h6 class="fw-bold mb-2" style="font-size: 13px;">Daftar Item Obat / Barang Dimutasi:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead style="background-color: #a6a6a6; color: white;">
                            <tr>
                                <th style="width: 35px; text-align: center;">No</th>
                                <th style="width: 120px;">Kode Barang</th>
                                <th>Nama Barang</th>
                                <th style="width: 90px; text-align: center;">Kemasan</th>
                                <th style="width: 150px;">Batch & Kadaluarsa</th>
                                <th style="width: 90px; text-align: right;">Jumlah Keluar</th>
                            </tr>
                        </thead>
                        <tbody id="detModalTableItems">
                            <!-- Populated by JS -->
                        </tbody>
                        <tfoot style="background-color: #f9f9f9; font-weight: bold;">
                            <tr>
                                <td colspan="5" class="text-end">Total Item Keluar:</td>
                                <td class="text-end" id="detModalTotalKeluar">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 px-3">
                <a id="detModalPrintBtn" href="#" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fa fa-print me-1"></i> Cetak Dokumen Mutasi
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function formatDate(dStr) {
        if (!dStr) return '-';
        var d = new Date(dStr);
        if (isNaN(d.getTime())) return dStr;
        var day = String(d.getDate()).padStart(2, '0');
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return day + '.' + months[d.getMonth()] + '.' + d.getFullYear();
    }

    function lihatDetailMutasi(id) {
        $.ajax({
            url: '/mutasibarang/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.data) {
                    var m = res.data;
                    $('#detModalNoMutasi').text(m.nomor_mutasi);
                    $('#detModalTanggal').text(formatDate(m.tanggal_mutasi));
                    $('#detModalGudangAsal').text(m.gudang_asal ? m.gudang_asal.nama_gudang : 'GUDANG AWAL');
                    $('#detModalGudangTujuan').text(m.gudang_tujuan ? m.gudang_tujuan.nama_gudang : (m.unit_tujuan || 'Gudang Farmasi'));
                    $('#detModalPetugas').text(m.petugas || 'dr. FAHMI RIZAL');

                    var isRec = (m.sudah_diterima === 'Sudah Terima' || m.status === 'Diterima');
                    if (isRec) {
                        $('#detModalStatus').text('Sudah Terima').attr('class', 'badge-sudah-terima');
                        $('#detModalPenerima').text((m.penerima || '-') + ' (' + formatDate(m.tanggal_terima) + ')');
                    } else {
                        $('#detModalStatus').text('Belum Terima').attr('class', 'badge-belum-terima');
                        $('#detModalPenerima').text('Belum Diterima');
                    }

                    var rows = '';
                    var totalQty = 0;
                    if (m.details && m.details.length > 0) {
                        m.details.forEach(function(d, i) {
                            totalQty += parseFloat(d.jumlah) || 0;
                            var batchInfo = d.nomor_batch ? ('Batch: ' + d.nomor_batch) : '-';
                            if (d.tanggal_kadaluarsa) batchInfo += ' | Exp: ' + formatDate(d.tanggal_kadaluarsa);

                            rows += '<tr>' +
                                '<td class="text-center">' + (i + 1) + '</td>' +
                                '<td>' + (d.master_barang ? (d.master_barang.kode_barang || '-') : '-') + '</td>' +
                                '<td><strong>' + (d.master_barang ? d.master_barang.nama_barang : ('Item #' + d.master_barang_id)) + '</strong></td>' +
                                '<td class="text-center">' + (d.kemasan || '-') + '</td>' +
                                '<td><small>' + batchInfo + '</small></td>' +
                                '<td class="text-end fw-bold">' + Number(d.jumlah).toLocaleString('id-ID') + '</td>' +
                            '</tr>';
                        });
                    } else {
                        rows = '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada rincian item.</td></tr>';
                    }

                    $('#detModalTableItems').html(rows);
                    $('#detModalTotalKeluar').text(Number(totalQty).toLocaleString('id-ID'));
                    $('#detModalPrintBtn').attr('href', '/mutasibarang/' + m.id + '/cetak-pdf');
                    $('#modalDetailMutasi').modal('show');
                }
            },
            error: function() {
                alert('Gagal mengambil data rincian mutasi barang.');
            }
        });
    }

    function hapusMutasi(id, noMutasi) {
        if (!confirm('Apakah Anda yakin ingin membatalkan Mutasi Barang (' + noMutasi + ')? Stok gudang asal akan dikembalikan.')) {
            return;
        }

        $.ajax({
            url: '/mutasibarang/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Gagal: ' + res.message);
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                alert('Gagal: ' + msg);
            }
        });
    }
</script>
@endsection
