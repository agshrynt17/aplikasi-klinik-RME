<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Ensure initial settings matching KONHAKES exist
     */
    private function ensureInitialData()
    {
        if (GeneralSetting::count() === 0) {
            $settings = [
                // Kwitansi
                [
                    'kategori'     => 'Kwitansi',
                    'nama_setting' => 'Pembulatan akhir ke ratusan pada nominal akhir subtotal',
                    'key'          => 'kwitansi_pembulatan',
                    'nilai'        => 'Ya',
                    'tipe_input'   => 'select',
                    'opsi'         => ['Ya', 'Tidak'],
                    'urutan'       => 1,
                ],
                [
                    'kategori'     => 'Kwitansi',
                    'nama_setting' => 'Tampilkan harga obat pada kwitansi',
                    'key'          => 'kwitansi_tampilkan_harga_obat',
                    'nilai'        => 'Ya',
                    'tipe_input'   => 'select',
                    'opsi'         => ['Ya', 'Tidak'],
                    'urutan'       => 2,
                ],
                [
                    'kategori'     => 'Kwitansi',
                    'nama_setting' => 'Ukuran kertas kwitansi',
                    'key'          => 'kwitansi_ukuran_kertas',
                    'nilai'        => 'A5 (14,8 x 21,0 cm)',
                    'tipe_input'   => 'select',
                    'opsi'         => ['A5 (14,8 x 21,0 cm)', 'A4 (21,0 x 29,7 cm)', 'Struk 76 mm(7,6 cm x 12,5 cm)', 'Struk 58 mm'],
                    'urutan'       => 3,
                ],

                // Kwitansi Penjualan Apotek
                [
                    'kategori'     => 'Kwitansi Penjualan Apotek',
                    'nama_setting' => 'Pembulatan akhir ke ratusan pada nominal total',
                    'key'          => 'apotek_pembulatan',
                    'nilai'        => 'Ya',
                    'tipe_input'   => 'select',
                    'opsi'         => ['Ya', 'Tidak'],
                    'urutan'       => 1,
                ],
                [
                    'kategori'     => 'Kwitansi Penjualan Apotek',
                    'nama_setting' => 'Tampilkan harga obat pada kwitansi',
                    'key'          => 'apotek_tampilkan_harga_obat',
                    'nilai'        => 'Tidak',
                    'tipe_input'   => 'select',
                    'opsi'         => ['Ya', 'Tidak'],
                    'urutan'       => 2,
                ],
                [
                    'kategori'     => 'Kwitansi Penjualan Apotek',
                    'nama_setting' => 'Ukuran kertas kwitansi',
                    'key'          => 'apotek_ukuran_kertas',
                    'nilai'        => 'Struk 76 mm(7,6 cm x 12,5 cm)',
                    'tipe_input'   => 'select',
                    'opsi'         => ['Struk 76 mm(7,6 cm x 12,5 cm)', 'Struk 58 mm', 'A5 (14,8 x 21,0 cm)', 'A4 (21,0 x 29,7 cm)'],
                    'urutan'       => 3,
                ],

                // Label Obat
                [
                    'kategori'     => 'Label Obat',
                    'nama_setting' => 'Ukuran Label',
                    'key'          => 'label_obat_ukuran',
                    'nilai'        => 'Kertas Label 40mm x 60mm',
                    'tipe_input'   => 'select',
                    'opsi'         => ['Kertas Label 40mm x 60mm', 'Kertas Label 50mm x 20mm', 'Kertas Label 30mm x 20mm'],
                    'urutan'       => 1,
                ],
            ];

            foreach ($settings as $s) {
                GeneralSetting::create($s);
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureInitialData();

        $groupedSettings = GeneralSetting::orderBy('id', 'asc')
            ->get()
            ->groupBy('kategori');

        return view('setting_lainnya', compact('groupedSettings'));
    }

    public function show($id)
    {
        $setting = GeneralSetting::findOrFail($id);
        return response()->json($setting);
    }

    public function update(Request $request, $id = null)
    {
        $targetId = $id ?: $request->id;
        $setting = GeneralSetting::findOrFail($targetId);

        $request->validate([
            'nilai' => 'required',
        ]);

        $setting->update([
            'nilai' => $request->nilai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan ' . $setting->nama_setting . ' berhasil diperbarui.',
            'data'    => $setting,
        ]);
    }
}
