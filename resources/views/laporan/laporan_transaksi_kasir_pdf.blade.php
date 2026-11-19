<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Kasir - {{ $tanggal }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
        }
        .header p {
            margin: 0;
            font-size: 11px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #777;
            padding: 4px 5px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .footer {
            margin-top: 25px;
            display: flex;
            justify-content: flex-end;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        @media print {
            body { margin: 5mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 10px;">
        <button onclick="window.print()" style="padding: 5px 12px; cursor: pointer;">Print / Cetak</button>
        <button onclick="window.close()" style="padding: 5px 12px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h2>KLINIK TELAGASARI 24 JAM</h2>
        <p>Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang | Telp: (0264) 8303763</p>
        <h3 style="margin-top: 8px;">LAPORAN TRANSAKSI KASIR</h3>
        <p>Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No Reg</th>
                <th>Nama</th>
                <th>No. RM</th>
                <th>J.K</th>
                <th>Penjamin</th>
                <th>No Kartu</th>
                <th>Klinik</th>
                <th>Adm</th>
                <th>Tindakan</th>
                <th>Konsultasi</th>
                <th>Farmasi</th>
                <th>Lab</th>
                <th>Radiologi</th>
                <th>Sub Total</th>
                <th>Disc(%)</th>
                <th>Total Tagihan</th>
                <th>Dijamin</th>
                <th>Dibayar</th>
                <th>Cara Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totAdm = 0; $totTindakan = 0; $totKonsultasi = 0; $totFarmasi = 0;
                $totLab = 0; $totRad = 0; $totSub = 0; $totTagihan = 0;
                $totDijamin = 0; $totDibayar = 0;
            @endphp
            @forelse($rows as $row)
            @php
                $totAdm += $row->adm;
                $totTindakan += $row->tindakan;
                $totKonsultasi += $row->konsultasi;
                $totFarmasi += $row->farmasi;
                $totLab += $row->lab;
                $totRad += $row->radiologi;
                $totSub += $row->sub_total;
                $totTagihan += $row->total_tagihan;
                $totDijamin += $row->dijamin;
                $totDibayar += $row->dibayar_pasien;
            @endphp
            <tr>
                <td class="text-center">{{ $row->no }}</td>
                <td class="text-center">{{ $row->tanggal }}</td>
                <td class="text-left">{{ $row->no_reg }}</td>
                <td class="text-left">{{ $row->nama }}</td>
                <td class="text-left">{{ $row->no_rm }}</td>
                <td class="text-center">{{ $row->jk == 'Perempuan' ? 'P' : 'L' }}</td>
                <td class="text-left">{{ $row->penjamin }}</td>
                <td class="text-left">{{ $row->no_kartu }}</td>
                <td class="text-left">{{ $row->klinik }}</td>
                <td class="text-right">{{ number_format($row->adm, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->tindakan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->konsultasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->farmasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->lab, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->radiologi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->sub_total, 0, ',', '.') }}</td>
                <td class="text-center">{{ $row->disc }}</td>
                <td class="text-right">{{ number_format($row->total_tagihan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->dijamin, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->dibayar_pasien, 0, ',', '.') }}</td>
                <td class="text-center">{{ $row->cara_bayar }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="21" class="text-center">Tidak ada data transaksi kasir</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($rows) > 0)
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="9" class="text-center">TOTAL</td>
                <td class="text-right">{{ number_format($totAdm, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totTindakan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totKonsultasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totFarmasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totLab, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totRad, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totSub, 0, ',', '.') }}</td>
                <td class="text-center">-</td>
                <td class="text-right">{{ number_format($totTagihan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totDijamin, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totDibayar, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <div class="signature">
            <p>Karawang, {{ date('d-m-Y') }}<br>Petugas Kasir,</p>
            <br><br><br>
            <p>( .................................................... )</p>
        </div>
    </div>
</body>
</html>

