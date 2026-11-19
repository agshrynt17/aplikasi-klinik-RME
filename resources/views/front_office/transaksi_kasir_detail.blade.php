@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<!-- Header Area (Title and Breadcrumbs) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">Pembayaran Kasir</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/transaksi-kasir" class="text-decoration-none text-muted">Transaksi Kasir</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Pembayaran</a></li>
            </ol>
        </nav>
    </div>
    <a href="/transaksi-kasir" class="btn btn-outline-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div id="alertBox" class="alert d-none mb-3 border-0 shadow-sm" role="alert"></div>

@if(isset($split) && $split['is_bpjs'])
<!-- Banner Informasi Split-Bill BPJS Kesehatan -->
<div class="card border-0 shadow-sm mb-3 rounded-3" style="background: linear-gradient(135deg, #e8f5e9 0%, #d4edda 100%); border-left: 5px solid #28a745 !important;">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="badge bg-success px-2 py-1 mb-1"><i class="fas fa-shield-alt me-1"></i> Penjamin BPJS Kesehatan</span>
                <h6 class="fw-bold text-success mb-0">Sistem Split-Bill BPJS Aktif</h6>
                <small class="text-muted">Tindakan medis standar &amp; Obat Formularium Nasional (Fornas) ditanggung penuh BPJS (Rp 0 bagi pasien). Pasien hanya membayar iur untuk item Non-Formularium atau kenaikan kelas kamar.</small>
            </div>
            <div class="d-flex gap-2 text-end">
                <div class="bg-white p-2 px-3 rounded border text-center shadow-sm">
                    <small class="text-muted d-block fw-semibold" style="font-size: 10px;">TOTAL BRUTO</small>
                    <span class="fw-bold text-dark fs-6">Rp {{ number_format($split['total_bruto'], 0, ',', '.') }}</span>
                </div>
                <div class="bg-white p-2 px-3 rounded border text-center shadow-sm">
                    <small class="text-success d-block fw-semibold" style="font-size: 10px;">DITANGGUNG BPJS</small>
                    <span class="fw-bold text-success fs-6">Rp {{ number_format($split['total_dijamin'], 0, ',', '.') }}</span>
                </div>
                <div class="bg-white p-2 px-3 rounded border text-center shadow-sm">
                    <small class="text-danger d-block fw-semibold" style="font-size: 10px;">IUR MANDIRI PASIEN</small>
                    <span class="fw-bold text-danger fs-6">Rp {{ number_format($split['total_iur_pasien'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <!-- Rincian Tagihan -->
    <div class="col-md-7 mb-4">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 h-100">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Rincian Tagihan Pelayanan</h6>
            
            <!-- Info Pasien Ringkas -->
            <div class="card border-0 bg-light mb-4 rounded-3" style="border-left: 4px solid #00c0ef !important;">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-sm-6 border-end">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase">Pasien</small>
                            <div class="fw-bold fs-5 text-dark">{{ $pendaftaran->pasien->nama_pasien ?? '-' }}</div>
                            <div class="small">RM: <span class="badge bg-secondary">{{ $pendaftaran->pasien->no_rm ?? '-' }}</span> | No. Rawat: <span class="fw-semibold">{{ $pendaftaran->no_rawat }}</span></div>
                        </div>
                        <div class="col-sm-6 ps-sm-4 mt-3 mt-sm-0">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase">Penjamin / Tipe</small>
                            <div class="fw-bold text-primary fs-5">
                                {{ $pendaftaran->penjamin->nama_penjamin ?? 'UMUM' }}
                                @if(isset($split) && $split['is_bpjs'])
                                    <span class="badge bg-success ms-1 small" style="font-size: 11px;">BPJS Active</span>
                                @endif
                            </div>
                            <div class="small text-muted">{{ $pendaftaran->poliklinik->nama_poli ?? '-' }} - {{ $pendaftaran->dokter->nama_pegawai ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- A. Tindakan Medis -->
            <h6 class="fw-bold text-secondary mt-3"><i class="fas fa-stethoscope me-1 text-primary"></i> A. Tindakan Medis / Prosedur</h6>
            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover table-bordered border-light align-middle" style="border: 1px solid #dee2e6;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="text-dark py-2 ps-3">Nama Layanan</th>
                            <th class="text-center text-dark py-2" style="width: 60px;">Qty</th>
                            <th class="text-end text-dark py-2" style="width: 110px;">Subtotal</th>
                            <th class="text-center text-dark py-2 pe-3" style="width: 140px;">Status Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tindakans as $t)
                        <tr>
                            <td class="ps-3">{{ $t->tindakan->nama_tindakan ?? '-' }}</td>
                            <td class="text-center">{{ $t->qty }}</td>
                            <td class="text-end">Rp {{ number_format($t->subtotal, 0, ',', '.') }}</td>
                            <td class="text-center pe-3">
                                @if(isset($t->is_dijamin) && $t->is_dijamin)
                                    <span class="badge bg-success text-white small"><i class="fas fa-shield-alt me-1"></i> Ditanggung BPJS</span>
                                @else
                                    <span class="badge bg-secondary text-white small">Bayar Mandiri</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada tindakan medis.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background-color: #e9ecef;">
                        <tr>
                            <th colspan="2" class="text-end py-2 text-dark">Total Tindakan:</th>
                            <th class="text-end py-2 text-dark">Rp {{ number_format($total_tindakan, 0, ',', '.') }}</th>
                            <th class="text-center py-2 pe-3 small text-muted">
                                @if(isset($split) && $split['is_bpjs'])
                                    <span class="text-success fw-bold">Rp 0 Pasien</span>
                                @endif
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- B. Resep Obat -->
            <h6 class="fw-bold text-secondary mt-2"><i class="fas fa-pills me-1 text-success"></i> B. Resep Obat (Farmasi / Apotek)</h6>
            <div class="table-responsive mb-2">
                <table class="table table-sm table-hover table-bordered border-light align-middle" style="border: 1px solid #dee2e6;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="text-center text-dark py-2" style="width: 35px;">No</th>
                            <th class="text-dark py-2 ps-3">Nama Obat / BHP</th>
                            <th class="text-center text-dark py-2" style="width: 50px;">Qty</th>
                            <th class="text-end text-dark py-2" style="width: 100px;">Harga</th>
                            <th class="text-end text-dark py-2" style="width: 110px;">Subtotal</th>
                            <th class="text-center text-dark py-2 pe-3" style="width: 150px;">Status Formularium</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $noObat = 1;
                            $adaObat = false;
                        @endphp
                        @if(isset($reseps) && $reseps->count() > 0)
                            @foreach($reseps as $resep)
                                @if($resep->details && $resep->details->count() > 0)
                                    @php $adaObat = true; @endphp
                                    @foreach($resep->details as $det)
                                        <tr>
                                            <td class="text-center text-muted small">{{ $noObat++ }}</td>
                                            <td class="ps-3">
                                                <div class="fw-bold text-dark">{{ $det->masterBarang->nama_barang ?? 'Obat' }}</div>
                                                @if($det->aturan_pakai)
                                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>{{ $det->aturan_pakai }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $det->jumlah }}</td>
                                            <td class="text-end">Rp {{ number_format($det->harga_satuan, 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold text-dark">Rp {{ number_format($det->subtotal, 0, ',', '.') }}</td>
                                            <td class="text-center pe-3">
                                                @if(isset($split) && $split['is_bpjs'])
                                                    @if(isset($det->is_fornas) && $det->is_fornas)
                                                        <span class="badge bg-success small"><i class="fas fa-check-circle me-1"></i> Fornas (BPJS)</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark small fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> Non-Fornas (Iur)</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary small">Umum</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @elseif($resep->total_harga > 0)
                                    @php $adaObat = true; @endphp
                                    <tr>
                                        <td class="text-center text-muted small">{{ $noObat++ }}</td>
                                        <td class="ps-3 fw-bold text-dark">Resep Obat ({{ $resep->no_resep }})</td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">Rp {{ number_format($resep->total_harga, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold text-dark">Rp {{ number_format($resep->total_harga, 0, ',', '.') }}</td>
                                        <td class="text-center pe-3">
                                            @if(isset($split) && $split['is_bpjs'])
                                                <span class="badge bg-success small">Fornas (BPJS)</span>
                                            @else
                                                <span class="badge bg-secondary small">Umum</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif

                        @if(!$adaObat)
                            <tr><td colspan="6" class="text-center text-muted py-3"><i class="fas fa-check-circle text-success me-1"></i> Tidak ada resep obat / belum ada transaksi obat.</td></tr>
                        @endif
                    </tbody>
                    <tfoot style="background-color: #e9ecef;">
                        <tr>
                            <th colspan="4" class="text-end py-2 text-dark">Total Obat:</th>
                            <th class="text-end py-2 text-dark">Rp {{ number_format($total_obat, 0, ',', '.') }}</th>
                            <th class="text-center py-2 pe-3 small">
                                @if(isset($split) && $split['is_bpjs'])
                                    @if($split['total_obat_iur'] > 0)
                                        <span class="text-danger fw-bold">Iur: Rp {{ number_format($split['total_obat_iur'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-success fw-bold">Rp 0 Pasien</span>
                                    @endif
                                @endif
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- C. Akomodasi Rawat Inap -->
            @if(isset($inapTransfers) && $inapTransfers->count() > 0)
            <h6 class="fw-bold text-secondary mt-3"><i class="fas fa-bed me-1 text-primary"></i> C. Akomodasi Kamar Rawat Inap</h6>
            <div class="table-responsive mb-2">
                <table class="table table-sm table-hover table-bordered border-light align-middle" style="border: 1px solid #dee2e6;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="text-dark py-2 ps-3">Kamar &amp; Bed</th>
                            <th class="text-center text-dark py-2">Kelas</th>
                            <th class="text-center text-dark py-2">Lama Inap</th>
                            <th class="text-end text-dark py-2">Tarif / Hari</th>
                            <th class="text-end text-dark py-2">Subtotal</th>
                            <th class="text-center text-dark py-2 pe-3">Penjaminan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inapTransfers as $it)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">{{ $it->kamarRanap->nama_kamar ?? 'Kamar' }} ({{ $it->kamarRanap->nomor_bed ?? '' }})</td>
                                <td class="text-center"><span class="badge bg-secondary">{{ $it->kamarRanap->kelas ?? '-' }}</span></td>
                                <td class="text-center fw-semibold">{{ $it->lama_hari }} Hari</td>
                                <td class="text-end">Rp {{ number_format($it->tarif_per_hari, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-primary">Rp {{ number_format($it->total_biaya_kamar, 0, ',', '.') }}</td>
                                <td class="text-center pe-3">
                                    @if(isset($split) && $split['is_bpjs'])
                                        @if(isset($it->nominal_iur) && $it->nominal_iur > 0)
                                            <span class="badge bg-warning text-dark small">Iur Selisih Rp {{ number_format($it->nominal_iur, 0, ',', '.') }}</span>
                                        @else
                                            <span class="badge bg-success small"><i class="fas fa-shield-alt me-1"></i> Ditanggung BPJS</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary small">Umum</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background-color: #e9ecef;">
                        <tr>
                            <th colspan="4" class="text-end py-2 text-dark">Total Biaya Kamar:</th>
                            <th class="text-end py-2 text-dark">Rp {{ number_format($total_kamar, 0, ',', '.') }}</th>
                            <th class="text-center py-2 pe-3 small">
                                @if(isset($split) && $split['is_bpjs'])
                                    @if($split['total_kamar_iur'] > 0)
                                        <span class="text-danger fw-bold">Iur: Rp {{ number_format($split['total_kamar_iur'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-success fw-bold">Rp 0 Pasien</span>
                                    @endif
                                @endif
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
    </div>

    <!-- Form Pembayaran / Status Kasir -->
    <div class="col-md-5 mb-4">
        <div class="card-custom p-4 shadow-sm border-0 rounded-3 h-100" style="background-color: #f8f9fa;">
            @if($kasir && $kasir->status_pembayaran == 'Lunas')
                <!-- Tampilan Jika Sudah Lunas -->
                <div class="text-center mb-4 mt-2">
                    <div class="d-inline-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle mb-3" style="width: 90px; height: 90px;">
                        <i class="fas fa-check text-success fa-3x"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-1">LUNAS</h3>
                    <p class="text-muted mb-0 fs-6">Kwitansi: <strong>{{ $kasir->no_kwitansi }}</strong></p>
                    <div class="text-muted small mt-2">
                        <div>Waktu: {{ \Carbon\Carbon::parse($kasir->waktu_bayar)->format('d-m-Y H:i') }}</div>
                        <div>Metode: <span class="badge bg-secondary">{{ $kasir->cara_bayar ?? ($kasir->akunPembayaran->nama_akun ?? 'CASH') }}</span></div>
                        <div>Kasir: {{ $kasir->kasir->name ?? 'Admin' }}</div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4 rounded-3">
                    <ul class="list-group list-group-flush rounded-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted">Total Bruto Pelayanan</span>
                            <span class="fw-bold">Rp {{ number_format($kasir->sub_total ?? ($kasir->total_tagihan + $kasir->diskon), 0, ',', '.') }}</span>
                        </li>
                        @if(($kasir->dijamin ?? 0) > 0)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-success bg-opacity-10 text-success">
                            <span><i class="fas fa-shield-alt me-1"></i> Ditanggung BPJS</span>
                            <span class="fw-bold">- Rp {{ number_format($kasir->dijamin, 0, ',', '.') }}</span>
                        </li>
                        @endif
                        @if($kasir->diskon > 0)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted">Diskon Tambahan</span>
                            <span class="fw-bold text-danger">- Rp {{ number_format($kasir->diskon, 0, ',', '.') }}</span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-light">
                            <span class="fw-bold text-dark fs-5">Dibayar Pasien</span>
                            <span class="fw-bold text-primary fs-5">Rp {{ number_format($kasir->total_tagihan, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted">Uang Diterima</span>
                            <span class="fw-bold">Rp {{ number_format($kasir->uang_diterima, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted">Kembalian</span>
                            <span class="fw-bold text-success">Rp {{ number_format($kasir->kembalian, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('transaksi-kasir.cetak', $kasir->id) }}" target="_blank" class="btn btn-success shadow-sm py-2">
                        <i class="fas fa-print me-2"></i> Cetak Kwitansi Resmi
                    </a>
                    <button class="btn btn-outline-danger py-2 mt-1" id="btnBatalBayar"><i class="fas fa-times me-2"></i> Batalkan Pembayaran</button>
                </div>

            @else
                <!-- Form Input Pembayaran Jika Belum Lunas -->
                <h5 class="fw-bold mb-3 border-bottom pb-2 text-center text-dark"><i class="fas fa-cash-register me-2 text-primary"></i>Proses Pembayaran Kasir</h5>
                
                @php
                    $isBpjs = isset($split) && $split['is_bpjs'];
                    $iurPasien = $isBpjs ? $split['total_iur_pasien'] : $total_semua;
                @endphp

                @if($isBpjs && $iurPasien <= 0)
                    <div class="alert alert-success border-0 shadow-sm py-2 px-3 mb-3 small">
                        <i class="fas fa-check-circle me-1"></i> <strong>Pasien BPJS Bebas Biaya (Rp 0).</strong> Seluruh tindakan &amp; obat masuk tanggungan formularium BPJS Kesehatan.
                    </div>
                @elseif($isBpjs && $iurPasien > 0)
                    <div class="alert alert-warning border-0 shadow-sm py-2 px-3 mb-3 small">
                        <i class="fas fa-exclamation-triangle me-1"></i> <strong>Split-Bill:</strong> Pasien hanya dikenakan iur bayar mandiri sebesar <strong>Rp {{ number_format($iurPasien, 0, ',', '.') }}</strong> untuk item non-formularium / selisih kelas.
                    </div>
                @endif

                <form id="formKasir">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                    <input type="hidden" id="raw_total" value="{{ $iurPasien }}">
                    <input type="hidden" id="is_bpjs" value="{{ $isBpjs ? '1' : '0' }}">

                    <!-- Breakdown Ringkas -->
                    <div class="card border rounded-3 p-3 bg-white mb-3 shadow-sm">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Total Bruto Pelayanan:</span>
                            <span class="fw-semibold text-dark">Rp {{ number_format($total_semua, 0, ',', '.') }}</span>
                        </div>
                        @if($isBpjs)
                        <div class="d-flex justify-content-between small text-success mb-1">
                            <span><i class="fas fa-shield-alt me-1"></i> Ditanggung BPJS:</span>
                            <span class="fw-semibold">- Rp {{ number_format($split['total_dijamin'], 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between small text-dark fw-bold border-top pt-1 mt-1">
                            <span>Tagihan Dasar Pasien:</span>
                            <span class="text-danger">Rp {{ number_format($iurPasien, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label fw-bold small text-danger">Diskon (Rp)</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control text-end shadow-sm" id="diskon" name="diskon" value="0" min="0">
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label fw-bold fs-6 text-dark">TOTAL DIBAYAR</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control form-control-lg text-end fw-bold text-primary bg-white border-0 shadow" id="grand_total_display" value="Rp {{ number_format($iurPasien, 0, ',', '.') }}" readonly>
                        </div>
                    </div>

                    <hr class="my-3" style="border-color: #dee2e6;">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Metode / Akun Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select fw-semibold shadow-sm" id="akun_pembayaran_id" name="akun_pembayaran_id">
                            @if($isBpjs && $iurPasien <= 0)
                                @foreach($akun_pembayarans as $akun)
                                    <option value="{{ $akun->id }}" {{ stripos($akun->nama_akun, 'BPJS') !== false ? 'selected' : '' }}>
                                        {{ $akun->nama_akun }} ({{ $akun->jenis_akun }})
                                    </option>
                                @endforeach
                            @else
                                <option value="">-- Pilih Metode / Rekening --</option>
                                @foreach($akun_pembayarans as $akun)
                                    <option value="{{ $akun->id }}" {{ stripos($akun->nama_akun, 'Tunai') !== false ? 'selected' : '' }}>
                                        {{ $akun->nama_akun }} ({{ $akun->jenis_akun }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="invalid-feedback" id="err_akun_pembayaran_id"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Uang Diterima (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-lg text-end fw-bold shadow-sm" id="uang_diterima" name="uang_diterima" value="{{ $iurPasien <= 0 ? '0' : $iurPasien }}" placeholder="0">
                        <div class="invalid-feedback" id="err_uang_diterima"></div>
                    </div>

                    <div class="mb-4 p-2 px-3 bg-white border rounded-3 shadow-sm d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success small">KEMBALIAN</span>
                        <input type="text" class="form-control-plaintext text-end fw-bold text-success fs-5 w-50 p-0 m-0" id="kembalian_display" value="Rp 0" readonly>
                    </div>

                    <button type="submit" class="btn btn-success w-100 text-white btn-lg shadow mt-1" id="btnSimpanBayar">
                        <i class="fas fa-check-circle me-2"></i> {{ $isBpjs && $iurPasien <= 0 ? 'Selesaikan Klaim BPJS (Rp 0)' : 'Selesaikan Pembayaran' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    
    function formatRupiah(angka) {
        return Math.floor(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    let rawTotal = parseInt($('#raw_total').val()) || 0;

    function calculate() {
        let diskon = parseInt($('#diskon').val()) || 0;
        let grandTotal = rawTotal - diskon;
        if(grandTotal < 0) grandTotal = 0;
        
        $('#grand_total_display').val('Rp ' + formatRupiah(grandTotal));

        let uangDiterima = parseInt($('#uang_diterima').val()) || 0;
        let kembalian = uangDiterima - grandTotal;
        if(kembalian < 0) kembalian = 0;

        $('#kembalian_display').val('Rp ' + formatRupiah(kembalian));
    }

    $('#diskon, #uang_diterima').on('keyup change', function() {
        calculate();
    });

    calculate();

    $('#formKasir').on('submit', function (e) {
        e.preventDefault();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        let btn = $('#btnSimpanBayar');
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

        $.ajax({
            url: '/transaksi-kasir', 
            type: 'POST', 
            data: $(this).serialize(),
            success: function (res) {
                btn.html(originalText).prop('disabled', false);
                $('#alertBox').removeClass('d-none alert-danger').addClass('alert-success').text(res.message).show();
                $('html, body').animate({scrollTop: 0}, 300);
                setTimeout(function() {
                    window.location.reload();
                }, 1200);
            },
            error: function (xhr) {
                btn.html(originalText).prop('disabled', false);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if(errors) {
                        $.each(errors, function (field, messages) {
                            $('#' + field).addClass('is-invalid');
                            $('#err_' + field).text(messages[0]);
                        });
                    } else if (xhr.responseJSON.message) {
                        $('#alertBox').removeClass('d-none alert-success').addClass('alert-danger').text(xhr.responseJSON.message).show();
                    }
                    $('html, body').animate({scrollTop: 0}, 300);
                } else {
                    $('#alertBox').removeClass('d-none alert-success').addClass('alert-danger').text('Terjadi kesalahan server!').show();
                }
            }
        });
    });

    @if($kasir)
    $('#btnBatalBayar').click(function() {
        if(confirm('Yakin ingin membatalkan pembayaran ini? Data kwitansi akan dihapus.')) {
            $.ajax({
                url: '/transaksi-kasir/{{ $kasir->id }}',
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    alert(res.message);
                    window.location.reload();
                }
            });
        }
    });
    @endif
});
</script>
@endsection
