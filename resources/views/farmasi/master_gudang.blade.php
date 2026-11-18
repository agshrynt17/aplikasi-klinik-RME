@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Gudang')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES Screenshot 1) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Data Gudang
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Gudang</span>
        </div>
    </div>

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

    <!-- Filter Bar (1:1 KONHAKES Screenshot 1) -->
    <div class="mb-3">
        <form method="GET" action="/gudang/index" class="d-flex flex-wrap align-items-end gap-2">
            <div style="width: 180px;">
                <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kode Gudang</label>
                <input type="text" name="kode_gudang" class="form-control form-control-sm" value="{{ request('kode_gudang') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
            </div>
            <div style="width: 180px;">
                <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Nama Gudang</label>
                <input type="text" name="nama_gudang" class="form-control form-control-sm" value="{{ request('nama_gudang') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
            </div>
            <div class="d-flex gap-1">
                <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; font-size: 12px; padding: 0 16px; border-radius: 3px; font-weight: 500;">
                    Search
                </button>
                <a href="/gudang/index" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; padding: 0 16px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                    Reset
                </a>
                <a href="/gudang/create" class="btn text-white btn-sm ms-1" style="background-color: #00a65a; border-color: #008d4c; height: 32px; font-size: 12px; padding: 0 16px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                    Tambah Data
                </a>
            </div>
        </form>
    </div>

    <!-- Menampilkan item count text (Right-aligned 1:1 KONHAKES Screenshot 1) -->
    <div class="text-end mb-2" style="font-size: 12px; color: #333;">
        Menampilkan <strong>{{ $gudangs->firstItem() ?? 0 }}-{{ $gudangs->lastItem() ?? 0 }}</strong> dari <strong>{{ $gudangs->total() }}</strong> item.
    </div>

    <!-- Data Table Container (1:1 KONHAKES Screenshot 1) -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 3px; margin-bottom: 20px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 12px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="text-center" style="width: 45px; padding: 8px 6px; font-weight: 600; color: #333;">#</th>
                        <th style="padding: 8px 12px; width: 250px; font-weight: 600; color: #337ab7;">Kode Gudang</th>
                        <th style="padding: 8px 12px; font-weight: 600; color: #337ab7;">Nama Gudang</th>
                        <th class="text-end" style="padding: 8px 16px; width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gudangs as $index => $item)
                    <tr style="border-bottom: 1px solid #f4f4f4;">
                        <td class="text-center" style="padding: 9px 6px; color: #555;">{{ $gudangs->firstItem() + $index }}</td>
                        <td style="padding: 9px 12px; color: #333;">
                            {{ $item->kode_gudang }}
                        </td>
                        <td style="padding: 9px 12px; color: #333;">
                            {{ $item->nama_gudang }}
                        </td>
                        <td class="text-end" style="padding: 9px 16px;">
                            <div class="d-inline-flex align-items-center gap-2">
                                <a href="javascript:void(0)" class="text-decoration-none btn-detail" 
                                   data-id="{{ $item->id }}"
                                   data-kode="{{ $item->kode_gudang }}"
                                   data-nama="{{ $item->nama_gudang }}"
                                   data-lokasi="{{ $item->lokasi ?: '-' }}"
                                   data-ket="{{ $item->keterangan ?: '-' }}"
                                   title="Detail">
                                    <i class="fas fa-eye text-info" style="font-size: 13px;"></i>
                                </a>
                                <a href="/gudang/{{ $item->id }}/edit" class="text-decoration-none" title="Edit">
                                    <i class="fas fa-pencil-alt text-info" style="font-size: 13px;"></i>
                                </a>
                                <form action="/gudang/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus gudang ini?');" style="display: inline; margin: 0; padding: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;" title="Hapus">
                                        <i class="fas fa-trash text-info" style="font-size: 13px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4" style="font-size: 12px;">
                            Tidak ada data gudang yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gudangs->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $gudangs->firstItem() }} sampai {{ $gudangs->lastItem() }} dari {{ $gudangs->total() }} entri
            </div>
            <div>
                {{ $gudangs->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Gudang -->
<div class="modal fade" id="modalDetailGudang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <div class="modal-header py-2 px-3" style="background-color: #00c0ef; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 14px;"><i class="fas fa-info-circle me-1"></i> Detail Data Gudang</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12.5px;">
                <table class="table table-sm table-bordered mb-0">
                    <tr>
                        <th style="width: 130px; background-color: #f9f9f9;">Kode Gudang</th>
                        <td id="detailKode" class="fw-bold text-primary"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Nama Gudang</th>
                        <td id="detailNama" class="fw-bold"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Lokasi</th>
                        <td id="detailLokasi"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Keterangan</th>
                        <td id="detailKet"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer py-2 px-3 justify-content-between">
                <div>
                    <a href="#" id="detailBtnEdit" class="btn btn-sm btn-info text-white" style="font-size: 12px; border-radius: 3px;">
                        <i class="fas fa-edit me-1"></i> Edit Data
                    </a>
                </div>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px; border-radius: 3px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalDetailEl = document.getElementById('modalDetailGudang');
    if (modalDetailEl) {
        const modalDetail = new bootstrap.Modal(modalDetailEl);
        document.querySelectorAll('.btn-detail').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('detailKode').innerText = this.getAttribute('data-kode');
                document.getElementById('detailNama').innerText = this.getAttribute('data-nama');
                document.getElementById('detailLokasi').innerText = this.getAttribute('data-lokasi');
                document.getElementById('detailKet').innerText = this.getAttribute('data-ket');
                document.getElementById('detailBtnEdit').href = '/gudang/' + id + '/edit';

                modalDetail.show();
            });
        });
    }
});
</script>
@endsection
