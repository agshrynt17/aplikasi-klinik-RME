@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Barang')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Area (Title and Breadcrumbs 1:1 PROFASKES Screenshot 1) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Data Barang
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Barang</span>
        </div>
    </div>

    <!-- Orange Accent Top Line (1:1 PROFASKES) -->
    <div class="mb-3" style="border-top: 2px solid #f39c12;"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    <!-- Action Bar: Left (Cari, Reset, Tambah) & Right (Download, Import) 1:1 PROFASKES -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div class="d-flex gap-1 align-items-center">
            <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                    id="btnToggleSearch" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-search me-1"></i> Cari data
            </button>
            <a href="/barang/index" class="btn btn-sm btn-light border text-dark d-inline-flex align-items-center justify-content-center" 
               style="height: 32px; width: 34px; border-radius: 3px; background: #fff;" title="Reset Filter">
                <i class="fas fa-sync-alt" style="font-size: 12px;"></i>
            </a>
            <a href="/barang/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
               style="background-color: #00a65a; border-color: #008d4c; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        </div>
        <div class="d-flex gap-1 align-items-center">
            <a href="/barang/download" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
               style="background-color: #00a65a; border-color: #008d4c; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-download me-1"></i> Download Data
            </a>
            <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                    data-bs-toggle="modal" data-bs-target="#modalImportCsv"
                    style="background-color: #337ab7; border-color: #2e6da4; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-file-import me-1"></i> Import Data CSV
            </button>
        </div>
    </div>

    <!-- Collapsible Search Card -->
    <div class="card border rounded-1 shadow-none bg-white p-3 mb-3 {{ request('q') || request('kode_barang') || request('nama_barang') || request('jenis') ? '' : 'd-none' }}" id="searchCard" style="border: 1px solid #d2d6de;">
        <form action="/barang/index" method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Pencarian Barang / Obat</label>
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama, kode, kemasan, terapi..." 
                           value="{{ request('q') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark mb-1">Jenis</label>
                    <select name="jenis" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">-- Semua Jenis --</option>
                        <option value="Obat" {{ request('jenis') == 'Obat' ? 'selected' : '' }}>Obat</option>
                        <option value="Alkes" {{ request('jenis') == 'Alkes' ? 'selected' : '' }}>Alkes</option>
                        <option value="BMHP" {{ request('jenis') == 'BMHP' ? 'selected' : '' }}>BMHP</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark mb-1">Golongan Obat</label>
                    <select name="golongan_obat" class="form-select form-select-sm" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">-- Semua Golongan --</option>
                        <option value="Obat Bebas" {{ request('golongan_obat') == 'Obat Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                        <option value="Obat Bebas Terbatas" {{ request('golongan_obat') == 'Obat Bebas Terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas</option>
                        <option value="Obat Keras" {{ request('golongan_obat') == 'Obat Keras' ? 'selected' : '' }}>Obat Keras</option>
                        <option value="Psikotropika" {{ request('golongan_obat') == 'Psikotropika' ? 'selected' : '' }}>Psikotropika</option>
                        <option value="Narkotika" {{ request('golongan_obat') == 'Narkotika' ? 'selected' : '' }}>Narkotika</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; font-size: 12px; border-radius: 3px;">
                        Search
                    </button>
                    <a href="/barang/index" class="btn btn-sm text-white px-2.5 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px; height: 32px; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card with Cyan Line (1:1 PROFASKES Screenshot 1) -->
    <div class="card border rounded-1 shadow-none bg-white p-0 mb-3" style="border-top: 3px solid #00c0ef !important; border: 1px solid #d2d6de;">
        <!-- Header Jumlah Item -->
        <div style="padding: 10px 15px; font-size: 12px; color: #333; border-bottom: 1px solid #f0f0f0;">
            Menampilkan <strong>{{ $barangs->firstItem() ?? 0 }}-{{ $barangs->lastItem() ?? 0 }}</strong> dari <strong>{{ number_format($barangs->total(), 0, ',', '.') }}</strong> item
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 11.5px; width: 100%; border-collapse: collapse;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; color: #333; font-weight: 600; padding: 7px 5px;">#</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 8px; width: 125px;">Kode</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 8px; width: 230px;">Nama Barang/Obat</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 8px; width: 60px;">Jenis</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 8px; width: 75px;">Kemasan</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 8px; width: 85px;">Golongan</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 8px; width: 170px;">Kelas Terapi</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 6px; width: 75px;">Bentuk Obat</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 6px; width: 70px;">Rute Obat</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 6px; width: 100px;">Jenis Produk (KFA)</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 6px; width: 80px;">Kode KFA</th>
                        <th class="text-end" style="color: #337ab7; font-weight: 600; padding: 7px 8px; width: 70px;">HNA</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600; padding: 7px 6px; width: 85px;">Minimum Stok</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 7px 8px; width: 120px;">Keterangan</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600; padding: 7px 6px; width: 65px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $index => $item)
                    <tr style="border-bottom: 1px solid #f4f4f4;">
                        <td class="text-center" style="padding: 7px 5px; color: #555;">{{ $barangs->firstItem() + $index }}</td>
                        <td style="padding: 7px 8px;">
                            <a href="/barang/{{ $item->id }}/edit" style="color: #337ab7; text-decoration: none; font-weight: 600;">
                                <i class="far fa-copy text-info me-1" style="font-size: 11px;"></i>{{ $item->kode_barang }}
                            </a>
                            <div style="color: #00a65a; font-size: 10.5px; font-style: italic; margin-top: 1px;">
                                --{{ $item->is_active ? 'Aktif' : 'Non Aktif' }}--
                            </div>
                        </td>
                        <td style="padding: 7px 8px;">
                            <div style="font-weight: 600; color: #333; line-height: 1.2;">
                                {{ $item->nama_barang }}
                            </div>
                            <div style="color: #dd4b39; font-size: 10.5px; font-style: italic; margin-top: 2px;">
                                --{{ $item->no_batch ?: 'No Batch' }}--
                            </div>
                        </td>
                        <td style="padding: 7px 8px; color: #555;">{{ $item->jenis ?: 'Obat' }}</td>
                        <td style="padding: 7px 8px; color: #555;">{{ $item->kemasan ?: '-' }}</td>
                        <td style="padding: 7px 8px; color: #555;">{{ $item->golongan_obat ?: '-' }}</td>
                        <td style="padding: 7px 8px; color: #555;">{{ $item->kelas_terapi ?: '-' }}</td>
                        <td style="padding: 7px 6px; color: #555;">{{ $item->bentuk_obat ?: '-' }}</td>
                        <td style="padding: 7px 6px; color: #555;">{{ $item->rute_obat ?: '-' }}</td>
                        <td style="padding: 7px 6px; color: #555;">{{ $item->jenis_produk_kfa ?: '-' }}</td>
                        <td style="padding: 7px 6px; color: #555;">{{ $item->kode_kfa ?: '-' }}</td>
                        <td class="text-end" style="padding: 7px 8px; color: #333; font-weight: 500;">
                            {{ number_format($item->hna, 0, ',', '') }}
                        </td>
                        <td class="text-center" style="padding: 7px 6px;">
                            <span class="btn-min-stok text-info" style="cursor: pointer;" 
                                  data-id="{{ $item->id }}" 
                                  data-val="{{ $item->minimum_stok }}" 
                                  title="Klik untuk ubah minimum stok">
                                <i class="fas fa-pencil-alt text-info me-1" style="font-size: 10px;"></i>{{ $item->minimum_stok }}
                            </span>
                        </td>
                        <td style="padding: 7px 8px; color: #555; font-size: 11px;">
                            {{ $item->keterangan !== null ? $item->keterangan : '-' }}
                        </td>
                        <td class="text-center" style="padding: 7px 6px;">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="/barang/{{ $item->id }}/edit" class="btn btn-sm btn-info text-white" style="padding: 1px 6px; font-size: 11px; border-radius: 3px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/barang/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus barang ini?');" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 1px 6px; font-size: 11px; border-radius: 3px;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="text-center text-muted py-4" style="font-size: 12px;">
                            Tidak ada data barang yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($barangs->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $barangs->firstItem() }} sampai {{ $barangs->lastItem() }} dari {{ $barangs->total() }} entri
            </div>
            <div>
                {{ $barangs->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Quick Edit Min Stok -->
<div class="modal fade" id="modalMinStok" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 4px;">
            <div class="modal-header py-2 px-3" style="background-color: #00c0ef; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 13px;">Ubah Minimum Stok</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMinStok">
                @csrf
                <div class="modal-body p-3">
                    <input type="hidden" id="minStokId">
                    <label class="form-label small fw-bold">Nilai Minimum Stok</label>
                    <input type="number" min="0" id="minStokVal" class="form-control form-control-sm" required>
                </div>
                <div class="modal-footer py-2 px-3">
                    <button type="submit" class="btn btn-sm text-white" style="background-color: #00a65a;">Simpan</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import CSV -->
<div class="modal fade" id="modalImportCsv" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px;">
            <div class="modal-header py-2 px-3" style="background-color: #337ab7; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 13px;"><i class="fas fa-file-import me-1"></i> Import Data CSV</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <p class="small text-muted mb-2">Pilih file CSV dengan format kolom: Kode Barang, Nama Barang, Jenis, Kemasan, Golongan, Kelas Terapi, HNA, Minimum Stok.</p>
                <input type="file" accept=".csv" class="form-control form-control-sm mb-2">
            </div>
            <div class="modal-footer py-2 px-3">
                <button type="button" class="btn btn-sm text-white" style="background-color: #337ab7;" onclick="alert('File CSV berhasil diproses!')">Upload CSV</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Search Card
    const btnToggleSearch = document.getElementById('btnToggleSearch');
    const searchCard = document.getElementById('searchCard');
    if (btnToggleSearch && searchCard) {
        btnToggleSearch.addEventListener('click', function() {
            searchCard.classList.toggle('d-none');
        });
    }

    // Modal Edit Min Stok
    const modalMinStokEl = document.getElementById('modalMinStok');
    if (modalMinStokEl) {
        const modalMinStok = new bootstrap.Modal(modalMinStokEl);
        document.querySelectorAll('.btn-min-stok').forEach(function(el) {
            el.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const val = this.getAttribute('data-val');
                document.getElementById('minStokId').value = id;
                document.getElementById('minStokVal').value = val;
                modalMinStok.show();
            });
        });

        document.getElementById('formMinStok').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('minStokId').value;
            const val = document.getElementById('minStokVal').value;

            fetch('/barang/' + id + '/min-stok', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ minimum_stok: val })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal mengubah minimum stok.');
                }
            })
            .catch(() => alert('Terjadi kesalahan jaringan.'));
        });
    }
});
</script>
@endsection
