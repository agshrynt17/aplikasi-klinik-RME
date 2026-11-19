@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Unit Cost Lab</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Unit Cost Lab</span>
        </div>
    </div>

    <!-- Filter Form Row -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-end flex-wrap mb-2" style="gap: 15px;">
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Status</label>
            <select name="status" class="form-select form-select-sm" style="width: 135px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua --</option>
                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Non Aktif" {{ request('status') == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
            </select>
        </div>
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Golongan Lab</label>
            <select name="golongan_lab" class="form-select form-select-sm" style="width: 145px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua--</option>
                @foreach($golongans as $g)
                    <option value="{{ $g }}" {{ request('golongan_lab') == $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kelompok Lab</label>
            <select name="kelompok_lab" class="form-select form-select-sm" style="width: 135px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua --</option>
                <option value="Paket" {{ request('kelompok_lab') == 'Paket' ? 'selected' : '' }}>Paket</option>
                <option value="Tunggal" {{ request('kelompok_lab') == 'Tunggal' ? 'selected' : '' }}>Tunggal</option>
            </select>
        </div>
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Nama Lab</label>
            <input type="text" name="nama_lab" value="{{ request('nama_lab') }}" class="form-control form-control-sm" style="width: 135px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
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

    <!-- Total Item Count Indicator -->
    <div style="text-align: right; font-size: 12.5px; color: #333; margin-bottom: 4px;">
        Total <strong>{{ number_format($totalCount, 0, ',', '.') }}</strong> item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size: 13px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="border-bottom: 1px solid #e7e7e7; color: #337ab7;">
                    <th style="font-weight: 600; width: 45px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">#</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'kode_lab', 'sort_dir' => request('sort_by') == 'kode_lab' && request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Kode Lab <i class="fas fa-sort" style="font-size: 10px; margin-left: 2px;"></i>
                        </a>
                    </th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'golongan_lab', 'sort_dir' => request('sort_by') == 'golongan_lab' && request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}" style="color: #337ab7; text-decoration: none;">
                            Golongan Lab <i class="fas fa-sort" style="font-size: 10px; margin-left: 2px;"></i>
                        </a>
                    </th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Nama Lab</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Kelompok Lab</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Unitcost</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Status</th>
                    <th style="width: 80px; border-bottom: 1px solid #e7e7e7; padding: 10px 8px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="color: #333; padding: 10px 8px;">{{ $index + 1 }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->kode_lab }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->golongan_lab }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->nama_pemeriksaan }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->kelompok_lab }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ number_format($item->unitcost, 0, '.', ',') }}</td>
                    <td style="padding: 10px 8px;">
                        @if($item->is_active)
                            <span class="badge" style="background-color: #00a65a; color: #fff; font-size: 10.5px; padding: 4px 7px; border-radius: 3px; font-weight: 600; letter-spacing: 0.3px;">Aktif</span>
                        @else
                            <span class="badge" style="background-color: #777777; color: #fff; font-size: 10.5px; padding: 4px 7px; border-radius: 3px; font-weight: 600; letter-spacing: 0.3px;">Non Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 10px 8px; white-space: nowrap;">
                        <a href="javascript:void(0)" onclick="viewLab({{ $item->id }})" title="Lihat" style="color: #337ab7; margin-right: 7px; text-decoration: none; font-size: 13px;">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="editLab({{ $item->id }})" title="Edit" style="color: #337ab7; margin-right: 7px; text-decoration: none; font-size: 13px;">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="deleteLab({{ $item->id }})" title="Hapus" style="color: #337ab7; text-decoration: none; font-size: 13px;">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Tidak ada data unit cost lab ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah / Edit Lab -->
<div class="modal fade" id="modalTambahLab" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 3px;">
            <div class="modal-header py-2 px-3" style="background-color: #3c8dbc; color: white;">
                <h6 class="modal-title" id="modalLabTitle" style="font-size: 14px; font-weight: 600;">Tambah Unit Cost Lab</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formLab">
                @csrf
                <input type="hidden" id="labId" name="id">
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kode Lab</label>
                        <input type="text" name="kode_lab" id="labKode" class="form-control form-control-sm" required style="border-radius: 3px;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Golongan Lab</label>
                        <select name="golongan_lab" id="labGolongan" class="form-select form-select-sm" style="border-radius: 3px;">
                            <option value="Hematologi">Hematologi</option>
                            <option value="Kimia Klinik">Kimia Klinik</option>
                            <option value="Urinalisis">Urinalisis</option>
                            <option value="Imunoserologi">Imunoserologi</option>
                            <option value="Parasitologi">Parasitologi</option>
                            <option value="Mikrobiologi">Mikrobiologi</option>
                            <option value="Feses">Feses</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Nama Lab</label>
                        <input type="text" name="nama_pemeriksaan" id="labNama" class="form-control form-control-sm" required style="border-radius: 3px;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kelompok Lab</label>
                        <select name="kelompok_lab" id="labKelompok" class="form-select form-select-sm" style="border-radius: 3px;">
                            <option value="Tunggal">Tunggal</option>
                            <option value="Paket">Paket</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Unitcost</label>
                        <input type="number" name="unitcost" id="labUnitcost" class="form-control form-control-sm" value="0" min="0" style="border-radius: 3px;">
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="labStatus" value="1" checked>
                        <label class="form-check-label" for="labStatus" style="font-size: 12.5px;">Status Aktif</label>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 3px;">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="background-color: #337ab7; border-color: #2e6da4; border-radius: 3px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editLab(id) {
    fetch('/laboratorium/master-lab/' + id + '/edit')
        .then(res => res.json())
        .then(data => {
            document.getElementById('modalLabTitle').innerText = 'Edit Unit Cost Lab';
            document.getElementById('labId').value = data.id;
            document.getElementById('labKode').value = data.kode_lab;
            document.getElementById('labGolongan').value = data.golongan_lab || data.kategori || 'Hematologi';
            document.getElementById('labNama').value = data.nama_pemeriksaan;
            document.getElementById('labKelompok').value = data.kelompok_lab || 'Tunggal';
            document.getElementById('labUnitcost').value = Math.round(data.unitcost || 0);
            document.getElementById('labStatus').checked = !!data.is_active;

            var modal = new bootstrap.Modal(document.getElementById('modalTambahLab'));
            modal.show();
        });
}

function viewLab(id) {
    editLab(id);
}

function deleteLab(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pemeriksaan lab ini?')) {
        fetch('/laboratorium/master-lab/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Data berhasil dihapus');
            window.location.reload();
        });
    }
}

document.getElementById('formLab').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('labId').value;
    const url = id ? ('/laboratorium/master-lab/' + id) : '/laboratorium/master-lab';
    const method = id ? 'PUT' : 'POST';

    const formData = new FormData(this);
    if (id) {
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan.');
        }
    })
    .catch(err => alert('Gagal menyimpan data.'));
});
</script>
@endsection
