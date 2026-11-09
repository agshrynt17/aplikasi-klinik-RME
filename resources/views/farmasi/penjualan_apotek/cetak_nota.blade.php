<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan Apotek - {{ $penjualan->kode_transaksi ?: $penjualan->nomor_faktur }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        .struk-card {
            background-color: #fff;
            width: 320px;
            margin: 0 auto;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: 1px dashed #ccc;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }
        .double-divider {
            border-bottom: 2px dashed #000;
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }
        table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .no-print {
            text-align: center;
            margin-bottom: 15px;
        }
        .btn-print {
            background-color: #00a65a;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 13px;
        }
        .btn-close {
            background-color: #777;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 13px;
            margin-left: 8px;
        }
        @media print {
            body {
                background-color: #fff;
                padding: 0;
                margin: 0;
            }
            .struk-card {
                width: 100%;
                box-shadow: none;
                border: none;
                padding: 5px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Struk (Print)</button>
        <button class="btn-close" onclick="window.close()">Tutup Jendela</button>
    </div>

    <div class="struk-card">
        <div class="text-center">
            <div class="fw-bold" style="font-size: 14px; text-transform: uppercase;">
                {{ $faskes->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM' }}
            </div>
            <div style="font-size: 10px; margin-top: 2px;">
                {{ $faskes->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Karawang' }}
            </div>
            <div style="font-size: 10px;">
                Telp: {{ $faskes->telepon ?? '(0264) 8303763' }}
            </div>
            @if(!empty($faskes->no_izin_apotek))
                <div style="font-size: 9.5px; color: #333;">
                    SIPA: {{ $faskes->no_izin_apotek }}
                </div>
            @endif
            @if(!empty($faskes->penanggung_jawab_apotek))
                <div style="font-size: 9.5px; color: #333;">
                    Apoteker: {{ $faskes->penanggung_jawab_apotek }}
                </div>
            @endif
        </div>

        <div class="double-divider"></div>

        <table>
            <tr>
                <td style="width: 75px;">No. Nota</td>
                <td>: <strong>{{ $penjualan->kode_transaksi ?: $penjualan->nomor_faktur }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>: {{ $penjualan->pelanggan_nama ?: ($penjualan->nama_pembeli ?: 'UMUM') }}</td>
            </tr>
            @if($penjualan->pasien)
                <tr>
                    <td>No. RM</td>
                    <td>: {{ $penjualan->pasien->no_rm }}</td>
                </tr>
            @endif
            @if($penjualan->dokter)
                <tr>
                    <td>Dokter</td>
                    <td>: {{ $penjualan->dokter->nama_pegawai }}</td>
                </tr>
            @endif
            <tr>
                <td>Cara Bayar</td>
                <td>: {{ $penjualan->cara_bayar ?: 'Tunai' }} ({{ $penjualan->status_bayar ?: 'Lunas' }})</td>
            </tr>
        </table>

        <div class="divider"></div>

        {{-- Detail Items --}}
        <table>
            @foreach($penjualan->details as $item)
                <tr>
                    <td colspan="2" class="fw-bold">
                        {{ $item->masterBarang ? $item->masterBarang->nama_barang : 'Barang' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; font-size: 10.5px;">
                        {{ $item->jumlah }} {{ $item->kemasan ?: '' }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                        @if($item->diskon > 0)
                            (-Rp {{ number_format($item->diskon, 0, ',', '.') }})
                        @endif
                    </td>
                    <td class="text-end fw-bold" style="font-size: 11px;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                @if($item->cara_minum || $item->dosis)
                    <tr>
                        <td colspan="2" style="padding-left: 10px; font-size: 10px; font-style: italic; color: #444;">
                            Aturan: {{ $item->cara_minum }} {{ $item->dosis ? '('.$item->dosis.')' : '' }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>

        <div class="divider"></div>

        {{-- Summary --}}
        <table>
            <tr>
                <td>Total Belanja</td>
                <td class="text-end fw-bold">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
            </tr>
            @if($penjualan->diskon > 0)
                <tr>
                    <td>Diskon</td>
                    <td class="text-end text-danger">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if($penjualan->pembulatan != 0)
                <tr>
                    <td>Pembulatan</td>
                    <td class="text-end">Rp {{ number_format($penjualan->pembulatan, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="fw-bold" style="font-size: 12.5px;">
                <td>GRAND TOTAL</td>
                <td class="text-end">Rp {{ number_format($penjualan->grand_total ?: $penjualan->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Jumlah Bayar</td>
                <td class="text-end fw-bold">Rp {{ number_format($penjualan->dibayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-end fw-bold">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="double-divider"></div>

        <div class="text-center" style="font-size: 10px; margin-top: 6px;">
            <div>*** TERIMA KASIH ***</div>
            <div>Semoga Lekas Sembuh</div>
            <div style="font-size: 9px; color: #555; margin-top: 3px;">
                Barang yang sudah dibeli tidak dapat ditukar/dikembalikan
            </div>
        </div>
    </div>

    <script>
        // Auto print on open
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
