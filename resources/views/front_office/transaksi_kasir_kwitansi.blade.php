<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran - {{ $kasir->no_kwitansi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background-color: #f4f6f9;
            font-size: 13px;
        }
        .kwitansi-container {
            max-width: 820px;
            margin: 30px auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        .header-title {
            font-size: 20px;
            font-weight: 800;
            color: #1e5647;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header-sub {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.4;
        }
        .kwitansi-badge {
            background-color: #00a65a;
            color: #fff;
            padding: 4px 14px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            border-radius: 4px;
            display: inline-block;
        }
        .table-custom th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 600;
            font-size: 12px;
            border-color: #e2e8f0;
            padding: 8px 10px;
        }
        .table-custom td {
            font-size: 12.5px;
            border-color: #f1f5f9;
            padding: 8px 10px;
            vertical-align: middle;
        }
        .total-box {
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 12px 18px;
            border: 1px solid #e2e8f0;
        }
        .signature-box {
            margin-top: 40px;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px dashed #94a3b8;
            font-weight: 600;
            padding-top: 4px;
        }
        @media print {
            body {
                background: #fff;
                font-size: 11pt;
            }
            .kwitansi-container {
                max-width: 100%;
                margin: 0;
                padding: 15px;
                box-shadow: none;
                border: none;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 1cm;
                size: A5 landscape;
            }
        }
    </style>
</head>
<body>

<div class="container my-3 no-print">
    <div class="d-flex justify-content-between align-items-center max-w-800 mx-auto" style="max-width: 820px;">
        <a href="javascript:window.history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-success btn-sm px-4 fw-semibold shadow-sm">
                <i class="fas fa-print me-1"></i> Cetak Dokumen / Print
            </button>
        </div>
    </div>
</div>

@php
    $penj = $kasir->pendaftaran->penjamin->nama_penjamin ?? '';
    $isBpjs = (isset($split) && $split['is_bpjs']) || stripos($penj, 'BPJS') !== false;
@endphp

<div class="kwitansi-container">
    <!-- Header Faskes -->
    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
        <div>
            <div class="header-title"><i class="fas fa-clinic-medical text-success me-2"></i>KLINIK TELAGASARI 24 JAM</div>
            <div class="header-sub">Jl. Raya Telagasari No. 12, Karawang, Jawa Barat</div>
            <div class="header-sub">Telp: (0267) 510123 / Hotline: 0812-3456-7890 | Email: info@kliniktelagasari.co.id</div>
        </div>
        <div class="text-end">
            <div class="kwitansi-badge mb-1">
                @if($isBpjs)
                    KWITANSI &amp; KLAIM BPJS
                @else
                    KWITANSI RESMI
                @endif
            </div>
            <div class="fw-bold fs-6 text-dark mt-1">{{ $kasir->no_kwitansi }}</div>
            <div class="text-muted small">Tgl: {{ \Carbon\Carbon::parse($kasir->waktu_bayar)->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <!-- Data Pasien & Kunjungan -->
    <div class="row g-2 mb-3 bg-light p-3 rounded border">
        <div class="col-6">
            <table class="table table-borderless table-sm mb-0 small">
                <tr>
                    <td class="text-muted py-0 ps-0" style="width: 110px;">No. Registrasi</td>
                    <td class="fw-bold py-0">: {{ $kasir->pendaftaran->nomor_registrasi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted py-0 ps-0">No. Rekam Medis</td>
                    <td class="fw-bold py-0">: {{ $kasir->pendaftaran->pasien->no_rm ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted py-0 ps-0">Nama Pasien</td>
                    <td class="fw-bold py-0">: {{ $kasir->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <table class="table table-borderless table-sm mb-0 small">
                <tr>
                    <td class="text-muted py-0 ps-0" style="width: 100px;">Poliklinik</td>
                    <td class="fw-semibold py-0">: {{ $kasir->pendaftaran->poliklinik->nama_poli ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted py-0 ps-0">Dokter</td>
                    <td class="fw-semibold py-0">: {{ $kasir->pendaftaran->dokter->nama_pegawai ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted py-0 ps-0">Penjamin</td>
                    <td class="fw-semibold py-0">: 
                        <span class="badge {{ $isBpjs ? 'bg-success' : 'bg-secondary' }}">
                            {{ $kasir->pendaftaran->penjamin->nama_penjamin ?? 'Umum/Pribadi' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Rincian Biaya -->
    <div class="mb-3">
        <table class="table table-bordered table-custom mb-0">
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th>Rincian Pelayanan / Tindakan &amp; Obat</th>
                    <th style="width: 50px;" class="text-center">Qty</th>
                    <th style="width: 110px;" class="text-end">Tarif Satuan</th>
                    <th style="width: 120px;" class="text-end">Subtotal</th>
                    <th style="width: 130px;" class="text-center">Penjaminan</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp

                <!-- Tindakan Medis -->
                @if($tindakans->count() > 0)
                    <tr class="table-light">
                        <td colspan="6" class="fw-bold text-dark py-1" style="font-size: 11px;">
                            <i class="fas fa-stethoscope me-1 text-primary"></i> A. TINDAKAN MEDIS / PROSEDUR
                        </td>
                    </tr>
                    @foreach($tindakans as $t)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $t->tindakan->nama_tindakan ?? 'Tindakan' }}</td>
                            <td class="text-center">{{ $t->qty }}</td>
                            <td class="text-end">Rp {{ number_format($t->tarif_satuan, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($t->subtotal, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($isBpjs)
                                    <span class="badge bg-success bg-opacity-75 text-white" style="font-size: 10px;">Ditanggung BPJS</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size: 10px;">Mandiri</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif

                <!-- Resep Obat / Farmasi -->
                @php
                    $hasObat = false;
                    foreach($reseps as $r) {
                        if($r->details->count() > 0 || $r->total_harga > 0) $hasObat = true;
                    }
                @endphp

                @if($hasObat)
                    <tr class="table-light">
                        <td colspan="6" class="fw-bold text-dark py-1" style="font-size: 11px;">
                            <i class="fas fa-pills me-1 text-success"></i> B. RESEP OBAT &amp; BHP FARMASI
                        </td>
                    </tr>
                    @foreach($reseps as $r)
                        @if($r->details->count() > 0)
                            @foreach($r->details as $d)
                                @php
                                    $isFornas = (bool) ($d->masterBarang && $d->masterBarang->is_formularium);
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>
                                        {{ $d->masterBarang->nama_barang ?? 'Obat' }}
                                        @if($d->aturan_pakai)
                                            <span class="text-muted small">({{ $d->aturan_pakai }})</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $d->jumlah }}</td>
                                    <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($isBpjs)
                                            @if($isFornas)
                                                <span class="badge bg-success bg-opacity-75 text-white" style="font-size: 10px;">Fornas (BPJS)</span>
                                            @else
                                                <span class="badge bg-warning text-dark fw-bold" style="font-size: 10px;">Non-Fornas (Iur)</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 10px;">Mandiri</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>Resep Obat ({{ $r->no_resep }})</td>
                                <td class="text-center">1</td>
                                <td class="text-end">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($isBpjs)
                                        <span class="badge bg-success bg-opacity-75 text-white" style="font-size: 10px;">Fornas (BPJS)</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 10px;">Mandiri</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif

                @if(isset($inapTransfers) && $inapTransfers->count() > 0)
                    <tr class="table-light">
                        <td colspan="6" class="fw-bold text-dark py-1" style="font-size: 11px;">
                            <i class="fas fa-bed me-1 text-primary"></i> C. AKOMODASI KAMAR RAWAT INAP
                        </td>
                    </tr>
                    @foreach($inapTransfers as $it)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $it->kamarRanap->nama_kamar ?? 'Kamar' }} ({{ $it->kamarRanap->nomor_bed ?? '' }}) - {{ $it->kamarRanap->kelas ?? '' }}</td>
                            <td class="text-center">{{ $it->lama_hari }} Hari</td>
                            <td class="text-end">Rp {{ number_format($it->tarif_per_hari, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($it->total_biaya_kamar, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($isBpjs)
                                    @if(str_contains(strtoupper($it->kamarRanap->kelas ?? ''), 'VIP'))
                                        <span class="badge bg-warning text-dark fw-bold" style="font-size: 10px;">Iur Selisih VIP</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-75 text-white" style="font-size: 10px;">Cover BPJS</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary" style="font-size: 10px;">Mandiri</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif

                @if($tindakans->count() == 0 && !$hasObat && (!isset($inapTransfers) || $inapTransfers->count() == 0))
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Tidak ada item tagihan</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Ringkasan Total & Pembayaran -->
    <div class="row g-3 align-items-start">
        <div class="col-7">
            <div class="p-2 px-3 border rounded bg-light small">
                <div class="text-muted mb-1">Status Pembayaran: <strong class="text-success"><i class="fas fa-check-circle me-1"></i>LUNAS</strong></div>
                <div class="text-muted mb-1">Metode Bayar: <strong>{{ $kasir->cara_bayar ?? ($kasir->akunPembayaran->nama_akun ?? 'CASH / TUNAI') }}</strong></div>
                <div class="text-muted">Kasir Petugas: <strong>{{ $kasir->kasir->name ?? 'Admin Klinik' }}</strong></div>
                @if($isBpjs)
                <div class="mt-2 text-success fw-semibold" style="font-size: 11px;">
                    <i class="fas fa-info-circle me-1"></i> Tagihan tindakan &amp; obat Formularium dijamin oleh BPJS Kesehatan via sistem klaim faskes.
                </div>
                @endif
                <div class="mt-1 text-muted fst-italic" style="font-size: 10.5px;">
                    * Struk kwitansi ini adalah bukti pembayaran yang sah yang diterbitkan oleh sistem Rekam Medis Elektronik (RME).
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="total-box">
                @php
                    $subTotalBruto = $kasir->sub_total > 0 ? $kasir->sub_total : ($kasir->total_tagihan + ($kasir->dijamin ?? 0) + $kasir->diskon);
                @endphp
                
                @if($isBpjs && ($kasir->dijamin ?? 0) > 0)
                <div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted">Total Bruto Pelayanan:</span>
                    <span class="fw-semibold">Rp {{ number_format($subTotalBruto, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-success">
                    <span><i class="fas fa-shield-alt me-1"></i> Ditanggung BPJS:</span>
                    <span class="fw-semibold">- Rp {{ number_format($kasir->dijamin, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-muted">
                    <span>Iur Mandiri Pasien:</span>
                    <span class="fw-semibold">Rp {{ number_format($kasir->dibayar_pasien + $kasir->diskon, 0, ',', '.') }}</span>
                </div>
                @else
                <div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted">Subtotal Tagihan:</span>
                    <span class="fw-semibold">Rp {{ number_format($subTotalBruto, 0, ',', '.') }}</span>
                </div>
                @endif

                @if($kasir->diskon > 0)
                <div class="d-flex justify-content-between mb-1 small text-danger">
                    <span>Diskon / Potongan:</span>
                    <span>- Rp {{ number_format($kasir->diskon, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="d-flex justify-content-between border-top pt-1 mt-1 mb-2">
                    <span class="fw-bold text-dark fs-6">{{ $isBpjs ? 'Grand Total Pasien:' : 'Grand Total:' }}</span>
                    <span class="fw-bold text-primary fs-6">Rp {{ number_format($kasir->total_tagihan, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span>Uang Diterima:</span>
                    <span class="fw-semibold text-dark">Rp {{ number_format($kasir->uang_diterima, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between small text-success fw-bold">
                    <span>Kembalian:</span>
                    <span>Rp {{ number_format($kasir->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tanda Tangan -->
    <div class="row signature-box">
        <div class="col-6">
            <div class="small text-muted mb-1">Pasien / Keluarga</div>
            <div class="signature-line" style="width: 180px; margin: 50px auto 0 auto;">
                ( {{ $kasir->pendaftaran->pasien->nama_pasien ?? '...........................' }} )
            </div>
        </div>
        <div class="col-6">
            <div class="small text-muted mb-1">Karawang, {{ \Carbon\Carbon::parse($kasir->waktu_bayar)->translatedFormat('d F Y') }}<br>Petugas Kasir</div>
            <div class="signature-line" style="width: 180px; margin: 38px auto 0 auto;">
                ( {{ $kasir->kasir->name ?? 'Admin Klinik' }} )
            </div>
        </div>
    </div>
</div>

<script>
    // Cetak otomatis jika query parameter print=1 diberikan
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        window.onload = function() {
            window.print();
        }
    }
</script>
</body>
</html>
