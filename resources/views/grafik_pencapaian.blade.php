@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'GRAFIK & PENCAPAIAN - ' . ($namaKlinik ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="grafik-container">
    <!-- Header Title -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem; letter-spacing: 0.3px;">
            GRAFIK & PENCAPAIAN - {{ $namaKlinik ?? 'KLINIK TELAGASARI 24 JAM' }}
        </h5>
    </div>

    <!-- Row 1: 2 Main Charts -->
    <div class="row g-3 mb-3">
        <!-- Chart 1: Jumlah Pasien Terdaftar -->
        <div class="col-12 col-lg-8">
            <div class="card border rounded-2 shadow-none h-100 bg-white">
                <div class="card-body p-2">
                    <div id="chartPasienTerdaftar" style="width: 100%; height: 360px;"></div>
                </div>
            </div>
        </div>

        <!-- Chart 2: Jumlah Kunjungan Pasien Per Klinik -->
        <div class="col-12 col-lg-4">
            <div class="card border rounded-2 shadow-none h-100 bg-white">
                <div class="card-body p-2">
                    <div id="chartKunjunganKlinik" style="width: 100%; height: 360px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: PENCAPAIAN KAPITASI BERBASIS KINERJA (KBK) -->
    <div class="kbk-divider mb-3" style="border-top: 2px solid #00c0ef; padding-top: 6px;">
        <h6 class="fw-bold mb-0" style="color: #0088cc; font-size: 13.5px; letter-spacing: 0.3px;">
            PENCAPAIAN KAPITASI BERBASIS KINERJA (KBK)
        </h6>
    </div>

    <!-- Row 2: KBK Charts (Angka Kontak & RRNS) -->
    <div class="row g-3 mb-3">
        <!-- Left: Rasio Angka Kontak Pasien BPJS -->
        <div class="col-12 col-lg-6">
            <div class="card border rounded-2 shadow-none bg-white kbk-card">
                <div class="card-body p-2">
                    <div id="chartAngkaKontak" style="width: 100%; height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- Right: Rasio Rujukan Non Spesialistik BPJS -->
        <div class="col-12 col-lg-6">
            <div class="card border rounded-2 shadow-none bg-white kbk-card">
                <div class="card-body p-2">
                    <div id="chartRRNS" style="width: 100%; height: 320px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Indikator DM & Hipertensi (Tables) -->
    <div class="row g-3 mb-4">
        <!-- Table 1: Rasio Pasien Diabetes Melitus -->
        <div class="col-12 col-lg-6">
            <div class="card border rounded-2 shadow-none bg-white kbk-card">
                <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-globe text-dark me-2" style="font-size: 13px;"></i>
                        <span class="fw-bold text-dark" style="font-size: 13px;">Rasio Pasien Diabetes Melitus</span>
                    </div>
                    <button class="btn btn-sm text-white py-0 px-2 rounded-1" data-bs-toggle="collapse" data-bs-target="#tableDMCollapse" style="background-color: #f39c12; font-size: 11px;">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="collapse show" id="tableDMCollapse">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped mb-0 text-center" style="font-size: 12px;">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="font-weight: 600; color: #444; padding: 8px;">Periode</th>
                                        <th style="font-weight: 600; color: #444; padding: 8px;">Pasien DM Terkendali</th>
                                        <th style="font-weight: 600; color: #444; padding: 8px;">Total Peserta DM</th>
                                        <th style="font-weight: 600; color: #444; padding: 8px;">RPPT-DM (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dataDM as $dm)
                                    <tr>
                                        <td class="text-start ps-3">{{ $dm['periode'] }}</td>
                                        <td>{{ $dm['terkendali'] }}</td>
                                        <td>{{ $dm['total'] }}</td>
                                        <td class="fw-semibold text-success">{{ $dm['rasio'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-muted py-3">Belum ada data tersedia</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table 2: Rasio Pasien Hipertensi -->
        <div class="col-12 col-lg-6">
            <div class="card border rounded-2 shadow-none bg-white kbk-card">
                <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-heartbeat text-dark me-2" style="font-size: 13px;"></i>
                        <span class="fw-bold text-dark" style="font-size: 13px;">Rasio Pasien Hipertensi</span>
                    </div>
                    <button class="btn btn-sm text-white py-0 px-2 rounded-1" data-bs-toggle="collapse" data-bs-target="#tableHTCollapse" style="background-color: #f39c12; font-size: 11px;">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="collapse show" id="tableHTCollapse">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped mb-0 text-center" style="font-size: 12px;">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="font-weight: 600; color: #444; padding: 8px;">Periode</th>
                                        <th style="font-weight: 600; color: #444; padding: 8px;">Pasien HT Terkendali</th>
                                        <th style="font-weight: 600; color: #444; padding: 8px;">Total Peserta HT</th>
                                        <th style="font-weight: 600; color: #444; padding: 8px;">RPPT-HT (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dataHT as $ht)
                                    <tr>
                                        <td class="text-start ps-3">{{ $ht['periode'] }}</td>
                                        <td>{{ $ht['terkendali'] }}</td>
                                        <td>{{ $ht['total'] }}</td>
                                        <td class="fw-semibold text-success">{{ $ht['rasio'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-muted py-3">Belum ada data tersedia</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .grafik-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .kbk-card {
        border-top: 3px solid #f39c12 !important;
        border-color: #e2e8f0;
    }
    .highcharts-credits {
        font-size: 9px !important;
        color: #999999 !important;
    }
</style>

<script>
(function() {
    function initHighcharts() {
        if (typeof Highcharts === 'undefined') {
            setTimeout(initHighcharts, 100);
            return;
        }

        // Global Highcharts theme font & vibrant colors (No Black)
        Highcharts.setOptions({
            colors: ['#3c8dbc', '#f39c12', '#00a65a', '#dd4b39', '#9b59b6', '#00c0ef', '#e84393', '#f1c40f', '#1abc9c', '#e67e22'],
            chart: {
                backgroundColor: '#ffffff',
                style: {
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif'
                }
            }
        });

        // 1. Chart Jumlah Pasien Terdaftar
        Highcharts.chart('chartPasienTerdaftar', {
            chart: {
                type: 'column',
                backgroundColor: '#ffffff'
            },
            title: {
                text: 'Jumlah Pasien Terdaftar',
                style: { fontSize: '14px', fontWeight: 'bold', color: '#2c3e50' }
            },
            subtitle: {
                text: 'Tahun {{ $tahun }}',
                style: { fontSize: '11px', color: '#7f8c8d' }
            },
            xAxis: {
                categories: {!! json_encode($bulanList) !!},
                crosshair: true,
                labels: {
                    style: { fontSize: '10px' }
                }
            },
            yAxis: {
                min: 0,
                max: 400,
                tickInterval: 100,
                title: {
                    text: 'Jumlah',
                    style: { fontSize: '11px' }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y} Pasien</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.15,
                    borderWidth: 0,
                    borderRadius: 2
                }
            },
            series: [{
                name: 'Pria',
                color: '#3c8dbc',
                data: {!! json_encode($dataPria) !!}
            }, {
                name: 'Wanita',
                color: '#f39c12',
                data: {!! json_encode($dataWanita) !!}
            }],
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal',
                itemStyle: { fontSize: '11px', fontWeight: 'normal', color: '#555' }
            },
            credits: {
                text: 'highcharts.com',
                href: 'https://www.highcharts.com'
            }
        });

        // 2. Chart Jumlah Kunjungan Pasien Per Klinik (Pie)
        Highcharts.chart('chartKunjunganKlinik', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Jumlah Kunjungan Pasien Per Klinik',
                style: { fontSize: '14px', fontWeight: 'bold', color: '#2c3e50' }
            },
            subtitle: {
                text: 'Tahun {{ $tahun }}',
                style: { fontSize: '11px', color: '#7f8c8d' }
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>',
                        distance: -30,
                        filter: {
                            property: 'percentage',
                            operator: '>',
                            value: 15
                        },
                        style: {
                            fontSize: '11px',
                            fontWeight: 'bold',
                            color: '#ffffff',
                            textOutline: 'none'
                        }
                    },
                    showInLegend: true
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                maxHeight: 100,
                itemStyle: {
                    fontSize: '9.5px',
                    fontWeight: 'normal',
                    color: '#555'
                },
                navigation: {
                    activeColor: '#00a65a',
                    animation: true
                }
            },
            series: [{
                name: 'Kunjungan',
                colorByPoint: true,
                data: {!! json_encode($klinikPieData) !!}
            }],
            credits: {
                text: 'highcharts.com',
                href: 'https://www.highcharts.com'
            }
        });

        // 3. Chart Rasio Angka Kontak Pasien BPJS
        Highcharts.chart('chartAngkaKontak', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Rasio Angka Kontak Pasien BPJS',
                style: { fontSize: '14px', fontWeight: 'bold', color: '#2c3e50' }
            },
            subtitle: {
                text: 'Tahun {{ $tahun }}',
                style: { fontSize: '11px', color: '#7f8c8d' }
            },
            xAxis: {
                categories: {!! json_encode($bulanList) !!},
                labels: { style: { fontSize: '10px' } }
            },
            yAxis: {
                min: 0,
                max: 250,
                tickInterval: 50,
                title: {
                    text: 'Jumlah (Permil)',
                    style: { fontSize: '11px' }
                }
            },
            tooltip: {
                valueSuffix: ' Permil'
            },
            plotOptions: {
                spline: {
                    lineWidth: 2,
                    states: {
                        hover: { lineWidth: 3 }
                    },
                    marker: {
                        enabled: true,
                        radius: 3
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return this.y > 50 ? this.y : '';
                        },
                        style: {
                            fontSize: '11px',
                            fontWeight: 'bold',
                            color: '#333'
                        }
                    }
                }
            },
            series: [{
                name: 'Angka Kontak',
                color: '#00a65a',
                data: {!! json_encode($angkaKontakData) !!}
            }],
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal',
                itemStyle: { fontSize: '11px', fontWeight: 'normal', color: '#555' }
            },
            credits: {
                text: 'highcharts.com',
                href: 'https://www.highcharts.com'
            }
        });

        // 4. Chart Rasio Rujukan Non Spesialistik BPJS (RRNS)
        Highcharts.chart('chartRRNS', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Rasio Rujukan Non Spesialistik BPJS',
                style: { fontSize: '14px', fontWeight: 'bold', color: '#2c3e50' }
            },
            subtitle: {
                text: 'Tahun {{ $tahun }}',
                style: { fontSize: '11px', color: '#7f8c8d' }
            },
            xAxis: {
                categories: {!! json_encode($bulanList) !!},
                labels: { style: { fontSize: '10px' } }
            },
            yAxis: {
                min: 0,
                max: 2,
                title: {
                    text: 'Jumlah (%)',
                    style: { fontSize: '11px' }
                }
            },
            tooltip: {
                valueSuffix: '%'
            },
            plotOptions: {
                line: {
                    lineWidth: 2,
                    marker: {
                        enabled: true,
                        radius: 3
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontWeight: 'normal',
                            color: '#333'
                        }
                    }
                }
            },
            series: [{
                name: 'RRNS',
                color: '#dd4b39',
                data: {!! json_encode($rrnsData) !!}
            }],
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal',
                itemStyle: { fontSize: '11px', fontWeight: 'normal', color: '#555' }
            },
            credits: {
                text: 'highcharts.com',
                href: 'https://www.highcharts.com'
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHighcharts);
    } else {
        initHighcharts();
    }
})();
</script>
@endsection
