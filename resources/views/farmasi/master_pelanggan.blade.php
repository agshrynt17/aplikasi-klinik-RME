@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 KONHAKES) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            Data Pelanggan
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Pelanggan</span>
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
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px; margin-bottom: 20px;">
        <form method="GET" action="/pelanggan/index" class="d-flex flex-wrap align-items-end justify-content-between gap-2">
            <div class="d-flex flex-wrap align-items-end gap-2">
                <div style="width: 135px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" class="form-control form-control-sm" value="{{ request('nama_pelanggan') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div style="width: 135px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Telp</label>
                    <input type="text" name="telp" class="form-control form-control-sm" value="{{ request('telp') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div style="width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode Prop</label>
                    <input type="text" name="kode_prop" class="form-control form-control-sm" value="{{ request('kode_prop') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div style="width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode Kab</label>
                    <input type="text" name="kode_kab" class="form-control form-control-sm" value="{{ request('kode_kab') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div style="width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode Kec</label>
                    <input type="text" name="kode_kec" class="form-control form-control-sm" value="{{ request('kode_kec') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div style="width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #333; margin-bottom: 3px; display: block;">Kode Desa</label>
                    <input type="text" name="kode_desa" class="form-control form-control-sm" value="{{ request('kode_desa') }}" style="height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #f39c12; border-color: #e08e0b; height: 32px; font-size: 12px; padding: 0 16px; border-radius: 3px; font-weight: 500;">
                        Cari
                    </button>
                    <a href="/pelanggan/index" class="btn btn-sm" style="border: 1px solid #d2d6de; background: #fff; height: 32px; font-size: 12px; padding: 0 14px; display: inline-flex; align-items: center; justify-content: center; color: #333; border-radius: 3px;">
                        Reset
                    </a>
                </div>
            </div>

            <div>
                <a href="/pelanggan/create" class="btn text-white btn-sm" style="background-color: #00a65a; border-color: #008d4c; height: 32px; font-size: 12px; padding: 0 18px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center;">
                    Tambah
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table Container (1:1 KONHAKES Screenshot 1) -->
    <div style="background: #fff; border: 1px solid #d2d6de; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 12px; border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f4f4f4; border-bottom: 2px solid #d2d6de;">
                    <tr class="align-middle">
                        <th class="text-center" style="width: 35px; padding: 8px 6px; font-weight: 600; color: #333;">#</th>
                        <th style="padding: 8px 12px; width: 160px; font-weight: 600; color: #337ab7;">Kode Pelanggan</th>
                        <th style="padding: 8px 12px; width: 220px; font-weight: 600; color: #337ab7;">Nama Pelanggan</th>
                        <th style="padding: 8px 12px; width: 140px; font-weight: 600; color: #337ab7;">Telp</th>
                        <th style="padding: 8px 12px; width: 130px; font-weight: 600; color: #337ab7;">Tanggal Lahir</th>
                        <th style="padding: 8px 12px; font-weight: 600; color: #337ab7;">Alamat</th>
                        <th class="text-center" style="padding: 8px 10px; width: 110px; font-weight: 600; color: #337ab7;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $index => $item)
                    <tr class="align-middle">
                        <td class="text-center" style="padding: 8px 6px; color: #555;">{{ $pelanggans->firstItem() + $index }}</td>
                        <td style="padding: 8px 12px;">
                            <a href="/pelanggan/{{ $item->id }}/edit" style="color: #337ab7; font-weight: 600; text-decoration: none;">
                                {{ $item->kode_pelanggan }}
                            </a>
                        </td>
                        <td style="padding: 8px 12px; font-weight: 600; color: #333;">
                            {{ strtoupper($item->nama_pelanggan) }}
                        </td>
                        <td style="padding: 8px 12px; color: #555;">
                            {{ $item->telp ?: ($item->no_telepon ?: '-') }}
                        </td>
                        <td style="padding: 8px 12px; color: #555;">
                            {{ $item->tanggal_lahir ? date('d/m/Y', strtotime($item->tanggal_lahir)) : '-' }}
                        </td>
                        <td style="padding: 8px 12px; color: #555;">
                            {{ $item->alamat ?: '-' }}
                        </td>
                        <td class="text-center" style="padding: 8px 10px;">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="/pelanggan/{{ $item->id }}/edit" class="btn btn-sm btn-info text-white" style="padding: 2px 8px; font-size: 11px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/pelanggan/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 2px 8px; font-size: 11px;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 12px 15px; color: #555; font-size: 12px;">
                            Tidak ada data yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pelanggans->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <div style="font-size: 12px; color: #666;">
                Menampilkan {{ $pelanggans->firstItem() }} sampai {{ $pelanggans->lastItem() }} dari {{ $pelanggans->total() }} entri
            </div>
            <div>
                {{ $pelanggans->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
