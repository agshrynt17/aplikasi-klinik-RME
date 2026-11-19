@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Produsen')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Area (Title and Breadcrumbs 1:1 KONHAKES) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Data Produsen
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/dashboard" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Produsen</span>
        </div>
    </div>

    <!-- Orange Accent Top Line (1:1 KONHAKES) -->
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

    <!-- Action Bar: Cari Data (Left) & Tambah Data (Right) 1:1 KONHAKES -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-1 align-items-center">
            <button type="button" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
                    id="btnToggleSearch" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-search me-1"></i> Cari data
            </button>
            <a href="/produsen/index" class="btn btn-sm btn-light border text-dark d-inline-flex align-items-center justify-content-center" 
               style="height: 32px; width: 34px; border-radius: 3px; background: #fff;" title="Reset Filter">
                <i class="fas fa-sync-alt" style="font-size: 12px;"></i>
            </a>
        </div>
        <div>
            <a href="/produsen/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
               style="background-color: #00a65a; border-color: #008d4c; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        </div>
    </div>

    <!-- Collapsible Search Card -->
    <div class="card border rounded-1 shadow-none bg-white p-3 mb-3 {{ request('q') || request('kode_produsen') || request('nama_produsen') ? '' : 'd-none' }}" id="searchCard" style="border: 1px solid #d2d6de;">
        <form action="/produsen/index" method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-dark mb-1">Pencarian Produsen</label>
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari kode, nama, telp, alamat..." 
                           value="{{ request('q') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark mb-1">Kode Produsen</label>
                    <input type="text" name="kode_produsen" class="form-control form-control-sm" placeholder="PROD..." 
                           value="{{ request('kode_produsen') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-4 d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; font-size: 12px; border-radius: 3px;">
                        Search
                    </button>
                    <a href="/produsen/index" class="btn btn-sm text-white px-2.5 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px; height: 32px; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card with Cyan Line (1:1 KONHAKES) -->
    <div class="card border rounded-1 shadow-none bg-white p-0 mb-3" style="border-top: 3px solid #00c0ef !important; border: 1px solid #d2d6de;">
        <!-- Menampilkan item count header -->
        <div style="padding: 10px 15px; font-size: 12px; color: #333; border-bottom: 1px solid #f0f0f0;">
            Menampilkan <strong>{{ $produsens->firstItem() ?? 0 }}-{{ $produsens->lastItem() ?? 0 }}</strong> dari <strong>{{ $produsens->total() }}</strong> item
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 12px; width: 100%;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="text-center" style="width: 40px; color: #333; font-weight: 600; padding: 8px;">#</th>
                        <th class="text-center" style="width: 80px; padding: 8px;"></th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px; width: 140px;">Kode Produsen</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px; width: 220px;">Nama Produsen</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px; width: 130px;">Telp</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px; width: 130px;">Email</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px;">Alamat</th>
                        <th class="text-center" style="width: 80px; color: #337ab7; font-weight: 600; padding: 8px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produsens as $index => $item)
                    <tr style="border-bottom: 1px solid #f4f4f4;">
                        <td class="text-center" style="padding: 8px; color: #555;">{{ $produsens->firstItem() + $index }}</td>
                        <td class="text-center" style="padding: 8px;">
                            <button type="button" class="btn btn-xs text-white btn-detail" 
                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode_produsen }}"
                                    data-nama="{{ $item->nama_produsen }}"
                                    data-telp="{{ $item->telp }}"
                                    data-fax="{{ $item->fax }}"
                                    data-email="{{ $item->email }}"
                                    data-alamat="{{ $item->alamat }}"
                                    data-kontak="{{ $item->kontak_person }}"
                                    style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 2px 8px; border-radius: 3px; font-weight: 500;">
                                <i class="fas fa-info-circle me-1"></i> Detail
                            </button>
                        </td>
                        <td style="padding: 8px 12px;">
                            <a href="/produsen/{{ $item->id }}/edit" style="color: #333; text-decoration: none; font-weight: 500;">
                                {{ $item->kode_produsen }}
                            </a>
                        </td>
                        <td style="padding: 8px 12px; color: #333;">
                            {{ $item->nama_produsen }}
                        </td>
                        <td style="padding: 8px 12px; color: #555;">
                            {{ $item->telp ?: 'xxx' }}
                        </td>
                        <td style="padding: 8px 12px; color: #555;">
                            {{ $item->email ?: 'xxx' }}
                        </td>
                        <td style="padding: 8px 12px; color: #555; white-space: normal; max-width: 380px;">
                            {{ $item->alamat ?: 'xxx' }}
                        </td>
                        <td class="text-center" style="padding: 8px;">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="/produsen/{{ $item->id }}/edit" class="btn btn-sm btn-info text-white" style="padding: 2px 7px; font-size: 11px; border-radius: 3px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/produsen/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus produsen ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 2px 7px; font-size: 11px; border-radius: 3px;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4" style="font-size: 12px;">
                            Tidak ada data produsen yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($produsens->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $produsens->firstItem() }} sampai {{ $produsens->lastItem() }} dari {{ $produsens->total() }} entri
            </div>
            <div>
                {{ $produsens->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Produsen (1:1 KONHAKES) -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <div class="modal-header py-2 px-3" style="background-color: #00c0ef; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 14px;"><i class="fas fa-info-circle me-1"></i> Detail Data Produsen</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12.5px;">
                <table class="table table-sm table-bordered mb-0">
                    <tr>
                        <th style="width: 130px; background-color: #f9f9f9;">Kode Produsen</th>
                        <td id="detailKode" class="fw-bold text-primary"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Nama Produsen</th>
                        <td id="detailNama" class="fw-bold"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Telepon</th>
                        <td id="detailTelp"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Fax</th>
                        <td id="detailFax"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Email</th>
                        <td id="detailEmail"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Alamat</th>
                        <td id="detailAlamat"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #f9f9f9;">Kontak Person</th>
                        <td id="detailKontak"></td>
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
    // Toggle Search Card
    const btnToggleSearch = document.getElementById('btnToggleSearch');
    const searchCard = document.getElementById('searchCard');
    if (btnToggleSearch && searchCard) {
        btnToggleSearch.addEventListener('click', function() {
            searchCard.classList.toggle('d-none');
        });
    }

    // Modal Detail Handler
    const modalDetailEl = document.getElementById('modalDetail');
    if (modalDetailEl) {
        const modalDetail = new bootstrap.Modal(modalDetailEl);
        document.querySelectorAll('.btn-detail').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const kode = this.getAttribute('data-kode');
                const nama = this.getAttribute('data-nama');
                const telp = this.getAttribute('data-telp') || 'xxx';
                const fax = this.getAttribute('data-fax') || '-';
                const email = this.getAttribute('data-email') || 'xxx';
                const alamat = this.getAttribute('data-alamat') || 'xxx';
                const kontak = this.getAttribute('data-kontak') || '-';

                document.getElementById('detailKode').innerText = kode;
                document.getElementById('detailNama').innerText = nama;
                document.getElementById('detailTelp').innerText = telp;
                document.getElementById('detailFax').innerText = fax;
                document.getElementById('detailEmail').innerText = email;
                document.getElementById('detailAlamat').innerText = alamat;
                document.getElementById('detailKontak').innerText = kontak;
                document.getElementById('detailBtnEdit').href = '/produsen/' + id + '/edit';

                modalDetail.show();
            });
        });
    }
});
</script>
@endsection
