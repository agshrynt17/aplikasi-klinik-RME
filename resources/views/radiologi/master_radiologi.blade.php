@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Setting Master & Cost Radiologi</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Setting Master & Cost Radiologi</span>
        </div>
    </div>

    <!-- Filter Form Row -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-end flex-wrap mb-2" style="gap: 16px;">
        <!-- Kategori -->
        <div>
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kategori</label>
            <select name="kategori" class="form-select form-select-sm" style="width: 230px; height: 34px; font-size: 12.5px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Pilih--</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>

        <!-- Nama Radiologi -->
        <div>
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Nama Radiologi</label>
            <input type="text" name="nama_radiologi" value="{{ request('nama_radiologi') }}" class="form-control form-control-sm" style="width: 220px; height: 34px; font-size: 12.5px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
        </div>

        <!-- Status -->
        <div>
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Status</label>
            <select name="status" class="form-select form-select-sm" style="width: 145px; height: 34px; font-size: 12.5px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Pilih --</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non Aktif</option>
            </select>
        </div>

        <!-- Alat Tersedia? -->
        <div>
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Alat Tersedia?</label>
            <select name="alat_tersedia" class="form-select form-select-sm" style="width: 145px; height: 34px; font-size: 12.5px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Pilih --</option>
                <option value="1" {{ request('alat_tersedia') === '1' ? 'selected' : '' }}>Tersedia</option>
                <option value="0" {{ request('alat_tersedia') === '0' ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="d-flex" style="gap: 6px;">
            <button type="submit" class="btn btn-sm" style="background-color: #f39c12; border-color: #e08e0b; color: white; width: 34px; height: 34px; border-radius: 3px; display: flex; align-items: center; justify-content: center;" title="Cari">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ url()->current() }}" class="btn btn-sm" style="background-color: #fff; border: 1px solid #d2d6de; color: #555; width: 34px; height: 34px; border-radius: 3px; display: flex; align-items: center; justify-content: center;" title="Reset Filter">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>
    </form>

    <!-- Total Item Count Indicator -->
    @php
        $from = $data->firstItem() ?? 0;
        $to = $data->lastItem() ?? 0;
        $total = $data->total();
    @endphp
    <div style="text-align: right; font-size: 12.5px; color: #333; margin-bottom: 6px;">
        Menampilkan <strong>{{ $from }}-{{ $to }}</strong> dari <strong>{{ number_format($total, 0, ',', '.') }}</strong> item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="tableRadiologi" style="font-size: 12.5px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="border-bottom: 1px solid #e7e7e7; color: #337ab7;">
                    <th style="font-weight: 600; width: 35px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px;">#</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'dir' => request('sort') == 'status' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Status <i class="fas fa-sort" style="font-size: 11px; margin-left: 2px;"></i>
                        </a>
                    </th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px;">Alat Tersedia</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px;">Kategori</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'kode', 'dir' => request('sort') == 'kode' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kode Rad
                        </a>
                    </th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama', 'dir' => request('sort') == 'nama' && request('dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Nama Radiologi <i class="fas fa-sort" style="font-size: 11px; margin-left: 2px;"></i>
                        </a>
                    </th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px;">Golongan</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px;">Permintaan / Hasil</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 9px 8px; text-align: right;">Unitcost</th>
                    <th style="width: 35px; border-bottom: 1px solid #e7e7e7; padding: 9px 8px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr style="border-bottom: 1px solid #f2f2f2; {{ $index % 2 == 1 ? 'background-color: #fafbfc;' : '' }}">
                    <td style="color: #333; padding: 9px 8px;">{{ $from + $index }}</td>
                    <td style="padding: 9px 8px;">
                        @if($item->is_active)
                            <span class="badge" style="background-color: #00a65a; color: #ffffff; font-weight: normal; font-size: 11px; padding: 3px 6px; border-radius: 3px;">Aktif</span>
                        @else
                            <span class="badge" style="background-color: #d2d6de; color: #444444; font-weight: normal; font-size: 11px; padding: 3px 6px; border-radius: 3px;">Non Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 9px 8px;">
                        @if($item->alat_tersedia)
                            <span class="badge" style="background-color: #00a65a; color: #ffffff; font-weight: normal; font-size: 11px; padding: 3px 6px; border-radius: 3px;">Tersedia</span>
                        @else
                            <span class="badge" style="background-color: #d2d6de; color: #444444; font-weight: normal; font-size: 11px; padding: 3px 6px; border-radius: 3px;">Tidak Tersedia</span>
                        @endif
                    </td>
                    <td style="padding: 9px 8px;">
                        @if(empty($item->kategori))
                            <span style="color: #dd4b39; font-style: italic;">(belum diset)</span>
                        @else
                            <span style="color: #333;">{{ $item->kategori }}</span>
                        @endif
                    </td>
                    <td style="color: #333; padding: 9px 8px;">{{ $item->kode_rad }}</td>
                    <td style="color: #333; padding: 9px 8px; font-weight: 400;">{{ $item->nama_pemeriksaan }}</td>
                    <td style="padding: 9px 8px;">
                        @if(empty($item->golongan))
                            <span style="color: #dd4b39; font-style: italic;">(belum diset)</span>
                        @else
                            <span style="color: #333;">{{ $item->golongan }}</span>
                        @endif
                    </td>
                    <td style="padding: 9px 8px;">
                        @if(empty($item->permintaan_hasil))
                            <span style="color: #dd4b39; font-style: italic;">(belum diset)</span>
                        @else
                            <span style="color: #333;">{{ $item->permintaan_hasil }}</span>
                        @endif
                    </td>
                    <td style="color: #333; padding: 9px 8px; text-align: right;">{{ number_format($item->unitcost, 0, '.', ',') }}</td>
                    <td style="padding: 9px 8px; text-align: center;">
                        <a href="javascript:void(0)" class="btn-edit-rad" data-id="{{ $item->id }}" title="Edit Cost & Detail" style="color: #337ab7; text-decoration: none;">
                            <i class="fas fa-pencil-alt" style="font-size: 12px;"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted" style="font-size: 13px;">
                        Tidak ada data Radiologi yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
        <div style="font-size: 12.5px; color: #666;">
            Menampilkan <strong>{{ $from }}</strong> sampai <strong>{{ $to }}</strong> dari <strong>{{ number_format($total, 0, ',', '.') }}</strong> entri
        </div>
        <div>
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Edit Setting Master & Cost Radiologi -->
<div class="modal fade" id="modalEditRadiologi" tabindex="-1" aria-labelledby="modalEditRadiologiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); border: none;">
            <div class="modal-header" style="background-color: #337ab7; color: #fff; padding: 12px 18px;">
                <h5 class="modal-title" id="modalEditRadiologiLabel" style="font-size: 15px; font-weight: 500;">
                    <i class="fas fa-edit me-2"></i>Setting Master & Cost Radiologi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditRadiologi">
                @csrf
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body" style="padding: 18px 20px;">
                    <!-- Kode Radiologi -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 3px;">Kode Radiologi</label>
                        <input type="text" id="edit_kode_rad" class="form-control form-control-sm bg-light" readonly style="font-size: 13px; font-weight: 600; color: #555;">
                    </div>

                    <!-- Nama Radiologi -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 3px;">Nama Radiologi <span class="text-danger">*</span></label>
                        <input type="text" id="edit_nama_pemeriksaan" name="nama_pemeriksaan" class="form-control form-control-sm" required style="font-size: 13px;">
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 3px;">Kategori</label>
                        <input type="text" list="kategori_options" id="edit_kategori" name="kategori" class="form-control form-control-sm" placeholder="Pilih atau ketik kategori..." style="font-size: 13px;">
                        <datalist id="kategori_options">
                            <option value="Radiologi Intervensional">
                            <option value="Radiologi Konvensional">
                            <option value="Radiologi Diagnostik">
                            <option value="USG">
                            <option value="CT Scan">
                            <option value="MRI">
                            <option value="Kedokteran Nuklir">
                            <option value="Radioterapi">
                        </datalist>
                    </div>

                    <!-- Golongan -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 3px;">Golongan</label>
                        <input type="text" list="golongan_options" id="edit_golongan" name="golongan" class="form-control form-control-sm" placeholder="Pilih atau ketik golongan..." style="font-size: 13px;">
                        <datalist id="golongan_options">
                            <option value="Radiologi Konvensional">
                            <option value="Radiologi Intervensional">
                            <option value="USG">
                            <option value="CT Scan">
                            <option value="MRI">
                        </datalist>
                    </div>

                    <!-- Permintaan / Hasil -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 3px;">Permintaan / Hasil</label>
                        <input type="text" id="edit_permintaan_hasil" name="permintaan_hasil" class="form-control form-control-sm" placeholder="Contoh: Hasil atau Permintaan" style="font-size: 13px;">
                    </div>

                    <!-- Unitcost -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 3px;">Unitcost (Rp)</label>
                        <input type="number" id="edit_unitcost" name="unitcost" class="form-control form-control-sm" min="0" step="1000" style="font-size: 13px; font-weight: 600;">
                    </div>

                    <!-- Status & Alat Tersedia -->
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label" style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 3px;">Status</label>
                            <select id="edit_is_active" name="is_active" class="form-select form-select-sm" style="font-size: 13px;">
                                <option value="1">Aktif</option>
                                <option value="0">Non Aktif</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 3px;">Alat Tersedia?</label>
                            <select id="edit_alat_tersedia" name="alat_tersedia" class="form-select form-select-sm" style="font-size: 13px;">
                                <option value="1">Tersedia</option>
                                <option value="0">Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #ebebeb; padding: 10px 18px;">
                    <button type="button" class="btn btn-sm btn-default border" data-bs-dismiss="modal" style="font-size: 12.5px; background: #fff; padding: 5px 15px;">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" id="btnSimpanEdit" style="background-color: #337ab7; border-color: #2e6da4; font-size: 12.5px; padding: 5px 18px; font-weight: 500;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Open modal on pencil click
    $(document).on('click', '.btn-edit-rad', function() {
        const id = $(this).data('id');
        
        // Fetch item details
        $.get('/rad/' + id + '/edit', function(res) {
            $('#edit_id').val(res.id);
            $('#edit_kode_rad').val(res.kode_rad);
            $('#edit_nama_pemeriksaan').val(res.nama_pemeriksaan);
            $('#edit_kategori').val(res.kategori || '');
            $('#edit_golongan').val(res.golongan || '');
            $('#edit_permintaan_hasil').val(res.permintaan_hasil || '');
            $('#edit_unitcost').val(res.unitcost || 0);
            $('#edit_is_active').val(res.is_active ? '1' : '0');
            $('#edit_alat_tersedia').val(res.alat_tersedia ? '1' : '0');

            $('#modalEditRadiologi').modal('show');
        }).fail(function() {
            alert('Gagal mengambil data pemeriksaan radiologi.');
        });
    });

    // Form submit
    $('#formEditRadiologi').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();
        const btn = $('#btnSimpanEdit');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

        $.ajax({
            url: '/rad/' + id,
            type: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                $('#modalEditRadiologi').modal('hide');
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                
                // Reload current view to refresh table and totals smoothly
                location.reload();
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                alert(xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.');
            }
        });
    });
});
</script>
@endsection
