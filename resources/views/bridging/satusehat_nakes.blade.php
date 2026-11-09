@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* PROFASKES 1:1 SETTING ID SATU SEHAT TENAGA KESEHATAN */
    .nakes-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .profaskes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .profaskes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .profaskes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .profaskes-breadcrumb a {
        color: #444;
        text-decoration: none;
    }
    .profaskes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .card-nakes-box {
        background-color: #fff;
        border-top: 2px solid #f39c12 !important;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 12px 15px;
        margin-top: 10px;
    }
    .btn-prof-cari {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 12.5px;
        padding: 4px 12px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        line-height: 1.4;
    }
    .btn-prof-cari:hover {
        background-color: #d68407;
        color: #ffffff !important;
    }
    .btn-prof-refresh {
        background-color: #ffffff;
        border: 1px solid #ccc;
        color: #333 !important;
        font-size: 12.5px;
        padding: 4px 10px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        line-height: 1.4;
    }
    .btn-prof-refresh:hover {
        background-color: #e6e6e6;
        border-color: #adadad;
    }
    .cyan-table-header {
        border-top: 2px solid #00c0ef !important;
        margin-top: 12px;
        padding-top: 8px;
        margin-bottom: 6px;
    }
    .profaskes-summary-text {
        font-size: 13px;
        color: #333;
        margin-bottom: 6px;
    }
    .table-satusehat-nakes {
        width: 100%;
        background-color: #fff;
        border: 1px solid #f4f4f4;
        border-collapse: collapse;
        font-size: 12.5px;
        margin-bottom: 15px;
    }
    .table-satusehat-nakes th {
        background-color: #fff !important;
        border: 1px solid #f4f4f4 !important;
        padding: 8px 8px;
        vertical-align: middle;
        white-space: nowrap;
        font-weight: 600;
        color: #333;
    }
    .table-satusehat-nakes td {
        border: 1px solid #f4f4f4 !important;
        padding: 7px 8px;
        vertical-align: middle;
        color: #333;
    }
    .table-satusehat-nakes tbody tr:nth-of-type(odd) td {
        background-color: #ffffff;
    }
    .table-satusehat-nakes tbody tr:nth-of-type(even) td {
        background-color: #f9f9f9;
    }
    .table-satusehat-nakes tbody tr:hover td {
        background-color: #f5f5f5;
    }
    .btn-prof-dapatkan {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff !important;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        white-space: nowrap;
        line-height: 1.4;
    }
    .btn-prof-dapatkan:hover {
        background-color: #d68407;
        color: #ffffff !important;
    }
    .text-belum-diset {
        color: #dd4b39;
        font-style: italic;
    }
    .link-sync-icon {
        color: #00c0ef;
        text-decoration: none;
        cursor: pointer;
    }
    .link-sync-icon:hover {
        color: #0097bc;
    }
    .link-edit-icon {
        color: #3c8dbc;
        text-decoration: none;
        cursor: pointer;
    }
    .link-edit-icon:hover {
        color: #205c82;
    }
    .pagination {
        margin: 0;
        font-size: 12px;
    }
    .page-item.active .page-link {
        background-color: #337ab7;
        border-color: #337ab7;
    }
</style>

<div class="nakes-page">
    <!-- Header Title & Breadcrumb (PROFASKES 1:1) -->
    <div class="profaskes-header">
        <h3 class="profaskes-title">Setting ID Satu Sehat Tenaga Kesehatan</h3>
        <div class="profaskes-breadcrumb">
            <a href="/">Beranda</a> <span style="color: #ccc; margin: 0 2px;">&gt;</span> <span style="color: #777;">Setting ID Satu Sehat Tenaga Kesehatan</span>
        </div>
    </div>

    <!-- Alert Success Flash -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small my-2" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Main Card Box with Orange Top Border (PROFASKES 1:1) -->
    <div class="card-nakes-box">
        <!-- Action Toolbar -->
        <div class="d-flex align-items-center gap-2 mb-2">
            <button type="button" class="btn btn-prof-cari" data-bs-toggle="collapse" data-bs-target="#boxPencarian" aria-expanded="false">
                <i class="fas fa-search"></i> Cari data
            </button>
            <a href="/satusehat-nakes/index" class="btn btn-prof-refresh" title="Reset / Segarkan Halaman">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>

        <!-- Filter Box (Collapsible) -->
        <div class="collapse mb-3 {{ request()->has('q') || request()->has('fungsional') || request()->has('status_ihs') ? 'show' : '' }}" id="boxPencarian">
            <div class="p-3 border rounded bg-light">
                <form action="/satusehat-nakes/index" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold mb-1">Cari Nama / NIK / ID Satu Sehat</label>
                        <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}" placeholder="Ketik kata kunci...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">Fungsional Medis</label>
                        <select name="fungsional" class="form-select form-select-sm">
                            <option value="ALL">-- Semua Fungsional --</option>
                            <option value="DOKTER" {{ request('fungsional') == 'DOKTER' ? 'selected' : '' }}>DOKTER</option>
                            <option value="TENAGA KESEHATAN" {{ request('fungsional') == 'TENAGA KESEHATAN' ? 'selected' : '' }}>TENAGA KESEHATAN</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">Status ID Satu Sehat</label>
                        <select name="status_ihs" class="form-select form-select-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="SUDAH" {{ request('status_ihs') == 'SUDAH' ? 'selected' : '' }}>Sudah Memiliki ID</option>
                            <option value="BELUM" {{ request('status_ihs') == 'BELUM' ? 'selected' : '' }}>Belum Memiliki ID</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill" style="background-color: #3c8dbc; border-color: #367fa9;">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="/satusehat-nakes/index" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cyan Top Border Separator & Summary (PROFASKES 1:1) -->
        <div class="cyan-table-header">
            <div class="profaskes-summary-text">
                Menampilkan <b>{{ $nakes->firstItem() ?? 1 }}-{{ $nakes->lastItem() ?? $nakes->count() }}</b> dari <b>{{ $nakes->total() }}</b> item.
            </div>
        </div>

        <!-- Table Grid (PROFASKES 1:1) -->
        <div class="table-responsive">
            <table class="table-satusehat-nakes">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">#</th>
                        <th style="width: 155px;">Kode NIK</th>
                        <th style="width: 220px;">Nama Karyawan</th>
                        <th style="width: 240px;">Nama Pada Satu Sehat (Sesuai SISDMK)</th>
                        <th style="width: 105px;">Tanggal Lahir</th>
                        <th style="width: 150px;">Fungsional Medis</th>
                        <th style="width: 85px;">J/K</th>
                        <th style="width: 165px;">ID Satu Sehat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nakes as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration + ($nakes->currentPage() - 1) * $nakes->perPage() }}</td>
                        <td>{{ $item->nik ?? '-' }}</td>
                        <td>{{ $item->nama_pegawai }}</td>
                        <td id="nama-col-{{ $item->id }}">
                            @if(!empty($item->nama_satusehat))
                                <span>{{ $item->nama_satusehat }}</span>
                                <a href="javascript:void(0)" onclick="bukaModalEditNama({{ $item->id }}, '{{ addslashes($item->nama_satusehat) }}', '{{ addslashes($item->nama_pegawai) }}')" class="link-edit-icon ms-1" title="Ubah Nama">
                                    <i class="fas fa-pencil-alt" style="font-size: 11px;"></i>
                                </a>
                            @else
                                <span class="text-belum-diset">--Belum Diset--</span>
                                <a href="javascript:void(0)" onclick="bukaModalEditNama({{ $item->id }}, '', '{{ addslashes($item->nama_pegawai) }}')" class="link-edit-icon ms-1" title="Atur Nama">
                                    <i class="fas fa-pencil-alt" style="font-size: 11px;"></i>
                                </a>
                            @endif
                        </td>
                        <td>
                            {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d.M.Y') : '-' }}
                        </td>
                        <td>{{ strtoupper($item->fungsional_medis ?? '-') }}</td>
                        <td>{{ $item->jenis_kelamin_label }}</td>
                        <td id="ihs-col-{{ $item->id }}">
                            @if(!empty($item->kode_ihs_satusehat))
                                <span id="ihs-text-{{ $item->id }}">{{ $item->kode_ihs_satusehat }}</span>
                                <a href="javascript:void(0)" onclick="syncSatuSehatNakes({{ $item->id }})" class="link-sync-icon ms-1" title="Sinkron Ulang Satu Sehat" id="btn-sync-{{ $item->id }}">
                                    <i class="fas fa-sync-alt" style="font-size: 11px;"></i>
                                </a>
                            @else
                                <button type="button" class="btn btn-prof-dapatkan" id="btn-sync-{{ $item->id }}" onclick="syncSatuSehatNakes({{ $item->id }})">
                                    <i class="fas fa-sync-alt"></i> Dapatkan ID Satu Sehat
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Tidak ada data tenaga kesehatan yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (PROFASKES 1:1) -->
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="small text-muted">
                Halaman {{ $nakes->currentPage() }} dari {{ $nakes->lastPage() }}
            </div>
            <div>
                {{ $nakes->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT NAMA PADA SATU SEHAT (SISDMK) -->
<div class="modal fade" id="modalEditNamaNakes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formEditNamaNakes" onsubmit="simpanNamaNakes(event)">
                @csrf
                <div class="modal-header py-2 px-3 text-white" style="background-color: #f39c12 !important;">
                    <h5 class="modal-title fs-6 fw-semibold text-white">
                        <i class="fas fa-pencil-alt me-1"></i> Edit Nama Pada Satu Sehat (SISDMK)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <input type="hidden" name="pegawai_id" id="modalPegawaiId">
                    <div class="mb-2">
                        <label class="form-label small text-muted mb-1">Nama Karyawan di Klinik:</label>
                        <div class="fw-bold fs-6 text-dark" id="modalNamaKaryawan">-</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold mb-1">Nama Sesuai Database SISDMK / Satu Sehat:</label>
                        <input type="text" name="nama_satusehat" id="modalInputNamaSatuSehat" class="form-control" required placeholder="Contoh: RESI ANGRAINI">
                        <div class="form-text small" style="font-size: 11px;">Sesuaikan ejaan nama dengan identitas yang terdaftar pada SISDMK Kemenkes.</div>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm text-white px-4" style="background-color: #f39c12; border-color: #e08e0b;" id="btnSimpanNama">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL HASIL SINKRONISASI NAKES -->
<div class="modal fade" id="modalHasilSyncNakes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white py-2 px-3" style="background-color: #00a65a !important;">
                <h5 class="modal-title fs-6 fw-semibold text-white">
                    <i class="fas fa-check-circle me-1"></i> Sinkronisasi Satu Sehat Berhasil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: #e8f8f5; border: 1px solid #a3e4d7;">
                    <i class="fas fa-user-check text-success fs-1 me-3"></i>
                    <div>
                        <div class="fw-bold text-success" style="font-size: 14px;">Practitioner ID Ditemukan!</div>
                        <div class="small text-muted">Data Nakes telah tervalidasi langsung dengan API DTO Kemenkes RI.</div>
                    </div>
                </div>
                <table class="table table-sm table-bordered mb-0 small" style="font-size: 12.5px;">
                    <tbody>
                        <tr>
                            <td class="bg-light fw-semibold" style="width: 140px;">ID Satu Sehat (IHS)</td>
                            <td><span class="badge bg-success fs-6" id="syncResIhs">-</span></td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold">Nama SISDMK</td>
                            <td id="syncResNama" class="fw-bold text-primary">-</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold">Status Bridging</td>
                            <td><span class="badge bg-info text-white"><i class="fas fa-check me-1"></i> Terhubung & Aktif</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-success btn-sm px-4" data-bs-dismiss="modal" style="background-color: #00a65a; border-color: #008d4c;">
                    <i class="fas fa-check me-1"></i> Tutup & Selesai
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function bukaModalEditNama(id, namaSatuSehat, namaPegawai) {
    $('#modalPegawaiId').val(id);
    $('#modalNamaKaryawan').text(namaPegawai);
    $('#modalInputNamaSatuSehat').val(namaSatuSehat || namaPegawai);

    const modal = new bootstrap.Modal(document.getElementById('modalEditNamaNakes'));
    modal.show();
}

function simpanNamaNakes(e) {
    e.preventDefault();
    const btn = $('#btnSimpanNama');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

    const pegawaiId = $('#modalPegawaiId').val();
    const namaBaru = $('#modalInputNamaSatuSehat').val();

    $.ajax({
        url: '/satusehat-nakes/update-nama',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        data: {
            pegawai_id: pegawaiId,
            nama_satusehat: namaBaru
        },
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
            bootstrap.Modal.getInstance(document.getElementById('modalEditNamaNakes')).hide();
            
            // Perbarui tampilan kolom Nama Pada Satu Sehat secara instan
            $('#nama-col-' + pegawaiId).html(
                '<span>' + res.nama + '</span> ' +
                '<a href="javascript:void(0)" onclick="bukaModalEditNama(' + pegawaiId + ', \'' + res.nama.replace(/'/g, "\\'") + '\', \'' + $('#modalNamaKaryawan').text().replace(/'/g, "\\'") + '\')" class="link-edit-icon ms-1" title="Ubah Nama">' +
                '<i class="fas fa-pencil-alt" style="font-size: 11px;"></i></a>'
            );
        },
        error: function(xhr) {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
            alert('Gagal menyimpan nama: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan sistem.'));
        }
    });
}

function syncSatuSehatNakes(id) {
    const btn = $('#btn-sync-' + id);
    const originalContent = btn.html();
    btn.html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: '/satusehat-nakes/sync/' + id,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        success: function(res) {
            if (res.success) {
                // Update kolom ID Satu Sehat secara instan
                $('#ihs-col-' + id).html(
                    '<span id="ihs-text-' + id + '">' + res.id_satusehat + '</span> ' +
                    '<a href="javascript:void(0)" onclick="syncSatuSehatNakes(' + id + ')" class="link-sync-icon ms-1" title="Sinkron Ulang Satu Sehat" id="btn-sync-' + id + '">' +
                    '<i class="fas fa-sync-alt" style="font-size: 11px;"></i></a>'
                );

                if (res.nama_satusehat) {
                    $('#nama-col-' + id).html(
                        '<span>' + res.nama_satusehat + '</span> ' +
                        '<a href="javascript:void(0)" onclick="bukaModalEditNama(' + id + ', \'' + res.nama_satusehat.replace(/'/g, "\\'") + '\')" class="link-edit-icon ms-1" title="Ubah Nama">' +
                        '<i class="fas fa-pencil-alt" style="font-size: 11px;"></i></a>'
                    );
                }

                // Tampilkan modal hasil
                $('#syncResIhs').text(res.id_satusehat);
                $('#syncResNama').text(res.nama_satusehat || '-');
                const modal = new bootstrap.Modal(document.getElementById('modalHasilSyncNakes'));
                modal.show();
            } else {
                btn.html(originalContent);
                alert(res.message || 'Data tidak ditemukan di Satu Sehat.');
            }
        },
        error: function(xhr) {
            btn.html(originalContent);
            alert('Gagal menghubungi server Satu Sehat: ' + (xhr.responseJSON?.message || 'Koneksi error.'));
        }
    });
}
</script>
@endsection
