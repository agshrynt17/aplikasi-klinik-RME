@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Log Pengiriman Antrian ke BPJS')

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
    .filter-kirim-container {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 12px 16px;
        margin-bottom: 16px;
    }
    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 4px;
        display: block;
    }
    .form-control-profaskes {
        font-size: 12.5px;
        height: 32px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        padding: 4px 8px;
        color: #333;
        width: 100%;
    }
    .form-control-profaskes:focus {
        border-color: #3c8dbc;
        outline: none;
    }

    .btn-search-orange {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff;
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 3px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s ease;
    }
    .btn-search-orange:hover {
        background-color: #e08e0b;
        color: #ffffff;
    }

    .btn-reset-white {
        background-color: #ffffff;
        border: 1px solid #ccc;
        color: #333333;
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 3px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        transition: background 0.15s ease;
    }
    .btn-reset-white:hover {
        background-color: #e6e6e6;
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
        color: #333333;
        margin-bottom: 8px;
    }

    .table-kirim {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .table-kirim th {
        background-color: #edf4f8;
        color: #337ab7;
        font-weight: 700;
        border: 1px solid #dbe6ec;
        padding: 7px 8px;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-kirim td {
        border: 1px solid #eef2f5;
        padding: 7px 8px;
        vertical-align: middle;
        color: #333333;
        line-height: 1.35;
    }
    .table-kirim tr:nth-child(even) td {
        background-color: #fbfcfd;
    }
    .table-kirim tr:hover td {
        background-color: #f1f7fa;
    }

    .badge-terkirim {
        background-color: #00a65a;
        color: #ffffff;
        font-size: 9.5px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 2px;
        display: inline-block;
    }
    .badge-gagal {
        background-color: #dd4b39;
        color: #ffffff;
        font-size: 9.5px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 2px;
        display: inline-block;
    }

    .text-blue-link {
        color: #337ab7;
        font-weight: 700;
        text-decoration: none;
    }
    .text-blue-link:hover {
        text-decoration: underline;
    }

    .text-mjkn-sub {
        color: #337ab7;
        font-size: 11px;
    }

    .link-detail-action {
        color: #337ab7;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }
    .link-detail-action:hover {
        text-decoration: underline;
    }
</style>

<div class="container-fluid p-3">
    <!-- Page Header & Breadcrumbs -->
    <div class="page-header-box">
        <h1 class="page-title-text">Log Pengiriman Antrian ke BPJS</h1>
        <div class="breadcrumb-custom">
            <a href="/admin">Beranda</a> &gt; <span>Log Pengiriman Antrian ke BPJS</span>
        </div>
    </div>

    <!-- Filter Bar Form -->
    <div class="filter-kirim-container">
        <form method="GET" action="{{ url()->current() }}" id="filterKirimForm">
            <div class="row g-2 align-items-end">
                <!-- 1. Tanggal -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Tanggal</label>
                    <input type="text" name="tanggal" class="form-control form-control-profaskes" value="{{ request('tanggal', $filterTanggal) }}" placeholder="14-10-2026">
                </div>

                <!-- 2. Poliklinik -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Poliklinik</label>
                    <select name="poliklinik" class="form-select form-control-profaskes">
                        <option value="">--Pilih--</option>
                        <option value="Klinik Umum 1" {{ request('poliklinik') == 'Klinik Umum 1' ? 'selected' : '' }}>Klinik Umum 1</option>
                        <option value="Klinik Gigi" {{ request('poliklinik') == 'Klinik Gigi' ? 'selected' : '' }}>Klinik Gigi</option>
                        <option value="Poli Bidan" {{ request('poliklinik') == 'Poli Bidan' ? 'selected' : '' }}>Poli Bidan</option>
                        @foreach($polikliniks as $poli)
                            <option value="{{ $poli->nama_poli }}" {{ request('poliklinik') == $poli->nama_poli ? 'selected' : '' }}>{{ $poli->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Kode Reg. -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Kode Reg.</label>
                    <input type="text" name="kode_reg" class="form-control form-control-profaskes" value="{{ request('kode_reg') }}" placeholder="">
                </div>

                <!-- 4. Nomor Kartu -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="filter-label">Nomor Kartu</label>
                    <input type="text" name="nomor_kartu" class="form-control form-control-profaskes" value="{{ request('nomor_kartu') }}" placeholder="">
                </div>

                <!-- 5. No Antrian -->
                <div class="col-lg-1 col-md-2 col-sm-4">
                    <label class="filter-label">No Antrian</label>
                    <input type="text" name="no_antrian" class="form-control form-control-profaskes" value="{{ request('no_antrian') }}" placeholder="">
                </div>

                <!-- 6. Status Antrian -->
                <div class="col-lg-2 col-md-4 col-sm-4">
                    <label class="filter-label">Status Antrian</label>
                    <select name="status_antrian" class="form-select form-control-profaskes">
                        <option value="">--Semua--</option>
                        <option value="Terkirim" {{ request('status_antrian') == 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                        <option value="Gagal" {{ request('status_antrian') == 'Gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <!-- 7. Action Buttons -->
                <div class="col-lg-1 col-md-2 col-sm-4 d-flex gap-1">
                    <button type="submit" class="btn btn-search-orange" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-reset-white" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="table-container-box">
        <!-- Item Counter (e.g. Menampilkan 1-20 dari 108 item.) -->
        <div class="table-counter-text">
            Menampilkan <strong>{{ $firstItem }}-{{ $lastItem }}</strong> dari <strong>{{ $totalItems }}</strong> item.
        </div>

        <div class="table-responsive">
            <table class="table-kirim">
                <thead>
                    <tr>
                        <th style="width: 30px; text-align: center;">#</th>
                        <th style="width: 80px;">Antrian</th>
                        <th style="width: 115px;">Pendaftaran</th>
                        <th style="width: 185px;">Pasien</th>
                        <th style="width: 165px;">No Kartu & NIK</th>
                        <th style="width: 165px;">Poli & Dokter</th>
                        <th style="width: 85px;">Jampraktek</th>
                        <th style="width: 140px;">Status Kirim Antrian</th>
                        <th>Ket</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $item)
                    <tr>
                        <!-- 1. Nomor -->
                        <td class="text-center">{{ $firstItem + $index }}</td>

                        <!-- 2. Antrian -->
                        <td>
                            <div style="font-weight: 700; color: #333;">{{ $item->no_antrian }}</div>
                            @if(!empty($item->sumber_antrian))
                                <div class="text-mjkn-sub">{{ $item->sumber_antrian }}</div>
                            @endif
                        </td>

                        <!-- 3. Pendaftaran -->
                        <td>
                            <div class="text-blue-link">{{ $item->no_registrasi }}</div>
                            <div style="color: #666; font-size: 11px;">
                                Tgl. {{ $item->tanggal_pendaftaran ? $item->tanggal_pendaftaran->format('d/m/Y') : '14/10/2026' }}
                            </div>
                        </td>

                        <!-- 4. Pasien -->
                        <td>
                            <div style="font-weight: 700; color: #333;">{{ $item->nama_pasien }}</div>
                            <div style="color: #555; font-size: 11px;">{{ $item->no_rm }}</div>
                        </td>

                        <!-- 5. No Kartu & NIK -->
                        <td>
                            <div style="font-size: 11px;">No.Kartu: {{ $item->no_kartu }}</div>
                            <div style="font-size: 11px;">NIK: {{ $item->nik }}</div>
                        </td>

                        <!-- 6. Poli & Dokter -->
                        <td>
                            <div style="font-weight: 700; color: #333;">{{ $item->nama_poli ?? 'Klinik Umum 1' }}</div>
                            <div style="color: #555; font-size: 11px;">{{ $item->nama_dokter ?? 'dr VIERTA AJI NUR YASIN' }}</div>
                        </td>

                        <!-- 7. Jampraktek -->
                        <td style="font-size: 11px; color: #333;">
                            {{ $item->jam_praktek ?? '06:00-22:00' }}
                        </td>

                        <!-- 8. Status Kirim Antrian -->
                        <td>
                            <div style="margin-bottom: 2px;">
                                Kirim Antrian: 
                                <span class="{{ $item->status_kirim_antrian == 'Terkirim' ? 'badge-terkirim' : 'badge-gagal' }}">
                                    {{ $item->status_kirim_antrian ?? 'Terkirim' }}
                                </span>
                            </div>
                            <div>
                                Kehadiran: 
                                <span class="{{ $item->status_kehadiran == 'Terkirim' ? 'badge-terkirim' : 'badge-gagal' }}">
                                    {{ $item->status_kehadiran ?? 'Terkirim' }}
                                </span>
                            </div>
                        </td>

                        <!-- 9. Ket & Detail -->
                        <td>
                            <span>{{ \Illuminate\Support\Str::limit($item->keterangan ?? 'SUKSES: Data antrian berhasil dikirim ke server BPJS Antrol.', 22) }}</span>
                            <a href="javascript:void(0)" class="link-detail-action btnDetailModal" data-id="{{ $item->id }}">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            Tidak ada data log pengiriman antrian BPJS yang cocok dengan filter.
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

<!-- Modal Detail Pengiriman Antrian BPJS -->
<div class="modal fade" id="modalDetailKirim" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2" style="background-color: #337ab7; color: #ffffff;">
                <h6 class="modal-title fw-bold" style="font-size: 14px;">
                    <i class="fas fa-paper-plane me-1"></i> Detail Log Pengiriman Antrian ke BPJS
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12.5px;">
                <div class="row g-2 mb-3 border-bottom pb-2">
                    <div class="col-md-6">
                        <div><strong>No. Registrasi:</strong> <span id="mdlNoReg" class="text-primary fw-bold">-</span></div>
                        <div><strong>No. Antrian:</strong> <span id="mdlNoAntrian" class="fw-bold">-</span></div>
                        <div><strong>Nama Pasien:</strong> <span id="mdlNamaPasien">-</span></div>
                        <div><strong>No. Kartu / NIK:</strong> <span id="mdlKartuNik">-</span></div>
                    </div>
                    <div class="col-md-6">
                        <div><strong>Poli & Dokter:</strong> <span id="mdlPoliDokter">-</span></div>
                        <div><strong>Waktu Kirim:</strong> <span id="mdlWaktuKirim">-</span></div>
                        <div><strong>Status Antrian:</strong> <span id="mdlStatusKirim" class="badge bg-success">Terkirim</span></div>
                        <div><strong>Status Kehadiran:</strong> <span id="mdlStatusHadir" class="badge bg-success">Terkirim</span></div>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="fw-bold text-dark small mb-1"><i class="fas fa-upload me-1 text-primary"></i> Request Body (Payload ke BPJS):</label>
                    <pre class="bg-dark text-success p-2 rounded small" id="mdlPayload" style="font-family: Consolas, monospace; max-height: 120px; overflow-y: auto;"></pre>
                </div>

                <div>
                    <label class="fw-bold text-dark small mb-1"><i class="fas fa-download me-1 text-info"></i> Response BPJS Antrol:</label>
                    <pre class="bg-dark text-warning p-2 rounded small" id="mdlResponse" style="font-family: Consolas, monospace; max-height: 120px; overflow-y: auto;"></pre>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('.btnDetailModal');
        const modalEl = document.getElementById('modalDetailKirim');
        const modal = new bootstrap.Modal(modalEl);

        detailButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                fetch(`/bridging-log-kirim-antrian/${id}/detail`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data) {
                            const d = res.data;
                            document.getElementById('mdlNoReg').textContent = d.no_registrasi || '-';
                            document.getElementById('mdlNoAntrian').textContent = `${d.no_antrian || '-'} ${d.sumber_antrian ? '(' + d.sumber_antrian + ')' : ''}`;
                            document.getElementById('mdlNamaPasien').textContent = `${d.nama_pasien || '-'} (${d.no_rm || '-'})`;
                            document.getElementById('mdlKartuNik').textContent = `${d.no_kartu || '-'} / ${d.nik || '-'}`;
                            document.getElementById('mdlPoliDokter').textContent = `${d.nama_poli || 'Klinik Umum 1'} - ${d.nama_dokter || 'dr VIERTA AJI NUR YASIN'}`;
                            document.getElementById('mdlWaktuKirim').textContent = d.waktu_kirim || d.created_at || '-';
                            document.getElementById('mdlStatusKirim').textContent = d.status_kirim_antrian || 'Terkirim';
                            document.getElementById('mdlStatusHadir').textContent = d.status_kehadiran || 'Terkirim';

                            try {
                                document.getElementById('mdlPayload').textContent = JSON.stringify(JSON.parse(d.request_payload), null, 2);
                            } catch (e) {
                                document.getElementById('mdlPayload').textContent = d.request_payload || '{"kodebooking": "' + (d.kodebooking || '') + '"}';
                            }

                            try {
                                document.getElementById('mdlResponse').textContent = JSON.stringify(JSON.parse(d.response_api), null, 2);
                            } catch (e) {
                                document.getElementById('mdlResponse').textContent = d.response_api || '{"metadata": {"code": 200, "message": "Ok"}}';
                            }

                            modal.show();
                        }
                    })
                    .catch(err => console.log(err));
            });
        });
    });
</script>
@endsection
