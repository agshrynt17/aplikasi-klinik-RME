<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Resep - KLINIK TELAGASARI 24 JAM</title>
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
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #555;
        }
        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 5px 6px;
            font-size: 10.5px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 6px 15px; background: #007bff; color: #fff; border: none; cursor: pointer; border-radius: 3px;">
            🖨 Cetak Dokumen
        </button>
        <button onclick="window.close()" style="padding: 6px 15px; background: #6c757d; color: #fff; border: none; cursor: pointer; border-radius: 3px; margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h2>{{ $profil->nama_klinik ?? 'KLINIK TELAGASARI 24 JAM' }}</h2>
        <p>{{ $profil->alamat ?? 'Jl. Raya Telagasari No. 12, Karawang, Jawa Barat' }} | Telp: {{ $profil->nomor_telepon ?? '0267-123456' }}</p>
    </div>

    <div class="title">LAPORAN PELAYANAN TRANSAKSI RESEP PASIEN</div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">No. Resep</th>
                <th width="8%">Antrian</th>
                <th width="10%">Tgl Resep</th>
                <th width="18%">Pasien & No RM</th>
                <th width="15%">Poli / Dokter</th>
                <th width="12%">Penjamin</th>
                <th width="11%">Status Proses</th>
                <th width="10%">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($data as $index => $row)
                @php
                    $pendaftaran = $row->pendaftaran;
                    $pasien = $row->pasien;
                    $grandTotal += $row->total_harga;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $row->nomor_resep }}</strong></td>
                    <td style="text-align: center;">{{ $row->antrian_nomor ?: ($pendaftaran->no_antrian ?? '-') }}</td>
                    <td style="text-align: center;">{{ $row->tanggal_resep ? \Carbon\Carbon::parse($row->tanggal_resep)->format('d/m/Y') : '-' }}</td>
                    <td>
                        <strong>{{ $pasien ? $pasien->nama_pasien : '-' }}</strong><br>
                        <small>RM: {{ $pasien ? $pasien->no_rm : '-' }}</small>
                    </td>
                    <td>
                        {{ $pendaftaran && $pendaftaran->poliklinik ? $pendaftaran->poliklinik->nama_poli : 'Klinik Umum 1' }}<br>
                        <small>{{ $row->dokter ? $row->dokter->nama_pegawai : '-' }}</small>
                    </td>
                    <td style="text-align: center;">{{ $pendaftaran && $pendaftaran->penjamin ? $pendaftaran->penjamin->nama_penjamin : 'BPJS Kesehatan' }}</td>
                    <td style="text-align: center;">{{ $row->status_proses }}</td>
                    <td style="text-align: right;">{{ number_format($row->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">Tidak ada data transaksi resep.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="8" style="text-align: right;">Total Keseluruhan:</td>
                <td style="text-align: right;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Karawang, {{ date('d F Y') }}<br>Petugas Farmasi,<br><br><br><br><strong>( Apt. SITI RAHMAH, S.Farm )</strong></p>
    </div>
</body>
</html>
