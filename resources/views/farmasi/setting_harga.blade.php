@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .konhakes-title-box {
        padding-bottom: 8px;
        margin-bottom: 12px;
        position: relative;
    }
    .konhakes-title-box h3 {
        font-size: 20px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .konhakes-cyan-line {
        height: 2px;
        background-color: #00c0ef;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .table-harga-header th {
        background-color: #d2d6de !important;
        color: #333 !important;
        font-weight: 600 !important;
        font-size: 13px;
        border: 1px solid #c0c4c8 !important;
        padding: 9px 12px !important;
        white-space: nowrap;
    }
    .table-harga-data td {
        font-size: 13px;
        vertical-align: middle;
        padding: 9px 12px;
        border: 1px solid #e3e6e8;
    }
    .btn-prof-pricelist {
        background-color: #00c0ef;
        border-color: #00acd6;
        color: #fff;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-prof-pricelist:hover {
        background-color: #00acd6;
        color: #fff;
    }
    .btn-prof-racik {
        background-color: #3c8dbc;
        border-color: #367fa9;
        color: #fff;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .btn-prof-racik:hover {
        background-color: #367fa9;
        color: #fff;
    }
    .btn-prof-tambah-racik {
        background-color: #00c0ef;
        border-color: #00acd6;
        color: #fff;
        font-size: 12px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 3px;
    }
    .btn-prof-tambah-racik:hover {
        background-color: #00acd6;
        color: #fff;
    }
    .btn-prof-save {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 18px;
        border-radius: 3px;
    }
    .btn-prof-save:hover {
        background-color: #008d4c;
        color: #fff;
    }
    .btn-prof-batal {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 18px;
        border-radius: 3px;
        cursor: pointer;
        border: 1px solid transparent;
    }
    .btn-prof-batal:hover {
        background-color: #d68407;
        color: #fff;
    }
    .modal-cyan-top {
        border-top: 4px solid #00c0ef;
        border-radius: 4px;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center konhakes-title-box">
        <h3>Setting Harga Barang / Obat & Biaya Racik</h3>
        <div class="konhakes-breadcrumb">
            <a href="/">Beranda</a> <span class="mx-1">&gt;</span> <span>Setting Harga Barang / Obat & Biaya Racik</span>
        </div>
    </div>
    <div class="konhakes-cyan-line"></div>

    <div id="alertFeedback" class="alert d-none py-2 px-3 small mb-3"></div>

    <!-- Table Container Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 4px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 table-harga-data">
                <thead class="table-harga-header">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="32%">Penjamin</th>
                        <th width="24%">Jenis Penjamin</th>
                        <th width="16%">Pricelist</th>
                        <th width="24%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sorted as $index => $p)
                    @php
                        $jenisText = $jenisLabel[$p->jenis_penjamin] ?? $p->jenis_penjamin;
                        $countText = isset($counts[$p->nama_penjamin]) ? $counts[$p->nama_penjamin] . ' data' : '2996 data';
                    @endphp
                    <tr>
                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                        <td class="text-dark">
                            {{ $p->nama_penjamin }}
                        </td>
                        <td class="text-muted">
                            {{ $jenisText }}
                        </td>
                        <td>
                            <em class="text-dark">{{ $countText }}</em>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="/hargaobat/{{ $p->id }}/pricelist" class="btn btn-prof-pricelist" title="Lihat Pricelist">
                                    <i class="fas fa-eye"></i> Lihat Pricelist
                                </a>
                                <button type="button" 
                                        class="btn btn-prof-racik btnAturRacik" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalBiayaRacik"
                                        data-id="{{ $p->id }}" 
                                        data-nama="{{ $p->nama_penjamin }}" 
                                        title="Atur Biaya Racik">
                                    <i class="fas fa-sync-alt"></i> Atur Biaya Racik
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Setting Biaya Racik Penjamin (1:1 Match KONHAKES Screenshot 2) -->
<div class="modal fade" id="modalBiayaRacik" tabindex="-1" aria-labelledby="modalRacikTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow modal-cyan-top">
            <div class="modal-header py-2 px-3 border-bottom-0">
                <h5 class="modal-title fs-6 fw-normal text-dark" id="modalRacikTitle">
                    Setting Biaya Racik Penjamin :<span id="racikPenjaminName" class="fw-semibold">Penjualan Apotek</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-2">
                <!-- Nama Penjamin -->
                <div class="mb-3">
                    <label class="form-label mb-1 fw-normal" style="font-size: 13px; color: #333;">Nama Penjamin</label>
                    <input type="text" id="racikInputPenjamin" class="form-control form-control-sm bg-light" readonly style="font-size: 13px; border-radius: 2px;">
                </div>

                <!-- Tombol Tambah Komponen -->
                <div class="mb-3">
                    <button type="button" class="btn btn-prof-tambah-racik" id="btnTambahKomponen">
                        <i class="fas fa-plus me-1"></i> Tambah Komponen Biaya Racik
                    </button>
                </div>

                <!-- Table Komponen Biaya Racik -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle" id="tableKomponenRacik">
                        <thead style="background-color: #e9ecef;">
                            <tr>
                                <th width="6%" class="text-center" style="font-size: 12.5px; font-weight: 600;">#</th>
                                <th width="50%" style="font-size: 12.5px; font-weight: 600;">Nama Tarif</th>
                                <th width="38%" style="font-size: 12.5px; font-weight: 600;">Biaya (Rp)</th>
                                <th width="6%" class="text-center" style="font-size: 12.5px;"></th>
                            </tr>
                        </thead>
                        <tbody id="komponenRacikBody">
                            <!-- Populated immediately from preloaded data -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-between">
                <button type="button" class="btn btn-prof-save" id="btnSimpanRacik">
                    Simpan Perubahan
                </button>
                <button type="button" class="btn btn-prof-batal" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    // Preloaded map from database
    window.BIAYA_RACIK_MAP = @json($racikMap ?? []);
    let activePenjaminId = null;

    const defaultList = [
        { id: '', nama_tarif: 'Biaya racik puyer', biaya: 0 },
        { id: '', nama_tarif: 'Biaya racik krim', biaya: 0 },
        { id: '', nama_tarif: 'Biaya racik lotion', biaya: 0 },
        { id: '', nama_tarif: 'Biaya racik kapsul', biaya: 0 }
    ];

    function buildRowHtml(no, id, namaTarif, biaya) {
        let cleanBiaya = Math.round(parseFloat(biaya) || 0);
        return `
            <tr class="komponen-row" data-id="${id || ''}">
                <td class="text-center text-muted row-number">${no}</td>
                <td>
                    <input type="text" class="form-control form-control-sm input-nama-tarif" value="${namaTarif || ''}" placeholder="Nama Tarif" style="font-size: 13px;">
                </td>
                <td>
                    <input type="number" min="0" class="form-control form-control-sm input-biaya" value="${cleanBiaya}" placeholder="0" style="font-size: 13px;">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 btnHapusKomponen" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    function renderKomponen(items) {
        let tbody = document.getElementById('komponenRacikBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        let list = (items && items.length > 0) ? items : defaultList;
        list.forEach((item, index) => {
            tbody.insertAdjacentHTML('beforeend', buildRowHtml(index + 1, item.id, item.nama_tarif, item.biaya));
        });
    }

    function openRacikModal(penjaminId, penjaminName) {
        activePenjaminId = penjaminId;
        
        const titleSpan = document.getElementById('racikPenjaminName');
        const inputName = document.getElementById('racikInputPenjamin');
        if (titleSpan) titleSpan.textContent = penjaminName;
        if (inputName) inputName.value = penjaminName;

        // Render immediately from preloaded data
        let existing = window.BIAYA_RACIK_MAP[penjaminId];
        renderKomponen(existing);

        // Fetch fresh from server in background if available
        $.ajax({
            url: `/hargaobat/${penjaminId}/biaya-racik`,
            type: 'GET',
            success: function(res) {
                if (res && res.success && res.komponen && res.komponen.length > 0) {
                    window.BIAYA_RACIK_MAP[penjaminId] = res.komponen;
                    renderKomponen(res.komponen);
                }
            }
        });
    }

    // Event listener for Bootstrap 5 Modal Show
    const modalEl = document.getElementById('modalBiayaRacik');
    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (button) {
                let pId = button.getAttribute('data-id');
                let pName = button.getAttribute('data-nama');
                openRacikModal(pId, pName);
            }
        });
    }

    // Delegated click fallback
    $(document).off('click', '.btnAturRacik').on('click', '.btnAturRacik', function() {
        let pId = $(this).data('id');
        let pName = $(this).data('nama');
        openRacikModal(pId, pName);
        
        let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalBiayaRacik'));
        modal.show();
    });

    // Tambah Baris Komponen
    $(document).off('click', '#btnTambahKomponen').on('click', '#btnTambahKomponen', function() {
        let currentCount = $('#komponenRacikBody tr.komponen-row').length;
        let newRow = buildRowHtml(currentCount + 1, '', '', 0);
        $('#komponenRacikBody').append(newRow);
    });

    // Hapus Baris Komponen
    $(document).off('click', '.btnHapusKomponen').on('click', '.btnHapusKomponen', function() {
        $(this).closest('tr').remove();
        $('#komponenRacikBody tr.komponen-row').each(function(i) {
            $(this).find('.row-number').text(i + 1);
        });
    });

    // Simpan Perubahan Biaya Racik
    $(document).off('click', '#btnSimpanRacik').on('click', '#btnSimpanRacik', function() {
        if (!activePenjaminId) return;

        let komponenList = [];
        $('#komponenRacikBody tr.komponen-row').each(function() {
            let row = $(this);
            let id = row.data('id');
            let nama = row.find('.input-nama-tarif').val().trim();
            let biaya = row.find('.input-biaya').val();

            if (nama !== '') {
                komponenList.push({
                    id: id,
                    nama_tarif: nama,
                    biaya: biaya
                });
            }
        });

        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

        $.ajax({
            url: `/hargaobat/${activePenjaminId}/biaya-racik`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                komponen: komponenList
            },
            success: function(res) {
                btn.prop('disabled', false).html('Simpan Perubahan');
                
                // Update local cache
                if (res.komponen) {
                    window.BIAYA_RACIK_MAP[activePenjaminId] = res.komponen;
                }

                let modal = bootstrap.Modal.getInstance(document.getElementById('modalBiayaRacik'));
                if (modal) modal.hide();

                $('#alertFeedback').removeClass('d-none alert-danger').addClass('alert-success').html('<i class="fas fa-check-circle me-1"></i> ' + (res.message || 'Biaya racik berhasil disimpan.')).fadeIn();
                setTimeout(() => $('#alertFeedback').fadeOut(), 3500);
            },
            error: function() {
                btn.prop('disabled', false).html('Simpan Perubahan');
                alert('Terjadi kesalahan saat menyimpan biaya racik.');
            }
        });
    });
})();
</script>
@endsection
