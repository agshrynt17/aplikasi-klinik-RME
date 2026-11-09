@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Setting Nilai Normal Lab</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Setting Nilai Normal Lab</span>
        </div>
    </div>

    <!-- Filter Form Row -->
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-end flex-wrap mb-2" style="gap: 15px;">
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Lab</label>
            <select name="lab_id" class="form-select form-select-sm" style="width: 140px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua--</option>
                @foreach($labs as $lab)
                    <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>{{ $lab->nama_pemeriksaan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kategori Lab</label>
            <select name="kategori_lab" class="form-select form-select-sm" style="width: 140px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua--</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori_lab') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Detail Lab</label>
            <select name="detail_lab" class="form-select form-select-sm" style="width: 150px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua--</option>
                @foreach($detailLabs as $det)
                    <option value="{{ $det }}" {{ request('detail_lab') == $det ? 'selected' : '' }}>{{ $det }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Nama Settinglabdetail</label>
            <input type="text" name="nama_setting" value="{{ request('nama_setting') }}" class="form-control form-control-sm" style="width: 150px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
        </div>
        <div>
            <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Bentuk Nilai</label>
            <select name="bentuk_nilai" class="form-select form-select-sm" style="width: 120px; height: 32px; font-size: 12px; border-radius: 3px; border-color: #d2d6de; background-color: #fff;">
                <option value="">--Semua--</option>
                <option value="range" {{ request('bentuk_nilai') == 'range' ? 'selected' : '' }}>range</option>
                <option value="kualitatif" {{ request('bentuk_nilai') == 'kualitatif' ? 'selected' : '' }}>kualitatif</option>
                <option value="kuantitatif" {{ request('bentuk_nilai') == 'kuantitatif' ? 'selected' : '' }}>kuantitatif</option>
            </select>
        </div>
        <div class="d-flex" style="gap: 5px;">
            <button type="submit" class="btn btn-sm text-white" title="Search" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;">
                <i class="fas fa-search" style="font-size: 12px;"></i>
            </button>
            <a href="{{ url()->current() }}" class="btn btn-sm text-white" title="Reset" style="background-color: #f0ad4e; border-color: #eea236; height: 32px; width: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;">
                <i class="fas fa-sync-alt" style="font-size: 12px;"></i>
            </a>
        </div>
    </form>

    <!-- Total Item Count Indicator (Left-aligned above the table) -->
    <div style="text-align: left; font-size: 12.5px; color: #333; margin-bottom: 6px;">
        Total <strong>{{ number_format($totalCount, 0, ',', '.') }}</strong> item.
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size: 13px; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="border-bottom: 1px solid #e7e7e7; color: #337ab7;">
                    <th style="font-weight: 600; width: 35px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">#</th>
                    <th style="width: 35px; border-bottom: 1px solid #e7e7e7; padding: 10px 6px;"></th>
                    <th style="font-weight: 600; width: 50px; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">ID</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Nama Lab</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Kategori</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Detail Lab</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Nama Setting</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Satuan</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Bentuk Nilai</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Nilai Normal</th>
                    <th style="font-weight: 600; border-bottom: 1px solid #e7e7e7; color: #337ab7; padding: 10px 8px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="color: #333; padding: 10px 8px;">{{ $index + 1 }}</td>
                    <td style="padding: 10px 6px;">
                        <div class="d-flex flex-column align-items-center" style="gap: 3px;">
                            <a href="javascript:void(0)" onclick="editNilaiNormal({{ $item->id }})" title="Edit" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-pencil-alt" style="font-size: 11px;"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteNilaiNormal({{ $item->id }})" title="Hapus" style="color: #337ab7; text-decoration: none;">
                                <i class="fas fa-trash" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->id }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->masterLab ? $item->masterLab->nama_pemeriksaan : '-' }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->kategori }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->detail_lab }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->nama_setting }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->satuan }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->bentuk_nilai }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->nilai_normal }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->keterangan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-4 text-muted">Tidak ada data setting nilai normal ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah / Edit Nilai Normal -->
<div class="modal fade" id="modalTambahNilaiNormal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 3px;">
            <div class="modal-header py-2 px-3" style="background-color: #3c8dbc; color: white;">
                <h6 class="modal-title" id="modalTitle" style="font-size: 14px; font-weight: 600;">Tambah Setting Nilai Normal Lab</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formNilaiNormal">
                @csrf
                <input type="hidden" id="normId" name="id">
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Pemeriksaan Lab</label>
                        <select name="master_lab_id" id="normLabId" class="form-select form-select-sm" required style="border-radius: 3px;">
                            @foreach($labs as $l)
                                <option value="{{ $l->id }}">{{ $l->nama_pemeriksaan }} ({{ $l->kode_lab }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Kategori</label>
                        <select name="kategori" id="normKategori" class="form-select form-select-sm" style="border-radius: 3px;">
                            <option value="Dewasa Laki-Laki">Dewasa Laki-Laki</option>
                            <option value="Dewasa Perempuan">Dewasa Perempuan</option>
                            <option value="Anak">Anak</option>
                            <option value="Neonatus">Neonatus</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Detail Lab</label>
                        <input type="text" name="detail_lab" id="normDetailLab" class="form-control form-control-sm" placeholder="Contoh: Haemoglobin" style="border-radius: 3px;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Nama Setting</label>
                        <input type="text" name="nama_setting" id="normNamaSetting" class="form-control form-control-sm" required placeholder="Contoh: Hb" style="border-radius: 3px;">
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold mb-1" style="font-size: 12px;">Satuan</label>
                            <input type="text" name="satuan" id="normSatuan" class="form-control form-control-sm" placeholder="Contoh: g/dL" style="border-radius: 3px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold mb-1" style="font-size: 12px;">Bentuk Nilai</label>
                            <select name="bentuk_nilai" id="normBentukNilai" class="form-select form-select-sm" style="border-radius: 3px;">
                                <option value="range">range</option>
                                <option value="kualitatif">kualitatif</option>
                                <option value="kuantitatif">kuantitatif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Nilai Normal</label>
                        <input type="text" name="nilai_normal" id="normNilaiNormal" class="form-control form-control-sm" placeholder="Contoh: 12 - 18" style="border-radius: 3px;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-1" style="font-size: 12px;">Keterangan</label>
                        <textarea name="keterangan" id="normKeterangan" rows="2" class="form-control form-control-sm" style="border-radius: 3px;"></textarea>
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
function editNilaiNormal(id) {
    fetch('/settinglabdetail/' + id + '/edit')
        .then(res => res.json())
        .then(data => {
            document.getElementById('modalTitle').innerText = 'Edit Setting Nilai Normal Lab';
            document.getElementById('normId').value = data.id;
            document.getElementById('normLabId').value = data.master_lab_id;
            document.getElementById('normKategori').value = data.kategori || 'Dewasa Laki-Laki';
            document.getElementById('normDetailLab').value = data.detail_lab || '';
            document.getElementById('normNamaSetting').value = data.nama_setting || '';
            document.getElementById('normSatuan').value = data.satuan || '';
            document.getElementById('normBentukNilai').value = data.bentuk_nilai || 'range';
            document.getElementById('normNilaiNormal').value = data.nilai_normal || '';
            document.getElementById('normKeterangan').value = data.keterangan || '';

            var modal = new bootstrap.Modal(document.getElementById('modalTambahNilaiNormal'));
            modal.show();
        });
}

function deleteNilaiNormal(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data setting nilai normal ini?')) {
        fetch('/settinglabdetail/' + id, {
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

document.getElementById('formNilaiNormal').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('normId').value;
    const url = id ? ('/settinglabdetail/' + id) : '/settinglabdetail';
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
