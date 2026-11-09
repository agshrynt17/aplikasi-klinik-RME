<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Penjualan Apotek - {{ $faskes->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        .header-print {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .clinic-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .clinic-address {
            font-size: 11px;
            color: #555;
        }
        .report-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #777;
            padding: 5px 6px;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .footer-print {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .sign-box {
            width: 220px;
            text-align: center;
        }
        .sign-space {
            height: 60px;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 10px; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 14px; font-weight: bold; cursor: pointer; background: #00a65a; color: #fff; border: none; border-radius: 3px;">
            🖨️ Cetak / Print Laporan
        </button>
        <button onclick="window.close()" style="padding: 6px 14px; font-weight: bold; cursor: pointer; background: #777; color: #fff; border: none; border-radius: 3px; margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class="header-print">
        <div class="clinic-name">{{ $faskes->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}</div>
        <div class="clinic-address">{{ $faskes->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat' }}</div>
        <div class="clinic-address">Telp: {{ $faskes->telepon ?? '(0264) 8303763' }} | Email: {{ $faskes->email ?? 'kliniktelagasari24jam@gmail.com' }}</div>
    </div>

    <div class="report-title">
        LAPORAN TRANSAKSI PENJUALAN APOTEK
        @if(request('tgl_awal') && request('tgl_akhir'))
            <div style="font-size: 11px; font-weight: normal; margin-top: 4px;">
                Periode: {{ \Carbon\Carbon::parse(request('tgl_awal'))->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse(request('tgl_akhir'))->format('d/m/Y') }}
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 75px;">Status Bayar</th>
                <th style="width: 110px;">Kode Transaksi</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Nama Pelanggan</th>
                <th style="width: 85px;">Telp</th>
                <th style="width: 70px;">Cara Bayar</th>
                <th style="width: 90px; text-align: right;">Total Transaksi</th>
                <th style="width: 90px; text-align: right;">Jumlah Bayar</th>
                <th style="width: 80px; text-align: right;">Kembali</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalAll = 0;
                $dibayarAll = 0;
                $kembaliAll = 0;
            @endphp
            @forelse($data as $index => $item)
                @php
                    $tot = $item->grand_total ?: $item->total_harga;
                    $grandTotalAll += $tot;
                    $dibayarAll += $item->dibayar;
                    $kembaliAll += $item->kembalian;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->status_bayar ?: 'Lunas' }}</td>
                    <td><strong>{{ $item->kode_transaksi ?: $item->nomor_faktur }}</strong></td>
                    <td>{{ $item->tanggal_penjualan ? \Carbon\Carbon::parse($item->tanggal_penjualan)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->pelanggan_nama ?: ($item->nama_pembeli ?: 'UMUM') }}</td>
                    <td>{{ $item->pelanggan_telp ?: '-' }}</td>
                    <td>{{ $item->cara_bayar ?: 'Tunai' }}</td>
                    <td class="text-end">Rp {{ number_format($tot, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->dibayar, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->kembalian, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-3">Tidak ada data transaksi ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        @if($data->count() > 0)
            <tfoot>
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td colspan="7" class="text-end">TOTAL REKAPITULASI:</td>
                    <td class="text-end">Rp {{ number_format($grandTotalAll, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($dibayarAll, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($kembaliAll, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer-print">
        <div class="sign-box">
            <div>Mengetahui,</div>
            <div>Penanggung Jawab Apotek</div>
            <div class="sign-space"></div>
            <div style="font-weight: bold; text-decoration: underline;">
                {{ $faskes->penanggung_jawab_apotek ?? 'apt. Fariddon Ricky Wahyudi, S.Farm' }}
            </div>
            <div style="font-size: 9px; color: #555;">SIPA: {{ $faskes->no_izin_apotek ?? '123/019/X/KLINIK-OSS/XXX' }}</div>
        </div>

        <div class="sign-box">
            <div>Karawang, {{ date('d F Y') }}</div>
            <div>Petugas Kasir Apotek</div>
            <div class="sign-space"></div>
            <div style="font-weight: bold; text-decoration: underline;">
                ( {{ auth()->user()->name ?? 'dr. FAHMI RIZAL' }} )
            </div>
        </div>
    </div>

</body>
</html>
