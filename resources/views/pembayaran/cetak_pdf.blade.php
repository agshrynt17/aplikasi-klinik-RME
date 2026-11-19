<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pembayaran Pasien - {{ $tanggal }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
            font-size: 10px;
        }
        th, td {
            border: 1px solid #777;
            padding: 5px 6px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
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
        <h3 style="margin-top: 8px;">DATA PEMBAYARAN PASIEN</h3>
        <p>Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">#</th>
                <th style="width: 110px;">Registrasi</th>
                <th>Pasien</th>
                <th style="width: 110px;">Poliklinik</th>
                <th style="width: 150px;">Pembayaran</th>
                <th style="width: 80px; text-align: right;">Tagihan</th>
                <th style="width: 90px; text-align: right;">Tanggungan</th>
                <th style="width: 80px; text-align: right;">Non Penjamin</th>
                <th style="width: 80px; text-align: right;">Jumlah Bayar</th>
                <th style="width: 75px; text-align: center;">Tgl Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totTagihan = 0; $totTanggungan = 0; $totNon = 0; $totBayar = 0;
            @endphp
            @forelse($items as $idx => $tk)
            @php
                $p = $tk->pendaftaran;
                $tagihan = $tk->total_tagihan ?: 0;
                $tanggungan = $tk->dijamin ?: $tagihan;
                $nonPenj = $tk->dibayar_pasien ?: 0;
                $jmlBayar = $tk->uang_diterima ?: $tagihan;
                $tglReg = $p->tanggal_daftar ? \Carbon\Carbon::parse($p->tanggal_daftar)->format('d-m-Y') : '14-10-2026';
                $tglBayar = $tk->waktu_bayar ? \Carbon\Carbon::parse($tk->waktu_bayar)->format('d-m-Y') : $tglReg;

                $totTagihan += $tagihan;
                $totTanggungan += $tanggungan;
                $totNon += $nonPenj;
                $totBayar += $jmlBayar;
            @endphp
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>
                    <strong>{{ $p->nomor_registrasi }}</strong><br>
                    <small>Tgl: {{ $tglReg }}</small>
                </td>
                <td>
                    Kode RM: {{ $p->pasien->no_rm ?? '-' }}<br>
                    <strong>{{ $p->pasien->nama_pasien ?? '-' }}</strong><br>
                    <small>Penjamin: {{ $p->penjamin->nama_penjamin ?? 'BPJS Kesehatan' }}</small>
                </td>
                <td>{{ $p->poliklinik->nama_poli ?? ($p->ruang_pemeriksaan ?: 'Klinik Umum 1') }}</td>
                <td>
                    Ditanggung Penjamin Penuh<br>
                    <small>Metode: {{ $tk->cara_bayar ?: 'Cash' }}</small>
                </td>
                <td class="text-right">{{ number_format($tagihan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($tanggungan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nonPenj, 0, ',', '.') }}</td>
                <td class="text-right"><strong>{{ number_format($jmlBayar, 0, ',', '.') }}</strong></td>
                <td class="text-center">{{ $tglBayar }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data pembayaran pasien</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($items) > 0)
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="5" class="text-center">TOTAL</td>
                <td class="text-right">{{ number_format($totTagihan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totTanggungan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totNon, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totBayar, 0, ',', '.') }}</td>
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

