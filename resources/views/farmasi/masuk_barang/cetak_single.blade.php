<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Penerimaan Barang (BKB) - {{ $mb->nomor_penerimaan }} - KLINIK TELAGASARI 24 JAM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 25px;
        }
        .header-print {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .clinic-name {
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .clinic-address {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }
        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 6px;
            vertical-align: top;
            font-size: 11px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.items-table th, table.items-table td {
            border: 1px solid #777;
            padding: 5px 6px;
            font-size: 10.5px;
        }
        table.items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .footer-print {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .sign-box {
            width: 200px;
            text-align: center;
            font-size: 11px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 15px; background: #3c8dbc; color: #fff; border: none; border-radius: 3px; cursor: pointer;">
            🖨️ Cetak / Print
        </button>
        <button onclick="window.close()" style="padding: 6px 15px; background: #777; color: #fff; border: none; border-radius: 3px; cursor: pointer;">
            Tutup
        </button>
    </div>

    <div class="header-print">
        <div class="clinic-name">{{ $profil->nama_klinik ?? 'KLINIK TELAGASARI 24 JAM' }}</div>
        <div class="clinic-address">
            {{ $profil->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat' }} | Telp: {{ $profil->telepon ?? '0812-3456-7890' }}
        </div>
    </div>

    <div class="doc-title">BUKTI PENERIMAAN BARANG (BKB)</div>

    <table class="meta-table">
        <tr>
            <td style="width: 18%;"><strong>No. Penerimaan</strong></td>
            <td style="width: 32%;">: <strong>{{ $mb->nomor_penerimaan }}</strong></td>
            <td style="width: 18%;"><strong>Vendor / PBF</strong></td>
            <td style="width: 32%;">: {{ $mb->masterVendor ? $mb->masterVendor->nama_vendor : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Terima</strong></td>
            <td>: {{ \Carbon\Carbon::parse($mb->tanggal_terima)->format('d-m-Y') }}</td>
            <td><strong>No. Faktur Vendor</strong></td>
            <td>: <strong>{{ $mb->nomor_faktur }}</strong></td>
        </tr>
        <tr>
            <td><strong>Ref. Kode PO</strong></td>
            <td>: {{ $mb->kode_po ?? ($mb->orderPembelian ? $mb->orderPembelian->nomor_po : '-') }}</td>
            <td><strong>Jenis Pembayaran</strong></td>
            <td>: {{ $mb->jenis_pembayaran }} ({{ $mb->status_pembayaran }})</td>
        </tr>
        <tr>
            <td><strong>Jatuh Tempo</strong></td>
            <td>: {{ $mb->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($mb->tanggal_jatuh_tempo)->format('d-m-Y') : '-' }}</td>
            <td><strong>Keterangan</strong></td>
            <td>: {{ $mb->keterangan ?? '-' }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th>Gudang / Lokasi</th>
                <th>Kode & Nama Barang / Obat</th>
                <th style="width: 70px;" class="text-center">Kemasan</th>
                <th style="width: 150px;">Batch / Kadaluarsa / BPOM</th>
                <th style="width: 60px;" class="text-end">Jumlah</th>
                <th style="width: 90px;" class="text-end">HNA (Rp)</th>
                <th style="width: 75px;" class="text-end">Diskon</th>
                <th style="width: 100px;" class="text-end">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSub = 0; @endphp
            @forelse($mb->details as $idx => $d)
                @php $totalSub += $d->subtotal; @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>
                        {{ $d->masterGudang ? $d->masterGudang->nama_gudang : 'Gudang Utama' }}
                        @if($d->lokasi_rak) ({{ $d->lokasi_rak }}) @endif
                    </td>
                    <td>
                        <strong>{{ $d->masterBarang ? $d->masterBarang->nama_barang : 'Item #' . $d->master_barang_id }}</strong>
                        @if($d->masterBarang && $d->masterBarang->kode_barang)
                            <div style="font-size: 9.5px; color: #555;">Kode: {{ $d->masterBarang->kode_barang }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $d->kemasan ?? ($d->masterBarang ? $d->masterBarang->kemasan : '-') }}</td>
                    <td>
                        @if($d->nomor_batch)<div>Batch: <strong>{{ $d->nomor_batch }}</strong></div>@endif
                        @if($d->tanggal_kadaluarsa)<div>Exp: {{ \Carbon\Carbon::parse($d->tanggal_kadaluarsa)->format('d-m-Y') }}</div>@endif
                        @if($d->nomor_bpom)<div>BPOM: {{ $d->nomor_bpom }}</div>@endif
                        @if(!$d->nomor_batch && !$d->tanggal_kadaluarsa && !$d->nomor_bpom)-@endif
                    </td>
                    <td class="text-end">{{ number_format($d->jumlah_terima, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-end">
                        @if($d->diskon_nominal > 0)
                            Rp {{ number_format($d->diskon_nominal, 0, ',', '.') }}
                        @elseif($d->diskon_persen > 0)
                            {{ number_format($d->diskon_persen, 1) }}%
                        @else
                            0
                        @endif
                    </td>
                    <td class="text-end">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada rincian item.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @php
                $gTotal = $mb->grand_total > 0 ? $mb->grand_total : $mb->total_tagihan;
            @endphp
            <tr>
                <td colspan="8" class="text-end"><strong>Total Nilai Barang:</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($mb->subtotal > 0 ? $mb->subtotal : $totalSub, 0, ',', '.') }}</strong></td>
            </tr>
            @if($mb->diskon_total_nominal > 0 || $mb->diskon_total_persen > 0)
            <tr>
                <td colspan="8" class="text-end">Diskon Total ({{ number_format($mb->diskon_total_persen, 1) }}%):</td>
                <td class="text-end" style="color: red;">- Rp {{ number_format($mb->diskon_total_nominal, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($mb->ppn_nominal > 0 || $mb->ppn_persen > 0)
            <tr>
                <td colspan="8" class="text-end">PPN ({{ number_format($mb->ppn_persen, 1) }}%):</td>
                <td class="text-end" style="color: green;">+ Rp {{ number_format($mb->ppn_nominal, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr style="background-color: #f2f2f2; font-size: 11.5px;">
                <td colspan="8" class="text-end"><strong>GRAND TOTAL PENERIMAAN:</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($gTotal, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="8" class="text-end">Total Bayar (DP / Lunas):</td>
                <td class="text-end">Rp {{ number_format($mb->total_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="8" class="text-end"><strong>Sisa Hutang:</strong></td>
                <td class="text-end" style="color: {{ $mb->sisa_bayar > 0 ? 'red' : 'green' }};">
                    <strong>Rp {{ number_format($mb->sisa_bayar, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-print">
        <div class="sign-box">
            Diterima & Diperiksa oleh,<br>
            <strong>Petugas Gudang Farmasi</strong>
            <br><br><br><br>
            ( ......................................... )
        </div>
        <div class="sign-box">
            Diserahkan oleh,<br>
            <strong>Kurir / Pengirim PBF</strong>
            <br><br><br><br>
            ( ......................................... )
        </div>
        <div class="sign-box">
            Mengetahui,<br>
            <strong>Apoteker Penanggung Jawab</strong>
            <br><br><br><br>
            ( ......................................... )
        </div>
    </div>
</body>
</html>
