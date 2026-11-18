<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Klinik Telagasari 24 Jam - Dashboard')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='46' fill='%23004aad'/><rect x='44' y='22' width='12' height='56' rx='4' fill='white'/><rect x='22' y='44' width='56' height='12' rx='4' fill='white'/></svg>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        /* Pagination Fix & Styling */
        nav[role="navigation"] svg,
        .pagination svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }
        .pagination {
            margin-bottom: 0 !important;
        }
        .page-item .page-link {
            font-size: 11.5px !important;
            padding: 4px 10px !important;
            color: #337ab7 !important;
            border-color: #d2d6de !important;
        }
        .page-item.active .page-link {
            background-color: #337ab7 !important;
            border-color: #2e6da4 !important;
            color: #fff !important;
        }

        /* Top Navbar */
        .top-navbar {
            background-color: #00a65a; /* Warna hijau khas */
            height: 56px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        
        .brand-logo {
            font-weight: bold;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 250px;
        }
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            border-right: 1px solid #dee2e6;
            transition: 0.3s;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        .user-panel {
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #f4f4f4;
        }

        .user-panel img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
        }

        .user-panel .info p {
            margin: 0;
            font-weight: bold;
            font-size: 14px;
        }

        .user-panel .info small {
            color: #28a745;
            font-size: 12px;
        }

        .nav-sidebar {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-sidebar li a {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f9f9f9;
        }

        .nav-sidebar li a:hover, .nav-sidebar li a.active {
            background-color: #f8f9fa;
            color: #00a65a;
        }

        .nav-sidebar li a i.menu-icon {
            width: 25px;
            color: #666;
        }

        .nav-sidebar li a:hover i.menu-icon, .nav-sidebar li a.active i.menu-icon {
            color: #00a65a;
        }

        /* Submenu */
        .nav-submenu {
            list-style: none;
            padding: 0;
            background-color: #f9f9f9;
            display: none;
        }

        .nav-sidebar li.open > .nav-submenu, .nav-submenu li.open > .nav-submenu {
            display: block;
        }

        .nav-submenu li a {
            padding: 10px 15px 10px 45px !important;
            font-size: 13px;
            color: #777;
            border-bottom: none;
            justify-content: flex-start;
        }

        .nav-submenu li a.active {
            color: #000 !important;
            font-weight: 700 !important;
        }

        .nav-submenu li a.active i.menu-icon {
            color: #000 !important;
        }
        
        .nav-submenu li a.toggle-submenu {
            justify-content: space-between !important;
        }

        .nav-submenu .nav-submenu {
            padding-left: 0 !important;
        }

        .nav-submenu .nav-submenu li a {
            padding-left: 65px !important;
        }

        .nav-submenu .nav-submenu .nav-submenu {
            padding-left: 0 !important;
            background-color: #f1f2f4;
        }

        .nav-submenu .nav-submenu .nav-submenu li a {
            padding-left: 80px !important;
        }

        /* Main Content */
        .content-wrapper {
            margin-left: 250px;
            margin-top: 56px;
            padding: 20px;
            min-height: calc(100vh - 98px);
            transition: 0.3s;
        }

        /* Main Footer - KONHAKES 1:1 */
        .main-footer {
            background: #fff;
            padding: 10px 20px;
            color: #444;
            border-top: 1px solid #d2d6de;
            font-size: 11.5px;
            margin-left: 250px;
            transition: 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 820;
        }

        .main-footer a {
            color: #337ab7;
            text-decoration: none;
        }

        .main-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .main-footer {
                margin-left: 0;
                flex-direction: column;
                gap: 5px;
                text-align: center;
            }
        }

        .page-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #00a65a;
            color: #333;
        }

        .info-box-cyan {
            background-color: #00c0ef;
            color: white;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .btn-cyan {
            background-color: #00c0ef;
            color: white;
            border: none;
        }
        .btn-cyan:hover {
            background-color: #00a3cb;
            color: white;
        }

        .card-custom {
            background: white;
            border-top: 3px solid #00c0ef;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="brand-logo" style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-bars" id="toggleSidebar" style="cursor: pointer; color: #ffffff; margin-right: 15px;"></i>
            
            <!-- CSS Logo -->
            <a href="/dashboard" style="text-decoration: none; display: flex; align-items: center;">
                <div style="position: relative; width: 40px; height: 40px; margin-right: 8px;">
                    <i class="fas fa-leaf" style="color: #61b15a; font-size: 28px; position: absolute; bottom: 0; left: 0; transform: rotate(-45deg);"></i>
                    <i class="fas fa-leaf" style="color: #5c3c92; font-size: 28px; position: absolute; bottom: 5px; left: -10px; transform: rotate(-90deg);"></i>
                    <i class="fas fa-leaf" style="color: #4a7c59; font-size: 40px; position: absolute; top: -5px; right: 0; transform: rotate(15deg); color: #3b5998;"></i>
                    <i class="fas fa-plus" style="color: #89cff0; font-size: 20px; position: absolute; top: 8px; right: 8px;"></i>
                </div>
                <div style="display: flex; flex-direction: column; line-height: 1.1;">
                    <div style="font-size: 0.85rem; font-weight: 900; letter-spacing: 0.5px;">
                        <span style="color: #0056b3;">KLINIK</span> <span style="color: #e60000;">24 JAM.</span>
                    </div>
                    <div style="font-size: 1.6rem; font-weight: 800; color: #1e5647; letter-spacing: -0.5px; font-family: 'Arial', sans-serif;">
                        Telagasari
                    </div>
                </div>
            </a>
        </div>
        @php
            $u = auth()->user();
            $userName = $u ? $u->name : 'Admin Klinik';
            $userRole = $u ? $u->role_name : 'Administrator';
            $avatarName = urlencode($userName);
        @endphp
        <div class="navbar-user d-flex align-items-center">
            <div class="dropdown">
                <a class="text-white text-decoration-none dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer; font-size: 12.5px; font-weight: 600;">
                    <i class="fas fa-user-circle" style="font-size: 16px;"></i>
                    <span>{{ $userName }} <span class="badge bg-light text-dark ms-1" style="font-size: 10px; font-weight: 700;">{{ $userRole }}</span></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="position: absolute;">
                    <li><div class="dropdown-header text-dark fw-bold py-1">{{ $userName }}</div></li>
                    <li><div class="dropdown-item-text text-muted small pb-2"><i class="fas fa-shield-alt text-primary me-1"></i> Role: {{ $userRole }}</div></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item" href="/user/admin"><i class="fas fa-user fa-sm fa-fw me-2 text-muted"></i> Profil Saya</a></li>
                    <li><a class="dropdown-item" href="/setting-user"><i class="fas fa-cogs fa-sm fa-fw me-2 text-muted"></i> Ganti Password</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item text-danger fw-bold" href="/logout"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i> Keluar (Logout)</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="user-panel d-flex align-items-center gap-3 p-3 border-bottom">
            <img src="https://ui-avatars.com/api/?name={{ $avatarName }}&background=00a65a&color=fff&rounded=true" alt="{{ $userName }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1.5px solid #e2e8f0;">
            <div class="info" style="overflow: hidden; max-width: 170px;">
                <p style="margin: 0; font-weight: 700; font-size: 13px; color: #2d3748; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $userName }}">{{ $userName }}</p>
                <small style="color: #007bff; font-size: 11px; font-weight: 600;"><i class="fas fa-circle text-success" style="font-size: 7px; vertical-align: middle;"></i> {{ $userRole }}</small>
            </div>
        </div>

        <ul class="nav-sidebar">
            <li>
                <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <span><i class="fas fa-home menu-icon"></i> Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/grafik" class="{{ request()->is('grafik*') || request()->is('site/grafik*') ? 'active' : '' }}">
                    <span><i class="fas fa-chart-bar menu-icon"></i> Grafik & Pencapaian</span>
                </a>
            </li>
            @if(!$u || $u->hasPermission(['profil_faskes', 'master_poliklinik', 'lokasi', 'sdm_pegawai', 'master_penjamin', 'master_tpa', 'master_tindakan', 'master_tarif_tindakan', 'manajemen_user', 'setting_hak_akses', 'akun_pembayaran', 'setting_lainnya']))
            <li class="{{ request()->is('profil-faskes*') || request()->is('profil-bisnis*') || request()->is('master-poliklinik*') || request()->is('klinik*') || request()->is('master-unit-layanan*') || request()->is('unit*') || request()->is('master-gedung*') || request()->is('master-lantai*') || request()->is('master-ruang*') || request()->is('master-pegawai*') || request()->is('pegawai*') || request()->is('master-penjamin*') || request()->is('penjamin*') || request()->is('master-tpa*') || request()->is('tpa*') || request()->is('master-tindakan*') || request()->is('tindakan*') || request()->is('master-tarif-tindakan*') || request()->is('tariftindakan*') || request()->is('setting-*') || request()->is('user/admin*') || request()->is('payment-account*') || request()->is('master-akun-pembayaran*') || request()->is('general-setting*') ? 'open' : '' }}">
                <a href="#" class="toggle-submenu">
                    <span><i class="fas fa-wrench menu-icon"></i> Setting Awal</span>
                    <i class="fas {{ request()->is('profil-faskes*') || request()->is('profil-bisnis*') || request()->is('master-poliklinik*') || request()->is('klinik*') || request()->is('master-unit-layanan*') || request()->is('unit*') || request()->is('master-gedung*') || request()->is('master-lantai*') || request()->is('master-ruang*') || request()->is('master-pegawai*') || request()->is('pegawai*') || request()->is('master-penjamin*') || request()->is('penjamin*') || request()->is('master-tpa*') || request()->is('tpa*') || request()->is('master-tindakan*') || request()->is('tindakan*') || request()->is('master-tarif-tindakan*') || request()->is('tariftindakan*') || request()->is('setting-*') || request()->is('user/admin*') || request()->is('payment-account*') || request()->is('master-akun-pembayaran*') || request()->is('general-setting*') ? 'fa-angle-down' : 'fa-angle-left' }}"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="/profil-faskes" class="{{ request()->is('profil-faskes*') || request()->is('profil-bisnis*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Profil Faskes</a></li>
                    <li><a href="/master-poliklinik" class="{{ request()->is('master-poliklinik*') || request()->is('klinik*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Poliklinik</a></li>
                    <li class="{{ request()->is('master-unit-layanan*') || request()->is('unit*') || request()->is('master-gedung*') || request()->is('master-lantai*') || request()->is('master-ruang*') ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-map-marker-alt menu-icon"></i> Lokasi</span>
                            <i class="fas {{ request()->is('master-unit-layanan*') || request()->is('unit*') || request()->is('master-gedung*') || request()->is('master-lantai*') || request()->is('master-ruang*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px;">
                            <li><a href="/master-unit-layanan" class="{{ request()->is('master-unit-layanan*') || request()->is('unit*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Unit Layanan</a></li>
                            <li><a href="/master-gedung" class="{{ request()->is('master-gedung*') || request()->is('gedung*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Gedung</a></li>
                            <li><a href="/master-lantai" class="{{ request()->is('master-lantai*') || request()->is('lantai*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Lantai</a></li>
                            <li><a href="/master-ruangan" class="{{ request()->is('master-ruangan*') || request()->is('ruang*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Ruang</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('master-pegawai*') || request()->is('pegawai*') ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-users-cog menu-icon"></i> SDM</span>
                            <i class="fas {{ request()->is('master-pegawai*') || request()->is('pegawai*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px;">
                            <li><a href="/master-pegawai" class="{{ request()->is('master-pegawai*') || request()->is('pegawai*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Pegawai</a></li>
                        </ul>
                    </li>
                    <li><a href="/master-penjamin" class="{{ request()->is('master-penjamin*') || request()->is('penjamin*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Penjamin</a></li>
                    <li><a href="/master-tpa" class="{{ request()->is('master-tpa*') || request()->is('tpa*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master TPA</a></li>
                    <li class="{{ request()->is('master-tindakan*') || request()->is('tindakan*') || request()->is('master-tarif-tindakan*') || request()->is('tariftindakan*') ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-stethoscope menu-icon"></i> Setting Tindakan</span>
                            <i class="fas {{ request()->is('master-tindakan*') || request()->is('tindakan*') || request()->is('master-tarif-tindakan*') || request()->is('tariftindakan*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px;">
                            <li><a href="/master-tindakan" class="{{ request()->is('master-tindakan*') || request()->is('tindakan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Tindakan</a></li>
                            <li><a href="/master-tarif-tindakan" class="{{ request()->is('master-tarif-tindakan*') || request()->is('tariftindakan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Tarif Tindakan</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('setting-user*') || request()->is('user/admin*') || request()->is('setting-role*') ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ request()->is('setting-user*') || request()->is('user/admin*') || request()->is('setting-role*') ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-user-shield menu-icon"></i> Setting User</span>
                            <i class="fas {{ request()->is('setting-user*') || request()->is('user/admin*') || request()->is('setting-role*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('setting-user*') || request()->is('user/admin*') || request()->is('setting-role*') ? 'display: block;' : '' }}">
                            <li><a href="/user/admin/index" class="{{ request()->is('setting-user*') || request()->is('user/admin*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Manajemen User</a></li>
                            <li><a href="/setting-role" class="{{ request()->is('setting-role*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Setting Hak Akses</a></li>
                        </ul>
                    </li>
                    <li><a href="/payment-account/index" class="{{ request()->is('payment-account*') || request()->is('master-akun-pembayaran*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Akun Pembayaran Klaim</a></li>
                    <li><a href="/general-setting/index" class="{{ request()->is('general-setting*') || request()->is('setting-lainnya*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Setting Lainnya</a></li>
                </ul>
            </li>
            @endif
            @php
                $isFoActive = request()->is('master-pasien*') || 
                              request()->is('pasien*') || 
                              request()->is('tambah-pasien*') || 
                              request()->is('pendaftaran*') || 
                              request()->is('pendaftaran-rajal*') || 
                              request()->is('transaksi-tindakan*') || 
                              request()->is('transaksitindakan*') || 
                              request()->is('tindakan-medis*') || 
                              request()->is('general-consent*') || 
                              request()->is('survei-kepuasan*') || 
                              request()->is('survei*') || 
                              request()->is('transaksi-kasir*') || 
                              request()->is('modul-kasir*');
                $isTindakanActive = request()->is('transaksi-tindakan*') || request()->is('transaksitindakan*') || request()->is('tindakan-medis*');
            @endphp
            @if(!$u || $u->hasPermission(['master_pasien', 'pendaftaran_rajal', 'antrian', 'general_consent']))
            <li class="{{ $isFoActive ? 'open' : '' }}" id="menuFrontOffice">
                <a href="#" class="toggle-submenu {{ $isFoActive ? 'active' : '' }}">
                    <span><i class="fas fa-desktop menu-icon"></i> Front Office</span>
                    <i class="fas {{ $isFoActive ? 'fa-angle-down' : 'fa-angle-left' }}"></i>
                </a>
                <ul class="nav-submenu" style="{{ $isFoActive ? 'display: block;' : '' }}">
                    <li><a href="/master-pasien" class="{{ request()->is('master-pasien*') || request()->is('pasien*') || request()->is('tambah-pasien*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Pasien</a></li>
                    <li><a href="/pendaftaran-rajal" class="{{ request()->is('pendaftaran*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Pendaftaran Rawat Jalan</a></li>
                    <li class="{{ $isTindakanActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isTindakanActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Tindakan Medis</span>
                            <i class="fas {{ $isTindakanActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ $isTindakanActive ? 'display: block;' : '' }}">
                            <li><a href="/transaksi-tindakan" class="{{ $isTindakanActive ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Transaksi Tindakan</a></li>
                        </ul>
                    </li>
                    <li><a href="/general-consent" class="{{ request()->is('general-consent*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> General Consent</a></li>
                    <li><a href="/survei" class="{{ request()->is('survei*') || request()->is('survei-kepuasan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Survei Kepuasan Pasien</a></li>
                    <li><a href="/transaksi-kasir" class="{{ request()->is('transaksi-kasir*') || request()->is('modul-kasir*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Transaksi Kasir</a></li>
                </ul>
            </li>
            @endif
            @php
                $isAntrianActive = request()->is('master-antrian*') || 
                                   request()->is('jadwal-antrian*') || 
                                   request()->is('antrian-jadwal*') || 
                                   request()->is('list-antrian*') || 
                                   request()->is('cetak-tiket*') || 
                                   request()->is('setting-slide*') || 
                                   request()->is('slide-antrian*') || 
                                   request()->is('dashboard-antrian*') || 
                                   request()->is('tv-antrian*') || 
                                   request()->is('bridging-*') || 
                                   request()->is('antrian-bpjs-setting*') || 
                                   request()->is('antrian-mjkn-log*') || 
                                   request()->is('pcare-pasien-baru*') || 
                                   request()->is('antrian-bpjs-log*');
            @endphp
            @if(!$u || $u->hasPermission(['antrian', 'pendaftaran_rajal', 'bridging_bpjs']))
            <li class="{{ $isAntrianActive ? 'open' : '' }}">
                <a href="#" class="toggle-submenu {{ $isAntrianActive ? 'active' : '' }}">
                    <span><i class="fas fa-bullhorn menu-icon"></i> Antrian</span>
                    <i class="fas {{ $isAntrianActive ? 'fa-angle-down' : 'fa-angle-left' }}"></i>
                </a>
                <ul class="nav-submenu" style="{{ $isAntrianActive ? 'display: block;' : '' }}">
                    <li><a href="/master-antrian" class="{{ request()->is('master-antrian*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Antrian</a></li>
                    <li><a href="/jadwal-antrian" class="{{ request()->is('jadwal-antrian*') || request()->is('antrian-jadwal*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Setting Jadwal Antrian</a></li>
                    <li><a href="/list-antrian" class="{{ request()->is('list-antrian*') || (request()->is('antrian*') && !request()->is('antrian-jadwal*')) ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> List Antrian</a></li>
                    <li><a href="/cetak-tiket" target="_blank" class="{{ request()->is('cetak-tiket*') ? 'active' : '' }}"><i class="fas fa-ticket-alt menu-icon"></i> Cetak Tiket Antrian</a></li>
                    <li><a href="/slide-antrian" class="{{ request()->is('setting-slide*') || request()->is('slide-antrian*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Setting Slide Dashboard</a></li>
                    <li><a href="/dashboard-antrian" class="{{ (request()->is('dashboard-antrian') || request()->is('dashboard-antrian/view*') || request()->is('tv-antrian*')) && !request()->is('dashboard-antrian/dashboard-task*') ? 'active' : '' }}"><i class="far fa-circle menu-icon"></i> Dashboard Antrian</a></li>
                    <li class="{{ request()->is('bridging-*') || request()->is('antrian-bpjs-setting*') || request()->is('antrian-mjkn-log*') || request()->is('pcare-pasien-baru*') || request()->is('antrian-bpjs-log*') || request()->is('dashboard-antrian/dashboard-task*') ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-angle-double-right menu-icon"></i> Bridging Antrol BPJS</span>
                            <i class="fas {{ request()->is('bridging-*') || request()->is('antrian-bpjs-setting*') || request()->is('antrian-mjkn-log*') || request()->is('pcare-pasien-baru*') || request()->is('antrian-bpjs-log*') || request()->is('dashboard-antrian/dashboard-task*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('bridging-*') || request()->is('antrian-bpjs-setting*') || request()->is('antrian-mjkn-log*') || request()->is('pcare-pasien-baru*') || request()->is('antrian-bpjs-log*') || request()->is('dashboard-antrian/dashboard-task*') ? 'display: block;' : '' }}">
                            <li><a href="/bridging-cek-akses" class="{{ request()->is('bridging-cek-akses*') || request()->is('antrian-bpjs-setting*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Cek Akses Bridging</a></li>
                            <li><a href="/bridging-log-antrian-mjkn" class="{{ request()->is('bridging-log-antrian-mjkn*') || request()->is('antrian-mjkn-log*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Log Antrian MJKN</a></li>
                            <li><a href="/bridging-log-pasien-baru" class="{{ request()->is('bridging-log-pasien-baru*') || request()->is('pcare-pasien-baru*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Log Pasien Baru MJKN</a></li>
                            <li><a href="/bridging-log-kirim-antrian" class="{{ request()->is('bridging-log-kirim-antrian*') || request()->is('antrian-bpjs-log*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Log Kirim Data Antrian</a></li>
                            <li><a href="/dashboard-antrian/dashboard-task-fktl" class="{{ request()->is('dashboard-antrian/dashboard-task*') || request()->is('bridging-dashboard-waktu*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Dashboard Waktu Pelayanan</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif
            @php
                $isLabActive = request()->is('laboratorium*') || 
                               request()->is('lab*') || 
                               request()->is('settinglabdetail*') || 
                               request()->is('tariflab*') || 
                               request()->is('transaksilab*') ||
                                request()->is('entryhasillab*') ||
                                request()->is('laporan/laporan-jasa-lab*') ||
                                request()->is('perujuk-eksternal*');
            @endphp
            @if(!$u || $u->hasPermission('laboratorium'))
            <li class="{{ $isLabActive ? 'open' : '' }}">
                <a href="#" class="toggle-submenu {{ $isLabActive ? 'active' : '' }}">
                    <span><i class="fas fa-flask menu-icon"></i> Laboratorium</span>
                    <i class="fas {{ $isLabActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                </a>
                <ul class="nav-submenu" style="{{ $isLabActive ? 'display: block;' : '' }}">
                    <li><a href="/lab/index" class="{{ request()->is('laboratorium/master-lab*') || request()->is('lab*') ? 'active' : '' }}"><i class="far fa-id-card menu-icon"></i> Master Lab</a></li>
                    <li><a href="/settinglabdetail/index" class="{{ request()->is('laboratorium/nilai-normal*') || request()->is('settinglabdetail*') ? 'active' : '' }}"><i class="fas fa-bars menu-icon"></i> Setting Nilai Normal</a></li>
                    <li><a href="/tariflab/index" class="{{ request()->is('laboratorium/tarif-lab*') || request()->is('tariflab*') ? 'active' : '' }}"><i class="fas fa-bars menu-icon"></i> Setting Tarif Lab</a></li>
                    <li><a href="/transaksilab/index" class="{{ request()->is('laboratorium/transaksi-lab*') || request()->is('transaksilab*') ? 'active' : '' }}"><i class="far fa-window-maximize menu-icon"></i> Transaksi Lab</a></li>
                    <li><a href="/entryhasillab/index" class="{{ request()->is('laboratorium/hasil-lab*') || request()->is('entryhasillab*') ? 'active' : '' }}"><i class="fas fa-bars menu-icon"></i> Hasil Pemeriksaan Lab</a></li>
                    <li><a href="/laporan/laporan-jasa-lab" class="{{ request()->is('laboratorium/lap-jasmed-lab*') || request()->is('laporan/laporan-jasa-lab*') ? 'active' : '' }}"><i class="fas fa-bars menu-icon"></i> Lap. Jasa Medis Lab</a></li>
                    <li><a href="/perujuk-eksternal/index" class="{{ request()->is('laboratorium/perujuk-eksternal*') || request()->is('perujuk-eksternal*') ? 'active' : '' }}"><i class="fas fa-bars menu-icon"></i> Data Perujuk Eksternal</a></li>
                </ul>
            </li>
            @endif
            @php
                $isRadActive = request()->is('radiologi*') || request()->is('rad*') || request()->is('tarifrad*') || request()->is('transaksirad*') || request()->is('entryhasilrad*');
            @endphp
            @if(!$u || $u->hasPermission('radiologi'))
            <li class="{{ $isRadActive ? 'open' : '' }}">
                <a href="#" class="toggle-submenu {{ $isRadActive ? 'active' : '' }}">
                    <span><i class="fas fa-heartbeat menu-icon"></i> Radio-Diagnostik</span>
                    <i class="fas {{ $isRadActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                </a>
                <ul class="nav-submenu" style="{{ $isRadActive ? 'display: block;' : '' }}">
                    <li><a href="/rad/index" class="{{ request()->is('radiologi/master*') || request()->is('rad*') ? 'active' : '' }}"><i class="far fa-id-card menu-icon"></i> Master Radiologi</a></li>
                    <li><a href="/tarifrad/index" class="{{ request()->is('radiologi/tarif*') || request()->is('tarifrad*') ? 'active' : '' }}"><i class="fas fa-bars menu-icon"></i> Setting Tarif Radiologi</a></li>
                    <li><a href="/transaksirad/index" class="{{ request()->is('radiologi/transaksi*') || request()->is('transaksirad*') ? 'active' : '' }}"><i class="far fa-window-maximize menu-icon"></i> Transaksi Radiologi</a></li>
                    <li><a href="/entryhasilrad/index" class="{{ request()->is('radiologi/hasil*') || request()->is('entryhasilrad*') ? 'active' : '' }}"><i class="fas fa-bars menu-icon"></i> Hasil Radiologi</a></li>
                </ul>
            </li>
            @endif
            @php
                $isRekamMedisActive = request()->is('rekam-medis*') || request()->is('diagnosa*') || request()->is('diagnosa-keperawatan*') || request()->is('prosedur*') || request()->is('asrajal*') || request()->is('asul*') || request()->is('asgizi*') || request()->is('asigd*') || request()->is('consent*') || request()->is('odon*') || request()->is('triase*') || request()->is('prabedah*') || request()->is('praanestesi*') || request()->is('laptindakan*') || request()->is('monitoring*') || request()->is('ohis*') || request()->is('surgical*') || request()->is('anc*') || request()->is('partograf*') || request()->is('moduldokter*') || request()->is('ppa*') || request()->is('skb*') || request()->is('skd*') || request()->is('sks*') || request()->is('skh*') || request()->is('skm*') || request()->is('sre*') || request()->is('skbn*') || request()->is('surat-keterangan*') || request()->is('inc*') || request()->is('laporan-external*') || request()->is('laporan-eksternal*');
                $isMasterDiagnosaActive = request()->is('diagnosa*') || request()->is('diagnosa-keperawatan*') || request()->is('rekam-medis/master-diagnosa*') || request()->is('rekam-medis/diagnosa-keperawatan*');
                $isMasterProsedurActive = request()->is('prosedur*') || request()->is('rekam-medis/master-prosedur*');
                $isAsesmenAwalRajalActive = request()->is('asrajal*') || request()->is('rekam-medis/asesmen-awal-rajal*');
                $isAsesmenUlangActive = request()->is('asul*') || request()->is('rekam-medis/asesmen-ulang*');
                $isAsesmenGiziActive = request()->is('asgizi*') || request()->is('rekam-medis/asesmen-gizi*');
                $isAsesmenIgdActive = request()->is('asigd*') || request()->is('rekam-medis/asesmen-igd*');
                $isInformedConsentActive = request()->is('consent*') || request()->is('rekam-medis/informed-consent*');
                $isOdontogramActive = request()->is('odon*') || request()->is('rekam-medis/odontogram*');
                $isTriaseActive = request()->is('triase*') || request()->is('rekam-medis/triase*');
                $isPraBedahActive = request()->is('prabedah*') || request()->is('rekam-medis/pra-bedah*');
                $isPraAnestesiActive = request()->is('praanestesi*') || request()->is('rekam-medis/pra-anestesi*');
                $isLapTindakanActive = request()->is('laptindakan*') || request()->is('rekam-medis/laporan-operasi*');
                $isMonitoringBedahActive = request()->is('monitoring*') || request()->is('rekam-medis/monitoring-bedah*');
                $isOhisActive = request()->is('ohis*') || request()->is('rekam-medis/ohis*');
                $isSscActive = request()->is('surgical*') || request()->is('rekam-medis/ssc*');
                $isAncActive = request()->is('anc*') || request()->is('rekam-medis/anc*');
                $isPartografActive = request()->is('partograf*') || request()->is('rekam-medis/partograf*');
                $isPpaActive = request()->is('moduldokter*') || request()->is('rekam-medis/ppa*') || request()->is('ppa*');
                $isSuratKeteranganActive = request()->is('skb*') || request()->is('skd*') || request()->is('sks*') || request()->is('skh*') || request()->is('skm*') || request()->is('sre*') || request()->is('skbn*') || request()->is('surat-keterangan*') || request()->is('rekam-medis/surat-keterangan*');
                $isIncActive = request()->is('inc*') || request()->is('rekam-medis/inc*');
                $isLaporanExternalActive = request()->is('laporan-external*') || request()->is('laporan-eksternal*');
            @endphp
            @if(!$u || $u->hasPermission(['rekam_medis', 'asesmen_rajal', 'asesmen_igd', 'odontogram', 'kebidanan', 'skrining']))
            <li class="{{ $isRekamMedisActive ? 'open' : '' }}">
                <a href="#" class="toggle-submenu {{ $isRekamMedisActive ? 'active' : '' }}">
                    <span><i class="fas fa-folder-open menu-icon"></i> Rekam Medis</span>
                    <i class="fas {{ $isRekamMedisActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                </a>
                <ul class="nav-submenu" style="{{ $isRekamMedisActive ? 'display: block;' : '' }}">
                    <li class="{{ $isMasterDiagnosaActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isMasterDiagnosaActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Master Diagnosa</span>
                            <i class="fas {{ $isMasterDiagnosaActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isMasterDiagnosaActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/diagnosa/index" class="{{ (request()->is('diagnosa*') && !request()->is('diagnosa-keperawatan*')) || request()->is('rekam-medis/master-diagnosa*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Diagnosa(ICD-10)</a></li>
                            <li><a href="/diagnosa-keperawatan/index" class="{{ request()->is('diagnosa-keperawatan*') || request()->is('rekam-medis/diagnosa-keperawatan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Diagnosa Keperawatan</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isMasterProsedurActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isMasterProsedurActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Master Prosedur</span>
                            <i class="fas {{ $isMasterProsedurActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isMasterProsedurActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/prosedur/index" class="{{ request()->is('prosedur*') || request()->is('rekam-medis/master-prosedur*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Prosedur</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isAsesmenAwalRajalActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isAsesmenAwalRajalActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Asesmen Awal Rajal</span>
                            <i class="fas {{ $isAsesmenAwalRajalActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isAsesmenAwalRajalActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/asrajal/index" class="{{ request()->is('asrajal*') || request()->is('rekam-medis/asesmen-awal-rajal*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Asesmen</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isAsesmenUlangActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isAsesmenUlangActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Asesmen Ulang</span>
                            <i class="fas {{ $isAsesmenUlangActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isAsesmenUlangActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/asul/index" class="{{ request()->is('asul*') || request()->is('rekam-medis/asesmen-ulang*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Asul</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isAsesmenGiziActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isAsesmenGiziActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Asesmen Gizi</span>
                            <i class="fas {{ $isAsesmenGiziActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isAsesmenGiziActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/asgizi/index" class="{{ request()->is('asgizi*') || request()->is('rekam-medis/asesmen-gizi*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Asesmen Gizi</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isAsesmenIgdActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isAsesmenIgdActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Asesmen IGD</span>
                            <i class="fas {{ $isAsesmenIgdActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isAsesmenIgdActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/asigd/index" class="{{ request()->is('asigd*') || request()->is('rekam-medis/asesmen-igd*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Data Asesmen IGD</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isInformedConsentActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isInformedConsentActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Informed Consent</span>
                            <i class="fas {{ $isInformedConsentActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isInformedConsentActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/consent/index" class="{{ request()->is('consent*') || request()->is('rekam-medis/informed-consent*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Informed Consent</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isOdontogramActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isOdontogramActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Odontogram</span>
                            <i class="fas {{ $isOdontogramActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isOdontogramActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/odon/index" class="{{ request()->is('odon*') || request()->is('rekam-medis/odontogram*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Odontogram</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isTriaseActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isTriaseActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Triase</span>
                            <i class="fas {{ $isTriaseActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isTriaseActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/triase/index" class="{{ request()->is('triase*') || request()->is('rekam-medis/triase*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Triase</a></li>
                        </ul>
                    </li>

                    <li class="{{ $isPraBedahActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isPraBedahActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Pengkajian Prabedah</span>
                            <i class="fas {{ $isPraBedahActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isPraBedahActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/prabedah/index" class="{{ request()->is('prabedah*') || request()->is('rekam-medis/pra-bedah*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Prabedah</a></li>
                        </ul>
                    </li>

                    <li class="{{ $isPraAnestesiActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isPraAnestesiActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Pengkajian Pra-Anestesi</span>
                            <i class="fas {{ $isPraAnestesiActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isPraAnestesiActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/praanestesi/index" class="{{ request()->is('praanestesi*') || request()->is('rekam-medis/pra-anestesi*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Pra Anestesi</a></li>
                        </ul>
                    </li>

                    <li class="{{ $isLapTindakanActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isLapTindakanActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Lap. Tindakan/Operasi</span>
                            <i class="fas {{ $isLapTindakanActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isLapTindakanActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/laptindakan/index" class="{{ request()->is('laptindakan*') || request()->is('rekam-medis/laporan-operasi*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Lap. Tindakan/Operasi</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isMonitoringBedahActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isMonitoringBedahActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Monitoring Tindakan/Bedah</span>
                            <i class="fas {{ $isMonitoringBedahActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isMonitoringBedahActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/monitoring/index" class="{{ request()->is('monitoring*') || request()->is('rekam-medis/monitoring-bedah*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Monitoring</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isOhisActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isOhisActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> OHI-S</span>
                            <i class="fas {{ $isOhisActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isOhisActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/ohis/index" class="{{ request()->is('ohis*') || request()->is('rekam-medis/ohis*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data OHI-S</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isSscActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isSscActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Surgical Safety Ceklist</span>
                            <i class="fas {{ $isSscActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isSscActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/surgical/index" class="{{ request()->is('surgical*') || request()->is('rekam-medis/ssc*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data SSC</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isAncActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isAncActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Ante Natal Care (ANC)</span>
                            <i class="fas {{ $isAncActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isAncActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/anc/index" class="{{ request()->is('anc*') || request()->is('rekam-medis/anc*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data ANC</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isPartografActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isPartografActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Partograf</span>
                            <i class="fas {{ $isPartografActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isPartografActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/partograf/index" class="{{ request()->is('partograf*') || request()->is('rekam-medis/partograf*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Partograf</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isPpaActive || $isSuratKeteranganActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isPpaActive || $isSuratKeteranganActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Modul PPA</span>
                            <i class="fas {{ $isPpaActive || $isSuratKeteranganActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isPpaActive || $isSuratKeteranganActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/moduldokter/index" class="{{ $isPpaActive ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Menu PPA</a></li>
                            <li class="{{ $isSuratKeteranganActive ? 'open' : '' }}">
                                <a href="#" class="toggle-submenu {{ $isSuratKeteranganActive ? 'active' : '' }}">
                                    <span><i class="fas fa-list menu-icon"></i> Surat Keterangan</span>
                                    <i class="fas {{ $isSuratKeteranganActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                                </a>
                                <ul class="nav-submenu" style="{{ $isSuratKeteranganActive ? 'display: block;' : '' }}">
                                    <li><a href="/skb/index" class="{{ request()->is('skb*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Surat Keterangan Berobat</a></li>
                                    <li><a href="/skd/index" class="{{ request()->is('skd*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Surat Keterangan Dokter</a></li>
                                    <li><a href="/sks/index" class="{{ request()->is('sks*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Surat Keterangan Sehat</a></li>
                                    <li><a href="/skh/index" class="{{ request()->is('skh*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Surat Keterangan Hamil</a></li>
                                    <li><a href="/skm/index" class="{{ request()->is('skm*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Surat Keterangan Kematian</a></li>
                                    <li><a href="/sre/index" class="{{ request()->is('sre*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Surat Rujukan Eksternal</a></li>
                                    <li><a href="/skbn/index" class="{{ request()->is('skbn*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Surat Bebas Narkoba</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $isIncActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isIncActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Intra Natal Care (INC)</span>
                            <i class="fas {{ $isIncActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isIncActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/inc/index" class="{{ $isIncActive ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data INC</a></li>
                        </ul>
                    </li>
                    <li class="{{ $isLaporanExternalActive ? 'open' : '' }}">
                        <a href="#" class="toggle-submenu {{ $isLaporanExternalActive ? 'active' : '' }}" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Laporan External</span>
                            <i class="fas {{ $isLaporanExternalActive ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ $isLaporanExternalActive ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/laporan-eksternal/laporan-diagnosa-ispa" class="{{ request()->is('*ispa*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Diagnosa ISPA</a></li>
                            <li><a href="/laporan-eksternal/laporan-diagnosa-gea" class="{{ request()->is('*gea*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Diagnosa GEA</a></li>
                            <li><a href="/laporan-eksternal/laporan-kunjungan-bayi" class="{{ request()->is('*bayi*') || request()->is('*balita*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Kunjungan Bayi dan Balita</a></li>
                            <li><a href="/laporan-eksternal/laporan-diagnosa-terbanyak" class="{{ request()->is('*diagnosa-terbanyak*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Diagnosa Terbanyak</a></li>
                            <li><a href="/laporan-eksternal/laporan-kelompok-diagnosa" class="{{ request()->is('*kelompok-diagnosa*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Kelompok Diagnosa</a></li>
                            <li><a href="/laporan-eksternal/laporan-jenis-penyakit" class="{{ request()->is('*jenis-penyakit*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Jenis Penyakit</a></li>
                            <li><a href="/laporan-eksternal/laporan-puskesmas" class="{{ request()->is('*laporan-puskesmas*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Laporan Puskesmas</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Post Natal Care (PNC)</span>
                            <i class="fas fa-angle-left float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ request()->is('*pnc*') ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/pnc/index" class="{{ request()->is('*pnc*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data PNC</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Edukasi Terintegrasi</span>
                            <i class="fas fa-angle-left float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ request()->is('*edukasi*') ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/edukasi/index" class="{{ request()->is('*edukasi*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Edukasi</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Imunisasi</span>
                            <i class="fas fa-angle-left float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="{{ request()->is('*imunisasi*') ? 'display: block;' : '' }}; padding-left: 15px;">
                            <li><a href="/imunisasi/index" class="{{ request()->is('*imunisasi*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Imunisasi</a></li>
                        </ul>
                    </li>
                    <li><a href="/dokumen-pasien/index" class="{{ request()->is('*dokumen*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> Dokumen Pelayanan Pasien</a></li>
                </ul>
            </li>
            @endif
            @if(!$u || $u->hasPermission('skrining'))
            <li class="{{ request()->is('*skrining*') ? 'open active' : '' }}">
                <a href="#" class="toggle-submenu">
                    <span><i class="fas fa-diagnoses menu-icon"></i> Skrining Pasien</span>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="nav-submenu" style="{{ request()->is('*skrining*') ? 'display: block;' : '' }}">
                    <li><a href="/skrining/index" class="{{ (request()->is('*skrining*') && !request()->is('*skrining*cek*') && !request()->is('*skrining/status*')) ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Skrining Pasien</a></li>
                    <li><a href="/skrining/index-cek" class="{{ (request()->is('*skrining*cek*') || request()->is('*skrining/status*')) ? 'active' : '' }}"><i class="fas fa-search menu-icon"></i> Cek Status Skrining Pasien</a></li>
                </ul>
            </li>
            <li class="{{ request()->is('*mcu*') ? 'open active' : '' }}">
                <a href="#" class="toggle-submenu">
                    <span><i class="fas fa-stethoscope menu-icon"></i> Medical Checkup</span>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="nav-submenu" style="{{ request()->is('*mcu*') ? 'display: block;' : '' }}">
                    <li><a href="/moduldokter/index-mcu" class="{{ request()->is('*mcu*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> PPA MCU</a></li>
                </ul>
            </li>
            @endif
            @if(!$u || $u->hasPermission(['farmasi_resep', 'master_obat']))
            <li class="{{ request()->is('*farmasi*') || request()->is('*pelanggan*') || request()->is('*produsen*') || request()->is('*vendor*') || request()->is('*gudang*') || request()->is('*barang*') || request()->is('*batch*') || request()->is('*hargaobat*') || request()->is('*stok*') || request()->is('*cancel*') || request()->is('*pembuatanorder*') || request()->is('*order*') ? 'open active' : '' }}">
                <a href="#" class="toggle-submenu">
                    <span><i class="fas fa-thermometer-half menu-icon"></i> Farmasi</span>
                    <i class="fas {{ request()->is('*farmasi*') || request()->is('*pelanggan*') || request()->is('*produsen*') || request()->is('*vendor*') || request()->is('*gudang*') || request()->is('*barang*') || request()->is('*batch*') || request()->is('*hargaobat*') || request()->is('*stok*') || request()->is('*cancel*') || request()->is('*pembuatanorder*') || request()->is('*order*') ? 'fa-angle-down' : 'fa-angle-left' }}"></i>
                </a>
                <ul class="nav-submenu" style="{{ request()->is('*farmasi*') || request()->is('*pelanggan*') || request()->is('*produsen*') || request()->is('*vendor*') || request()->is('*gudang*') || request()->is('*barang*') || request()->is('*batch*') || request()->is('*hargaobat*') || request()->is('*stok*') || request()->is('*cancel*') ? 'display: block;' : '' }}">
                    <li class="{{ request()->is('*pelanggan*') || request()->is('*master-pelanggan*') || request()->is('*produsen*') || request()->is('*vendor*') || request()->is('*gudang*') ? 'open active' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Master Data</span>
                            <i class="fas fa-angle-left float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('*pelanggan*') || request()->is('*master-pelanggan*') || request()->is('*produsen*') || request()->is('*vendor*') || request()->is('*gudang*') ? 'display: block;' : '' }}">
                            <li><a href="/pelanggan/index" class="{{ request()->is('*pelanggan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Pelanggan</a></li>
                            <li><a href="/produsen/index" class="{{ request()->is('*produsen*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Produsen</a></li>
                            <li><a href="/vendor/index" class="{{ request()->is('*vendor*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Vendor</a></li>
                            <li><a href="/gudang/index" class="{{ request()->is('*gudang*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Gudang</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('*barang*') || request()->is('*master-barang*') || request()->is('*daftar-batch*') || request()->is('*batch*') || request()->is('*hargaobat*') || request()->is('*setting-harga*') ? 'open active' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Master Barang/Obat</span>
                            <i class="fas fa-angle-left float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('*barang*') || request()->is('*master-barang*') || request()->is('*daftar-batch*') || request()->is('*batch*') || request()->is('*hargaobat*') || request()->is('*setting-harga*') ? 'display: block;' : '' }}">
                            <li><a href="/barang/index" class="{{ request()->is('*barang*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Master Barang</a></li>
                            <li><a href="/batch/index" class="{{ request()->is('*batch*') || request()->is('*daftar-batch*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Daftar Batch</a></li>
                            <li><a href="/hargaobat/index" class="{{ request()->is('*hargaobat*') || request()->is('*setting-harga*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Setting Harga</a></li>
                        </ul>
                    </li>
                    <li class="{{ (request()->is('*stok*') && !request()->is('*master*')) || request()->is('*history*') || request()->is('*pembatalan*') || request()->is('*cancel*') || request()->is('*adjustment*') ? 'open active' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Stok Barang/Obat</span>
                            <i class="fas {{ (request()->is('*stok*') && !request()->is('*master*')) || request()->is('*history*') || request()->is('*pembatalan*') || request()->is('*cancel*') || request()->is('*adjustment*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ (request()->is('*stok*') && !request()->is('*master*')) || request()->is('*history*') || request()->is('*pembatalan*') || request()->is('*cancel*') || request()->is('*adjustment*') ? 'display: block;' : '' }}">
                            <li><a href="/stok/index" class="{{ request()->is('*stok*') && !request()->is('*pembatalan*') && !request()->is('*cancel*') && !request()->is('*history*') && !request()->is('*adjustment*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Stok Barang</a></li>
                            <li><a href="/stok-cancel/index" class="{{ request()->is('*cancel*') || request()->is('*pembatalan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Pembatalan Stok</a></li>
                            <li><a href="/stok-adjustment/index" class="{{ request()->is('*history*') || request()->is('*adjustment*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> History Penyesuaian</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('*pembuatanorder*') || request()->is('*order-pembelian*') || request()->is('*barangmasuk*') || request()->is('*masuk-barang*') || request()->is('*hutang*') ? 'open active' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Masuk Barang</span>
                            <i class="fas {{ request()->is('*pembuatanorder*') || request()->is('*order-pembelian*') || request()->is('*barangmasuk*') || request()->is('*masuk-barang*') || request()->is('*hutang*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('*pembuatanorder*') || request()->is('*order-pembelian*') || request()->is('*barangmasuk*') || request()->is('*masuk-barang*') || request()->is('*hutang*') ? 'display: block;' : '' }}">
                            <li><a href="/pembuatanorder/index" class="{{ request()->is('*pembuatanorder*') || request()->is('*order-pembelian*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Transaksi Pembuatan Order</a></li>
                            <li><a href="/barangmasuk/index" class="{{ request()->is('*barangmasuk*') || request()->is('*masuk-barang*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Transaksi Masuk Barang</a></li>
                            <li><a href="/pembayaran-hutang/index" class="{{ request()->is('*hutang*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Pembayaran Hutang</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('*mutasibarang*') || request()->is('*mutasi-barang*') || request()->is('*penerimaanmutasibarang*') || request()->is('*penerimaan-mutasi*') ? 'open active' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Mutasi Barang</span>
                            <i class="fas {{ request()->is('*mutasibarang*') || request()->is('*mutasi-barang*') || request()->is('*penerimaanmutasibarang*') || request()->is('*penerimaan-mutasi*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('*mutasibarang*') || request()->is('*mutasi-barang*') || request()->is('*penerimaanmutasibarang*') || request()->is('*penerimaan-mutasi*') ? 'display: block;' : '' }}">
                            <li><a href="/mutasibarang/index" class="{{ (request()->is('*mutasibarang*') || request()->is('*mutasi-barang*')) && !request()->is('*penerimaan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Transaksi Mutasi Barang</a></li>
                            <li><a href="/penerimaanmutasibarang/index" class="{{ request()->is('*penerimaanmutasibarang*') || request()->is('*penerimaan-mutasi*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Transaksi Penerimaan Mutasi</a></li>
                        </ul>
                    </li>
                    <li><a href="/transaksiresep/index" class="{{ request()->is('*transaksiresep*') || request()->is('*resep*') || request()->is('*transaksibhp*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> Resep Dokter</a></li>
                </ul>
            </li>
            @endif
            @if(!$u || $u->hasPermission('penjualan_apotek'))
            <li class="{{ request()->is('*penjualan*') || request()->is('*transaksipenjualan*') ? 'active' : '' }}">
                <a href="/transaksipenjualan/index" class="{{ request()->is('*penjualan*') || request()->is('*transaksipenjualan*') ? 'active' : '' }}">
                    <span><i class="fas fa-plus-square menu-icon"></i> Penjualan Apotek</span>
                </a>
            </li>
            @endif
            @if(!$u || $u->hasPermission('bridging_bpjs'))
            <li class="{{ request()->is('bridging/pcare*') || request()->is('pcare/*') || request()->is('icare/*') || request()->is('pcare-*') ? 'open active' : '' }}">
                <a href="#" class="toggle-submenu">
                    <span><i class="fas fa-rss menu-icon"></i> Bridging PCare & i-Care</span>
                    <i class="fas {{ request()->is('bridging/pcare*') || request()->is('pcare/*') || request()->is('icare/*') || request()->is('pcare-*') ? 'fa-angle-down' : 'fa-angle-left' }}"></i>
                </a>
                <ul class="nav-submenu" style="{{ request()->is('bridging/pcare*') || request()->is('pcare/*') || request()->is('icare/*') || request()->is('pcare-*') ? 'display: block;' : '' }}">
                    {{-- PCare --}}
                    <li class="{{ request()->is('pcare/setting*') || request()->is('pcare-tkp*') ? 'open active' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> PCare</span>
                            <i class="fas {{ request()->is('pcare/setting*') || request()->is('pcare-tkp*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('pcare/setting*') || request()->is('pcare-tkp*') ? 'display: block;' : '' }}">
                            <li class="{{ request()->is('pcare/setting*') || request()->is('pcare-tkp*') ? 'open active' : '' }}">
                                <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                                    <span><i class="fas fa-cog menu-icon"></i> Setting Data</span>
                                    <i class="fas {{ request()->is('pcare/setting*') || request()->is('pcare-tkp*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                                </a>
                                <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('pcare/setting*') || request()->is('pcare-tkp*') ? 'display: block;' : '' }}">
                                    <li><a href="/pcare/setting/akun" class="{{ request()->is('pcare/setting/akun*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Akun Pcare</a></li>
                                    <li><a href="/pcare/setting/poli" class="{{ request()->is('pcare/setting/poli*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Master Poli</a></li>
                                    <li><a href="/pcare/setting/dokter" class="{{ request()->is('pcare/setting/dokter*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Data Dokter</a></li>
                                    <li><a href="/pcare-tkp/index" class="{{ request()->is('pcare/setting/tkp*') || request()->is('pcare-tkp*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting TKP</a></li>
                                    <li><a href="/pcare-kesadaran/index" class="{{ request()->is('pcare/setting/kesadaran*') || request()->is('pcare-kesadaran*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Kesadaran</a></li>
                                    <li><a href="/pcare-tacc/index" class="{{ request()->is('pcare/setting/tacc*') || request()->is('pcare-tacc*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting TACC</a></li>
                                    <li><a href="/pcare-status-pulang/index" class="{{ request()->is('pcare/setting/status-pulang*') || request()->is('pcare-status-pulang*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Status Pulang</a></li>
                                    <li><a href="/pcare-spesialis/index" class="{{ request()->is('pcare/setting/spesialis*') || request()->is('pcare-spesialis*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Spesialis</a></li>
                                    <li><a href="/pcare-sarana/index" class="{{ request()->is('pcare/setting/sarana*') || request()->is('pcare-sarana*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Sarana</a></li>
                                    <li><a href="/pcare/setting/khusus" class="{{ request()->is('pcare/setting/khusus*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Khusus</a></li>
                                    <li><a href="/pcare/setting/tindakan" class="{{ request()->is('pcare/setting/tindakan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Setting Tindakan</a></li>
                                    <li><a href="/pcare/setting/alergi" class="{{ request()->is('pcare/setting/alergi*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Alergi</a></li>
                                    <li><a href="/pcare/setting/prognosa" class="{{ request()->is('pcare/setting/prognosa*') ? 'active' : '' }}"><i class="fas fa-angle-double-right menu-icon"></i> Setting Prognosa</a></li>
                                </ul>
                            </li>
                            <li><a href="/pcare/pencarian-diagnosa" class="{{ request()->is('pcare/pencarian-diagnosa*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> Pencarian Diagnosa</a></li>
                            <li><a href="/pcare/list-pasien-bpjs" class="{{ request()->is('pcare/list-pasien-bpjs*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> List Pasien BPJS</a></li>
                            <li><a href="/pcare/cek-pendaftaran" class="{{ request()->is('pcare/cek-pendaftaran*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> Cek Pendaftaran Pcare</a></li>
                            <li><a href="/pcare/cek-kunjungan" class="{{ request()->is('pcare/cek-kunjungan*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> Cek Kunjungan</a></li>
                            <li><a href="/pcare/histori-pendaftaran" class="{{ request()->is('pcare/histori-pendaftaran*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> Histori Pendaftaran</a></li>
                        </ul>
                    </li>
                    {{-- i-Care --}}
                    <li class="{{ request()->is('icare*') ? 'open active' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-info-circle menu-icon"></i> i-Care</span>
                            <i class="fas {{ request()->is('icare*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('icare*') ? 'display: block;' : '' }}">
                            <li><a href="/icare/akun" class="{{ request()->is('icare/akun*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Akun Bridging i-Care</a></li>
                            <li><a href="/icare/cek-riwayat-kunjungan" class="{{ request()->is('icare/cek-riwayat*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Cek Riwayat Kunjungan</a></li>
                            <li><a href="/icare/update-password" class="{{ request()->is('icare/update-password*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Update Password i-Care</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif
            @if(!$u || $u->hasPermission('bridging_satusehat'))
            <li class="{{ request()->is('*satusehat*') || request()->is('*satu-sehat*') ? 'open active' : '' }}">
                <a href="#" class="toggle-submenu">
                    <span><i class="fas fa-hospital menu-icon"></i> Bridging Satu Sehat</span>
                    <i class="fas {{ request()->is('*satusehat*') || request()->is('*satu-sehat*') ? 'fa-angle-down' : 'fa-angle-left' }}"></i>
                </a>
                <ul class="nav-submenu" style="{{ request()->is('*satusehat*') || request()->is('*satu-sehat*') ? 'display: block;' : '' }}">
                    <li class="{{ request()->is('*satusehat') || request()->is('*satu-sehat*') || request()->is('*satusehat/faskes*') || request()->is('*satusehat*nakes*') || request()->is('*satusehat/unit*') || request()->is('*satusehat/ruangan*') ? 'open active' : '' }}">
                        <a href="#" class="toggle-submenu" style="padding-left: 30px;">
                            <span><i class="fas fa-list menu-icon"></i> Setting Data</span>
                            <i class="fas {{ request()->is('*satusehat') || request()->is('*satu-sehat*') || request()->is('*satusehat/faskes*') || request()->is('*satusehat*nakes*') || request()->is('*satusehat/unit*') || request()->is('*satusehat/ruangan*') ? 'fa-angle-down' : 'fa-angle-left' }} float-end mt-1"></i>
                        </a>
                        <ul class="nav-submenu" style="padding-left: 15px; {{ request()->is('*satusehat') || request()->is('*satu-sehat*') || request()->is('*satusehat/faskes*') || request()->is('*satusehat*nakes*') || request()->is('*satusehat/unit*') || request()->is('*satusehat/ruangan*') ? 'display: block;' : '' }}">
                            <li><a href="/satu-sehat/index" class="{{ request()->is('*satu-sehat*') || request()->is('*satusehat') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Key & URL Access</a></li>
                            <li><a href="/satu-sehat/profil-klinik" class="{{ request()->is('*profil-klinik*') || request()->is('*satusehat/faskes*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Data Faskes</a></li>
                            <li><a href="/satusehat-nakes/index" class="{{ request()->is('*satusehat*nakes*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Setting Data Nakes</a></li>
                            <li><a href="/bridging/satusehat/unit" class="{{ request()->is('*satusehat/unit*') ? 'active' : '' }}"><i class="far fa-circle menu-icon"></i> Setting Unit Layanan</a></li>
                            <li><a href="/bridging/satusehat/ruangan" class="{{ request()->is('*satusehat/ruangan*') ? 'active' : '' }}"><i class="fas fa-list menu-icon"></i> Setting Ruang Rawat</a></li>
                        </ul>
                    </li>
                    <li><a href="/bridging/satusehat/pasien" class="{{ request()->is('*satusehat/pasien*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> Data Pasien Satu Sehat</a></li>
                    <li><a href="/bridging/satusehat/kunjungan" class="{{ request()->is('*satusehat/kunjungan*') ? 'active' : '' }}" style="padding-left: 30px;"><i class="fas fa-list menu-icon"></i> Data Kunjungan</a></li>
                </ul>
            </li>
            @endif
            @if(!$u || $u->hasPermission(['laporan_keuangan', 'profil_faskes', 'manajemen_user']))
            <li>
                <a href="#" class="toggle-submenu">
                    <span><i class="fas fa-chart-line menu-icon"></i> Laporan PMKP</span>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="/laporan-pmkp/survey-kepuasan"><i class="fas fa-list menu-icon"></i> Lap. Survey Kepuasan</a></li>
                    <li><a href="/laporan-pmkp/waktu-tunggu-obat"><i class="fas fa-list menu-icon"></i> Lap. Waktu Tunggu Obat</a></li>
                    <li><a href="/laporan-pmkp/waktu-tunggu-pelayanan"><i class="fas fa-list menu-icon"></i> Lap. Waktu Tunggu Pelayanan</a></li>
                    <li><a href="/laporan-pmkp/waktu-tunggu-racik"><i class="fas fa-list menu-icon"></i> Lap. Waktu Tunggu Obat Racik</a></li>
                </ul>
            </li>
            @endif
            @if(!$u || $u->hasPermission(['kasir', 'laporan_keuangan', 'manajemen_user']))
            <li>
                <a href="#" class="toggle-submenu">
                    <span><i class="fas fa-briefcase menu-icon"></i> Back Office</span>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="/backoffice/jasa-medis"><i class="fas fa-list menu-icon"></i> Lap. Jasa Medis Tindakan</a></li>
                    <li><a href="/backoffice/klaim-rajal"><i class="fas fa-list menu-icon"></i> Klaim Rawat Jalan</a></li>
                    <li><a href="/backoffice/pendapatan"><i class="fas fa-list menu-icon"></i> Pendapatan Pasien</a></li>
                    <li><a href="/backoffice/laporan-kasir"><i class="fas fa-list menu-icon"></i> Lap. Transaksi Kasir</a></li>
                    <li><a href="/backoffice/transaksi-kasir"><i class="fas fa-list menu-icon"></i> Transaksi Kasir</a></li>
                    <li><a href="/farmasi/master-vendor"><i class="fas fa-list menu-icon"></i> Supplier & Distributor (PBF)</a></li>
                    <li><a href="/farmasi/order-pembelian"><i class="fas fa-list menu-icon"></i> Pengadaan & OP Obat</a></li>
                </ul>
            </li>
            @endif
            @if(!$u || $u->isAdministrator())
            <li>
                <a href="/backup-data">
                    <span><i class="fas fa-cog menu-icon"></i> Backup Data</span>
                </a>
            </li>
            @endif
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="content-wrapper" id="contentWrapper">
        <div id="main-content-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="font-size: 13px; border-radius: 4px;">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="font-size: 13px; border-radius: 4px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <!-- Main Footer - KONHAKES 1:1 -->
    <footer class="main-footer" id="mainFooter">
        <div class="pull-left">
            <strong>Copyright &copy; 2026 <a href="https://konhakes.id" target="_blank">Konhakes.id</a></strong>
        </div>
        <div class="pull-right" style="color: #337ab7;">
            <span>Waktu Server: <span id="footerServerTime">{{ date('d m Y H:i:s') }}</span> Zone : Asia/Jakarta <b>Version</b> 1.0</span>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            function syncMenuState() {
                let currentPath = window.location.pathname;

                // Cari menu link yang sedang aktif
                let activeLink = null;
                $('.nav-sidebar a').not('.toggle-submenu').each(function() {
                    let linkHref = $(this).attr('href');
                    if (linkHref && linkHref !== '#' && linkHref !== '/') {
                        if (currentPath === linkHref || (linkHref.length > 1 && currentPath.startsWith(linkHref))) {
                            if (!activeLink || linkHref.length > activeLink.attr('href').length) {
                                activeLink = $(this);
                            }
                        }
                    }
                });

                // Khusus URL alias
                if (!activeLink) {
                    if (currentPath.startsWith('/tambah-pasien') || currentPath.startsWith('/pasien')) {
                        activeLink = $('a[href="/master-pasien"]');
                    } else if (currentPath.startsWith('/pendaftaran')) {
                        activeLink = $('a[href="/pendaftaran-rajal"]');
                    } else if (currentPath.startsWith('/transaksitindakan')) {
                        activeLink = $('a[href="/transaksi-tindakan"]');
                    } else if (currentPath.startsWith('/survei')) {
                        activeLink = $('a[href="/survei"]');
                    } else if (currentPath.startsWith('/modul-kasir')) {
                        activeLink = $('a[href="/transaksi-kasir"]');
                    } else if (currentPath.startsWith('/antrian-jadwal')) {
                        activeLink = $('a[href="/jadwal-antrian"]');
                    } else if (currentPath.startsWith('/antrian')) {
                        activeLink = $('a[href="/list-antrian"]');
                    } else if (currentPath.startsWith('/laboratorium/hasil-lab') || currentPath.startsWith('/entryhasillab')) {
                        activeLink = $('a[href="/entryhasillab/index"]');
                    } else if (currentPath.startsWith('/laboratorium/lap-jasmed-lab') || currentPath.startsWith('/laporan/laporan-jasa-lab')) {
                        activeLink = $('a[href="/laporan/laporan-jasa-lab"]');
                    } else if (currentPath.startsWith('/laboratorium/perujuk-eksternal') || currentPath.startsWith('/perujuk-eksternal')) {
                        activeLink = $('a[href="/perujuk-eksternal/index"]');
                    } else if (currentPath.startsWith('/rad') || currentPath.startsWith('/radiologi/master')) {
                        activeLink = $('a[href="/rad/index"]');
                    } else if (currentPath.startsWith('/tarifrad') || currentPath.startsWith('/radiologi/tarif')) {
                        activeLink = $('a[href="/tarifrad/index"]');
                    } else if (currentPath.startsWith('/transaksirad') || currentPath.startsWith('/radiologi/transaksi')) {
                        activeLink = $('a[href="/transaksirad/index"]');
                    } else if (currentPath.startsWith('/entryhasilrad') || currentPath.startsWith('/radiologi/hasil')) {
                        activeLink = $('a[href="/entryhasilrad/index"]');
                    } else if (currentPath.startsWith('/pelanggan') || currentPath.startsWith('/farmasi/master-pelanggan')) {
                        activeLink = $('a[href="/pelanggan/index"]');
                    } else if (currentPath.startsWith('/produsen') || currentPath.startsWith('/farmasi/master-produsen')) {
                        activeLink = $('a[href="/produsen/index"]');
                    } else if (currentPath.startsWith('/vendor') || currentPath.startsWith('/farmasi/master-vendor')) {
                        activeLink = $('a[href="/vendor/index"]');
                    } else if (currentPath.startsWith('/gudang') || currentPath.startsWith('/farmasi/master-gudang')) {
                        activeLink = $('a[href="/gudang/index"]');
                    } else if (currentPath.startsWith('/barang') || currentPath.startsWith('/farmasi/master-barang')) {
                        activeLink = $('a[href="/barang/index"]');
                    } else if (currentPath.startsWith('/batch') || currentPath.startsWith('/farmasi/daftar-batch')) {
                        activeLink = $('a[href="/batch/index"]');
                    } else if (currentPath.startsWith('/hargaobat') || currentPath.startsWith('/farmasi/setting-harga')) {
                        activeLink = $('a[href="/hargaobat/index"]');
                    } else if (currentPath.startsWith('/stok-cancel') || currentPath.startsWith('/farmasi/pembatalan-stok')) {
                        activeLink = $('a[href="/stok-cancel/index"]');
                    } else if (currentPath.startsWith('/stok-adjustment') || currentPath.startsWith('/farmasi/history-penyesuaian')) {
                        activeLink = $('a[href="/stok-adjustment/index"]');
                    } else if (currentPath.startsWith('/pembuatanorder') || currentPath.startsWith('/farmasi/order-pembelian')) {
                        activeLink = $('a[href="/pembuatanorder/index"]');
                    } else if (currentPath.startsWith('/barangmasuk') || currentPath.startsWith('/farmasi/masuk-barang')) {
                        activeLink = $('a[href="/barangmasuk/index"]');
                    } else if (currentPath.startsWith('/pembayaran-hutang') || currentPath.startsWith('/farmasi/hutang')) {
                        activeLink = $('a[href="/pembayaran-hutang/index"]');
                    } else if (currentPath.startsWith('/penerimaanmutasibarang') || currentPath.startsWith('/farmasi/penerimaan-mutasi')) {
                        activeLink = $('a[href="/penerimaanmutasibarang/index"]');
                    } else if (currentPath.startsWith('/mutasibarang') || currentPath.startsWith('/farmasi/mutasi-barang')) {
                        activeLink = $('a[href="/mutasibarang/index"]');
                    } else if (currentPath.startsWith('/transaksiresep') || currentPath.startsWith('/farmasi/resep') || currentPath.startsWith('/transaksibhp')) {
                        activeLink = $('a[href="/transaksiresep/index"]');
                    } else if (currentPath.startsWith('/stok') && !currentPath.includes('cancel') && !currentPath.includes('pembatalan') && !currentPath.includes('history') && !currentPath.includes('adjustment')) {
                        activeLink = $('a[href="/stok/index"]');
                    } else if (currentPath.startsWith('/satu-sehat/profil-klinik') || currentPath.startsWith('/bridging/satusehat/faskes')) {
                        activeLink = $('a[href="/satu-sehat/profil-klinik"]');
                    } else if (currentPath.startsWith('/satu-sehat') || (currentPath.startsWith('/bridging/satusehat') && !currentPath.includes('faskes') && !currentPath.includes('nakes') && !currentPath.includes('unit') && !currentPath.includes('ruangan') && !currentPath.includes('pasien') && !currentPath.includes('kunjungan'))) {
                        activeLink = $('a[href="/satu-sehat/index"]');
                    } else if (currentPath.startsWith('/satusehat-nakes') || currentPath.startsWith('/bridging/satusehat/nakes')) {
                        activeLink = $('a[href="/satusehat-nakes/index"]');
                    }
                }

                if (activeLink && activeLink.length) {
                    $('.nav-sidebar a').removeClass('active');
                    activeLink.addClass('active');

                    let topParentLi = activeLink.closest('.nav-sidebar > li');
                    
                    // Tutup top-level menu lain yang bukan parent dari link aktif
                    topParentLi.siblings('li.open').each(function() {
                        $(this).removeClass('open');
                        $(this).children('.nav-submenu').hide();
                        $(this).find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-left');
                        $(this).find('li.open').removeClass('open').children('.nav-submenu').hide();
                    });

                    // Pastikan parent li dari link aktif terbuka
                    activeLink.parents('li').each(function() {
                        $(this).addClass('open');
                        $(this).children('.nav-submenu').show();
                        $(this).children('.toggle-submenu').find('> i.fa-angle-left, > span > i.fa-angle-left, > i.float-end.fa-angle-left')
                            .removeClass('fa-angle-left').addClass('fa-angle-down');
                    });
                } else {
                    // Sync initial server-rendered open menus
                    $('.nav-sidebar li.open').each(function() {
                        $(this).children('.nav-submenu').show();
                        $(this).children('.toggle-submenu').find('> i.fa-angle-left, > span > i.fa-angle-left, > i.float-end.fa-angle-left')
                            .removeClass('fa-angle-left').addClass('fa-angle-down');
                    });
                }
            }

            syncMenuState();

            // Toggle Submenu (Accordion Behavior)
            $('.toggle-submenu').click(function(e) {
                e.preventDefault();
                
                let parentLi = $(this).parent();
                let willOpen = !parentLi.hasClass('open');

                if (willOpen) {
                    // Tutup menu siblings yang sedang terbuka pada level yang sama (accordion)
                    let siblings = parentLi.siblings('li.open');
                    siblings.removeClass('open');
                    siblings.children('.nav-submenu').slideUp(150);
                    siblings.find('> a.toggle-submenu > i.fa-angle-down, > a.toggle-submenu i.fa-angle-down, > a.toggle-submenu > span > i.fa-angle-down, > a.toggle-submenu > i.float-end.fa-angle-down')
                        .removeClass('fa-angle-down').addClass('fa-angle-left');
                    // Pastikan submenu di dalam sibling juga tertutup
                    siblings.find('li.open').removeClass('open').children('.nav-submenu').hide();

                    // Buka menu yang diklik
                    parentLi.addClass('open');
                    let icon = $(this).find('> i.fa-angle-left, > i.fa-angle-down, > span > i.fa-angle-left, > span > i.fa-angle-down, > i.float-end');
                    icon.removeClass('fa-angle-left').addClass('fa-angle-down');
                    parentLi.children('.nav-submenu').slideDown(150);
                } else {
                    // Tutup menu yang diklik
                    parentLi.removeClass('open');
                    let icon = $(this).find('> i.fa-angle-left, > i.fa-angle-down, > span > i.fa-angle-left, > span > i.fa-angle-down, > i.float-end');
                    icon.removeClass('fa-angle-down').addClass('fa-angle-left');
                    parentLi.children('.nav-submenu').slideUp(150);
                    parentLi.find('li.open').removeClass('open').children('.nav-submenu').hide();
                }
            });

            // Toggle Sidebar
            $('#toggleSidebar').click(function() {
                if ($('#sidebar').css('left') === '0px') {
                    $('#sidebar').css('left', '-250px');
                    $('#contentWrapper').css('margin-left', '0');
                    $('#mainFooter').css('margin-left', '0');
                } else {
                    $('#sidebar').css('left', '0px');
                    $('#contentWrapper').css('margin-left', '250px');
                    $('#mainFooter').css('margin-left', '250px');
                }
            });

            // Live Server Time Clock
            function updateClock() {
                let now = new Date();
                let d = String(now.getDate()).padStart(2, '0');
                let m = String(now.getMonth() + 1).padStart(2, '0');
                let y = now.getFullYear();
                let hh = String(now.getHours()).padStart(2, '0');
                let mm = String(now.getMinutes()).padStart(2, '0');
                let ss = String(now.getSeconds()).padStart(2, '0');
                let el = document.getElementById('footerServerTime');
                if (el) {
                    el.innerText = `${d} ${m} ${y} ${hh}:${mm}:${ss}`;
                }
            }
            setInterval(updateClock, 1000);

            // AJAX Navigation
            $('.nav-sidebar a').not('.toggle-submenu').click(function(e) {
                let href = $(this).attr('href');
                // Allow normal navigation for root or empty or external/new tab
                if (href && href !== '#' && href !== '/' && !href.startsWith('http') && $(this).attr('target') !== '_blank') {
                    e.preventDefault();
                    
                    $('.nav-sidebar a').removeClass('active');
                    $(this).addClass('active');

                    // Tutup top-level menu lain yang bukan parent dari link ini
                    let topParentLi = $(this).closest('.nav-sidebar > li');
                    topParentLi.siblings('li.open').each(function() {
                        $(this).removeClass('open');
                        $(this).children('.nav-submenu').slideUp(150);
                        $(this).find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-left');
                        $(this).find('li.open').removeClass('open').children('.nav-submenu').hide();
                    });
                    
                    $('#main-content-body').html('<div class="text-center mt-5"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Memuat data...</p></div>');
                    
                    $.ajax({
                        url: href,
                        type: 'GET',
                        success: function(response) {
                            $('#main-content-body').html(response);
                            window.history.pushState({"html":response,"pageTitle":""},"", href);
                            syncMenuState();
                        },
                        error: function(xhr) {
                            $('#main-content-body').html('<div class="alert alert-danger mt-3">Gagal memuat halaman. (' + xhr.status + ')</div>');
                        }
                    });
                }
            });

            // Handle back button for AJAX history
            window.onpopstate = function(e){
                if(e.state){
                    $('#main-content-body').html(e.state.html);
                    syncMenuState();
                } else {
                    window.location.reload();
                }
            };
        });
    </script>
</body>
</html>
