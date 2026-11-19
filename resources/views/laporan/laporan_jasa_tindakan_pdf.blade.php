<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Jasa Tindakan Medis - {{ $tanggal }}</title>
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
        <h3 style="margin-top: 8px;">LAPORAN JASA TINDAKAN MEDIS</h3>
        <p>Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th style="width: 75px; text-align: center;">Tanggal</th>
                <th style="width: 50px; text-align: center;">Jam</th>
                <th style="width: 90px;">No Reg</th>
                <th>Nama Pasien</th>
                <th style="width: 80px;">Kode RM</th>
                <th style="width: 85px;">Penjamin</th>
                <th style="width: 130px;">Dokter</th>
                <th style="width: 150px;">Tindakan</th>
                <th style="width: 75px; text-align: right;">Jasa Medis</th>
            </tr>
        </thead>
        <tbody>
            @php $totJasa = 0; @endphp
            @forelse($items as $idx => $item)
            @php
                $p = $item->pendaftaran;
                $tgl = $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : $tanggal;
                $jam = $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('H:i') : '08:00';
                $jasa = $item->subtotal ?: 0;
                $totJasa += $jasa;
            @endphp
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="text-center">{{ $tgl }}</td>
                <td class="text-center">{{ $jam }}</td>
                <td>{{ $p->nomor_registrasi ?? '-' }}</td>
                <td>{{ $p->pasien->nama_pasien ?? '-' }}</td>
                <td>{{ $p->pasien->no_rm ?? '-' }}</td>
                <td>{{ $p->penjamin->nama_penjamin ?? 'BPJS Kesehatan' }}</td>
                <td>{{ $item->pelaksana->nama_pegawai ?? ($p->dokter->nama_pegawai ?? '-') }}</td>
                <td>{{ $item->tindakan->nama_tindakan ?? '-' }}</td>
                <td class="text-right">{{ number_format($jasa, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data tindakan</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($items) > 0)
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="9" class="text-center">TOTAL</td>
                <td class="text-right">{{ number_format($totJasa, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <div class="signature">
            <p>Karawang, {{ date('d-m-Y') }}<br>Penanggung Jawab Medis,</p>
            <br><br><br>
            <p>( .................................................... )</p>
        </div>
    </div>
</body>
</html>

