<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Histori Penyesuaian Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 6px 14px; background: #00c0ef; color: #fff; border: none; border-radius: 3px; cursor: pointer;">Cetak Dokumen</button>
        <button onclick="window.close()" style="padding: 6px 14px; background: #777; color: #fff; border: none; border-radius: 3px; cursor: pointer; margin-left: 5px;">Tutup</button>
    </div>

    <div class="header">
        <h2>Laporan Histori Penyesuaian Stok</h2>
        <p>Klinik RME / KONHAKES</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">#</th>
                <th>No Penyesuaian</th>
                <th>Tgl. Penyesuaian</th>
                <th>Gudang</th>
                <th>Tipe</th>
                <th class="text-center">Jumlah Item</th>
                <th>Tgl. Input</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adjustments as $idx => $item)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ $item->no_penyesuaian }}</td>
                <td>{{ $item->tanggal_penyesuaian ? $item->tanggal_penyesuaian->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->masterGudang->nama_gudang ?? $item->kode_gudang }}</td>
                <td>{{ $item->tipe }}</td>
                <td class="text-center">{{ $item->details->count() }}</td>
                <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $item->user_name ?? ($item->user->name ?? '-') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data penyesuaian stok.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
