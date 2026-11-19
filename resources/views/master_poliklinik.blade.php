@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Master Poliklinik - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="master-poliklinik-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">Master Poliklinik</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Master Poliklinik</a></li>
            </ol>
        </nav>
    </div>

    <!-- Blue Accent Top Line -->
    <div class="mb-3" style="border-top: 2px solid #00c0ef;"></div>

    {{-- Alert Box --}}
    <div id="alertBox" class="alert d-none py-2 px-3 mb-3" role="alert" style="font-size: 13px;"></div>

    @if(session('success'))
    <div class="alert alert-success py-2 px-3 mb-3 small alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter Bar Card -->
    <div class="card border rounded-2 shadow-none bg-white p-3 mb-3">
        <form action="/master-poliklinik" method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-bold text-dark mb-1">Kode Unit</label>
                    <select name="kode_unit" class="form-select form-select-sm" id="filterUnit">
                        <option value="">--Pilih Unit--</option>
                        <option value="Pelayanan" {{ request('kode_unit') == 'Pelayanan' ? 'selected' : '' }}>Pelayanan</option>
                        <option value="Penunjang" {{ request('kode_unit') == 'Penunjang' ? 'selected' : '' }}>Penunjang</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-bold text-dark mb-1">Kode Poliklinik</label>
                    <input type="text" name="kode_poli" class="form-control form-control-sm" id="filterKodePoli" value="{{ request('kode_poli') }}">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-bold text-dark mb-1">Nama Poliklinik</label>
                    <input type="text" name="nama_poli" class="form-control form-control-sm" id="filterNamaPoli" value="{{ request('nama_poli') }}">
                </div>
                <div class="col-12 col-sm-6 col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: #337ab7; border-color: #2e6da4;">
                        Search
                    </button>
                    <a href="/master-poliklinik" class="btn btn-sm text-white px-2.5 fw-semibold" style="background-color: #f0ad4e; border-color: #eea236; min-width: 34px;" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                    <a href="/master-poliklinik/create" class="btn btn-sm text-white px-3 fw-semibold d-inline-flex align-items-center" id="btnTambahPoli" style="background-color: #5cb85c; border-color: #4cae4c; text-decoration: none;">
                        Tambah Data
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Total Item Count -->
    <div class="mb-2 text-dark" style="font-size: 13px;">
        Total <strong id="totalItemCounter">{{ count($data) }}</strong> item.
    </div>

    <!-- Data Table Card -->
    <div class="card border rounded-2 shadow-none bg-white p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0 text-nowrap poliklinik-table" id="tabelPoli" style="font-size: 12.5px;">
                <thead style="background-color: #ffffff; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th width="35" class="text-center" style="color: #333; font-weight: 600;">#</th>
                        <th width="40" class="text-center"></th>
                        <th style="color: #337ab7; font-weight: 600;">Kode Poliklinik</th>
                        <th style="color: #337ab7; font-weight: 600;">Nama Poliklinik</th>
                        <th style="color: #337ab7; font-weight: 600;">Unit</th>
                        <th style="color: #337ab7; font-weight: 600;">Wajib PPA</th>
                        <th style="color: #337ab7; font-weight: 600;">Jenis Poliklinik</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">IGD/ UGD</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Rehab Medik</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">MCU</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Laboratorium</th>
                        <th class="text-center" style="color: #337ab7; font-weight: 600;">Radiologi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center text-muted" style="font-size: 11.5px;">{{ $index + 1 }}</td>
                        <td class="text-center py-1">
                            <a href="/master-poliklinik/{{ $item->id }}/edit" class="btn btn-sm text-white py-0 px-2 rounded-1" style="background-color: #337ab7; border-color: #2e6da4; font-size: 11px; text-decoration: none;" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                        </td>
                        <td class="fw-semibold text-dark">{{ $item->kode_poli }}</td>
                        <td>{{ $item->nama_poli }}</td>
                        <td>{{ $item->unit ?? 'Pelayanan' }}</td>
                        <td>
                            <span class="badge" style="background-color: #00c0ef; color: white; font-weight: 700; font-size: 10.5px; padding: 3px 6px; border-radius: 3px;">
                                {{ $item->wajib_ppa ?? 'Ya' }}
                            </span>
                        </td>
                        <td>
                            @if(($item->jenis_poli ?? 'Poli Sakit') == 'Poli Sehat')
                                <span class="badge" style="background-color: #00a65a; color: white; font-weight: 700; font-size: 10.5px; padding: 3px 6px; border-radius: 3px;">
                                    Poli Sehat
                                </span>
                            @else
                                <span class="badge" style="background-color: #00b4d8; color: white; font-weight: 700; font-size: 10.5px; padding: 3px 6px; border-radius: 3px;">
                                    Poli Sakit
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_igd)
                                <span class="badge" style="background-color: #00a65a; color: white; padding: 3px 5px; font-size: 10px;">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_rehab_medik)
                                <span class="badge" style="background-color: #00a65a; color: white; padding: 3px 5px; font-size: 10px;">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_mcu)
                                <span class="badge" style="background-color: #00a65a; color: white; padding: 3px 5px; font-size: 10px;">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_lab)
                                <span class="badge" style="background-color: #00a65a; color: white; padding: 3px 5px; font-size: 10px;">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_radiologi)
                                <span class="badge" style="background-color: #00a65a; color: white; padding: 3px 5px; font-size: 10px;">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">Belum ada data Poliklinik.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .master-poliklinik-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .poliklinik-table th {
        font-size: 12px;
        padding: 9px 12px;
        vertical-align: middle;
        background-color: #ffffff;
    }
    .poliklinik-table td {
        padding: 8px 12px;
        vertical-align: middle;
        color: #333333;
    }
    .poliklinik-table tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }
    .poliklinik-table tbody tr:hover {
        background-color: #f1f5f9;
    }
</style>
@endsection
