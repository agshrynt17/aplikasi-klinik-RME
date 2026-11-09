<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi Pembayaran Hutang - {{ $pembayaran ? $pembayaran->nomor_bukti : 'KUITANSI' }} - KLINIK TELAGASARI 24 JAM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11.5px;
            color: #333;
            margin: 25px;
        }
        .kuitansi-box {
            border: 2px solid #333;
            padding: 20px;
            max-width: 750px;
            margin: 0 auto;
        }
        .header-print {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 15px;
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
            letter-spacing: 1px;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table-data td {
            padding: 6px 8px;
            vertical-align: top;
            font-size: 12px;
        }
        .box-nominal {
            border: 2px dashed #008d4c;
            background-color: #f6fdf8;
            padding: 10px 15px;
            display: inline-block;
            font-size: 17px;
            font-weight: bold;
            color: #008d4c;
            border-radius: 4px;
        }
        .footer-sign {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .sign-box {
            width: 220px;
            text-align: center;
            font-size: 11.5px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: center;">
        <button onclick="window.print()" style="padding: 6px 18px; background: #3c8dbc; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
            🖨️ Cetak / Print Kuitansi
        </button>
        <button onclick="window.close()" style="padding: 6px 18px; background: #777; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class="kuitansi-box">
        <div class="header-print">
            <div class="clinic-name">{{ $profil->nama_klinik ?? 'KLINIK TELAGASARI 24 JAM' }}</div>
            <div class="clinic-address">
                {{ $profil->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat' }} | Telp: {{ $profil->telepon ?? '0812-3456-7890' }}
            </div>
        </div>

        <div class="doc-title">BUKTI PEMBAYARAN HUTANG TAGIHAN</div>

        <table class="table-data">
            <tr>
                <td style="width: 25%;"><strong>Nomor Bukti</strong></td>
                <td style="width: 3%;">:</td>
                <td style="width: 72%;"><strong>{{ $pembayaran ? $pembayaran->nomor_bukti : 'BKT-' . date('Ymd') }}</strong></td>
            </tr>
            <tr>
                <td><strong>Tanggal Pembayaran</strong></td>
                <td>:</td>
                <td>{{ $pembayaran ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d F Y') : date('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Dibayarkan Kepada</strong></td>
                <td>:</td>
                <td><strong>{{ $hutang->masterVendor ? $hutang->masterVendor->nama_vendor : '-' }}</strong></td>
            </tr>
            <tr>
                <td><strong>Untuk Pembayaran</strong></td>
                <td>:</td>
                <td>
                    Pelunasan / Cicilan Faktur: <strong>{{ $hutang->no_tagihan_display }}</strong>
                    @if($hutang->masukBarang)
                        (Ref. Penerimaan: {{ $hutang->masukBarang->nomor_penerimaan }})
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Metode Pembayaran</strong></td>
                <td>:</td>
                <td>{{ $pembayaran ? $pembayaran->metode_pembayaran : ($hutang->cara_bayar ?? 'Transfer Bank') }}</td>
            </tr>
            <tr>
                <td><strong>Catatan / Keterangan</strong></td>
                <td>:</td>
                <td>{{ $pembayaran ? ($pembayaran->keterangan ?? '-') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Status Tagihan</strong></td>
                <td>:</td>
                <td>
                    <span style="font-weight: bold; color: {{ $hutang->status === 'Lunas' ? '#008d4c' : '#dd4b39' }};">
                        {{ $hutang->status }}
                    </span>
                    (Sisa Hutang: <strong>Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</strong>)
                </td>
            </tr>
            <tr>
                <td style="padding-top: 15px;"><strong>Jumlah Pembayaran</strong></td>
                <td style="padding-top: 15px;">:</td>
                <td style="padding-top: 15px;">
                    <div class="box-nominal">
                        Rp {{ number_format($pembayaran ? $pembayaran->nominal_bayar : $hutang->jumlah_dibayar, 0, ',', '.') }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer-sign">
            <div class="sign-box">
                Penerima / Vendor,<br>
                <strong>{{ $hutang->masterVendor ? $hutang->masterVendor->nama_vendor : 'PBF / Vendor' }}</strong>
                <br><br><br><br>
                ( ......................................... )
            </div>
            <div class="sign-box">
                Karawang, {{ date('d F Y') }}<br>
                Bagian Keuangan Farmasi,<br>
                <strong>{{ $profil->nama_klinik ?? 'KLINIK TELAGASARI 24 JAM' }}</strong>
                <br><br><br><br>
                ( ......................................... )
            </div>
        </div>
    </div>
</body>
</html>
