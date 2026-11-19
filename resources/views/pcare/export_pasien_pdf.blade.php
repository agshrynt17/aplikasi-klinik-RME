<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Pasien BPJS</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 0; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 5px 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #00c0ef; color: #fff; border: 1px solid #00acd6; border-radius: 3px; cursor: pointer;">
            🖨 Cetak Dokumen
        </button>
    </div>

    <div class="header">
        <h2>Data Pasien BPJS</h2>
        <p>Klinik Telagasari 24 Jam — Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">#</th>
                <th style="width: 90px;">Kode RM</th>
                <th style="width: 100px;">Nomor KIS</th>
                <th>Nama Pasien</th>
                <th style="width: 75px;">Tgl Lahir</th>
                <th style="width: 60px;">J/K</th>
                <th style="width: 90px;">No. HP</th>
                <th>Alamat</th>
                <th style="width: 100px;">Nama Ibu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pasiens as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $p->no_rm }}</td>
                <td>{{ $p->no_bpjs }}</td>
                <td><b>{{ $p->nama_pasien }}</b></td>
                <td>{{ $p->tanggal_lahir ? $p->tanggal_lahir->format('d.M.Y') : '-' }}</td>
                <td>{{ $p->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-Laki' }}</td>
                <td>{{ $p->no_hp ?? '-' }}</td>
                <td>{{ $p->alamat ?? '-' }}</td>
                <td>{{ $p->nama_ibu_kandung ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

