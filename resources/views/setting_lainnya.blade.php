@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h3 class="fw-normal mb-0" style="color: #333; font-size: 24px;">Setting Umum Lainnya</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 12px; background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Setting Umum Lainnya</li>
            </ol>
        </nav>
    </div>
    
    <!-- Orange accent line below header -->
    <div style="height: 3px; background-color: #f39c12; margin-bottom: 18px; width: 100%;"></div>

    <!-- Alert Box -->
    <div id="alertBox" class="alert d-none mb-3" role="alert"></div>

    <!-- Settings Groups -->
    @foreach($groupedSettings as $kategori => $items)
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 3px; overflow: hidden; background-color: #ffffff;">
        <!-- Card Header Cyan with Cog Icon -->
        <div class="card-header py-2 px-3 text-white fw-bold d-flex align-items-center" style="background-color: #00c0ef; font-size: 14px; border: none;">
            <i class="fas fa-cog me-2"></i> {{ $kategori }}
        </div>
        
        <!-- Table Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead style="background-color: #ffffff; border-bottom: 2px solid #f4f4f4;">
                        <tr style="color: #333;">
                            <th style="width: 45px;" class="text-center">#</th>
                            <th>Nama Setting</th>
                            <th style="width: 320px; text-align: right; padding-right: 25px;">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $idx => $item)
                        <tr id="rowSetting{{ $item->id }}">
                            <td class="text-center fw-normal" style="color: #555;">{{ $idx + 1 }}</td>
                            <td style="color: #333;">{{ $item->nama_setting }}</td>
                            <td style="text-align: right; padding-right: 25px;">
                                <div class="d-inline-flex align-items-center justify-content-end">
                                    <span id="badgeVal{{ $item->id }}" class="setting-badge">
                                        @if($item->nilai === 'Ya')
                                            <span class="badge py-1 px-2" style="background-color: #00a65a; font-size: 11px; font-weight: normal; border-radius: 2px;">Ya</span>
                                        @elseif($item->nilai === 'Tidak')
                                            <span class="badge py-1 px-2" style="background-color: #dd4b39; font-size: 11px; font-weight: normal; border-radius: 2px;">Tidak</span>
                                        @else
                                            <span class="badge py-1 px-2" style="background-color: #00c0ef; font-size: 11px; font-weight: normal; border-radius: 2px;">{{ $item->nilai }}</span>
                                        @endif
                                    </span>
                                    <button type="button" class="btn btn-link p-0 text-dark ms-2 btnEditSetting" 
                                            data-id="{{ $item->id }}" 
                                            data-name="{{ $item->nama_setting }}" 
                                            data-val="{{ $item->nilai }}" 
                                            data-options="{{ json_encode($item->opsi) }}"
                                            title="Ubah Setting" 
                                            style="text-decoration: none; line-height: 1;">
                                        <i class="fas fa-pencil-alt text-dark" style="font-size: 12px;"></i>
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
    @endforeach
</div>

<!-- Modal Edit Setting -->
<div class="modal fade" id="modalEditSetting" tabindex="-1" aria-labelledby="modalEditSettingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #00c0ef; border-radius: 3px 3px 0 0;">
                <h5 class="modal-title fs-6 fw-bold" id="modalEditSettingLabel">
                    <i class="fas fa-cog me-2"></i> Ubah Nilai Pengaturan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditSetting">
                @csrf
                <input type="hidden" id="editSettingId" name="id">
                <div class="modal-body p-4" style="font-size: 13px;">
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1 fw-bold">Nama Pengaturan</label>
                        <div class="fw-bold text-dark" id="labelSettingName" style="font-size: 14px;">-</div>
                    </div>
                    <div class="mb-3">
                        <label for="inputSettingVal" class="form-label text-dark fw-bold mb-1">Nilai Pengaturan</label>
                        <div id="containerSettingInput">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white px-4 fw-bold" style="background-color: #00c0ef; border-color: #00c0ef;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Open edit modal
    $(document).on('click', '.btnEditSetting', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let val = $(this).data('val');
        let options = $(this).data('options');

        $('#editSettingId').val(id);
        $('#labelSettingName').text(name);

        let inputHtml = '';
        if (options && Array.isArray(options) && options.length > 0) {
            inputHtml = `<select name="nilai" id="inputSettingVal" class="form-select form-select-sm" style="font-size: 13px;">`;
            options.forEach(function (opt) {
                let selected = (opt === val) ? 'selected' : '';
                inputHtml += `<option value="${opt}" ${selected}>${opt}</option>`;
            });
            inputHtml += `</select>`;
        } else {
            inputHtml = `<input type="text" name="nilai" id="inputSettingVal" class="form-control form-control-sm" value="${val}" style="font-size: 13px;" required>`;
        }

        $('#containerSettingInput').html(inputHtml);
        $('#modalEditSetting').modal('show');
    });

    // Form submit AJAX
    $('#formEditSetting').on('submit', function (e) {
        e.preventDefault();
        let id = $('#editSettingId').val();
        let newVal = $('#inputSettingVal').val();

        $.ajax({
            url: '/general-setting/' + id,
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                $('#modalEditSetting').modal('hide');
                showAlert('success', res.message || 'Pengaturan berhasil disimpan.');

                // Update badge live
                let badgeHtml = '';
                if (newVal === 'Ya') {
                    badgeHtml = '<span class="badge py-1 px-2" style="background-color: #00a65a; font-size: 11px; font-weight: normal; border-radius: 2px;">Ya</span>';
                } else if (newVal === 'Tidak') {
                    badgeHtml = '<span class="badge py-1 px-2" style="background-color: #dd4b39; font-size: 11px; font-weight: normal; border-radius: 2px;">Tidak</span>';
                } else {
                    badgeHtml = `<span class="badge py-1 px-2" style="background-color: #00c0ef; font-size: 11px; font-weight: normal; border-radius: 2px;">${newVal}</span>`;
                }

                $(`#badgeVal${id}`).html(badgeHtml);
                $(`#rowSetting${id} .btnEditSetting`).data('val', newVal);
            },
            error: function () {
                alert('Gagal menyimpan pengaturan.');
            }
        });
    });

    function showAlert(type, msg) {
        $('#alertBox').removeClass('d-none alert-success alert-warning alert-danger')
            .addClass('alert-' + type).html('<i class="fas fa-check-circle me-1"></i> ' + msg).show();
        setTimeout(() => $('#alertBox').addClass('d-none'), 4000);
    }
});
</script>
@endsection
