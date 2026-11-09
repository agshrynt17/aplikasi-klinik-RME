@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Pilih Peserta Skrining (Tanpa Front Office)')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Pilih Peserta Skrining (Tanpa Front Office)
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/skrining/index" style="color: #777; text-decoration: none;">Pendaftaran Skrining</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Pilih Peserta</span>
        </div>
    </div>

    <!-- Filter Card -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #f39c12; border-radius: 3px; margin-top: 15px; margin-bottom: 20px;">
        <div class="d-flex justify-content-between align-items-center" style="padding: 9px 15px; border-bottom: 1px solid #f4f4f4;">
            <span style="font-size: 12.5px; font-weight: 600; color: #333;">Cari Data Pasien</span>
            <a href="/skrining/index" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; font-size: 11.5px; padding: 2px 14px; border-radius: 3px;">
                Kembali
            </a>
        </div>
        <div style="padding: 15px;">
            <form method="GET" action="/skrining/index-pasien" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kata Kunci Pencarian</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Ketik No. RM / Nama Pasien / NIK / No BPJS..." value="{{ request('search') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; font-size: 12px; padding: 0 14px; border-radius: 3px;">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                    <a href="/skrining/index-pasien" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div style="padding: 9px 15px; font-size: 12px; color: #444; font-weight: 600; border-bottom: 1px solid #eef2f5;">
            Menampilkan {{ $pasiens->firstItem() ?? 0 }}-{{ $pasiens->lastItem() ?? 0 }} dari {{ $pasiens->total() }} pasien.
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 11.5px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; border-bottom: 2px solid #d2d6de;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; padding: 8px 6px;">#</th>
                        <th class="text-center" style="width: 75px; padding: 8px 6px;"></th>
                        <th style="padding: 8px 12px; width: 130px; font-weight: 600; color: #337ab7;">No. Rekam Medis</th>
                        <th style="padding: 8px 12px; width: 220px; font-weight: 600; color: #333;">Nama &amp; J.K</th>
                        <th style="padding: 8px 12px; width: 160px; font-weight: 600; color: #333;">NIK / BPJS</th>
                        <th style="padding: 8px 10px; width: 110px; font-weight: 600; color: #333;">Tgl Lahir</th>
                        <th style="padding: 8px 12px; font-weight: 600; color: #333;">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasiens as $index => $item)
                    @php
                        $gender = ($item->jenis_kelamin == 'L' || $item->jenis_kelamin == 'Laki-laki') ? 'Laki-Laki' : 'Perempuan';
                    @endphp
                    <tr class="align-middle">
                        <td class="text-center" style="padding: 8px 6px; color: #555;">{{ $pasiens->firstItem() + $index }}</td>
                        <td class="text-center" style="padding: 8px 6px;">
                            <a href="/skrining/create-pasien/{{ $item->id }}" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; padding: 2px 10px; font-size: 11px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                <i class="fas fa-check"></i> Pilih
                            </a>
                        </td>
                        <td style="padding: 8px 12px; font-weight: 600; color: #337ab7;">
                            {{ $item->no_rm }}
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="font-weight: 600; color: #333;">{{ strtoupper($item->nama_pasien) }}</div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">{{ $gender }}</div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>NIK: {{ $item->nik ?? '-' }}</div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">BPJS: {{ $item->no_bpjs ?? '-' }}</div>
                        </td>
                        <td style="padding: 8px 10px;">
                            {{ $item->tanggal_lahir ? date('d M Y', strtotime($item->tanggal_lahir)) : '-' }}
                        </td>
                        <td style="padding: 8px 12px;">
                            {{ $item->alamat ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-1"></i> Tidak ada data pasien ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pasiens->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $pasiens->firstItem() }} sampai {{ $pasiens->lastItem() }} dari {{ $pasiens->total() }} entri
            </div>
            <div>
                {{ $pasiens->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
