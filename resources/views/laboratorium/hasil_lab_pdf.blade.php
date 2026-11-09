<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Order Lab - KLINIK TELAGASARI 24 JAM</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; color: #555; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 15px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f4f8fb; color: #337ab7; font-weight: bold; }
        .text-center { text-align: center; }
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

    <div class="title">Daftar Order Laboratorium</div>
    <div style="margin-bottom: 8px;">Tanggal: <strong>{{ date('d F Y', strtotime($tanggal)) }}</strong></div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">#</th>
                <th style="width: 110px;">Terima Order</th>
                <th>Pemeriksaan &amp; Spesimen</th>
                <th style="width: 100px;">Registrasi</th>
                <th style="width: 150px;">Pasien</th>
                <th style="width: 90px;">J.K &amp; Tgl.Lahir</th>
                <th style="width: 140px;">Klinik &amp; Dokter</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>Diterima</strong><br>
                    Pada: {{ $item->order_time_formatted ?? ($item->tanggal_daftar . ' ' . $item->jam_daftar) }}<br>
                    Pengirim: <em>Internal</em>
                </td>
                <td>
                    @foreach($item->transaksiLabs as $trx)
                    <div style="margin-bottom: 4px;">
                        • <strong>{{ $trx->masterLab->nama_pemeriksaan ?? '-' }}</strong><br>
                        &nbsp;&nbsp;Spesimen: {{ $trx->specimen_label ?? 'Blood specimen' }}
                    </div>
                    @endforeach
                </td>
                <td>
                    {{ $item->nomor_registrasi }}<br>
                    Tgl: {{ date('d.M.Y', strtotime($item->tanggal_daftar)) }}<br>
                    <span style="color: #f39c12;">{{ $item->jenis_kunjungan ?? 'Kunjungan Sakit' }}</span>
                </td>
                <td>
                    <strong>{{ strtoupper($item->pasien->nama_pasien ?? '-') }}</strong><br>
                    {{ $item->pasien->no_rm ?? '-' }}<br>
                    NIK: {{ $item->pasien->nik ?? '-' }}<br>
                    No.KIS: {{ $item->pasien->no_bpjs ?? '-' }}
                </td>
                <td>
                    {{ ($item->pasien && $item->pasien->jenis_kelamin == 'L') ? 'Laki-Laki' : 'Perempuan' }}<br>
                    {{ $item->pasien && $item->pasien->tanggal_lahir ? date('d.M.Y', strtotime($item->pasien->tanggal_lahir)) : '-' }}
                </td>
                <td>
                    {{ $item->poliklinik->nama_poli ?? 'Laboratorium' }}<br>
                    Dokter: {{ $item->dokter->nama_pegawai ?? 'dr. Fahmi Rizal' }}<br>
                    Perawat: {{ $item->nama_perawat ?: '-' }}<br>
                    Penjamin: {{ $item->penjamin->nama_penjamin ?? 'Pribadi' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data order laboratorium.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Karawang, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>dr. Fahmi Rizal</strong><br>Penanggung Jawab Laboratorium</p>
    </div>
</body>
</html>
