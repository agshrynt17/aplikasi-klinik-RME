<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Puskesmas ({{ $tglAwalFormatted }} s/d {{ $tglAkhirFormatted }})</title>
    <style>
        @page {
            size: landscape;
            margin: 8mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 12px;
            position: relative;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 16px;
            text-transform: uppercase;
            color: #1a5c38;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #555;
        }
        .report-title {
            text-align: center;
            margin: 10px 0;
        }
        .report-title h3 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
            text-decoration: underline;
        }
        .report-title p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #777;
            padding: 4px 5px;
            font-size: 9.5px;
        }
        th {
            background-color: #e4e7ea;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 5mm; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 4px; display: flex; gap: 10px; align-items: center;">
        <button onclick="window.print()" style="padding: 6px 14px; background: #00a65a; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" style="padding: 6px 14px; background: #6c757d; color: white; border: none; border-radius: 3px; cursor: pointer;">
            Tutup
        </button>
        <span style="font-size: 12px; color: #666;">Gunakan Ctrl+P atau tombol di atas untuk mencetak atau menyimpan sebagai PDF (Landscape).</span>
    </div>

    <div class="header">
        <h2>KLINIK TELAGASARI 24 JAM</h2>
        <p>Jl. Raya Telagasari - Kosambi No. 12, Telagasari, Kab. Karawang, Jawa Barat</p>
        <p>Telp: (0267) 510123 | Email: kliniktelagasari24jam@gmail.com</p>
    </div>

    <div class="report-title">
        <h3>LAPORAN PUSKESMAS (SP2TP REGISTER RAWAT JALAN)</h3>
        <p>Periode: {{ $tglAwalFormatted }} s/d {{ $tglAkhirFormatted }}</p>
        @if($kecamatan || $desa || $provinsi || $kabupaten)
            <p style="font-size: 9.5px; color: #444;">Wilayah: {{ $desa ? 'Desa ' . $desa . ', ' : '' }} {{ $kecamatan ? 'Kec. ' . $kecamatan . ', ' : '' }} {{ $kabupaten ? 'Kab. ' . $kabupaten . ', ' : '' }} {{ $provinsi }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="25">No</th>
                <th width="75">Tgl &amp; Jam</th>
                <th width="75">RM#</th>
                <th width="85">RK#</th>
                <th width="65">Status</th>
                <th>Nama Pasien</th>
                <th width="105">NIK</th>
                <th width="90">JKN-KIS#</th>
                <th width="60">Tgl Lahir</th>
                <th width="85">U/Jk</th>
                <th>Alamat</th>
                <th width="75">No HP</th>
                <th width="40">Prolanis</th>
                <th width="35">PRB</th>
                <th width="80">Pembiaya</th>
                <th>Dokter</th>
                <th>Keluhan</th>
                <th>Diagnosa</th>
                <th width="65">Status Pulang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data_puskesmas as $idx => $item)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="text-center">{{ $item['tgl_jam'] }}</td>
                <td class="text-center">{{ $item['no_rm'] }}</td>
                <td class="text-center">{{ $item['no_rk'] }}</td>
                <td class="text-center">{{ $item['status_pasien'] }}</td>
                <td><strong>{{ $item['nama_pasien'] }}</strong></td>
                <td class="text-center">{{ $item['nik'] }}</td>
                <td class="text-center">{{ $item['no_jkn'] }}</td>
                <td class="text-center">{{ $item['tgl_lahir'] }}</td>
                <td class="text-center">{{ $item['u_jk'] }}</td>
                <td>{{ $item['alamat'] }}</td>
                <td class="text-center">{{ $item['no_hp'] }}</td>
                <td class="text-center">{{ $item['prolanis'] }}</td>
                <td class="text-center">{{ $item['prb'] }}</td>
                <td class="text-center">{{ $item['pembiaya'] }}</td>
                <td>{{ $item['dokter'] }}</td>
                <td>{{ $item['keluhan'] }}</td>
                <td>{{ $item['diagnosa'] }}</td>
                <td class="text-center">{{ $item['status_pulang'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="19" class="text-center" style="padding: 20px;">Tidak ada data kunjungan yang ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table style="border: none; margin-top: 25px; width: 100%;">
        <tr style="border: none;">
            <td style="border: none; width: 65%;">
                <p style="margin: 0; font-size: 9.5px; color: #555;">Total: <b>{{ $data_puskesmas->count() }}</b> pasien terdata pada periode ini.</p>
            </td>
            <td style="border: none; width: 35%; text-align: center;">
                <p style="margin: 0;">Karawang, {{ date('d F Y') }}</p>
                <p style="margin: 4px 0 0 0; font-weight: bold;">Dokter Penanggung Jawab,</p>
                <div style="height: 50px;"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">dr. Fahmi Rizal</p>
                <p style="margin: 2px 0 0 0; font-size: 9.5px; color: #555;">SIP: 446.1/1029/Dinkes/2022</p>
            </td>
        </tr>
    </table>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
