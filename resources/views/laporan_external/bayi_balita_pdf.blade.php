<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kunjungan Bayi & Balita ({{ $tglAwalFormatted }} s/d {{ $tglAkhirFormatted }})</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
            position: relative;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
            color: #1a5c38;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #555;
        }
        .report-title {
            text-align: center;
            margin: 15px 0;
        }
        .report-title h3 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
        }
        .report-title p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #777;
            padding: 5px 6px;
            font-size: 10.5px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .footer-sig {
            margin-top: 35px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .sig-box {
            width: 250px;
            text-align: center;
            font-size: 11px;
        }
        .sig-space {
            height: 60px;
        }
        @media print {
            .no-print { display: none !important; }
            body { margin: 10mm; }
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
        <span style="font-size: 12px; color: #666;">Gunakan Ctrl+P atau tombol di atas untuk mencetak atau menyimpan sebagai PDF.</span>
    </div>

    <div class="header">
        <h2>KLINIK TELAGASARI 24 JAM</h2>
        <p>Jl. Raya Telagasari - Kosambi No. 12, Telagasari, Kab. Karawang, Jawa Barat</p>
        <p>Telp: (0267) 510123 | Email: klinik.telagasari@konhakes.id</p>
    </div>

    <div class="report-title">
        <h3>LAPORAN KUNJUNGAN BAYI &amp; BALITA</h3>
        <p>Periode: {{ $tglAwalFormatted }} s/d {{ $tglAkhirFormatted }}</p>
        @if($kecamatan || $desa)
            <p style="font-size: 10px; color: #444;">Wilayah: {{ $desa ? 'Desa ' . $desa : '' }} {{ $kecamatan ? 'Kec. ' . $kecamatan : '' }} {{ $kabupaten ? 'Kab. ' . $kabupaten : '' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="75">Tanggal</th>
                <th width="125">NIK</th>
                <th width="150">Nama Anak</th>
                <th width="120">Nama Ibu</th>
                <th width="55">Usia</th>
                <th>Alamat</th>
                <th width="75">Telp</th>
                <th width="160">Diagnosa</th>
                <th width="75">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data_kunjungan as $idx => $item)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="text-center">{{ $item['tanggal'] }}</td>
                <td>{{ $item['nik'] }}</td>
                <td><strong>{{ strtoupper($item['nama_anak']) }}</strong></td>
                <td>{{ $item['nama_ibu'] }}</td>
                <td class="text-center">{{ $item['usia'] }}</td>
                <td style="font-size: 9.5px;">{{ $item['alamat'] }}</td>
                <td>{{ $item['telp'] ?: '-' }}</td>
                <td style="font-size: 10px;">{!! nl2br(e($item['diagnosa'])) !!}</td>
                <td>{{ $item['keterangan'] ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 20px;">Tidak ada data yang ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table style="border: none; margin-top: 30px; width: 100%;">
        <tr style="border: none;">
            <td style="border: none; width: 60%;"></td>
            <td style="border: none; width: 40%; text-align: center;">
                <p style="margin: 0;">Karawang, {{ date('d F Y') }}</p>
                <p style="margin: 4px 0 0 0; font-weight: bold;">Dokter Penanggung Jawab,</p>
                <div style="height: 55px;"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">dr. Fahmi Rizal</p>
                <p style="margin: 2px 0 0 0; font-size: 10px; color: #555;">SIP: 446.1/1029/Dinkes/2022</p>
            </td>
        </tr>
    </table>

    <script>
        // Auto trigger print on load if requested
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
