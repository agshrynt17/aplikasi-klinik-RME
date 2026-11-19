@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-kunjungan-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .konhakes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }

    /* Action Box */
    .card-action-box {
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        padding: 8px 12px;
        margin-bottom: 12px;
    }
    .btn-orange-cari {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-orange-cari:hover {
        background-color: #d68407;
    }
    .btn-reset-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-reset-light:hover {
        background-color: #f4f4f4;
    }
    .btn-cyan-cetak {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        font-size: 12px;
        padding: 5px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-cyan-cetak:hover {
        background-color: #00acd6;
    }

    /* Cyan Line */
    .cyan-divider {
        border-top: 2px solid #00c0ef;
        margin-top: 8px;
        margin-bottom: 8px;
    }

    .meta-info-line {
        font-size: 12px;
        color: #555;
        margin-bottom: 6px;
    }

    /* Table */
    .table-satusehat-kunjungan {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #d2d6de;
        font-size: 12px;
    }
    .table-satusehat-kunjungan th {
        background-color: #f9f9f9;
        border: 1px solid #d2d6de;
        padding: 8px 8px;
        font-weight: 700;
        color: #337ab7;
        font-size: 12px;
        text-align: left;
    }
    .table-satusehat-kunjungan td {
        border: 1px solid #e4e7ea;
        padding: 6px 8px;
        vertical-align: top;
        color: #333;
        font-size: 12px;
    }
    .table-satusehat-kunjungan tbody tr:hover {
        background-color: #f5f5f5;
    }

    .badge-status-grey {
        background-color: #f4f4f4;
        color: #444;
        font-weight: normal;
        font-size: 10px;
        border: 1px solid #ccc;
        padding: 2px 6px;
        border-radius: 2px;
        display: inline-block;
    }
    .badge-status-green {
        background-color: #00a65a;
        color: #fff;
        font-weight: normal;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 2px;
        display: inline-block;
    }
    .link-buka {
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        text-decoration: none;
        margin-top: 3px;
    }
    .link-buka:hover {
        text-decoration: underline;
    }

    .link-reg {
        color: #337ab7 !important;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .link-reg:hover {
        text-decoration: underline;
    }

    .sub-meta-text {
        font-size: 11px;
        color: #555;
        line-height: 1.35;
    }
    .sub-meta-status {
        font-size: 11px;
        color: #777;
        font-style: italic;
    }

    .skrining-bar {
        display: inline-block;
        width: 22px;
        height: 8px;
        background-color: #00a65a;
        border-radius: 2px;
    }

    .btn-dapatkan-ihs-small {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 10px;
        padding: 1px 6px;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-weight: normal;
        text-decoration: none;
    }
    .btn-dapatkan-ihs-small:hover {
        background-color: #d68407;
    }
</style>

<div class="satusehat-kunjungan-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Satu Sehat - Cek Kunjungan</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-kunjungan/index">Satu Sehat - Cek Kunjungan</a>
        </div>
    </div>

    {{-- Alert info / sukses --}}
    <div id="alertAjax" class="alert d-none py-2 px-3 small my-2" role="alert"></div>

    {{-- Action Box --}}
    <div class="card-action-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn-orange-cari" id="btnToggleFilter">
                    <i class="fas fa-search"></i> Cari data
                </button>
                <a href="/satusehat-kunjungan/index" class="btn-reset-light" title="Reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
            <div>
                <a href="/satusehat-kunjungan/cetak-pdf" target="_blank" class="btn-cyan-cetak">
                    <i class="fas fa-print"></i> Cetak Laporan (pdf)
                </a>
            </div>
        </div>

        {{-- Collapsible Filter Box --}}
        <div id="filterBox" class="mt-2 pt-2 border-top {{ request('q') || request('tanggal') ? '' : 'd-none' }}">
            <form method="GET" action="/satusehat-kunjungan/index" class="d-flex align-items-center flex-wrap gap-2">
                <input type="text" name="q" class="form-control form-control-sm" style="max-width: 300px; height: 30px; font-size: 12px;"
                       placeholder="Cari No Reg, Nama Pasien, No RM..." value="{{ request('q') }}">
                <input type="date" name="tanggal" class="form-control form-control-sm" style="max-width: 170px; height: 30px; font-size: 12px;"
                       value="{{ request('tanggal') ?: '2026-10-14' }}">
                <button type="submit" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 12px;">Cari</button>
                <a href="/satusehat-kunjungan/index" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 12px;">Reset</a>
            </form>
        </div>
    </div>

    {{-- Cyan Line Divider --}}
    <div class="cyan-divider"></div>

    {{-- Meta Count Info --}}
    <div class="meta-info-line">
        Menampilkan {{ $kunjungans->firstItem() ?? 0 }}-{{ $kunjungans->lastItem() ?? 0 }} dari {{ number_format($kunjungans->total(), 0, ',', '.') }} item.
    </div>

    {{-- Table Data --}}
    <div class="table-responsive">
        <table class="table-satusehat-kunjungan">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center; color: #333;">#</th>
                    <th style="width: 110px;">Status Bridging</th>
                    <th style="width: 160px;">Nomor Registrasi</th>
                    <th style="width: 180px;">Nomor Rekam Medis</th>
                    <th style="width: 220px;">Nama &amp; Skrining</th>
                    <th style="width: 120px;">Tgl Lahir &amp; J.K</th>
                    <th style="width: 120px;">Penjamin</th>
                    <th>Klinik, Ruang &amp; Nakes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjungans as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $kunjungans->firstItem() + $index }}</td>
                    <td>
                        @if(!empty($item->kode_ihs_satusehat))
                            <span class="badge-status-green" title="Encounter ID: {{ $item->kode_ihs_satusehat }}">Terkirim (FHIR)</span>
                            <div>
                                <a href="/satusehat-kunjungan/view?id={{ $item->id }}" class="link-buka text-success">
                                    <i class="fas fa-external-link-alt"></i> Buka
                                </a>
                            </div>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-success py-0 px-1 btn-quick-kirim-ss" data-id="{{ $item->id }}" style="font-size: 10px; border-radius: 3px;" title="Kirim Encounter ke Satu Sehat">
                                <i class="fas fa-paper-plane"></i> Kirim SS
                            </button>
                            <div class="mt-1">
                                <a href="/satusehat-kunjungan/view?id={{ $item->id }}" class="link-buka text-info">
                                    <i class="fas fa-external-link-alt"></i> Detail
                                </a>
                            </div>
                        @endif
                    </td>
                    <td>
                        <a href="/satusehat-kunjungan/view?id={{ $item->id }}" class="link-reg">
                            <i class="fas fa-info-circle text-info"></i> {{ $item->nomor_registrasi }}
                        </a>
                        <div class="sub-meta-text">Tgl: {{ date('d.M.Y', strtotime($item->tanggal_daftar)) }}</div>
                        <div class="sub-meta-status">{{ $item->status_periksa ?? 'Dalam Proses' }}</div>
                    </td>
                    <td id="ihs_box_{{ $item->pasien_id }}">
                        <div class="fw-bold text-dark">{{ $item->no_rm }}</div>
                        @if($item->pasien_ihs)
                            <div class="sub-meta-text">ID IHS : {{ $item->pasien_ihs }}</div>
                        @else
                            <div class="sub-meta-text d-flex align-items-center gap-1">
                                ID IHS : 
                                <button type="button" class="btn-dapatkan-ihs-small btn-sync-pasien-ihs" data-id="{{ $item->pasien_id }}" data-nik="{{ $item->nik }}">
                                    <i class="fas fa-id-card"></i> Dapatkan ID IHS by NIK
                                </button>
                            </div>
                        @endif
                        <div class="sub-meta-text">{{ $item->jenis_kunjungan ?? 'Kunjungan Sakit' }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark text-uppercase">{{ $item->nama_pasien }}</div>
                        <div class="sub-meta-text d-flex align-items-center gap-1 mt-1">
                            <span>skrining :</span> <span class="skrining-bar"></span>
                        </div>
                    </td>
                    <td>
                        <div>{{ $item->tanggal_lahir ? date('d.M.Y', strtotime($item->tanggal_lahir)) : '-' }}</div>
                        <div class="sub-meta-text">{{ $item->jenis_kelamin == 'L' ? 'Laki-Laki' : ($item->jenis_kelamin == 'P' ? 'Perempuan' : ($item->jenis_kelamin ?? '-')) }}</div>
                    </td>
                    <td>
                        <div>{{ $item->nama_penjamin ?? ($item->penjamin_id == 10 ? 'BPJS Kesehatan' : 'Pribadi') }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $item->nama_unit ?? 'Klinik Umum 1' }} | {{ $item->ruang_pemeriksaan ?? 'Ruang Rawat 1' }}</div>
                        <div class="sub-meta-text">Dokter :{{ $item->nama_dokter ? (str_starts_with($item->nama_dokter, 'dr') ? $item->nama_dokter : 'dr. ' . $item->nama_dokter) : 'dr. VIERTA AJI NUR YASIN' }}</div>
                        <div class="sub-meta-text">Perawat :{{ $item->nama_perawat ?? 'LALA WIJAYANTI AMD.KEB' }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        Data kunjungan tidak ditemukan pada tanggal tersebut.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
        <div style="font-size: 12px; color: #777;">
            Menampilkan data {{ $kunjungans->firstItem() ?? 0 }} sampai {{ $kunjungans->lastItem() ?? 0 }}
        </div>
        <div>
            {{ $kunjungans->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btnToggleFilter = document.getElementById('btnToggleFilter');
    var filterBox = document.getElementById('filterBox');
    if (btnToggleFilter && filterBox) {
        btnToggleFilter.addEventListener('click', function() {
            filterBox.classList.toggle('d-none');
        });
    }

    var alertAjax = document.getElementById('alertAjax');

    function showAlert(type, message) {
        alertAjax.className = 'alert alert-' + type + ' py-2 px-3 small my-2';
        alertAjax.innerHTML = (type === 'success' ? '<i class="fas fa-check-circle me-1"></i> ' : '<i class="fas fa-exclamation-circle me-1"></i> ') + message;
        alertAjax.classList.remove('d-none');
        setTimeout(function() {
            alertAjax.classList.add('d-none');
        }, 4000);
    }

    // Sync IHS ID directly from table
    document.addEventListener('click', function(e) {
        var target = e.target.closest('.btn-sync-pasien-ihs');
        if (target) {
            e.preventDefault();
            var id = target.getAttribute('data-id');
            var nik = target.getAttribute('data-nik');

            if (!nik) {
                showAlert('danger', 'Gagal: Pasien ini belum memiliki NIK.');
                return;
            }

            var originalHtml = target.innerHTML;
            target.disabled = true;
            target.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('/satusehat-pasien/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, nik: nik })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                target.disabled = false;
                target.innerHTML = originalHtml;
                if (data.success) {
                    showAlert('success', data.message);
                    var boxes = document.querySelectorAll('#ihs_box_' + id);
                    boxes.forEach(function(box) {
                        box.querySelector('.sub-meta-text').innerHTML = 'ID IHS : ' + data.ihs;
                    });
                } else {
                    showAlert('danger', data.message || 'Gagal mendapatkan ID Satu Sehat.');
                }
            })
            .catch(function() {
                target.disabled = false;
                target.innerHTML = originalHtml;
                showAlert('danger', 'Terjadi kesalahan koneksi.');
            });
        }
    });
    // Quick Kirim Satu Sehat from table row
    $(document).off('click', '.btn-quick-kirim-ss').on('click', '.btn-quick-kirim-ss', function(e) {
        e.preventDefault();
        var btn = $(this);
        var id = btn.data('id');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        fetch('/satusehat-kunjungan/kirim', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                showAlert('success', data.message);
                btn.parent().html('<span class="badge-status-green" title="' + data.encounter_id + '">Terkirim (FHIR)</span><div><a href="/satusehat-kunjungan/view?id=' + id + '" class="link-buka text-success"><i class="fas fa-external-link-alt"></i> Buka</a></div>');
            } else {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim SS');
                showAlert('danger', data.message || 'Gagal mengirim encounter.');
            }
        })
        .catch(function() {
            btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim SS');
            showAlert('danger', 'Terjadi kesalahan jaringan.');
        });
    });
});
</script>
@endsection
