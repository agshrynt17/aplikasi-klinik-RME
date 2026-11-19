@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .icare-kunjungan-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
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

    .form-group-icare {
        margin-bottom: 14px;
    }
    .form-group-icare label {
        font-size: 13px;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }
    .input-icare-group {
        display: flex;
        width: 100%;
        max-width: 600px;
    }
    .input-pasien-box {
        flex: 1;
        height: 34px;
        padding: 6px 12px;
        font-size: 13px;
        border: 1px solid #d2d6de;
        border-right: none;
        border-radius: 2px 0 0 2px;
        background-color: #fff;
        color: #555;
        outline: none;
        cursor: pointer;
    }
    .btn-search-cyan {
        background-color: #00c0ef;
        border: 1px solid #00acd6;
        color: #fff !important;
        width: 40px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 2px 2px 0;
        cursor: pointer;
    }
    .btn-search-cyan:hover {
        background-color: #00acd6;
    }
    .helper-text {
        font-size: 12px;
        color: #777;
        margin-top: 4px;
    }
    .input-readonly-bpjs {
        width: 100%;
        max-width: 600px;
        height: 34px;
        padding: 6px 12px;
        font-size: 13px;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        background-color: #f5f5f5;
        color: #555;
        outline: none;
    }
    .btn-blue-ok {
        background-color: #337ab7;
        border: 1px solid #2e6da4;
        color: #fff !important;
        font-size: 12px;
        padding: 6px 16px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-blue-ok:hover {
        background-color: #286090;
    }
    .btn-batal-light {
        background-color: #fff;
        border: 1px solid #d2d6de;
        color: #333 !important;
        font-size: 12px;
        padding: 6px 16px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-batal-light:hover {
        background-color: #f4f4f4;
    }

    /* Modal Table */
    .table-pilih-pasien {
        font-size: 12.5px;
        width: 100%;
        border-collapse: collapse;
    }
    .table-pilih-pasien th {
        background: #f9f9f9;
        border-bottom: 2px solid #dee2e6;
        padding: 8px 10px;
        font-weight: 700;
    }
    .table-pilih-pasien td {
        padding: 6px 10px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    .table-pilih-pasien tr:hover {
        background: #f5f5f5;
    }
</style>

<div class="icare-kunjungan-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">I-Care : Pencarian Data Riwayat Berobat Pasien</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/icare-kunjungan/index">I-Care : Pencarian Data Riwayat Berobat Pasien</a>
        </div>
    </div>

    {{-- Form Pencarian Riwayat Pasien --}}
    <form method="GET" action="/icare-kunjungan/index" id="formCariIcare">
        <div class="form-group-icare">
            <label>Pilih Pasien</label>
            <div class="input-icare-group">
                <input type="text" id="displayPasien" class="input-pasien-box" placeholder="Klik cari untuk memilih pasien"
                       value="{{ $namaPasien }}" readonly>
                <button type="button" class="btn-search-cyan" id="btnCariPasien" title="Pilih Pasien">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="helper-text">
                Pastikan pasien yang dipilih sudah melakukan pendaftaran pada hari ini
            </div>
        </div>

        <div class="form-group-icare">
            <label>No Kartu BPJS/KIS</label>
            <input type="text" name="no_kartu" id="inputNoKartu" class="input-readonly-bpjs"
                   value="{{ $noKartu }}" placeholder="No Kartu BPJS Pasien" readonly>
            <input type="hidden" name="nama_pasien" id="hiddenNamaPasien" value="{{ $namaPasien }}">
        </div>

        <div style="display: flex; gap: 6px; margin-top: 18px;">
            <button type="submit" class="btn-blue-ok">
                <i class="fas fa-check"></i> OK! Cari
            </button>
            <a href="/icare-kunjungan/index" class="btn-batal-light">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>

    {{-- Hasil Pencarian jika ada --}}
    @if($searched)
    <div class="card mt-4 shadow-sm border-0" style="border-radius: 3px; max-width: 900px;">
        <div class="card-header bg-primary text-white py-2 px-3" style="background: #3c8dbc !important;">
            <h6 class="mb-0 small fw-bold"><i class="fas fa-history me-1"></i> Riwayat Pelayanan & Kunjungan i-Care BPJS</h6>
        </div>
        <div class="card-body p-3">
            @if($hasil && $hasil['pasien'])
                <div class="alert alert-info py-2 px-3 small mb-3">
                    <i class="fas fa-user me-1"></i> Data Pasien: <b>{{ strtoupper($hasil['pasien']->nama_pasien) }}</b> (RM: {{ $hasil['pasien']->no_rm }}) | No. KIS: <b>{{ $hasil['no_kartu'] }}</b>
                </div>

                @if(count($hasil['riwayat']) > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover mb-0" style="font-size: 12.5px;">
                        <thead style="background: #f9f9f9;">
                            <tr>
                                <th style="width: 35px; text-align: center;">No</th>
                                <th style="width: 120px;">Tanggal Kunjungan</th>
                                <th>Poliklinik Tujuan</th>
                                <th>Dokter Pelaksana</th>
                                <th>Keluhan / Diagnosa</th>
                                <th style="width: 120px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil['riwayat'] as $idx => $rw)
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($rw->tanggal_daftar)->format('d F Y') }}</td>
                                <td>{{ $rw->poliklinik->nama_poli ?? 'POLI UMUM' }}</td>
                                <td>{{ $rw->dokter->nama_pegawai ?? '-' }}</td>
                                <td>{{ $rw->keluhan ?: 'Pemeriksaan Kesehatan Rutin' }}</td>
                                <td style="text-align: center;"><span class="badge bg-success">Tervalidasi PCare</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-warning py-2 mb-0 small">
                    <i class="fas fa-info-circle me-1"></i> Belum ada data riwayat pelayanan sebelumnya untuk nomor kartu <b>{{ $hasil['no_kartu'] }}</b>.
                </div>
                @endif
            @else
                <div class="alert alert-warning py-2 mb-0 small">
                    <i class="fas fa-exclamation-triangle me-1"></i> Data pasien dengan nomor kartu <b>{{ $noKartu }}</b> tidak ditemukan pada sistem.
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- Modal Pilih Pasien Hari Ini --}}
<div class="modal fade" id="modalPilihPasien" tabindex="-1" aria-labelledby="modalPilihPasienLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <div class="modal-header py-2 px-3" style="background-color: #00c0ef; color: #fff;">
                <h6 class="modal-title fw-bold" id="modalPilihPasienLabel" style="font-size: 14px;">
                    <i class="fas fa-users me-1"></i> Pilih Pasien Pendaftaran Aktif (BPJS/KIS)
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <input type="text" id="filterModalPasien" class="form-control form-control-sm"
                           placeholder="Ketik nama pasien, No RM, atau No BPJS untuk memfilter...">
                </div>

                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table-pilih-pasien">
                        <thead>
                            <tr>
                                <th style="width: 35px; text-align: center;">#</th>
                                <th style="width: 130px;">No. Rawat</th>
                                <th style="width: 100px;">No. RM</th>
                                <th>Nama Pasien</th>
                                <th style="width: 140px;">No. Kartu BPJS</th>
                                <th style="width: 120px;">Poliklinik</th>
                                <th style="width: 70px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyPasien">
                            @forelse($pasienList as $idx => $p)
                            @php
                                $bpjsNo = $p->pasien->no_bpjs ?: ($p->no_bpjs_asuransi ?: '');
                                $namaPasienRow = strtoupper($p->pasien->nama_pasien ?? '-');
                                $noRmRow = $p->pasien->no_rm ?? '-';
                            @endphp
                            <tr class="row-pasien-item">
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td style="font-weight: 500;">{{ $p->no_rawat }}</td>
                                <td><span class="badge bg-secondary">{{ $noRmRow }}</span></td>
                                <td class="item-nama fw-bold">{{ $namaPasienRow }}</td>
                                <td class="item-bpjs text-primary fw-semibold">{{ $bpjsNo }}</td>
                                <td>{{ $p->poliklinik->nama_poli ?? '-' }}</td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-sm btn-primary py-1 px-2 btnPilihItem"
                                            data-nama="{{ $namaPasienRow }}"
                                            data-rm="{{ $noRmRow }}"
                                            data-bpjs="{{ $bpjsNo }}"
                                            style="font-size: 11px;">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Tidak ada pasien BPJS yang terdaftar pada tanggal aktif.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 px-3" style="background: #f9f9f9;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalPilihPasien');
    const modal = new bootstrap.Modal(modalEl);
    const btnCari = document.getElementById('btnCariPasien');
    const displayPasien = document.getElementById('displayPasien');
    const inputNoKartu = document.getElementById('inputNoKartu');
    const hiddenNamaPasien = document.getElementById('hiddenNamaPasien');
    const filterInput = document.getElementById('filterModalPasien');

    // Open modal on search button or input click
    btnCari.addEventListener('click', function () {
        modal.show();
    });
    displayPasien.addEventListener('click', function () {
        modal.show();
    });

    // Realtime filter inside modal
    filterInput.addEventListener('keyup', function () {
        const val = this.value.toLowerCase();
        document.querySelectorAll('.row-pasien-item').forEach(function (row) {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });

    // Select patient
    document.querySelectorAll('.btnPilihItem').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const nama = this.getAttribute('data-nama');
            const rm = this.getAttribute('data-rm');
            const bpjs = this.getAttribute('data-bpjs');

            displayPasien.value = '[' + rm + '] ' + nama;
            hiddenNamaPasien.value = '[' + rm + '] ' + nama;
            inputNoKartu.value = bpjs;

            modal.hide();
        });
    });
});
</script>
@endsection
