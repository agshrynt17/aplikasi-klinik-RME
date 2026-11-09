@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title -->
    <h4 style="font-size: 21px; font-weight: 600; color: #333; margin-bottom: 16px; letter-spacing: -0.2px;">
        Dashboard Waktu Tunggu Pelayanan Antrian FKTL
    </h4>

    <!-- Alert Banner -->
    @if(!empty($statusError))
    <div class="alert alert-dismissible fade show text-white" role="alert" style="background-color: #dd4b39; border-color: #d73925; border-radius: 3px; padding: 11px 18px; margin-bottom: 18px; font-size: 13.5px; font-weight: 500;">
        <i class="fas fa-times-circle me-1" style="font-size: 15px;"></i> {{ $statusError }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="font-size: 10px;"></button>
    </div>
    @endif

    <!-- Filter Card -->
    <div class="card mb-3" style="border: 1px solid #d2d6de; border-top: 3px solid #3c8dbc; border-radius: 3px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); background: #fff;">
        <div class="card-body p-2 px-3">
            <form method="GET" action="{{ url()->current() }}" class="d-flex flex-wrap align-items-center gap-2">
                <div>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" style="height: 34px; font-size: 13px; border-radius: 3px; width: 165px; border-color: #d2d6de; color: #495057;">
                </div>
                <div>
                    <select name="jenis_waktu" class="form-select" style="height: 34px; font-size: 13px; border-radius: 3px; width: 145px; border-color: #d2d6de; color: #495057;">
                        <option value="Harian" {{ $jenis_waktu == 'Harian' ? 'selected' : '' }}>Harian</option>
                        <option value="Bulanan" {{ $jenis_waktu == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div>
                    <select name="faskes_tipe" class="form-select" style="height: 34px; font-size: 13px; border-radius: 3px; width: 135px; border-color: #d2d6de; color: #495057;">
                        <option value="RS" {{ $faskes_tipe == 'RS' ? 'selected' : '' }}>RS</option>
                        <option value="Klinik" {{ $faskes_tipe == 'Klinik' ? 'selected' : '' }}>Klinik</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" style="background-color: #337ab7; border-color: #2e6da4; height: 34px; padding: 0 22px; font-size: 13px; font-weight: 500; border-radius: 3px;">
                        Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6 Metric Cards in 1 Row -->
    <div class="row g-2 mb-3">
        <!-- 1. Tunggu Admisi -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card text-white" style="background-color: #f39c12; border: none; border-radius: 3px; position: relative; overflow: hidden; min-height: 88px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                <div class="card-body p-2 px-3" style="position: relative; z-index: 2;">
                    <div style="font-size: 38px; font-weight: 700; line-height: 1; margin-bottom: 4px;">{{ $tunggu_admisi ?? 0 }}</div>
                    <div style="font-size: 13px; font-weight: 500; white-space: nowrap; opacity: 0.95;">Tunggu Admisi (menit)</div>
                </div>
                <i class="far fa-clock" style="position: absolute; right: 10px; top: 12px; font-size: 62px; color: rgba(0, 0, 0, 0.15); z-index: 1; pointer-events: none;"></i>
            </div>
        </div>

        <!-- 2. Layan Admisi -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card text-white" style="background-color: #00a65a; border: none; border-radius: 3px; position: relative; overflow: hidden; min-height: 88px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                <div class="card-body p-2 px-3" style="position: relative; z-index: 2;">
                    <div style="font-size: 38px; font-weight: 700; line-height: 1; margin-bottom: 4px;">{{ $layan_admisi ?? 0 }}</div>
                    <div style="font-size: 13px; font-weight: 500; white-space: nowrap; opacity: 0.95;">Layan Admisi (menit)</div>
                </div>
                <i class="fas fa-check-circle" style="position: absolute; right: 10px; top: 12px; font-size: 62px; color: rgba(0, 0, 0, 0.15); z-index: 1; pointer-events: none;"></i>
            </div>
        </div>

        <!-- 3. Tunggu Poli -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card text-white" style="background-color: #dd4b39; border: none; border-radius: 3px; position: relative; overflow: hidden; min-height: 88px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                <div class="card-body p-2 px-3" style="position: relative; z-index: 2;">
                    <div style="font-size: 38px; font-weight: 700; line-height: 1; margin-bottom: 4px;">{{ $tunggu_poli ?? 0 }}</div>
                    <div style="font-size: 13px; font-weight: 500; white-space: nowrap; opacity: 0.95;">Tunggu Poli (menit)</div>
                </div>
                <i class="fas fa-stethoscope" style="position: absolute; right: 10px; top: 12px; font-size: 62px; color: rgba(0, 0, 0, 0.15); z-index: 1; pointer-events: none;"></i>
            </div>
        </div>

        <!-- 4. Layan Poli -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card text-white" style="background-color: #00c0ef; border: none; border-radius: 3px; position: relative; overflow: hidden; min-height: 88px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                <div class="card-body p-2 px-3" style="position: relative; z-index: 2;">
                    <div style="font-size: 38px; font-weight: 700; line-height: 1; margin-bottom: 4px;">{{ $layan_poli ?? 0 }}</div>
                    <div style="font-size: 13px; font-weight: 500; white-space: nowrap; opacity: 0.95;">Layan Poli (menit)</div>
                </div>
                <i class="fas fa-user-md" style="position: absolute; right: 10px; top: 12px; font-size: 62px; color: rgba(0, 0, 0, 0.15); z-index: 1; pointer-events: none;"></i>
            </div>
        </div>

        <!-- 5. Tunggu Farmasi -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card text-white" style="background-color: #f39c12; border: none; border-radius: 3px; position: relative; overflow: hidden; min-height: 88px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                <div class="card-body p-2 px-3" style="position: relative; z-index: 2;">
                    <div style="font-size: 38px; font-weight: 700; line-height: 1; margin-bottom: 4px;">{{ $tunggu_farmasi ?? 0 }}</div>
                    <div style="font-size: 13px; font-weight: 500; white-space: nowrap; opacity: 0.95;">Tunggu Farmasi (menit)</div>
                </div>
                <i class="fas fa-hourglass-half" style="position: absolute; right: 10px; top: 12px; font-size: 62px; color: rgba(0, 0, 0, 0.15); z-index: 1; pointer-events: none;"></i>
            </div>
        </div>

        <!-- 6. Layan Farmasi -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card text-white" style="background-color: #00a65a; border: none; border-radius: 3px; position: relative; overflow: hidden; min-height: 88px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                <div class="card-body p-2 px-3" style="position: relative; z-index: 2;">
                    <div style="font-size: 38px; font-weight: 700; line-height: 1; margin-bottom: 4px;">{{ $layan_farmasi ?? 0 }}</div>
                    <div style="font-size: 13px; font-weight: 500; white-space: nowrap; opacity: 0.95;">Layan Farmasi (menit)</div>
                </div>
                <i class="fas fa-briefcase-medical" style="position: absolute; right: 10px; top: 12px; font-size: 62px; color: rgba(0, 0, 0, 0.15); z-index: 1; pointer-events: none;"></i>
            </div>
        </div>
    </div>

    <!-- 2 Charts Side by Side -->
    <div class="row g-3">
        <!-- Chart 1: Rata-rata per Poli -->
        <div class="col-md-6 col-12">
            <div class="card h-100" style="border: 1px solid #d2d6de; border-radius: 3px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); background: #fff;">
                <div class="card-body p-3">
                    <div style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">Rata-rata per Poli</div>
                    <div class="text-center" style="font-size: 16px; font-weight: 500; color: #444; margin-top: 5px; margin-bottom: 25px;">
                        Rata-rata Waktu Tunggu per Poli
                    </div>
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="chartRataPoli"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 2: Trend Waktu Tunggu -->
        <div class="col-md-6 col-12">
            <div class="card h-100" style="border: 1px solid #d2d6de; border-radius: 3px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); background: #fff;">
                <div class="card-body p-3">
                    <div style="font-size: 13px; font-weight: 500; color: #333; margin-bottom: 6px;">Trend Waktu Tunggu</div>
                    <div class="text-center" style="margin-top: 5px; margin-bottom: 20px;">
                        <div style="font-size: 16px; font-weight: 500; color: #444;">Trend Waktu Tunggu</div>
                        <div style="font-size: 12px; color: #777; margin-top: 2px;">Per Poli</div>
                    </div>
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="chartTrend"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const poliLabels = {!! json_encode($poli_labels ?? []) !!};
    const poliData = {!! json_encode($poli_data ?? []) !!};

    const trendLabels = {!! json_encode($trend_labels ?? []) !!};
    const trendData = {!! json_encode($trend_data ?? []) !!};

    // Chart 1: Rata-rata per Poli
    const ctxRata = document.getElementById('chartRataPoli').getContext('2d');
    new Chart(ctxRata, {
        type: 'bar',
        data: {
            labels: poliLabels.length > 0 ? poliLabels : [''],
            datasets: [{
                label: 'Rata-rata Waktu Tunggu (Menit)',
                data: poliData.length > 0 ? poliData : [0],
                backgroundColor: '#3c8dbc',
                borderColor: '#367fa9',
                borderWidth: 1,
                borderRadius: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: poliData.length > 0
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: poliData.length > 0 ? Math.max(...poliData) + 10 : 1,
                    title: {
                        display: true,
                        text: 'Menit',
                        color: '#666',
                        font: { size: 12 }
                    },
                    grid: {
                        color: '#f0f0f0'
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Chart 2: Trend Waktu Tunggu
    const ctxTrend = document.getElementById('chartTrend').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: trendLabels.length > 0 ? trendLabels : [''],
            datasets: [{
                label: 'Trend Waktu Tunggu (Menit)',
                data: trendData.length > 0 ? trendData : [0],
                borderColor: '#3c8dbc',
                backgroundColor: 'rgba(60, 141, 188, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: trendData.length > 0 ? 3 : 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: trendData.length > 0
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: trendData.length > 0 ? Math.max(...trendData) + 10 : 1,
                    title: {
                        display: true,
                        text: 'Menit',
                        color: '#666',
                        font: { size: 12 }
                    },
                    grid: {
                        color: '#f0f0f0'
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endsection
