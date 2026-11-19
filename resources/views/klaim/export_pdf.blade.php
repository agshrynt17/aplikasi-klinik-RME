<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Klaim Pasien</title>
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
            font-size: 13px;
        }
        .header p {
            margin: 0;
            font-size: 10px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }
        th, td {
            border: 1px solid #777;
            padding: 4px 6px;
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
            float: right;
        }
        .no-print {
            margin-bottom: 15px;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 5mm; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 6px 15px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <div class="header">
        <h2>KLINIK TELAGASARI 24 JAM</h2>
        <h3>TRANSAKSI KLAIM PASIEN</h3>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th>Status Klaim</th>
                <th>Tgl Klaim</th>
                <th>No Klaim</th>
                <th>Penjamin</th>
                <th>Tgl. Transaksi</th>
                <th>Jml.Hari</th>
                <th>Subtotal</th>
                <th>Diskon (Rp)</th>
                <th>Total Tagihan</th>
                <th>Total Realisasi</th>
                <th>Selisih</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totSubtotal = 0;
                $totDiskon = 0;
                $totTagihan = 0;
                $totRealisasi = 0;
                $totSelisih = 0;
            @endphp
            @forelse($klaims as $idx => $item)
                @php
                    $totSubtotal += $item->subtotal;
                    $totDiskon += $item->diskon;
                    $totTagihan += $item->total_tagihan;
                    $totRealisasi += $item->total_realisasi;
                    $totSelisih += $item->selisih;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $item->status_klaim }}</td>
                    <td class="text-center">{{ $item->tgl_klaim ? $item->tgl_klaim->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->no_klaim }}</td>
                    <td>{{ $item->nama_penjamin }}</td>
                    <td class="text-center">
                        @if($item->tgl_transaksi_awal && $item->tgl_transaksi_akhir)
                            {{ $item->tgl_transaksi_awal->format('d/m/Y') }} - {{ $item->tgl_transaksi_akhir->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $item->jml_hari }} Hari</td>
                    <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->diskon, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total_realisasi, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->selisih, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data klaim</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f2f2f2;">
                <td colspan="7" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-right">{{ number_format($totSubtotal, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totDiskon, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totTagihan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totRealisasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totSelisih, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; width: 100%;">
        <div class="signature">
            <p>Petugas Klaim,</p>
            <br><br><br>
            <p><strong>( ..................................... )</strong></p>
        </div>
    </div>
</body>
</html>

