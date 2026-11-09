@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">Data Perujuk Eksternal</h4>
        <div style="font-size: 11px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">Data Perujuk Eksternal</span>
        </div>
    </div>

    <!-- Filter Bar & Tambah Data Button -->
    <form method="GET" action="{{ url()->current() }}" id="filterForm">
        <div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-end flex-wrap gap-2">
                <!-- Nama Pengirim/Perujuk -->
                <div>
                    <label class="form-label mb-1" style="font-size: 12px; color: #333; font-weight: 500;">Nama Pengirim/Perujuk</label>
                    <input type="text" name="nama_perujuk" value="{{ request('nama_perujuk') }}" class="form-control form-control-sm" style="width: 200px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Faskes/Instansi Perujuk -->
                <div>
                    <label class="form-label mb-1" style="font-size: 12px; color: #333; font-weight: 500;">Faskes/Instansi Perujuk</label>
                    <input type="text" name="institusi" value="{{ request('institusi') }}" class="form-control form-control-sm" style="width: 200px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Alamat Faskes/Instansi -->
                <div>
                    <label class="form-label mb-1" style="font-size: 12px; color: #333; font-weight: 500;">Alamat Faskes/Instansi</label>
                    <input type="text" name="alamat" value="{{ request('alamat') }}" class="form-control form-control-sm" style="width: 260px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Status -->
                <div>
                    <label class="form-label mb-1" style="font-size: 12px; color: #333; font-weight: 500;">Status</label>
                    <select name="status" class="form-select form-select-sm" style="width: 130px; height: 32px; font-size: 12px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih--</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                </div>

                <!-- Tombol Cari & Reset -->
                <div class="d-flex align-items-center gap-1">
                    <button type="submit" class="btn text-white btn-sm" style="background-color: #337ab7; border-color: #2e6da4; height: 32px; width: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 3px;" title="Cari">
                        <i class="fas fa-search" style="font-size: 11px;"></i>
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-sm" style="border: 1px solid #d2d6de; border-radius: 3px; width: 34px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background-color: #fff; color: #444;" title="Reset">
                        <i class="fas fa-sync-alt" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>

            <!-- Tombol Tambah Data -->
            <div>
                <a href="/perujuk-eksternal/create" class="btn text-white btn-sm" style="background-color: #00a65a; border-color: #008d4c; font-size: 13px; font-weight: 500; border-radius: 3px; padding: 6px 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
        </div>
    </form>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size: 12px; border-radius: 3px;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Table Section -->
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size: 12px; border-collapse: collapse; width: 100%; border: 1px solid #e9ecef;">
            <thead>
                <tr style="background-color: #eef5f9; color: #337ab7; border-bottom: 1px solid #d2d6de;">
                    <th style="width: 35px; color: #337ab7; font-weight: 600; padding: 10px 8px; text-align: center;">#</th>
                    <th style="color: #337ab7; font-weight: 600; padding: 10px 8px; cursor: pointer;">
                        <span style="color: #337ab7;">Nama Pengirim/Perujuk</span>
                    </th>
                    <th style="color: #337ab7; font-weight: 600; padding: 10px 8px; cursor: pointer;">
                        <span style="color: #337ab7;">Faskes/Instansi Perujuk</span>
                    </th>
                    <th style="color: #337ab7; font-weight: 600; padding: 10px 8px; cursor: pointer;">
                        <span style="color: #337ab7;">No Telp</span>
                    </th>
                    <th style="color: #337ab7; font-weight: 600; padding: 10px 8px; cursor: pointer;">
                        <span style="color: #337ab7;">Alamat Faskes/Instansi</span>
                    </th>
                    <th style="width: 100px; color: #337ab7; font-weight: 600; padding: 10px 8px; cursor: pointer;">
                        <span style="color: #337ab7;">Status</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr style="border-bottom: 1px solid #f0f0f0; background: #fff;">
                    <td style="text-align: center; color: #333; padding: 10px 8px;">{{ $index + 1 }}</td>
                    <td style="color: #333; padding: 10px 8px; font-weight: 500;">
                        <a href="/perujuk-eksternal/{{ $item->id }}/edit" style="color: #337ab7; text-decoration: none;">
                            {{ $item->nama_perujuk }}
                        </a>
                    </td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->institusi ?: '-' }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->no_telepon ?: '-' }}</td>
                    <td style="color: #333; padding: 10px 8px;">{{ $item->alamat ?: '-' }}</td>
                    <td style="padding: 10px 8px;">
                        @if($item->is_active)
                            <span class="badge" style="background-color: #00a65a; font-weight: 500; font-size: 11px;">Aktif</span>
                        @else
                            <span class="badge" style="background-color: #dd4b39; font-weight: 500; font-size: 11px;">Non Aktif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr style="background: #fff;">
                    <td colspan="6" style="padding: 12px 10px; color: #555; background-color: #fff;">
                        Tidak ada data yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
