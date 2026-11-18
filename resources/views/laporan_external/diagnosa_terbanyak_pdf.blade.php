<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Kesakitan Terbanyak ({{ $tglAwalFormatted }} s/d {{ $tglAkhirFormatted }})</title>
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
            margin: 15px 0 10px 0;
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
        .summary-box {
            font-size: 11.5px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 1px solid #777;
            padding: 5px 8px;
            font-size: 10.5px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
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
        <h3>LAPORAN DATA KESAKITAN TERBANYAK</h3>
        <p>Periode: {{ $tglAwalFormatted }} s/d {{ $tglAkhirFormatted }}</p>
    </div>

    <div class="summary-box">
        Total Kunjungan Pasien : {{ number_format($total_kunjungan) }} , Total Diagnosa Pasien : {{ number_format($total_diagnosa) }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="40" class="text-center">No</th>
                <th>Jenis Penyakit</th>
                <th width="80" class="text-center">ICD 10</th>
                <th width="140" class="text-end">Jumlah Kasus Baru</th>
                <th width="140" class="text-end">Jumlah Kasus Lama</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data_diagnosa as $item)
            <tr>
                <td class="text-center">{{ $item['no'] }}</td>
                <td>{{ $item['jenis_penyakit'] }}</td>
                <td class="text-center">{{ $item['icd_10'] }}</td>
                <td class="text-end">{{ number_format($item['kasus_baru']) }}</td>
                <td class="text-end">{{ number_format($item['kasus_lama']) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px;">Tidak ada data diagnosa pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table style="border: none; margin-top: 35px; width: 100%;">
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
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
