<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan Mutasi Barang - {{ $mutasi->nomor_mutasi }} - KLINIK TELAGASARI 24 JAM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11.5px;
            color: #333;
            margin: 25px;
        }
        .header-print {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .clinic-name {
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .clinic-address {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }
        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 6px;
            vertical-align: top;
            font-size: 11.5px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.items-table th, table.items-table td {
            border: 1px solid #777;
            padding: 6px 8px;
            font-size: 11px;
        }
        table.items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .footer-print {
            display: flex;
            justify-content: space-between;
            margin-top: 35px;
        }
        .sign-box {
            width: 220px;
            text-align: center;
            font-size: 11.5px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 15px; background: #3c8dbc; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
            🖨️ Cetak / Print Surat Jalan
        </button>
        <button onclick="window.close()" style="padding: 6px 15px; background: #777; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class="header-print">
        <div class="clinic-name">{{ $profil->nama_klinik ?? 'KLINIK TELAGASARI 24 JAM' }}</div>
        <div class="clinic-address">
            {{ $profil->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat' }} | Telp: {{ $profil->telepon ?? '0812-3456-7890' }}
        </div>
    </div>

    <div class="doc-title">SURAT JALAN MUTASI BARANG ANTAR GUDANG</div>

    <table class="meta-table">
        <tr>
            <td style="width: 18%;"><strong>Nomor Mutasi</strong></td>
            <td style="width: 32%;">: <strong>{{ $mutasi->nomor_mutasi }}</strong></td>
            <td style="width: 18%;"><strong>Gudang Asal</strong></td>
            <td style="width: 32%;">: <strong>{{ $mutasi->gudangAsal ? $mutasi->gudangAsal->nama_gudang : 'GUDANG AWAL' }}</strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal Mutasi</strong></td>
            <td>: {{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d F Y') }}</td>
            <td><strong>Gudang Tujuan</strong></td>
            <td>: <strong>{{ $mutasi->gudangTujuan ? $mutasi->gudangTujuan->nama_gudang : ($mutasi->unit_tujuan ?: 'Gudang Farmasi') }}</strong></td>
        </tr>
        <tr>
            <td><strong>Petugas Pengirim</strong></td>
            <td>: {{ $mutasi->petugas ?? 'dr. FAHMI RIZAL' }}</td>
            <td><strong>Status Penerimaan</strong></td>
            <td>: {{ $mutasi->sudah_diterima ?? ($mutasi->status === 'Diterima' ? 'Sudah Terima' : 'Belum Terima') }}</td>
        </tr>
        <tr>
            <td><strong>Keterangan</strong></td>
            <td>: {{ $mutasi->keterangan ?? '-' }}</td>
            <td><strong>Penerima / Tgl Terima</strong></td>
            <td>: {{ $mutasi->penerima ? ($mutasi->penerima . ' (' . \Carbon\Carbon::parse($mutasi->tanggal_terima)->format('d-m-Y') . ')') : 'Belum Diterima' }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 35px;" class="text-center">No</th>
                <th style="width: 130px;">Kode Barang</th>
                <th>Nama Barang / Obat</th>
                <th style="width: 90px;" class="text-center">Kemasan</th>
                <th style="width: 160px;">Batch & Kadaluarsa</th>
                <th style="width: 100px;" class="text-end">Jumlah Keluar</th>
            </tr>
        </thead>
        <tbody>
            @php $totalQty = 0; @endphp
            @forelse($mutasi->details as $idx => $d)
                @php $totalQty += $d->jumlah; @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $d->masterBarang ? ($d->masterBarang->kode_barang ?? '-') : '-' }}</td>
                    <td><strong>{{ $d->masterBarang ? $d->masterBarang->nama_barang : ('Item #' . $d->master_barang_id) }}</strong></td>
                    <td class="text-center">{{ $d->kemasan ?? ($d->masterBarang ? $d->masterBarang->kemasan : '-') }}</td>
                    <td>
                        @if($d->nomor_batch)<div>Batch: <strong>{{ $d->nomor_batch }}</strong></div>@endif
                        @if($d->tanggal_kadaluarsa)<div>Exp: {{ \Carbon\Carbon::parse($d->tanggal_kadaluarsa)->format('d-m-Y') }}</div>@endif
                        @if(!$d->nomor_batch && !$d->tanggal_kadaluarsa)-@endif
                    </td>
                    <td class="text-end fw-bold">{{ number_format($d->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-3">Tidak ada detail item mutasi.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="5" class="text-end">TOTAL JUMLAH KELUAR:</td>
                <td class="text-end">{{ number_format($totalQty, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-print">
        <div class="sign-box">
            Pengirim (Gudang Asal),<br>
            <strong>Petugas Logistik Farmasi</strong>
            <br><br><br><br>
            ( {{ $mutasi->petugas ?? 'dr. FAHMI RIZAL' }} )
        </div>
        <div class="sign-box">
            Pembawa / Pengantar,<br>
            <strong>Kurir Internal</strong>
            <br><br><br><br>
            ( ......................................... )
        </div>
        <div class="sign-box">
            Penerima (Gudang Tujuan),<br>
            <strong>Kepala Unit / Petugas</strong>
            <br><br><br><br>
            ( {{ $mutasi->penerima ?: '.........................................' }} )
        </div>
    </div>
</body>
</html>
