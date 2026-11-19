@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Pilih Layanan Aktif Tanggal ' . $formattedTanggal . ' - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<style>
    .page-header-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .page-title-text {
        font-size: 20px;
        font-weight: 700;
        color: #333333;
        margin: 0;
    }
    .breadcrumb-custom {
        font-size: 11.5px;
        margin: 0;
        color: #777;
    }
    .breadcrumb-custom a {
        color: #337ab7;
        text-decoration: none;
    }

    /* Container Card with Top Blue Line */
    .card-layanan-container {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-top: 3px solid #3c8dbc;
        border-radius: 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        padding: 22px 20px 30px 20px;
    }

    /* Layanan Box / Card */
    .layanan-card {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 3px;
        display: flex;
        align-items: stretch;
        min-height: 82px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        text-decoration: none !important;
        color: inherit;
        overflow: hidden;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }
    .layanan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #3c8dbc;
        color: inherit;
    }

    .layanan-icon-box {
        width: 80px;
        min-width: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
    }
    .layanan-icon-box.bg-semua {
        background-color: #00a65a; /* Green */
    }
    .layanan-icon-box.bg-pendaftaran {
        background-color: #0073b7; /* Navy Blue */
    }
    .layanan-icon-box.bg-farmasi {
        background-color: #00c0ef; /* Cyan */
    }
    .layanan-icon-box.bg-poli {
        background-color: #f39c12; /* Orange */
    }

    .layanan-icon-box .letter-badge {
        font-size: 36px;
        font-weight: 700;
        line-height: 1;
        letter-spacing: -1px;
    }
    .layanan-icon-box i {
        font-size: 32px;
    }

    .layanan-content-box {
        padding: 8px 14px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.25;
        flex: 1;
    }
    .layanan-title {
        font-size: 13px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 2px;
    }
    .layanan-subtitle {
        font-size: 12px;
        color: #555555;
    }
    .layanan-jam {
        font-size: 11.5px;
        font-weight: 700;
        color: #333333;
        margin-top: 1px;
    }
    .layanan-terisi {
        font-size: 12px;
        font-weight: 700;
        color: #0073b7;
        margin-top: 2px;
    }
    .layanan-hint {
        font-size: 10.5px;
        color: #888888;
        margin-top: 2px;
    }
</style>

<div class="container-fluid p-3">
    <!-- Page Header & Breadcrumb -->
    <div class="page-header-box">
        <h1 class="page-title-text">Pilih Layanan Aktif Tanggal {{ $formattedTanggal }}</h1>
        <div class="breadcrumb-custom">
            <a href="/dashboard">Beranda</a> &gt; <span>Pilih Layanan Aktif Tanggal {{ $formattedBreadcrumb }}</span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="card-layanan-container">
        <div class="row g-3">
            <!-- 1. Tampilkan Semua Layanan -->
            <div class="col-lg-3 col-md-6 col-12">
                <a href="/dashboard-antrian/view-all?tanggal_antrian={{ $tanggal }}" target="_blank" class="layanan-card">
                    <div class="layanan-icon-box bg-semua">
                        <i class="fas fa-th-list"></i>
                    </div>
                    <div class="layanan-content-box">
                        <div class="layanan-subtitle">Tampilkan</div>
                        <div class="layanan-title">Semua Layanan</div>
                    </div>
                </a>
            </div>

            <!-- 2. Dashboard Pendaftaran -->
            <div class="col-lg-3 col-md-6 col-12">
                <a href="/dashboard-antrian/view/1?tanggal_antrian={{ $tanggal }}" target="_blank" class="layanan-card">
                    <div class="layanan-icon-box bg-pendaftaran">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="layanan-content-box">
                        <div class="layanan-title">Dashboard Pendaftaran</div>
                    </div>
                </a>
            </div>

            <!-- 3. Dashboard Farmasi -->
            <div class="col-lg-3 col-md-6 col-12">
                <a href="/dashboard-antrian/view/3?tanggal_antrian={{ $tanggal }}" target="_blank" class="layanan-card">
                    <div class="layanan-icon-box bg-farmasi">
                        <i class="fas fa-medkit"></i>
                    </div>
                    <div class="layanan-content-box">
                        <div class="layanan-title">Dashboard Farmasi</div>
                    </div>
                </a>
            </div>

            <!-- 4. POLI DOKTER (A) -->
            @foreach($poliCards as $poli)
            <div class="col-lg-3 col-md-6 col-12">
                <a href="/dashboard-antrian/view/{{ $poli['loket_id'] }}?tanggal_antrian={{ $tanggal }}" target="_blank" class="layanan-card">
                    <div class="layanan-icon-box bg-poli">
                        <span class="letter-badge">{{ $poli['kode'] }}</span>
                    </div>
                    <div class="layanan-content-box">
                        <div class="layanan-title">{{ $poli['nama'] }}</div>
                        <div class="layanan-jam">{{ $poli['jam'] }}</div>
                        <div class="layanan-terisi">{{ $poli['terisi'] }} terisi</div>
                        <div class="layanan-hint">klik untuk view</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
