@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <h3 class="mb-3" style="color: #333; font-weight: normal;">Backup Data System</h3>
    
    <div class="alert alert-info d-flex align-items-center" role="alert" style="background-color: #00c0ef; color: white; border: none;">
        <i class="fas fa-info-circle me-3 fs-5"></i>
        <div>
            Proses backup akan membutuhkan waktu tergantung jumlah data yang dibackup. Pastikan anda melakukan backup data ketika aplikasi sedang tidak digunakan oleh user lain agar tidak terkendala saat melakukan backup data!
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card card-custom mb-4" style="border-top: none;">
        <div class="card-body">
            <div class="row mb-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label" style="font-size: 14px; font-weight: 500;">Tanggal Transaksi</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size: 14px; font-weight: 500;">sampai dengan</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-light border w-100"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3 mt-4" style="font-weight: 600; color: #444;">Bakcup Rawat Jalan</h6>
            
            <button class="btn w-100 text-white mb-4 fw-bold" style="background-color: #00c0ef;"><i class="fas fa-download me-2"></i> Backup Semua</button>
            
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6 pe-md-4">
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2 mb-2">
                        <div>
                            <span style="font-weight: 600; font-size: 14px;">1. Data Pasien</span><br>
                            <small class="text-muted" style="font-size: 12px;">Tidak berlaku tanggal transaksi! Semua data akan dibackup</small>
                        </div>
                        <button class="btn btn-sm text-white mt-1" style="background-color: #00c0ef; padding: 2px 10px; font-size: 12px;"><i class="fas fa-download"></i> Backup</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2 mb-2">
                        <div>
                            <span style="font-weight: 600; font-size: 14px;">2. Registrasi</span><br>
                            <small class="text-muted" style="font-size: 12px;">Data yang dibackup sesuai tanggal transaksi</small>
                        </div>
                        <button class="btn btn-sm text-white mt-1" style="background-color: #00c0ef; padding: 2px 10px; font-size: 12px;"><i class="fas fa-download"></i> Backup</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2 mb-2">
                        <div>
                            <span style="font-weight: 600; font-size: 14px;">3. Asesmen Awal</span><br>
                            <small class="text-muted" style="font-size: 12px;">Data yang dibackup sesuai tanggal transaksi</small>
                        </div>
                        <button class="btn btn-sm text-white mt-1" style="background-color: #00c0ef; padding: 2px 10px; font-size: 12px;"><i class="fas fa-download"></i> Backup</button>
                    </div>
                </div>
                
                <!-- Kolom Kanan -->
                <div class="col-md-6 ps-md-4">
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2 mb-2">
                        <div>
                            <span style="font-weight: 600; font-size: 14px;">13. Monitoring Tindakan/Operasi</span><br>
                            <small class="text-muted" style="font-size: 12px;">Data yang dibackup sesuai tanggal transaksi</small>
                        </div>
                        <button class="btn btn-sm text-white mt-1" style="background-color: #00c0ef; padding: 2px 10px; font-size: 12px;"><i class="fas fa-download"></i> Backup</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2 mb-2">
                        <div>
                            <span style="font-weight: 600; font-size: 14px;">14. Asesmen ANC</span><br>
                            <small class="text-muted" style="font-size: 12px;">Data yang dibackup sesuai tanggal transaksi</small>
                        </div>
                        <button class="btn btn-sm text-white mt-1" style="background-color: #00c0ef; padding: 2px 10px; font-size: 12px;"><i class="fas fa-download"></i> Backup</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2 mb-2">
                        <div>
                            <span style="font-weight: 600; font-size: 14px;">15. Asesmen PNC</span><br>
                            <small class="text-muted" style="font-size: 12px;">Data yang dibackup sesuai tanggal transaksi</small>
                        </div>
                        <button class="btn btn-sm text-white mt-1" style="background-color: #00c0ef; padding: 2px 10px; font-size: 12px;"><i class="fas fa-download"></i> Backup</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2 mb-2">
                        <div>
                            <span style="font-weight: 600; font-size: 14px;">16. Asesmen Inc</span><br>
                            <small class="text-muted" style="font-size: 12px;">Data yang dibackup sesuai tanggal transaksi</small>
                        </div>
                        <button class="btn btn-sm text-white mt-1" style="background-color: #00c0ef; padding: 2px 10px; font-size: 12px;"><i class="fas fa-download"></i> Backup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
