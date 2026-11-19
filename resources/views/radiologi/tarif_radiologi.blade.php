@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Setting Tarif Radiologi Per Penjamin</h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Setting Tarif Radiologi Per Penjamin</span>
        </div>
    </div>

    <!-- Filter Form Row -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-end flex-wrap mb-3" style="gap: 15px;">
        <div>
            <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kode Penjamin</label>
            <input type="text" name="kode_penjamin" value="{{ request('kode_penjamin') }}" class="form-control form-control-sm" style="width: 220px; height: 34px; font-size: 13px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
        </div>
        <div class="d-flex" style="gap: 6px;">
            <button type="submit" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; height: 34px; padding: 0 16px; font-size: 13px; font-weight: 500; border-radius: 3px;">
                Search
            </button>
            <a href="{{ url()->current() }}" class="btn btn-sm text-white" style="background-color: #f0ad4e; border-color: #eea236; height: 34px; padding: 0 16px; font-size: 13px; font-weight: 500; border-radius: 3px; display: inline-flex; align-items: center;">
                Reset
            </a>
        </div>
    </form>

    <!-- Item Count Indicator (Left-aligned as in screenshot) -->
    <div style="font-size: 12.5px; color: #333; margin-bottom: 6px;">
        Menampilkan <strong>1-{{ $data->count() }}</strong> dari <strong>{{ $totalCount }}</strong> item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size: 12.5px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="border-bottom: 1px solid #e7e7e7; color: #337ab7;">
                    <th style="font-weight: 600; width: 45px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">#</th>
                    <th style="font-weight: 600; width: 150px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Kode Penjamin</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Nama Penjamin</th>
                    <th style="font-weight: 600; width: 150px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Jenis Penjamin</th>
                    <th style="font-weight: 600; width: 120px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Telp</th>
                    <th style="width: 260px; border-bottom: 1px solid #e7e7e7; padding: 10px 8px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $penjamin)
                @php
                    $isSet = isset($tarifCountMap[$penjamin->id]) && $tarifCountMap[$penjamin->id] > 0;
                @endphp
                <tr style="border-bottom: 1px solid #f2f2f2; {{ $index % 2 == 1 ? 'background-color: #fafbfc;' : '' }}">
                    <td style="color: #333; padding: 10px 8px;">{{ $index + 1 }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $penjamin->kode_penjamin }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $penjamin->nama_penjamin }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $penjamin->jenis_penjamin ?: '01' }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $penjamin->telp }}</td>
                    <td style="padding: 10px 8px;">
                        <div class="mb-1">
                            @if($isSet)
                                <button type="button" onclick="bukaModalTarifRad({{ $penjamin->id }}, '{{ addslashes($penjamin->nama_penjamin) }}')" class="btn btn-xs text-white" style="background-color: #00a65a; border-color: #008d4c; font-size: 11px; padding: 2px 8px; border-radius: 3px; font-weight: 500;">
                                    Tarif Sudah Diset ({{ $tarifCountMap[$penjamin->id] }})
                                </button>
                            @else
                                <button type="button" onclick="bukaModalTarifRad({{ $penjamin->id }}, '{{ addslashes($penjamin->nama_penjamin) }}')" class="btn btn-xs text-white" style="background-color: #f39c12; border-color: #e08e0b; font-size: 11px; padding: 2px 8px; border-radius: 3px; font-weight: 500;">
                                    Tarif Belum Diset
                                </button>
                            @endif
                        </div>
                        <div class="d-flex" style="gap: 5px;">
                            <button type="button" onclick="bukaModalTarifRad({{ $penjamin->id }}, '{{ addslashes($penjamin->nama_penjamin) }}')" class="btn btn-xs text-white" style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 2px 8px; border-radius: 3px; font-weight: 500;">
                                Create Tarif Rad
                            </button>
                            <button type="button" onclick="bukaModalImportRad({{ $penjamin->id }}, '{{ addslashes($penjamin->nama_penjamin) }}')" class="btn btn-xs text-white" style="background-color: #337ab7; border-color: #2e6da4; font-size: 11px; padding: 2px 8px; border-radius: 3px; font-weight: 500;">
                                <i class="fas fa-upload me-1"></i> Import Tarif
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada data penjamin ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Setting Tarif Radiologi Per Penjamin -->
<div class="modal fade" id="modalTarifRadiologi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #337ab7; color: white;">
                <h6 class="modal-title" style="font-size: 14.5px; font-weight: 500;">
                    <i class="fas fa-x-ray me-2"></i>Setting Tarif Radiologi - <span id="modalTarifPenjaminNama">-</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div style="font-size: 13px; color: #555;">
                        Atur harga tarif pemeriksaan radiologi untuk penjamin ini.
                    </div>
                    <div>
                        <input type="text" id="filterModalRad" class="form-control form-control-sm" placeholder="Cari nama pemeriksaan..." style="width: 220px; font-size: 12.5px;">
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 430px; overflow-y: auto;">
                    <table class="table table-sm table-hover align-middle mb-0" id="tableModalTarifRad" style="font-size: 12.5px;">
                        <thead class="sticky-top" style="background-color: #f8f9fa;">
                            <tr style="border-bottom: 2px solid #dee2e6; color: #337ab7;">
                                <th width="35" class="text-center">#</th>
                                <th width="110">Kode Rad</th>
                                <th>Nama Pemeriksaan</th>
                                <th width="140">Golongan</th>
                                <th width="110" class="text-end">Unitcost</th>
                                <th width="160" class="text-end">Tarif Pasien (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="modalTarifRadBody">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-spinner fa-spin me-2"></i> Memuat data pemeriksaan radiologi...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between">
                <small class="text-muted" style="font-size: 11.5px;">* Tarif akan tersimpan di sistem saat tombol Simpan ditekan.</small>
                <div>
                    <button type="button" class="btn btn-default btn-sm border" data-bs-dismiss="modal" style="background: #fff;">Batal</button>
                    <button type="button" onclick="simpanBatchTarifRad()" class="btn btn-primary btn-sm" id="btnSimpanTarifRad" style="background-color: #337ab7; border-color: #2e6da4;">
                        <i class="fas fa-save me-1"></i> Simpan Tarif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Tarif Radiologi -->
<div class="modal fade" id="modalImportRadiologi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #337ab7; color: white;">
                <h6 class="modal-title" style="font-size: 14.5px; font-weight: 500;">
                    <i class="fas fa-upload me-2"></i>Import Tarif Radiologi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formImportRad">
                @csrf
                <input type="hidden" id="import_target_penjamin_id" name="target_penjamin_id">
                <div class="modal-body p-3">
                    <p style="font-size: 13px; color: #444; margin-bottom: 12px;">
                        Salin konfigurasi tarif radiologi ke penjamin <strong id="import_target_nama">-</strong>:
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 12.5px;">Pilih Sumber Salinan Tarif</label>
                        <select name="source_penjamin_id" id="source_penjamin_id" class="form-select form-select-sm" style="font-size: 13px;">
                            @foreach($allPenjamins as $ap)
                                <option value="{{ $ap->id }}">{{ $ap->kode_penjamin }} - {{ $ap->nama_penjamin }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info py-2 px-3 mb-0" style="font-size: 12px;">
                        <i class="fas fa-info-circle me-1"></i> Jika penjamin sumber belum memiliki tarif tersimpan, sistem akan otomatis menggunakan nilai Unitcost dari Master Radiologi.
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-default btn-sm border" data-bs-dismiss="modal" style="background: #fff;">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnProsesImport" style="background-color: #337ab7; border-color: #2e6da4;">
                        <i class="fas fa-copy me-1"></i> Mulai Salin Tarif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPenjaminId = null;

function bukaModalTarifRad(penjaminId, penjaminNama) {
    currentPenjaminId = penjaminId;
    document.getElementById('modalTarifPenjaminNama').innerText = penjaminNama;

    const tbody = document.getElementById('modalTarifRadBody');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> Memuat data pemeriksaan radiologi...</td></tr>';

    const modal = new bootstrap.Modal(document.getElementById('modalTarifRadiologi'));
    modal.show();

    fetch('/tarifrad/penjamin/' + penjaminId)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.length > 0) {
                let html = '';
                res.data.forEach((item, index) => {
                    html += `
                        <tr class="row-rad-item" data-search="${item.kode_rad.toLowerCase()} ${item.nama_pemeriksaan.toLowerCase()}">
                            <td class="text-center text-muted">${index + 1}</td>
                            <td><span class="badge font-monospace text-dark bg-light border">${item.kode_rad}</span></td>
                            <td class="fw-semibold text-dark">${item.nama_pemeriksaan}</td>
                            <td><span style="font-size: 11.5px; color: #555;">${item.golongan}</span></td>
                            <td class="text-end text-muted font-monospace">${Number(item.unitcost).toLocaleString('id-ID')}</td>
                            <td class="text-end">
                                <input type="number" 
                                       class="form-control form-control-sm text-end input-harga-rad font-monospace" 
                                       data-rad-id="${item.master_radiologi_id}" 
                                       value="${item.harga}" 
                                       min="0" 
                                       step="1000"
                                       style="font-size: 12.5px; font-weight: 600; width: 140px; display: inline-block;">
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data pemeriksaan radiologi yang tersedia.</td></tr>';
            }
        })
        .catch(err => {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Gagal memuat data tarif.</td></tr>';
        });
}

// Client-side quick filter in modal
document.getElementById('filterModalRad').addEventListener('keyup', function() {
    let q = this.value.toLowerCase().trim();
    let rows = document.querySelectorAll('#modalTarifRadBody .row-rad-item');
    rows.forEach(r => {
        let text = r.getAttribute('data-search') || '';
        r.style.display = text.includes(q) ? '' : 'none';
    });
});

function simpanBatchTarifRad() {
    if (!currentPenjaminId) return;

    let items = [];
    document.querySelectorAll('.input-harga-rad').forEach(input => {
        items.push({
            master_radiologi_id: input.getAttribute('data-rad-id'),
            harga: input.value || 0
        });
    });

    const btn = document.getElementById('btnSimpanTarifRad');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

    fetch('/tarifrad/save-batch', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            penjamin_id: currentPenjaminId,
            items: items
        })
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Tarif';
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalTarifRadiologi')).hide();
            location.reload();
        } else {
            alert(res.message || 'Gagal menyimpan tarif.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Tarif';
        alert('Terjadi kesalahan jaringan.');
    });
}

function bukaModalImportRad(penjaminId, penjaminNama) {
    document.getElementById('import_target_penjamin_id').value = penjaminId;
    document.getElementById('import_target_nama').innerText = penjaminNama;

    // Filter penjamin sumber agar tidak memilih penjamin target itu sendiri
    let select = document.getElementById('source_penjamin_id');
    for (let opt of select.options) {
        opt.disabled = (opt.value == penjaminId);
    }

    const modal = new bootstrap.Modal(document.getElementById('modalImportRadiologi'));
    modal.show();
}

document.getElementById('formImportRad').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnProsesImport');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mengimport...';

    fetch('/tarifrad/import', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            target_penjamin_id: document.getElementById('import_target_penjamin_id').value,
            source_penjamin_id: document.getElementById('source_penjamin_id').value
        })
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-copy me-1"></i> Mulai Salin Tarif';
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalImportRadiologi')).hide();
            location.reload();
        } else {
            alert(res.message || 'Gagal mengimport tarif.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-copy me-1"></i> Mulai Salin Tarif';
        alert('Terjadi kesalahan koneksi.');
    });
});
</script>
@endsection
