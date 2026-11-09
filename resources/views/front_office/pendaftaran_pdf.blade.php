<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendaftaran Rawat Jalan - {{ $tanggal }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 6px 12px; background: #00a65a; color: white; border: none; cursor: pointer; border-radius: 4px;">
            Cetak Dokumen
        </button>
        <button onclick="window.close()" style="padding: 6px 12px; background: #6c757d; color: white; border: none; cursor: pointer; border-radius: 4px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h2>KLINIK TELAGASARI 24 JAM</h2>
        <p>Jl. Raya Telagasari No. 12, Karawang, Jawa Barat | Telp: (0267) 123456</p>
        <p><strong>LAPORAN PENDAFTARAN PASIEN RAWAT JALAN</strong></p>
        <p>Tanggal: {{ date('d-m-Y', strtotime($tanggal)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="15%">No. Registrasi</th>
                <th width="12%">No. RM</th>
                <th width="20%">Nama Pasien</th>
                <th width="12%">Penjamin</th>
                <th width="18%">Poli &amp; Dokter</th>
                <th width="10%">Status</th>
                <th width="10%">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftarans as $idx => $p)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td><strong>{{ $p->nomor_registrasi ?: $p->no_rawat }}</strong></td>
                <td>{{ $p->pasien ? $p->pasien->no_rm : '-' }}</td>
                <td><strong>{{ $p->pasien ? $p->pasien->nama_pasien : '-' }}</strong></td>
                <td>{{ $p->penjamin ? $p->penjamin->nama_penjamin : 'Pribadi' }}</td>
                <td>
                    {{ $p->poliklinik ? $p->poliklinik->nama_poli : '-' }}<br>
                    <small>{{ $p->dokter ? $p->dokter->nama_pegawai : '-' }}</small>
                </td>
                <td>{{ $p->status_periksa }}</td>
                <td>{{ $p->jam_daftar ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data pendaftaran pada tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; text-align: center; width: 200px;">
        <p>Karawang, {{ date('d F Y') }}</p>
        <p>Petugas Pendaftaran,</p>
        <br><br><br>
        <p><strong>( ..................................... )</strong></p>
    </div>
</body>
</html>
