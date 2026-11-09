<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Modul Kasir - Klinik Telagasari 24 Jam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 14px; background: #00c0ef; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-weight: bold;">
            🖶 Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" style="padding: 6px 14px; background: #888; color: #fff; border: none; border-radius: 3px; cursor: pointer;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h2>KLINIK TELAGASARI 24 JAM</h2>
        <p>Jl. Raya Telagasari - Karawang | Telp: (0267) 123456 | Email: info@kliniktelagasari.com</p>
        <h3 style="margin-top: 10px; margin-bottom: 0;">LAPORAN TRANSAKSI KASIR / PEMBAYARAN</h3>
        <p>Tanggal Cetak: {{ date('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="14%">No. Registrasi</th>
                <th width="12%">No. RM</th>
                <th width="18%">Nama Pasien</th>
                <th width="15%">Klinik & Dokter</th>
                <th width="12%">Penjamin</th>
                <th width="12%" class="text-end">Total Tagihan</th>
                <th width="12%" class="text-center">Status Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($data as $idx => $r)
                @php
                    $isLunas = $r->transaksiKasir && $r->transaksiKasir->status_pembayaran == 'Lunas';
                    $tagihan = 0;
                    if ($r->transaksiKasir) {
                        $tagihan = $r->transaksiKasir->total_tagihan;
                    } else {
                        $tindakanTotal = $r->transaksiTindakans->sum('subtotal');
                        $tagihan = $tindakanTotal > 0 ? $tindakanTotal : 10000;
                    }
                    $grandTotal += $tagihan;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ $r->nomor_registrasi ?: $r->no_rawat }}</strong><br><small>{{ $r->tanggal_daftar ? $r->tanggal_daftar->format('d-m-Y') : '-' }}</small></td>
                    <td>{{ $r->pasien->no_rm ?? '-' }}</td>
                    <td>{{ strtoupper($r->pasien->nama_pasien ?? '-') }}</td>
                    <td>{{ $r->poliklinik->nama_poli ?? '-' }}<br><small>{{ $r->dokter->nama_pegawai ?? '-' }}</small></td>
                    <td>{{ $r->penjamin->nama_penjamin ?? 'Pribadi' }}</td>
                    <td class="text-end">Rp {{ number_format($tagihan, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $isLunas ? 'Lunas' : 'Belum Bayar' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #f4f4f4; font-weight: bold;">
                <td colspan="6" class="text-end">GRAND TOTAL:</td>
                <td class="text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Petugas Kasir,</p>
            <br><br><br>
            <p><strong>( {{ Auth::user()->name ?? 'dr. FAHMI RIZAL' }} )</strong></p>
        </div>
    </div>
</body>
</html>
