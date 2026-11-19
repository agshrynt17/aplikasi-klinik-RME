@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Log Pengambilan Antrian Dari Mobile JKN')

@section('content')
<style>
    .page-header-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .page-title-text {
        font-size: 20px;
        font-weight: 700;
        color: #333333;
        margin: 0;
    }
    .breadcrumb-custom {
        font-size: 11.5px;
        margin: 0;
        color: #777;
    }
    .breadcrumb-custom a {
        color: #337ab7;
        text-decoration: none;
    }

    /* Filter Form Container */
    .filter-mjkn-container {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 14px 16px;
        margin-bottom: 16px;
    }
    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 4px;
        display: block;
    }
    .form-control-konhakes {
        font-size: 12.5px;
        height: 32px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        padding: 4px 8px;
        color: #333;
        width: 100%;
    }
    .form-control-konhakes:focus {
        border-color: #3c8dbc;
        outline: none;
        box-shadow: none;
    }

    .btn-search-blue {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 600;
        padding: 5px 16px;
        border-radius: 3px;
        height: 32px;
        transition: background 0.15s ease;
    }
    .btn-search-blue:hover {
        background-color: #286090;
        border-color: #204d74;
        color: #ffffff;
    }

    .btn-reset-gray {
        background-color: #ffffff;
        border: 1px solid #ccc;
        color: #333333;
        font-size: 12.5px;
        padding: 5px 14px;
        border-radius: 3px;
        height: 32px;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s ease;
    }
    .btn-reset-gray:hover {
        background-color: #e6e6e6;
        border-color: #adadad;
        color: #333333;
    }

    /* Main Table Container */
    .table-container-box {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 14px 16px 20px 16px;
    }

    .table-counter-text {
        font-size: 12.5px;
        color: #444444;
        margin-bottom: 8px;
    }

    .table-mjkn {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
    }
    .table-mjkn th {
        background-color: #edf4f8;
        color: #337ab7;
        font-weight: 700;
        border: 1px solid #dbe6ec;
        padding: 7px 10px;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-mjkn td {
        border: 1px solid #eef2f5;
        padding: 7px 10px;
        vertical-align: middle;
        color: #333333;
        line-height: 1.35;
    }
    .table-mjkn tr:nth-child(even) td {
        background-color: #fbfcfd;
    }
    .table-mjkn tr:hover td {
        background-color: #f1f7fa;
    }

    /* Status Badges */
    .badge-menunggu {
        background-color: #777777;
        color: #ffffff;
        font-size: 10.5px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 3px;
        display: inline-block;
    }
    .badge-didaftarkan {
        background-color: #00a65a;
        color: #ffffff;
        font-size: 10.5px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 3px;
        display: inline-block;
    }
    .badge-pasien-baru {
        background-color: #00a65a;
        color: #ffffff;
        font-size: 10.5px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 3px;
        display: inline-block;
    }
    .reg-code-text {
        color: #337ab7;
        font-weight: 700;
        font-size: 11px;
        margin-top: 2px;
    }
    .btn-buat-rm {
        background: #ffffff;
        border: 1px solid #d2d6de;
        color: #444;
        font-size: 10.5px;
        padding: 1px 6px;
        border-radius: 3px;
        text-decoration: none !important;
        display: inline-block;
        margin-top: 3px;
    }
    .btn-buat-rm:hover {
        background: #f4f4f4;
        color: #222;
    }

    .badge-hari-ini {
        background-color: #00c0ef;
        color: #ffffff;
        font-size: 9.5px;
        font-weight: 600;
        padding: 1px 5px;
        border-radius: 2px;
        display: inline-block;
    }

    /* Action eye icon */
    .btn-eye-action {
        color: #00c0ef;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 2px 4px;
        transition: color 0.15s ease;
    }
    .btn-eye-action:hover {
        color: #0097bc;
    }

    /* Pagination styling */
    .pagination-custom {
        display: flex;
        justify-content: flex-end;
        margin-top: 15px;
        gap: 3px;
    }
</style>

<div class="container-fluid p-3">
    <!-- Page Header & Breadcrumbs -->
    <div class="page-header-box">
        <h1 class="page-title-text">Log Pengambilan Antrian Dari Mobile JKN</h1>
        <div class="breadcrumb-custom">
            <a href="/dashboard">Beranda</a> &gt; <span>Log Pengambilan Antrian Dari Mobile JKN</span>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="filter-mjkn-container">
        <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <div class="row g-2 align-items-end">
                <!-- 1. Tanggal Periksa -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Tanggal Periksa</label>
                    <input type="text" name="tanggal_periksa" id="tanggal_periksa" class="form-control form-control-konhakes" value="{{ request('tanggal_periksa', $filterTanggal) }}" placeholder="14-10-2026">
                </div>

                <!-- 2. Poliklinik -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Poliklinik</label>
                    <select name="poliklinik" class="form-select form-control-konhakes">
                        <option value="">--Pilih--</option>
                        <option value="Klinik Umum 1" {{ request('poliklinik') == 'Klinik Umum 1' ? 'selected' : '' }}>Klinik Umum 1</option>
                        <option value="Poli Gigi" {{ request('poliklinik') == 'Poli Gigi' ? 'selected' : '' }}>Poli Dokter Gigi</option>
                        <option value="Poli Bidan" {{ request('poliklinik') == 'Poli Bidan' ? 'selected' : '' }}>Poli Bidan</option>
                        @foreach($polikliniks as $poli)
                            <option value="{{ $poli->nama_poli }}" {{ request('poliklinik') == $poli->nama_poli ? 'selected' : '' }}>{{ $poli->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Nomor Kartu -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Nomor Kartu</label>
                    <input type="text" name="nomor_kartu" class="form-control form-control-konhakes" value="{{ request('nomor_kartu') }}" placeholder="">
                </div>

                <!-- 4. Kodeno Antrian -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Kodeno Antrian</label>
                    <input type="text" name="kodeno_antrian" class="form-control form-control-konhakes" value="{{ request('kodeno_antrian') }}" placeholder="">
                </div>

                <!-- 5. Batal -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Batal</label>
                    <select name="batal" class="form-select form-control-konhakes">
                        <option value="">--Pilih--</option>
                        <option value="Ya" {{ request('batal') == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ request('batal') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <!-- 6. Action Buttons -->
                <div class="col-lg-2 col-md-4 col-sm-6 d-flex gap-2">
                    <button type="submit" class="btn btn-search-blue">Search</button>
                    <a href="{{ url()->current() }}" class="btn btn-reset-gray">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="table-container-box">
        <!-- Item Counter -->
        <div class="table-counter-text">
            Menampilkan <strong>{{ $firstItem }}-{{ $lastItem }}</strong> dari <strong>{{ $totalItems }}</strong> item.
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table-mjkn">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th style="width: 130px;">Status</th>
                        <th style="width: 105px;">Tanggal Berobat</th>
                        <th style="width: 90px;">Kodeno Antrian</th>
                        <th style="width: 170px;">Poli & Dokter</th>
                        <th style="width: 200px;">No Kartu & Nama</th>
                        <th style="width: 130px;">NIK</th>
                        <th>Keluhan</th>
                        <th style="width: 125px;">Dibuat Pada</th>
                        <th style="width: 35px; text-align: center;"><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $item)
                    <tr>
                        <!-- 1. Nomor -->
                        <td class="text-center">{{ $firstItem + $index }}</td>

                        <!-- 2. Status -->
                        <td>
                            @if($item->status_antrian == 'Sudah didaftarkan')
                                <span class="badge-didaftarkan">Sudah didaftarkan</span>
                                @if(!empty($item->no_registrasi))
                                    <div class="reg-code-text">{{ $item->no_registrasi }}</div>
                                @endif
                            @elseif($item->status_antrian == 'Pasien Baru')
                                <span class="badge-pasien-baru">Pasien Baru</span>
                                <div>
                                    <a href="/master-pasien?nik={{ $item->nik }}" class="btn-buat-rm">
                                        + Buatkan RM
                                    </a>
                                </div>
                            @elseif($item->is_batal)
                                <span class="badge bg-danger" style="font-size: 10.5px;">Batal</span>
                            @else
                                <span class="badge-menunggu">Menunggu</span>
                            @endif
                        </td>

                        <!-- 3. Tanggal Berobat -->
                        <td>
                            <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($item->tanggalperiksa)->format('d-m-Y') }}</div>
                            <div><span class="badge-hari-ini">Hari ini</span></div>
                            <div style="color: #666; font-size: 11px;">{{ $item->jam_praktek ?? '06:00-22:00' }}</div>
                        </td>

                        <!-- 4. Kodeno Antrian -->
                        <td style="font-weight: 700; color: #222;">
                            {{ $item->nomorantrean }}
                        </td>

                        <!-- 5. Poli & Dokter -->
                        <td>
                            <div style="font-weight: 700; color: #333;">{{ $item->nama_poli ?? 'Klinik Umum 1' }}</div>
                            <div style="font-size: 11px; color: #555;">{{ $item->nama_dokter ?? 'dr VIERTA AJI NUR YASIN' }}</div>
                        </td>

                        <!-- 6. No Kartu & Nama -->
                        <td>
                            <div style="font-weight: 700; color: #333;">{{ $item->jenis_pasien ?? 'Pasien Lama' }}</div>
                            <div style="font-size: 11.5px; color: #555;">{{ $item->nomorkartu }}</div>
                            <div style="font-size: 11.5px; color: #337ab7; font-weight: 600;">
                                a.n: {{ $item->nama_pasien }}
                            </div>
                        </td>

                        <!-- 7. NIK -->
                        <td style="font-size: 11.5px; color: #444;">
                            {{ $item->nik }}
                        </td>

                        <!-- 8. Keluhan -->
                        <td style="font-size: 11.5px; color: #555;">
                            {{ $item->keluhan ?? '-' }}
                        </td>

                        <!-- 9. Dibuat Pada -->
                        <td style="font-size: 11px; color: #666; white-space: nowrap;">
                            {{ $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '-' }}
                        </td>

                        <!-- 10. Action Button -->
                        <td class="text-center">
                            <button type="button" class="btn-eye-action btnDetail" data-id="{{ $item->id }}" title="Lihat Detail">
                                <i class="far fa-eye" style="font-size: 14px;"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            Tidak ada data antrian Mobile JKN yang cocok dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">
                Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
            </div>
            <div>
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>

<!-- Modal Detail Antrian Mobile JKN -->
<div class="modal fade" id="modalDetailMJKN" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2" style="background-color: #337ab7; color: #ffffff;">
                <h6 class="modal-title fw-bold" style="font-size: 14px;">
                    <i class="fas fa-info-circle me-1"></i> Detail Antrian Mobile JKN
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12.5px;">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th width="35%" class="text-muted">Nomor Antrean</th>
                        <td width="65%" class="fw-bold fs-5 text-primary" id="mdlNoAntrean">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status Antrian</th>
                        <td id="mdlStatus">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">No. Registrasi</th>
                        <td class="fw-bold" id="mdlNoReg">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Nama Pasien</th>
                        <td class="fw-bold text-dark" id="mdlNamaPasien">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">No. Kartu BPJS</th>
                        <td id="mdlNoKartu">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">NIK Pasien</th>
                        <td id="mdlNik">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Poliklinik Tujuan</th>
                        <td id="mdlPoli">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Dokter Praktek</th>
                        <td id="mdlDokter">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal Periksa</th>
                        <td id="mdlTanggal">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Keluhan</th>
                        <td class="text-danger" id="mdlKeluhan">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Waktu Pendaftaran</th>
                        <td id="mdlDibuat">-</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('.btnDetail');
        const modalEl = document.getElementById('modalDetailMJKN');
        const modal = new bootstrap.Modal(modalEl);

        detailButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                fetch(`/bridging-log-antrian-mjkn/${id}/detail`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data) {
                            const d = res.data;
                            document.getElementById('mdlNoAntrean').textContent = d.nomorantrean || '-';
                            document.getElementById('mdlStatus').textContent = d.status_antrian || 'Menunggu';
                            document.getElementById('mdlNoReg').textContent = d.no_registrasi || '-';
                            document.getElementById('mdlNamaPasien').textContent = d.nama_pasien || '-';
                            document.getElementById('mdlNoKartu').textContent = d.nomorkartu || '-';
                            document.getElementById('mdlNik').textContent = d.nik || '-';
                            document.getElementById('mdlPoli').textContent = d.nama_poli || 'Klinik Umum 1';
                            document.getElementById('mdlDokter').textContent = d.nama_dokter || 'dr VIERTA AJI NUR YASIN';
                            document.getElementById('mdlTanggal').textContent = `${d.tanggalperiksa || ''} (${d.jam_praktek || '06:00-22:00'})`;
                            document.getElementById('mdlKeluhan').textContent = d.keluhan || '-';
                            document.getElementById('mdlDibuat').textContent = d.created_at || '-';

                            modal.show();
                        }
                    })
                    .catch(err => console.log(err));
            });
        });
    });
</script>
@endsection
