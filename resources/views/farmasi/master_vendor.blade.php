@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Vendor')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Area (Title and Breadcrumbs 1:1 KONHAKES Screenshot 1) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Data Vendor
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Vendor</span>
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
            <a href="/vendor/index" class="btn btn-sm btn-light border text-dark d-inline-flex align-items-center justify-content-center" 
               style="height: 32px; width: 34px; border-radius: 3px; background: #fff;" title="Reset Filter">
                <i class="fas fa-sync-alt" style="font-size: 12px;"></i>
            </a>
        </div>
        <div>
            <a href="/vendor/create" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center" 
               style="background-color: #00a65a; border-color: #008d4c; height: 32px; font-size: 12px; border-radius: 3px;">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        </div>
    </div>

    <!-- Collapsible Search Card -->
    <div class="card border rounded-1 shadow-none bg-white p-3 mb-3 {{ request('q') || request('kode_vendor') || request('nama_vendor') ? '' : 'd-none' }}" id="searchCard" style="border: 1px solid #d2d6de;">
        <form action="/vendor/index" method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-dark mb-1">Pencarian Vendor</label>
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari kode, nama, alamat, telp..." 
                           value="{{ request('q') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark mb-1">Kode Vendor</label>
                    <input type="text" name="kode_vendor" class="form-control form-control-sm" placeholder="01, 02..." 
                           value="{{ request('kode_vendor') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="col-md-4 d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; font-size: 12px; border-radius: 3px;">
                        Search
                    </button>
                    <a href="/vendor/index" class="btn btn-sm text-white px-2.5 fw-semibold d-flex align-items-center justify-content-center" 
                       style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px; height: 32px; border-radius: 3px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card with Cyan Line (1:1 KONHAKES Screenshot 1) -->
    <div class="card border rounded-1 shadow-none bg-white p-0 mb-3" style="border-top: 3px solid #00c0ef !important; border: 1px solid #d2d6de;">
        <!-- Menampilkan item count header -->
        <div style="padding: 10px 15px; font-size: 12px; color: #333; border-bottom: 1px solid #f0f0f0;">
            Menampilkan <strong>{{ $vendors->firstItem() ?? 0 }}-{{ $vendors->lastItem() ?? 0 }}</strong> dari <strong>{{ $vendors->total() }}</strong> item
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 12px; width: 100%;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="text-center" style="width: 40px; color: #333; font-weight: 600; padding: 8px;">#</th>
                        <th class="text-center" style="width: 80px; padding: 8px;"></th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px; width: 130px;">
                            Kode Vendor <i class="fas fa-sort" style="font-size: 11px;"></i>
                        </th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px; width: 280px;">Nama Vendor</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px; width: 130px;">Telp</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px; width: 130px;">Email</th>
                        <th style="color: #337ab7; font-weight: 600; padding: 8px 12px;">Alamat</th>
                        <th class="text-center" style="width: 80px; color: #337ab7; font-weight: 600; padding: 8px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $index => $item)
                    <tr style="border-bottom: 1px solid #f4f4f4;">
                        <td class="text-center" style="padding: 8px; color: #555;">{{ $vendors->firstItem() + $index }}</td>
                        <td class="text-center" style="padding: 8px;">
                            <button type="button" class="btn btn-xs text-white btn-detail" 
                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode_vendor }}"
                                    data-nama="{{ $item->nama_vendor }}"
                                    data-join="{{ $item->join_date ? date('d/m/Y', strtotime($item->join_date)) : '-' }}"
                                    data-alamat="{{ $item->alamat ?: '-' }}"
                                    data-telp="{{ $item->telp ?: ($item->no_telepon ?: '-') }}"
                                    data-fax="{{ $item->fax ?: '-' }}"
                                    data-email="{{ $item->email ?: '-' }}"
                                    data-spk="{{ $item->nomor_spk ?: '-' }}"
                                    data-expspk="{{ $item->expired_spk ? date('d/m/Y', strtotime($item->expired_spk)) : '-' }}"
                                    data-pic="{{ $item->nama_pic ?: ($item->kontak_person ?: '-') }}"
                                    data-telppic="{{ $item->telp_pic ?: '-' }}"
                                    data-emailpic="{{ $item->email_pic ?: '-' }}"
                                    style="background-color: #00c0ef; border-color: #00acd6; font-size: 11px; padding: 2px 8px; border-radius: 3px; font-weight: 500;">
                                <i class="fas fa-info-circle me-1"></i> Detail
                            </button>
                        </td>
                        <td style="padding: 8px 12px;">
                            <a href="/vendor/{{ $item->id }}/edit" style="color: #333; text-decoration: none; font-weight: 500;">
                                {{ $item->kode_vendor }}
                            </a>
                        </td>
                        <td style="padding: 8px 12px; color: #333; font-weight: 500;">
                            {{ $item->nama_vendor }}
                        </td>
                        <td style="padding: 8px 12px; color: #555;">
                            {{ $item->telp ?: ($item->no_telepon ?: '') }}
                        </td>
                        <td style="padding: 8px 12px; color: #555;">
                            {{ $item->email ?: '' }}
                        </td>
                        <td style="padding: 8px 12px; color: #555; white-space: normal; max-width: 380px;">
                            {{ $item->alamat ?: '' }}
                        </td>
                        <td class="text-center" style="padding: 8px;">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="/vendor/{{ $item->id }}/edit" class="btn btn-sm btn-info text-white" style="padding: 2px 7px; font-size: 11px; border-radius: 3px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/vendor/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus vendor ini?');" style="display: inline;">
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
                            Tidak ada data vendor yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vendors->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $vendors->firstItem() }} sampai {{ $vendors->lastItem() }} dari {{ $vendors->total() }} entri
            </div>
            <div>
                {{ $vendors->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Vendor (1:1 KONHAKES) -->
<div class="modal fade" id="modalDetailVendor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <div class="modal-header py-2 px-3" style="background-color: #00c0ef; color: #fff;">
                <h6 class="modal-title fw-bold" style="font-size: 14px;"><i class="fas fa-info-circle me-1"></i> Detail Data Vendor</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 12.5px;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered mb-0">
                            <tr>
                                <th style="width: 140px; background-color: #f9f9f9;">Kode Vendor</th>
                                <td id="vKode" class="fw-bold text-primary"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Nama Vendor</th>
                                <td id="vNama" class="fw-bold"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Join Date</th>
                                <td id="vJoin"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Alamat</th>
                                <td id="vAlamat"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Telepon</th>
                                <td id="vTelp"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Fax</th>
                                <td id="vFax"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered mb-0">
                            <tr>
                                <th style="width: 140px; background-color: #f9f9f9;">Email</th>
                                <td id="vEmail"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Nomor SPK</th>
                                <td id="vSpk"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Expired SPK</th>
                                <td id="vExpSpk"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Nama PIC</th>
                                <td id="vPic"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Telp PIC</th>
                                <td id="vTelpPic"></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f9f9f9;">Email PIC</th>
                                <td id="vEmailPic"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 justify-content-between">
                <div>
                    <a href="#" id="vBtnEdit" class="btn btn-sm btn-info text-white" style="font-size: 12px; border-radius: 3px;">
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
    const modalDetailEl = document.getElementById('modalDetailVendor');
    if (modalDetailEl) {
        const modalDetail = new bootstrap.Modal(modalDetailEl);
        document.querySelectorAll('.btn-detail').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('vKode').innerText = this.getAttribute('data-kode');
                document.getElementById('vNama').innerText = this.getAttribute('data-nama');
                document.getElementById('vJoin').innerText = this.getAttribute('data-join');
                document.getElementById('vAlamat').innerText = this.getAttribute('data-alamat');
                document.getElementById('vTelp').innerText = this.getAttribute('data-telp');
                document.getElementById('vFax').innerText = this.getAttribute('data-fax');
                document.getElementById('vEmail').innerText = this.getAttribute('data-email');
                document.getElementById('vSpk').innerText = this.getAttribute('data-spk');
                document.getElementById('vExpSpk').innerText = this.getAttribute('data-expspk');
                document.getElementById('vPic').innerText = this.getAttribute('data-pic');
                document.getElementById('vTelpPic').innerText = this.getAttribute('data-telppic');
                document.getElementById('vEmailPic').innerText = this.getAttribute('data-emailpic');
                document.getElementById('vBtnEdit').href = '/vendor/' + id + '/edit';

                modalDetail.show();
            });
        });
    }
});
</script>
@endsection
