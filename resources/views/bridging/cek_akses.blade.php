@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Setting Bridging Antrol BPJS')

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

    /* Container Box with Blue Top Border matching KONHAKES */
    .box-setting-container {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-top: 3px solid #00c0ef;
        border-radius: 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        padding: 16px 20px 24px 20px;
        margin-bottom: 25px;
    }

    .table-info-header {
        font-size: 12.5px;
        color: #555;
        margin-bottom: 8px;
    }

    .table-setting {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 18px;
    }
    .table-setting th {
        background-color: #fbfbfb;
        color: #337ab7;
        font-weight: 700;
        border: 1px solid #e7e7e7;
        padding: 8px 12px;
        vertical-align: middle;
    }
    .table-setting th.col-no {
        color: #333;
        width: 45px;
        text-align: center;
    }
    .table-setting th.col-nama {
        width: 280px;
    }
    .table-setting th a.sort-link {
        color: #337ab7;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .table-setting td {
        border: 1px solid #f0f0f0;
        padding: 8px 12px;
        vertical-align: middle;
        color: #333333;
    }
    .table-setting tr:nth-child(even) td {
        background-color: #fdfdfd;
    }
    .table-setting tr:hover td {
        background-color: #f9fbfd;
    }

    .cid-hint {
        font-size: 11px;
        color: #777777;
        margin-top: 2px;
    }

    /* Cyan Full-Width Status Banner */
    .banner-status-antrol {
        background-color: #00c0ef;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        padding: 8px 15px;
        border-radius: 3px;
        margin-bottom: 12px;
        letter-spacing: 0.3px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    /* Prompt Text */
    .prompt-text {
        font-size: 12.5px;
        color: #333333;
        text-align: center;
        margin: 10px 0 12px 0;
    }

    /* Orange Testing Button */
    .btn-test-antrol {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .btn-test-antrol:hover, .btn-test-antrol:focus {
        background-color: #e08e0b;
        border-color: #d58512;
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .btn-test-antrol:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Test Result Box */
    .test-result-box {
        display: none;
        margin-top: 20px;
        border-top: 1px dashed #dcdcdc;
        padding-top: 15px;
    }
</style>

<div class="container-fluid p-3">
    <!-- Page Header & Breadcrumb -->
    <div class="page-header-box">
        <h1 class="page-title-text">Setting Bridging Antrol BPJS</h1>
        <div class="breadcrumb-custom">
            <a href="/admin">Beranda</a> &gt; <span>Setting Bridging Antrol BPJS</span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="box-setting-container">
        <!-- Text Counter -->
        <div class="table-info-header">
            Menampilkan <strong>1-{{ count($settings) }}</strong> dari <strong>{{ count($settings) }}</strong> item.
        </div>

        <!-- Table Settings -->
        <table class="table-setting">
            <thead>
                <tr>
                    <th class="col-no">#</th>
                    <th class="col-nama">
                        <a href="javascript:void(0)" class="sort-link">
                            Nama Setting <i class="fas fa-sort text-muted ms-1"></i>
                        </a>
                    </th>
                    <th>Nilai Setting</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $row)
                <tr>
                    <td class="text-center">{{ $row['no'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>
                        {{ $row['nilai'] }}
                        @if(!empty($row['desc']))
                            <div class="cid-hint">{{ $row['desc'] }}</div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Cyan Banner: Status Bridging Antrol: Aktif -->
        <div class="banner-status-antrol" id="bannerStatusAntrol">
            Status Bridging Antrol: {{ $statusAntrol ?? 'Aktif' }}
        </div>

        <!-- Prompt Text -->
        <div class="prompt-text">
            Silakan klik tombol dibawah ini untuk menguji status Akses Antrol BPJS!
        </div>

        <!-- Action Button -->
        <div class="text-center">
            <button type="button" class="btn-test-antrol" id="btnTestingAkses">
                <i class="fas fa-sync-alt" id="iconSync"></i> Testing Akses Antrol
            </button>
        </div>

        <!-- Result Box (Hidden until clicked) -->
        <div class="test-result-box" id="resultContainer">
            <div class="alert alert-success d-flex align-items-center mb-2" style="font-size: 12.5px; border-left: 4px solid #00a65a;">
                <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                <div class="flex-grow-1">
                    <strong>Koneksi Berhasil!</strong> Server Bridging Antrol BPJS Terhubung (HTTP 200 OK).
                </div>
                <div class="text-end text-muted small ms-3" id="resLatencyTime">
                    Latency: 180 ms | <span id="resTime"></span>
                </div>
            </div>
            <pre class="bg-light p-2 border rounded text-dark small mb-0" id="jsonResult" style="font-family: Consolas, monospace; font-size: 11px; max-height: 140px; overflow-y: auto;"></pre>
        </div>

    </div>
</div>

<!-- SweetAlert2 (if available in layout or fallback) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('btnTestingAkses');
        const icon = document.getElementById('iconSync');
        const resultContainer = document.getElementById('resultContainer');
        const jsonResult = document.getElementById('jsonResult');
        const resLatencyTime = document.getElementById('resLatencyTime');
        const resTime = document.getElementById('resTime');

        if (btn) {
            btn.addEventListener('click', function () {
                // Set loading state
                btn.disabled = true;
                icon.classList.add('fa-spin');
                btn.innerHTML = '<i class="fas fa-sync-alt fa-spin me-1"></i> Menguji Akses...';

                fetch('/bridging-cek-akses/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Testing Akses Antrol';

                    if (data.success) {
                        if (resultContainer) {
                            resultContainer.style.display = 'block';
                            if (jsonResult) jsonResult.textContent = JSON.stringify(data, null, 2);
                            if (resLatencyTime) resLatencyTime.textContent = `Latency: ${data.latency} | ${data.timestamp}`;
                        }

                        // SweetAlert notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Akses Antrol: Aktif',
                            html: `
                                <div style="text-align: left; font-size: 13px; line-height: 1.6;">
                                    <div><strong>Status:</strong> <span class="badge bg-success">Terhubung (200 OK)</span></div>
                                    <div><strong>Endpoint:</strong> <code>${data.endpoint}</code></div>
                                    <div><strong>Latency:</strong> ${data.latency}</div>
                                    <div><strong>Waktu Uji:</strong> ${data.timestamp}</div>
                                </div>
                            `,
                            confirmButtonColor: '#00a65a',
                            confirmButtonText: 'Tutup'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Terhubung',
                            text: data.message || 'Gagal menghubungi server BPJS.',
                            confirmButtonColor: '#d33'
                        });
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Testing Akses Antrol';

                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Error',
                        text: 'Terjadi kesalahan saat menguji koneksi ke server BPJS Antrol.',
                        confirmButtonColor: '#d33'
                    });
                });
            });
        }
    });
</script>
@endsection
