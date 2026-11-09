<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Pemesanan Barang (PO) - KLINIK TELAGASARI 24 JAM</title>
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
            font-size: 10.5px;
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
            width: 200px;
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
        <button onclick="window.close()" style="padding: 6px 15px; background: #999; color: #fff; border: none; border-radius: 3px; cursor: pointer; margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class="header-print">
        <div class="clinic-name">{{ $profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}</div>
        <div class="clinic-address">{{ $profil->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat' }}</div>
        <div class="clinic-address">Telp: {{ $profil->telepon ?? '(0264) 8303763' }} | Email: {{ $profil->email ?? 'kliniktelagasari24jam@gmail.com' }}</div>
    </div>

    <div class="report-title">LAPORAN TRANSAKSI PEMESANAN BARANG (PURCHASE ORDER)</div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">#</th>
                <th style="width: 130px;">No. PO</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Nama Vendor / Distributor</th>
                <th style="width: 80px;">Tgl. Kirim</th>
                <th style="width: 120px;">Kurir/Pengiriman</th>
                <th class="text-end" style="width: 100px;">Total PO</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($orders as $idx => $order)
            @php $grandTotal += $order->total_harga; @endphp
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $order->nomor_po }}</td>
                <td>{{ $order->tanggal_po ? \Carbon\Carbon::parse($order->tanggal_po)->format('d-m-Y') : '-' }}</td>
                <td>{{ $order->masterVendor->nama_vendor ?? '-' }}</td>
                <td>{{ $order->tgl_kirim ? \Carbon\Carbon::parse($order->tgl_kirim)->format('d-m-Y') : '-' }}</td>
                <td>{{ $order->pengiriman_kurir ?? '-' }}</td>
                <td class="text-end">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 15px;">Tidak ada data pemesanan barang.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f2f2f2;">
                <td colspan="6" class="text-end">TOTAL KESELURUHAN:</td>
                <td class="text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-print" style="margin-top: 40px; display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; text-align: center;">
            <p style="margin-bottom: 60px;">Mengetahui,<br>Pimpinan Klinik</p>
            <p style="font-weight: bold; text-decoration: underline;">( .................................................. )</p>
        </div>
        <div style="display: table-cell; width: 50%; text-align: center;">
            <p style="margin-bottom: 60px;">Karawang, {{ date('d F Y') }}<br>Bagian Farmasi / Pengadaan</p>
            <p style="font-weight: bold; text-decoration: underline;">( .................................................. )</p>
        </div>
    </div>
</body>
</html>
