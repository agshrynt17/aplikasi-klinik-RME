<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Kesakitan Berdasar Jenis &amp; Usia ({{ $bulanNama }} {{ $tahun }})</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 6mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5px;
            color: #333;
            margin: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 16px;
            text-transform: uppercase;
            color: #1a5c38;
        }
        .header p {
            margin: 1px 0;
            font-size: 10px;
            color: #555;
        }
        .report-title {
            text-align: center;
            margin: 10px 0 8px 0;
        }
        .report-title h3 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .report-title p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #444;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 1px solid #777;
            padding: 3px 4px;
            font-size: 8.5px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 12px; padding: 8px 12px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 4px; display: flex; gap: 10px; align-items: center;">
        <button onclick="window.print()" style="padding: 5px 12px; background: #00a65a; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" style="padding: 5px 12px; background: #6c757d; color: white; border: none; border-radius: 3px; cursor: pointer;">
            Tutup
        </button>
        <span style="font-size: 11px; color: #666;">Gunakan mode Landscape pada dialog cetak/PDF.</span>
    </div>

    <div class="header">
        <h2>KLINIK TELAGASARI 24 JAM</h2>
        <p>Jl. Raya Telagasari - Kosambi No. 12, Telagasari, Kab. Karawang, Jawa Barat</p>
        <p>Telp: (0267) 510123 | Email: kliniktelagasari24jam@gmail.com</p>
    </div>

    <div class="report-title">
        <h3>LAPORAN DATA KESAKITAN BERDASAR JENIS &amp; USIA</h3>
        <p>Periode: {{ $bulanNama }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="3" width="22">No</th>
                <th rowspan="3" style="min-width: 130px; text-align: left;">Jenis Penyakit</th>
                <th colspan="22">Jumlah Kasus Baru (Umur dan Jenis Kelamin)</th>
                <th colspan="2">Jumlah Kasus Lama</th>
                <th rowspan="3" width="45">Jumlah Total</th>
            </tr>
            <tr>
                <th colspan="2">0-7 Hr</th>
                <th colspan="2">8-28 Hr</th>
                <th colspan="2">1-11 Bln</th>
                <th colspan="2">1-4 Th</th>
                <th colspan="2">5-9 Th</th>
                <th colspan="2">10-14 Th</th>
                <th colspan="2">15-19 Th</th>
                <th colspan="2">20-44 Th</th>
                <th colspan="2">45-59 Th</th>
                <th colspan="2">&gt;60 Th</th>
                <th colspan="2">Jumlah</th>
                <th colspan="2" style="border-top: none;"></th>
            </tr>
            <tr>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
                <th>L</th><th>P</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_penyakit as $row)
            <tr>
                <td class="text-center">{{ $row['no'] }}</td>
                <td style="font-weight: 500;">{{ $row['jenis_penyakit'] }}</td>
                <td class="text-center">{{ $row['baru_0_7_hr_l'] }}</td>
                <td class="text-center">{{ $row['baru_0_7_hr_p'] }}</td>
                <td class="text-center">{{ $row['baru_8_28_hr_l'] }}</td>
                <td class="text-center">{{ $row['baru_8_28_hr_p'] }}</td>
                <td class="text-center">{{ $row['baru_1_11_bln_l'] }}</td>
                <td class="text-center">{{ $row['baru_1_11_bln_p'] }}</td>
                <td class="text-center">{{ $row['baru_1_4_th_l'] }}</td>
                <td class="text-center">{{ $row['baru_1_4_th_p'] }}</td>
                <td class="text-center">{{ $row['baru_5_9_th_l'] }}</td>
                <td class="text-center">{{ $row['baru_5_9_th_p'] }}</td>
                <td class="text-center">{{ $row['baru_10_14_th_l'] }}</td>
                <td class="text-center">{{ $row['baru_10_14_th_p'] }}</td>
                <td class="text-center">{{ $row['baru_15_19_th_l'] }}</td>
                <td class="text-center">{{ $row['baru_15_19_th_p'] }}</td>
                <td class="text-center">{{ $row['baru_20_44_th_l'] }}</td>
                <td class="text-center">{{ $row['baru_20_44_th_p'] }}</td>
                <td class="text-center">{{ $row['baru_45_59_th_l'] }}</td>
                <td class="text-center">{{ $row['baru_45_59_th_p'] }}</td>
                <td class="text-center">{{ $row['baru_gt_60_th_l'] }}</td>
                <td class="text-center">{{ $row['baru_gt_60_th_p'] }}</td>
                <td class="text-center" style="font-weight: bold;">{{ $row['jumlah_baru_l'] }}</td>
                <td class="text-center" style="font-weight: bold;">{{ $row['jumlah_baru_p'] }}</td>
                <td class="text-center">{{ $row['lama_l'] }}</td>
                <td class="text-center">{{ $row['lama_p'] }}</td>
                <td class="text-center" style="font-weight: bold;">{{ $row['jumlah_total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="border: none; margin-top: 25px; width: 100%;">
        <tr style="border: none;">
            <td style="border: none; width: 70%;"></td>
            <td style="border: none; width: 30%; text-align: center;">
                <p style="margin: 0;">Karawang, {{ date('d F Y') }}</p>
                <p style="margin: 3px 0 0 0; font-weight: bold;">Dokter Penanggung Jawab,</p>
                <div style="height: 50px;"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">dr. Fahmi Rizal</p>
                <p style="margin: 2px 0 0 0; font-size: 9px; color: #555;">SIP: 446.1/1029/Dinkes/2022</p>
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
