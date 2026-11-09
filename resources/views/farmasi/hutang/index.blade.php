@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* PROFASKES 1:1 DATA PEMBAYARAN HUTANG */
    .ph-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .profaskes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .profaskes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #444;
        text-decoration: none;
    }
    .profaskes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .card-ph-box {
        background-color: #fff;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px 20px;
        margin-top: 10px;
    }
    .prof-card-subtitle {
        font-size: 14px;
        font-weight: 600;
        color: #444;
        margin-bottom: 12px;
    }
    .filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: #555;
        margin-bottom: 3px;
        display: block;
    }
    .filter-input {
        height: 32px;
        font-size: 12.5px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 4px 8px;
        width: 100%;
        color: #333;
        background-color: #fff;
    }
    .filter-input:focus {
        border-color: #3c8dbc;
        outline: none;
        box-shadow: none;
    }
    .btn-ph-search {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
        color: #ffffff !important;
        height: 32px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-ph-search:hover {
        background-color: #286090;
    }
    .btn-ph-reset {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        height: 32px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-ph-reset:hover {
        background-color: #d68407;
    }
    .btn-ph-excel {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #ffffff !important;
        height: 32px;
        padding: 0 12px;
        border-radius: 2px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        text-decoration: none;
    }
    .btn-ph-excel:hover {
        background-color: #00a7d0;
        color: #ffffff !important;
    }
    .cyan-divider {
        height: 3px;
        background-color: #00c0ef;
        margin-top: 15px;
        margin-bottom: 15px;
    }
    .table-profaskes {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        border: 1px solid #e1e1e1;
    }
    .table-profaskes thead th {
        background-color: #ffffff;
        color: #337ab7;
        font-weight: 600;
        padding: 8px 10px;
        border-bottom: 1px solid #e1e1e1;
        border-right: 1px solid #f0f0f0;
        white-space: nowrap;
    }
    .table-profaskes tbody td {
        padding: 7px 10px;
        border-bottom: 1px solid #f4f4f4;
        border-right: 1px solid #f9f9f9;
        vertical-align: middle;
        font-size: 12.5px;
    }
    .table-profaskes tbody tr:hover {
        background-color: #fcfcfc;
    }
</style>

<div class="ph-page">
    {{-- Header & Breadcrumbs --}}
    <div class="profaskes-header">
        <h1 class="profaskes-title">Data Pembayaran Hutang</h1>
        <div class="profaskes-breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> &gt; <span>Data Pembayaran Hutang</span>
        </div>
    </div>

    {{-- Main Container Card --}}
    <div class="card-ph-box">
        <div class="prof-card-subtitle">Pencarian Data</div>

        {{-- Filter Form 1:1 --}}
        <form action="{{ url('/pembayaran-hutang/index') }}" method="GET">
            <div class="row g-2 align-items-end mb-2">
                <div class="col-md-2 filter-group">
                    <label>No Tagihan</label>
                    <input type="text" name="no_tagihan" class="filter-input" placeholder="" value="{{ request('no_tagihan') }}">
                </div>
                <div class="col-md-2 filter-group">
                    <label>Tgl Tagihan</label>
                    <input type="date" name="tgl_tagihan" class="filter-input" value="{{ request('tgl_tagihan', date('Y-01-01')) }}">
                </div>
                <div class="col-md-2 filter-group">
                    <label>s.d Tanggal</label>
                    <input type="date" name="sd_tanggal" class="filter-input" value="{{ request('sd_tanggal', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2 filter-group">
                    <label>Vendor</label>
                    <select name="vendor_id" class="filter-input">
                        <option value="">Vendor...</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 filter-group">
                    <label>Status Bayar</label>
                    <select name="status_bayar" class="filter-input">
                        <option value="">--Status Bayar--</option>
                        <option value="Belum Lunas" {{ request('status_bayar') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="Lunas" {{ request('status_bayar') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2 filter-group">
                    <label>Cara Bayar</label>
                    <select name="cara_bayar" class="filter-input">
                        <option value="">--Metode Pembayaran--</option>
                        <option value="Transfer Bank" {{ request('cara_bayar') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Tunai/Cash" {{ request('cara_bayar') == 'Tunai/Cash' ? 'selected' : '' }}>Tunai/Cash</option>
                        <option value="Giro/Cek" {{ request('cara_bayar') == 'Giro/Cek' ? 'selected' : '' }}>Giro/Cek</option>
                    </select>
                </div>
            </div>

            <div class="row g-2 align-items-center">
                <div class="col-md-2 filter-group">
                    <label>Periode Jatuh Tempo</label>
                    <select name="periode_jatuh_tempo" class="filter-input">
                        <option value="">--Semua--</option>
                        <option value="lewat" {{ request('periode_jatuh_tempo') == 'lewat' ? 'selected' : '' }}>Sudah Jatuh Tempo</option>
                        <option value="hari_ini" {{ request('periode_jatuh_tempo') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="7_hari" {{ request('periode_jatuh_tempo') == '7_hari' ? 'selected' : '' }}>7 Hari Kedepan</option>
                        <option value="30_hari" {{ request('periode_jatuh_tempo') == '30_hari' ? 'selected' : '' }}>30 Hari Kedepan</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-center gap-1 mt-auto">
                    <button type="submit" class="btn-ph-search" title="Cari Data">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="{{ url('/pembayaran-hutang/index') }}" class="btn-ph-reset" title="Segarkan Filter">
                        <i class="fa fa-sync-alt"></i>
                    </a>
                    <a href="{{ url('/pembayaran-hutang/export-excel' . (request()->getQueryString() ? '?' . request()->getQueryString() : '')) }}" class="btn-ph-excel ms-1">
                        <i class="fa fa-file-excel"></i> Cetak Excel
                    </a>
                </div>
            </div>
        </form>

        {{-- Cyan Divider --}}
        <div class="cyan-divider"></div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-profaskes">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th style="width: 140px;">No Tagihan</th>
                        <th style="width: 110px;">Tagihan Dibuat <span style="font-size: 10px; color: #999;">⇅</span></th>
                        <th style="width: 110px;">Jatuh Tempo <span style="font-size: 10px; color: #999;">⇅</span></th>
                        <th>Vendor</th>
                        <th style="width: 120px; text-align: right;">Total Tagihan</th>
                        <th style="width: 120px; text-align: right;">Total Pembayaran</th>
                        <th style="width: 120px; text-align: right;">Sisa Pembayaran</th>
                        <th style="width: 120px; text-align: center;">Pembayaran</th>
                        <th style="width: 120px; text-align: center;">Status Pembayaran <span style="font-size: 10px; color: #999;">⇅</span></th>
                        <th style="width: 80px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                        @php
                            $tglDibuat = $row->masukBarang ? \Carbon\Carbon::parse($row->masukBarang->tanggal_terima)->format('d-m-Y') : \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                            $tglJatuhTempo = $row->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($row->tanggal_jatuh_tempo)->format('d-m-Y') : '-';
                            $isOverdue = ($row->status !== 'Lunas' && $row->tanggal_jatuh_tempo && \Carbon\Carbon::parse($row->tanggal_jatuh_tempo)->isPast());
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $data->firstItem() + $index }}</td>
                            <td class="fw-bold" style="color: #007bff;">
                                <a href="javascript:void(0)" onclick="lihatDetailHutang({{ $row->id }})" style="text-decoration: none; color: inherit;">
                                    {{ $row->no_tagihan_display }}
                                </a>
                                @if($row->masukBarang && $row->masukBarang->nomor_penerimaan !== $row->no_tagihan_display)
                                    <div style="font-size: 10.5px; color: #777;">({{ $row->masukBarang->nomor_penerimaan }})</div>
                                @endif
                            </td>
                            <td>{{ $tglDibuat }}</td>
                            <td class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                {{ $tglJatuhTempo }}
                                @if($isOverdue)
                                    <small class="d-block text-danger" style="font-size: 10px;">(Lewat Tempo)</small>
                                @endif
                            </td>
                            <td>{{ $row->masterVendor ? $row->masterVendor->nama_vendor : '-' }}</td>
                            <td style="text-align: right; font-weight: 600;">
                                Rp {{ number_format($row->jumlah_hutang, 0, ',', '.') }}
                            </td>
                            <td style="text-align: right;">
                                Rp {{ number_format($row->jumlah_dibayar, 0, ',', '.') }}
                            </td>
                            <td style="text-align: right; font-weight: 600; color: {{ $row->sisa_hutang > 0 ? '#d9534f' : '#28a745' }};">
                                Rp {{ number_format($row->sisa_hutang, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                @if($row->status !== 'Lunas')
                                    <button type="button" class="btn btn-sm btn-success py-0 px-2" style="font-size: 11.5px;" onclick="bukaModalBayar({{ $row->id }}, '{{ $row->no_tagihan_display }}', {{ $row->sisa_hutang }})">
                                        <i class="fa fa-money-bill me-1"></i> Bayar
                                    </button>
                                @else
                                    <span class="text-muted" style="font-size: 11px;">
                                        {{ $row->cara_bayar ?: ($row->pembayarans->first() ? $row->pembayarans->first()->metode_pembayaran : 'Lunas') }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($row->status === 'Lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-danger">Belum Lunas</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-info btn-sm py-0 px-2" onclick="lihatDetailHutang({{ $row->id }})" title="Riwayat Pembayaran">
                                        <i class="fa fa-history"></i>
                                    </button>
                                    @if($row->pembayarans->count() > 0)
                                        <a href="{{ url('/pembayaran-hutang/' . $row->id . '/cetak-kuitansi') }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" title="Cetak Kuitansi Terakhir">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align: left; padding: 12px 15px; color: #555;">
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
                    Menampilkan {{ $data->firstItem() }} s/d {{ $data->lastItem() }} dari {{ $data->total() }} data
                </div>
                <div>
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL BAYAR HUTANG --}}
<div class="modal fade" id="modalBayarHutang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background-color: #f39c12; color: #ffffff; padding: 10px 15px;">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa fa-money-bill-wave me-1"></i> Form Pembayaran Hutang Tagihan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formProsesBayar" onsubmit="submitProsesBayar(event)">
                @csrf
                <input type="hidden" id="bayar_hutang_id" name="id">
                <div class="modal-body p-3">
                    <div class="text-center p-3 bg-light rounded border mb-3">
                        <small class="text-muted d-block">Nomor Tagihan / Faktur</small>
                        <h5 class="fw-bold text-primary mb-1" id="bayar_modal_notagihan">-</h5>
                        <div class="mt-2 text-muted" style="font-size: 12px;">Sisa Hutang:</div>
                        <h4 class="fw-bold text-danger mb-0" id="bayar_modal_sisahutang">Rp 0</h4>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 12px;">Nominal Bayar (Rp) <span class="text-danger">*</span></label>
                        <input type="number" step="any" class="form-control form-control-sm text-end fw-bold" id="bayar_nominal" name="nominal_bayar" required min="1">
                        <small class="text-muted" style="font-size: 11px;">Maksimal pembayaran adalah sisa hutang.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 12px;">Tanggal Pembayaran <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="bayar_tanggal" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 12px;">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="bayar_metode" name="metode_pembayaran" required>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai/Cash">Tunai/Cash</option>
                            <option value="Giro/Cek">Giro/Cek</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 12px;">Nomor Bukti Transfer / Kuitansi</label>
                        <input type="text" class="form-control form-control-sm" id="bayar_nomor_bukti" name="nomor_bukti" placeholder="Opsional (otomatis terisi jika kosong)">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size: 12px;">Catatan / Keterangan</label>
                        <textarea class="form-control form-control-sm" id="bayar_keterangan" name="keterangan" rows="2" placeholder="Catatan pembayaran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm px-3" id="btnSubmitBayar">
                        <i class="fa fa-check me-1"></i> Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL RIWAYAT / DETAIL HUTANG --}}
<div class="modal fade" id="modalDetailHutang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background-color: #337ab7; color: white; padding: 10px 15px;">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa fa-info-circle me-1"></i> Rincian & Riwayat Pembayaran Tagihan (<span id="detModalNoTagihan"></span>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2 mb-3 bg-light p-2 rounded border" style="font-size: 12px;">
                    <div class="col-md-3">
                        <span class="text-muted d-block">Vendor / Supplier:</span>
                        <strong id="detModalVendor">-</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block">Jatuh Tempo:</span>
                        <strong id="detModalJatuhTempo">-</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block">Total Tagihan:</span>
                        <strong class="text-primary" id="detModalTotalHutang">-</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block">Sisa Hutang:</span>
                        <strong class="text-danger" id="detModalSisaHutang">-</strong>
                    </div>
                </div>

                <h6 class="fw-bold mb-2" style="font-size: 13px;">Riwayat Cicilan / Pembayaran:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead style="background-color: #a6a6a6; color: white;">
                            <tr>
                                <th style="width: 35px; text-align: center;">No</th>
                                <th>No. Bukti</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode Bayar</th>
                                <th style="text-align: right;">Nominal (Rp)</th>
                                <th>Keterangan</th>
                                <th style="width: 60px; text-align: center;">Kuitansi</th>
                            </tr>
                        </thead>
                        <tbody id="detModalTablePembayaran">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 px-3">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    }

    function formatDate(dStr) {
        if (!dStr) return '-';
        var d = new Date(dStr);
        if (isNaN(d.getTime())) return dStr;
        var day = String(d.getDate()).padStart(2, '0');
        var mon = String(d.getMonth() + 1).padStart(2, '0');
        var yr = d.getFullYear();
        return day + '-' + mon + '-' + yr;
    }

    var currentMaxBayar = 0;

    function bukaModalBayar(id, noTagihan, sisaHutang) {
        currentMaxBayar = parseFloat(sisaHutang) || 0;
        $('#bayar_hutang_id').val(id);
        $('#bayar_modal_notagihan').text(noTagihan);
        $('#bayar_modal_sisahutang').text(formatRupiah(currentMaxBayar));
        $('#bayar_nominal').val(currentMaxBayar).attr('max', currentMaxBayar);
        $('#modalBayarHutang').modal('show');
    }

    function submitProsesBayar(e) {
        e.preventDefault();
        var id = $('#bayar_hutang_id').val();
        var nominal = parseFloat($('#bayar_nominal').val()) || 0;

        if (nominal <= 0) {
            alert('Nominal pembayaran harus lebih dari 0.');
            $('#bayar_nominal').focus();
            return;
        }

        if (nominal > currentMaxBayar) {
            alert('Nominal pembayaran tidak boleh melebihi sisa hutang (' + formatRupiah(currentMaxBayar) + ').');
            $('#bayar_nominal').focus();
            return;
        }

        var btn = $('#btnSubmitBayar');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Memproses...');

        $.ajax({
            url: '/pembayaran-hutang/' + id + '/bayar',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nominal_bayar: nominal,
                tanggal_bayar: $('#bayar_tanggal').val(),
                metode_pembayaran: $('#bayar_metode').val(),
                nomor_bukti: $('#bayar_nomor_bukti').val(),
                keterangan: $('#bayar_keterangan').val()
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    $('#modalBayarHutang').modal('hide');
                    if (confirm('Apakah Anda ingin mencetak Kuitansi Pembayaran sekarang?')) {
                        window.open('/pembayaran-hutang/' + id + '/cetak-kuitansi?pembayaran_id=' + res.pembayaran_id, '_blank');
                    }
                    location.reload();
                } else {
                    alert('Gagal: ' + res.message);
                    btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Simpan Pembayaran');
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                alert('Gagal: ' + msg);
                btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Simpan Pembayaran');
            }
        });
    }

    function lihatDetailHutang(id) {
        $.ajax({
            url: '/pembayaran-hutang/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.hutang) {
                    var h = res.hutang;
                    var noTagihan = h.no_tagihan || (h.masuk_barang ? (h.masuk_barang.nomor_faktur || h.masuk_barang.nomor_penerimaan) : ('TAG-' + h.id));
                    $('#detModalNoTagihan').text(noTagihan);
                    $('#detModalVendor').text(h.master_vendor ? h.master_vendor.nama_vendor : '-');
                    $('#detModalJatuhTempo').text(formatDate(h.tanggal_jatuh_tempo));
                    $('#detModalTotalHutang').text(formatRupiah(h.jumlah_hutang));
                    $('#detModalSisaHutang').text(formatRupiah(h.sisa_hutang));

                    var rows = '';
                    if (h.pembayarans && h.pembayarans.length > 0) {
                        h.pembayarans.forEach(function(p, i) {
                            rows += '<tr>' +
                                '<td class="text-center">' + (i + 1) + '</td>' +
                                '<td><strong>' + (p.nomor_bukti || '-') + '</strong></td>' +
                                '<td>' + formatDate(p.tanggal_bayar) + '</td>' +
                                '<td>' + p.metode_pembayaran + '</td>' +
                                '<td class="text-end fw-bold text-success">' + formatRupiah(p.nominal_bayar) + '</td>' +
                                '<td>' + (p.keterangan || '-') + '</td>' +
                                '<td class="text-center">' +
                                    '<a href="/pembayaran-hutang/' + h.id + '/cetak-kuitansi?pembayaran_id=' + p.id + '" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" title="Cetak Kuitansi">' +
                                        '<i class="fa fa-print"></i>' +
                                    '</a>' +
                                '</td>' +
                            '</tr>';
                        });
                    } else {
                        rows = '<tr><td colspan="7" class="text-center text-muted py-3">Belum ada riwayat pembayaran yang tercatat.</td></tr>';
                    }
                    $('#detModalTablePembayaran').html(rows);
                    $('#modalDetailHutang').modal('show');
                }
            },
            error: function() {
                alert('Gagal mengambil data detail hutang.');
            }
        });
    }
</script>
@endsection
