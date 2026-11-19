@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Setting Tarif Lab Per Penjamin</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Setting Tarif Lab Per Penjamin</span>
        </div>
    </div>

    <!-- Filter Form Row -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-end flex-wrap mb-2" style="gap: 15px;">
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kode Penjamin</label>
            <input type="text" name="kode_penjamin" value="{{ request('kode_penjamin') }}" class="form-control form-control-sm" style="width: 200px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
        </div>
        <div class="d-flex" style="gap: 6px;">
            <button type="submit" class="btn btn-sm text-white" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; padding: 0 16px; font-size: 12px; font-weight: 500; border-radius: 3px;">
                Search
            </button>
            <a href="{{ url()->current() }}" class="btn btn-sm text-white" style="background-color: #f0ad4e; border-color: #eea236; height: 32px; padding: 0 16px; font-size: 12px; font-weight: 500; border-radius: 3px; display: inline-flex; align-items: center;">
                Reset
            </a>
        </div>
    </form>

    <!-- Item Count Indicator -->
    <div style="text-align: right; font-size: 12.5px; color: #333; margin-bottom: 6px;">
        Menampilkan 1-{{ $data->count() }} dari {{ $totalCount }} item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size: 13px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="border-bottom: 1px solid #e7e7e7; color: #337ab7;">
                    <th style="font-weight: 600; width: 45px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">#</th>
                    <th style="font-weight: 600; width: 150px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Kode Penjamin</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Nama Penjamin</th>
                    <th style="font-weight: 600; width: 140px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Jenis Penjamin</th>
                    <th style="font-weight: 600; width: 120px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Telp</th>
                    <th style="width: 250px; border-bottom: 1px solid #e7e7e7; padding: 10px 8px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $penjamin)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="color: #333; padding: 10px 8px;">{{ $index + 1 }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $penjamin->kode_penjamin }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $penjamin->nama_penjamin }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $penjamin->jenis_penjamin ?: '01' }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $penjamin->telp }}</td>
                    <td style="padding: 10px 8px; text-align: right; white-space: nowrap;">
                        <button type="button" onclick="bukaModalTarif({{ $penjamin->id }}, '{{ addslashes($penjamin->nama_penjamin) }}', 'Tunggal')" class="btn btn-sm text-white me-1" style="background-color: #00c0ef; border-color: #00c0ef; font-size: 11px; padding: 3px 8px; border-radius: 3px; font-weight: 500;">
                            Lihat Tarif Satuan
                        </button>
                        <button type="button" onclick="bukaModalTarif({{ $penjamin->id }}, '{{ addslashes($penjamin->nama_penjamin) }}', 'Paket')" class="btn btn-sm text-white" style="background-color: #f39c12; border-color: #e08e0b; font-size: 11px; padding: 3px 8px; border-radius: 3px; font-weight: 500;">
                            <i class="fas fa-cubes me-1" style="font-size: 10px;"></i> Tarif Paket
                        </button>
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

<!-- Modal Tarif Lab (Satuan / Paket) -->
<div class="modal fade" id="modalTarifLab" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 3px;">
            <div class="modal-header py-2 px-3" style="background-color: #3c8dbc; color: white;">
                <h6 class="modal-title" id="modalTarifTitle" style="font-size: 14px; font-weight: 600;">Setting Tarif Lab</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <span class="text-muted" style="font-size: 12px;">Penjamin:</span>
                        <strong id="modalTarifPenjamin" style="font-size: 14px; color: #333;" class="ms-1">-</strong>
                    </div>
                    <div>
                        <span class="badge" id="modalTarifBadge" style="background-color: #00a65a; font-size: 12px; border-radius: 3px;">Satuan</span>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 12.5px;">
                        <thead class="sticky-top" style="background-color: #f8f9fa;">
                            <tr>
                                <th width="40" class="text-center">#</th>
                                <th width="120">Kode Lab</th>
                                <th>Nama Pemeriksaan</th>
                                <th width="140">Golongan</th>
                                <th width="160" class="text-end">Tarif Pasien (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="modalTarifBody">
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Memuat data tarif...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between">
                <small class="text-muted" style="font-size: 11.5px;">* Perubahan tarif akan tersimpan saat tombol Simpan Tarif ditekan.</small>
                <div>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 3px;">Tutup</button>
                    <button type="button" onclick="simpanBatchTarif()" class="btn btn-primary btn-sm" style="background-color: #337ab7; border-color: #2e6da4; border-radius: 3px;">
                        <i class="fas fa-save me-1"></i> Simpan Tarif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPenjaminId = null;

function bukaModalTarif(penjaminId, penjaminNama, tipe) {
    currentPenjaminId = penjaminId;
    document.getElementById('modalTarifPenjamin').innerText = penjaminNama;
    document.getElementById('modalTarifTitle').innerText = 'Setting Tarif Lab ' + (tipe === 'Tunggal' ? 'Satuan' : 'Paket');
    
    const badge = document.getElementById('modalTarifBadge');
    badge.innerText = tipe === 'Tunggal' ? 'Tarif Satuan' : 'Tarif Paket';
    badge.style.backgroundColor = tipe === 'Tunggal' ? '#00c0ef' : '#f39c12';

    const tbody = document.getElementById('modalTarifBody');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> Memuat data...</td></tr>';

    var modal = new bootstrap.Modal(document.getElementById('modalTarifLab'));
    modal.show();

    fetch('/tariflab/penjamin/' + penjaminId + '?tipe=' + tipe)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.length > 0) {
                let html = '';
                res.data.forEach((item, index) => {
                    html += `
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td class="text-center text-muted">${index + 1}</td>
                            <td style="color: #333; font-weight: 500;">${item.kode_lab}</td>
                            <td style="color: #333; font-weight: 500;">${item.nama_lab}</td>
                            <td style="color: #555;">${item.golongan_lab || '-'}</td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm text-end input-tarif" 
                                       data-lab-id="${item.master_lab_id}" 
                                       value="${Math.round(item.harga)}" 
                                       min="0" 
                                       step="1000"
                                       style="height: 28px; font-size: 12px; font-weight: 600; border-radius: 3px;">
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada pemeriksaan lab pada kategori ini.</td></tr>';
            }
        })
        .catch(err => {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-danger">Gagal memuat data tarif.</td></tr>';
        });
}

function simpanBatchTarif() {
    if (!currentPenjaminId) return;

    const inputs = document.querySelectorAll('.input-tarif');
    const items = [];
    inputs.forEach(inp => {
        items.push({
            master_lab_id: inp.getAttribute('data-lab-id'),
            harga: inp.value
        });
    });

    fetch('/tariflab/save-batch', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            penjamin_id: currentPenjaminId,
            items: items
        })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            alert(res.message);
            bootstrap.Modal.getInstance(document.getElementById('modalTarifLab')).hide();
        } else {
            alert('Gagal menyimpan tarif.');
        }
    })
    .catch(err => alert('Terjadi kesalahan koneksi.'));
}
</script>
@endsection
