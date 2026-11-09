@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Pendaftaran Skrining')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Pendaftaran Skrining
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Pendaftaran Skrining</span>
        </div>
    </div>

    <!-- Thin Divider / Header Line -->
    <div style="height: 1px; background: #eee; margin-bottom: 15px; margin-top: 10px;"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    <!-- Action Toolbar (1:1 PROFASKES Screenshot 1) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="btn text-white btn-sm" id="btnToggleFilter" style="background-color: #f39c12; border-color: #e08e0b; font-size: 12px; padding: 6px 14px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-search"></i> Cari data
            </button>
            <a href="/skrining/index" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; color: #444; border-radius: 3px;" title="Reset / Segarkan">
                <i class="fas fa-sync-alt"></i>
            </a>
            <button type="button" class="btn text-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalPilihPeserta" style="background-color: #00a65a; border-color: #008d4c; font-size: 12px; padding: 6px 14px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-plus"></i> Pendaftaran Skrining
            </button>
        </div>
        <div>
            <a href="/skrining/export-excel" class="btn text-white btn-sm" style="background-color: #00c0ef; border-color: #00acd6; font-size: 12px; padding: 6px 14px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
                <i class="far fa-file-excel"></i> Export Data (Excel)
            </a>
        </div>
    </div>

    <!-- Collapsible Search Filter -->
    <div id="filterSearchCard" style="display: {{ request('keyword') || request('tanggal') ? 'block' : 'none' }}; background: #fdfdfd; border: 1px solid #d2d6de; border-radius: 3px; padding: 12px 15px; margin-bottom: 15px;">
        <form method="GET" action="/skrining/index" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 2px;">Cari Pasien / No. RM</label>
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Nama / No RM..." value="{{ request('keyword') }}" style="height: 32px; font-size: 12px;">
            </div>
            <div class="col-md-2">
                <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 2px;">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" style="height: 32px; font-size: 12px;">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; height: 32px; font-size: 12px; padding: 0 14px;">
                    <i class="fas fa-search me-1"></i> Terapkan
                </button>
                <a href="/skrining/index" class="btn btn-sm btn-light border" style="height: 32px; font-size: 12px;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table Card Container (1:1 PROFASKES Screenshot 1) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #00c0ef; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 12px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; border-bottom: 2px solid #d2d6de;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; padding: 9px 6px; font-weight: 600; color: #333;">#</th>
                        <th style="padding: 9px 12px; width: 130px; font-weight: 600; color: #337ab7;">
                            Tgl &amp; Jam <i class="fas fa-arrows-alt-v ms-1" style="font-size: 10px; color: #888;"></i>
                        </th>
                        <th style="padding: 9px 12px; width: 180px; font-weight: 600; color: #337ab7;">Nama Skrining</th>
                        <th style="padding: 9px 12px; width: 220px; font-weight: 600; color: #337ab7;">Nama &amp; No.RM</th>
                        <th style="padding: 9px 12px; width: 150px; font-weight: 600; color: #333;">J.K &amp; Tgl.Lahir</th>
                        <th style="padding: 9px 12px; width: 170px; font-weight: 600; color: #337ab7;">Usia &amp; Kelompok Usia</th>
                        <th style="padding: 9px 12px; width: 200px; font-weight: 600; color: #337ab7;">Hasil Skrining</th>
                        <th class="text-center" style="padding: 9px 12px; width: 100px; font-weight: 600; color: #337ab7;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skrinings as $index => $item)
                    @php
                        $pasien = $item->pasien;
                        $gender = ($pasien && ($pasien->jenis_kelamin == 'L' || $pasien->jenis_kelamin == 'Laki-laki')) ? 'Laki-Laki' : 'Perempuan';
                        $tglLahir = $pasien && $pasien->tanggal_lahir ? date('d/m/Y', strtotime($pasien->tanggal_lahir)) : '-';
                        $usiaTahun = $pasien && $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->age : null;
                        $kelompokUsia = '-';
                        if ($usiaTahun !== null) {
                            if ($usiaTahun <= 5) $kelompokUsia = 'Balita';
                            elseif ($usiaTahun <= 12) $kelompokUsia = 'Anak';
                            elseif ($usiaTahun <= 18) $kelompokUsia = 'Remaja';
                            elseif ($usiaTahun <= 59) $kelompokUsia = 'Dewasa';
                            else $kelompokUsia = 'Lansia';
                        }
                    @endphp
                    <tr class="align-middle">
                        <td class="text-center" style="padding: 8px 6px; color: #555;">{{ $skrinings->firstItem() + $index }}</td>
                        <td style="padding: 8px 12px;">
                            {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td style="padding: 8px 12px; font-weight: 500; color: #333;">
                            {{ $item->nama_skrining ?: 'Skrining Visual / Umum' }}
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="font-weight: 600; color: #337ab7;">
                                {{ strtoupper($pasien->nama_pasien ?? '-') }}
                            </div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">
                                RM: {{ $pasien->no_rm ?? '-' }}
                            </div>
                        </td>
                        <td style="padding: 8px 12px;">
                            <div>{{ $gender }}</div>
                            <div style="font-size: 11px; color: #666; margin-top: 2px;">{{ $tglLahir }}</div>
                        </td>
                        <td style="padding: 8px 12px;">
                            @if($usiaTahun !== null)
                                <span>{{ $usiaTahun }} Thn ({{ $kelompokUsia }})</span>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td style="padding: 8px 12px;">
                            <div style="font-size: 11.5px;">
                                @if($item->skrining_nyeri == 'Ya')
                                    <span class="badge bg-danger" style="font-size: 10px;">Nyeri: {{ $item->skor_nyeri ?? 0 }}</span>
                                @else
                                    <span class="badge bg-success" style="font-size: 10px;">Bebas Nyeri</span>
                                @endif
                                <span class="badge {{ $item->resiko_jatuh == 'Tinggi' ? 'bg-danger' : ($item->resiko_jatuh == 'Sedang' ? 'bg-warning text-dark' : 'bg-info text-white') }}" style="font-size: 10px;">
                                    Jatuh: {{ $item->resiko_jatuh ?? 'Rendah' }}
                                </span>
                            </div>
                            @if($item->kesimpulan_skrining)
                                <div style="font-size: 11px; color: #666; margin-top: 2px;">
                                    {{ Str::limit($item->kesimpulan_skrining, 40) }}
                                </div>
                            @endif
                        </td>
                        <td class="text-center" style="padding: 8px 12px;">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <a href="/skrining/edit/{{ $item->id }}" class="btn btn-sm btn-info text-white" style="padding: 2px 8px; font-size: 11px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/skrining/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus data skrining ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 2px 8px; font-size: 11px;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 12px 15px; color: #555; font-size: 12px;">
                            Tidak ada data yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($skrinings->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $skrinings->firstItem() }} sampai {{ $skrinings->lastItem() }} dari {{ $skrinings->total() }} entri
            </div>
            <div>
                {{ $skrinings->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Pilih Peserta (1:1 PROFASKES Screenshot 2) -->
<div class="modal fade" id="modalPilihPeserta" tabindex="-1" aria-labelledby="modalPilihPesertaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 620px;">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 5px 25px rgba(0,0,0,0.25);">
            <div class="modal-header" style="padding: 12px 18px; border-bottom: 1px solid #f0f0f0;">
                <h5 class="modal-title" id="modalPilihPesertaLabel" style="font-size: 15px; font-weight: 600; color: #333;">
                    Pilih Peserta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 11px;"></button>
            </div>
            <div class="modal-body" style="padding: 22px 20px;">
                <!-- Card 1: Pasien Dengan Pendaftaran Front Office (Cyan) -->
                <div style="background-color: #00c0ef; border-radius: 4px; position: relative; overflow: hidden; color: #fff; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="position: absolute; right: 18px; top: 12px; font-size: 72px; opacity: 0.18; pointer-events: none; color: #fff;">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div style="padding: 18px 20px 14px 20px; position: relative; z-index: 1;">
                        <h5 style="font-weight: 700; font-size: 16px; margin: 0 0 6px 0; color: #fff; letter-spacing: 0.2px;">
                            Pasien Dengan Pendaftaran Front Office
                        </h5>
                        <p style="font-size: 12px; margin: 0; color: #fff; opacity: 0.95;">
                            Untuk pasien yang sudah didaftarkan pada bagian Pendaftaran Berobat
                        </p>
                    </div>
                    <a href="/skrining/index-register" style="display: block; background: rgba(0, 0, 0, 0.15); color: #fff; text-decoration: none; padding: 7px 15px; font-size: 12px; text-align: center; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.25)'" onmouseout="this.style.background='rgba(0,0,0,0.15)'">
                        Open <i class="fas fa-arrow-circle-right ms-1"></i>
                    </a>
                </div>

                <!-- Card 2: Pasien Tanpa Melalui Pendaftaran Front Office (Orange) -->
                <div style="background-color: #f39c12; border-radius: 4px; position: relative; overflow: hidden; color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="position: absolute; right: 18px; top: 12px; font-size: 72px; opacity: 0.18; pointer-events: none; color: #fff;">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div style="padding: 18px 20px 14px 20px; position: relative; z-index: 1;">
                        <h5 style="font-weight: 700; font-size: 16px; margin: 0 0 6px 0; color: #fff; letter-spacing: 0.2px;">
                            Pasien Tanpa Melalui Pendaftaran Front Office
                        </h5>
                        <p style="font-size: 12px; margin: 0; color: #fff; opacity: 0.95;">
                            Untuk pasien yang sudah mempunyai No. Rekam Medis tapi tidak melakukan Pendaftaran Berobat
                        </p>
                    </div>
                    <a href="/skrining/index-pasien" style="display: block; background: rgba(0, 0, 0, 0.15); color: #fff; text-decoration: none; padding: 7px 15px; font-size: 12px; text-align: center; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.25)'" onmouseout="this.style.background='rgba(0,0,0,0.15)'">
                        Open <i class="fas fa-arrow-circle-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnToggle = document.getElementById('btnToggleFilter');
    const filterCard = document.getElementById('filterSearchCard');
    if (btnToggle && filterCard) {
        btnToggle.addEventListener('click', function() {
            if (filterCard.style.display === 'none' || filterCard.style.display === '') {
                filterCard.style.display = 'block';
            } else {
                filterCard.style.display = 'none';
            }
        });
    }
});
</script>
@endsection
