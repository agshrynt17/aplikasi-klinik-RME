@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-kunjungan-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .konhakes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }
    .card-detail-box {
        background: #fff;
        border: 1px solid #d2d6de;
        border-top: 3px solid #00c0ef;
        border-radius: 3px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .table-detail-info th {
        background-color: #f9f9f9;
        width: 240px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        padding: 8px 12px;
        border: 1px solid #e4e7ea;
    }
    .table-detail-info td {
        font-size: 13px;
        color: #333;
        padding: 8px 12px;
        border: 1px solid #e4e7ea;
    }
    .btn-kembali {
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        color: #444 !important;
        font-size: 12.5px;
        padding: 6px 16px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-kembali:hover {
        background-color: #e7e7e7;
    }
</style>

<div class="satusehat-kunjungan-page">
    {{-- Header & Breadcrumb --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Detail Kunjungan Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-kunjungan/index">Satu Sehat - Cek Kunjungan</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <span>Detail Kunjungan</span>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="card-detail-box shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h5 class="fw-bold mb-0 text-dark" style="font-size:15px;">
                <i class="fas fa-stethoscope text-info me-1"></i> Informasi Kunjungan Pasien
            </h5>
            @if($kunjungan->status_satusehat == 'Pasien Datang')
                <span class="badge bg-primary px-3 py-2" style="font-size:12px;">Pasien Datang</span>
            @elseif($kunjungan->status_satusehat == 'Selesai')
                <span class="badge bg-success px-3 py-2" style="font-size:12px;">Selesai Bridging</span>
            @else
                <span class="badge bg-secondary px-3 py-2" style="font-size:12px;">{{ $kunjungan->status_satusehat ?? 'Belum Kirim' }}</span>
            @endif
        </div>

        <table class="table table-bordered table-detail-info mb-3">
            <tr>
                <th>Nomor Registrasi</th>
                <td class="fw-bold text-primary">{{ $kunjungan->nomor_registrasi }}</td>
            </tr>
            <tr>
                <th>Tanggal &amp; Waktu Daftar</th>
                <td>{{ date('d-m-Y', strtotime($kunjungan->tanggal_daftar)) }} {{ $kunjungan->jam_daftar ?? '' }}</td>
            </tr>
            <tr>
                <th>Data Pasien</th>
                <td>
                    <div class="fw-bold">{{ $kunjungan->nama_pasien }} ({{ $kunjungan->no_rm }})</div>
                    <div class="text-muted small">NIK: {{ $kunjungan->nik ?? '-' }} | ID IHS: {{ $kunjungan->pasien_ihs ?? 'Belum ada' }}</div>
                </td>
            </tr>
            <tr>
                <th>Status Pelayanan</th>
                <td><span class="badge bg-info text-dark">{{ $kunjungan->status_periksa ?? 'Dalam Proses' }}</span></td>
            </tr>
            <tr>
                <th>Jenis Kunjungan</th>
                <td>{{ $kunjungan->jenis_kunjungan ?? 'Kunjungan Sakit' }}</td>
            </tr>
            <tr>
                <th>Unit &amp; Ruang Layanan</th>
                <td>{{ $kunjungan->nama_unit ?? 'Klinik Umum 1' }} | {{ $kunjungan->ruang_pemeriksaan ?? 'Ruang Rawat 1' }}</td>
            </tr>
            <tr>
                <th>Tenaga Medis</th>
                <td>
                    <div><strong>Dokter:</strong> {{ $kunjungan->nama_dokter ?? 'dr. VIERTA AJI NUR YASIN' }}</div>
                    <div><strong>Perawat:</strong> {{ $kunjungan->nama_perawat ?? 'LALA WIJAYANTI AMD.KEB' }}</div>
                </td>
            </tr>
            <tr>
                <th>Penjamin</th>
                <td>{{ $kunjungan->nama_penjamin ?? 'BPJS Kesehatan' }}</td>
            </tr>
            <tr>
                <th>Kode Encounter Satu Sehat</th>
                <td id="encounterCell">
                    @if($kunjungan->kode_ihs_satusehat)
                        <span class="badge bg-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>{{ $kunjungan->kode_ihs_satusehat }}</span>
                    @else
                        <code class="text-muted">Belum Dikirim</code>
                    @endif
                </td>
            </tr>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
            <a href="/satusehat-kunjungan/index" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" class="btn btn-sm text-white fw-bold px-3 py-2 shadow-sm" id="btnKirimSatuSehat" data-id="{{ $kunjungan->id }}" style="background-color: #00a65a;">
                <i class="fas fa-paper-plane me-1"></i> Kirim / Sinkron Encounter ke Satu Sehat
            </button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).off('click', '#btnKirimSatuSehat').on('click', '#btnKirimSatuSehat', function() {
        const btn = $(this);
        const id = btn.attr('data-id');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Mengirim ke Satu Sehat...');

        fetch('/satusehat-kunjungan/kirim', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Kirim / Sinkron Encounter ke Satu Sehat');
            if (data.success) {
                $('#encounterCell').html('<span class="badge bg-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>' + data.encounter_id + '</span>');
                alert(data.message);
            } else {
                alert(data.message || 'Gagal mengirim encounter.');
            }
        })
        .catch(() => {
            btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Kirim / Sinkron Encounter ke Satu Sehat');
            alert('Terjadi kesalahan jaringan.');
        });
    });
});
</script>
@endsection

