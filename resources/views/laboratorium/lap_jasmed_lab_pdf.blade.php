<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Insentif Lab - KLINIK TELAGASARI 24 JAM</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; color: #555; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 15px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #e4e4e4; color: #333; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 6px 15px; background: #00c0ef; border: none; color: white; border-radius: 3px; cursor: pointer;">
            Cetak / Download PDF
        </button>
    </div>

    <div class="header">
        <h2>KLINIK TELAGASARI 24 JAM</h2>
        <p>Jl. Raya Telagasari - Kosambi No. 12, Karawang, Jawa Barat</p>
        <p>Telp: (0267) 123456 | Email: info@kliniktelagasari.com</p>
    </div>

    <div class="title">Laporan Insentif Lab</div>
    <div style="margin-bottom: 8px;">Periode: <strong>{{ date('d-m-Y', strtotime($tglAwal)) }} s/d {{ date('d-m-Y', strtotime($tglAkhir)) }}</strong></div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">#</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 95px;">No Reg</th>
                <th>Nama Pasien</th>
                <th style="width: 75px;">Kode RM</th>
                <th style="width: 80px;">Penjamin</th>
                <th style="width: 120px;">Dokter</th>
                <th>Nama Lab</th>
                <th style="width: 65px;" class="text-right">Tarif Lab</th>
                <th style="width: 55px;" class="text-right">Jasa Medis</th>
            </tr>
        </thead>
        <tbody>
            @php $totalTarif = 0; $totalJasa = 0; @endphp
            @forelse($transaksis as $index => $item)
            @php
                $tarif = $item->tarif ?? 0;
                $jasa = 0;
                $totalTarif += $tarif;
                $totalJasa += $jasa;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->pendaftaran->tanggal_daftar ?? '-' }}</td>
                <td>{{ $item->pendaftaran->nomor_registrasi ?? '-' }}</td>
                <td><strong>{{ strtoupper($item->pendaftaran->pasien->nama_pasien ?? '-') }}</strong></td>
                <td>{{ $item->pendaftaran->pasien->no_rm ?? '-' }}</td>
                <td>{{ $item->pendaftaran->penjamin->nama_penjamin ?? 'Pribadi' }}</td>
                <td>{{ $item->pendaftaran->dokter->nama_pegawai ?? 'dr. Fahmi Rizal' }}</td>
                <td>{{ $item->masterLab->nama_pemeriksaan ?? '-' }}</td>
                <td class="text-right">{{ number_format($tarif) }}</td>
                <td class="text-right">0</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data transaksi lab pada periode ini.</td>
            </tr>
            @endforelse
            <tr style="background-color: #f7f7f7; font-weight: bold;">
                <td colspan="8" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($totalTarif) }}</td>
                <td class="text-right">{{ number_format($totalJasa) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Karawang, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>dr. Fahmi Rizal</strong><br>Penanggung Jawab Laboratorium</p>
    </div>
</body>
</html>
