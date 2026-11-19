<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\UnitLayananController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenjaminController;
use App\Http\Controllers\TpaController;
use App\Http\Controllers\TindakanController;
use App\Http\Controllers\TarifTindakanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AkunPembayaranController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\TransaksiTindakanController;
use App\Http\Controllers\GeneralConsentController;
use App\Http\Controllers\SurveiKepuasanController;
use App\Http\Controllers\TransaksiKasirController;
use App\Http\Controllers\MasterAntrianController;
use App\Http\Controllers\JadwalAntrianController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DashboardAntrianController;
use App\Http\Controllers\BridgingConfigController;
use App\Http\Controllers\BridgingMappingController;
use App\Http\Controllers\BridgingCheckController;
use App\Http\Controllers\LogAntrianMjknController;
use App\Http\Controllers\LogPasienBaruMjknController;
use App\Http\Controllers\LogKirimAntrianController;
use App\Http\Controllers\BridgingDashboardController;
use App\Http\Controllers\MasterLabController;
use App\Http\Controllers\LabNilaiNormalController;
use App\Http\Controllers\TarifLabController;
use App\Http\Controllers\TransaksiLabController;
use App\Http\Controllers\HasilLabController;
use App\Http\Controllers\LapJasMedLabController;
use App\Http\Controllers\PerujukEksternalController;
// --- Radiologi ---
use App\Http\Controllers\MasterRadiologiController;
use App\Http\Controllers\TarifRadiologiController;
use App\Http\Controllers\TransaksiRadiologiController;
use App\Http\Controllers\HasilRadiologiController;
// --- Rekam Medis ---
use App\Http\Controllers\MasterDiagnosaController;
use App\Http\Controllers\DiagnosaKeperawatanController;
use App\Http\Controllers\MasterProsedurController;
use App\Http\Controllers\MasterPelangganController;
use App\Http\Controllers\MasterProdusenController;
use App\Http\Controllers\MasterVendorController;
use App\Http\Controllers\MasterGudangController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\AsesmenAwalRajalController;
use App\Http\Controllers\AsesmenUlangController;
use App\Http\Controllers\AsesmenGiziController;
use App\Http\Controllers\AsesmenIgdController;
use App\Http\Controllers\InformedConsentController;
use App\Http\Controllers\OdontogramController;
use App\Http\Controllers\TriaseController;
use App\Http\Controllers\PraBedahController;
use App\Http\Controllers\PraAnestesiController;
use App\Http\Controllers\LaporanOperasiController;
use App\Http\Controllers\MonitoringBedahController;
use App\Http\Controllers\OhisController;
use App\Http\Controllers\SscController;
use App\Http\Controllers\AncController;
use App\Http\Controllers\PpaController;
use App\Http\Controllers\SuratKeteranganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\ProfilFaskesController;
use App\Http\Controllers\RawatInapController;

// === Landing Page (konhakes.id) ===
Route::get('/', function () {
    return view('landing');
});
Route::get('/landing', function () {
    return view('landing');
});

// === Dashboard SIMRS ===
Route::get('/dashboard', [DashboardController::class, 'index']);

// === Grafik & Pencapaian ===
Route::get('/grafik', [GrafikController::class, 'index']);
Route::get('/grafik-pencapaian', [GrafikController::class, 'index']);
Route::get('/site/grafik', [GrafikController::class, 'index']);

// === Profil Faskes ===
Route::get('/profil-faskes', [ProfilFaskesController::class, 'index']);
Route::post('/profil-faskes', [ProfilFaskesController::class, 'update']);
Route::get('/profil-bisnis/index', [ProfilFaskesController::class, 'index']);
Route::post('/profil-bisnis/index', [ProfilFaskesController::class, 'update']);

// === Master Poliklinik ===
Route::get('/master-poliklinik',                  [PoliklinikController::class, 'index'])->name('poliklinik.index');
Route::get('/master-poliklinik/create',           [PoliklinikController::class, 'create'])->name('poliklinik.create');
Route::post('/master-poliklinik',                 [PoliklinikController::class, 'store'])->name('poliklinik.store');
Route::get('/master-poliklinik/{id}',             [PoliklinikController::class, 'show'])->name('poliklinik.show');
Route::get('/master-poliklinik/{id}/edit',        [PoliklinikController::class, 'edit'])->name('poliklinik.edit');
Route::put('/master-poliklinik/{id}',             [PoliklinikController::class, 'update'])->name('poliklinik.update');
Route::delete('/master-poliklinik/{id}',          [PoliklinikController::class, 'destroy'])->name('poliklinik.destroy');

// KONHAKES exact aliases
Route::get('/klinik/index',                       [PoliklinikController::class, 'index']);
Route::get('/klinik',                             [PoliklinikController::class, 'index']);
Route::get('/klinik/create',                      [PoliklinikController::class, 'create']);
Route::post('/klinik/store',                      [PoliklinikController::class, 'store']);
Route::get('/klinik/{id}',                        [PoliklinikController::class, 'show']);
Route::get('/klinik/edit/{id}',                   [PoliklinikController::class, 'edit']);
Route::get('/klinik/{id}/edit',                   [PoliklinikController::class, 'edit']);
Route::post('/klinik/update/{id}',                [PoliklinikController::class, 'update']);
Route::put('/klinik/update/{id}',                 [PoliklinikController::class, 'update']);
Route::delete('/klinik/delete/{id}',              [PoliklinikController::class, 'destroy']);
Route::delete('/klinik/{id}',                     [PoliklinikController::class, 'destroy']);



Route::get('/backup-data', function () {
    return view('backup_data');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout']);

// === LOKASI: Master Unit Layanan ===
Route::get('/master-unit-layanan',           [UnitLayananController::class, 'index']);
Route::get('/unit/index',                    [UnitLayananController::class, 'index']);
Route::get('/master-unit-layanan/create',    [UnitLayananController::class, 'create']);
Route::get('/unit/create',                   [UnitLayananController::class, 'create']);
Route::get('/master-unit-layanan/{id}',      [UnitLayananController::class, 'show']);
Route::post('/master-unit-layanan',          [UnitLayananController::class, 'store']);
Route::get('/master-unit-layanan/{id}/edit', [UnitLayananController::class, 'edit']);
Route::put('/master-unit-layanan/{id}',      [UnitLayananController::class, 'update']);
Route::delete('/master-unit-layanan/{id}',   [UnitLayananController::class, 'destroy']);

// === LOKASI: Master Gedung ===
Route::get('/master-gedung',           [GedungController::class, 'index']);
Route::get('/gedung/index',            [GedungController::class, 'index']);
Route::get('/master-gedung/create',    [GedungController::class, 'create']);
Route::get('/gedung/create',           [GedungController::class, 'create']);
Route::get('/master-gedung/{id}',      [GedungController::class, 'show']);
Route::post('/master-gedung',          [GedungController::class, 'store']);
Route::get('/master-gedung/{id}/edit', [GedungController::class, 'edit']);
Route::put('/master-gedung/{id}',      [GedungController::class, 'update']);
Route::delete('/master-gedung/{id}',   [GedungController::class, 'destroy']);

// === LOKASI: Master Lantai ===
Route::get('/master-lantai',           [LantaiController::class, 'index']);
Route::get('/lantai/index',            [LantaiController::class, 'index']);
Route::get('/master-lantai/create',    [LantaiController::class, 'create']);
Route::get('/lantai/create',           [LantaiController::class, 'create']);
Route::get('/master-lantai/{id}',      [LantaiController::class, 'show']);
Route::post('/master-lantai',          [LantaiController::class, 'store']);
Route::get('/master-lantai/{id}/edit', [LantaiController::class, 'edit']);
Route::put('/master-lantai/{id}',      [LantaiController::class, 'update']);
Route::delete('/master-lantai/{id}',   [LantaiController::class, 'destroy']);

// === LOKASI: Master Ruangan ===
Route::get('/master-ruangan',           [RuanganController::class, 'index']);
Route::get('/ruang/index',              [RuanganController::class, 'index']);
Route::get('/master-ruangan/create',    [RuanganController::class, 'create']);
Route::get('/ruang/create',             [RuanganController::class, 'create']);
Route::get('/master-ruangan/{id}',      [RuanganController::class, 'show']);
Route::post('/master-ruangan',          [RuanganController::class, 'store']);
Route::get('/master-ruangan/{id}/edit', [RuanganController::class, 'edit']);
Route::put('/master-ruangan/{id}',      [RuanganController::class, 'update']);
// === SDM: Master Pegawai ===
Route::get('/master-pegawai',           [PegawaiController::class, 'index']);
Route::get('/pegawai/index',            [PegawaiController::class, 'index']);
Route::get('/master-pegawai/create',    [PegawaiController::class, 'create']);
Route::get('/pegawai/create',           [PegawaiController::class, 'create']);
Route::get('/master-pegawai/{id}',      [PegawaiController::class, 'show']);
Route::post('/master-pegawai',          [PegawaiController::class, 'store']);
Route::get('/master-pegawai/{id}/edit', [PegawaiController::class, 'edit']);
Route::put('/master-pegawai/{id}',      [PegawaiController::class, 'update']);
Route::delete('/master-pegawai/{id}',   [PegawaiController::class, 'destroy']);

// === Master Penjamin ===
Route::get('/master-penjamin',              [PenjaminController::class, 'index']);
Route::get('/penjamin/index',               [PenjaminController::class, 'index']);
Route::get('/master-penjamin/create',       [PenjaminController::class, 'create']);
Route::get('/penjamin/create',              [PenjaminController::class, 'create']);
Route::post('/master-penjamin/set-default', [PenjaminController::class, 'setDefault']);
Route::post('/penjamin/set-default',        [PenjaminController::class, 'setDefault']);
Route::get('/master-penjamin/{id}',         [PenjaminController::class, 'show']);
Route::post('/master-penjamin',             [PenjaminController::class, 'store']);
Route::post('/penjamin',                    [PenjaminController::class, 'store']);
Route::get('/master-penjamin/{id}/edit',    [PenjaminController::class, 'edit']);
Route::get('/penjamin/{id}/edit',           [PenjaminController::class, 'edit']);
Route::put('/master-penjamin/{id}',         [PenjaminController::class, 'update']);
Route::put('/penjamin/{id}',                [PenjaminController::class, 'update']);
Route::delete('/master-penjamin/{id}',      [PenjaminController::class, 'destroy']);
Route::delete('/penjamin/{id}',             [PenjaminController::class, 'destroy']);

// === Master TPA ===
Route::get('/master-tpa',           [TpaController::class, 'index']);
Route::get('/tpa/index',            [TpaController::class, 'index']);
Route::get('/master-tpa/create',    [TpaController::class, 'create']);
Route::get('/tpa/create',           [TpaController::class, 'create']);
Route::get('/master-tpa/{id}',      [TpaController::class, 'show']);
Route::post('/master-tpa',          [TpaController::class, 'store']);
Route::post('/tpa',                 [TpaController::class, 'store']);
Route::get('/master-tpa/{id}/edit', [TpaController::class, 'edit']);
Route::get('/tpa/{id}/edit',        [TpaController::class, 'edit']);
Route::put('/master-tpa/{id}',      [TpaController::class, 'update']);
Route::put('/tpa/{id}',             [TpaController::class, 'update']);
Route::delete('/master-tpa/{id}',   [TpaController::class, 'destroy']);
Route::delete('/tpa/{id}',          [TpaController::class, 'destroy']);

// === Setting Tindakan ===
// 1. Master Tindakan
Route::get('/master-tindakan',           [TindakanController::class, 'index']);
Route::get('/tindakan/index',            [TindakanController::class, 'index']);
Route::get('/master-tindakan/create',    [TindakanController::class, 'create']);
Route::get('/tindakan/create',           [TindakanController::class, 'create']);
Route::post('/master-tindakan',          [TindakanController::class, 'store']);
Route::post('/tindakan',                 [TindakanController::class, 'store']);
Route::get('/master-tindakan/{id}/edit', [TindakanController::class, 'edit']);
Route::get('/tindakan/{id}/edit',        [TindakanController::class, 'edit']);
Route::put('/master-tindakan/{id}',      [TindakanController::class, 'update']);
Route::put('/tindakan/{id}',             [TindakanController::class, 'update']);
Route::delete('/master-tindakan/{id}',   [TindakanController::class, 'destroy']);
Route::delete('/tindakan/{id}',          [TindakanController::class, 'destroy']);

// 2. Master Tarif Tindakan
Route::get('/master-tarif-tindakan',           [TarifTindakanController::class, 'index']);
Route::get('/tariftindakan/index',            [TarifTindakanController::class, 'index']);
Route::get('/master-tarif-tindakan/create',    [TarifTindakanController::class, 'create']);
Route::get('/tariftindakan/create',           [TarifTindakanController::class, 'create']);
Route::post('/master-tarif-tindakan',          [TarifTindakanController::class, 'store']);
Route::post('/tariftindakan',                 [TarifTindakanController::class, 'store']);
Route::get('/master-tarif-tindakan/{id}/edit', [TarifTindakanController::class, 'edit']);
Route::get('/tariftindakan/{id}/edit',        [TarifTindakanController::class, 'edit']);
Route::put('/master-tarif-tindakan/{id}',      [TarifTindakanController::class, 'update']);
Route::post('/master-tarif-tindakan/{id}/update', [TarifTindakanController::class, 'update']);
Route::put('/tariftindakan/{id}',             [TarifTindakanController::class, 'update']);
Route::delete('/master-tarif-tindakan/{id}',   [TarifTindakanController::class, 'destroy']);
Route::delete('/tariftindakan/{id}',          [TarifTindakanController::class, 'destroy']);

// === Setting User ===
// 1. Setting Hak Akses (Role)
Route::get('/setting-role',                  [RoleController::class, 'index'])->name('role.index');
Route::get('/setting-role/create',           [RoleController::class, 'create'])->name('role.create');
Route::post('/setting-role',                 [RoleController::class, 'store'])->name('role.store');
Route::get('/setting-role/{id}',             [RoleController::class, 'show'])->name('role.show');
Route::get('/setting-role/{id}/edit',        [RoleController::class, 'edit'])->name('role.edit');
Route::put('/setting-role/{id}',             [RoleController::class, 'update'])->name('role.update');
Route::delete('/setting-role/{id}',          [RoleController::class, 'destroy'])->name('role.destroy');

// Aliases matching KONHAKES exact role URLs
Route::get('/user/role/index',               [RoleController::class, 'index']);
Route::get('/user/role/create',              [RoleController::class, 'create']);
Route::post('/user/role/store',              [RoleController::class, 'store']);
Route::get('/user/role/{id}',                [RoleController::class, 'show']);
Route::get('/user/role/edit/{id}',           [RoleController::class, 'edit']);
Route::post('/user/role/update/{id}',        [RoleController::class, 'update']);
Route::delete('/user/role/delete/{id}',      [RoleController::class, 'destroy']);

// 2. Manajemen User
Route::get('/setting-user',                  [UserController::class, 'index'])->name('user.index');
Route::get('/setting-user/create',           [UserController::class, 'create'])->name('user.create');
Route::post('/setting-user',                 [UserController::class, 'store'])->name('user.store');
Route::get('/setting-user/{id}',             [UserController::class, 'show'])->name('user.show');
Route::get('/setting-user/{id}/edit',        [UserController::class, 'edit'])->name('user.edit');
Route::put('/setting-user/{id}',             [UserController::class, 'update'])->name('user.update');
Route::delete('/setting-user/{id}',          [UserController::class, 'destroy'])->name('user.destroy');

// User Profile
Route::get('/user/profile',                  [UserController::class, 'profile'])->name('user.profile');
Route::post('/user/profile',                 [UserController::class, 'updateProfile'])->name('user.profile.update');
Route::get('/user/admin',                    [UserController::class, 'profile']);
Route::get('/user/admin/index',              [UserController::class, 'index']);
Route::get('/user/admin/create',             [UserController::class, 'create']);
Route::post('/user/admin/store',             [UserController::class, 'store']);
Route::get('/user/admin/{id}',               [UserController::class, 'show']);
Route::get('/user/admin/edit/{id}',          [UserController::class, 'edit']);
Route::post('/user/admin/update/{id}',       [UserController::class, 'update']);
Route::get('/user/admin/toggle-status/{id}', [UserController::class, 'toggleStatus']);
Route::delete('/user/admin/delete/{id}',     [UserController::class, 'destroy']);

// === Akun Pembayaran Klaim ===
Route::get('/master-akun-pembayaran',                  [AkunPembayaranController::class, 'index'])->name('payment_account.index');
Route::get('/master-akun-pembayaran/create',           [AkunPembayaranController::class, 'create'])->name('payment_account.create');
Route::post('/master-akun-pembayaran',                 [AkunPembayaranController::class, 'store'])->name('payment_account.store');
Route::get('/master-akun-pembayaran/{id}',             [AkunPembayaranController::class, 'show'])->name('payment_account.show');
Route::get('/master-akun-pembayaran/{id}/edit',        [AkunPembayaranController::class, 'edit'])->name('payment_account.edit');
Route::put('/master-akun-pembayaran/{id}',             [AkunPembayaranController::class, 'update'])->name('payment_account.update');
Route::delete('/master-akun-pembayaran/{id}',          [AkunPembayaranController::class, 'destroy'])->name('payment_account.destroy');

// Aliases matching KONHAKES exact URLs
Route::get('/payment-account/index',                   [AkunPembayaranController::class, 'index']);
Route::get('/payment-account',                         [AkunPembayaranController::class, 'index']);
Route::get('/payment-account/create',                  [AkunPembayaranController::class, 'create']);
Route::post('/payment-account/store',                  [AkunPembayaranController::class, 'store']);
Route::get('/payment-account/{id}',                    [AkunPembayaranController::class, 'show']);
Route::get('/payment-account/edit/{id}',               [AkunPembayaranController::class, 'edit']);
Route::post('/payment-account/update/{id}',            [AkunPembayaranController::class, 'update']);
Route::put('/payment-account/update/{id}',             [AkunPembayaranController::class, 'update']);
Route::delete('/payment-account/delete/{id}',          [AkunPembayaranController::class, 'destroy']);

// === Setting Lainnya ===
Route::get('/setting-lainnya',                  [PengaturanController::class, 'index'])->name('setting_lainnya.index');
Route::get('/setting-lainnya/{id}',             [PengaturanController::class, 'show']);
Route::put('/setting-lainnya/{id}',             [PengaturanController::class, 'update']);
Route::post('/setting-lainnya/{id}',            [PengaturanController::class, 'update']);

// Aliases matching KONHAKES exact URLs
Route::get('/general-setting/index',            [PengaturanController::class, 'index']);
Route::get('/general-setting',                  [PengaturanController::class, 'index']);
Route::get('/general-setting/{id}',             [PengaturanController::class, 'show']);
Route::put('/general-setting/{id}',             [PengaturanController::class, 'update']);
Route::post('/general-setting/{id}',            [PengaturanController::class, 'update']);

// ==============================================
// 2. FRONT OFFICE
// ==============================================

// === Master Pasien & Data Pasien (KONHAKES 1:1) ===
Route::get('/master-pasien',                 [PasienController::class, 'index']);
Route::get('/pasien/index',                  [PasienController::class, 'index']);
Route::get('/pasien',                        [PasienController::class, 'index']);

Route::get('/tambah-pasien',                 [PasienController::class, 'create']);
Route::get('/pasien/create',                 [PasienController::class, 'create']);

Route::post('/master-pasien',                [PasienController::class, 'store']);
Route::post('/pasien',                       [PasienController::class, 'store']);

Route::get('/master-pasien/{id}/edit',       [PasienController::class, 'edit']);
Route::get('/pasien/{id}/edit',              [PasienController::class, 'edit']);

Route::put('/master-pasien/{id}',            [PasienController::class, 'update']);
Route::put('/pasien/{id}',                   [PasienController::class, 'update']);

Route::delete('/master-pasien/{id}',         [PasienController::class, 'destroy']);
Route::delete('/pasien/{id}',                [PasienController::class, 'destroy']);

Route::get('/pasien/index-search-ibu',       [PasienController::class, 'searchIbu']);
Route::get('/master-pasien/search-ibu',      [PasienController::class, 'searchIbu']);

Route::post('/pasien/pcare-check',           [PasienController::class, 'checkPcare']);
Route::get('/pasien/export-csv',             [PasienController::class, 'exportCsv']);
Route::post('/pasien/import-csv',            [PasienController::class, 'importCsv']);

// === Pendaftaran Rawat Jalan (KONHAKES 1:1) ===
Route::get('/pendaftaran',                      [PendaftaranController::class, 'index']);
Route::get('/pendaftaran/index',                [PendaftaranController::class, 'index']);
Route::get('/pendaftaran-rajal',                [PendaftaranController::class, 'index']);

Route::get('/pendaftaran/create',               [PendaftaranController::class, 'create']);
Route::get('/pendaftaran-rajal/create',         [PendaftaranController::class, 'create']);

Route::post('/pendaftaran',                     [PendaftaranController::class, 'store']);
Route::post('/pendaftaran-rajal',               [PendaftaranController::class, 'store']);

Route::get('/pendaftaran/{id}/edit',            [PendaftaranController::class, 'edit']);
Route::get('/pendaftaran-rajal/{id}/edit',      [PendaftaranController::class, 'edit']);

Route::put('/pendaftaran/{id}',                 [PendaftaranController::class, 'update']);
Route::put('/pendaftaran-rajal/{id}',           [PendaftaranController::class, 'update']);

Route::delete('/pendaftaran/{id}',              [PendaftaranController::class, 'destroy']);
Route::delete('/pendaftaran-rajal/{id}',        [PendaftaranController::class, 'destroy']);

Route::get('/pendaftaran/antrian',              [PendaftaranController::class, 'antrian']);
Route::post('/pendaftaran/antrian/{id}/panggil', [PendaftaranController::class, 'panggilAntrian']);
Route::get('/pendaftaran/antrian/{id}/hadir',   [PendaftaranController::class, 'hadirAntrian']);

Route::get('/pendaftaran/cetak-pdf',            [PendaftaranController::class, 'cetakPdf']);
Route::get('/pendaftaran/ajax-search-pasien',   [PendaftaranController::class, 'searchPasien']);

// === Transaksi Tindakan ===
Route::get('/transaksi-tindakan',           [TransaksiTindakanController::class, 'index'])->name('transaksi-tindakan.index');
Route::get('/transaksitindakan/index',      [TransaksiTindakanController::class, 'index']);
Route::get('/transaksitindakan',            [TransaksiTindakanController::class, 'index']);
Route::get('/transaksi-tindakan/export',     [TransaksiTindakanController::class, 'export'])->name('transaksi-tindakan.export');
Route::get('/transaksi-tindakan/api/get-tarif', [TransaksiTindakanController::class, 'getTarif']);
Route::get('/transaksi-tindakan/{id}',      [TransaksiTindakanController::class, 'show'])->name('transaksi-tindakan.show');
Route::post('/transaksi-tindakan',          [TransaksiTindakanController::class, 'store']);
Route::delete('/transaksi-tindakan/{id}',   [TransaksiTindakanController::class, 'destroy']);

// === General Consent ===
Route::get('/general-consent',           [GeneralConsentController::class, 'index'])->name('general-consent.index');
Route::get('/general-consent/index',     [GeneralConsentController::class, 'index']);
Route::get('/general-consent/export',    [GeneralConsentController::class, 'export'])->name('general-consent.export');
Route::get('/general-consent/{id}',      [GeneralConsentController::class, 'show'])->name('general-consent.show');
Route::post('/general-consent',          [GeneralConsentController::class, 'store'])->name('general-consent.store');

// === Survei Kepuasan Pasien ===
Route::get('/survei-kepuasan',           [SurveiKepuasanController::class, 'index'])->name('survei-kepuasan.index');
Route::get('/survei',                    [SurveiKepuasanController::class, 'index'])->name('survei.index');
Route::get('/survei/index',              [SurveiKepuasanController::class, 'index']);
Route::get('/survei-kepuasan/index',     [SurveiKepuasanController::class, 'index']);
Route::get('/survei/{id}',               [SurveiKepuasanController::class, 'show'])->name('survei.show');
Route::get('/survei-kepuasan/{id}',      [SurveiKepuasanController::class, 'show'])->name('survei-kepuasan.show');
Route::post('/survei',                   [SurveiKepuasanController::class, 'store'])->name('survei.store');
Route::post('/survei-kepuasan',          [SurveiKepuasanController::class, 'store'])->name('survei-kepuasan.store');

// === Transaksi Kasir ===
Route::get('/transaksi-kasir',           [TransaksiKasirController::class, 'index'])->name('transaksi-kasir.index');
Route::get('/modul-kasir',               [TransaksiKasirController::class, 'index'])->name('modul-kasir.index');
Route::get('/modul-kasir/index',         [TransaksiKasirController::class, 'index']);
Route::get('/transaksi-kasir/index',     [TransaksiKasirController::class, 'index']);
Route::get('/modul-kasir/cetak-laporan', [TransaksiKasirController::class, 'cetakLaporan'])->name('modul-kasir.cetak');
Route::get('/transaksi-kasir/cetak-laporan', [TransaksiKasirController::class, 'cetakLaporan']);
Route::get('/transaksi-kasir/{id}/cetak', [TransaksiKasirController::class, 'cetakKwitansi'])->name('transaksi-kasir.cetak');
Route::get('/modul-kasir/{id}/cetak',     [TransaksiKasirController::class, 'cetakKwitansi'])->name('modul-kasir.cetak');
Route::get('/transaksi-kasir/{id}',      [TransaksiKasirController::class, 'show'])->name('transaksi-kasir.show');
Route::get('/modul-kasir/{id}',          [TransaksiKasirController::class, 'show'])->name('modul-kasir.show');
Route::post('/transaksi-kasir',          [TransaksiKasirController::class, 'store'])->name('transaksi-kasir.store');
Route::post('/modul-kasir',              [TransaksiKasirController::class, 'store'])->name('modul-kasir.store');
Route::delete('/transaksi-kasir/{id}',   [TransaksiKasirController::class, 'destroy'])->name('transaksi-kasir.destroy');
Route::delete('/modul-kasir/{id}',       [TransaksiKasirController::class, 'destroy'])->name('modul-kasir.destroy');

// === Rawat Inap & Bed Management ===
Route::get('/rawat-inap',                  [RawatInapController::class, 'index'])->name('rawat-inap.index');
Route::get('/rawat-inap/bed',              [RawatInapController::class, 'index'])->name('rawat-inap.bed');
Route::get('/rawat-inap/pasien',           [RawatInapController::class, 'daftarPasien'])->name('rawat-inap.pasien');
Route::post('/rawat-inap/admisi',          [RawatInapController::class, 'storeAdmisi'])->name('rawat-inap.admisi');
Route::post('/rawat-inap/transfer',        [RawatInapController::class, 'storeTransfer'])->name('rawat-inap.transfer');
Route::post('/rawat-inap/{id}/discharge',  [RawatInapController::class, 'discharge'])->name('rawat-inap.discharge');
Route::post('/rawat-inap/bed/store',       [RawatInapController::class, 'storeBed'])->name('rawat-inap.bed.store');

// ==============================================
// 3. ANTRIAN
// ==============================================

// === Master Antrian ===
Route::get('/master-antrian',                  [MasterAntrianController::class, 'index'])->name('master-antrian.index');
Route::get('/master-antrian/index',            [MasterAntrianController::class, 'index']);
Route::get('/master-antrian/create',           [MasterAntrianController::class, 'create'])->name('master-antrian.create');
Route::post('/master-antrian',                 [MasterAntrianController::class, 'store'])->name('master-antrian.store');
Route::post('/master-antrian/store',           [MasterAntrianController::class, 'store']);
Route::get('/master-antrian/{id}/edit',        [MasterAntrianController::class, 'edit'])->name('master-antrian.edit');
Route::put('/master-antrian/{id}',             [MasterAntrianController::class, 'update'])->name('master-antrian.update');
Route::post('/master-antrian/{id}/update',     [MasterAntrianController::class, 'update']);
Route::post('/master-antrian/{id}/sound',      [MasterAntrianController::class, 'updateSound'])->name('master-antrian.sound');
Route::delete('/master-antrian/{id}',          [MasterAntrianController::class, 'destroy'])->name('master-antrian.destroy');

// === Setting Jadwal Antrian ===
Route::get('/jadwal-antrian',                 [JadwalAntrianController::class, 'index'])->name('jadwal-antrian.index');
Route::get('/antrian-jadwal',                 [JadwalAntrianController::class, 'index'])->name('antrian-jadwal.index');
Route::post('/jadwal-antrian',                [JadwalAntrianController::class, 'store'])->name('jadwal-antrian.store');
Route::post('/antrian-jadwal',                [JadwalAntrianController::class, 'store']);
Route::get('/jadwal-antrian/{id}/edit',       [JadwalAntrianController::class, 'edit'])->name('jadwal-antrian.edit');
Route::get('/antrian-jadwal/{id}/edit',       [JadwalAntrianController::class, 'edit']);
Route::put('/jadwal-antrian/{id}',            [JadwalAntrianController::class, 'update'])->name('jadwal-antrian.update');
Route::put('/antrian-jadwal/{id}',            [JadwalAntrianController::class, 'update']);
Route::post('/jadwal-antrian/{id}/status',    [JadwalAntrianController::class, 'toggleStatus'])->name('jadwal-antrian.status');
Route::post('/antrian-jadwal/{id}/status',    [JadwalAntrianController::class, 'toggleStatus']);
Route::post('/jadwal-antrian/{id}/kuota',     [JadwalAntrianController::class, 'updateKuota'])->name('jadwal-antrian.kuota');
Route::post('/antrian-jadwal/{id}/kuota',     [JadwalAntrianController::class, 'updateKuota']);
Route::post('/jadwal-antrian/tarik-hfis',     [JadwalAntrianController::class, 'tarikHfis'])->name('jadwal-antrian.tarik-hfis');
Route::post('/antrian-jadwal/tarik-hfis',     [JadwalAntrianController::class, 'tarikHfis']);
Route::get('/antrian-jadwal/download-jadwal-hfis', [JadwalAntrianController::class, 'tarikHfis']);
Route::get('/jadwal-antrian/download-jadwal-hfis', [JadwalAntrianController::class, 'tarikHfis']);
Route::post('/antrian-jadwal/sound',          [JadwalAntrianController::class, 'saveSound'])->name('antrian-jadwal.sound');
Route::post('/jadwal-antrian/sound',          [JadwalAntrianController::class, 'saveSound']);
Route::delete('/jadwal-antrian/{id}',         [JadwalAntrianController::class, 'destroy'])->name('jadwal-antrian.destroy');
Route::delete('/antrian-jadwal/{id}',         [JadwalAntrianController::class, 'destroy']);

// === List Antrian ===
Route::get('/list-antrian',                [AntrianController::class, 'index'])->name('list-antrian.index');
Route::get('/antrian',                     [AntrianController::class, 'index'])->name('antrian.index');
Route::get('/antrian/index',               [AntrianController::class, 'index']);
Route::post('/list-antrian/{id}/status',   [AntrianController::class, 'updateStatus']);
Route::post('/antrian/{id}/status',        [AntrianController::class, 'updateStatus']);
Route::post('/antrian/{id}/update-status', [AntrianController::class, 'updateStatus']);
Route::post('/antrian/booking',            [AntrianController::class, 'bookingAntrian'])->name('antrian.booking');
Route::post('/list-antrian/booking',       [AntrianController::class, 'bookingAntrian']);
Route::post('/antrian/{id}/batal',         [AntrianController::class, 'batal'])->name('antrian.batal');
Route::get('/antrian/{id}/cetak-ulang',    [AntrianController::class, 'cetakUlang'])->name('antrian.cetak-ulang');
Route::get('/api/jadwal-dokter',           [AntrianController::class, 'getJadwalDokter']);

// === Cetak Tiket (Kiosk) ===
Route::get('/cetak-tiket',                     [AntrianController::class, 'kiosk'])->name('cetak-tiket');
Route::get('/antrian/tiket-antrian-v2',        [AntrianController::class, 'kiosk'])->name('antrian.tiket-v2');
Route::get('/antrian/cetak-tiket',             [AntrianController::class, 'kiosk']);
Route::post('/cetak-tiket',                    [AntrianController::class, 'cetakTiket'])->name('cetak-tiket.store');
Route::post('/antrian/tiket-antrian-v2',       [AntrianController::class, 'cetakTiket']);

// === Dashboard Antrian (TV & Slide Antrian - KONHAKES 1:1) ===
Route::get('/setting-slide',               [DashboardAntrianController::class, 'settingSlide'])->name('setting-slide.index');
Route::get('/slide-antrian',               [DashboardAntrianController::class, 'settingSlide'])->name('slide-antrian.index');
Route::get('/slide-antrian/index',         [DashboardAntrianController::class, 'settingSlide']);
Route::get('/setting-slide/create',        [DashboardAntrianController::class, 'create'])->name('slide-antrian.create');
Route::get('/slide-antrian/create',        [DashboardAntrianController::class, 'create']);
Route::post('/setting-slide',              [DashboardAntrianController::class, 'storeSlide'])->name('slide-antrian.store');
Route::post('/slide-antrian',              [DashboardAntrianController::class, 'storeSlide']);
Route::get('/setting-slide/{id}/edit',     [DashboardAntrianController::class, 'edit'])->name('slide-antrian.edit');
Route::get('/slide-antrian/{id}/edit',     [DashboardAntrianController::class, 'edit']);
Route::put('/setting-slide/{id}',          [DashboardAntrianController::class, 'updateSlide'])->name('slide-antrian.update');
Route::put('/slide-antrian/{id}',          [DashboardAntrianController::class, 'updateSlide']);
Route::post('/setting-slide/{id}/update',  [DashboardAntrianController::class, 'updateSlide']);
Route::post('/slide-antrian/{id}/update',  [DashboardAntrianController::class, 'updateSlide']);
Route::delete('/setting-slide/{id}',       [DashboardAntrianController::class, 'destroySlide'])->name('slide-antrian.destroy');
Route::delete('/slide-antrian/{id}',       [DashboardAntrianController::class, 'destroySlide']);
// === Dashboard Antrian (Layar TV & Layanan - KONHAKES 1:1) ===
Route::get('/dashboard-antrian',                 [DashboardAntrianController::class, 'pilihLayanan'])->name('dashboard-antrian.index');
Route::get('/dashboard-antrian/index',           [DashboardAntrianController::class, 'pilihLayanan']);
Route::get('/dashboard-antrian/view-all',         [DashboardAntrianController::class, 'viewAll'])->name('dashboard-antrian.view-all');
Route::get('/dashboard-antrian/view/{id}',       [DashboardAntrianController::class, 'viewLoket'])->name('dashboard-antrian.view-loket');
Route::get('/api/dashboard-antrian/status',       [DashboardAntrianController::class, 'pollStatus']);
Route::get('/tv-antrian',                        [DashboardAntrianController::class, 'viewAll'])->name('tv-antrian');
Route::get('/api/tv-antrian',                    [DashboardAntrianController::class, 'getActiveAntrian']);

// === Dummy API untuk Generate Antrian ===
Route::post('/api/dummy-antrian', function (Illuminate\Http\Request $request) {
    $loket = App\Models\MasterAntrian::findOrFail($request->loket_id);
    
    // Cari nomor terakhir hari ini
    $last = App\Models\Antrian::where('master_antrian_id', $loket->id)
                              ->whereDate('tanggal', $request->tanggal)
                              ->orderBy('nomor_antrian', 'desc')
                              ->first();
                              
    $no = $last ? $last->nomor_antrian + 1 : 1;
    $kode = $loket->kode_antrian . '-' . str_pad($no, 3, '0', STR_PAD_LEFT);
    
    App\Models\Antrian::create([
        'master_antrian_id' => $loket->id,
        'tanggal'           => $request->tanggal,
        'nomor_antrian'     => $no,
        'kode_nomor'        => $kode,
        'status'            => 'Menunggu'
    ]);
    return response()->json(['success' => true]);
});
// ==============================================
// 4. BRIDGING CONFIGURATION
// ==============================================

// === Config BPJS ===
Route::get('/config-bpjs',               [BridgingConfigController::class, 'configBpjs']);
Route::post('/config-bpjs',              [BridgingConfigController::class, 'storeBpjs']);

// === Config SatuSehat ===
Route::get('/config-satusehat',          [BridgingConfigController::class, 'configSatuSehat']);
Route::post('/config-satusehat',         [BridgingConfigController::class, 'storeSatuSehat']);

// API Test Koneksi
Route::post('/api/bridging/test-connection', [BridgingConfigController::class, 'testConnection']);

// === Mapping Master (Poli & Dokter) ===
Route::get('/bridging-mapping-poli',     [BridgingMappingController::class, 'mappingPoli']);
Route::post('/bridging-mapping-poli',    [BridgingMappingController::class, 'storeMappingPoli']);
Route::get('/bridging-mapping-dokter',   [BridgingMappingController::class, 'mappingDokter']);
Route::post('/bridging-mapping-dokter',  [BridgingMappingController::class, 'storeMappingDokter']);

// === Cek Akses Bridging (Setting Bridging Antrol BPJS) ===
Route::get('/bridging-cek-akses',            [BridgingCheckController::class, 'index'])->name('bridging.cek-akses');
Route::get('/antrian-bpjs-setting',          [BridgingCheckController::class, 'index']);
Route::get('/antrian-bpjs-setting/index',    [BridgingCheckController::class, 'index']);
Route::match(['get', 'post'], '/bridging-cek-akses/run',       [BridgingCheckController::class, 'testAntrol']);
Route::match(['get', 'post'], '/bridging-cek-akses/test',      [BridgingCheckController::class, 'testAntrol']);
Route::match(['get', 'post'], '/antrian-bpjs-setting/test',    [BridgingCheckController::class, 'testAntrol']);

// === Log Antrian MJKN (antrian-mjkn-log/index) ===
Route::get('/bridging-log-antrian-mjkn',          [LogAntrianMjknController::class, 'index'])->name('bridging.log-antrian-mjkn');
Route::get('/antrian-mjkn-log',                   [LogAntrianMjknController::class, 'index']);
Route::get('/antrian-mjkn-log/index',             [LogAntrianMjknController::class, 'index']);
Route::get('/bridging-log-antrian-mjkn/{id}/detail', [LogAntrianMjknController::class, 'detail']);
Route::post('/bridging-log-antrian-mjkn/{id}/resync', [LogAntrianMjknController::class, 'resync']);

// === Log Pasien Baru MJKN (pcare-pasien-baru/index) ===
Route::get('/bridging-log-pasien-baru',           [LogPasienBaruMjknController::class, 'index'])->name('bridging.log-pasien-baru');
Route::get('/pcare-pasien-baru',                  [LogPasienBaruMjknController::class, 'index']);
Route::get('/pcare-pasien-baru/index',            [LogPasienBaruMjknController::class, 'index']);
Route::get('/bridging-log-pasien-baru/{id}/detail', [LogPasienBaruMjknController::class, 'detail']);
Route::post('/bridging-log-pasien-baru/{id}/import', [LogPasienBaruMjknController::class, 'import']);

// === Log Kirim Data Antrian (antrian-bpjs-log/index) ===
Route::get('/bridging-log-kirim-antrian',          [LogKirimAntrianController::class, 'index'])->name('bridging.log-kirim-antrian');
Route::get('/antrian-bpjs-log',                   [LogKirimAntrianController::class, 'index']);
Route::get('/antrian-bpjs-log/index',             [LogKirimAntrianController::class, 'index']);
Route::get('/bridging-log-kirim-antrian/{id}',     [LogKirimAntrianController::class, 'detail']);
Route::get('/bridging-log-kirim-antrian/{id}/detail', [LogKirimAntrianController::class, 'detail']);

// === Dashboard Waktu Pelayanan (dashboard-task-fktl - KONHAKES 1:1) ===
Route::get('/dashboard-antrian/dashboard-task-fktl', [BridgingDashboardController::class, 'index'])->name('bridging.dashboard-task-fktl');
Route::get('/bridging-dashboard-waktu',              [BridgingDashboardController::class, 'index'])->name('bridging.dashboard-waktu');

// ==========================================
// MODUL LABORATORIUM (KONHAKES 1:1)
// ==========================================
// Master Lab (Unit Cost Lab)
Route::get('/laboratorium/master-lab',          [MasterLabController::class, 'index'])->name('laboratorium.master-lab');
Route::get('/lab/index',                        [MasterLabController::class, 'index'])->name('lab.index');
Route::post('/laboratorium/master-lab',         [MasterLabController::class, 'store']);
Route::get('/laboratorium/master-lab/{id}/edit',[MasterLabController::class, 'edit']);
Route::put('/laboratorium/master-lab/{id}',     [MasterLabController::class, 'update']);
Route::delete('/laboratorium/master-lab/{id}',  [MasterLabController::class, 'destroy']);

// Setting Nilai Normal Lab
Route::get('/laboratorium/nilai-normal',          [LabNilaiNormalController::class, 'index'])->name('laboratorium.nilai-normal');
Route::get('/settinglabdetail/index',             [LabNilaiNormalController::class, 'index'])->name('settinglabdetail.index');
Route::post('/laboratorium/nilai-normal',         [LabNilaiNormalController::class, 'store']);
Route::post('/settinglabdetail',                  [LabNilaiNormalController::class, 'store']);
Route::get('/laboratorium/nilai-normal/{id}/edit',[LabNilaiNormalController::class, 'edit']);
Route::get('/settinglabdetail/{id}/edit',         [LabNilaiNormalController::class, 'edit']);
Route::put('/laboratorium/nilai-normal/{id}',     [LabNilaiNormalController::class, 'update']);
Route::put('/settinglabdetail/{id}',              [LabNilaiNormalController::class, 'update']);
Route::delete('/laboratorium/nilai-normal/{id}',  [LabNilaiNormalController::class, 'destroy']);
Route::delete('/settinglabdetail/{id}',           [LabNilaiNormalController::class, 'destroy']);

// Setting Tarif Lab Per Penjamin
Route::get('/laboratorium/tarif-lab',                 [TarifLabController::class, 'index'])->name('laboratorium.tarif-lab');
Route::get('/tariflab/index',                         [TarifLabController::class, 'index'])->name('tariflab.index');
Route::get('/laboratorium/tarif-lab/penjamin/{id}',   [TarifLabController::class, 'getTarifByPenjamin']);
Route::get('/tariflab/penjamin/{id}',                 [TarifLabController::class, 'getTarifByPenjamin']);
Route::post('/laboratorium/tarif-lab/save-batch',     [TarifLabController::class, 'saveBatchTarif']);
Route::post('/tariflab/save-batch',                   [TarifLabController::class, 'saveBatchTarif']);
Route::post('/laboratorium/tarif-lab',                [TarifLabController::class, 'store']);
Route::get('/laboratorium/tarif-lab/{id}/edit',       [TarifLabController::class, 'edit']);
Route::put('/laboratorium/tarif-lab/{id}',            [TarifLabController::class, 'update']);
Route::delete('/laboratorium/tarif-lab/{id}',         [TarifLabController::class, 'destroy']);

// Transaksi Laboratorium
Route::get('/laboratorium/transaksi-lab',                        [TransaksiLabController::class, 'index'])->name('laboratorium.transaksi-lab');
Route::get('/transaksilab/index',                                [TransaksiLabController::class, 'index'])->name('transaksilab.index');
Route::get('/laboratorium/transaksi-lab/export-xls',             [TransaksiLabController::class, 'exportXls']);
Route::get('/transaksilab/export-xls',                           [TransaksiLabController::class, 'exportXls']);
Route::get('/laboratorium/transaksi-lab/api/get-tarif',          [TransaksiLabController::class, 'getTarif']);
Route::get('/transaksilab/api/get-tarif',                        [TransaksiLabController::class, 'getTarif']);
Route::get('/laboratorium/transaksi-lab/{id}',                   [TransaksiLabController::class, 'show']);
Route::get('/transaksilab/{id}',                                 [TransaksiLabController::class, 'show']);
Route::post('/laboratorium/transaksi-lab',                       [TransaksiLabController::class, 'store']);
Route::post('/transaksilab',                                     [TransaksiLabController::class, 'store']);
Route::post('/laboratorium/transaksi-lab/{id}/status',           [TransaksiLabController::class, 'updateStatus']);
Route::post('/transaksilab/{id}/status',                         [TransaksiLabController::class, 'updateStatus']);
Route::delete('/laboratorium/transaksi-lab/{id}',                [TransaksiLabController::class, 'destroy']);
Route::delete('/transaksilab/{id}',                             [TransaksiLabController::class, 'destroy']);

// === Hasil Pemeriksaan Lab (Daftar Order Lab - KONHAKES 1:1) ===
Route::get('/laboratorium/hasil-lab',                 [HasilLabController::class, 'index'])->name('laboratorium.hasil-lab');
Route::get('/entryhasillab/index',                    [HasilLabController::class, 'index'])->name('entryhasillab.index');
Route::get('/entryhasillab',                          [HasilLabController::class, 'index']);
Route::get('/entryhasillab/export-pdf',               [HasilLabController::class, 'exportPdf']);
Route::get('/laboratorium/hasil-lab/export-pdf',      [HasilLabController::class, 'exportPdf']);
Route::get('/laboratorium/hasil-lab/{id}',            [HasilLabController::class, 'show']);
Route::get('/entryhasillab/{id}',                     [HasilLabController::class, 'show']);
Route::post('/laboratorium/hasil-lab/simpan',         [HasilLabController::class, 'simpan']);
Route::post('/entryhasillab/simpan',                  [HasilLabController::class, 'simpan']);
Route::post('/laboratorium/hasil-lab/{id}/verifikasi',[HasilLabController::class, 'verifikasi']);
Route::delete('/laboratorium/hasil-lab/{id}/hapus',   [HasilLabController::class, 'hapus']);

// === Laporan Jasa Medis Lab (Laporan Insentif Lab - KONHAKES 1:1) ===
Route::get('/laboratorium/lap-jasmed-lab',     [LapJasMedLabController::class, 'index'])->name('laboratorium.lap-jasmed-lab');
Route::get('/laporan/laporan-jasa-lab',         [LapJasMedLabController::class, 'index'])->name('laporan.laporan-jasa-lab');
Route::get('/laporan/laporan-jasa-lab/pdf',     [LapJasMedLabController::class, 'exportPdf']);
Route::get('/laporan/laporan-jasa-lab/xls',     [LapJasMedLabController::class, 'exportXls']);
Route::get('/laboratorium/lap-jasmed-lab/pdf', [LapJasMedLabController::class, 'exportPdf']);
Route::get('/laboratorium/lap-jasmed-lab/xls', [LapJasMedLabController::class, 'exportXls']);

// === Data Perujuk Eksternal (KONHAKES 1:1) ===
Route::get('/perujuk-eksternal/index',           [PerujukEksternalController::class, 'index'])->name('perujuk-eksternal.index');
Route::get('/perujuk-eksternal',                 [PerujukEksternalController::class, 'index']);
Route::get('/perujuk-eksternal/create',          [PerujukEksternalController::class, 'create'])->name('perujuk-eksternal.create');
Route::post('/perujuk-eksternal',                [PerujukEksternalController::class, 'store'])->name('perujuk-eksternal.store');
Route::get('/perujuk-eksternal/{id}/edit',       [PerujukEksternalController::class, 'edit'])->name('perujuk-eksternal.edit');
Route::put('/perujuk-eksternal/{id}',            [PerujukEksternalController::class, 'update'])->name('perujuk-eksternal.update');
Route::delete('/perujuk-eksternal/{id}',         [PerujukEksternalController::class, 'destroy'])->name('perujuk-eksternal.destroy');

// Aliases
Route::get('/laboratorium/perujuk-eksternal',           [PerujukEksternalController::class, 'index'])->name('laboratorium.perujuk-eksternal');
Route::get('/laboratorium/perujuk-eksternal/create',    [PerujukEksternalController::class, 'create']);
Route::post('/laboratorium/perujuk-eksternal',          [PerujukEksternalController::class, 'store']);
Route::get('/laboratorium/perujuk-eksternal/{id}/edit', [PerujukEksternalController::class, 'edit']);
Route::put('/laboratorium/perujuk-eksternal/{id}',      [PerujukEksternalController::class, 'update']);
Route::delete('/laboratorium/perujuk-eksternal/{id}',   [PerujukEksternalController::class, 'destroy']);

// =========================================================
// === MODUL RADIOLOGI =====================================
// =========================================================

// === Master Radiologi (KONHAKES /rad/index) ===
Route::get('/rad/index',                  [MasterRadiologiController::class, 'index']);
Route::get('/rad',                        [MasterRadiologiController::class, 'index']);
Route::get('/radiologi/master',           [MasterRadiologiController::class, 'index']);
Route::post('/radiologi/master',          [MasterRadiologiController::class, 'store']);
Route::get('/radiologi/master/{id}/edit', [MasterRadiologiController::class, 'edit']);
Route::put('/radiologi/master/{id}',      [MasterRadiologiController::class, 'update']);
Route::delete('/radiologi/master/{id}',   [MasterRadiologiController::class, 'destroy']);
Route::get('/rad/{id}/edit',              [MasterRadiologiController::class, 'edit']);
Route::put('/rad/{id}',                   [MasterRadiologiController::class, 'update']);
Route::delete('/rad/{id}',                [MasterRadiologiController::class, 'destroy']);

// === Setting Tarif Radiologi (KONHAKES /tarifrad/index) ===
Route::get('/tarifrad/index',                        [TarifRadiologiController::class, 'index']);
Route::get('/tarifrad',                              [TarifRadiologiController::class, 'index']);
Route::get('/radiologi/tarif',                       [TarifRadiologiController::class, 'index']);
Route::get('/tarifrad/penjamin/{penjamin_id}',       [TarifRadiologiController::class, 'getTarifByPenjamin']);
Route::post('/tarifrad/save-batch',                  [TarifRadiologiController::class, 'saveBatchTarif']);
Route::post('/tarifrad/import',                      [TarifRadiologiController::class, 'importTarif']);
Route::post('/radiologi/tarif',                      [TarifRadiologiController::class, 'store']);
Route::get('/radiologi/tarif/{id}/edit',             [TarifRadiologiController::class, 'edit']);
Route::put('/radiologi/tarif/{id}',                  [TarifRadiologiController::class, 'update']);
Route::delete('/radiologi/tarif/{id}',               [TarifRadiologiController::class, 'destroy']);

// === Transaksi Radiologi (KONHAKES /transaksirad/index) ===
Route::get('/transaksirad/index',                         [TransaksiRadiologiController::class, 'index']);
Route::get('/transaksirad',                               [TransaksiRadiologiController::class, 'index']);
Route::get('/transaksirad/export-xls',                    [TransaksiRadiologiController::class, 'exportXls']);
Route::get('/transaksirad/{id}',                          [TransaksiRadiologiController::class, 'show']);
Route::get('/radiologi/transaksi',                        [TransaksiRadiologiController::class, 'index']);
Route::get('/radiologi/transaksi/api/get-tarif',          [TransaksiRadiologiController::class, 'getTarif']);
Route::get('/radiologi/transaksi/{id}',                   [TransaksiRadiologiController::class, 'show']);
Route::post('/radiologi/transaksi',                       [TransaksiRadiologiController::class, 'store']);
Route::post('/radiologi/transaksi/{id}/status',           [TransaksiRadiologiController::class, 'updateStatus']);
Route::put('/radiologi/transaksi/{id}',                   [TransaksiRadiologiController::class, 'update']);
Route::delete('/radiologi/transaksi/{id}',                [TransaksiRadiologiController::class, 'destroy']);

// === Hasil Radiologi (KONHAKES /entryhasilrad/index) ===
Route::get('/entryhasilrad/index',                  [HasilRadiologiController::class, 'index']);
Route::get('/entryhasilrad',                        [HasilRadiologiController::class, 'index']);
Route::get('/entryhasilrad/export-xls',             [HasilRadiologiController::class, 'exportXls']);
Route::get('/entryhasilrad/{id}',                   [HasilRadiologiController::class, 'show']);
Route::post('/entryhasilrad/{id}/terima',           [HasilRadiologiController::class, 'terimaOrder']);
Route::get('/radiologi/hasil',                      [HasilRadiologiController::class, 'index']);
Route::get('/radiologi/hasil/{id}',                 [HasilRadiologiController::class, 'show']);
Route::post('/radiologi/hasil/{id}/terima',          [HasilRadiologiController::class, 'terimaOrder']);
Route::post('/radiologi/hasil/simpan',              [HasilRadiologiController::class, 'simpan']);
Route::post('/radiologi/hasil/{id}/verifikasi',     [HasilRadiologiController::class, 'verifikasi']);
Route::post('/radiologi/hasil/{id}/batal',          [HasilRadiologiController::class, 'batalVerifikasi']);
Route::delete('/radiologi/hasil/{id}/hapus',        [HasilRadiologiController::class, 'hapus']);

// =========================================================
// === MODUL REKAM MEDIS ===================================
// =========================================================

// === Master Diagnosa (KONHAKES /diagnosa/index) ===
Route::get('/diagnosa/index',                        [MasterDiagnosaController::class, 'index']);
Route::get('/diagnosa',                              [MasterDiagnosaController::class, 'index']);
Route::get('/diagnosa/{id}/edit',                    [MasterDiagnosaController::class, 'edit']);
Route::get('/diagnosa/{id}',                         [MasterDiagnosaController::class, 'show']);
Route::post('/diagnosa',                             [MasterDiagnosaController::class, 'store']);
Route::put('/diagnosa/{id}',                         [MasterDiagnosaController::class, 'update']);
Route::delete('/diagnosa/{id}',                      [MasterDiagnosaController::class, 'destroy']);
Route::get('/rekam-medis/master-diagnosa',           [MasterDiagnosaController::class, 'index']);
Route::post('/rekam-medis/master-diagnosa',          [MasterDiagnosaController::class, 'store']);
Route::get('/rekam-medis/master-diagnosa/{id}/edit', [MasterDiagnosaController::class, 'edit']);
Route::put('/rekam-medis/master-diagnosa/{id}',      [MasterDiagnosaController::class, 'update']);
Route::delete('/rekam-medis/master-diagnosa/{id}',   [MasterDiagnosaController::class, 'destroy']);

// === Diagnosa Keperawatan (KONHAKES /diagnosa-keperawatan/index) ===
Route::get('/diagnosa-keperawatan/index',                  [DiagnosaKeperawatanController::class, 'index']);
Route::get('/diagnosa-keperawatan',                        [DiagnosaKeperawatanController::class, 'index']);
Route::get('/diagnosa-keperawatan/{id}/edit',              [DiagnosaKeperawatanController::class, 'edit']);
Route::get('/diagnosa-keperawatan/{id}',                   [DiagnosaKeperawatanController::class, 'show']);
Route::post('/diagnosa-keperawatan',                       [DiagnosaKeperawatanController::class, 'store']);
Route::put('/diagnosa-keperawatan/{id}',                   [DiagnosaKeperawatanController::class, 'update']);
Route::delete('/diagnosa-keperawatan/{id}',                [DiagnosaKeperawatanController::class, 'destroy']);
Route::get('/rekam-medis/diagnosa-keperawatan',           [DiagnosaKeperawatanController::class, 'index']);
Route::post('/rekam-medis/diagnosa-keperawatan',          [DiagnosaKeperawatanController::class, 'store']);
Route::get('/rekam-medis/diagnosa-keperawatan/{id}/edit', [DiagnosaKeperawatanController::class, 'edit']);
Route::put('/rekam-medis/diagnosa-keperawatan/{id}',      [DiagnosaKeperawatanController::class, 'update']);
Route::delete('/rekam-medis/diagnosa-keperawatan/{id}',   [DiagnosaKeperawatanController::class, 'destroy']);

// === Master Prosedur (KONHAKES /prosedur/index) ===
Route::get('/prosedur/index',                        [MasterProsedurController::class, 'index']);
Route::get('/prosedur',                              [MasterProsedurController::class, 'index']);
Route::get('/prosedur/{id}/edit',                    [MasterProsedurController::class, 'edit']);
Route::get('/prosedur/{id}',                         [MasterProsedurController::class, 'show']);
Route::post('/prosedur',                             [MasterProsedurController::class, 'store']);
Route::put('/prosedur/{id}',                         [MasterProsedurController::class, 'update']);
Route::delete('/prosedur/{id}',                      [MasterProsedurController::class, 'destroy']);
Route::get('/rekam-medis/master-prosedur',           [MasterProsedurController::class, 'index']);
Route::post('/rekam-medis/master-prosedur',          [MasterProsedurController::class, 'store']);
Route::get('/rekam-medis/master-prosedur/{id}/edit', [MasterProsedurController::class, 'edit']);
Route::put('/rekam-medis/master-prosedur/{id}',      [MasterProsedurController::class, 'update']);
Route::delete('/rekam-medis/master-prosedur/{id}',   [MasterProsedurController::class, 'destroy']);

// === Asesmen Awal Rajal (KONHAKES /asrajal/index) ===
Route::get('/asrajal/index',                            [AsesmenAwalRajalController::class, 'index']);
Route::get('/asrajal',                                  [AsesmenAwalRajalController::class, 'index']);
Route::get('/asrajal/create/{pendaftaran_id}',          [AsesmenAwalRajalController::class, 'create']);
Route::post('/asrajal',                                 [AsesmenAwalRajalController::class, 'store']);
Route::get('/asrajal/{id}/edit',                        [AsesmenAwalRajalController::class, 'edit']);
Route::put('/asrajal/{id}',                             [AsesmenAwalRajalController::class, 'update']);
Route::delete('/asrajal/{id}',                          [AsesmenAwalRajalController::class, 'destroy']);
Route::get('/rekam-medis/asesmen-awal-rajal',           [AsesmenAwalRajalController::class, 'index']);
Route::get('/rekam-medis/asesmen-awal-rajal/create/{pendaftaran_id}', [AsesmenAwalRajalController::class, 'create']);
Route::post('/rekam-medis/asesmen-awal-rajal',          [AsesmenAwalRajalController::class, 'store']);
Route::get('/rekam-medis/asesmen-awal-rajal/{id}/edit', [AsesmenAwalRajalController::class, 'edit']);
Route::put('/rekam-medis/asesmen-awal-rajal/{id}',      [AsesmenAwalRajalController::class, 'update']);
Route::delete('/rekam-medis/asesmen-awal-rajal/{id}',   [AsesmenAwalRajalController::class, 'destroy']);

// === Asesmen Ulang (KONHAKES /asul/index) ===
Route::get('/asul/index',                           [AsesmenUlangController::class, 'index']);
Route::get('/asul',                                 [AsesmenUlangController::class, 'index']);
Route::get('/asul/{pendaftaran_id}',                [AsesmenUlangController::class, 'show']);
Route::post('/asul',                                [AsesmenUlangController::class, 'store']);
Route::get('/asul/edit/{id}',                       [AsesmenUlangController::class, 'edit']);
Route::put('/asul/{id}',                            [AsesmenUlangController::class, 'update']);
Route::delete('/asul/{id}',                         [AsesmenUlangController::class, 'destroy']);
Route::get('/rekam-medis/asesmen-ulang',           [AsesmenUlangController::class, 'index']);
Route::get('/rekam-medis/asesmen-ulang/{pendaftaran_id}', [AsesmenUlangController::class, 'show']);
Route::post('/rekam-medis/asesmen-ulang',          [AsesmenUlangController::class, 'store']);
Route::get('/rekam-medis/asesmen-ulang/edit/{id}', [AsesmenUlangController::class, 'edit']);
Route::put('/rekam-medis/asesmen-ulang/{id}',      [AsesmenUlangController::class, 'update']);
Route::delete('/rekam-medis/asesmen-ulang/{id}',   [AsesmenUlangController::class, 'destroy']);

// === Asesmen Gizi (KONHAKES /asgizi/index) ===
Route::get('/asgizi/index',                           [AsesmenGiziController::class, 'index']);
Route::get('/asgizi',                                 [AsesmenGiziController::class, 'index']);
Route::get('/asgizi/create-asgizi',                   [AsesmenGiziController::class, 'createAsgizi']);
Route::get('/asgizi/create/{pendaftaran_id}',         [AsesmenGiziController::class, 'create']);
Route::post('/asgizi',                                [AsesmenGiziController::class, 'store']);
Route::get('/asgizi/edit/{id}',                       [AsesmenGiziController::class, 'edit']);
Route::put('/asgizi/{id}',                            [AsesmenGiziController::class, 'update']);
Route::delete('/asgizi/{id}',                         [AsesmenGiziController::class, 'destroy']);
Route::get('/rekam-medis/asesmen-gizi',               [AsesmenGiziController::class, 'index']);
Route::get('/rekam-medis/asesmen-gizi/create/{pendaftaran_id}', [AsesmenGiziController::class, 'create']);
Route::post('/rekam-medis/asesmen-gizi',              [AsesmenGiziController::class, 'store']);
Route::get('/rekam-medis/asesmen-gizi/{id}/edit',     [AsesmenGiziController::class, 'edit']);
Route::put('/rekam-medis/asesmen-gizi/{id}',          [AsesmenGiziController::class, 'update']);
Route::delete('/rekam-medis/asesmen-gizi/{id}',       [AsesmenGiziController::class, 'destroy']);

// === Asesmen IGD (KONHAKES /asigd/index) ===
Route::get('/asigd/index',                            [AsesmenIgdController::class, 'index']);
Route::get('/asigd',                                  [AsesmenIgdController::class, 'index']);
Route::get('/asigd/create/{pendaftaran_id}',          [AsesmenIgdController::class, 'create']);
Route::post('/asigd',                                 [AsesmenIgdController::class, 'store']);
Route::get('/asigd/{id}/edit',                        [AsesmenIgdController::class, 'edit']);
Route::put('/asigd/{id}',                             [AsesmenIgdController::class, 'update']);
Route::delete('/asigd/{id}',                          [AsesmenIgdController::class, 'destroy']);
Route::get('/rekam-medis/asesmen-igd',               [AsesmenIgdController::class, 'index']);
Route::get('/rekam-medis/asesmen-igd/create/{pendaftaran_id}', [AsesmenIgdController::class, 'create']);
Route::post('/rekam-medis/asesmen-igd',              [AsesmenIgdController::class, 'store']);
Route::get('/rekam-medis/asesmen-igd/{id}/edit',     [AsesmenIgdController::class, 'edit']);
Route::put('/rekam-medis/asesmen-igd/{id}',          [AsesmenIgdController::class, 'update']);
Route::delete('/rekam-medis/asesmen-igd/{id}',       [AsesmenIgdController::class, 'destroy']);

// === Informed Consent (KONHAKES /consent/index) ===
Route::get('/consent/index',                              [InformedConsentController::class, 'index']);
Route::get('/consent',                                    [InformedConsentController::class, 'index']);
Route::get('/consent/create',                             [InformedConsentController::class, 'create']);
Route::get('/consent/show/{pendaftaran_id}',              [InformedConsentController::class, 'show']);
Route::post('/consent',                                   [InformedConsentController::class, 'store']);
Route::get('/consent/edit/{id}',                          [InformedConsentController::class, 'edit']);
Route::put('/consent/{id}',                               [InformedConsentController::class, 'update']);
Route::delete('/consent/{id}',                            [InformedConsentController::class, 'destroy']);
Route::get('/consent/export-xls',                         [InformedConsentController::class, 'exportXls']);
Route::get('/consent/export-pdf',                         [InformedConsentController::class, 'exportPdf']);
Route::get('/rekam-medis/informed-consent',                [InformedConsentController::class, 'index']);
Route::get('/rekam-medis/informed-consent/show/{pendaftaran_id}', [InformedConsentController::class, 'show']);
Route::post('/rekam-medis/informed-consent',               [InformedConsentController::class, 'store']);
Route::get('/rekam-medis/informed-consent/edit/{id}',      [InformedConsentController::class, 'edit']);
Route::put('/rekam-medis/informed-consent/{id}',           [InformedConsentController::class, 'update']);
Route::delete('/rekam-medis/informed-consent/{id}',        [InformedConsentController::class, 'destroy']);

// === Odontogram (KONHAKES /odon/index) ===
Route::get('/odon/index',                               [OdontogramController::class, 'index']);
Route::get('/odon',                                     [OdontogramController::class, 'index']);
Route::get('/odon/create',                              [OdontogramController::class, 'create']);
Route::get('/odon/show/{pendaftaran_id}',               [OdontogramController::class, 'show']);
Route::post('/odon',                                    [OdontogramController::class, 'store']);
Route::get('/odon/export-xls',                          [OdontogramController::class, 'exportXls']);
Route::get('/odon/export-pdf',                          [OdontogramController::class, 'exportPdf']);
Route::get('/rekam-medis/odontogram',                   [OdontogramController::class, 'index']);
Route::get('/rekam-medis/odontogram/create',            [OdontogramController::class, 'create']);
Route::get('/rekam-medis/odontogram/show/{pendaftaran_id}', [OdontogramController::class, 'show']);
Route::post('/rekam-medis/odontogram',                  [OdontogramController::class, 'store']);

// === Triase (KONHAKES /triase/index) ===
Route::get('/triase/index',                             [TriaseController::class, 'index']);
Route::get('/triase',                                   [TriaseController::class, 'index']);
Route::get('/triase/create',                            [TriaseController::class, 'createTriase']);
Route::get('/triase/create/{pendaftaran_id}',           [TriaseController::class, 'create']);
Route::post('/triase',                                  [TriaseController::class, 'store']);
Route::get('/triase/{id}/edit',                         [TriaseController::class, 'edit']);
Route::put('/triase/{id}',                              [TriaseController::class, 'update']);
Route::delete('/triase/{id}',                           [TriaseController::class, 'destroy']);
Route::get('/triase/export-xls',                        [TriaseController::class, 'exportXls']);
Route::get('/triase/export-pdf',                        [TriaseController::class, 'exportPdf']);
Route::get('/rekam-medis/triase',                       [TriaseController::class, 'index']);
Route::get('/rekam-medis/triase/create/{pendaftaran_id}', [TriaseController::class, 'create']);
Route::post('/rekam-medis/triase',                      [TriaseController::class, 'store']);
Route::get('/rekam-medis/triase/{id}/edit',             [TriaseController::class, 'edit']);
Route::put('/rekam-medis/triase/{id}',                  [TriaseController::class, 'update']);
Route::delete('/rekam-medis/triase/{id}',               [TriaseController::class, 'destroy']);

// === Pengkajian Pra Bedah (KONHAKES /prabedah/index) ===
Route::get('/prabedah/index',                               [PraBedahController::class, 'index']);
Route::get('/prabedah',                                     [PraBedahController::class, 'index']);
Route::get('/prabedah/create',                              [PraBedahController::class, 'createPrabedah']);
Route::get('/prabedah/create/{pendaftaran_id}',             [PraBedahController::class, 'create']);
Route::post('/prabedah',                                    [PraBedahController::class, 'store']);
Route::get('/prabedah/{id}/edit',                           [PraBedahController::class, 'edit']);
Route::put('/prabedah/{id}',                                [PraBedahController::class, 'update']);
Route::delete('/prabedah/{id}',                             [PraBedahController::class, 'destroy']);
Route::get('/prabedah/export-xls',                          [PraBedahController::class, 'exportXls']);
Route::get('/prabedah/export-pdf',                          [PraBedahController::class, 'exportPdf']);
Route::get('/rekam-medis/pra-bedah',                        [PraBedahController::class, 'index']);
Route::get('/rekam-medis/pra-bedah/create/{pendaftaran_id}', [PraBedahController::class, 'create']);
Route::post('/rekam-medis/pra-bedah',                       [PraBedahController::class, 'store']);
Route::get('/rekam-medis/pra-bedah/{id}/edit',              [PraBedahController::class, 'edit']);
Route::put('/rekam-medis/pra-bedah/{id}',                   [PraBedahController::class, 'update']);
Route::delete('/rekam-medis/pra-bedah/{id}',                [PraBedahController::class, 'destroy']);

// === Pengkajian Pra Anestesi (KONHAKES /praanestesi/index) ===
Route::get('/praanestesi/index',                            [PraAnestesiController::class, 'index']);
Route::get('/praanestesi',                                  [PraAnestesiController::class, 'index']);
Route::get('/praanestesi/create',                           [PraAnestesiController::class, 'createPraAnestesi']);
Route::get('/praanestesi/create/{pendaftaran_id}',          [PraAnestesiController::class, 'create']);
Route::post('/praanestesi',                                 [PraAnestesiController::class, 'store']);
Route::get('/praanestesi/{id}/edit',                        [PraAnestesiController::class, 'edit']);
Route::put('/praanestesi/{id}',                             [PraAnestesiController::class, 'update']);
Route::delete('/praanestesi/{id}',                          [PraAnestesiController::class, 'destroy']);
Route::get('/praanestesi/export-xls',                       [PraAnestesiController::class, 'exportXls']);
Route::get('/praanestesi/export-pdf',                       [PraAnestesiController::class, 'exportPdf']);
Route::get('/rekam-medis/pra-anestesi',                     [PraAnestesiController::class, 'index']);
Route::get('/rekam-medis/pra-anestesi/create/{pendaftaran_id}', [PraAnestesiController::class, 'create']);
Route::post('/rekam-medis/pra-anestesi',                    [PraAnestesiController::class, 'store']);
Route::get('/rekam-medis/pra-anestesi/{id}/edit',           [PraAnestesiController::class, 'edit']);
Route::put('/rekam-medis/pra-anestesi/{id}',                [PraAnestesiController::class, 'update']);
Route::delete('/rekam-medis/pra-anestesi/{id}',             [PraAnestesiController::class, 'destroy']);

// === Laporan Tindakan / Operasi (KONHAKES /laptindakan/index) ===
Route::get('/laptindakan/index',                            [LaporanOperasiController::class, 'index']);
Route::get('/laptindakan',                                  [LaporanOperasiController::class, 'index']);
Route::get('/laptindakan/create',                           [LaporanOperasiController::class, 'createLapTindakan']);
Route::get('/laptindakan/create/{pendaftaran_id}',          [LaporanOperasiController::class, 'create']);
Route::post('/laptindakan',                                 [LaporanOperasiController::class, 'store']);
Route::get('/laptindakan/{id}/edit',                        [LaporanOperasiController::class, 'edit']);
Route::put('/laptindakan/{id}',                             [LaporanOperasiController::class, 'update']);
Route::delete('/laptindakan/{id}',                          [LaporanOperasiController::class, 'destroy']);
Route::get('/laptindakan/export-xls',                       [LaporanOperasiController::class, 'exportXls']);
Route::get('/laptindakan/export-pdf',                       [LaporanOperasiController::class, 'exportPdf']);
Route::get('/rekam-medis/laporan-operasi',                  [LaporanOperasiController::class, 'index']);
Route::get('/rekam-medis/laporan-operasi/create/{pendaftaran_id}', [LaporanOperasiController::class, 'create']);
Route::post('/rekam-medis/laporan-operasi',                 [LaporanOperasiController::class, 'store']);
Route::get('/rekam-medis/laporan-operasi/{id}/edit',        [LaporanOperasiController::class, 'edit']);
Route::put('/rekam-medis/laporan-operasi/{id}',             [LaporanOperasiController::class, 'update']);
Route::delete('/rekam-medis/laporan-operasi/{id}',          [LaporanOperasiController::class, 'destroy']);

// === Monitoring Durante Bedah (KONHAKES /monitoring/index) ===
Route::get('/monitoring/index',                            [MonitoringBedahController::class, 'index']);
Route::get('/monitoring',                                  [MonitoringBedahController::class, 'index']);
Route::get('/monitoring/create',                           [MonitoringBedahController::class, 'createMonitoring']);
Route::get('/monitoring/create/{pendaftaran_id}',          [MonitoringBedahController::class, 'create']);
Route::post('/monitoring',                                 [MonitoringBedahController::class, 'store']);
Route::get('/monitoring/{id}/edit',                        [MonitoringBedahController::class, 'edit']);
Route::put('/monitoring/{id}',                             [MonitoringBedahController::class, 'update']);
Route::delete('/monitoring/{id}',                          [MonitoringBedahController::class, 'destroy']);
Route::get('/monitoring/export-xls',                       [MonitoringBedahController::class, 'exportXls']);
Route::get('/monitoring/export-pdf',                       [MonitoringBedahController::class, 'exportPdf']);
Route::get('/rekam-medis/monitoring-bedah',                [MonitoringBedahController::class, 'index']);
Route::get('/rekam-medis/monitoring-bedah/create/{pendaftaran_id}', [MonitoringBedahController::class, 'create']);
Route::post('/rekam-medis/monitoring-bedah',               [MonitoringBedahController::class, 'store']);
Route::get('/rekam-medis/monitoring-bedah/{id}/edit',      [MonitoringBedahController::class, 'edit']);
Route::put('/rekam-medis/monitoring-bedah/{id}',           [MonitoringBedahController::class, 'update']);
Route::delete('/rekam-medis/monitoring-bedah/{id}',        [MonitoringBedahController::class, 'destroy']);

// === OHI-S (KONHAKES /ohis/index) ===
Route::get('/ohis/index',                            [OhisController::class, 'index']);
Route::get('/ohis',                                  [OhisController::class, 'index']);
Route::get('/ohis/create',                           [OhisController::class, 'createOhis']);
Route::get('/ohis/create/{pendaftaran_id}',          [OhisController::class, 'create']);
Route::post('/ohis',                                 [OhisController::class, 'store']);
Route::get('/ohis/{id}/edit',                        [OhisController::class, 'edit']);
Route::put('/ohis/{id}',                             [OhisController::class, 'update']);
Route::delete('/ohis/{id}',                          [OhisController::class, 'destroy']);
Route::get('/rekam-medis/ohis',                      [OhisController::class, 'index']);
Route::get('/rekam-medis/ohis/create/{pendaftaran_id}', [OhisController::class, 'create']);
Route::post('/rekam-medis/ohis',                     [OhisController::class, 'store']);
Route::get('/rekam-medis/ohis/{id}/edit',            [OhisController::class, 'edit']);
Route::put('/rekam-medis/ohis/{id}',                 [OhisController::class, 'update']);
Route::delete('/rekam-medis/ohis/{id}',              [OhisController::class, 'destroy']);

// === Surgical Safety Checklist (KONHAKES /surgical/index) ===
Route::get('/surgical/index',                            [SscController::class, 'index']);
Route::get('/surgical',                                  [SscController::class, 'index']);
Route::get('/surgical/create',                           [SscController::class, 'createSurgical']);
Route::get('/surgical/create/{pendaftaran_id}',          [SscController::class, 'create']);
Route::post('/surgical',                                 [SscController::class, 'store']);
Route::get('/surgical/{id}/edit',                        [SscController::class, 'edit']);
Route::put('/surgical/{id}',                             [SscController::class, 'update']);
Route::delete('/surgical/{id}',                          [SscController::class, 'destroy']);
Route::get('/surgical/export-xls',                       [SscController::class, 'exportXls']);
Route::get('/surgical/export-pdf',                       [SscController::class, 'exportPdf']);
Route::get('/rekam-medis/ssc',                           [SscController::class, 'index']);
Route::get('/rekam-medis/ssc/create/{pendaftaran_id}',    [SscController::class, 'create']);
Route::post('/rekam-medis/ssc',                          [SscController::class, 'store']);
Route::get('/rekam-medis/ssc/{id}/edit',                 [SscController::class, 'edit']);
Route::put('/rekam-medis/ssc/{id}',                      [SscController::class, 'update']);
Route::delete('/rekam-medis/ssc/{id}',                   [SscController::class, 'destroy']);

// === Ante Natal Care (ANC) (KONHAKES /anc/index) ===
Route::get('/anc/index',                            [AncController::class, 'index']);
Route::get('/anc',                                  [AncController::class, 'index']);
Route::get('/anc/create',                           [AncController::class, 'createAnc']);
Route::get('/anc/create/{pendaftaran_id}',          [AncController::class, 'create']);
Route::post('/anc',                                 [AncController::class, 'store']);
Route::get('/anc/{id}/edit',                        [AncController::class, 'edit']);
Route::put('/anc/{id}',                             [AncController::class, 'update']);
Route::delete('/anc/{id}',                          [AncController::class, 'destroy']);
Route::get('/rekam-medis/anc',                      [AncController::class, 'index']);
Route::get('/rekam-medis/anc/create/{pendaftaran_id}', [AncController::class, 'create']);
Route::post('/rekam-medis/anc',                     [AncController::class, 'store']);
Route::get('/rekam-medis/anc/{id}/edit',            [AncController::class, 'edit']);
Route::put('/rekam-medis/anc/{id}',                 [AncController::class, 'update']);
Route::delete('/rekam-medis/anc/{id}',              [AncController::class, 'destroy']);

// === PPA (Modul Dokter / Data Pemeriksaan PPA - KONHAKES /moduldokter/index) ===
Route::get('/moduldokter/index',                     [PpaController::class, 'index']);
Route::get('/moduldokter',                           [PpaController::class, 'index']);
Route::get('/ppa',                                   [PpaController::class, 'index']);
Route::get('/rekam-medis/ppa',                       [PpaController::class, 'index']);
Route::get('/rekam-medis/ppa/{pendaftaran_id}',       [PpaController::class, 'show'])->where('pendaftaran_id', '[0-9]+');
Route::get('/rekam-medis/ppa/create/{pendaftaran_id}', [PpaController::class, 'create']);
Route::post('/rekam-medis/ppa',                      [PpaController::class, 'store']);
Route::get('/rekam-medis/ppa/edit/{id}',             [PpaController::class, 'edit']);
Route::put('/rekam-medis/ppa/{id}',                  [PpaController::class, 'update']);
Route::delete('/rekam-medis/ppa/{id}',               [PpaController::class, 'destroy']);

// === Surat Keterangan (KONHAKES /skb, /skd, /sks, /sre, /skbn) ===
Route::get('/skb/index',                                  [SuratKeteranganController::class, 'skb']);
Route::get('/skb',                                        [SuratKeteranganController::class, 'skb']);
Route::get('/skb/create/{pendaftaran_id}',                [SuratKeteranganController::class, 'createSkb']);

Route::get('/skd/index',                                  [SuratKeteranganController::class, 'skd']);
Route::get('/skd',                                        [SuratKeteranganController::class, 'skd']);
Route::get('/skd/create/{pendaftaran_id}',                [SuratKeteranganController::class, 'createSkd']);

Route::get('/sks/index',                                  [SuratKeteranganController::class, 'sks']);
Route::get('/sks',                                        [SuratKeteranganController::class, 'sks']);
Route::get('/sks/create/{pendaftaran_id}',                [SuratKeteranganController::class, 'createSks']);

Route::get('/sre/index',                                  [SuratKeteranganController::class, 'sre']);
Route::get('/sre',                                        [SuratKeteranganController::class, 'sre']);
Route::get('/sre/create/{pendaftaran_id}',                [SuratKeteranganController::class, 'createSre']);

Route::get('/skbn/index',                                 [SuratKeteranganController::class, 'skbn']);
Route::get('/skbn',                                       [SuratKeteranganController::class, 'skbn']);
Route::get('/skbn/create/{pendaftaran_id}',               [SuratKeteranganController::class, 'createSkbn']);

Route::get('/skm/index',                                  [SuratKeteranganController::class, 'skm']);
Route::get('/skm',                                        [SuratKeteranganController::class, 'skm']);
Route::get('/skm/create/{pendaftaran_id}',                [SuratKeteranganController::class, 'createSkm']);

Route::get('/skh/index',                                  [SuratKeteranganController::class, 'skh']);
Route::get('/skh',                                        [SuratKeteranganController::class, 'skh']);
Route::get('/skh/create/{pendaftaran_id}',                [SuratKeteranganController::class, 'createSkh']);

Route::get('/surat-keterangan/show/{id}',                 [SuratKeteranganController::class, 'show']);
Route::get('/surat-keterangan/export/{type}',             [SuratKeteranganController::class, 'export']);
Route::post('/surat-keterangan',                          [SuratKeteranganController::class, 'store']);
Route::put('/surat-keterangan/{id}',                      [SuratKeteranganController::class, 'update']);
Route::delete('/surat-keterangan/{id}',                   [SuratKeteranganController::class, 'destroy']);

Route::get('/rekam-medis/surat-keterangan',                 [SuratKeteranganController::class, 'index']);
Route::get('/rekam-medis/surat-keterangan/{pendaftaran_id}', [SuratKeteranganController::class, 'show'])->where('pendaftaran_id', '[0-9]+');
Route::get('/rekam-medis/surat-keterangan/create/{pendaftaran_id}', [SuratKeteranganController::class, 'createSkb']);
Route::post('/rekam-medis/surat-keterangan',                [SuratKeteranganController::class, 'store']);
Route::get('/rekam-medis/surat-keterangan/edit/{id}',       [SuratKeteranganController::class, 'edit']);
Route::put('/rekam-medis/surat-keterangan/{id}',            [SuratKeteranganController::class, 'update']);
Route::delete('/rekam-medis/surat-keterangan/{id}',         [SuratKeteranganController::class, 'destroy']);

// === Partograf (KONHAKES /partograf/index) ===
Route::get('/partograf/index',                            [\App\Http\Controllers\PartografController::class, 'index']);
Route::get('/partograf',                                  [\App\Http\Controllers\PartografController::class, 'index']);
Route::get('/partograf/create',                           [\App\Http\Controllers\PartografController::class, 'createPartograf']);
Route::get('/partograf/create/{pendaftaran_id}',          [\App\Http\Controllers\PartografController::class, 'create']);
Route::post('/partograf',                                 [\App\Http\Controllers\PartografController::class, 'store']);
Route::get('/partograf/edit/{id}',                        [\App\Http\Controllers\PartografController::class, 'edit']);
Route::put('/partograf/{id}',                             [\App\Http\Controllers\PartografController::class, 'update']);
Route::delete('/partograf/{id}',                          [\App\Http\Controllers\PartografController::class, 'destroy']);
Route::get('/rekam-medis/partograf',                      [\App\Http\Controllers\PartografController::class, 'index']);
Route::get('/rekam-medis/partograf/{pendaftaran_id}',     [\App\Http\Controllers\PartografController::class, 'show'])->where('pendaftaran_id', '[0-9]+');
Route::get('/rekam-medis/partograf/create/{pendaftaran_id}', [\App\Http\Controllers\PartografController::class, 'create']);
Route::post('/rekam-medis/partograf',                     [\App\Http\Controllers\PartografController::class, 'store']);
Route::get('/rekam-medis/partograf/edit/{id}',            [\App\Http\Controllers\PartografController::class, 'edit']);
Route::put('/rekam-medis/partograf/{id}',                 [\App\Http\Controllers\PartografController::class, 'update']);
Route::delete('/rekam-medis/partograf/{id}',              [\App\Http\Controllers\PartografController::class, 'destroy']);

// === INC (Intra Natal Care - KONHAKES /inc/index) ===
Route::get('/inc/index',                            [App\Http\Controllers\IncController::class, 'index']);
Route::get('/inc',                                  [App\Http\Controllers\IncController::class, 'index']);
Route::get('/inc/create/{pendaftaran_id}',          [App\Http\Controllers\IncController::class, 'create']);
Route::post('/inc',                                 [App\Http\Controllers\IncController::class, 'store']);
Route::get('/inc/edit/{id}',                        [App\Http\Controllers\IncController::class, 'edit']);
Route::put('/inc/{id}',                             [App\Http\Controllers\IncController::class, 'update']);
Route::delete('/inc/{id}',                          [App\Http\Controllers\IncController::class, 'destroy']);
Route::get('/rekam-medis/inc',                      [App\Http\Controllers\IncController::class, 'index']);
Route::get('/rekam-medis/inc/{pendaftaran_id}',      [App\Http\Controllers\IncController::class, 'show'])->where('pendaftaran_id', '[0-9]+');
Route::get('/rekam-medis/inc/create/{pendaftaran_id}', [App\Http\Controllers\IncController::class, 'create']);
Route::post('/rekam-medis/inc',                     [App\Http\Controllers\IncController::class, 'store']);
Route::get('/rekam-medis/inc/edit/{id}',            [App\Http\Controllers\IncController::class, 'edit']);
Route::put('/rekam-medis/inc/{id}',                 [App\Http\Controllers\IncController::class, 'update']);
Route::delete('/rekam-medis/inc/{id}',              [App\Http\Controllers\IncController::class, 'destroy']);

// === Imunisasi ===
Route::get('/rekam-medis/imunisasi',                 [App\Http\Controllers\ImunisasiController::class, 'index']);
Route::get('/rekam-medis/imunisasi/create/{pendaftaran_id}', [App\Http\Controllers\ImunisasiController::class, 'create']);
Route::post('/rekam-medis/imunisasi',                [App\Http\Controllers\ImunisasiController::class, 'store']);
Route::get('/rekam-medis/imunisasi/edit/{id}',       [App\Http\Controllers\ImunisasiController::class, 'edit']);
Route::put('/rekam-medis/imunisasi/{id}',            [App\Http\Controllers\ImunisasiController::class, 'update']);
Route::delete('/rekam-medis/imunisasi/{id}',         [App\Http\Controllers\ImunisasiController::class, 'destroy']);

// === Dokumen Pelayanan ===
Route::get('/rekam-medis/dokumen',                 [App\Http\Controllers\DokumenPelayananController::class, 'index']);
Route::get('/rekam-medis/dokumen/{pendaftaran_id}', [App\Http\Controllers\DokumenPelayananController::class, 'showUploadForm']);
Route::post('/rekam-medis/dokumen',                [App\Http\Controllers\DokumenPelayananController::class, 'store']);
Route::delete('/rekam-medis/dokumen/{id}',         [App\Http\Controllers\DokumenPelayananController::class, 'destroy']);

// === Skrining Pasien (KONHAKES 1:1) ===
Route::get('/skrining/index',                         [App\Http\Controllers\SkriningController::class, 'index'])->name('skrining.index');
Route::get('/skrining',                               [App\Http\Controllers\SkriningController::class, 'index']);
Route::get('/rekam-medis/skrining',                   [App\Http\Controllers\SkriningController::class, 'index']);
Route::get('/skrining/index-register',                [App\Http\Controllers\SkriningController::class, 'indexRegister'])->name('skrining.index-register');
Route::get('/skrining/index-pasien',                  [App\Http\Controllers\SkriningController::class, 'indexPasien'])->name('skrining.index-pasien');
Route::get('/skrining/export-excel',                  [App\Http\Controllers\SkriningController::class, 'exportExcel'])->name('skrining.export-excel');
Route::get('/skrining/create/{pendaftaran_id}',       [App\Http\Controllers\SkriningController::class, 'create'])->name('skrining.create');
Route::get('/rekam-medis/skrining/create/{pendaftaran_id}', [App\Http\Controllers\SkriningController::class, 'create']);
Route::get('/skrining/create-pasien/{pasien_id}',     [App\Http\Controllers\SkriningController::class, 'createPasien'])->name('skrining.create-pasien');
Route::post('/skrining',                              [App\Http\Controllers\SkriningController::class, 'store'])->name('skrining.store');
Route::post('/rekam-medis/skrining',                  [App\Http\Controllers\SkriningController::class, 'store']);
Route::get('/skrining/edit/{id}',                     [App\Http\Controllers\SkriningController::class, 'edit'])->name('skrining.edit');
Route::get('/rekam-medis/skrining/edit/{id}',         [App\Http\Controllers\SkriningController::class, 'edit']);
Route::put('/skrining/{id}',                          [App\Http\Controllers\SkriningController::class, 'update'])->name('skrining.update');
Route::put('/rekam-medis/skrining/{id}',              [App\Http\Controllers\SkriningController::class, 'update']);
Route::delete('/skrining/{id}',                       [App\Http\Controllers\SkriningController::class, 'destroy'])->name('skrining.destroy');
Route::get('/skrining/index-cek',                     [App\Http\Controllers\SkriningController::class, 'indexCek'])->name('skrining.index-cek');
Route::get('/skrining/status',                        [App\Http\Controllers\SkriningController::class, 'indexCek']);
Route::get('/rekam-medis/skrining/status',            [App\Http\Controllers\SkriningController::class, 'indexCek']);

// === PPA MCU (Medical Checkup - KONHAKES 1:1) ===
Route::get('/moduldokter/index-mcu',                 [App\Http\Controllers\McuController::class, 'index'])->name('mcu.index');
Route::get('/mcu/index',                            [App\Http\Controllers\McuController::class, 'index']);
Route::get('/mcu',                                  [App\Http\Controllers\McuController::class, 'index']);
Route::get('/rekam-medis/mcu',                      [App\Http\Controllers\McuController::class, 'index']);
Route::get('/rekam-medis/mcu/create/{pendaftaran_id}', [App\Http\Controllers\McuController::class, 'create'])->name('mcu.create');
Route::get('/moduldokter/mcu/create/{pendaftaran_id}', [App\Http\Controllers\McuController::class, 'create']);
Route::post('/rekam-medis/mcu',                     [App\Http\Controllers\McuController::class, 'store'])->name('mcu.store');
Route::post('/moduldokter/mcu',                     [App\Http\Controllers\McuController::class, 'store']);
Route::get('/rekam-medis/mcu/edit/{id}',            [App\Http\Controllers\McuController::class, 'edit'])->name('mcu.edit');
Route::get('/moduldokter/mcu/edit/{id}',            [App\Http\Controllers\McuController::class, 'edit']);
Route::put('/rekam-medis/mcu/{id}',                 [App\Http\Controllers\McuController::class, 'update'])->name('mcu.update');
Route::put('/moduldokter/mcu/{id}',                 [App\Http\Controllers\McuController::class, 'update']);

// === PNC (Post Natal Care) ===
Route::get('/rekam-medis/pnc',                 [App\Http\Controllers\PncController::class, 'index']);
Route::get('/rekam-medis/pnc/create/{pendaftaran_id}', [App\Http\Controllers\PncController::class, 'create']);
Route::post('/rekam-medis/pnc',                [App\Http\Controllers\PncController::class, 'store']);
Route::get('/rekam-medis/pnc/edit/{id}',       [App\Http\Controllers\PncController::class, 'edit']);
Route::put('/rekam-medis/pnc/{id}',            [App\Http\Controllers\PncController::class, 'update']);
Route::delete('/rekam-medis/pnc/{id}',         [App\Http\Controllers\PncController::class, 'destroy']);

// === Edukasi Terintegrasi ===
Route::get('/rekam-medis/edukasi',                 [App\Http\Controllers\EdukasiController::class, 'index']);
Route::get('/rekam-medis/edukasi/{pendaftaran_id}', [App\Http\Controllers\EdukasiController::class, 'show'])->where('pendaftaran_id', '[0-9]+');
Route::get('/rekam-medis/edukasi/create/{pendaftaran_id}', [App\Http\Controllers\EdukasiController::class, 'create']);
Route::post('/rekam-medis/edukasi',                [App\Http\Controllers\EdukasiController::class, 'store']);
Route::get('/rekam-medis/edukasi/edit/{id}',       [App\Http\Controllers\EdukasiController::class, 'edit']);
Route::put('/rekam-medis/edukasi/{id}',            [App\Http\Controllers\EdukasiController::class, 'update']);
Route::delete('/rekam-medis/edukasi/{id}',         [App\Http\Controllers\EdukasiController::class, 'destroy']);

// === Laporan External ===
Route::get('/laporan-external/ispa',                    [App\Http\Controllers\LaporanExternalController::class, 'ispa']);
Route::get('/laporan-eksternal/laporan-diagnosa-ispa',  [App\Http\Controllers\LaporanExternalController::class, 'ispa']);
Route::get('/laporan-eksternal/ispa',                   [App\Http\Controllers\LaporanExternalController::class, 'ispa']);
Route::get('/laporan-external/ispa/export-excel',       [App\Http\Controllers\LaporanExternalController::class, 'exportIspaExcel']);
Route::get('/laporan-external/ispa/export-pdf',         [App\Http\Controllers\LaporanExternalController::class, 'exportIspaPdf']);
Route::get('/laporan-eksternal/ispa/export-excel',       [App\Http\Controllers\LaporanExternalController::class, 'exportIspaExcel']);
Route::get('/laporan-eksternal/ispa/export-pdf',         [App\Http\Controllers\LaporanExternalController::class, 'exportIspaPdf']);
Route::get('/laporan-external/gea',                     [App\Http\Controllers\LaporanExternalController::class, 'gea']);
Route::get('/laporan-eksternal/laporan-diagnosa-gea',   [App\Http\Controllers\LaporanExternalController::class, 'gea']);
Route::get('/laporan-eksternal/gea',                    [App\Http\Controllers\LaporanExternalController::class, 'gea']);
Route::get('/laporan-external/gea/export-excel',        [App\Http\Controllers\LaporanExternalController::class, 'exportGeaExcel']);
Route::get('/laporan-external/gea/export-pdf',          [App\Http\Controllers\LaporanExternalController::class, 'exportGeaPdf']);
Route::get('/laporan-eksternal/gea/export-excel',       [App\Http\Controllers\LaporanExternalController::class, 'exportGeaExcel']);
Route::get('/laporan-eksternal/gea/export-pdf',         [App\Http\Controllers\LaporanExternalController::class, 'exportGeaPdf']);
Route::get('/laporan-external/bayi-balita',             [App\Http\Controllers\LaporanExternalController::class, 'kunjunganBayiBalita']);
Route::get('/laporan-eksternal/laporan-kunjungan-bayi', [App\Http\Controllers\LaporanExternalController::class, 'kunjunganBayiBalita']);
Route::get('/laporan-eksternal/kunjungan-bayi',         [App\Http\Controllers\LaporanExternalController::class, 'kunjunganBayiBalita']);
Route::get('/laporan-external/kunjungan-bayi/export-excel', [App\Http\Controllers\LaporanExternalController::class, 'exportBayiBalitaExcel']);
Route::get('/laporan-external/kunjungan-bayi/export-pdf',   [App\Http\Controllers\LaporanExternalController::class, 'exportBayiBalitaPdf']);
Route::get('/laporan-eksternal/kunjungan-bayi/export-excel',[App\Http\Controllers\LaporanExternalController::class, 'exportBayiBalitaExcel']);
Route::get('/laporan-eksternal/kunjungan-bayi/export-pdf',  [App\Http\Controllers\LaporanExternalController::class, 'exportBayiBalitaPdf']);
Route::get('/laporan-eksternal/laporan-diagnosa-terbanyak',      [App\Http\Controllers\LaporanExternalController::class, 'diagnosaTerbanyak']);
Route::get('/laporan-eksternal/diagnosa-terbanyak',              [App\Http\Controllers\LaporanExternalController::class, 'diagnosaTerbanyak']);
Route::get('/laporan-external/diagnosa-terbanyak',               [App\Http\Controllers\LaporanExternalController::class, 'diagnosaTerbanyak']);
Route::get('/laporan-eksternal/diagnosa-terbanyak/export-excel', [App\Http\Controllers\LaporanExternalController::class, 'exportDiagnosaTerbanyakExcel']);
Route::get('/laporan-eksternal/diagnosa-terbanyak/export-pdf',   [App\Http\Controllers\LaporanExternalController::class, 'exportDiagnosaTerbanyakPdf']);
Route::get('/laporan-external/diagnosa-terbanyak/export-excel',  [App\Http\Controllers\LaporanExternalController::class, 'exportDiagnosaTerbanyakExcel']);
Route::get('/laporan-external/diagnosa-terbanyak/export-pdf',    [App\Http\Controllers\LaporanExternalController::class, 'exportDiagnosaTerbanyakPdf']);
Route::get('/laporan-eksternal/laporan-kelompok-diagnosa',      [App\Http\Controllers\LaporanExternalController::class, 'kelompokDiagnosa']);
Route::get('/laporan-eksternal/kelompok-diagnosa',              [App\Http\Controllers\LaporanExternalController::class, 'kelompokDiagnosa']);
Route::get('/laporan-external/kelompok-diagnosa',                [App\Http\Controllers\LaporanExternalController::class, 'kelompokDiagnosa']);
Route::get('/laporan-eksternal/kelompok-diagnosa/export-excel', [App\Http\Controllers\LaporanExternalController::class, 'exportKelompokDiagnosaExcel']);
Route::get('/laporan-eksternal/kelompok-diagnosa/export-pdf',   [App\Http\Controllers\LaporanExternalController::class, 'exportKelompokDiagnosaPdf']);
Route::get('/laporan-external/kelompok-diagnosa/export-excel',  [App\Http\Controllers\LaporanExternalController::class, 'exportKelompokDiagnosaExcel']);
Route::get('/laporan-external/kelompok-diagnosa/export-pdf',    [App\Http\Controllers\LaporanExternalController::class, 'exportKelompokDiagnosaPdf']);
Route::get('/laporan-eksternal/laporan-jenis-penyakit',      [App\Http\Controllers\LaporanExternalController::class, 'jenisPenyakit']);
Route::get('/laporan-eksternal/jenis-penyakit',              [App\Http\Controllers\LaporanExternalController::class, 'jenisPenyakit']);
Route::get('/laporan-external/jenis-penyakit',                [App\Http\Controllers\LaporanExternalController::class, 'jenisPenyakit']);
Route::get('/laporan-eksternal/jenis-penyakit/export-excel', [App\Http\Controllers\LaporanExternalController::class, 'exportJenisPenyakitExcel']);
Route::get('/laporan-eksternal/jenis-penyakit/export-pdf',   [App\Http\Controllers\LaporanExternalController::class, 'exportJenisPenyakitPdf']);
Route::get('/laporan-external/jenis-penyakit/export-excel',  [App\Http\Controllers\LaporanExternalController::class, 'exportJenisPenyakitExcel']);
Route::get('/laporan-external/jenis-penyakit/export-pdf',    [App\Http\Controllers\LaporanExternalController::class, 'exportJenisPenyakitPdf']);
Route::get('/laporan-eksternal/laporan-puskesmas',      [App\Http\Controllers\LaporanExternalController::class, 'laporanPuskesmas']);
Route::get('/laporan-eksternal/puskesmas',              [App\Http\Controllers\LaporanExternalController::class, 'laporanPuskesmas']);
Route::get('/laporan-external/laporan-puskesmas',        [App\Http\Controllers\LaporanExternalController::class, 'laporanPuskesmas']);
Route::get('/laporan-external/puskesmas',                [App\Http\Controllers\LaporanExternalController::class, 'laporanPuskesmas']);
Route::get('/laporan-eksternal/laporan-puskesmas/export-excel', [App\Http\Controllers\LaporanExternalController::class, 'exportLaporanPuskesmasExcel']);
Route::get('/laporan-eksternal/laporan-puskesmas/export-pdf',   [App\Http\Controllers\LaporanExternalController::class, 'exportLaporanPuskesmasPdf']);
Route::get('/laporan-external/laporan-puskesmas/export-excel',  [App\Http\Controllers\LaporanExternalController::class, 'exportLaporanPuskesmasExcel']);
Route::get('/laporan-external/laporan-puskesmas/export-pdf',    [App\Http\Controllers\LaporanExternalController::class, 'exportLaporanPuskesmasPdf']);

// === Post Natal Care (PNC) - KONHAKES 1:1 ===
Route::get('/pnc',                          [\App\Http\Controllers\PncController::class, 'index'])->name('pnc.index');
Route::get('/pnc/index',                    [\App\Http\Controllers\PncController::class, 'index']);
Route::get('/rekam-medis/pnc',              [\App\Http\Controllers\PncController::class, 'index']);
Route::get('/pnc/create/{id}',              [\App\Http\Controllers\PncController::class, 'create'])->name('pnc.create');
Route::get('/pnc/form/{id}',                [\App\Http\Controllers\PncController::class, 'create']);
Route::get('/rekam-medis/pnc/create/{id}',  [\App\Http\Controllers\PncController::class, 'create']);
Route::post('/pnc',                         [\App\Http\Controllers\PncController::class, 'store'])->name('pnc.store');
Route::post('/pnc/store',                   [\App\Http\Controllers\PncController::class, 'store']);
Route::post('/rekam-medis/pnc',             [\App\Http\Controllers\PncController::class, 'store']);
Route::get('/pnc/edit/{id}',                [\App\Http\Controllers\PncController::class, 'edit'])->name('pnc.edit');
Route::get('/rekam-medis/pnc/edit/{id}',    [\App\Http\Controllers\PncController::class, 'edit']);
Route::put('/pnc/{id}',                     [\App\Http\Controllers\PncController::class, 'update'])->name('pnc.update');
Route::post('/pnc/{id}/update',             [\App\Http\Controllers\PncController::class, 'update']);
Route::put('/rekam-medis/pnc/{id}',         [\App\Http\Controllers\PncController::class, 'update']);
Route::delete('/pnc/{id}',                  [\App\Http\Controllers\PncController::class, 'destroy'])->name('pnc.destroy');
Route::delete('/rekam-medis/pnc/{id}',      [\App\Http\Controllers\PncController::class, 'destroy']);

// === Edukasi Terintegrasi Rawat Jalan - KONHAKES 1:1 ===
Route::get('/edukasi',                          [\App\Http\Controllers\EdukasiController::class, 'index'])->name('edukasi.index');
Route::get('/edukasi/index',                    [\App\Http\Controllers\EdukasiController::class, 'index']);
Route::get('/rekam-medis/edukasi',              [\App\Http\Controllers\EdukasiController::class, 'index']);
Route::get('/edukasi/create/{id}',              [\App\Http\Controllers\EdukasiController::class, 'create'])->name('edukasi.create');
Route::get('/edukasi/form/{id}',                [\App\Http\Controllers\EdukasiController::class, 'create']);
Route::get('/rekam-medis/edukasi/create/{id}',  [\App\Http\Controllers\EdukasiController::class, 'create']);
Route::post('/edukasi',                         [\App\Http\Controllers\EdukasiController::class, 'store'])->name('edukasi.store');
Route::post('/edukasi/store',                   [\App\Http\Controllers\EdukasiController::class, 'store']);
Route::post('/rekam-medis/edukasi',             [\App\Http\Controllers\EdukasiController::class, 'store']);
Route::get('/edukasi/edit/{id}',                [\App\Http\Controllers\EdukasiController::class, 'edit'])->name('edukasi.edit');
Route::get('/rekam-medis/edukasi/edit/{id}',    [\App\Http\Controllers\EdukasiController::class, 'edit']);
Route::put('/edukasi/{id}',                     [\App\Http\Controllers\EdukasiController::class, 'update'])->name('edukasi.update');
Route::post('/edukasi/{id}/update',             [\App\Http\Controllers\EdukasiController::class, 'update']);
Route::put('/rekam-medis/edukasi/{id}',         [\App\Http\Controllers\EdukasiController::class, 'update']);
Route::delete('/edukasi/{id}',                  [\App\Http\Controllers\EdukasiController::class, 'destroy'])->name('edukasi.destroy');
Route::delete('/rekam-medis/edukasi/{id}',      [\App\Http\Controllers\EdukasiController::class, 'destroy']);

// === Asesmen Imunisasi Rawat Jalan - KONHAKES 1:1 ===
Route::get('/imunisasi',                          [\App\Http\Controllers\ImunisasiController::class, 'index'])->name('imunisasi.index');
Route::get('/imunisasi/index',                    [\App\Http\Controllers\ImunisasiController::class, 'index']);
Route::get('/rekam-medis/imunisasi',              [\App\Http\Controllers\ImunisasiController::class, 'index']);
Route::get('/imunisasi/create/{id}',              [\App\Http\Controllers\ImunisasiController::class, 'create'])->name('imunisasi.create');
Route::get('/imunisasi/form/{id}',                [\App\Http\Controllers\ImunisasiController::class, 'create']);
Route::get('/rekam-medis/imunisasi/create/{id}',  [\App\Http\Controllers\ImunisasiController::class, 'create']);
Route::post('/imunisasi',                         [\App\Http\Controllers\ImunisasiController::class, 'store'])->name('imunisasi.store');
Route::post('/imunisasi/store',                   [\App\Http\Controllers\ImunisasiController::class, 'store']);
Route::post('/rekam-medis/imunisasi',             [\App\Http\Controllers\ImunisasiController::class, 'store']);
Route::get('/imunisasi/edit/{id}',                [\App\Http\Controllers\ImunisasiController::class, 'edit'])->name('imunisasi.edit');
Route::get('/rekam-medis/imunisasi/edit/{id}',    [\App\Http\Controllers\ImunisasiController::class, 'edit']);
Route::put('/imunisasi/{id}',                     [\App\Http\Controllers\ImunisasiController::class, 'update'])->name('imunisasi.update');
Route::post('/imunisasi/{id}/update',             [\App\Http\Controllers\ImunisasiController::class, 'update']);
Route::put('/rekam-medis/imunisasi/{id}',         [\App\Http\Controllers\ImunisasiController::class, 'update']);
Route::delete('/imunisasi/{id}',                  [\App\Http\Controllers\ImunisasiController::class, 'destroy'])->name('imunisasi.destroy');
Route::delete('/rekam-medis/imunisasi/{id}',      [\App\Http\Controllers\ImunisasiController::class, 'destroy']);

// === Data Dokumen Pelayanan Pasien - KONHAKES 1:1 ===
Route::get('/dokumen-pasien',                          [\App\Http\Controllers\DokumenPelayananController::class, 'index'])->name('dokumen-pasien.index');
Route::get('/dokumen-pasien/index',                    [\App\Http\Controllers\DokumenPelayananController::class, 'index']);
Route::get('/rekam-medis/dokumen',                     [\App\Http\Controllers\DokumenPelayananController::class, 'index']);
Route::get('/dokumen-pasien/detail/{id}',              [\App\Http\Controllers\DokumenPelayananController::class, 'showUploadForm'])->name('dokumen-pasien.detail');
Route::get('/dokumen-pasien/{id}',                     [\App\Http\Controllers\DokumenPelayananController::class, 'showUploadForm']);
Route::get('/rekam-medis/dokumen/{id}',                 [\App\Http\Controllers\DokumenPelayananController::class, 'showUploadForm']);
Route::post('/dokumen-pasien/upload',                  [\App\Http\Controllers\DokumenPelayananController::class, 'store'])->name('dokumen-pasien.store');
Route::post('/dokumen-pasien/store',                   [\App\Http\Controllers\DokumenPelayananController::class, 'store']);
Route::post('/rekam-medis/dokumen',                    [\App\Http\Controllers\DokumenPelayananController::class, 'store']);
Route::delete('/dokumen-pasien/{id}',                  [\App\Http\Controllers\DokumenPelayananController::class, 'destroy'])->name('dokumen-pasien.destroy');
Route::delete('/rekam-medis/dokumen/{id}',              [\App\Http\Controllers\DokumenPelayananController::class, 'destroy']);






Route::get('/master-pegawai',           [PegawaiController::class, 'index']);
Route::post('/master-pegawai',          [PegawaiController::class, 'store']);
Route::get('/master-pegawai/{id}/edit', [PegawaiController::class, 'edit']);
Route::put('/master-pegawai/{id}',      [PegawaiController::class, 'update']);
Route::delete('/master-pegawai/{id}',   [PegawaiController::class, 'destroy']);

// =========================================================
// === MODUL FARMASI =======================================
// =========================================================

// === Master Pelanggan (KONHAKES 1:1) ===
Route::get('/pelanggan/index',                    [MasterPelangganController::class, 'index'])->name('pelanggan.index');
Route::get('/pelanggan',                          [MasterPelangganController::class, 'index']);
Route::get('/pelanggan/create',                   [MasterPelangganController::class, 'create'])->name('pelanggan.create');
Route::post('/pelanggan/store',                   [MasterPelangganController::class, 'store'])->name('pelanggan.store');
Route::post('/pelanggan',                         [MasterPelangganController::class, 'store']);
Route::get('/pelanggan/{id}/edit',                [MasterPelangganController::class, 'edit'])->name('pelanggan.edit');
Route::put('/pelanggan/{id}',                     [MasterPelangganController::class, 'update'])->name('pelanggan.update');
Route::delete('/pelanggan/{id}',                  [MasterPelangganController::class, 'destroy'])->name('pelanggan.destroy');

Route::get('/farmasi/master-pelanggan',           [MasterPelangganController::class, 'index']);
Route::get('/farmasi/master-pelanggan/create',    [MasterPelangganController::class, 'create']);
Route::post('/farmasi/master-pelanggan',          [MasterPelangganController::class, 'store']);
Route::get('/farmasi/master-pelanggan/{id}/edit', [MasterPelangganController::class, 'edit']);
Route::put('/farmasi/master-pelanggan/{id}',      [MasterPelangganController::class, 'update']);
Route::delete('/farmasi/master-pelanggan/{id}',   [MasterPelangganController::class, 'destroy']);

// === Master Produsen (KONHAKES 1:1) ===
Route::get('/produsen/index',                    [MasterProdusenController::class, 'index'])->name('produsen.index');
Route::get('/produsen',                          [MasterProdusenController::class, 'index']);
Route::get('/produsen/create',                   [MasterProdusenController::class, 'create'])->name('produsen.create');
Route::post('/produsen/store',                   [MasterProdusenController::class, 'store'])->name('produsen.store');
Route::post('/produsen',                         [MasterProdusenController::class, 'store']);
Route::get('/produsen/{id}',                     [MasterProdusenController::class, 'show'])->name('produsen.show');
Route::get('/produsen/{id}/edit',                [MasterProdusenController::class, 'edit'])->name('produsen.edit');
Route::put('/produsen/{id}',                     [MasterProdusenController::class, 'update'])->name('produsen.update');
Route::delete('/produsen/{id}',                  [MasterProdusenController::class, 'destroy'])->name('produsen.destroy');

Route::get('/farmasi/master-produsen',           [MasterProdusenController::class, 'index']);
Route::get('/farmasi/master-produsen/create',    [MasterProdusenController::class, 'create']);
Route::post('/farmasi/master-produsen',          [MasterProdusenController::class, 'store']);
Route::get('/farmasi/master-produsen/{id}/edit', [MasterProdusenController::class, 'edit']);
Route::put('/farmasi/master-produsen/{id}',      [MasterProdusenController::class, 'update']);
Route::delete('/farmasi/master-produsen/{id}',   [MasterProdusenController::class, 'destroy']);

// === Master Vendor (KONHAKES 1:1) ===
Route::get('/vendor/index',                    [MasterVendorController::class, 'index'])->name('vendor.index');
Route::get('/vendor',                          [MasterVendorController::class, 'index']);
Route::get('/vendor/create',                   [MasterVendorController::class, 'create'])->name('vendor.create');
Route::post('/vendor/store',                   [MasterVendorController::class, 'store'])->name('vendor.store');
Route::post('/vendor',                         [MasterVendorController::class, 'store']);
Route::get('/vendor/{id}',                     [MasterVendorController::class, 'show'])->name('vendor.show');
Route::get('/vendor/{id}/edit',                [MasterVendorController::class, 'edit'])->name('vendor.edit');
Route::put('/vendor/{id}',                     [MasterVendorController::class, 'update'])->name('vendor.update');
Route::delete('/vendor/{id}',                  [MasterVendorController::class, 'destroy'])->name('vendor.destroy');

Route::get('/farmasi/master-vendor',           [MasterVendorController::class, 'index']);
Route::get('/farmasi/master-vendor/create',    [MasterVendorController::class, 'create']);
Route::post('/farmasi/master-vendor',          [MasterVendorController::class, 'store']);
Route::get('/farmasi/master-vendor/{id}',      [MasterVendorController::class, 'show']);
Route::get('/farmasi/master-vendor/{id}/edit', [MasterVendorController::class, 'edit']);
Route::put('/farmasi/master-vendor/{id}',      [MasterVendorController::class, 'update']);
Route::delete('/farmasi/master-vendor/{id}',   [MasterVendorController::class, 'destroy']);

// === Master Gudang (KONHAKES 1:1) ===
Route::get('/gudang/index',                    [MasterGudangController::class, 'index'])->name('gudang.index');
Route::get('/gudang',                          [MasterGudangController::class, 'index']);
Route::get('/gudang/create',                   [MasterGudangController::class, 'create'])->name('gudang.create');
Route::post('/gudang/store',                   [MasterGudangController::class, 'store'])->name('gudang.store');
Route::post('/gudang',                         [MasterGudangController::class, 'store']);
Route::get('/gudang/{id}',                     [MasterGudangController::class, 'show'])->name('gudang.show');
Route::get('/gudang/{id}/edit',                [MasterGudangController::class, 'edit'])->name('gudang.edit');
Route::put('/gudang/{id}',                     [MasterGudangController::class, 'update'])->name('gudang.update');
Route::delete('/gudang/{id}',                  [MasterGudangController::class, 'destroy'])->name('gudang.destroy');

Route::get('/farmasi/master-gudang',           [MasterGudangController::class, 'index']);
Route::get('/farmasi/master-gudang/create',    [MasterGudangController::class, 'create']);
Route::post('/farmasi/master-gudang',          [MasterGudangController::class, 'store']);
Route::get('/farmasi/master-gudang/{id}',      [MasterGudangController::class, 'show']);
Route::get('/farmasi/master-gudang/{id}/edit', [MasterGudangController::class, 'edit']);
Route::put('/farmasi/master-gudang/{id}',      [MasterGudangController::class, 'update']);
Route::delete('/farmasi/master-gudang/{id}',   [MasterGudangController::class, 'destroy']);

// === Master Barang (KONHAKES 1:1) ===
Route::get('/barang/index',                    [MasterBarangController::class, 'index'])->name('barang.index');
Route::get('/barang',                          [MasterBarangController::class, 'index']);
Route::get('/barang/create',                   [MasterBarangController::class, 'create'])->name('barang.create');
Route::post('/barang/store',                   [MasterBarangController::class, 'store'])->name('barang.store');
Route::post('/barang',                         [MasterBarangController::class, 'store']);
Route::get('/barang/download',                 [MasterBarangController::class, 'download'])->name('barang.download');
Route::post('/barang/{id}/min-stok',           [MasterBarangController::class, 'updateMinStok'])->name('barang.min_stok');
Route::get('/barang/{id}',                     [MasterBarangController::class, 'show'])->name('barang.show');
Route::get('/barang/{id}/edit',                [MasterBarangController::class, 'edit'])->name('barang.edit');
Route::put('/barang/{id}',                     [MasterBarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{id}',                  [MasterBarangController::class, 'destroy'])->name('barang.destroy');

Route::get('/farmasi/master-barang',           [MasterBarangController::class, 'index']);
Route::get('/farmasi/master-barang/create',    [MasterBarangController::class, 'create']);
Route::post('/farmasi/master-barang',          [MasterBarangController::class, 'store']);
Route::get('/farmasi/master-barang/{id}',      [MasterBarangController::class, 'show']);
Route::get('/farmasi/master-barang/{id}/edit', [MasterBarangController::class, 'edit']);
Route::put('/farmasi/master-barang/{id}',      [MasterBarangController::class, 'update']);
Route::delete('/farmasi/master-barang/{id}',   [MasterBarangController::class, 'destroy']);

// === Daftar Batch (KONHAKES 1:1) ===
Route::get('/batch/index',                    [\App\Http\Controllers\BatchBarangController::class, 'index'])->name('batch.index');
Route::get('/batch',                          [\App\Http\Controllers\BatchBarangController::class, 'index']);
Route::get('/batch/create',                   [\App\Http\Controllers\BatchBarangController::class, 'create'])->name('batch.create');
Route::post('/batch/store',                   [\App\Http\Controllers\BatchBarangController::class, 'store'])->name('batch.store');
Route::post('/batch',                         [\App\Http\Controllers\BatchBarangController::class, 'store']);
Route::get('/batch/{id}/edit',                [\App\Http\Controllers\BatchBarangController::class, 'edit'])->name('batch.edit');
Route::put('/batch/{id}',                     [\App\Http\Controllers\BatchBarangController::class, 'update'])->name('batch.update');
Route::delete('/batch/{id}',                  [\App\Http\Controllers\BatchBarangController::class, 'destroy'])->name('batch.destroy');

Route::get('/farmasi/daftar-batch',           [\App\Http\Controllers\BatchBarangController::class, 'index']);
Route::get('/farmasi/daftar-batch/create',    [\App\Http\Controllers\BatchBarangController::class, 'create']);
Route::post('/farmasi/daftar-batch',          [\App\Http\Controllers\BatchBarangController::class, 'store']);
Route::get('/farmasi/daftar-batch/{id}/edit', [\App\Http\Controllers\BatchBarangController::class, 'edit']);
Route::put('/farmasi/daftar-batch/{id}',      [\App\Http\Controllers\BatchBarangController::class, 'update']);
Route::delete('/farmasi/daftar-batch/{id}',   [\App\Http\Controllers\BatchBarangController::class, 'destroy']);

// === Setting Harga Barang & Biaya Racik (KONHAKES 1:1) ===
Route::get('/hargaobat/index',                                  [\App\Http\Controllers\SettingHargaController::class, 'index'])->name('hargaobat.index');
Route::get('/hargaobat',                                        [\App\Http\Controllers\SettingHargaController::class, 'index']);
Route::get('/hargaobat/biaya-racik',                            [\App\Http\Controllers\SettingHargaController::class, 'biayaRacikIndex'])->name('hargaobat.biaya_racik.page');
Route::get('/hargaobat/{penjaminId}/biaya-racik',               [\App\Http\Controllers\SettingHargaController::class, 'getBiayaRacik'])->name('hargaobat.biaya_racik.get');
Route::post('/hargaobat/{penjaminId}/biaya-racik',              [\App\Http\Controllers\SettingHargaController::class, 'saveBiayaRacik'])->name('hargaobat.biaya_racik.save');
Route::get('/hargaobat/{penjaminId}/pricelist',                 [\App\Http\Controllers\SettingHargaController::class, 'pricelist'])->name('hargaobat.pricelist');
Route::post('/hargaobat/{penjaminId}/pricelist',                [\App\Http\Controllers\SettingHargaController::class, 'updatePricelist'])->name('hargaobat.pricelist.update');

Route::get('/farmasi/setting-harga',                            [\App\Http\Controllers\SettingHargaController::class, 'index']);
Route::get('/farmasi/setting-biaya-racik',                      [\App\Http\Controllers\SettingHargaController::class, 'biayaRacikIndex']);
Route::get('/farmasi/setting-harga/{penjaminId}/biaya-racik',   [\App\Http\Controllers\SettingHargaController::class, 'getBiayaRacik']);
Route::post('/farmasi/setting-harga/{penjaminId}/biaya-racik',  [\App\Http\Controllers\SettingHargaController::class, 'saveBiayaRacik']);
Route::get('/farmasi/setting-harga/{penjaminId}/pricelist',     [\App\Http\Controllers\SettingHargaController::class, 'pricelist']);
Route::post('/farmasi/setting-harga/{penjaminId}/pricelist',    [\App\Http\Controllers\SettingHargaController::class, 'updatePricelist']);
Route::get('/farmasi/setting-harga/{id}/edit',                  [\App\Http\Controllers\SettingHargaController::class, 'edit']);
Route::put('/farmasi/setting-harga/{id}',                       [\App\Http\Controllers\SettingHargaController::class, 'update']);

// === Stok Barang Per-Gudang (KONHAKES 1:1) ===
Route::get('/stok/index',                        [\App\Http\Controllers\StokBarangController::class, 'index'])->name('stok.index');
Route::get('/stok',                              [\App\Http\Controllers\StokBarangController::class, 'index']);
Route::get('/stok/create',                       [\App\Http\Controllers\StokBarangController::class, 'createStokAwal'])->name('stok.create');
Route::post('/stok/store',                       [\App\Http\Controllers\StokBarangController::class, 'storeStokAwal'])->name('stok.store');
Route::post('/stok',                             [\App\Http\Controllers\StokBarangController::class, 'storeStokAwal']);
Route::get('/stok/export',                       [\App\Http\Controllers\StokBarangController::class, 'export'])->name('stok.export');
Route::get('/stok/upload',                       [\App\Http\Controllers\StokBarangController::class, 'uploadForm'])->name('stok.upload');
Route::post('/stok/upload',                      [\App\Http\Controllers\StokBarangController::class, 'processUpload'])->name('stok.upload.process');
Route::get('/stok/template-upload',              [\App\Http\Controllers\StokBarangController::class, 'downloadTemplate'])->name('stok.template');
Route::get('/stok/kartu-stok/{id}',              [\App\Http\Controllers\StokBarangController::class, 'kartuStok'])->name('stok.kartu_stok');

// === Histori Penyesuaian Stok (KONHAKES 1:1) ===
Route::get('/stok-adjustment/index',             [\App\Http\Controllers\StokAdjustmentController::class, 'index'])->name('stok_adjustment.index');
Route::get('/stok-adjustment',                   [\App\Http\Controllers\StokAdjustmentController::class, 'index']);
Route::get('/stok-adjustment/create',            [\App\Http\Controllers\StokAdjustmentController::class, 'create'])->name('stok_adjustment.create');
Route::post('/stok-adjustment/store',            [\App\Http\Controllers\StokAdjustmentController::class, 'store'])->name('stok_adjustment.store');
Route::post('/stok-adjustment',                  [\App\Http\Controllers\StokAdjustmentController::class, 'store']);
Route::get('/stok-adjustment/view',              [\App\Http\Controllers\StokAdjustmentController::class, 'show'])->name('stok_adjustment.view');
Route::get('/stok-adjustment/export-pdf',        [\App\Http\Controllers\StokAdjustmentController::class, 'exportPdf'])->name('stok_adjustment.pdf');
Route::get('/stok-adjustment/export-xls',        [\App\Http\Controllers\StokAdjustmentController::class, 'exportXls'])->name('stok_adjustment.xls');
Route::get('/stok-adjustment/{id}',              [\App\Http\Controllers\StokAdjustmentController::class, 'show'])->name('stok_adjustment.show');

Route::get('/farmasi/stok-barang',               [\App\Http\Controllers\StokBarangController::class, 'indexStok']);
// === Stok Cancels (Pembatalan Stok KONHAKES 1:1) ===
Route::get('/stok-cancel/index',                 [\App\Http\Controllers\StokCancelController::class, 'index'])->name('stok_cancel.index');
Route::get('/stok-cancel',                       [\App\Http\Controllers\StokCancelController::class, 'index']);
Route::get('/stok-cancel/view',                  [\App\Http\Controllers\StokCancelController::class, 'view'])->name('stok_cancel.view');
Route::get('/stok-cancel/{id}',                  [\App\Http\Controllers\StokCancelController::class, 'show'])->name('stok_cancel.show');
Route::post('/stok-cancel/store',                [\App\Http\Controllers\StokCancelController::class, 'store'])->name('stok_cancel.store');
Route::post('/stok-cancel',                      [\App\Http\Controllers\StokCancelController::class, 'store']);
Route::get('/farmasi/pembatalan-stok',           [\App\Http\Controllers\StokCancelController::class, 'index']);
Route::post('/farmasi/pembatalan-stok',          [\App\Http\Controllers\StokCancelController::class, 'store']);
Route::post('/farmasi/pembatalan-stok/{id}',     [\App\Http\Controllers\StokBarangController::class, 'batalStok']);
Route::get('/farmasi/history-penyesuaian',       [\App\Http\Controllers\StokAdjustmentController::class, 'index']);

// === Masuk Barang / Purchasing ===
Route::get('/farmasi/order-pembelian',           [\App\Http\Controllers\OrderPembelianController::class, 'index']);
Route::get('/farmasi/order-pembelian/create',    [\App\Http\Controllers\OrderPembelianController::class, 'create']);
Route::post('/farmasi/order-pembelian',          [\App\Http\Controllers\OrderPembelianController::class, 'store']);
Route::get('/farmasi/order-pembelian/{id}',      [\App\Http\Controllers\OrderPembelianController::class, 'show']);
Route::delete('/farmasi/order-pembelian/{id}',   [\App\Http\Controllers\OrderPembelianController::class, 'destroy']);

// === Transaksi Penerimaan Barang / Barang Masuk (KONHAKES 1:1) ===
Route::get('/barangmasuk/index',              [\App\Http\Controllers\MasukBarangController::class, 'index'])->name('barangmasuk.index');
Route::get('/barangmasuk',                    [\App\Http\Controllers\MasukBarangController::class, 'index']);
Route::get('/barangmasuk/create',             [\App\Http\Controllers\MasukBarangController::class, 'create'])->name('barangmasuk.create');
Route::post('/barangmasuk/store',             [\App\Http\Controllers\MasukBarangController::class, 'store'])->name('barangmasuk.store');
Route::post('/barangmasuk',                   [\App\Http\Controllers\MasukBarangController::class, 'store']);
Route::get('/barangmasuk/cetak-pdf',          [\App\Http\Controllers\MasukBarangController::class, 'cetakPdf'])->name('barangmasuk.cetak_pdf');
Route::get('/barangmasuk/export-excel',       [\App\Http\Controllers\MasukBarangController::class, 'exportExcel'])->name('barangmasuk.export_excel');
Route::get('/barangmasuk/get-po/{id}',        [\App\Http\Controllers\MasukBarangController::class, 'getPoDetails'])->name('barangmasuk.get_po');
Route::post('/barangmasuk/quick-vendor',      [\App\Http\Controllers\MasukBarangController::class, 'quickVendor'])->name('barangmasuk.quick_vendor');
Route::get('/barangmasuk/{id}/cetak-pdf',     [\App\Http\Controllers\MasukBarangController::class, 'cetakSingle'])->name('barangmasuk.cetak_single');
Route::get('/barangmasuk/{id}',               [\App\Http\Controllers\MasukBarangController::class, 'show'])->name('barangmasuk.show');
Route::delete('/barangmasuk/{id}',            [\App\Http\Controllers\MasukBarangController::class, 'destroy'])->name('barangmasuk.destroy');

// Aliases for /farmasi/masuk-barang
Route::get('/farmasi/masuk-barang',           [\App\Http\Controllers\MasukBarangController::class, 'index']);
Route::get('/farmasi/masuk-barang/create',    [\App\Http\Controllers\MasukBarangController::class, 'create']);
Route::post('/farmasi/masuk-barang',          [\App\Http\Controllers\MasukBarangController::class, 'store']);
Route::get('/farmasi/masuk-barang/{id}',      [\App\Http\Controllers\MasukBarangController::class, 'show']);
Route::delete('/farmasi/masuk-barang/{id}',   [\App\Http\Controllers\MasukBarangController::class, 'destroy']);

// === Data Pembayaran Hutang (KONHAKES 1:1) ===
Route::get('/pembayaran-hutang/index',        [\App\Http\Controllers\HutangController::class, 'index'])->name('pembayaran_hutang.index');
Route::get('/pembayaran-hutang',              [\App\Http\Controllers\HutangController::class, 'index']);
Route::get('/pembayaran-hutang/export-excel', [\App\Http\Controllers\HutangController::class, 'exportExcel'])->name('pembayaran_hutang.export_excel');
Route::get('/pembayaran-hutang/{id}/cetak-kuitansi', [\App\Http\Controllers\HutangController::class, 'cetakKuitansi'])->name('pembayaran_hutang.cetak_kuitansi');
Route::get('/pembayaran-hutang/{id}',         [\App\Http\Controllers\HutangController::class, 'show'])->name('pembayaran_hutang.show');
Route::post('/pembayaran-hutang/{id}/bayar',  [\App\Http\Controllers\HutangController::class, 'bayar'])->name('pembayaran_hutang.bayar');

Route::get('/farmasi/hutang',                 [\App\Http\Controllers\HutangController::class, 'index']);
Route::get('/farmasi/hutang/export-excel',    [\App\Http\Controllers\HutangController::class, 'exportExcel']);
Route::get('/farmasi/hutang/{id}',            [\App\Http\Controllers\HutangController::class, 'show']);
Route::post('/farmasi/hutang/{id}/bayar',     [\App\Http\Controllers\HutangController::class, 'bayar']);

// === Mutasi Barang Antar Gudang (KONHAKES 1:1) ===
Route::get('/mutasibarang/index',             [\App\Http\Controllers\MutasiBarangController::class, 'index'])->name('mutasibarang.index');
Route::get('/mutasibarang',                   [\App\Http\Controllers\MutasiBarangController::class, 'index']);
Route::get('/mutasibarang/create',            [\App\Http\Controllers\MutasiBarangController::class, 'create'])->name('mutasibarang.create');
Route::post('/mutasibarang/store',            [\App\Http\Controllers\MutasiBarangController::class, 'store'])->name('mutasibarang.store');
Route::post('/mutasibarang',                  [\App\Http\Controllers\MutasiBarangController::class, 'store']);
Route::get('/mutasibarang/get-batches/{barangId}', [\App\Http\Controllers\MutasiBarangController::class, 'getBatches'])->name('mutasibarang.get_batches');
Route::get('/mutasibarang/{id}/cetak-pdf',    [\App\Http\Controllers\MutasiBarangController::class, 'cetakSingle'])->name('mutasibarang.cetak_single');
Route::get('/mutasibarang/{id}',              [\App\Http\Controllers\MutasiBarangController::class, 'show'])->name('mutasibarang.show');
Route::delete('/mutasibarang/{id}',           [\App\Http\Controllers\MutasiBarangController::class, 'destroy'])->name('mutasibarang.destroy');

// Aliases for /farmasi/mutasi-barang
Route::get('/farmasi/mutasi-barang',           [\App\Http\Controllers\MutasiBarangController::class, 'index']);
Route::get('/farmasi/mutasi-barang/create',    [\App\Http\Controllers\MutasiBarangController::class, 'create']);
Route::post('/farmasi/mutasi-barang',          [\App\Http\Controllers\MutasiBarangController::class, 'store']);
Route::get('/farmasi/mutasi-barang/{id}',      [\App\Http\Controllers\MutasiBarangController::class, 'show']);
Route::delete('/farmasi/mutasi-barang/{id}',   [\App\Http\Controllers\MutasiBarangController::class, 'destroy']);

// === Transaksi Penerimaan Mutasi Barang Antar Gudang (KONHAKES 1:1) ===
Route::get('/penerimaanmutasibarang/index',             [\App\Http\Controllers\MutasiBarangController::class, 'indexPenerimaan'])->name('penerimaanmutasibarang.index');
Route::get('/penerimaanmutasibarang',                   [\App\Http\Controllers\MutasiBarangController::class, 'indexPenerimaan']);
Route::get('/penerimaanmutasibarang/create',            [\App\Http\Controllers\MutasiBarangController::class, 'create'])->name('penerimaanmutasibarang.create');
Route::post('/penerimaanmutasibarang/{id}/terima',      [\App\Http\Controllers\MutasiBarangController::class, 'terimaMutasi'])->name('penerimaanmutasibarang.terima');
Route::get('/farmasi/penerimaan-mutasi',                [\App\Http\Controllers\MutasiBarangController::class, 'indexPenerimaan']);
Route::post('/farmasi/penerimaan-mutasi/{id}',          [\App\Http\Controllers\MutasiBarangController::class, 'terimaMutasi']);

// === Transaksi Pembuatan Order / Pemesanan Barang (PO) (KONHAKES 1:1) ===
Route::get('/pembuatanorder/index',              [\App\Http\Controllers\PembuatanOrderController::class, 'index'])->name('pembuatanorder.index');
Route::get('/pembuatanorder',                    [\App\Http\Controllers\PembuatanOrderController::class, 'index']);
Route::get('/pembuatanorder/create',             [\App\Http\Controllers\PembuatanOrderController::class, 'create'])->name('pembuatanorder.create');
Route::post('/pembuatanorder/store',             [\App\Http\Controllers\PembuatanOrderController::class, 'store'])->name('pembuatanorder.store');
Route::post('/pembuatanorder',                   [\App\Http\Controllers\PembuatanOrderController::class, 'store']);
Route::get('/pembuatanorder/cetak-pdf',          [\App\Http\Controllers\PembuatanOrderController::class, 'cetakPdf'])->name('pembuatanorder.cetak_pdf');
Route::get('/pembuatanorder/{id}/cetak-pdf',     [\App\Http\Controllers\PembuatanOrderController::class, 'cetakPdf'])->name('pembuatanorder.cetak_single');
Route::get('/pembuatanorder/{id}',               [\App\Http\Controllers\PembuatanOrderController::class, 'show'])->name('pembuatanorder.show');
Route::delete('/pembuatanorder/{id}',            [\App\Http\Controllers\PembuatanOrderController::class, 'destroy'])->name('pembuatanorder.destroy');
Route::post('/pembuatanorder/quick-vendor',      [\App\Http\Controllers\PembuatanOrderController::class, 'quickVendor'])->name('pembuatanorder.quick_vendor');

Route::get('/farmasi/order-pembelian',           [\App\Http\Controllers\PembuatanOrderController::class, 'index']);
Route::get('/farmasi/order-pembelian/create',    [\App\Http\Controllers\PembuatanOrderController::class, 'create']);
Route::post('/farmasi/order-pembelian',          [\App\Http\Controllers\PembuatanOrderController::class, 'store']);

// === Transaksi Resep & Transaksi BHP (KONHAKES 1:1) ===
Route::get('/transaksiresep/index',             [\App\Http\Controllers\ResepController::class, 'index'])->name('transaksiresep.index');
Route::get('/transaksiresep',                   [\App\Http\Controllers\ResepController::class, 'index']);
Route::get('/transaksiresep/cetak-pdf',          [\App\Http\Controllers\ResepController::class, 'cetakPdf'])->name('transaksiresep.cetak_pdf');
Route::get('/transaksiresep/cetak-xls',          [\App\Http\Controllers\ResepController::class, 'cetakXls'])->name('transaksiresep.cetak_xls');
Route::get('/transaksiresep/{id}/detail',       [\App\Http\Controllers\ResepController::class, 'show'])->name('transaksiresep.show');
Route::post('/transaksiresep/{id}/proses',      [\App\Http\Controllers\ResepController::class, 'prosesResep'])->name('transaksiresep.proses');
Route::post('/transaksiresep/{id}/serahkan',    [\App\Http\Controllers\ResepController::class, 'serahkanObat'])->name('transaksiresep.serahkan');
Route::post('/transaksiresep/{id}/conseling',   [\App\Http\Controllers\ResepController::class, 'simpanConseling'])->name('transaksiresep.conseling');
Route::post('/transaksiresep/{id}/pio',          [\App\Http\Controllers\ResepController::class, 'simpanPio'])->name('transaksiresep.pio');

// Transaksi BHP
Route::get('/transaksibhp/index',               [\App\Http\Controllers\ResepController::class, 'indexBhp'])->name('transaksibhp.index');
Route::get('/transaksibhp',                     [\App\Http\Controllers\ResepController::class, 'indexBhp']);

// Aliases
Route::get('/farmasi/resep',                    [\App\Http\Controllers\ResepController::class, 'index'])->name('resep.index');
Route::get('/farmasi/resep/create',             [\App\Http\Controllers\ResepController::class, 'create'])->name('resep.create');
Route::post('/farmasi/resep',                   [\App\Http\Controllers\ResepController::class, 'store'])->name('resep.store');
Route::get('/farmasi/resep/{id}',               [\App\Http\Controllers\ResepController::class, 'show']);
Route::post('/farmasi/resep/{id}/layani',       [\App\Http\Controllers\ResepController::class, 'layani']);

// === Transaksi Penjualan Apotek (KONHAKES 1:1) ===
Route::get('/transaksipenjualan/index',             [\App\Http\Controllers\PenjualanApotekController::class, 'index'])->name('transaksipenjualan.index');
Route::get('/transaksipenjualan',                   [\App\Http\Controllers\PenjualanApotekController::class, 'index']);
Route::get('/transaksipenjualan/create',            [\App\Http\Controllers\PenjualanApotekController::class, 'create'])->name('transaksipenjualan.create');
Route::post('/transaksipenjualan/store',            [\App\Http\Controllers\PenjualanApotekController::class, 'store'])->name('transaksipenjualan.store');
Route::post('/transaksipenjualan',                  [\App\Http\Controllers\PenjualanApotekController::class, 'store']);
Route::get('/transaksipenjualan/{id}',              [\App\Http\Controllers\PenjualanApotekController::class, 'show'])->name('transaksipenjualan.show');
Route::get('/transaksipenjualan/{id}/cetak-nota',   [\App\Http\Controllers\PenjualanApotekController::class, 'cetakNota'])->name('transaksipenjualan.cetak_nota');
Route::get('/transaksipenjualan/cetak-laporan',     [\App\Http\Controllers\PenjualanApotekController::class, 'cetakLaporan'])->name('transaksipenjualan.cetak_laporan');
Route::post('/transaksipenjualan/quick-pasien',     [\App\Http\Controllers\PenjualanApotekController::class, 'quickPasien'])->name('transaksipenjualan.quick_pasien');
Route::get('/transaksipenjualan/search-obat',       [\App\Http\Controllers\PenjualanApotekController::class, 'searchObat'])->name('transaksipenjualan.search_obat');

// Aliases
Route::get('/farmasi/penjualan',                    [\App\Http\Controllers\PenjualanApotekController::class, 'index'])->name('penjualan.index');
Route::get('/farmasi/penjualan/create',             [\App\Http\Controllers\PenjualanApotekController::class, 'create'])->name('penjualan.create');
Route::post('/farmasi/penjualan',                   [\App\Http\Controllers\PenjualanApotekController::class, 'store'])->name('penjualan.store');
Route::get('/farmasi/penjualan/{id}',               [\App\Http\Controllers\PenjualanApotekController::class, 'show']);

// === Bridging PCare & i-Care ===
Route::get('/bridging/pcare',              [\App\Http\Controllers\BridgingConfigController::class, 'configPcare']);
Route::post('/bridging/pcare',             [\App\Http\Controllers\BridgingConfigController::class, 'storePcare']);
Route::post('/bridging/test',              [\App\Http\Controllers\BridgingConfigController::class, 'testConnection']);

// === PCare Setting Data ===
Route::get('/pcare/setting/akun',          [\App\Http\Controllers\PcareController::class, 'settingAkun'])->name('pcare.setting.akun');
Route::post('/pcare/setting/akun/update',  [\App\Http\Controllers\PcareController::class, 'updateSettingAkun'])->name('pcare.setting.akun.update');
Route::post('/pcare/setting/akun/test',    [\App\Http\Controllers\PcareController::class, 'testKoneksiPcare'])->name('pcare.setting.akun.test');
Route::get('/pcare/setting/poli',              [\App\Http\Controllers\PcareController::class, 'settingPoli'])->name('pcare.setting.poli');
Route::post('/pcare/setting/poli/update',      [\App\Http\Controllers\PcareController::class, 'updateSettingPoli'])->name('pcare.setting.poli.update');
Route::post('/pcare/setting/poli/tarik',       [\App\Http\Controllers\PcareController::class, 'tarikDataPoli'])->name('pcare.setting.poli.tarik');
Route::get('/pcare/setting/dokter',            [\App\Http\Controllers\PcareController::class, 'settingDokter'])->name('pcare.setting.dokter');
Route::post('/pcare/setting/dokter/tarik',     [\App\Http\Controllers\PcareController::class, 'tarikDataDokter'])->name('pcare.setting.dokter.tarik');
Route::post('/pcare/setting/dokter/mapping',   [\App\Http\Controllers\PcareController::class, 'mappingDokterPcare'])->name('pcare.setting.dokter.mapping');
// === PCare TKP ===
Route::get('/pcare-tkp/index',             [\App\Http\Controllers\PcareController::class, 'settingTkp'])->name('pcare.tkp.index');
Route::get('/pcare-tkp',                   [\App\Http\Controllers\PcareController::class, 'settingTkp']);
Route::get('/pcare/setting/tkp',           [\App\Http\Controllers\PcareController::class, 'settingTkp'])->name('pcare.setting.tkp');
Route::get('/pcare-tkp/create',            [\App\Http\Controllers\PcareController::class, 'createTkp'])->name('pcare.tkp.create');
Route::post('/pcare-tkp/store',            [\App\Http\Controllers\PcareController::class, 'storeTkp'])->name('pcare.tkp.store');
Route::post('/pcare-tkp',                  [\App\Http\Controllers\PcareController::class, 'storeTkp']);
Route::get('/pcare-tkp/{id}',              [\App\Http\Controllers\PcareController::class, 'showTkp'])->name('pcare.tkp.show');
Route::get('/pcare-tkp/{id}/edit',         [\App\Http\Controllers\PcareController::class, 'editTkp'])->name('pcare.tkp.edit');
Route::match(['post', 'put'], '/pcare-tkp/{id}/update', [\App\Http\Controllers\PcareController::class, 'updateTkp'])->name('pcare.tkp.update');
Route::delete('/pcare-tkp/{id}',           [\App\Http\Controllers\PcareController::class, 'destroyTkp'])->name('pcare.tkp.destroy');
Route::get('/pcare-tkp/{id}/delete',       [\App\Http\Controllers\PcareController::class, 'destroyTkp']);
// === PCare Kesadaran ===
Route::get('/pcare-kesadaran/index',             [\App\Http\Controllers\PcareController::class, 'settingKesadaran'])->name('pcare.kesadaran.index');
Route::get('/pcare-kesadaran',                   [\App\Http\Controllers\PcareController::class, 'settingKesadaran']);
Route::get('/pcare/setting/kesadaran',           [\App\Http\Controllers\PcareController::class, 'settingKesadaran'])->name('pcare.setting.kesadaran');
Route::post('/pcare-kesadaran/tarik',            [\App\Http\Controllers\PcareController::class, 'tarikDataKesadaran'])->name('pcare.kesadaran.tarik');
Route::get('/pcare-kesadaran/{id}',              [\App\Http\Controllers\PcareController::class, 'showKesadaran'])->name('pcare.kesadaran.show');
Route::get('/pcare-kesadaran/{id}/edit',         [\App\Http\Controllers\PcareController::class, 'editKesadaran'])->name('pcare.kesadaran.edit');
Route::match(['post', 'put'], '/pcare-kesadaran/{id}/update', [\App\Http\Controllers\PcareController::class, 'updateKesadaran'])->name('pcare.kesadaran.update');
Route::delete('/pcare-kesadaran/{id}',           [\App\Http\Controllers\PcareController::class, 'destroyKesadaran'])->name('pcare.kesadaran.destroy');
Route::get('/pcare-kesadaran/{id}/delete',       [\App\Http\Controllers\PcareController::class, 'destroyKesadaran']);
// === PCare TACC ===
Route::get('/pcare-tacc/index',             [\App\Http\Controllers\PcareController::class, 'settingTacc'])->name('pcare.tacc.index');
Route::get('/pcare-tacc',                   [\App\Http\Controllers\PcareController::class, 'settingTacc']);
Route::get('/pcare/setting/tacc',           [\App\Http\Controllers\PcareController::class, 'settingTacc'])->name('pcare.setting.tacc');
Route::get('/pcare-tacc/create',            [\App\Http\Controllers\PcareController::class, 'createTacc'])->name('pcare.tacc.create');
Route::post('/pcare-tacc/store',            [\App\Http\Controllers\PcareController::class, 'storeTacc'])->name('pcare.tacc.store');
Route::post('/pcare-tacc',                  [\App\Http\Controllers\PcareController::class, 'storeTacc']);
Route::get('/pcare-tacc/{id}',              [\App\Http\Controllers\PcareController::class, 'showTacc'])->name('pcare.tacc.show');
Route::get('/pcare-tacc/{id}/edit',         [\App\Http\Controllers\PcareController::class, 'editTacc'])->name('pcare.tacc.edit');
Route::match(['post', 'put'], '/pcare-tacc/{id}/update', [\App\Http\Controllers\PcareController::class, 'updateTacc'])->name('pcare.tacc.update');
Route::delete('/pcare-tacc/{id}',           [\App\Http\Controllers\PcareController::class, 'destroyTacc'])->name('pcare.tacc.destroy');
Route::get('/pcare-tacc/{id}/delete',       [\App\Http\Controllers\PcareController::class, 'destroyTacc']);
// === PCare Status Pulang ===
Route::get('/pcare-status-pulang/index',     [\App\Http\Controllers\PcareController::class, 'settingStatusPulang'])->name('pcare.status-pulang.index');
Route::get('/pcare-status-pulang',           [\App\Http\Controllers\PcareController::class, 'settingStatusPulang']);
Route::get('/pcare/setting/status-pulang',   [\App\Http\Controllers\PcareController::class, 'settingStatusPulang'])->name('pcare.setting.status-pulang');
Route::get('/pcare-status-pulang/update',    [\App\Http\Controllers\PcareController::class, 'editStatusPulang'])->name('pcare.status-pulang.edit');
Route::post('/pcare-status-pulang/update',   [\App\Http\Controllers\PcareController::class, 'updateBatchStatusPulang'])->name('pcare.status-pulang.update');
Route::post('/pcare-status-pulang/tarik',    [\App\Http\Controllers\PcareController::class, 'tarikDataStatusPulang'])->name('pcare.status-pulang.tarik');
// === PCare Spesialis ===
Route::get('/pcare-spesialis/index',         [\App\Http\Controllers\PcareController::class, 'settingSpesialis'])->name('pcare.spesialis.index');
Route::get('/pcare-spesialis',               [\App\Http\Controllers\PcareController::class, 'settingSpesialis']);
Route::get('/pcare/setting/spesialis',       [\App\Http\Controllers\PcareController::class, 'settingSpesialis'])->name('pcare.setting.spesialis');
Route::post('/pcare-spesialis/tarik',        [\App\Http\Controllers\PcareController::class, 'tarikDataSpesialis'])->name('pcare.spesialis.tarik');
Route::get('/pcare-spesialis/sub-spesialis', [\App\Http\Controllers\PcareController::class, 'subSpesialis'])->name('pcare.spesialis.sub');
// === PCare Sarana ===
Route::get('/pcare-sarana/index',           [\App\Http\Controllers\PcareController::class, 'settingSarana'])->name('pcare.sarana.index');
Route::get('/pcare-sarana',                 [\App\Http\Controllers\PcareController::class, 'settingSarana']);
Route::get('/pcare/setting/sarana',         [\App\Http\Controllers\PcareController::class, 'settingSarana'])->name('pcare.setting.sarana');
Route::post('/pcare-sarana/tarik',          [\App\Http\Controllers\PcareController::class, 'tarikDataSarana'])->name('pcare.sarana.tarik');
Route::get('/pcare-sarana/{id}',            [\App\Http\Controllers\PcareController::class, 'showSarana'])->name('pcare.sarana.show');
Route::get('/pcare-sarana/{id}/edit',       [\App\Http\Controllers\PcareController::class, 'editSarana'])->name('pcare.sarana.edit');
Route::match(['post', 'put'], '/pcare-sarana/{id}/update', [\App\Http\Controllers\PcareController::class, 'updateSarana'])->name('pcare.sarana.update');
Route::delete('/pcare-sarana/{id}',         [\App\Http\Controllers\PcareController::class, 'destroySarana'])->name('pcare.sarana.destroy');
Route::get('/pcare-sarana/{id}/delete',     [\App\Http\Controllers\PcareController::class, 'destroySarana']);
// === PCare Khusus ===
Route::get('/pcare-khusus/index',           [\App\Http\Controllers\PcareController::class, 'settingKhusus'])->name('pcare.khusus.index');
Route::get('/pcare-khusus',                 [\App\Http\Controllers\PcareController::class, 'settingKhusus']);
Route::get('/pcare/setting/khusus',         [\App\Http\Controllers\PcareController::class, 'settingKhusus'])->name('pcare.setting.khusus');
Route::post('/pcare-khusus/tarik',          [\App\Http\Controllers\PcareController::class, 'tarikDataKhusus'])->name('pcare.khusus.tarik');
Route::post('/pcare-khusus/update-batch',   [\App\Http\Controllers\PcareController::class, 'updateBatchKhusus'])->name('pcare.khusus.update_batch');
Route::get('/pcare-khusus/{id}',            [\App\Http\Controllers\PcareController::class, 'showKhusus'])->name('pcare.khusus.show');
Route::get('/pcare-khusus/{id}/edit',       [\App\Http\Controllers\PcareController::class, 'editKhusus'])->name('pcare.khusus.edit');
Route::match(['post', 'put'], '/pcare-khusus/{id}/update', [\App\Http\Controllers\PcareController::class, 'updateKhusus'])->name('pcare.khusus.update');
Route::delete('/pcare-khusus/{id}',         [\App\Http\Controllers\PcareController::class, 'destroyKhusus'])->name('pcare.khusus.destroy');
Route::get('/pcare-khusus/{id}/delete',     [\App\Http\Controllers\PcareController::class, 'destroyKhusus']);
// === PCare Tindakan ===
Route::get('/pcare-tindakan/index',           [\App\Http\Controllers\PcareController::class, 'settingTindakan'])->name('pcare.tindakan.index');
Route::get('/pcare-tindakan',                 [\App\Http\Controllers\PcareController::class, 'settingTindakan']);
Route::get('/pcare/setting/tindakan',         [\App\Http\Controllers\PcareController::class, 'settingTindakan'])->name('pcare.setting.tindakan');
Route::post('/pcare-tindakan/tarik',          [\App\Http\Controllers\PcareController::class, 'tarikDataTindakan'])->name('pcare.tindakan.tarik');
Route::post('/pcare-tindakan/update-batch',   [\App\Http\Controllers\PcareController::class, 'updateBatchTindakan'])->name('pcare.tindakan.update_batch');
Route::get('/pcare-tindakan/{id}/edit',       [\App\Http\Controllers\PcareController::class, 'editTindakan'])->name('pcare.tindakan.edit');
Route::match(['post', 'put'], '/pcare-tindakan/{id}/update', [\App\Http\Controllers\PcareController::class, 'updateTindakan'])->name('pcare.tindakan.update');
Route::get('/pcare-alergi/index',           [\App\Http\Controllers\PcareController::class, 'settingAlergi'])->name('pcare.alergi.index');
Route::get('/pcare-alergi',                 [\App\Http\Controllers\PcareController::class, 'settingAlergi']);
Route::get('/pcare/setting/alergi',         [\App\Http\Controllers\PcareController::class, 'settingAlergi'])->name('pcare.setting.alergi');
Route::post('/pcare-alergi/tarik',          [\App\Http\Controllers\PcareController::class, 'tarikDataAlergi'])->name('pcare.alergi.tarik');
Route::post('/pcare-alergi/update-batch',   [\App\Http\Controllers\PcareController::class, 'updateBatchAlergi'])->name('pcare.alergi.update_batch');
Route::get('/pcare-alergi/{id}',            [\App\Http\Controllers\PcareController::class, 'showAlergi'])->name('pcare.alergi.show');
Route::get('/pcare-alergi/{id}/edit',       [\App\Http\Controllers\PcareController::class, 'editAlergi'])->name('pcare.alergi.edit');
Route::match(['post', 'put'], '/pcare-alergi/{id}/update', [\App\Http\Controllers\PcareController::class, 'updateAlergi'])->name('pcare.alergi.update');
Route::delete('/pcare-alergi/{id}',         [\App\Http\Controllers\PcareController::class, 'destroyAlergi'])->name('pcare.alergi.destroy');
Route::get('/pcare-alergi/{id}/delete',      [\App\Http\Controllers\PcareController::class, 'destroyAlergi']);
Route::get('/pcare-prognosa/index',         [\App\Http\Controllers\PcareController::class, 'settingPrognosa'])->name('pcare.prognosa.index');
Route::get('/pcare-prognosa',               [\App\Http\Controllers\PcareController::class, 'settingPrognosa']);
Route::get('/pcare/setting/prognosa',       [\App\Http\Controllers\PcareController::class, 'settingPrognosa'])->name('pcare.setting.prognosa');
Route::post('/pcare-prognosa/tarik',        [\App\Http\Controllers\PcareController::class, 'tarikDataPrognosa'])->name('pcare.prognosa.tarik');
Route::post('/pcare-prognosa/update-batch', [\App\Http\Controllers\PcareController::class, 'updateBatchPrognosa'])->name('pcare.prognosa.update_batch');
Route::get('/pcare-prognosa/{id}',          [\App\Http\Controllers\PcareController::class, 'showPrognosa'])->name('pcare.prognosa.show');
Route::get('/pcare-prognosa/{id}/edit',     [\App\Http\Controllers\PcareController::class, 'editPrognosa'])->name('pcare.prognosa.edit');
Route::match(['post', 'put'], '/pcare-prognosa/{id}/update', [\App\Http\Controllers\PcareController::class, 'updatePrognosa'])->name('pcare.prognosa.update');
Route::delete('/pcare-prognosa/{id}',       [\App\Http\Controllers\PcareController::class, 'destroyPrognosa'])->name('pcare.prognosa.destroy');
Route::get('/pcare-prognosa/{id}/delete',    [\App\Http\Controllers\PcareController::class, 'destroyPrognosa']);

// === PCare Data ===
Route::get('/pcare-diagnosa/index',       [\App\Http\Controllers\PcareController::class, 'pencarianDiagnosa'])->name('pcare.diagnosa.index');
Route::get('/pcare-diagnosa',             [\App\Http\Controllers\PcareController::class, 'pencarianDiagnosa']);
Route::get('/pcare/pencarian-diagnosa',   [\App\Http\Controllers\PcareController::class, 'pencarianDiagnosa'])->name('pcare.pencarian-diagnosa');
Route::get('/pcare-pasien/index',          [\App\Http\Controllers\PcareController::class, 'listPasienBpjs'])->name('pcare.pasien.index');
Route::get('/pcare-pasien',                [\App\Http\Controllers\PcareController::class, 'listPasienBpjs']);
Route::get('/pcare/list-pasien-bpjs',      [\App\Http\Controllers\PcareController::class, 'listPasienBpjs'])->name('pcare.list-pasien-bpjs');
Route::post('/pcare-pasien/tarik',         [\App\Http\Controllers\PcareController::class, 'tarikPasienPcare'])->name('pcare.pasien.tarik');
Route::get('/pcare-pasien/export-excel',    [\App\Http\Controllers\PcareController::class, 'exportExcelPasienBpjs'])->name('pcare.pasien.export_excel');
Route::get('/pcare-pasien/export-pdf',      [\App\Http\Controllers\PcareController::class, 'exportPdfPasienBpjs'])->name('pcare.pasien.export_pdf');
Route::get('/pasien/view', function (\Illuminate\Http\Request $request) {
    $id = trim($request->get('id', ''));
    $pasien = \App\Models\Pasien::where('no_rm', $id)->orWhere('id', $id)->first();
    if ($pasien) {
        return redirect('/pasien/' . $pasien->id . '/edit');
    }
    return redirect('/pcare-pasien/index')->with('error', 'Pasien dengan ID/No RM ' . $id . ' tidak ditemukan.');
})->name('pasien.view');
Route::get('/pcare-pendaftaran/index',        [\App\Http\Controllers\PcareController::class, 'cekPendaftaran'])->name('pcare.pendaftaran.index');
Route::get('/pcare-pendaftaran',              [\App\Http\Controllers\PcareController::class, 'cekPendaftaran']);
Route::get('/pcare/cek-pendaftaran',          [\App\Http\Controllers\PcareController::class, 'cekPendaftaran'])->name('pcare.cek-pendaftaran');
Route::get('/pcare-pendaftaran/cari-peserta', [\App\Http\Controllers\PcareController::class, 'cariPendaftaranByNoUrut'])->name('pcare.pendaftaran.cari_peserta');
Route::post('/pcare-pendaftaran/cari-peserta',[\App\Http\Controllers\PcareController::class, 'prosesCariPendaftaranByNoUrut']);
Route::get('/pcare-kunjungan/index',           [\App\Http\Controllers\PcareController::class, 'cekKunjungan'])->name('pcare.kunjungan.index');
Route::get('/pcare-kunjungan',                 [\App\Http\Controllers\PcareController::class, 'cekKunjungan']);
Route::get('/pcare/cek-kunjungan',             [\App\Http\Controllers\PcareController::class, 'cekKunjungan'])->name('pcare.cek-kunjungan');
Route::get('/pcare-kunjungan/view-pendaftaran',[\App\Http\Controllers\PcareController::class, 'viewKunjunganPendaftaran'])->name('pcare.kunjungan.view_pendaftaran');
Route::get('/pcare-pendaftaran/index-log',       [\App\Http\Controllers\PcareController::class, 'historiPendaftaran'])->name('pcare.pendaftaran.index_log');
Route::get('/pcare/histori-pendaftaran',         [\App\Http\Controllers\PcareController::class, 'historiPendaftaran'])->name('pcare.histori-pendaftaran');
Route::get('/pcare-pendaftaran/view-log',        [\App\Http\Controllers\PcareController::class, 'viewHistoriPendaftaranLog'])->name('pcare.pendaftaran.view_log');

// === i-Care ===
Route::get('/icare-user/index',              [\App\Http\Controllers\IcareController::class, 'akun'])->name('icare.user.index');
Route::get('/icare-user',                    [\App\Http\Controllers\IcareController::class, 'akun']);
Route::post('/icare-user/{id}/update',       [\App\Http\Controllers\IcareController::class, 'updateAkun'])->name('icare.user.update');
Route::get('/icare-user/{id}/edit',          [\App\Http\Controllers\IcareController::class, 'editAkun'])->name('icare.user.edit');
Route::get('/icare',                         [\App\Http\Controllers\IcareController::class, 'index'])->name('icare.index');
Route::get('/icare/akun',                    [\App\Http\Controllers\IcareController::class, 'akun'])->name('icare.akun');
Route::get('/icare-kunjungan/index',            [\App\Http\Controllers\IcareController::class, 'cekRiwayatKunjungan'])->name('icare.kunjungan.index');
Route::get('/icare-kunjungan',                  [\App\Http\Controllers\IcareController::class, 'cekRiwayatKunjungan']);
Route::get('/icare-kunjungan/api-pasien',       [\App\Http\Controllers\IcareController::class, 'apiPasienToday'])->name('icare.kunjungan.api_pasien');
Route::get('/icare/cek-riwayat-kunjungan',   [\App\Http\Controllers\IcareController::class, 'cekRiwayatKunjungan'])->name('icare.cek-riwayat-kunjungan');
Route::get('/icare-user/view',                [\App\Http\Controllers\IcareController::class, 'viewDoctorAccount'])->name('icare.user.view');
Route::post('/icare-user/update-password',     [\App\Http\Controllers\IcareController::class, 'storeDoctorPassword'])->name('icare.user.update_password');
Route::get('/icare/update-password',           [\App\Http\Controllers\IcareController::class, 'viewDoctorAccount'])->name('icare.update-password');
Route::post('/icare/update-password',          [\App\Http\Controllers\IcareController::class, 'storeDoctorPassword'])->name('icare.update-password.store');

// === Bridging Satu Sehat (KONHAKES 1:1) ===
Route::get('/satu-sehat/index',             [\App\Http\Controllers\BridgingConfigController::class, 'configSatuSehat'])->name('satusehat.index');
Route::get('/satu-sehat',                   [\App\Http\Controllers\BridgingConfigController::class, 'configSatuSehat']);
Route::post('/satu-sehat/update-key',       [\App\Http\Controllers\BridgingConfigController::class, 'updateSettingKey'])->name('satusehat.update_key');
Route::post('/satu-sehat/test',             [\App\Http\Controllers\BridgingConfigController::class, 'testSatuSehat'])->name('satusehat.test');
Route::get('/satu-sehat/test',              [\App\Http\Controllers\BridgingConfigController::class, 'testSatuSehat']);

Route::get('/bridging/satusehat',          [\App\Http\Controllers\BridgingConfigController::class, 'configSatuSehat']);
Route::post('/bridging/satusehat',         [\App\Http\Controllers\BridgingConfigController::class, 'storeSatuSehat']);
Route::post('/bridging/satusehat/test',    [\App\Http\Controllers\BridgingConfigController::class, 'testSatuSehat']);

Route::get('/satu-sehat/profil-klinik',      [\App\Http\Controllers\SatuSehatSettingController::class, 'faskes'])->name('satusehat.faskes');
Route::get('/bridging/satusehat/faskes',   [\App\Http\Controllers\SatuSehatSettingController::class, 'faskes']);
Route::post('/bridging/satusehat/faskes',  [\App\Http\Controllers\SatuSehatSettingController::class, 'storeFaskes']);
Route::get('/satusehat-nakes/index',         [\App\Http\Controllers\SatuSehatSettingController::class, 'nakes'])->name('satusehat.nakes');
Route::get('/satusehat-nakes',               [\App\Http\Controllers\SatuSehatSettingController::class, 'nakes']);
Route::post('/satusehat-nakes/sync/{id}',    [\App\Http\Controllers\SatuSehatSettingController::class, 'syncNakes'])->name('satusehat.nakes.sync');
Route::post('/satusehat-nakes/update-nama',  [\App\Http\Controllers\SatuSehatSettingController::class, 'updateNamaNakes'])->name('satusehat.nakes.update_nama');

Route::get('/bridging/satusehat/nakes',      [\App\Http\Controllers\SatuSehatSettingController::class, 'nakes']);
Route::post('/bridging/satusehat/nakes',     [\App\Http\Controllers\SatuSehatSettingController::class, 'storeNakes']);
Route::get('/satusehat-unit/index',            [\App\Http\Controllers\SatuSehatSettingController::class, 'unitIndex'])->name('satusehat.unit.index');
Route::get('/satusehat-unit',                  [\App\Http\Controllers\SatuSehatSettingController::class, 'unitIndex']);
Route::get('/satusehat-unit/create',           [\App\Http\Controllers\SatuSehatSettingController::class, 'unitCreate'])->name('satusehat.unit.create');
Route::post('/satusehat-unit/store',           [\App\Http\Controllers\SatuSehatSettingController::class, 'unitStore'])->name('satusehat.unit.store');
Route::get('/satusehat-unit/view',             [\App\Http\Controllers\SatuSehatSettingController::class, 'unitView'])->name('satusehat.unit.view');
Route::post('/satusehat-unit/update',          [\App\Http\Controllers\SatuSehatSettingController::class, 'unitUpdate'])->name('satusehat.unit.update');
Route::post('/satusehat-unit/sync',            [\App\Http\Controllers\SatuSehatSettingController::class, 'unitSync'])->name('satusehat.unit.sync');
Route::get('/bridging/satusehat/unit',         [\App\Http\Controllers\SatuSehatSettingController::class, 'unitIndex'])->name('satusehat.unit');
Route::post('/bridging/satusehat/unit',        [\App\Http\Controllers\SatuSehatSettingController::class, 'storeUnit']);
// === SATU SEHAT: Setting Lokasi / Ruang Rawat ===
Route::get('/satusehat-lokasi/index',          [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiIndex'])->name('satusehat.lokasi.index');
Route::get('/satusehat-lokasi',                [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiIndex']);
Route::get('/satusehat-lokasi/create',         [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiCreate'])->name('satusehat.lokasi.create');
Route::post('/satusehat-lokasi/store',         [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiStore'])->name('satusehat.lokasi.store');
Route::get('/satusehat-lokasi/view',           [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiView'])->name('satusehat.lokasi.view');
Route::post('/satusehat-lokasi/update',        [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiUpdate'])->name('satusehat.lokasi.update');
Route::post('/satusehat-lokasi/sync',          [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiSync'])->name('satusehat.lokasi.sync');
Route::get('/bridging/satusehat/ruangan',      [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiIndex']);
Route::post('/bridging/satusehat/ruangan',     [\App\Http\Controllers\SatuSehatSettingController::class, 'lokasiStore']);

// === SATU SEHAT: Data Pasien ===
Route::get('/satusehat-pasien/index',          [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'pasien'])->name('satusehat.pasien.index');
Route::get('/satusehat-pasien',                [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'pasien']);
Route::get('/satusehat-pasien/view',           [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'viewPasien'])->name('satusehat.pasien.view');
Route::post('/satusehat-pasien/sync',          [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'cariIhsPasien'])->name('satusehat.pasien.sync');
Route::post('/satusehat-pasien/delete-ihs',     [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'deleteIhsPasien'])->name('satusehat.pasien.deleteIhs');
Route::get('/bridging/satusehat/pasien',       [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'pasien'])->name('satusehat.pasien');
Route::post('/bridging/satusehat/pasien/sync', [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'cariIhsPasien']);

// === SATU SEHAT: Data Kunjungan ===
Route::get('/satusehat-kunjungan/index',          [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'kunjungan'])->name('satusehat.kunjungan.index');
Route::get('/satusehat-kunjungan',                [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'kunjungan']);
Route::get('/satusehat-kunjungan/view',           [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'viewKunjungan'])->name('satusehat.kunjungan.view');
Route::get('/satusehat-kunjungan/cetak-pdf',      [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'cetakPdf'])->name('satusehat.kunjungan.cetak');
Route::post('/satusehat-kunjungan/kirim',         [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'kirimKunjungan'])->name('satusehat.kunjungan.kirim');
Route::get('/bridging/satusehat/kunjungan',       [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'kunjungan']);
Route::post('/bridging/satusehat/kunjungan/kirim', [\App\Http\Controllers\BridgingSatuSehatDataController::class, 'kirimKunjungan']);

// === Laporan PMKP & Farmasi ===
Route::get('/laporan-farmasi/laporan-waktu-tunggu-obat', [\App\Http\Controllers\LaporanPmkpController::class, 'waktuTungguObat'])->name('laporan.farmasi.waktu_tunggu_obat');
Route::get('/laporan-farmasi/cetak-waktu-tunggu-obat-pdf', [\App\Http\Controllers\LaporanPmkpController::class, 'cetakWaktuTungguObatPdf'])->name('laporan.farmasi.waktu_tunggu_obat.pdf');
Route::get('/laporan-farmasi/cetak-waktu-tunggu-obat-excel', [\App\Http\Controllers\LaporanPmkpController::class, 'cetakWaktuTungguObatExcel'])->name('laporan.farmasi.waktu_tunggu_obat.excel');
Route::get('/laporan-pmkp/laporan-survey-kepuasan', [\App\Http\Controllers\LaporanPmkpController::class, 'surveyKepuasan'])->name('laporan.pmkp.survey_kepuasan');
Route::get('/laporan-pmkp/survey-kepuasan',         [\App\Http\Controllers\LaporanPmkpController::class, 'surveyKepuasan']);
Route::get('/laporan-pmkp/cetak-survey-kepuasan-pdf', [\App\Http\Controllers\LaporanPmkpController::class, 'cetakSurveyKepuasanPdf'])->name('laporan.pmkp.survey_kepuasan.pdf');
Route::get('/laporan-pmkp/waktu-tunggu-obat',      [\App\Http\Controllers\LaporanPmkpController::class, 'waktuTungguObat']);
Route::get('/laporan-pmkp/laporan-waktu-tunggu-layanan', [\App\Http\Controllers\LaporanPmkpController::class, 'waktuTungguPelayanan'])->name('laporan.pmkp.waktu_tunggu_layanan');
Route::get('/laporan-pmkp/waktu-tunggu-pelayanan',       [\App\Http\Controllers\LaporanPmkpController::class, 'waktuTungguPelayanan']);
Route::get('/laporan-pmkp/cetak-waktu-tunggu-layanan-pdf', [\App\Http\Controllers\LaporanPmkpController::class, 'cetakWaktuTungguPelayananPdf'])->name('laporan.pmkp.waktu_tunggu_layanan.pdf');
Route::get('/laporan-pmkp/cetak-waktu-tunggu-layanan-excel', [\App\Http\Controllers\LaporanPmkpController::class, 'cetakWaktuTungguPelayananExcel'])->name('laporan.pmkp.waktu_tunggu_layanan.excel');
Route::get('/laporan-pmkp/laporan-waktu-tunggu-obat-racik', [\App\Http\Controllers\LaporanPmkpController::class, 'waktuTungguObatRacik'])->name('laporan.pmkp.waktu_tunggu_racik');
Route::get('/laporan-pmkp/waktu-tunggu-racik',     [\App\Http\Controllers\LaporanPmkpController::class, 'waktuTungguObatRacik']);
Route::get('/laporan-pmkp/cetak-waktu-tunggu-racik-pdf', [\App\Http\Controllers\LaporanPmkpController::class, 'cetakWaktuTungguRacikPdf'])->name('laporan.pmkp.waktu_tunggu_racik.pdf');
Route::get('/laporan-pmkp/cetak-waktu-tunggu-racik-excel', [\App\Http\Controllers\LaporanPmkpController::class, 'cetakWaktuTungguRacikExcel'])->name('laporan.pmkp.waktu_tunggu_racik.excel');

// === Data Pembayaran Pasien & Kasir ===
Route::get('/pembayaran/index',                     [\App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran.index');
Route::get('/pembayaran',                           [\App\Http\Controllers\PembayaranController::class, 'index']);
Route::get('/pembayaran/cetak-pdf',                 [\App\Http\Controllers\PembayaranController::class, 'cetakPdf'])->name('pembayaran.pdf');
Route::get('/pembayaran/cetak-excel',               [\App\Http\Controllers\PembayaranController::class, 'cetakExcel'])->name('pembayaran.excel');
Route::delete('/pembayaran/{id}',                   [\App\Http\Controllers\PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
Route::get('/laporan/laporan-transaksi-kasir',       [\App\Http\Controllers\LaporanTransaksiKasirController::class, 'index'])->name('laporan.transaksi-kasir');
Route::get('/laporan/laporan-transaksi-kasir/pdf',   [\App\Http\Controllers\LaporanTransaksiKasirController::class, 'exportPdf'])->name('laporan.transaksi-kasir.pdf');
Route::get('/laporan/laporan-transaksi-kasir/excel', [\App\Http\Controllers\LaporanTransaksiKasirController::class, 'exportExcel'])->name('laporan.transaksi-kasir.excel');
// === Laporan Jasa Tindakan Medis & Back Office ===
Route::get('/laporan/laporan-jasa-tindakan',       [\App\Http\Controllers\LaporanJasaTindakanController::class, 'index'])->name('laporan.jasa-tindakan');
Route::get('/laporan/laporan-jasa-tindakan/pdf',   [\App\Http\Controllers\LaporanJasaTindakanController::class, 'exportPdf'])->name('laporan.jasa-tindakan.pdf');
Route::get('/laporan/laporan-jasa-tindakan/xls',   [\App\Http\Controllers\LaporanJasaTindakanController::class, 'exportXls'])->name('laporan.jasa-tindakan.xls');
Route::get('/laporan/laporan-jasa-tindakan/excel', [\App\Http\Controllers\LaporanJasaTindakanController::class, 'exportXls']);
Route::get('/backoffice/jasa-medis',               [\App\Http\Controllers\LaporanJasaTindakanController::class, 'index']);
// === Transaksi Klaim Pasien ===
Route::get('/klaim/index',          [\App\Http\Controllers\KlaimController::class, 'index'])->name('klaim.index');
Route::get('/klaim',                [\App\Http\Controllers\KlaimController::class, 'index']);
Route::post('/klaim',               [\App\Http\Controllers\KlaimController::class, 'store'])->name('klaim.store');
Route::put('/klaim/{id}',           [\App\Http\Controllers\KlaimController::class, 'update'])->name('klaim.update');
Route::delete('/klaim/{id}',        [\App\Http\Controllers\KlaimController::class, 'destroy'])->name('klaim.destroy');
Route::get('/klaim/export-pdf',     [\App\Http\Controllers\KlaimController::class, 'exportPdf'])->name('klaim.export-pdf');
Route::get('/klaim/export-excel',   [\App\Http\Controllers\KlaimController::class, 'exportExcel'])->name('klaim.export-excel');
Route::get('/klaim/pdf',            [\App\Http\Controllers\KlaimController::class, 'exportPdf'])->name('klaim.exportPdf');
Route::get('/klaim/excel',          [\App\Http\Controllers\KlaimController::class, 'exportExcel'])->name('klaim.exportExcel');
Route::get('/backoffice/klaim-rajal', [\App\Http\Controllers\KlaimController::class, 'index']);
Route::get('/backoffice/pendapatan',                 [\App\Http\Controllers\BackOfficeController::class, 'pendapatanPasien']);
Route::get('/backoffice/laporan-kasir',              [\App\Http\Controllers\LaporanTransaksiKasirController::class, 'index']);
Route::get('/backoffice/transaksi-kasir',            [\App\Http\Controllers\BackOfficeController::class, 'transaksiKasir']);

// === Transaksi Penjualan Apotek (KONHAKES 1:1) ===
Route::get('/transaksipenjualan/index',         [\App\Http\Controllers\PenjualanApotekController::class, 'index'])->name('transaksipenjualan.index');
Route::get('/transaksipenjualan',               [\App\Http\Controllers\PenjualanApotekController::class, 'index']);
Route::get('/transaksipenjualan/create',        [\App\Http\Controllers\PenjualanApotekController::class, 'create'])->name('transaksipenjualan.create');
Route::post('/transaksipenjualan/store',        [\App\Http\Controllers\PenjualanApotekController::class, 'store'])->name('transaksipenjualan.store');
Route::post('/transaksipenjualan',              [\App\Http\Controllers\PenjualanApotekController::class, 'store']);
Route::get('/transaksipenjualan/hitung-ulang',  [\App\Http\Controllers\PenjualanApotekController::class, 'hitungUlang'])->name('transaksipenjualan.hitung_ulang');
Route::get('/transaksipenjualan/cetak-laporan', [\App\Http\Controllers\PenjualanApotekController::class, 'cetakLaporan'])->name('transaksipenjualan.cetak_laporan');
Route::get('/transaksipenjualan/{id}/cetak-nota', [\App\Http\Controllers\PenjualanApotekController::class, 'cetakNota'])->name('transaksipenjualan.cetak_nota');
Route::get('/transaksipenjualan/search-obat',   [\App\Http\Controllers\PenjualanApotekController::class, 'searchObat'])->name('transaksipenjualan.search_obat');
Route::get('/transaksipenjualan/daftar-harga',  [\App\Http\Controllers\PenjualanApotekController::class, 'daftarHargaObat'])->name('transaksipenjualan.daftar_harga');
Route::post('/transaksipenjualan/quick-pasien', [\App\Http\Controllers\PenjualanApotekController::class, 'quickPasien'])->name('transaksipenjualan.quick_pasien');
Route::get('/transaksipenjualan/{id}',          [\App\Http\Controllers\PenjualanApotekController::class, 'show'])->name('transaksipenjualan.show')->whereNumber('id');
Route::delete('/transaksipenjualan/{id}',       [\App\Http\Controllers\PenjualanApotekController::class, 'destroy'])->name('transaksipenjualan.destroy')->whereNumber('id');

// Aliases for /farmasi/penjualan
Route::get('/farmasi/penjualan',                [\App\Http\Controllers\PenjualanApotekController::class, 'index']);
Route::get('/farmasi/penjualan/create',         [\App\Http\Controllers\PenjualanApotekController::class, 'create']);
Route::post('/farmasi/penjualan',               [\App\Http\Controllers\PenjualanApotekController::class, 'store']);
Route::get('/farmasi/penjualan/{id}',           [\App\Http\Controllers\PenjualanApotekController::class, 'show'])->whereNumber('id');

