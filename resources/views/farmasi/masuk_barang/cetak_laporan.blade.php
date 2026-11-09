<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Penerimaan Barang - KLINIK TELAGASARI 24 JAM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        .header-print {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .clinic-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .clinic-address {
            font-size: 11px;
            color: #555;
        }
        .report-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #777;
            padding: 5px 6px;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .footer-print {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .sign-box {
            width: 220px;
            text-align: center;
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

    <div class="report-title">
        LAPORAN TRANSAKSI PENERIMAAN BARANG & OBAT (MASUK BARANG)
        <div style="font-size: 10.5px; font-weight: normal; margin-top: 3px;">
            Dicetak pada: {{ date('d-m-Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 95px;">Kode Penerimaan</th>
                <th style="width: 75px;" class="text-center">Tgl Terima</th>
                <th>Vendor / PBF</th>
                <th style="width: 100px;">No Faktur</th>
                <th style="width: 75px;" class="text-center">Pembayaran</th>
                <th style="width: 75px;" class="text-center">Jatuh Tempo</th>
                <th style="width: 85px;" class="text-end">Grand Total</th>
                <th style="width: 80px;" class="text-end">Total Bayar</th>
                <th style="width: 80px;" class="text-end">Sisa Bayar</th>
                <th style="width: 65px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sumGrandTotal = 0;
                $sumTotalBayar = 0;
                $sumSisaBayar = 0;
            @endphp
            @forelse($data as $idx => $row)
                @php
                    $gTotal = $row->grand_total > 0 ? $row->grand_total : $row->total_tagihan;
                    $sumGrandTotal += $gTotal;
                    $sumTotalBayar += $row->total_bayar;
                    $sumSisaBayar += $row->sisa_bayar;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ $row->nomor_penerimaan }}</strong></td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_terima)->format('d-m-Y') }}</td>
                    <td>{{ $row->masterVendor ? $row->masterVendor->nama_vendor : '-' }}</td>
                    <td>
                        {{ $row->nomor_faktur }}
                        @if($row->kode_po || $row->orderPembelian)
                            <div style="font-size: 9px; color: #666;">PO: {{ $row->kode_po ?? ($row->orderPembelian->nomor_po ?? '') }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $row->jenis_pembayaran }}</td>
                    <td class="text-center">{{ $row->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($row->tanggal_jatuh_tempo)->format('d-m-Y') : '-' }}</td>
                    <td class="text-end">Rp {{ number_format($gTotal, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($row->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($row->sisa_bayar, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <strong style="color: {{ $row->status_pembayaran === 'Lunas' ? 'green' : 'red' }};">
                            {{ $row->status_pembayaran }}
                        </strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 15px;">Tidak ada data penerimaan barang yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="7" class="text-end">TOTAL KESELURUHAN:</td>
                <td class="text-end">Rp {{ number_format($sumGrandTotal, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($sumTotalBayar, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($sumSisaBayar, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-print">
        <div class="sign-box">
            Mengetahui,<br>
            <strong>Penanggung Jawab Farmasi</strong>
            <br><br><br><br>
            ( ......................................... )
        </div>
        <div class="sign-box">
            Karawang, {{ date('d-m-Y') }}<br>
            <strong>Bagian Penerimaan Logistik</strong>
            <br><br><br><br>
            ( ......................................... )
        </div>
    </div>
</body>
</html>
