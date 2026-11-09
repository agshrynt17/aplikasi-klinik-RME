<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemesanan Barang (PO) - {{ $order->nomor_po }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11.5px;
            color: #333;
            margin: 25px;
        }
        .header-print {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 8px;
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
            text-decoration: underline;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-grid td {
            vertical-align: top;
            padding: 3px 0;
            font-size: 11.5px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.items-table th, table.items-table td {
            border: 1px solid #555;
            padding: 6px 8px;
            font-size: 11px;
        }
        table.items-table th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 15px; background: #00a65a; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak Surat Pesanan (PO)
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

    <div class="report-title">SURAT PESANAN OBAT & BARANG MEDIS (PURCHASE ORDER)</div>

    <table class="info-grid">
        <tr>
            <td style="width: 50%;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 120px; font-weight: bold;">Nomor PO</td>
                        <td style="width: 10px;">:</td>
                        <td style="font-weight: bold;">{{ $order->nomor_po }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal PO</td>
                        <td>:</td>
                        <td>{{ $order->tanggal_po ? \Carbon\Carbon::parse($order->tanggal_po)->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tgl. Rencana Kirim</td>
                        <td>:</td>
                        <td>{{ $order->tgl_kirim ? \Carbon\Carbon::parse($order->tgl_kirim)->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Pengiriman / Kurir</td>
                        <td>:</td>
                        <td>{{ $order->pengiriman_kurir ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; padding-left: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Kepada Yth.</td>
                        <td style="width: 10px;">:</td>
                        <td style="font-weight: bold; font-size: 12px;">{{ $order->masterVendor->nama_vendor ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Vendor</td>
                        <td>:</td>
                        <td>{{ $order->masterVendor->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Kirim</td>
                        <td>:</td>
                        <td>{{ $order->alamat_kirim ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 35px;">No</th>
                <th style="width: 120px;">Kode Barang</th>
                <th>Nama Barang / Obat</th>
                <th style="width: 100px;">Kemasan</th>
                <th class="text-end" style="width: 80px;">Jumlah</th>
                <th class="text-end" style="width: 120px;">Harga Satuan</th>
                <th class="text-end" style="width: 130px;">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @php $totalQty = 0; $grandTotal = 0; @endphp
            @foreach($order->details as $idx => $detail)
            @php 
                $totalQty += $detail->jumlah; 
                $grandTotal += $detail->subtotal; 
            @endphp
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ $detail->masterBarang->kode_barang ?? '-' }}</td>
                <td style="font-weight: bold;">{{ $detail->masterBarang->nama_barang ?? '-' }}</td>
                <td>{{ $detail->kemasan ?? $detail->masterBarang->kemasan ?? '-' }}</td>
                <td class="text-end">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f2f2f2;">
                <td colspan="4" class="text-end">TOTAL:</td>
                <td class="text-end">{{ number_format($totalQty, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 40px; display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; text-align: center;">
            <p style="margin-bottom: 60px;">Penerima / Vendor,</p>
            <p style="font-weight: bold; text-decoration: underline;">( .................................................. )</p>
        </div>
        <div style="display: table-cell; width: 50%; text-align: center;">
            <p style="margin-bottom: 60px;">Karawang, {{ date('d F Y') }}<br>Penanggung Jawab Farmasi / Apoteker,</p>
            <p style="font-weight: bold; text-decoration: underline;">{{ $profil->penanggung_jawab_apotek ?? 'apt. Fariddon Ricky Wahyudi, S.Farm' }}</p>
            <p style="font-size: 10px; color: #666;">SIP: {{ $profil->no_izin_apotek ?? '123/019/X/KLINIK-OSS/XXX' }}</p>
        </div>
    </div>
</body>
</html>
